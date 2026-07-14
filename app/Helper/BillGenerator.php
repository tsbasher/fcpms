<?php

namespace App\Helper;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\BillPart;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\BoqSubItem;
use App\Models\BoqVersion;
use App\Models\BoqVersionDetails;
use App\Models\Contractor;
use App\Models\Package;
use App\Models\Project;
use App\Models\Scheme;
use App\Models\Upazila;

class BillGenerator
{


    public static function shelterWiseView(Bill $this_bill, array $previous_bill_ids, string $project_id, string $package_id, array $scheme_ids, string $report_type)
    {
        $summary_bill = [];
        // return view('backend.bill.test');
        // $this_bill = Bill::with('schemes')->findOrFail($bill_id);
        // $previous_bill_ids = Bill::where('id', '!=', $this_bill->id)
        //     ->where('serial', '<', $this_bill->serial)
        //     ->where('project_id', $this_bill->project_id)
        //     ->wherehas('boq_version', function ($query) use ($project_id, $package_id) {
        //         $query->where('project_id', $project_id)
        //             ->where('package_id', $package_id);
        //     })
        //     ->orderBy('serial', 'desc')
        //     ->get()->pluck('id')->toArray();

        // dd($this_bill, $previous_bill_ids);

        $boq_version = BoqVersion::findOrFail($this_bill->boq_version_id);


        // $scheme_ids = BillScheme::where(function ($query) use ($this_bill, $previous_bill_ids) {
        //     $query->where('bill_id', $this_bill->id)
        //         ->orwhereIn('bill_id', $previous_bill_ids);
        // })->get()->pluck('scheme_id')->toArray();
        $schemes = Scheme::whereIn('id', $scheme_ids)->orderBy('code')->with('package', 'district', 'upazila', 'union', 'scheme_option')->get();

        $shelter_bill = [];
        $next_bill = Bill::where('serial', '>', $this_bill->serial)
            ->where('project_id', $this_bill->project_id)
            ->wherehas('boq_version', function ($query) use ($project_id, $package_id) {
                $query->where('project_id', $project_id)
                    ->where('package_id', $package_id);
            })
            ->orderBy('serial', 'asc')
            ->first();
        // dd($next_bill);
        foreach ($schemes as $scheme) {
            $info = new \stdClass();
            $info->scheme = $scheme;
            $info->has_bill_details = false;
            $info->parts = [];
            $bill_part_ids = BillPart::where('project_id', $project_id)->where('scheme_id', $scheme->id);
            if ($next_bill)
                $bill_part_ids = $bill_part_ids->whereHas('bill', function ($query) use ($next_bill) {
                    $query->where('serial', '<', $next_bill->serial);
                });
            $bill_part_ids = $bill_part_ids->distinct('boq_part_id')->get()->pluck('boq_part_id')->toArray();
            $parts = BoqPart::whereIn('id', $bill_part_ids)->orderby('code')->get();
            // dd($parts);
            foreach ($parts as $part) {
                $part_data = new \stdClass();
                $part_data->part = $part;
                $part_data->items = [];

                if (!isset($summary_bill[$part->id])) {

                    $summary_bill[$part->id] = [
                        'part' => $part,
                        'items' => []
                    ];
                }

                $item_ids = BoqVersionDetails::where('boq_version_id', $boq_version->id)
                    ->where('boq_part_id', $part->id)
                    ->distinct('boq_item_id')
                    ->get()->pluck('boq_item_id')->toArray();
                // $items = BoqItem::whereIn('id', $item_ids)->orderbyraw("regexp_replace(code, '[0-9]+', '', 'g') ASC,
                $items = BoqItem::whereIn('id', $item_ids)->with('unit')->orderbyraw("regexp_replace(code, '[0-9]+', '', 'g') ASC,
        COALESCE(NULLIF(regexp_replace(code, '[^0-9]', '', 'g'), ''),'0')::int ASC")->get();


                foreach ($items as $item) {
                    $item_info = new \stdClass();

                    $item_info->item = $item;


                    if ($item->has_sub_items) {
                        $item_info->sub_items = [];
                        $sub_item_ids = BoqVersionDetails::where('boq_version_id', $boq_version->id)
                            ->where('boq_part_id', $part->id)
                            ->where('boq_item_id', $item->id)
                            ->distinct('boq_sub_item_id')
                            ->get()->pluck('boq_sub_item_id')->toArray();
                        // dd($sub_item_ids);
                        $sub_items = BoqSubItem::whereIn('id', $sub_item_ids)->with('unit')->orderbyraw("regexp_replace(code, '[0-9]+', '', 'g') ASC, COALESCE(NULLIF(regexp_replace(code, '[^0-9]', '', 'g'), ''),'0')::int ASC")->get();
                        // dd($sub_items);
                        foreach ($sub_items as $sub_item) {

                            if (!isset($summary_bill[$part->id]['items'][$item->id])) {

                                $summary_bill[$part->id]['items'][$item->id] = [
                                    'item' => $item,
                                    'sub_items' => []
                                ];
                            }



                            if (!isset($summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id])) {

                                $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id] = [
                                    'sub_item' => $sub_item,
                                    'boq_quantity' => 0,
                                    'rate' => 0,
                                    'total_quantity' => 0,
                                    'previous_quantity' => 0,
                                    'this_quantity' => 0,
                                    'this_amount' => 0,
                                ];
                            }
                            $sub_item_info = new \stdClass();
                            $sub_item_info->sub_item = $sub_item;

                            $this_bill_detail = BillDetail::where('bill_id', $this_bill->id)
                                ->where('scheme_id', $scheme->id)
                                ->where('boq_part_id', $part->id)
                                ->where('boq_item_id', $item->id)
                                ->where('boq_subitem_id', $sub_item->id)
                                ->with('measurements')
                                ->first();
                            $sub_item_info->this_bill_detail = $this_bill_detail;

                            if (!$this_bill_detail) {
                                $sub_item_info->this_bill_detail = null;
                                $old_bill_details = BillDetail::whereIn('bill_id', $previous_bill_ids)
                                    ->where('scheme_id', $scheme->id)
                                    ->where('boq_part_id', $part->id)
                                    ->where('boq_item_id', $item->id)
                                    ->where('boq_subitem_id', $sub_item->id)
                                    ->orderby('created_at', 'desc')
                                    ->with('measurements')
                                    ->first();
                                $sub_item_info->old_bill_detail = $old_bill_details;
                                if ($old_bill_details)
                                    $info->has_bill_details = true;
                            } else {

                                $info->has_bill_details = true;
                                $sub_item_info->old_bill_detail = null;
                            }

                            $boq_details = BoqVersionDetails::where('boq_version_id', $boq_version->id)
                                ->where('boq_part_id', $part->id)
                                ->where('boq_item_id', $item->id)
                                ->where('boq_sub_item_id', $sub_item->id);
                            if ($part->has_option_variation)
                                $boq_details->where('scheme_option_id', $scheme->scheme_option_id);
                            $boq_details = $boq_details->first();

                            $sub_item_info->boq_version_details = $boq_details;

                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['boq_quantity'] += $boq_details ? $boq_details->quantity : 0;
                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['rate'] = $boq_details ? $boq_details->rate : 0;
                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['total_quantity'] += $this_bill_detail ? ($this_bill_detail->quantity - $this_bill_detail->held_up_quantity) : ($old_bill_details ? ($old_bill_details->quantity - $old_bill_details->held_up_quantity) : 0);
                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['previous_quantity'] += $this_bill_detail ? $this_bill_detail->previous_quantity : ($old_bill_details ? ($old_bill_details->quantity - $old_bill_details->held_up_quantity) : 0);
                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['this_quantity'] += $this_bill_detail ? $this_bill_detail->this_bill_quantity : 0;
                            $summary_bill[$part->id]['items'][$item->id]['sub_items'][$sub_item->id]['this_amount'] += $this_bill_detail ? ($this_bill_detail->this_bill_amount) : 0;




                            // dd($sub_item_info);

                            array_push($item_info->sub_items, $sub_item_info);
                        }
                        array_push($part_data->items, $item_info);
                    } else {
                        if (!isset($summary_bill[$part->id]['items'][$item->id])) {

                            $summary_bill[$part->id]['items'][$item->id] = [
                                'item' => $item,
                                'boq_quantity' => 0,
                                'rate' => 0,
                                'total_quantity' => 0,
                                'previous_quantity' => 0,
                                'this_quantity' => 0,
                                'this_amount' => 0,
                            ];
                        }

                        $this_bill_detail = BillDetail::where('bill_id', $this_bill->id)
                            ->where('scheme_id', $scheme->id)
                            ->where('boq_part_id', $part->id)
                            ->where('boq_item_id', $item->id)
                            ->with('measurements')
                            ->first();
                        $item_info->this_bill_detail = $this_bill_detail;

                        if (!$this_bill_detail) {
                            $item_info->this_bill_detail = null;
                            $old_bill_details = BillDetail::whereIn('bill_id', $previous_bill_ids)
                                ->where('scheme_id', $scheme->id)
                                ->where('boq_part_id', $part->id)
                                ->where('boq_item_id', $item->id)
                                ->orderby('created_at', 'desc')
                                ->with('measurements')
                                ->first();
                            $item_info->old_bill_detail = $old_bill_details;
                            if ($old_bill_details)
                                $info->has_bill_details = true;
                        } else {
                            $item_info->old_bill_detail = null;
                            $info->has_bill_details = true;
                        }

                        $boq_details = BoqVersionDetails::where('boq_version_id', $boq_version->id)
                            ->where('boq_part_id', $part->id)
                            ->where('boq_item_id', $item->id);
                        if ($part->has_option_variation)
                            $boq_details->where('scheme_option_id', $scheme->scheme_option_id);
                        $boq_details = $boq_details->first();

                        $item_info->boq_version_details = $boq_details;

                        $summary_bill[$part->id]['items'][$item->id]['boq_quantity'] += $boq_details ? $boq_details->quantity : 0;
                        $summary_bill[$part->id]['items'][$item->id]['rate'] = $boq_details ? $boq_details->rate : 0;
                        $summary_bill[$part->id]['items'][$item->id]['total_quantity'] += $this_bill_detail ? ($this_bill_detail->quantity - $this_bill_detail->held_up_quantity) : ($old_bill_details ? ($old_bill_details->quantity - $old_bill_details->held_up_quantity) : 0);
                        $summary_bill[$part->id]['items'][$item->id]['previous_quantity'] += $this_bill_detail ? $this_bill_detail->previous_quantity : ($old_bill_details ? ($old_bill_details->quantity - $old_bill_details->held_up_quantity) : 0);
                        $summary_bill[$part->id]['items'][$item->id]['this_quantity'] += $this_bill_detail ? $this_bill_detail->this_bill_quantity : 0;
                        $summary_bill[$part->id]['items'][$item->id]['this_amount'] += $this_bill_detail ? ($this_bill_detail->this_bill_amount) : 0;

                        array_push($part_data->items, $item_info);
                    }
                }
                array_push($info->parts, $part_data);
            }
            array_push($shelter_bill, $info);
        }
        // dd($summary_bill);
        // return response()->json($summary_bill);
        $contractor = Contractor::findOrFail($this_bill->contractor_id);
        $last_bill = Bill::where('id', '!=', $this_bill->id)->where('created_at', '<', $this_bill->created_at)->where('project_id', $this_bill->project_id)->orderBy('id', 'desc')->first();
        $project = Project::findOrFail($this_bill->project_id);
        $package = Package::findOrFail($this_bill->boq_version->package_id);
        $upazila = Upazila::findOrFail($schemes->first()->upazila_id);
        if ($report_type == "PKG_SUM") {
            return view('backend.bill.summary_bill', compact('project', 'this_bill', 'last_bill', 'package', 'summary_bill', 'upazila', 'contractor'));
        } else {
            return view('backend.bill.shelter_bill', compact('shelter_bill', 'project', 'this_bill', 'last_bill', 'package', 'summary_bill', 'upazila', 'contractor'));
        }
        // return view('backend.bill.shelter_bill', compact('shelter_bill', 'project', 'this_bill', 'last_bill', 'package', 'summary_bill', 'upazila', 'contractor'));
    }
}
