<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSpecialization;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class DoctorSpecializationController extends Controller
{
    public function addSpecialization(Request $request)
    {
        $rules = array(
            'specialization' => 'required',
        );

        $customMessages = [
            'specialization.required' => "Specialization need to be selected",
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
                $doctorSpecialization = new DoctorSpecialization();
                $doctorSpecialization->doctor = $doctor->id;
                $doctorSpecialization->specialization = $request->specialization;
                $doctorSpecialization->experience_year = $request->experience_year;
                $doctorSpecialization->note = $request->note;
                $doctorSpecialization->save();

                $response["msg"] = "Specialization added successfully";
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

    public function updateSpecialization(Request $request, $id) {
        if(is_null($id) or empty($id)) {
            $response["msg"] = "Invalid Specialization";
            $response["status"] = "Failed";
            $response["is_success"] = false;
            return $response;
        }

        $rules = array(
            'specialization' => 'required',
        );

        $customMessages = [
            'specialization.required' => "Specialization can't be empty",
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $doctor = Doctor::where('user', Auth::user()->id)->first();

        if ($validator->fails()) {
            $response["msg"] = $validator->messages()->first();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if (empty($doctor) or is_null($doctor)) {
            $response["msg"] = "You are not allowed to update specialization";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else {
            try {
                $specialization = DoctorSpecialization::where('doctor', $doctor->id)->where('id', $id)->first();
                if (!is_null($specialization) && !empty($specialization)) {
                    $specialization->specialization = $request->specialization;
                    $specialization->experience_year = $request->experience_year;
                    $specialization->note = $request->note;
                    $specialization->save();

                    $response["msg"] = "Specialization has been updated successfully";
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

    public function deleteSpecialization($id)
    {
        if(is_null($id) or empty($id)) {
            return redirect()->back()->with('error', "Invalid Specialization");
        }

        $doctor = Doctor::where('user', Auth::user()->id)->first();
        $specialization = DoctorSpecialization::where('doctor', $doctor->id)->where('id', $id)->first();
        
        if (empty($doctor) or is_null($doctor)) {
            return redirect()->back()->with('error', "You are not allowed to delete Specialization");
        } else if(is_null($specialization) or empty($specialization)) {
            return redirect()->back()->with('error', "Invalid Specialization");
        } else {
            try {
                DoctorSpecialization::where('doctor', $doctor->id)->where('id', $id)->delete();

                Session::flash('message', "Specialization has been deleted successfully");
            } catch(\Exception $ex) {
                return redirect()->back()->with('error', "Operation failed. Please try again.");
            } catch(\Throwable $ex) {
                return redirect()->back()->with('error', "Operation failed. Please try again.");
            }
        }
        return redirect()->back();
    }
}
