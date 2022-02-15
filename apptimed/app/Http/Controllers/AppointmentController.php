<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;
use DateTime;

class AppointmentController extends Controller
{
    public function getAllAppointments(Request $request)
    {
        // try {
            $searchFilter = $request->searchFilter;
            $doctorFilter = $request->doctor;
            $fdate = $request->fdate;
            $tdate = $request->tdate;
            $stime = $request->stime;
            $etime = $request->etime;
            $statusFilter = $request->status;

            $date = new DateTime();
            $currentDate = $date->format('Y-m-d');
            $currentTime = $date->format('H:i:s');
// return json_encode(date("H:i:s"));
            if (is_null($fdate) or empty($fdate)) {
                $fdate = $currentDate;
            }
            if (is_null($stime) or empty($stime)) {
                $stime = $currentTime;
            }
            $appointments = DB::table('appointments as a')
                        ->select('a.id', DB::raw("IFNULL(a.patient_name, CONCAT(u1.fname, ' ', u1.lname)) as patient_name"), 'a.date', 'a.appointment_number', 'a.status', 'a.time_from', DB::raw("DATE_FORMAT(a.time_from, '%r') as start_time"), DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"), 'a.doctor')
                        ->leftjoin('doctors as d', 'd.id', '=', 'a.doctor')
                        ->leftjoin('users as u', 'u.id', '=', 'd.user')
                        ->leftjoin('patients as p', 'p.id', '=', 'a.patient')
                        ->leftjoin('users as u1', 'u1.id', '=', 'p.user');
            
            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $appointments = $appointments->where(function ($query) use($searchFilter) {
                    $query->where(DB::raw("IFNULL(a.patient_name, CONCAT(u1.fname, ' ', u1.lname))"), 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('a.appointment_number', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('a.notes', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('a.prescriptions', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('a.status', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere(DB::raw("CONCAT(u.fname, ' ', u.lname)"), 'LIKE', '%'.$searchFilter.'%');
                });
            }

            if(isset($doctorFilter) && $doctorFilter != "") {
                $appointments->where('a.doctor', '=', $doctorFilter);
            }
            if(isset($fdate) && $fdate != "") {
                $appointments = $appointments->whereDate('a.date', '>=', $fdate);
            }
            if(isset($tdate) && $tdate != "") {
                $appointments = $appointments->whereDate('a.date', '<=', $tdate);
            }
            if(isset($stime) && $stime != "") {
                $appointments->where('a.time_from', '>=', $stime);
            }
            if(isset($etime) && $etime != "") {
                $appointments->where('a.time_from', '<=', $etime);
            }
            if(isset($statusFilter) && $statusFilter != "") {
                $appointments->where('a.status', '=', $statusFilter);
            }
            $appointments = $appointments->orderBy('a.date')->orderBy('a.time_from')->paginate(20);

            $doctors = DB::table('doctors as d')
                        ->select('d.id', DB::raw("CONCAT(u.fname, ' ', u.lname) as name"))
                        ->leftjoin('users as u', 'u.id', '=', 'd.user')->get();
            // return $doctors;
            return view('admin.appointments.index', compact('appointments', 'searchFilter', 'doctorFilter', 'fdate',
                    'tdate', 'stime', 'etime', 'statusFilter', 'doctors'));
        // } catch(\Exception $e) {
        //     return redirect()->route('error');
        // } catch(\Throwable $e) {
        //     return redirect()->route('error');
        // }
    }
    
    public function getReserveView($code)
    {
        try {
            if(is_null($code) or empty($code)) {
                return redirect()->route('dashboard')->with('error', 'Invalid Timeslot');
            }
            $currentDate = new DateTime();
            $currentDate = $currentDate->format('Y-m-d');
            
            $patient = DB::table('patients as p')
                    ->select('p.id', 'u.gender', 'u.dob', DB::raw("DATE_FORMAT(FROM_DAYS(DATEDIFF(now(), u.dob)), '%Y')+0 AS age"), DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"))
                    ->leftjoin('users as u', 'u.id', '=', 'p.user')
                    ->where('p.user', Auth::user()->id)->first();
            if(is_null($patient) or empty($patient)) {
                return redirect()->route('dashboard')->with('error', 'You are not a patient');
            }

            $specialization_query = DB::raw("(SELECT ds.doctor AS doctor1, GROUP_CONCAT(' ', s.name) AS specialization FROM doctor_specializations AS ds LEFT JOIN specializations AS s ON s.id = ds.specialization GROUP BY ds.doctor) as specializations");

            $data = DB::table('doctor_timeslots as dt')
                    ->select('dt.id as timeslot_id', 'dt.code as timeslot_code', 'dt.date', DB::raw("DATE_FORMAT(dt.time_from, '%r') as start_time"), 'd.timeslot_duration', 'd.fees', 'd.app_fees', 'u.code', 'u.image', DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"), DB::raw("DATE_FORMAT(dt.time_from, '%r') as time_from"), "specializations.specialization as specializations")
                    ->leftjoin('doctors as d', 'd.id', '=', 'dt.doctor')
                    ->leftjoin('users as u', 'u.id', '=', 'd.user')
                    ->leftJoin($specialization_query, 'd.id', '=', 'doctor1')
                    ->where('dt.code', $code)->first();
            $appointment_number = DB::table('appointments as a')
                                ->select(DB::raw("IFNULL(MAX(appointment_number), 0) + 1 as appointment_number"))
                                ->where('a.doctor_timeslot', $data->timeslot_id)
                                ->where('a.status', 'Confirmed')->first();
            $next_available_time = DB::table('appointments as a')
                                ->select(DB::raw("DATE_FORMAT(ADDTIME(MAX(a.time_from), SEC_TO_TIME($data->timeslot_duration*60)), '%r') as next_available_time"))
                                ->where('a.doctor_timeslot', $data->timeslot_id)
                                ->where('a.status', 'Confirmed')->first();
            if (is_null($next_available_time->next_available_time) or empty($next_available_time->next_available_time)) {
                $next_available_time->next_available_time = $data->start_time;
            }
            $tomorrowDate = new DateTime();
            $tomorrowDate->modify('+1 day');
            $tomorrowDate = $tomorrowDate->format('Y-m-d');
            
            return view('patients.doctors.reserve', compact('data', 'patient', 'currentDate', 'tomorrowDate', 'appointment_number', 'next_available_time'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function getMyAppointments()
    {
        // try {
            $user = User::find(Auth::user()->id);
            if ($user->type != "Patient") {
                return redirect()->route('dashboard')->with('error', 'You are not a Patient');
            }
            $patient_id = Patient::where('user', $user->id)->first();
            $appointments = DB::table('appointments as a')
                        ->select('a.id', 'a.patient_name', 'a.date', 'a.appointment_number', 'a.status', DB::raw("DATE_FORMAT(a.time_from, '%r') as start_time"), DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"))
                        ->leftjoin('doctors as d', 'd.id', '=', 'a.doctor')
                        ->leftjoin('users as u', 'u.id', '=', 'd.user')
                        ->where('a.patient', $patient_id->id)
                        ->orderBy('a.date', 'DESC')->orderBy('a.time_from', 'ASC')->paginate(20);
            // return $appointments;
            return view('patients.appointments.index', compact('appointments'));
        // } catch(\Exception $e) {
        //     return redirect()->route('error');
        // } catch(\Throwable $e) {
        //     return redirect()->route('error');
        // }
    }

    
    public function show($id)
    {
        // try {
            if(is_null($id) or empty($id)) {
                return redirect()->route('dashboard')->with('error', 'Invalid Appointment');
            }

            $appointment = DB::table('appointments as a')
                    ->select('a.id', 'a.hangout_link', DB::raw("IFNULL(a.patient_name, CONCAT(u1.fname, ' ', u1.lname)) as patient_name"), 'a.date', 'a.appointment_number', 'a.status', DB::raw("DATE_FORMAT(a.time_from, '%r') as start_time"), DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"),
                    'pa.tax', 'pa.status as payment_status', 'pa.amount', 'u.dob as patient_dob', 'u.gender as patient_gender', 'u.image', DB::raw("IFNULL(a.patient_dob, u1.dob) as patient_dob"), DB::raw("IFNULL(a.patient_gender, u1.gender) as patient_gender"))
                    ->leftjoin('doctors as d', 'd.id', '=', 'a.doctor')
                    ->leftjoin('users as u', 'u.id', '=', 'd.user')
                    ->leftjoin('payments as pa', 'pa.id', '=', 'a.payment')
                    ->leftjoin('patients as p', 'p.id', '=', 'a.patient')
                    ->leftjoin('users as u1', 'u1.id', '=', 'p.user')
                    ->where('a.id', $id)->first();
            
            if ($appointment->status == "Confirmed") {
                return view('patients.appointments.show', compact('appointment'));
            } else {
                return Redirect::to('/checkout/appointment/'.$appointment->id);
            }
            
        // } catch(\Exception $e) {
        //     return redirect()->route('error');
        // } catch(\Throwable $e) {
        //     return redirect()->route('error');
        // }
    }

    public function cancelAppointment($id)
    {
        if(is_null($id) or empty($id)) {
            return redirect()->route('dashboard')->with('error', 'Invalid Appointment');
        }
        try {
            $appointment = Appointment::find($id);
            $appointment->status = 'Cancelled-by-Admin';
            $appointment->save();

            Session::flash('message', 'Appointment has been cancelled');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while cancelling Appointment');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while cancelling Appointment');
        }
        return redirect()->route('appointments');
    }

    public function createEvent()
    {
             
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
