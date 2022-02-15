<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorQualification;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class DoctorQualificationController extends Controller
{
    public function addQualification(Request $request)
    {
        $rules = array(
            'qualification' => 'required',
        );

        $customMessages = [
            'qualification.required' => "Qualification can't be empty",
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $doctor = Doctor::where('user', Auth::user()->id)->first();

        if ($validator->fails()) {
            $response["msg"] = $validator->messages()->first();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if (empty($doctor) or is_null($doctor)) {
            $response["msg"] = "You are not a Doctor";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else {
            try {
                $doctorQualification = new DoctorQualification();
                $doctorQualification->doctor = $doctor->id;
                $doctorQualification->qualification = $request->qualification;
                $doctorQualification->year = $request->year;
                $doctorQualification->save();
                // return $doctorQualification;
                $response["msg"] = "Qualification added successfully";
                $response["status"] = "Sucess";
                $response["is_success"] = true;
            } catch(\Exception $ex) {
                $response["msg"] = "Operation failed. Please try again.";
                $response["exception"] = $ex->getMessage();
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } catch(\Throwable $ex) {
                $response["msg"] = "Operation failed. Please try again.";
                $response["exception"] = $ex->getMessage();
                $response["status"] = "Failed";
                $response["is_success"] = false;
            }
        }
        return $response;
    }

    // public function editViewQualification($id)
    // {
    //     if (is_null($id) or empty($id)) {
    //         return redirect()->back()->with('error', "Invalid Qualification");
    //     }
    //     try {
    //         $user_id = Auth::user()->id;
    //         $userDetails = DB::table('users as u')->where('u.id', $user_id);
    //         $userType = $userDetails->first()->type;

    //         $qualification = DB::table('doctor_qualifications as dq')
    //                 ->select('dq.*')
    //                 ->where('dq.id', $id)->first();

    //         return View::make('doctor.profile.qualification', compact('userType', 'qualification'));
    //     } catch(\Exception $e) {
    //         return redirect()->route('error');
    //     } catch(\Throwable $e) {
    //         return redirect()->route('error');
    //     }
    // }

    public function updateQualification(Request $request, $id) {
        if(is_null($id) or empty($id)) {
            $response["msg"] = "Invalid Qualification";
            $response["status"] = "Failed";
            $response["is_success"] = false;
            return $response;
        }

        $rules = array(
            'qualification' => 'required',
        );

        $customMessages = [
            'qualification.required' => "Qualification can't be empty",
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $doctor = Doctor::where('user', Auth::user()->id)->first();

        if ($validator->fails()) {
            $response["msg"] = $validator->messages()->first();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if (empty($doctor) or is_null($doctor)) {
            $response["msg"] = "You are not allowed to update qualification";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else {
            try {
                $qualification = DoctorQualification::where('doctor', $doctor->id)->where('id', $id)->first();
                if (!is_null($qualification) && !empty($qualification)) {
                    $qualification->qualification = $request->qualification;
                    $qualification->year = $request->qualification_year;
                    $qualification->save();

                    $response["msg"] = "Qualification has been updated successfully";
                    $response["status"] = "Sucess";
                    $response["is_success"] = true;
                }
            } catch(\Exception $e) {
                $response["msg"] = "Operation failed. Please try again.";
                $response["exception"] = $ex->getMessage();
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } catch(\Throwable $e) {
                $response["msg"] = "Operation failed. Please try again.";
                $response["exception"] = $ex->getMessage();
                $response["status"] = "Failed";
                $response["is_success"] = false;
            }
        }
        return $response;
    }

    public function deleteQualification($id)
    {
        if(is_null($id) or empty($id)) {
            return redirect()->back()->with('error', "Invalid Qualification");
        }

        $doctor = Doctor::where('user', Auth::user()->id)->first();
        $qualification = DoctorQualification::where('doctor', $doctor->id)->where('id', $id)->first();
        
        if (empty($doctor) or is_null($doctor)) {
            return redirect()->back()->with('error', "You are not allowed to delete Qualification");
        } else if(is_null($qualification) or empty($qualification)) {
            return redirect()->back()->with('error', "Invalid Qualification");
        } else {
            try {
                DoctorQualification::where('doctor', $doctor->id)->where('id', $id)->delete();
                Session::flash('message', "Qualification has been deleted successfully");
            } catch(\Exception $ex) {
                return redirect()->back()->with('error', "Operation failed. Please try again.");
            } catch(\Throwable $ex) {
                return redirect()->back()->with('error', "Operation failed. Please try again.");
            }
        }
        return redirect()->back();
    }
}
