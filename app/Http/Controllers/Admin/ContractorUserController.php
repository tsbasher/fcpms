<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator ;
use stdClass;

class ContractorUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users=User::with('contractor');
        if($request->has('search_text') && !empty($request->search_text)){
            $users->where(function($query) use($request){
                $query->where('name','ilike','%'.$request->search_text.'%')
                      ->orWhere('email','ilike','%'.$request->search_text.'%')
                      ->orWhere('phone','ilike','%'.$request->search_text.'%');
            });
        }
        if($request->has('contractor_id') && !empty($request->contractor_id)){
            $users->where('contractor_id',$request->contractor_id);
        }
        $users=$users->paginate();
        $contractors= Contractor::where('is_active',1)->get();
        return view('backend.admin.users.index',compact('users','contractors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contractors= Contractor::where('is_active',1)->get();
        return view('backend.admin.users.create',compact('contractors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!$request->has('is_active'))
            $request->merge(['is_active'=>0]);
        $v=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'phone'=>'nullable|unique:users,phone',
            'password'=>'required|min:6|confirmed ',
            'contractor_id'=>'required|exists:contractors,id',
        ]);
        if($v->fails()){
            return redirect()->back()->withErrors($v)->withInput();
        }
        $user_data=$request->only(['name','email','phone','contractor_id','is_active']);
        $user_data['password']=Hash::make($request->password);
        $contractor=Contractor::find($request->contractor_id);
        // dd($contractor->packages->first());
        $user_data['project_id']=$contractor->project_id;
        $user_data['package_id']=$contractor->packages->first() ? $contractor->packages->first()->id : null;
        User::create($user_data);
        return redirect()->route('admin.contractor_users.index')->with('success','User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user=User::findOrFail($id);
        $contractors= Contractor::where('is_active',1)->get();
        return view('backend.admin.users.edit',compact('user','contractors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user=User::findOrFail($id);
        if(!$request->has('is_active'))
            $request->merge(['is_active'=>0]);
        $v=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'phone'=>'nullable|unique:users,phone,'.$user->id,
            'password'=>'nullable|min:6|confirmed ',
            'contractor_id'=>'required|exists:contractors,id',
        ]);
        if($v->fails()){
            return redirect()->back()->withErrors($v)->withInput();
        }
        $user_data=$request->only(['name','email','phone','contractor_id','is_active']);
        if($request->filled('password')){
            $user_data['password']=Hash::make($request->password);
        }
        $contractor=Contractor::with('packages')->find($request->contractor_id);
        $user_data['project_id']=$contractor->packages->first() ? $contractor->packages->first()->project_id : null;
        $project=Project::find($user_data['project_id']);
        $user_data['project_code']=$project ? $project->code : null;
        $user_data['package_id']=$contractor->packages->first() ? $contractor->packages->first()->id : null;
        // dd($contractor->packages);
        $user->update($user_data);
        return redirect()->route('admin.contractor_users.index')->with('success','User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            // Check if the package is associated with any other records
            $user->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'User deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting User.';
            return response()->json($data);
        }
    }
}
