<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Config;
use Redirect;
use Validator;
use Session;

class RoleUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-view-role', ['only' => [
            'index',
            'show',
        ]]);
        $this->middleware('permission:can-add-role', ['only' => [
            'create',
            'store',
        ]]);
        $this->middleware('permission:can-edit-role', ['only' => [
            'edit',
            'update',
        ]]);
        $this->middleware('permission:can-delete-role', ['only' => [
            'destroy'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchFilter = $request->search;
        $data =  Role::with('permissions');
        if(isset($searchFilter) && $searchFilter != "")
        {
            $data->where(function ($q) use($searchFilter) {
                $q->where('roles.name', 'LIKE', '%' . $searchFilter . '%' );
            });
        }
        $data = $data->get();

        return view('admin.roles.index',compact('searchFilter'))->with(['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Permission::select('type')->groupBy('type')->get();
        $permissions = Permission::all();
        return view('admin.roles.create')->with(['permissions'=>$permissions,'types'=>$types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $rules = array(
                'name' => 'required|string|max:50|unique:roles,name,id',
                'description' => 'nullable|string|max:150',
                'permission.*' => 'exists:permissions,id',
            );

            $customMessages = [
                'name.required' => 'Name cannot be empty',
                'name.unique' => 'Name has been already taken. Please try with another one',
            ];


            $validator = Validator::make($request->all(), $rules, $customMessages);


            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput($request->all());
            } else {
                // store

                 $role = Role::create($request->all());


                $role->permissions()->sync($request->permission);
                // redirect
                Session::flash('message', 'Role has been added successfully');
                return redirect()->route('roles.index');
            }

        }catch(\Exception $e){

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $types = Permission::select('type')->groupBy('type')->get();
        $permissions = Permission::all();
        return view('admin.roles.show')->with(['role'=>$role,'permissions'=>$permissions,'types'=>$types]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $types = Permission::select('type')->groupBy('type')->get();
        $permissions = Permission::all();
        return view('admin.roles.edit')->with(['role'=>$role,'permissions'=>$permissions,'types'=>$types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $rules = array(
                'name' => 'required|string|max:50|unique:roles,name,'.$id,
                'description' => 'nullable|string|max:150',
                'permission.*' => 'exists:permissions,id',
            );

            $customMessages = [
                'name.required' => 'Name cannot be empty',
                'name.unique' => 'Name has been already taken. Please try with another one',
            ];

            $validator = Validator::make($request->all(), $rules, $customMessages);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput($request->all());
            } else {
                // store
                $role = Role::find($id)->update($request->all());
                PermissionRole::where('role_id','=',$id)->delete();
                $role = Role::find($id);
                $request->permission;
                $role->permissions()->sync($request->permission);
                // redirect

                Session::flash('message', 'Role has been updated successfully');
                return redirect()->route('roles.index');
            }

        }catch(\Exception $e){

        }
    }
}
