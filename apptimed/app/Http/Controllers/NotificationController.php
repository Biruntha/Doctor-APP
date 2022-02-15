<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $sDateFrom = $request->fsdate;
        $sDateTo = $request->tsdate;
        $search=$request->search;

        $data=Notification::with(["order","performUser"])->orderBy('id', 'DESC')->limit(300);

        if(isset($sDateFrom) && $sDateFrom != "")
        {
            $data = $data->whereDate('created_at','>=',$sDateFrom);
        }


        if(isset($sDateTo) && $sDateTo != "")
        {
            $data = $data->whereDate('created_at','<=',$sDateTo);
        }

        if(isset($search) && $search != "")
        {
            $data = $data->where('order_id','=',$search);
        }

        $data= $data->where('to_user',Auth::user()->id)->get();

        $update=Notification::where('to_user',Auth::user()->id)
        ->where('is_read',0)->update(["is_read" => 1 , "read_at" => Carbon::now()]);

        return view('notifications', compact('data'))->with(["data"=> $data , "sDateFrom" =>$sDateFrom , "sDateTo" =>$sDateTo, "search" => $search]);
    }

    public static function sendNotificationsToAllManagers($msg, $orderId){

    }
}
