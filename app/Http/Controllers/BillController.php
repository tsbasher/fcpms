<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\BillItem;
use App\Models\BillPart;
use App\Models\BillScheme;
use App\Models\BillSubItem;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\BoqSubItem;
use App\Models\BoqVersion;
use App\Models\BoqVersionDetails;
use App\Models\Measurement;
use App\Models\Project;
use App\Models\Scheme;
use App\Models\Unit;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bills = Bill::query();

        if ($request->filled('search_text')) {
            $bills->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_text . '%')
                    ->orWhere('reference_code', 'like', '%' . $request->search_text . '%');
            });
        }

        $bills = $bills->paginate();

        return view('backend.user.bill.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boq_versions = BoqVersion::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.create', compact(['boq_versions', 'schemes']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator($request->all(), [
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'reference_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'boq_version_id' => 'required|exists:boq_versions,id',
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        // dd(Auth::guard('web')->user());
        DB::transaction(function () use ($request) {
            $bill = new Bill();
            $bill->bill_no = $request->bill_no;
            $bill->bill_date = $request->bill_date;
            $bill->reference_code = $request->reference_code;
            $bill->name = $request->name;
            $bill->remarks = $request->remarks;
            $bill->boq_version_id = $request->boq_version_id;
            $bill->contractor_id = Auth::guard('web')->user()->contractor_id;
            $bill->project_id = Auth::guard('web')->user()->project_id;
            $bill->package_id = Auth::guard('web')->user()->package_id;
            $bill->created_by = Auth::guard('web')->user()->id;
            $bill->save();
            foreach ($request->schemes as $scheme_id) {
                BillScheme::create([
                    'bill_id' => $bill->id,
                    'scheme_id' => $scheme_id,
                    'project_id' => Auth::guard('web')->user()->project_id,
                ]);
            }
        });
        return redirect()->route('user.bills.index')->with('success', 'Bill created successfully');
    }

    /**
     * Display the specified resource.
     */


    public function show($id, Request $request)
    {
        return redirect()->route('user.bills.details.scheme', [$id]);

        $menu = $request->has('menu') && isset($request->menu) ? $request->menu : "";
        if ($menu == "") {
            $bill = Bill::with('schemes')->findOrFail($id);
            $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
                ->where('package_id', Auth::guard('web')->user()->package_id)->get();
            return view('backend.user.bill.show', compact('bill', 'schemes'));
        }
        if ($menu == "#scheme") {
            $bill = Bill::with('schemes')->findOrFail($id);
            $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
                ->where('package_id', Auth::guard('web')->user()->package_id)->get();
            return view('backend.user.bill.partials.scheme', compact('bill', 'schemes'));
        }
        $bill = Bill::with('contractor', 'project', 'boq_version', 'schemes');
        if ($request->filled('part_scheme')) {
            $bill->with('bill_parts', function ($q) use ($request) {
                $q->where('scheme_id', $request->part_scheme);
            });
        } else {
            $bill = $bill->with('bill_parts');
        }
        if ($request->filled('item_scheme')) {
            $bill->with('bill_parts', function ($q) use ($request) {
                $q->where('scheme_id', $request->item_scheme);
            });

            $bill->with('bill_items', function ($q) use ($request) {
                $q->where('scheme_id', $request->item_scheme);
            });
        }
        if ($request->filled('item_part')) {


            $bill->with('bill_items', function ($q) use ($request) {
                $q->where('boq_part_id', $request->item_part);
            });
        }
        $bill = $bill->with('bill_items');
        $bill = $bill->findOrFail($id);
        // dd($bill);
        // $bill_parts = $bill->bill_parts()->where('scheme_id', $request->part_scheme)->with('scheme', 'boq_part')->get();
        // dd($bill->bill_pxxxxxxxxxxxxxxxxxxxxxxxxxarts);
        return view('backend.user.bill.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        $boq_versions = BoqVersion::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.edit', compact(['boq_versions', 'schemes', 'bill']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $v = Validator($request->all(), [
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'reference_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'boq_version_id' => 'required|exists:boq_versions,id',
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        DB::transaction(function () use ($request, $bill) {
            $bill->bill_no = $request->bill_no;
            $bill->bill_date = $request->bill_date;
            $bill->reference_code = $request->reference_code;
            $bill->name = $request->name;
            $bill->remarks = $request->remarks;
            $bill->boq_version_id = $request->boq_version_id;
            $bill->contractor_id = Auth::guard('web')->user()->contractor_id;
            $bill->project_id = Auth::guard('web')->user()->project_id;
            $bill->package_id = Auth::guard('web')->user()->package_id;
            $bill->updated_by = Auth::guard('web')->user()->id;
            $bill->save();
            $bill->schemes()->sync($request->schemes);
        });
        return redirect()->route('user.bills.index')->with('success', 'Bill updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        try {
            $bill->schemes()->detach();
            $bill->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Bill deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill.' . $e->getMessage();
            return response()->json($data);
        }
    }




    public function show_scheme($id, Request $request)
    {
        $bill = Bill::with('schemes')->findOrFail($id);
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.show_scheme', compact('bill', 'schemes'));
    }


    public function storeScheme(Request $request, $bill_id)
    {
        $v = Validator($request->all(), [
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $bill = Bill::findOrFail($bill_id);
        BillScheme::where('bill_id', $bill->id)->delete();
        foreach ($request->schemes as $scheme_id) {
            BillScheme::create([
                'bill_id' => $bill->id,
                'scheme_id' => $scheme_id,
                'project_id' => Auth::guard('web')->user()->project_id,
            ]);
        }
        return redirect()->route('user.bills.show', $bill_id)->with('success', 'Schemes added to bill successfully.');
    }

    public function removeScheme($scheme_id, $bill_id)
    {
        try {
            $bill = Bill::findOrFail($bill_id);
            $bill->schemes()->detach($scheme_id);
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Scheme removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill scheme: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill scheme.' . $e->getMessage();
            return response()->json($data);
        }
    }


    public function show_boq_part($id, Request $request)
    {
        $bill = Bill::with('schemes'); //->findOrFail($id);
        if ($request->has('schemes') && isset($request->schemes))
            $bill->with('bill_parts', function ($q) use ($request) {
                $q->where('scheme_id', $request->schemes);
            });
        else
            $bill->with('bill_parts');

        $bill = $bill->findOrFail($id);
        $boq_version_details = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
            ->where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)
            ->distinct('boq_part_id')
            ->get()->pluck('boq_part_id')->toArray();
        // dd($boq_version_details);
        $boq_parts = BoqPart::whereIn('id', $boq_version_details)->get();

        $schemes = $bill->schemes;
        // dd($bill);
        return view('backend.user.bill.show_boq_part', compact('bill', 'schemes', 'boq_parts'));
    }
    public function storeBoqPart(Request $request, $bill_id)
    {
        $v = Validator($request->all(), [
            'schemes' => 'required|exists:schemes,id',
            'boq_parts' => 'required|array',
            'boq_parts.*' => 'exists:boq_parts,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $bill = Bill::findOrFail($bill_id);
        DB::transaction(function () use ($request, $bill) {
            // Add new bill parts
            BillPart::where('bill_id', $bill->id)->where('scheme_id', $request->schemes)->delete();
            $bill_parts = [];
            foreach ($request->boq_parts as $boq_part_id) {

                $bill_parts = [
                    'bill_id' => $bill->id,
                    'scheme_id' => $request->schemes,
                    'project_id' => Auth::guard('web')->user()->project_id,
                    'boq_part_id' => $boq_part_id,
                ];
                // dd($bill_parts);
                BillPart::create($bill_parts);
            }
        });
        // dd($request);
        return redirect()->back()->with('success', 'BOQ Parts added to bill successfully.');
    }
    public function removeBoqPart($id, $bill_id)
    {
        try {
            $bill = BillPart::findOrFail($id);
            $bill->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Part removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill Boq Part: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill Boq Part.' . $e->getMessage();
            return response()->json($data);
        }
    }



    public function show_boq_item($id, Request $request)
    {
        // dd($request->all());
        $bill = Bill::with('schemes'); //->findOrFail($id);
        $bill_parts = BillPart::with('boq_part');

        $has_scheme = false;
        if ($request->has('schemes') && isset($request->schemes)) {
            // dd($request->all());
            $bill_parts->where('scheme_id', $request->schemes);
        }


        $bill->with('bill_items', function ($q) use ($request) {
            if ($request->has('schemes') && isset($request->schemes)) {
                $q->where('scheme_id', $request->schemes);
            }

            if ($request->has('boq_part_id') && isset($request->boq_part_id)) {
                $q->where('boq_part_id', $request->boq_part_id);
            }
        });


        $bill = $bill->findOrFail($id);

        $bill_parts = $bill_parts->groupby('boq_part_id')->select('boq_part_id')->get();
        $boq_items = BoqItem::where('boq_part_id', $request->boq_part_id)->get();

        $schemes = Bill::with('schemes')->findOrFail($id)->schemes;
        // dd($bill);
        return view('backend.user.bill.show_boq_item', compact('bill', 'schemes', 'bill_parts', 'boq_items'));
    }


    public function storeBoqItem(Request $request, $bill_id)
    {
        $v = Validator($request->all(), [
            'schemes' => 'required|exists:schemes,id',
            'boq_part_id' => 'required',
            'boq_part_id.*' => 'exists:boq_parts,id',
            'boq_items' => 'required|array',
            'boq_items.*' => 'exists:boq_items,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $bill = Bill::findOrFail($bill_id);
        DB::transaction(function () use ($request, $bill) {
            // Add new bill parts
            BillItem::where('bill_id', $bill->id)->where('scheme_id', $request->schemes)->where('boq_part_id', $request->boq_part_id)->delete();
            $bill_parts = [];
            foreach ($request->boq_items as $boq_item_id) {

                $bill_items = [
                    'bill_id' => $bill->id,
                    'scheme_id' => $request->schemes,
                    'boq_part_id' => $request->boq_part_id,
                    'boq_item_id' => $boq_item_id,
                ];

                // dd($bill_parts);
                BillItem::create($bill_items);
            }
        });
        // dd($request);
        return redirect()->back()->with('success', 'BOQ Items added to bill successfully.');
    }
    public function removeBoqItem($id, $bill_id)
    {
        try {
            $bill = BillItem::findOrFail($id);
            $bill->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Item removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill Boq Item: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill Boq Item.' . $e->getMessage();
            return response()->json($data);
        }
    }


    public function show_boq_subitem($id, Request $request)
    {
        $bill = Bill::with('schemes'); //->findOrFail($id);
        $bill_parts = BillPart::with('boq_part');
        $bill_items = BillItem::with('boq_item');


        if ($request->has('schemes') && isset($request->schemes)) {
            $bill_parts->where('scheme_id', $request->schemes);
            $bill_items->where('scheme_id', $request->schemes);
        }

        if ($request->has('boq_part_id') && isset($request->boq_part_id)) {
            $bill_items->where('boq_part_id', $request->boq_part_id);
        }



        $bill->with('bill_subitems', function ($q) use ($request) {
            if ($request->has('schemes') && isset($request->schemes)) {
                $q->where('scheme_id', $request->schemes);
            }

            if ($request->has('boq_part_id') && isset($request->boq_part_id)) {
                $q->where('boq_part_id', $request->boq_part_id);
            }

            if ($request->has('boq_item_id') && isset($request->boq_item_id)) {
                $q->where('boq_item_id', $request->boq_item_id);
            }
        });

        $bill = $bill->findOrFail($id);
        // dd($bill->);
        $bill_parts = $bill_parts->wherehas('boq_part', function ($query) use ($request) {
            $query->whereHas('boq_item', function ($q) use ($request) {
                $q->whereHas('boq_sub');
            });
        })->groupby('boq_part_id')->select('boq_part_id')->get();

        $bill_items = $bill_items->wherehas('boq_item', function ($query) use ($request) {
            $query->whereHas('boq_sub');
        })->groupby('boq_item_id')->select('boq_item_id')->get();
        $schemes = Bill::with('schemes')->findOrFail($id)->schemes;
        $boq_subitems = BoqSubItem::where('boq_item_id', $request->boq_item_id)->get();
        // dd($bill);
        return view('backend.user.bill.show_boq_sub_item', compact('bill', 'schemes', 'bill_parts', 'bill_items', 'boq_subitems'));
    }
    public function storeBoqSubItem(Request $request, $bill_id)
    {
        $v = Validator($request->all(), [
            'schemes' => 'required|exists:schemes,id',
            'boq_part_id' => 'required',
            'boq_part_id.*' => 'exists:boq_parts,id',
            'boq_item_id' => 'required',
            'boq_item_id' => 'exists:boq_items,id',
            'boq_subitems' => 'required|array',
            'boq_subitems.*' => 'exists:boq_sub_items,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $bill = Bill::findOrFail($bill_id);
        DB::transaction(function () use ($request, $bill) {
            // Add new bill parts
            BillSubItem::where('bill_id', $bill->id)->where('scheme_id', $request->schemes)->where('boq_part_id', $request->boq_part_id)
                ->where('boq_item_id', $request->boq_item_id)->delete();
            $bill_parts = [];
            foreach ($request->boq_subitems as $boq_subitem_id) {

                $bill_subitems = [
                    'bill_id' => $bill->id,
                    'scheme_id' => $request->schemes,
                    'boq_part_id' => $request->boq_part_id,
                    'boq_item_id' => $request->boq_item_id,
                    'boq_subitem_id' => $boq_subitem_id,
                ];

                // dd($bill_parts);
                BillSubItem::create($bill_subitems);
            }
        });
        // dd($request);
        return redirect()->back()->with('success', 'BOQ Sub Items added to bill successfully.');
    }
    public function removeBoqSubItem($id, $bill_id)
    {
        try {
            $bill = BillSubItem::findOrFail($id);
            $bill->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Sub Item removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill Boq Item: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill Boq Item.' . $e->getMessage();
            return response()->json($data);
        }
    }


    public function show_measurement($id, Request $request)
    {
        $schemes = Bill::with('schemes')->findOrFail($id)->schemes;
        $bill = Bill::find($id);
        $bill_parts = BillPart::with('boq_part');
        // $bill_items=BoqItem::


        // if ($request->has('schemes') && isset($request->schemes)) {
        $bill_parts->where('scheme_id', $request->schemes);
        // }

        $bill_parts = $bill_parts->groupby('boq_part_id')->select('boq_part_id')->get();

        $boq_version_details_item = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
            ->where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)
            ->where('boq_part_id', $request->boq_part_id)
            ->distinct('boq_item_id')
            ->get()->pluck('boq_item_id')->toArray();

        $boq_items = BoqItem::wherein('id', $boq_version_details_item)->get();

        $boq_version_details_subitem = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
            ->where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)
            ->where('boq_part_id', $request->boq_part_id)
            ->where('boq_item_id', $request->boq_item_id)
            ->distinct('boq_sub_item_id')
            ->get()->pluck('boq_sub_item_id')->toArray();
        $boq_subitems = BoqSubItem::wherein('id', $boq_version_details_subitem)->get();
        $measurement_view = '';
        if ($request->has('boq_item_id') && isset($request->boq_item_id)) {
            $item = BoqItem::find($request->boq_item_id);

            if ($item->has_sub_items == 0) {
                $unit = Unit::find($item->unit_id);
                $fields = json_decode($unit->fields);
                $measurement_view = view('backend.user.bill.measurement.measurement', compact('unit', 'fields'));
            }
        }
        $measurements = Measurement::with('unit')->where('bill_id', $id)->where('scheme_id', $request->schemes)
            ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
        $scheme = Scheme::find($request->schemes);
        $boq_version_item = null;
        if ($scheme) {
            $boq_version_item = BoqVersionDetails::with('unit')->where('boq_version_id', $bill->boq_version_id)
                ->where('project_id', Auth::guard('web')->user()->project_id)
                ->where('package_id', Auth::guard('web')->user()->package_id)
                ->where('boq_part_id', $request->boq_part_id)
                ->where('boq_item_id', $request->boq_item_id)
                ->where('scheme_option_id', $scheme->scheme_option_id);
        }
        if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
            if ($boq_version_item)
                $boq_version_item->where('boq_sub_item_id', $request->boq_subitem_id);
            $measurements->where('boq_subitem_id', $request->boq_subitem_id);
            $item = BoqSubItem::find($request->boq_subitem_id);

            $unit = Unit::find($item->unit_id);
            $fields = json_decode($unit->fields);
            $measurement_view = view('backend.user.bill.measurement.measurement', compact('unit', 'fields'));
        }

        $measurements = $measurements->get();
        $boq_version_item = $boq_version_item ? $boq_version_item->first() : null;


        $old_bill = Bill::where('contractor_id', Auth::guard('web')->user()->contractor_id)
            ->where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)
            ->where('id', '!=', $id)
            ->where('created_at', '<', $bill->created_at)
            ->get()->pluck('id')->toArray();

        $old_bill_details = BillDetail::with('measurements')->whereIn('bill_id', $old_bill)
            ->where('scheme_id', $request->schemes)
            ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
        if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
            $old_bill_details->where('boq_subitem_id', $request->boq_subitem_id);
        }
        $old_bill_details = $old_bill_details->get();


        $this_bill_details = BillDetail::with('measurements')->where('bill_id', $id)
            ->where('scheme_id', $request->schemes)
            ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
        if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
            $this_bill_details->where('boq_subitem_id', $request->boq_subitem_id);
        }
        $this_bill_details = $this_bill_details->first();

        // dd($old_bill_details->sum('this_bill_quantity'));

        return view('backend.user.bill.show_measurement', compact('bill', 'schemes', 'bill_parts', 'boq_items', 'boq_subitems', 'measurement_view', 'measurements', 'boq_version_item', 'old_bill_details', 'this_bill_details'));
    }
    public function storeMeasurement($id, Request $request)
    {
        // dd($request->all());
        // Implementation for storing measurement
        $v = Validator($request->all(), [
            'schemes' => 'required|exists:schemes,id',
            'boq_part_id' => 'required',
            'boq_item_id' => 'required',
            // 'boq_subitem_id' => 'required'
            'measurement_item' => 'required|string|max:255',
            'nos' => 'required|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
        ]);

        if ($v->fails()) {
            // $response = new stdClass();
            // $response->status = 0;
            // $response->message = $v->errors();

            // return $response;
            return redirect()->back()->withErrors($v)->withInput();
        }
        DB::transaction(function () use ($request, $id) {
            $bill = Bill::findOrFail($id);
            $scheme = Scheme::findOrFail($request->schemes);
            // dd($scheme);
            $bill_detail = BillDetail::where('bill_id', $id)->where('scheme_id', $request->schemes)
                ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
            if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
                $bill_detail->where('boq_subitem_id', $request->boq_subitem_id);
            }
            $bill_detail = $bill_detail->first();
            $boq_version_details = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
                ->where('package_id', Auth::guard('web')->user()->package_id)
                ->where('boq_part_id', $request->boq_part_id)
                ->where('boq_item_id', $request->boq_item_id)
                ->where('scheme_option_id', $scheme->scheme_option_id);
            if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
                $boq_version_details->where('boq_sub_item_id', $request->boq_subitem_id);
            }
            $boq_version_details = $boq_version_details->first();
            // dd(Auth::guard('web')->user()->project_id);
            if ($bill_detail) {
                $bill_detail_id = $bill_detail->id;
                $measurement = Measurement::create([
                    'project_id' => Auth::guard('web')->user()->project_id,
                    'bill_id' => $id,
                    'bill_detail_id' => $bill_detail->id,
                    'scheme_id' => $request->schemes,
                    'boq_part_id' => $request->boq_part_id,
                    'boq_item_id' => $request->boq_item_id,
                    'boq_subitem_id' => $request->boq_subitem_id,
                    'unit_id' => $request->unit_id,
                    'description' => $request->measurement_item,
                    'nos' => $request->nos,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'quantity' => ($request->nos) * ($request->length ?? 1) * ($request->width ?? 1) * ($request->height ?? 1) * ($request->weight ?? 1),
                ]);
                // $bill_details=BillDetail::

            } else {
                $boq_version_details = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
                    ->where('package_id', Auth::guard('web')->user()->package_id)
                    ->where('boq_part_id', $request->boq_part_id)
                    ->where('boq_item_id', $request->boq_item_id)
                    ->where('scheme_option_id', $scheme->scheme_option_id);
                if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
                    $boq_version_details->where('boq_sub_item_id', $request->boq_subitem_id);
                }
                $boq_version_details = $boq_version_details->first();

                $bill_details = BillDetail::create([
                    'bill_id' => $id,
                    'scheme_id' => $request->schemes,
                    'boq_part_id' => $request->boq_part_id,
                    'boq_item_id' => $request->boq_item_id,
                    'boq_subitem_id' => $request->boq_subitem_id,
                    'scheme_option_id' => $scheme->scheme_option_id,
                    'project_id' => Auth::guard('web')->user()->project_id,
                    'quantity' => 0,
                    'boq_quantity' => $boq_version_details->quantity,
                    'rate' => $boq_version_details->rate,
                    'amount'   => 0,

                ]);

                $bill_detail_id = $bill_details->id;
                $measurement = Measurement::create([
                    'project_id' => Auth::guard('web')->user()->project_id,
                    'bill_id' => $id,
                    'bill_detail_id' => $bill_details->id,
                    'scheme_id' => $request->schemes,
                    'boq_part_id' => $request->boq_part_id,
                    'boq_item_id' => $request->boq_item_id,
                    'boq_subitem_id' => $request->boq_subitem_id,
                    'unit_id' => $request->unit_id,
                    'description' => $request->measurement_item,
                    'nos' => $request->nos,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'quantity' => ($request->nos) * ($request->length ?? 1) * ($request->width ?? 1) * ($request->height ?? 1) * ($request->weight ?? 1),
                ]);
            }

            // $bill->contractor_id = Auth::guard('web')->user()->contractor_id;
            // $bill->project_id = Auth::guard('web')->user()->project_id;
            // $bill->package_id = Auth::guard('web')->user()->package_id;

            $old_bill = Bill::where('contractor_id', Auth::guard('web')->user()->contractor_id)
                ->where('project_id', Auth::guard('web')->user()->project_id)
                ->where('package_id', Auth::guard('web')->user()->package_id)
                ->where('id', '!=', $id)
                ->where('created_at', '<', $bill->created_at)
                ->get()->pluck('id')->toArray();

            $old_bill_details = BillDetail::with('measurements')->whereIn('bill_id', $old_bill)
                ->where('scheme_id', $request->schemes)
                ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
            if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
                $old_bill_details->where('boq_subitem_id', $request->boq_subitem_id);
            }
            $old_bill_details = $old_bill_details->get();


            $this_bill_details = BillDetail::with('measurements')->where('bill_id', $id)
                ->where('scheme_id', $request->schemes)
                ->where('boq_part_id', $request->boq_part_id)->where('boq_item_id', $request->boq_item_id);
            if ($request->has('boq_subitem_id') && isset($request->boq_subitem_id)) {
                $this_bill_details->where('boq_subitem_id', $request->boq_subitem_id);
            }
            $this_bill_details = $this_bill_details->first();



            $bill_details = BillDetail::with('measurements')->find($bill_detail_id);

            $bill_details->quantity = $bill_details->measurements->sum('quantity');
            $bill_details->previous_quantity = $old_bill_details->sum('this_bill_quantity');
            if ($bill_details->boq_quantity < $bill_details->measurements->sum('quantity')) {
                $bill_details->held_up_quantity = $bill_details->measurements->sum('quantity')-$bill_details->boq_quantity;
                $bill_details->this_bill_quantity = $bill_details->measurements->sum('quantity') - $old_bill_details->sum('this_bill_quantity') - $bill_details->held_up_quantity;
            } else
                $bill_details->this_bill_quantity = $bill_details->measurements->sum('quantity') - $old_bill_details->sum('this_bill_quantity');
            $bill_details->amount = $bill_details->quantity * $bill_details->rate;
            $bill_details->this_bill_amount = $bill_details->this_bill_quantity * $bill_details->rate;
            $bill_details->save();
        });
        return redirect()->back()->with('success', 'Measurement added successfully.')->withInput();
    }

    public function removeMeasurement($id, $bill_id)
    {
        try {
            DB::transaction(function () use ($bill_id, $id) {

                $measurement = Measurement::findOrFail($id);
                $bill_detail_id = $measurement->bill_detail_id;
                $measurement->delete();

                $bill_details = BillDetail::with('measurements')->find($bill_detail_id);



                $old_bill = Bill::where('contractor_id', Auth::guard('web')->user()->contractor_id)
                    ->where('project_id', Auth::guard('web')->user()->project_id)
                    ->where('package_id', Auth::guard('web')->user()->package_id)
                    ->where('id', '!=', $bill_id)
                    ->get()->pluck('id')->toArray();

                $old_bill_details = BillDetail::with('measurements')->whereIn('bill_id', $old_bill)
                    ->where('scheme_id', $bill_details->scheme_id)
                    ->where('boq_part_id', $bill_details->boq_part_id)->where('boq_item_id', $bill_details->boq_item_id);
                if (isset($bill_details->boq_subitem_id)) {
                    $old_bill_details->where('boq_subitem_id', $bill_details->boq_subitem_id);
                }
                $old_bill_details = $old_bill_details->get();


                $this_bill_details = BillDetail::with('measurements')->where('bill_id', $bill_id)
                    ->where('scheme_id', $bill_details->scheme_id)
                    ->where('boq_part_id', $bill_details->boq_part_id)->where('boq_item_id', $bill_details->boq_item_id);
                if (isset($bill_details->boq_subitem_id)) {
                    $this_bill_details->where('boq_subitem_id', $bill_details->boq_subitem_id);
                }
                $this_bill_details = $this_bill_details->first();



                // $bill_details = BillDetail::with('measurements')->find($bill_detail_id);
                $bill_details->quantity = $bill_details->measurements->sum('quantity');
                $bill_details->previous_quantity = $old_bill_details->sum('this_bill_quantity');
                if ($bill_details->boq_quantity < $bill_details->measurements->sum('quantity')) {
                    $bill_details->held_up_quantity = $bill_details->measurements->sum('quantity') - $bill_details->boq_quantity;
                } else {
                    $bill_details->held_up_quantity = 0;
                }

                $bill_details->this_bill_quantity = $bill_details->measurements->sum('quantity') - $old_bill_details->sum('this_bill_quantity') - $bill_details->held_up_quantity;
                if ($bill_details->this_bill_quantity < 0) {
                    $bill_details->this_bill_quantity = 0;
                }
                $bill_details->amount = $bill_details->this_bill_quantity * $bill_details->rate;
                // dd($bill_details);
                $bill_details->save();
            });
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Measurement removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill Boq Item: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill Measurement.' . $e->getMessage();
            return response()->json($data);
        }
    }

    public function shelterWiseView($id)
    {

        $bill = Bill::with('schemes')->findOrFail($id);
        $last_bill = Bill::where('id', '!=', $bill->id)->where('project_id', $bill->project_id)->orderBy('id', 'desc')->first();
        $project = Project::findOrFail($bill->project_id);
        // dd($bill);
        $schemes = $bill->schemes->pluck('id')->toArray();
        // dd($schemes);
        $shelter_bill = [];

        foreach ($schemes as $scheme_id) {
            $shelter = Scheme::where('id', $scheme_id)->with('scheme_option', 'package')->first();
            $info = new stdClass();
            $info->scheme = $shelter;
            $info->parts = [];

            $bill_parts = BillPart::where('project_id', $bill->project_id)->where('scheme_id', $scheme_id)->distinct('boq_part_id')->get()->pluck('boq_part_id')->toArray();
            foreach ($bill_parts as $part_id) {
                $part = BoqPart::where('id', $part_id)->first();
                $part_data = new stdClass();
                $part_data->part = $part;
                $part_data->items = [];

                $items = BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
                    ->where('boq_part_id', $part_id)->with('boq_item')
                    ->get();

                $items = $items->sortBy(function ($item) {
                    preg_match('/([A-Za-z]+)(\d+)/', $item->boq_item->code, $matches);

                    return [
                        $matches[1] ?? '',
                        (int)($matches[2] ?? 0)
                    ];
                });
                // dd($items);
                foreach ($items as $item) {
                    $item_info = new stdClass();

                    $item_info->item = $item->boq_item;
                    if (!$item->boq_item->has_sub_items) {
                        $bill_detail = BillDetail::where('bill_id', $bill->id)//->with('measurements')
                            ->where('scheme_id', $scheme_id)
                            ->where('boq_part_id', $part_id)
                            ->where('boq_item_id', $item->boq_item_id)
                            ->first();
                        
                        $item_info->bill_detail = $bill_detail;
                        $boq=BoqVersionDetails::where('boq_version_id', $bill->boq_version_id)
                            ->where('scheme_option_id', $shelter->scheme_option_id)
                            ->where('boq_part_id', $part_id)
                            ->where('boq_item_id', $item->boq_item_id)
                            ->first();
             
                        $item_info->boq_version_details=$boq;
                        $item_info->measurements=Measurement::where('bill_id', $bill->id)
                            ->where('scheme_id', $scheme_id)
                            ->where('boq_part_id', $part_id)
                            ->where('boq_item_id', $item->boq_item_id)
                            ->get();
                        
                    }
                    array_push($part_data->items, $item_info);
                }
                array_push($info->parts, $part_data);
            }
            array_push($shelter_bill, $info);
        }
        // dd($bill,$last_bill);
        // dd($shelter_bill);
        return view('backend.bill.shelter_bill', compact('shelter_bill', 'project', 'bill', 'last_bill'));
        return response()->json($shelter_bill);
    }
}
