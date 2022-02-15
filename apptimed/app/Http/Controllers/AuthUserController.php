<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatersUsers;
use Illuminate\Support\Str;
use Validator;
use Session;
use Redirect;
use DB;


class AuthUserController extends Controller
{
    function login(Request $request)
    {
        $checkUser = User::where('email',$request->email)->first();

        if($checkUser == null)
            return Redirect::to('/login')->with('error','Invalid credentials. Please try again.');

        if($checkUser->status == 0) {
            return redirect()->route('login')->with('error', "You account hasn't been activated yet. Please check the email that was sent to you during your account creation. If you don't have that email, please use 'Reset Password' functionality.");
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) 
        {
            $user = Auth::user();
            $newToken = (string) Str::uuid();
            $user->remember_token = $newToken;
            $user->save();

            session(['loginedIn' => Auth::user()->id]);
            return Redirect::to('/dashboard');
        }
        else{
            return Redirect::to('/login')->with('error','Invalid credentials. Please try again.');
        }
    }


    function emailOTP(Request $request)
    {
        if($request->token == null)
            return Redirect::to('/login')->with('error','You need to login first to continue doing the requested operation');
        else{
            $user = User::where("remember_token", $request->token)->first();

            if($user == null){
                return Redirect::to('/login')->with('error','You need to login first to continue doing the requested operation');
            }
            else{
                return view('auth.email-otp')->with("token",  $request->token);
            }
        }        
    }
    
    function logout()
    {
        $user = Auth::user();
        $response["msg"] = 'Successfully logout into the system';
        $response["status"] = "success";
        $response["is_success"] = true;
        if ($user->save()) {
            $response["msg"] = "Logout in successfully";
            $response["status"] = "Success";
            $response["is_success"] = true;
        }
        session()->flush();
        return Redirect::to('/login');
    }

    public function patientSignup() {
        return view('auth.patient-signup');
    }

    public function doctorSignup() {
        return view('auth.doctor-signup');
    }

    public function patientRegister(Request $request) {
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'contact' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'gender' => 'required',
            'dob' => 'required',
        );

        $customMessages = [
            'firstname.required' => 'First Name cannot be empty',
            'lastname.required' => 'Last Name cannot be empty',
            'contact.required' => 'Contact Number cannot be empty',
            'contact.unique' => 'Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'password.required' => 'Password cannot be empty',
            'gender.required' => 'Gender need to be selected',
            'dob.required' => 'Date of Birth need to be selected',
        ];
       
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()
                    ->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = new User();
                $user->type = 'Patient';
                $user->fname = $request->firstname;
                $user->lname = $request->lastname;
                $user->contact = $request->contact;
                $user->email = $request->email;
                $user->remember_token = Str::random(100);
                $user->password = Hash::make($request->password);
                $user->gender = $request->gender; 
                $user->dob = $request->dob; 
                $user->save();

                $stringUtil = new \App\Services\StringUtils();
                $user->code = $stringUtil->randomStringGenerator($user->fname, $user->id);
                $user->save();

                $patient = new Patient();
                $patient->user = $user->id;
                $patient->save();
                
                $details = [
                    'title' => 'Welcome to Placements.lk',
                    'token' => 'verify?token='.$user->remember_token,
                    'name' => $user->fname." ".$user->lname
                ];
                
                try {
                    \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));
                } catch(\Exception $e) {
                    return redirect()->back()->with('error', 'Error while sending email.');
                }
                
                Session::flash('message', 'Successfully registered.');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            }
            return redirect()->route('login');
        }
    }

    public function verifyEmail(Request $request){
        $rules = array(
            'token' => 'required'
        );

        $customMessages = [
            'token.required' => 'Token cannot be empty'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $reqToken = $request->token;
            $user = User::where('remember_token', $reqToken)->first();
            if (!is_null($user) && !empty($user)) {
                $user->status = 1;
                $user->remember_token = null;
                $user->save();
                Session::flush();
                Session::flash('message', 'Your account has been activated successfully. Please login to continue.');
                return redirect()->route('login');
            } else {
                Session::flash('error', 'Something is not right. Chances are your account is already activated OR you have clicked on a wrong link.');
                return redirect()->route('login');
            }
        }
    }

    public function doctorRegister(Request $request) {
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'contact' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'title' => 'required',
            'registration_id' => 'required|unique:doctors',
            'timeslot_duration' => 'required',
            'fees' => 'required',
            'bank_branch' => 'required',
            'bank_account_no' => 'required',
            'bank_holder_name' => 'required',
        );

        $customMessages = [
            'firstname.required' => 'First Name cannot be empty',
            'lastname.required' => 'Last Name cannot be empty',
            'contact.required' => 'Contact Number cannot be empty',
            'contact.unique' => 'Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'password.required' => 'Password cannot be empty',
            'title.required' => 'Title cannot be empty',
            'registration_id.required' => 'Registration Id cannot be empty',
            'registration_id.unique' => 'Registration Id has been already taken. Please try with another one',
            'timeslot_duration.required' => 'Average Consultation Time(Min) cannot be empty',
            'fees.required' => 'Consultation fees cannot be empty',
            'bank_branch.required' => 'Bank and Branch name cannot be empty',
            'bank_account_no.required' => 'Bank Account No cannot be empty',
            'bank_holder_name.required' => 'Bank Account Holder Name cannot be empty',
        ];
       
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()
                    ->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = new User();
                $user->type = 'Doctor';
                $user->fname = $request->firstname;
                $user->lname = $request->lastname;
                $user->contact = $request->contact;
                $user->email = $request->email;
                $user->remember_token = Str::random(100);
                $user->password = Hash::make($request->password);
                $user->gender = $request->gender; 
                $user->dob = $request->dob; 
                $user->save();

                $stringUtil = new \App\Services\StringUtils();
                $user->code = $stringUtil->randomStringGenerator($user->fname, $user->id);
                $user->save();
                
                $doctor = new Doctor();
                $doctor->user = $user->id;
                $doctor->title = $request->title;
                $doctor->registration_id = $request->registration_id;
                $doctor->timeslot_duration = $request->timeslot_duration;
                $doctor->fees = $request->fees;
                $doctor->app_fees = ($request->fees) * 0.1;
                $doctor->bank_branch = $request->bank_branch;
                $doctor->bank_account_no = $request->bank_account_no;
                $doctor->bank_holder_name = $request->bank_holder_name;
                $doctor->save();
                
                $details = [
                    'title' => 'Welcome to Placements.lk',
                    'token' => 'verify?token='.$user->remember_token,
                    'name' => $user->fname
                ];
                
                try {
                    \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));
                } catch(\Exception $e) {
                    return redirect()->back()->with('error', 'Error while sending email.');
                }
                
                Session::flash('message', 'Successfully registered.');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            }
            return redirect()->route('login');
        }
    }

    public function passwordRequest(Request $request)
    {
        if($request->token == null){
            return view("auth.forgot-password",['token' => null]);
        }else{
            return view("auth.forgot-password",['token' => $request->token]);
        }
    }

    public function forgotPassword(Request $request)
    {
        if($request->email){
            $user = User::where('email',$request->email)->first();
            if($user != null){
                $user->remember_token = Str::random(100);
                $user->save();
                $details = [
                    'title' => 'Password Reset Email',
                    'token' => 'forgot-password?token='.$user->remember_token,
                    'name' => $user->fname." ".$user->lname,
                    'email' => $user->email
                ];

                \Mail::to($user->email)->send(new \App\Mail\forgotPasswordEmail($details));
                return redirect()->route('login')->with('message', 'Please Check your Email');
            }
            Session::flash('message', 'Please Check your Email');
            return view('auth.forgot-password');
        } else {
            $rules = array(
                'password' => 'required',
            );
    
            $customMessages = [
                'password.required' => 'Password cannot be empty',
            ];
            $validator = Validator::make($request->all(), $rules, $customMessages);

            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return  view("auth.forgot-password",['token' => $request->token]);
            } else {
                $user = User::where('remember_token', $request->token)->first();
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect()->route('login')->with('message', 'Your password has been successfully reset');
            }
        }
    }
}
