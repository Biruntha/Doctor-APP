<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use Auth;
use DateTime;

class HomeController extends Controller
{
    public function getHome() {
        try {
            return view('home');
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }
}
