<?php

namespace App\Http\Controllers;

use ApiChef\PayHere\Payment;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Wallet;
use stdClass;
use Validator;
use Session;
use Redirect;
use Auth;
use Log;
use DB;
use DateTime;

class CheckoutController extends Controller
{
    public function storePatientDetails(Request $request)
    {
        $rules = array(
            'doctor_timeslot' => 'required',
            'doctor_fees' => 'required',
            'app_fees' => 'required',
        );

        $customMessages = [
            'doctor_timeslot.required' => 'Timeslot cannot be empty',
            'doctor_fees.required' => 'Fees cannot be empty',
            'app_fees.required' => 'App Fees cannot be empty',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            // try {
                $appointments = DB::raw("(SELECT a.doctor_timeslot, COUNT(*) AS appointment_count FROM appointments AS a GROUP BY doctor_timeslot) as appointments");

                $timeslot = DB::table('doctor_timeslots as dt')
                            ->select('dt.doctor', 'd.timeslot_duration', 'dt.date', 'dt.time_from as start_time')
                            ->leftjoin($appointments, 'doctor_timeslot', '=', 'dt.id')
                            ->leftjoin('doctors as d', 'd.id', '=', 'dt.doctor')
                            ->leftjoin('users as u', 'u.id', '=', 'd.user')
                            ->where('dt.id', $request->doctor_timeslot)->first();
                $appointment_no = DB::table('appointments as a')
                                ->select(DB::raw('IFNULL(MAX(0 + a.appointment_number), 0) + 1 as max_appointment_no'))
                                ->where('a.doctor_timeslot', $request->doctor_timeslot)
                                ->where('a.status', 'Confirmed')->first();
               
                $next_available_time = DB::table('appointments as a')
                                ->select(DB::raw("ADDTIME(MAX(a.time_from), SEC_TO_TIME($timeslot->timeslot_duration*60)) as next_available_time"))
                                ->where('a.doctor_timeslot', $request->doctor_timeslot)
                                ->where('a.status', 'Confirmed')->first();
                if (is_null($next_available_time->next_available_time) or empty($next_available_time->next_available_time)) {
                    $next_available_time->next_available_time = $timeslot->start_time;
                }
                $appointment = new Appointment();
                $appointment->patient = $request->patient_id;
                $appointment->patient_name = $request->patient_name;
                $appointment->patient_dob = $request->patient_dob;
                $appointment->patient_gender = $request->patient_gender;
                $appointment->doctor = $timeslot->doctor;
                $appointment->doctor_timeslot = $request->doctor_timeslot;
                $appointment->allocated_time = $timeslot->timeslot_duration;
                $appointment->date = $timeslot->date;
                $appointment->time_from = $next_available_time->next_available_time;
                $appointment->doctor_fees = $request->doctor_fees;
                $appointment->app_fees = $request->app_fees;
                $appointment->appointment_number = $appointment_no->max_appointment_no;
                $appointment->status = 'Pending';
                $appointment->save();

                $pending_appointments = DB::table('appointments as a')
                            ->select('a.*', DB::raw("ADDTIME(a.time_from, SEC_TO_TIME($timeslot->timeslot_duration*60)) as next_available_time"))
                            ->where('a.doctor_timeslot', $request->doctor_timeslot)
                            ->where('a.status', 'Pending')
                            ->where('a.id', '<>', $appointment->id)->get();
                // return $pending_appointments;
                foreach ($pending_appointments as $pen_appointment) {
                    $ex_appointment = Appointment::find($pen_appointment->id);
                    $ex_appointment->time_from = $pen_appointment->next_available_time;
                    $ex_appointment->appointment_number = $pen_appointment->appointment_number + 1;
                    $ex_appointment->save();
                }

                return Redirect::to('/checkout/appointment/'.$appointment->id);
            // } catch(\Exception $e) {
            //     return redirect()->back()->with('error', 'Error while doing the checkout');
            // } catch(\Throwable $e) {
            //     return redirect()->back()->with('error', 'Error while doing the checkout');
            // }
        }
    }

    public function checkoutAppointment($id)
    {
        if (is_null($id) or empty($id)) {
            return redirect()->back()->with('error', 'Invalid Appointment');
        }
        
        // try {
            $appointment = DB::table('appointments as a')
                        ->select('*', DB::raw("DATE_FORMAT(time_from, '%r') as time_from"), DB::raw('doctor_fees+app_fees as fees'))
                        ->where('a.id', $id)->first();

            $doctor = DB::table('doctors as d')
                        ->select(DB::raw('CONCAT(u.fname, " ", u.lname) as name'), 'u.image')
                        ->leftjoin('users as u', 'd.user', '=', 'u.id')
                        ->where('d.id', $appointment->doctor)->first();

            $patient = new stdClass();
            if (!is_null($appointment->patient_name) && !is_null($appointment->patient_dob) && !is_null($appointment->patient_gender)) {
                $patient->patient_name = $appointment->patient_name;
                $patient->patient_dob = $appointment->patient_dob;
                $patient->patient_gender = $appointment->patient_gender;
            } else {
                $patient = DB::table('patients as p')
                        ->select(DB::raw('CONCAT(u.fname, " ", u.lname) as name'), 'u.dob', 'u.gender')
                        ->leftjoin('users as u', 'p.user', '=', 'u.id')
                        ->where('p.id', $appointment->patient)->first();
                $patient->patient_name = $patient->name;
                $patient->patient_dob = $patient->dob;
                $patient->patient_gender = $patient->gender;
            }

            $currentDate = new DateTime();
            $currentDate = $currentDate->format('Y-m-d');
            $tomorrowDate = new DateTime();
            $tomorrowDate->modify('+1 day');
            $tomorrowDate = $tomorrowDate->format('Y-m-d');

            $wallet = DB::table('wallets as w')
                            ->select(DB::raw('SUM(IFNULL(w.amount_in, 0))-SUM(IFNULL(w.amount_out, 0)) as balance'))
                            ->where('w.user', Auth::user()->id)->first();
            $wallet_balance = 0;
            if(!is_null($wallet) && !empty($wallet)) {
                $wallet_balance = $wallet->balance;
            }
            $card_balance = $appointment->fees - $wallet_balance - $appointment->payment_wallet - $appointment->payment_card;

            return view('payments.checkout', compact('appointment', 'doctor', 'patient', 'currentDate', 'tomorrowDate', 'wallet_balance', 'card_balance'));
    // } catch(\Exception $e) {
    //     return redirect()->back()->with('error', 'Error while doing the checkout');
    // } catch(\Throwable $e) {
    //     return redirect()->back()->with('error', 'Error while doing the checkout');
    // }
    }

    public function makePaymentCard(Request $request)
    {
        $rules = array(
            'appointment_id' => 'required',
        );

        $customMessages = [
            'appointment_id.required' => 'Appointment id cannot be empty',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $appointment = Appointment::find($request->appointment_id);

                $payment = Payment::make($appointment, Auth::user(), $appointment->doctor_fees + $appointment->app_fees - $appointment->payment_wallet);
                $payment->tax = 10;
                $payment->save();
                return view('payments.pay-with-card')->with([
                    'payment' => $payment,
                    'appointment' => $appointment,
                ]);
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while doing the checkout');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while doing the checkout');
            }
        }
    }

    public function makePaymentBankSlip(Request $request)
    {
        $rules = array(
            'appointment_id' => 'required',
        );

        $customMessages = [
            'appointment_id.required' => 'Appointment id cannot be empty',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $appointment = Appointment::find($request->appointment_id);
                if (!is_null($appointment) && !empty($appointment)) {
                    return view('payments.pay-with-slip', compact('appointment'));
                } else {
                    return redirect()->route('error');
                }
            } catch(\Exception $e) {
                return redirect()->route('error');
            } catch(\Throwable $e) {
                return redirect()->route('error');
            }
        }
    }

    public function makePaymentWallet(Request $request)
    {
        $rules = array(
            'appointment_id' => 'required',
            'wallet_amount' => 'required',
        );

        $customMessages = [
            'appointment_id.required' => 'Appointment id cannot be empty',
            'wallet_amount.required' => 'Amount cannot be empty',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            $res["msg"] = $validator->messages()->first();
            $res["status"] = "Fail";
            $res["is_success"] = false;
        } else {
            // try {
                $appointment = Appointment::find($request->appointment_id);
                if (!is_null($appointment) && !empty($appointment)) {
                    $wallet = new Wallet();
                    $wallet->user = Auth::user()->id;
                    $wallet->appointment = $appointment->id;
                    $wallet->amount_out = $request->wallet_amount;
                    $wallet->save();

                    $appointment->payment_wallet = $request->wallet_amount;
                    $appointment->save();

                    $paid_amount = $appointment->payment_wallet + $appointment->payment_card;
                    $payable_amount = $appointment->doctor_fees + $appointment->app_fees;
                    if ($paid_amount >= $payable_amount) {
                        $appointment->status = 'Confirmed';
                        $appointment->save();

                        $doctor = DB::table('doctors as d')
                                ->select('u.code')
                                ->leftjoin('users as u', 'u.id', 'd.user')
                                ->where('d.id', $appointment->doctor)->first();

                        $calendar = new \App\Services\GoogleCalendar();
                        $client = $calendar->getClient();
                        if ($client['status'] == 'success') {
                            $response = $calendar->createEvent($appointment->id); 
                            if ($response['status'] == 'success') {
                                $res["msg"] = 'Successfully Added';
                                $res["status"] = "Success";
                                $res["is_success"] = true;
                                $res["id"] = $appointment->id;
                                // return \Redirect::route('appointment-view', ['id'=>$appointment->id]);
                            } else {
                                $res["msg"] = $response['msg'];
                                $res["status"] = "Failed";
                                $res["is_success"] = false;
                                $res["id"] = $doctor->code;
                                // return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $response['msg']);
                            }
                        } else {
                            $res["msg"] = $client['msg'];
                            $res["status"] = "Failed";
                            $res["is_success"] = false;
                            $res["id"] = $doctor->code;
                            // return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $client['msg']);
                        }
                    } else {
                        $res["msg"] = 'Please pay the remaining payment';
                        $res["status"] = "Fail";
                        $res["is_success"] = false;
                        return $res;
                    }
                } else {
                    $res["msg"] = 'Invalid Appointment';
                    $res["status"] = "Fail";
                    $res["is_success"] = false;
                    return $res;
                }
            // } catch(\Exception $e) {
            //     return redirect()->route('error');
            // } catch(\Throwable $e) {
            //     return redirect()->route('error');
            // }
        }
        return $res;
    }

    public function saveBankSlip($id, Request $request)
    {
        $rules = array(
            'bank_slip' => 'required|mimes:pdf,jpg,jpeg,png,bmp',
        );

        $customMessages = [
            'bank_slip.required' => 'Appointment id cannot be empty',
            'bank_slip.mimes' => 'Only jpeg,png, bmp and pdf files are allowed',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                if($request->hasFile('bank_slip')) {
                    $bank_slip = $request->file('bank_slip');
                    $name = $bank_slip->getClientOriginalName();
                    $file_name = pathinfo($name, PATHINFO_FILENAME); 
                    $fileName = $file_name. time().'.'.$bank_slip->extension();
                    $appointment = Appointment::where("id", $id)->first();

                    if($appointment == null) {
                        return \Redirect::route('dashboard')->with('error', "Invalid Appointment");
                    }

                    $bank_slip->move(storage_path('app/public/BankSlips'), $fileName);
                
                    $payment = new Payment();
                    $payment->amount = $appointment->doctor_fees + $appointment->app_fees;
                    $payment->tax = 0;
                    $payment->paid_mode = 'BankSlip';
                    $payment->payment_attachment = $fileName;
                    $payment->currency = 'LKR';
                    $payment->payer_id = Auth::user()->id;
                    $payment->save();

                    $appointment->status = 'Confirmed';
                    $appointment->payment = $payment->id;
                    $appointment->save();
                    
                    $doctor = DB::table('doctors as d')
                            ->select('u.code')
                            ->leftjoin('users as u', 'u.id', 'd.user')
                            ->where('d.id', $appointment->doctor)->first();
                    $calendar = new \App\Services\GoogleCalendar();
                    $client = $calendar->getClient();
                    if ($client['status'] == 'success') {
                        $response = $calendar->createEvent($appointment->id); 
                        if ($response['status'] == 'success') {
                            return \Redirect::route('appointment-view', ['id'=>$appointment->id]);
                        } else {
                            return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $response['msg']);
                        }
                    } else {
                        return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $client['msg']);
                    }
                }
            } catch(\Exception $e) {
                return redirect()->route('error');
            } catch(\Throwable $e) {
                return redirect()->route('error');
            }
        }
    }

    public function success(Request $request)
    {
        try {
            $payment = Payment::findByOrderId($request->get('order_id'));
            $appointment = $payment->payable;

            $appointment->payment = $payment->id;
            $appointment->payment_card = $payment->amount;
            $appointment->save();
            
            $paid_amount = $appointment->payment_wallet + $appointment->payment_card;
            $payable_amount = $appointment->doctor_fees + $appointment->app_fees;
            if ($paid_amount >= $payable_amount) {
                $appointment->status = 'Confirmed';
                $appointment->save();
                
                $doctor = DB::table('doctors as d')
                        ->select('u.code')
                        ->leftjoin('users as u', 'u.id', 'd.user')
                        ->where('d.id', $appointment->doctor)->first();
                $calendar = new \App\Services\GoogleCalendar();
                $client = $calendar->getClient();
                if ($client['status'] == 'success') {
                    $response = $calendar->createEvent($appointment->id); 
                    if ($response['status'] == 'success') {
                        return \Redirect::route('appointment-view', ['id'=>$appointment->id]);
                    } else {
                        return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $response['msg']);
                    }
                } else {
                    return \Redirect::route('patient-timeslots', ['id'=>$doctor->code])->with('error', $client['msg']);
                }
            }
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while scheduling the appointment');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while scheduling the appointment');
        }
    }

    public function cancelled(Request $request)
    {
        $payment = Payment::findByOrderId($request->get('order_id'));
        
        $appointment = $payment->payable;
        dd($appointment);
        // perform the side effects of cancelled payment

        // redirect to payment cancelled page
    }
}