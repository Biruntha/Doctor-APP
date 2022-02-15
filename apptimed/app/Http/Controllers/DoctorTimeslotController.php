<?php

namespace App\Http\Controllers;

use App\Models\DoctorTimeslot;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;
use stdClass;
use DateTime;
use Log;

class DoctorTimeslotController extends Controller
{
    public function myCalender(Request $request)
    {
        try {
            $year  = $request->year;
            $month = $request->month;
            if (is_null($year) or empty($year)) {
                $year = date("Y", time());
            }
            if (is_null($month) or empty($month)) {
                $month = date("m", time());
            }
            $nextMonth = $month == 12 ? 1 : intval($month) + 1;
            $nextYear = $month == 12 ? intval($year) + 1 : $year;
            $preMonth = $month == 1 ? 12 : intval($month) - 1;
            $preYear = $month == 1 ? intval($year) - 1 : $year;

            /********************* PROPERTY ********************/
            $dayLabels = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
            $currentYear = $year;
            $currentMonth = $month;
            $daysInMonth = self::daysInMonth($month, $year);
            $weeksInMonth = self::weeksInMonth($month, $year);
            $firstDayOfTheWeek = date('N', strtotime($currentYear . '-' . $currentMonth . '-01'));

            $doctor = Doctor::where('user', Auth::user()->id)->first();

            $timeSlots = DB::table('doctor_timeslots as dt')
                ->select('dt.date', DB::raw('TIME_FORMAT(SUM(ABS(TIMEDIFF(dt.time_to, dt.time_from))),"%HH:%im") AS total_hours'))
                ->where('dt.doctor', $doctor->id)
                ->groupBy('dt.date')->get();
            $day_total_hours = array();
            foreach ($timeSlots as $timeSlot) {
                $day_total_hours[$timeSlot->date] = $timeSlot->total_hours;
            }

            $appointments = DB::table('appointments AS a')
                ->select('dt.date', DB::raw('COUNT(*) AS appointment_count'))
                ->leftjoin('doctor_timeslots AS dt', 'dt.id', '=', 'a.doctor_timeslot')
                ->where('dt.doctor', $doctor->id)
                ->groupBy('dt.date')->get();
            $day_appointments = array();
            foreach ($appointments as $appointment) {
                $day_appointments[$appointment->date] = $appointment->appointment_count;
            }
            
            $month_hours = DB::table('doctor_timeslots')
                ->select('date', DB::raw('TIME_FORMAT(SEC_TO_TIME(SUM((TIME_TO_SEC(time_to) - TIME_TO_SEC(time_from)))),"%HH:%im") AS t_hours'))
                ->where('doctor', $doctor->id)
                ->where(DB::raw('MONTH(date)'), $currentMonth)
                ->where(DB::raw('YEAR(date)'), $currentYear)->first();
            $month_total_hours = "0H";
            if (!is_null($month_hours) && !empty($month_hours)) {
                $month_total_hours = $month_hours->t_hours;
            }

            $currentDate = $currentYear  . '-' . substr("0". ($currentMonth), -2);
            return view('doctor.timeslots.calender', compact('day_appointments', 'currentDate', 'day_total_hours', 'month_total_hours','nextMonth', 'nextYear', 'preMonth',
                'preYear', 'dayLabels', 'daysInMonth', 'weeksInMonth', 'currentYear', 'currentMonth', 'firstDayOfTheWeek'
            ));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    /**
     * calculate number of weeks in a particular month
     */
    private function weeksInMonth($month, $year)
    {

        if (is_null($year)) {
            $year =  date("Y", time());
        }

        if (is_null($month)) {
            $month = date("m", time());
        }

        // find number of days in this month
        $daysInMonths = self::daysInMonth($month, $year);
        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);
        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));
        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

        if ($monthEndingDay < $monthStartDay) {
            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function daysInMonth($month, $year)
    {

        if (is_null($year))
            $year =  date("Y", time());
        if (is_null($month))
            $month = date("m", time());

        return date('t', strtotime($year . '-' . $month . '-01'));
    }

    public function getAllTimeslots(Request $request) {
        try {
            $startDateFilter = $request->startDate;
            $endDateFilter = $request->endDate;
            $doctor = Doctor::where('user', Auth::user()->id)->first();
            if(is_null($doctor) or empty($doctor)) {
                return redirect()->route('dashboard')->with('error', 'You are not a doctor');
            }

            $appointments = DB::raw("(SELECT a.doctor_timeslot, COUNT(*) AS appointment_count, SUM(a.doctor_fees) AS amount FROM appointments AS a GROUP BY doctor_timeslot) as appointments");

            $data = DB::table('doctor_timeslots as dt')
                    ->select('dt.*', "appointments.appointment_count", "appointments.amount")
                    ->leftjoin($appointments, 'doctor_timeslot', '=', 'dt.id')
                    ->where('dt.doctor', $doctor->id);

            if(isset($endDateFilter) && $endDateFilter != "") {
                $data = $data->whereDate('dt.date','<=',$endDateFilter);
            }

            if(isset($startDateFilter) && $startDateFilter != "") {
                $data = $data->whereDate('dt.date','>=',$startDateFilter);
            }

            $data = $data->paginate(20);

            return view('doctor.timeslots.index', compact('data', 'startDateFilter', 'endDateFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function getRecentTimeslots($doc_code) {
        try {
            if(is_null($doc_code) or empty($doc_code)) {
                return redirect()->route('dashboard')->with('error', 'Invalid doctor');
            }
            $currentDate = new DateTime();
            $currentDate = $currentDate->format('Y-m-d');
            
            $patient = Patient::where('user', Auth::user()->id)->first();
            if(is_null($patient) or empty($patient)) {
                return redirect()->route('dashboard')->with('error', 'You are not a patient');
            }

            $appointments = DB::raw("(SELECT a.doctor_timeslot, COUNT(*) AS appointment_count, MAX(a.time_from) as max_time FROM appointments AS a WHERE a.status = 'Confirmed' GROUP BY doctor_timeslot) as appointments");

            $data = DB::table('doctor_timeslots as dt')
                    ->select('dt.*', 'dt.code as timeslot_code', 'u.code', "appointments.appointment_count", DB::raw("DATE_FORMAT(dt.time_from, '%r') as time_from"), DB::raw("DATE_FORMAT(ADDTIME(appointments.max_time, SEC_TO_TIME(d.timeslot_duration*60)), '%r') as next_available_time"))
                    ->leftjoin($appointments, 'doctor_timeslot', '=', 'dt.id')
                    ->leftjoin('doctors as d', 'd.id', '=', 'dt.doctor')
                    ->leftjoin('users as u', 'u.id', '=', 'd.user')
                    ->where('u.code', $doc_code)
                    ->whereDate('dt.date', '>=', $currentDate)
                    ->orderby("date", 'ASC')->limit(7)->get();
            $tomorrowDate = new DateTime();
            $tomorrowDate->modify('+1 day');
            $tomorrowDate = $tomorrowDate->format('Y-m-d');
                        
            return view('patients.doctors.timeslots', compact('data', 'currentDate', 'tomorrowDate'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function addTimeslot(Request $request)
    {
        try {
            $rules = array(
                'doctor_date' => 'required',
                'doctor_time_from' => 'required',
                'doctor_time_to' => 'required',
            );

            $customMessages = [
                'doctor_date.required' => 'Timeslot Start Date need to be selected',
                'doctor_time_from.required' => 'Start time need to be selected',
                'doctor_time_to.required' => 'End time need to be selected',
            ];

            $isRecurring = $request->recurring;
            $start_date = $request->doctor_date;
            $end_date = $start_date;
            $available_day_label = array();
            if (isset($isRecurring) && $isRecurring == true) {
                $rules['doctor_end_date'] = 'required';
                $rules['week_days'] = 'required';

                $customMessages['doctor_end_date.required'] = 'Timeslot End Date need to be selected';
                $customMessages['week_days.required'] = 'Scheduling days need to be selected';
                $available_day_label = $request->week_days;
                $end_date = $request->doctor_end_date;
            }

            $start_time = $request->doctor_time_from;
            $end_time = $request->doctor_time_to;
            $no_of_appointments = $request->no_of_appointments;

            $validator = Validator::make($request->all(), $rules, $customMessages);
            $doctor = Doctor::where('user', Auth::user()->id)->first();

            $begin = new DateTime($start_date);
            $end   = new DateTime($end_date);
            $valid_end_date = new DateTime($start_date);

            if ($validator->fails()) {
                $response["msg"] = $validator->messages()->first();
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } else if (empty($doctor) or is_null($doctor)) {
                $response["msg"] = "You are not allowed to add Timeslots";
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } else if ($start_time > $end_time) {
                $response["msg"] = "Start time need to be less than End time";
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } else if (isset($isRecurring) && $isRecurring == true && ($begin >= $end)) {
                $response["msg"] = "End date need to be grater than Start date";
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } else if (isset($isRecurring) && $isRecurring == true && ($valid_end_date->modify('+40 day') < $end)) {
                $response["msg"] = "You can't add timeslots for more than 40 days";
                $response["status"] = "Failed";
                $response["is_success"] = false;
            } else {
                $valid_dates = array();
                $invalid_dates = array();
            
                for($i = $begin; $i <= $end; $i->modify('+1 day')) {
                    $current_date = $i->format("Y-m-d");
                    $day_label = date('l', strtotime($current_date));
                    if ((!isset($isRecurring) or $isRecurring == false) or (isset($isRecurring) && $isRecurring == true && in_array($day_label, $available_day_label))) {
                        $timeSlots = DoctorTimeslot::where('doctor', $doctor->id)->where('date', $current_date)->get();
                        $invalid_timeslots = array();
                        
                        foreach ($timeSlots as $timeSlot) {
                            $time_from = $timeSlot->time_from;
                            $time_to = $timeSlot->time_to;
                            if (!(($time_from > $start_time && $time_from >= $end_time) || ($time_to <= $start_time && $time_to < $end_time))) {
                                array_push($invalid_timeslots, $time_from . " - " . $time_to);
                            }
                        }
                        if (count($invalid_timeslots) > 0) {
                            array_push($invalid_dates, "[" .$current_date ." :- ". join(", ", $invalid_timeslots) . "] ");
                        } else {
                            array_push($valid_dates, $current_date);
                        }
                    }
                }
                if (count($invalid_dates) > 0) {
                    $response["msg"] = nl2br("You are overlapping the following timeslots : \n" . join("\n", $invalid_dates));
                    $response["status"] = "Failed";
                    $response["is_success"] = false;
                } else {
                    foreach ($valid_dates as $valid_date) {
                        $doctor_timeslot = new DoctorTimeslot();
                        $doctor_timeslot->doctor = $doctor->id;
                        $doctor_timeslot->date = $valid_date;
                        $doctor_timeslot->time_from = $start_time;
                        $doctor_timeslot->time_to = $end_time;
                        $doctor_timeslot->max_appointments = $no_of_appointments;
                        $doctor_timeslot->save();

                        $stringUtil = new \App\Services\StringUtils();
                        $doctor_timeslot->code = $stringUtil->randomStringGenerator("t", $doctor_timeslot->id);
                        $doctor_timeslot->save();
                        Log::debug($doctor_timeslot);
                    }
                    $response["msg"] = "Sucessfully Added";
                    $response["status"] = "Sucess";
                    $response["is_success"] = true;
                }
            }
        } catch (\Exception $ex) {
            $response["msg"] = "Operation failed. Please try again.";
            $response["exception"] = $ex->getMessage();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        }
        return $response;
    }

    public function updateTimeslot(Request $request, $id) {
        if(is_null($id) or empty($id)) {
            $response["msg"] = "Invalid Timeslot";
            $response["status"] = "Failed";
            $response["is_success"] = false;
            return $response;
        }

        $rules = array(
            'timeslot_date' => 'required',
            'timeslot_start_time' => 'required',
            'timeslot_end_time' => 'required',
        );

        $customMessages = [
            'timeslot_date.required' => 'Timeslot Date need to be selected',
            'timeslot_start_time.required' => 'Start time need to be selected',
            'timeslot_end_time.required' => 'End time need to be selected',
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $doctor = Doctor::where('user', Auth::user()->id)->first();

        $start_time = $request->timeslot_start_time;
        $end_time = $request->timeslot_end_time;
        $date = $request->timeslot_date;

        if ($validator->fails()) {
            $response["msg"] = $validator->messages()->first();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if (empty($doctor) or is_null($doctor)) {
            $response["msg"] = "You are not allowed to update Timeslots";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if ($start_time > $end_time) {
            $response["msg"] = "Start time need to be less than End time";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else {
            try {
                $timeSlots = DoctorTimeslot::where('doctor', $doctor->id)->where('date', $date)
                                    ->where('id', '<>', $id)->get();
                $invalid_timeslots = array();
                
                foreach ($timeSlots as $timeSlot) {
                    $time_from = $timeSlot->time_from;
                    $time_to = $timeSlot->time_to;
                    if (!(($time_from > $start_time && $time_from >= $end_time) || ($time_to <= $start_time && $time_to < $end_time))) {
                        array_push($invalid_timeslots, $time_from . " - " . $time_to);
                    }
                }
                if (count($invalid_timeslots) > 0) {
                    $response["msg"] = nl2br("You are overlapping the following timeslots on ". $date . ": \n" . join("\n", $invalid_timeslots));
                    $response["status"] = "Failed";
                    $response["is_success"] = false;
                } else {
                    $doctorTimeslot = DoctorTimeslot::find($id);
                    $doctorTimeslot->date = $date;
                    $doctorTimeslot->time_from = $start_time;
                    $doctorTimeslot->time_to = $end_time;
                    $doctorTimeslot->max_appointments = $request->timeslot_appointments;
                    $doctorTimeslot->save();

                    $response["msg"] = "Timeslot has been updated successfully";
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

    public function deleteTimeslots(Request $request)
    {
        $rules = array(
            'delete_start_date' => 'required',
            'delete_end_date' => 'required',
        );

        $customMessages = [
            'delete_start_date.required' => 'Start Date need to be selected',
            'delete_end_date.required' => 'End Date need to be selected',
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $doctor = Doctor::where('user', Auth::user()->id)->first();

        $start_date = $request->delete_start_date;
        $end_date = $request->delete_end_date;

        if ($validator->fails()) {
            $response["msg"] = $validator->messages()->first();
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if (empty($doctor) or is_null($doctor)) {
            $response["msg"] = "You are not allowed to delete Timeslots";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else if ($start_date > $end_date) {
            $response["msg"] = "Start Date need to be less than End Date";
            $response["status"] = "Failed";
            $response["is_success"] = false;
        } else {
            try {
                $appointments = DB::raw("(SELECT a.doctor_timeslot, COUNT(*) AS appointment_count FROM appointments AS a GROUP BY doctor_timeslot) as appointments");

                $timeSlots =  DB::table('doctor_timeslots as dt')
                                ->select('dt.*', "appointments.appointment_count")
                                ->leftjoin($appointments, 'doctor_timeslot', '=', 'dt.id')
                                ->where('dt.doctor', $doctor->id)
                                ->whereDate('dt.date', '<=', $end_date)
                                ->whereDate('dt.date', '>=', $start_date)->get();
                $invalid_timeslots = array();
                
                foreach ($timeSlots as $timeSlot) {
                    $time_from = $timeSlot->time_from;
                    $time_to = $timeSlot->time_to;
                    $date = $timeSlot->date;
                    if ($timeSlot->appointment_count > 0) {
                        array_push($invalid_timeslots, $date . " :- [" . $time_from . " - " . $time_to. "]");
                    }
                }
                if (count($invalid_timeslots) > 0) {
                    $response["msg"] = nl2br("You are having appointments on following dates. Please cancel those \n" . join("\n", $invalid_timeslots));
                    $response["status"] = "Failed";
                    $response["is_success"] = false;
                } else {
                    $timeSlots =  DoctorTimeslot::where('doctor', $doctor->id)
                                ->whereDate('date', '<=', $end_date)
                                ->whereDate('date', '>=', $start_date)->delete();

                    $response["msg"] = "Timeslots has been deleted successfully";
                    $response["status"] = "Sucess";
                    $response["is_success"] = true;
                }
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DoctorTimeslot  $doctorTimeslot
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorTimeslot $doctorTimeslot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorTimeslot  $doctorTimeslot
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorTimeslot $doctorTimeslot)
    {
        //
    }

    public function deleteTimeslot($timeslot_id)
    {
        try{
            $doctorTimeslot = DoctorTimeslot::where("id", $timeslot_id)->first();
            if(is_null($doctorTimeslot)) {
                return redirect()->back()->with('error','Invalid Timeslot');
            }
            
            $doctorTimeslot->delete();
            \Session::flash('message','Your Timeslot has been successfully removed');
        } catch(\Exception $ex) {
            return redirect()->back()->with('error','Error while deleting the Timeslot');
        }
        return redirect()->back();
    }
}
