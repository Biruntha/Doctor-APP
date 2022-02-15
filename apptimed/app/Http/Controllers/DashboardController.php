<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use DB;
use Illuminate\Http\Request;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index(Request $request) {
       return view('admin.dashboard');
    }

}