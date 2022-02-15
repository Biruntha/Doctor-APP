<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\DoctorSpecialization;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class DoctorController extends Controller
{
    public function getAllDoctorsByAdmin(Request $request) {
        $view = 'admin.doctors.index';
        return self::getAllDoctors($request, $view);
    }

    public function getAllDoctorsByPatient(Request $request) {
        $view = 'patients.doctors.index';
        return self::getAllDoctors($request, $view);
    }

    public function getAllDoctors($request, $view)
    {
        try {
            $searchFilter = $request->searchFilter;
            $specializationFilter = $request->specializationFilter;
            
            $data = DB::table('doctors as d')
                    ->select('d.id', 'u.*', DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"), 'd.*', 'u.created_at as jdate')
                    ->leftjoin('users as u', 'u.id', '=', 'd.user');

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('u.fname', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.lname', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.oname', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.contact', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.email', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.contact_secondary', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('u.gender', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('d.title', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('d.registration_id', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('d.fees', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('d.bank_holder_name', 'LIKE', '%'.$searchFilter.'%');
                });
            }

            if(isset($specializationFilter) && $specializationFilter != "") {
                $specialization = DB::raw("(SELECT ds.doctor as doctor2 FROM doctor_specializations AS ds WHERE ds.specialization = ".$specializationFilter." GROUP BY ds.doctor) as specialization1");

                $data = $data->join($specialization, 'd.id', '=', 'doctor2');
            }
            $data = $data->paginate(20);
            $specializations = Specialization::all();

            return view($view, compact('data', 'searchFilter', 'specializationFilter', 'specializations'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function getAllDoctorsBySpecialization()
    {
        try {
            $specializations = Specialization::all();
            
            $specializationsDoctorMap = array();

            $data = DoctorSpecialization::all();
            foreach ($data as $d) {
                $specialization = $d->specialization;
                $doctor = $d->doctor;
                if (array_key_exists($specialization, $specializationsDoctorMap)) {
                    array_push($specializationsDoctorMap[$specialization], $doctor);
                } else {
                    $doctors = array();
                    array_push($doctors, $doctor);
                    $specializationsDoctorMap[$specialization] = $doctors;
                }
            }
            
            $doctorsMap = array();
            
            $specialization_query = DB::raw("(SELECT ds.doctor AS doctor1, GROUP_CONCAT(' ', s.name) AS specialization FROM doctor_specializations AS ds LEFT JOIN specializations AS s ON s.id = ds.specialization GROUP BY ds.doctor) as specializations");
            $doctors = DB::table('doctors as d')
                    ->select('d.id', 'u.*', DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"), 'd.*', 'specializations.specialization as specializations', 'u.created_at as jdate')
                    ->leftjoin('users as u', 'u.id', '=', 'd.user')
                    ->leftJoin($specialization_query, 'd.id', '=', 'doctor1')->get();
            foreach ($doctors as $doctor) {
                $doctorsMap[$doctor->id] = $doctor;
            }
            // return $specializationsDoctorMap;
            return view('patients.doctors.doctors-by-specialization', compact('specializations', 'specializationsDoctorMap', 'doctorsMap'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function show($code)
    {
        try {
            if(is_null($code) or empty($code)) {
                return redirect()->route('dashboard')->with('error', 'Invalid Doctor');
            }

            $specializations = DB::raw("(SELECT ds.doctor AS doctor1, GROUP_CONCAT(' ', s.name) AS specialization, GROUP_CONCAT(s.id) AS special FROM doctor_specializations AS ds LEFT JOIN specializations AS s ON s.id = ds.specialization GROUP BY ds.doctor) as specializations");

            $data = DB::table('doctors as d')
                ->select('d.*', 'u.code', 'd.title', 'd.registration_id', 'd.timeslot_duration', 'd.fees', 'specializations.specialization as specializations', 'specializations.special as special', 'u.email', 'u.contact', 'u.contact_secondary', 'u.image', 'u.status', 'u.remarks', DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"))
                ->leftjoin('users as u', 'u.id', '=', 'd.user')
                ->leftJoin($specializations, 'd.id', '=', 'doctor1')
                ->where('u.code', $code);

            $doctor = $data->first();

            $doc_specializations = explode(',', $doctor->special);
            $specialized_doctors = DB::table('doctor_specializations as ds')
                ->select('ds.doctor')
                ->whereIn('ds.specialization', $doc_specializations)
                ->where('ds.doctor', '<>', $doctor->id)
                ->get();
            $specialized_doctors_arr = array();
            foreach ($specialized_doctors as $specialized_doctor) {
                array_push($specialized_doctors_arr, $specialized_doctor->doctor);
            }
            $specialized_doctors_arr = array_unique($specialized_doctors_arr);
            $doctors = DB::table('doctors as d')
                ->select('d.*', 'd.title', 'd.registration_id', 'd.timeslot_duration', 'd.fees', 'specializations.specialization as specializations', 'u.code', 'u.email', 'u.contact', 'u.contact_secondary', 'u.image', 'u.status', 'u.remarks', DB::raw("CONCAT(u.fname, ' ', u.lname) as fullname"), 'u.created_at as jdate')
                ->leftjoin('users as u', 'u.id', '=', 'd.user')
                ->leftJoin($specializations, 'd.id', '=', 'doctor1')
                ->whereIn('d.id', $specialized_doctors_arr)->get();

            return view('patients.doctors.show', compact('doctor', 'doctors'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
