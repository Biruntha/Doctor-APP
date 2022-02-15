<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Redirect;
use Validator;
use Session;
use Storage;
use Image;
use View;
use Str;
use Auth;
use DB;
use App\Helpers\ConfigUserStatusHelper;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-view-user', ['only' => [
            'index',
            'show',
        ]]);
        $this->middleware('permission:can-add-user', ['only' => [
            'create',
            'store',
        ]]);
        $this->middleware('permission:can-edit-user', ['only' => [
            'edit',
            'update',
        ]]);
        $this->middleware('permission:can-delete-user', ['only' => [
            'destroy',
            'updateStatus'
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
        $roleFilter = $request->role;
        $roles = Role::get(['id','name']);
        $data =  User::leftJoin('roles','roles.id','=','users.role')
        ->select('users.*','roles.name as role_name')->whereNotNull("users.role");
        if(isset($roleFilter) && $roleFilter != "")
        {
            $data->where('users.role','=',$roleFilter);
        }
        if(isset($searchFilter) && $searchFilter != "")
        {
            $data->where(function ($q) use($searchFilter) {
                $q->where('users.fname', 'LIKE', '%' . $searchFilter . '%' );
            });
        }
        $data = $data->get();

        return view('admin.users.index',compact('searchFilter','roleFilter','roles'))->with(['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get(['id','name']);

        $fieldsForFilter = Field::all();
        $languagesForFilter = Language::all();


        return view('admin.users.create')->with(['roles'=>$roles])
                ->with("fieldsForFilter", $fieldsForFilter)
                ->with("languagesForFilter", $languagesForFilter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->role == 3) {
            $rules = array(
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required|string|unique:users',
                'password' => 'required',
                'fields' => 'required',
                'languages' => 'required',
            );
    
            $customMessages = [
                'fname.required' => 'First Name cannot be empty',
                'lname.required' => 'Last Name cannot be empty',
                'email.required' => 'Email cannot be empty',
                'email.unique' => 'Email has been already taken. Please try with another one',
                'password.required' => 'Password cannot be empty',
                'fields.required' => 'Fields cannot be empty',
                'languages.required' => 'Language cannot be empty',
            ];
        } else {
            $rules = array(
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required|string|unique:users',
                'password' => 'required',
            );
    
            $customMessages = [
                'fname.required' => 'First Name cannot be empty',
                'lname.required' => 'Last Name cannot be empty',
                'email.required' => 'Email cannot be empty',
                'email.unique' => 'Email has been already taken. Please try with another one',
                'password.required' => 'Password cannot be empty',
            ];
        }
       
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput($request->all());
        } else {
            // store

                $user = new User();
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->email = $request->email;
                $user->role = $request->role;
                $user->status = false;
                $user->password = Hash::make($request->password);
                if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
    
                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();                 
                });
    
                $img->stream(); // <-- Key point
    
                //dd();
                Storage::disk('local')->put('UserImages'.'/'.$fileName, $img, 'public');
                $user->image = $fileName;
            }
            $user->save();

            $permissions = PermissionRole::where('role_id','=',$request->role)->get();
            foreach ($permissions as $key => $value) {
                $userPermission = new UserPermission();
                $userPermission->user_id = $user->id;
                $userPermission->permission_id = $value->permission_id;
                $userPermission->save();
            }

            if($request->role == 3) {
                foreach ($request->input('fields') as $field) {
                    $fields = Field::where('id', $field)->get();
                    if (!is_null($fields)) {
                        $contentWriterField = new ContentWriterField();
                        $contentWriterField->user = $user->id;
                        $contentWriterField->field = $field;
                        $contentWriterField->save();
                    }
                }

                foreach ($request->input('languages') as $language) {
                    $languages = Language::where('id', $language)->get();
                    if (!is_null($languages)) {
                        $contentWriterLanguage = new ContentWriterLanguage();
                        $contentWriterLanguage->user = $user->id;
                        $contentWriterLanguage->language = $language;
                        $contentWriterLanguage->save();
                    }
                }
            }

            // dd($user->remember_token);
            $user->remember_token = Str::random(100);
            $user->save();
            $details = [
                'title' => 'Welcome to AllTheBestLinks.com',
                'token' => 'verify?token='.$user->remember_token,
                'name' => $user->fname." ".$user->lname
            ];
            // return $details;
            \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));

            // redirect
            Session::flash('message', 'User has been added successfully');
            return redirect()->route('users.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sm_op = 0;
        if($request->get('sm_op')) {
            $sm_op = 1;
        }
        $user = User::find($id);
        $types = Permission::select('type')->groupBy('type')->get();
        $permissions = Permission::all();
        $user->permissions = UserPermission::where('user_id','=',$id)->leftJoin('permissions','permissions.id','=','user_permissions.permission_id')->select('permissions.*')->get();
        // return $user;
        return View::make('admin.users.show', compact('user','types','permissions','sm_op'));
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
        if($request->role == 3) {
            $rules = array(
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required|string|unique:users,email,' . $id,
                'fields' => 'required',
                'languages' => 'required',
            );
    
            $customMessages = [
                'fname.required' => 'First Name cannot be empty',
                'lname.required' => 'Last Name cannot be empty',
                'email.required' => 'Email cannot be empty',
                'email.unique' => 'Email has been already taken. Please try with another one',
                'fields.required' => 'Fields cannot be empty',
                'languages.required' => 'Language cannot be empty',
            ];
        } else {
            $rules = array(
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required|string|unique:users,email,' . $id,
            );
    
            $customMessages = [
                'fname.required' => 'First Name cannot be empty',
                'lname.required' => 'Last Name cannot be empty',
                'email.required' => 'Email cannot be empty',
                'email.unique' => 'Email has been already taken. Please try with another one',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        } else {
            // store
            $user = User::find($id);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->email = $request->email;
            $user->role = $request->role;
            if($request->password != '') {
            $user->password = Hash::make($request->password);
            }
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
    
                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();                 
                });
    
                $img->stream(); // <-- Key point
    
                //dd();
                Storage::disk('local')->put('UserImages'.'/'.$fileName, $img, 'public');
                $user->image = $fileName;
            }
            $user->save();

            $de = UserPermission::where('user_id','=',$id)->delete();
            foreach ($request->permission as $key => $value) {
                $userPermission = new UserPermission();
                $userPermission->user_id = $id;
                $userPermission->permission_id = $value;
                $userPermission->save();
            }

            if($user->role == 3) {
                $contentWriterFields = DB::table('content_writer_fields')->where('user', $id);
                if (!empty($contentWriterFields) or !is_null($contentWriterFields)) {
                    $contentWriterFields->delete();
                }

                foreach ($request->input('fields') as $field) {
                    $fields = Field::where('id', $field)->get();
                    if (!is_null($fields)) {
                        $contentWriterField = new ContentWriterField();
                        $contentWriterField->user = $user->id;
                        $contentWriterField->field = $field;
                        $contentWriterField->save();
                    }
                }

                $contentWriterLanguages = DB::table('content_writer_languages')->where('user', $id);
                if (!empty($contentWriterLanguages) or !is_null($contentWriterLanguages)) {
                    $contentWriterLanguages->delete();
                }

                foreach ($request->input('languages') as $language) {
                    $languages = Language::where('id', $language)->get();
                    if (!is_null($languages)) {
                        $contentWriterLanguage = new ContentWriterLanguage();
                        $contentWriterLanguage->user = $user->id;
                        $contentWriterLanguage->language = $language;
                        $contentWriterLanguage->save();
                    }
                }
            }

            // redirect
            Session::flash('message', 'User has been updated successfully');
            return redirect()->route('users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        // 
    }

    public function editProfile(Request $request) {
        try {
            $user_id = Auth::user()->id;
            $selectArray = array('u.fname', 'u.type', 'u.lname', 'u.oname', 'u.email', 'u.contact', 'u.contact_secondary', 'u.password', 'u.gender', 'u.dob', 'u.image', 'u.remarks', 'u.registered_mode', 'u.found_mode', 'u.timezone');
            $userDetails = DB::table('users as u')->where('u.id', $user_id);
            $userType = $userDetails->first()->type;

            $doctor = null;
            if ($userType == "Doctor") {
                $doctor = Doctor::where('user', $user_id)->first();
                if (is_null($doctor) or empty($doctor)) {
                    return redirect()->route('dashboard')->with('error', 'You are not a Doctor');
                }
                array_push($selectArray, 'd.title', 'd.registration_id', 'd.timeslot_duration', 'd.fees', 'd.bank_branch', 'd.bank_account_no', 'd.bank_holder_name');
                $userDetails = $userDetails->leftjoin('doctors as d', 'd.user', '=', 'u.id');
            } else if ($userType == "Patient") {
                $patient = Patient::where('user', $user_id)->first();
                if (is_null($patient) or empty($patient)) {
                    return redirect()->route('dashboard')->with('error', 'You are not a Patient');
                }
                array_push($selectArray, 'p.latititude', 'p.longitude', 'p.bank_branch', 'p.bank_account_no', 'p.bank_holder_name');
                $userDetails = $userDetails->leftjoin('patients as p', 'p.user', '=', 'u.id');
            }

            $userDetails = $userDetails->select($selectArray)->first();
// return $userDetails;
            $doctor_qualifications = array();
            $doctor_specializations = array();

            if ($userType == "Doctor") {
                $doctor_qualifications = DB::table('doctor_qualifications as dq')
                        ->select('dq.*')
                        ->where('dq.doctor', $doctor->id)->get();

                $doctor_specializations = DB::table('doctor_specializations as ds')
                    ->select('ds.*', 's.name as specialization', 'ds.specialization as specialization_id')
                    ->leftjoin('specializations as s', 's.id', '=', 'ds.specialization')
                    ->where('ds.doctor', $doctor->id)->get();
            }
            $specializations = Specialization::all();
            return view('common.edit-profile', compact('doctor_qualifications', 'doctor_specializations', 'specializations', 'userType'))->with('user', $userDetails);
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function updateProfile(Request $request) {
        $user_id = Auth::user()->id;
        $userType = DB::table('users as u')->where('u.id', $user_id)->first()->type;
        
        $rules = array(
            'fname' => 'required',
            'lname' => 'required',
            'contact' => 'required|unique:users,contact,' . $user_id,
        );

        $customMessages = [
            'fname.required' => 'First Name cannot be empty',
            'lname.required' => 'Last Name cannot be empty',
            'contact.required' => 'Contact Number cannot be empty',
            'contact.unique' => 'Contact Number has been already taken. Please try with another one',
        ];

        if ($userType == "Doctor") {
            $rules['title'] = 'required';
            $rules['registration_id'] = 'required';
            $rules['timeslot_duration'] = 'required';
            $rules['fees'] = 'required';
            $rules['bank_branch'] = 'required';
            $rules['bank_account_no'] = 'required';
            $rules['bank_holder_name'] = 'required';

            $customMessages['title.required'] = 'Title cannot be empty';
            $customMessages['registration_id.required'] = 'Registration No cannot be empty';
            $customMessages['timeslot_duration.required'] = 'Average Consultation Time cannot be empty';
            $customMessages['fees.required'] = 'Consultation Fees cannot be empty';
            $customMessages['bank_branch.required'] = 'Bank and Branch name cannot be empty';
            $customMessages['bank_account_no.required'] = 'Bank Account No cannot be empty';
            $customMessages['bank_holder_name.required'] = 'Bank Account Holder Name cannot be empty';
        }

        if ($userType == "Patient") {
            $rules['gender'] = 'required';
            $rules['dob'] = 'required';
            $customMessages['gender.required'] = 'Gender need to be selected';
            $customMessages['dob.required'] = 'Date of Birth need to be selected';
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = User::find($user_id);
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->oname = $request->oname;
                $user->contact = $request->contact;
                $user->contact_secondary = $request->contact_secondary;
                $user->gender = $request->gender;
                $user->dob = $request->dob;
                $user->remarks = $request->remarks;
                $user->found_mode = $request->found_mode;
               
                //Profile Image
                if ($request->hasFile('profile_image')) {
                    $image      = $request->file('profile_image');
                    $fileName   = time() . '.' . $image->getClientOriginalExtension();
                    
                    $img = Image::make($image->getRealPath());
                    $img->resize(120, 120, function ($constraint) {
                        $constraint->aspectRatio();                 
                    });
                    $img->stream();
        
                    Storage::disk('local')->put('/UserImages'.'/'.$fileName, $img);
                    $user->image = $fileName;
                } else {
                    $is_delete = $request['delete-dp'];
                    if (!is_null($is_delete) && $is_delete == '1') {
                        $user->image = null;
                    }
                }

                //Password
                $current_pass = $request->cpassword;
                $new_pass = $request->npassword;
                $confirm_pass = $request->npassword2;
                if (!is_null($current_pass) && !empty($current_pass)) {
                    if (!is_null($new_pass) && !empty($new_pass) && !is_null($confirm_pass) && !empty($confirm_pass)) {
                        if ($new_pass == $confirm_pass) {
                            $newPass = Hash::make($new_pass);
                            $exist_pass = $user->password;
                            if (Hash::check($current_pass, $exist_pass)) {
                                if ($new_pass != $current_pass) {
                                    $user->password = $newPass;
                                } else {
                                    return redirect()->back()->with('error', 'Current password and New password cannot be same'); 
                                }
                            } else {
                                return redirect()->back()->with('error', 'Invalid Current password provided'); 
                            }
                        } else {
                            return redirect()->back()->with('error', 'New password and confirm password need to be same');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Please provide new/confirm password');
                    }
                }
                $user->save();

                if ($userType == "Doctor") {
                    $doctor = Doctor::where('user', $user_id)->first();
                    $doctor->title = $request->title;
                    $doctor->registration_id = $request->registration_id;
                    $doctor->timeslot_duration = $request->timeslot_duration;
                    $doctor->fees = $request->fees;
                    $doctor->app_fees = ($request->fees) * 0.1;
                    $doctor->bank_branch = $request->bank_branch;
                    $doctor->bank_account_no = $request->bank_account_no;
                    $doctor->bank_holder_name = $request->bank_holder_name;
                    $doctor->save();
                } else if ($userType == "Patient") {
                    $patient = Patient::where('user', $user_id)->first();
                    $patient->latititude = $request->latititude;
                    $patient->longitude = $request->longitude;
                    $patient->bank_branch = $request->bank_branch;
                    $patient->bank_account_no = $request->bank_account_no;
                    $patient->bank_holder_name = $request->bank_holder_name;
                    $patient->save();
                }
                Session::flash('message', 'Profile has been updated successfully.');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            }
            return redirect()->back();
        }
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        // redirect
        Session::flash('message', 'User status has been updated successfully');
        return redirect()->route('users.show',$request->user_id);
    }
}