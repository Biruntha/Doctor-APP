<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProblemTypeController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\CancellationReasonController;
use App\Http\Controllers\TransferReasonController;
use App\Http\Controllers\DoctorTimeslotController;
use App\Http\Controllers\DoctorSpecializationController;
use App\Http\Controllers\DoctorQualificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['authCheck']], function () {
    Route::get('/login', function () { return view('auth/login'); })->name('login');
});

Route::get('/', [HomeController::class, "getHome"])->name("home");

Route::get('/patient/signup', [AuthUserController::class, 'patientSignup'])->name('patient-signup');
Route::post('/patient/register', [AuthUserController::class, 'patientRegister'])->name('patient-register');

Route::get('/doctor/signup', [AuthUserController::class, 'doctorSignup'])->name('doctor-signup');
Route::post('/doctor/register', [AuthUserController::class, 'doctorRegister'])->name('doctor-register');
Route::get('/verify', [AuthUserController::class, 'verifyEmail'])->name('verify');

Route::post('/login',[AuthUserController::class, 'login'])->name('login-dash');
Route::get('/email-otp',[AuthUserController::class, 'emailOTP'])->name('email-otp');
Route::post('/logout', [AuthUserController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthUserController::class, 'passwordRequest'])->name('password.request');
Route::post('/forgot-password', [AuthUserController::class, 'forgotPassword'])->name('forgot-password');

Route::get('/doctors', [DoctorController::class, "getAllDoctorsByPatient"])->name("doctors");
Route::get('/doctorsBySpecialization', [DoctorController::class, "getAllDoctorsBySpecialization"])->name("doctorsBySpecialization");
Route::get('/doctors/{id}', [DoctorController::class, "show"])->name("doctor-view");

Route::group(['middleware' => ['Authent', 'web','notificationCount']], function () {

    Route::get('/error', function () { return view('errors.error'); })->name('error');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //======================================================================================
    //System Setup Routes
    //======================================================================================
    Route::resource('/manage/roles', RoleUserController::class);
    Route::resource('/manage/users', UserController::class);
    Route::resource('/manage/specializations', SpecializationController::class);
    Route::resource('/manage/problem-types', ProblemTypeController::class);
    Route::resource('/manage/problems', ProblemController::class);
    Route::resource('/manage/cancellation-reasons', CancellationReasonController::class);
    Route::resource('/manage/transfer-reasons', TransferReasonController::class);
    Route::get('/manage/appointments', [AppointmentController::class, "getAllAppointments"])->name("appointments");
    Route::get('/manage/cancel-appointment/{id}', [AppointmentController::class, "cancelAppointment"])->name("cancel-appointment");

    Route::get('/doctor/calender', [DoctorTimeslotController::class, "myCalender"])->name("my-calender");
    Route::get('/doctor/timeslots', [DoctorTimeslotController::class, "getAllTimeslots"])->name("doctor-timeslots");
    Route::post('/doctor/timeslots', [DoctorTimeslotController::class, "addTimeslot"])->name("add-timeslot");
    Route::post('/doctor/update-timeslot/{id}', [DoctorTimeslotController::class, "updateTimeslot"])->name("update-timeslot");
    Route::delete('/doctor/delete-timeslot/{id}', [DoctorTimeslotController::class, "deleteTimeslot"])->name('delete-timeslot');
    Route::post('/doctor/delete-timeslots', [DoctorTimeslotController::class, "deleteTimeslots"])->name('delete-timeslots');
    // Route::post('/doctor/timeslots', [UserController::class, "addTimeslot"])->name("add-timeslot");
    Route::post('/doctor/specialization', [DoctorSpecializationController::class, "addSpecialization"])->name("add-specialization");
    Route::post('/doctor/qualification', [DoctorQualificationController::class, "addQualification"])->name("add-qualification");
    Route::post('/doctor/qualification/{id}', [DoctorQualificationController::class, "updateQualification"])->name('update-qualification');
    Route::post('/doctor/specialization/{id}', [DoctorSpecializationController::class, "updateSpecialization"])->name('update-specialization');
    Route::delete('/doctor/delete-qualification/{id}', [DoctorQualificationController::class, "deleteQualification"])->name('delete-qualification');
    Route::delete('/doctor/delete-specialization/{id}', [DoctorSpecializationController::class, "deleteSpecialization"])->name('delete-specialization');

    //======================================================================================
    //Doctor and Patient Routes
    //======================================================================================
   
    Route::get('/edit-profile', [UserController::class, "editProfile"])->name("edit-profile");
    Route::post('/update-profile', [UserController::class, "updateProfile"])->name("update-profile");
    Route::get('/patient/timeslots/{id}', [DoctorTimeslotController::class, "getRecentTimeslots"])->name("patient-timeslots");
    Route::get('/reserve/{code}', [AppointmentController::class, "getReserveView"])->name("reserve-view");
    Route::get('/my-appointments', [AppointmentController::class, "getMyAppointments"])->name("my-appointments");
    Route::get('/patient/appointments/{id}', [AppointmentController::class, "show"])->name("appointment-view");

    //======================================================================================
    //Doctor Routes
    //======================================================================================
   
    Route::get('/token', [AppointmentController::class, "createEvent"])->name("token");

    //======================================================================================
    //Payment
    //======================================================================================
    Route::get('/checkout/success', [CheckoutController::class, "success"])->name('checkout.success');
    Route::get('/checkout/cancelled', [CheckoutController::class, "cancelled"])->name('checkout.cancelled');
    Route::post('/store-patient-details', [CheckoutController::class, "storePatientDetails"])->name('store-patient-details');
    Route::get('/checkout/appointment/{id}', [CheckoutController::class, "checkoutAppointment"])->name('checkout-appointment');
    Route::post('/pay-with-card', [CheckoutController::class, "makePaymentCard"])->name('pay-with-card');
    Route::post('/pay-with-slip', [CheckoutController::class, "makePaymentBankSlip"])->name('pay-with-slip');
    Route::post('/pay-with-wallet', [CheckoutController::class, "makePaymentWallet"])->name('pay-with-wallet');
    Route::post('/save-bank-slip/{id}', [CheckoutController::class, "saveBankSlip"])->name('save-bank-slip');
    
});