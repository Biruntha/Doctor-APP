<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Session;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-sites', ['only' => [
            'index',
            'updateSetting',
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $settings = Setting::all();
        if (is_null($settings) or empty($settings)) {
            Session::flash('error', 'No setting details found');
            return redirect()->back();
        } else {
            $pixel = Setting::where('s_key', 'conversion_api_pixel_id')->first();
            if (!is_null($pixel)) {
                $conversion_api_pixel_id = $pixel->s_value;
            }
            $con_accesstoken = Setting::where('s_key', 'conversion_api_accesstoken')->first();
            if (!is_null($con_accesstoken)) {
                $conversion_api_accesstoken = $con_accesstoken->s_value;
            }
            $con_event_code = Setting::where('s_key', 'conversion_api_test_event_code')->first();
            if (!is_null($con_event_code)) {
                $conversion_api_test_event_code = $con_event_code->s_value;
            }
            $ahref_client_id = Setting::where('s_key', 'ahrefs_client_id')->first();
            if (!is_null($ahref_client_id)) {
                $ahrefs_client_id = $ahref_client_id->s_value;
            }
            $ahref_client_secret = Setting::where('s_key', 'ahrefs_client_secret')->first();
            if (!is_null($ahref_client_secret)) {
                $ahrefs_client_secret = $ahref_client_secret->s_value;
            }
            $ahref_redirect_uri = Setting::where('s_key', 'ahrefs_redirect_uri')->first();
            if (!is_null($ahref_redirect_uri)) {
                $ahrefs_redirect_uri = $ahref_redirect_uri->s_value;
            }

            $pay_client_id = Setting::where('s_key', 'paypal_client_id')->first();
            if (!is_null($pay_client_id)) {
                $paypal_client_id = $pay_client_id->s_value;
            }
            $pay_secret = Setting::where('s_key', 'paypal_secret')->first();
            if (!is_null($pay_secret)) {
                $paypal_secret = $pay_secret->s_value;
            }
            $pay_mode = Setting::where('s_key', 'paypal_mode')->first();
            if (!is_null($pay_mode)) {
                $paypal_mode = $pay_mode->s_value;
            }

            $order_flag_reasons = Setting::where('s_key', 'order_flag_reasons')->first();
            if (!is_null($order_flag_reasons)) {
                $order_flag_reasons = $order_flag_reasons->s_value;
            }

            $order_disapprove_reasons = Setting::where('s_key', 'order_disapprove_reasons')->first();
            if (!is_null($order_disapprove_reasons)) {
                $order_disapprove_reasons = $order_disapprove_reasons->s_value;
            }

            $order_cancel_reasons = Setting::where('s_key', 'order_cancel_reasons')->first();
            if (!is_null($order_cancel_reasons)) {
                $order_cancel_reasons = $order_cancel_reasons->s_value;
            }

            $inquiry_reasons = Setting::where('s_key', 'inquiry_reasons')->first();
            if (!is_null($inquiry_reasons)) {
                $inquiry_reasons = $inquiry_reasons->s_value;
            }

            $last_updated_site_id = Setting::where('s_key', 'last_updated_site_id')->first();
            if (!is_null($last_updated_site_id)) {
                $last_updated_site_id = $last_updated_site_id->s_value;
            }

            $last_updated_task_id = Setting::where('s_key', 'last_updated_task_id')->first();
            if (!is_null($last_updated_task_id)) {
                $last_updated_task_id = $last_updated_task_id->s_value;
            }

            $site_or_task_count = Setting::where('s_key', 'site_or_task_count')->first();
            if (!is_null($site_or_task_count)) {
                $site_or_task_count = $site_or_task_count->s_value;
            }
            
            return view('admin.settings.settings')
                    ->with('conversion_api_pixel_id', $conversion_api_pixel_id)
                    ->with('conversion_api_accesstoken', $conversion_api_accesstoken)
                    ->with('conversion_api_test_event_code', $conversion_api_test_event_code)
                    ->with('ahrefs_client_id', $ahrefs_client_id)
                    ->with('ahrefs_client_secret', $ahrefs_client_secret)
                    ->with('ahrefs_redirect_uri', $ahrefs_redirect_uri)
                    ->with('paypal_client_id', $paypal_client_id)
                    ->with('paypal_secret', $paypal_secret)
                    ->with('paypal_mode', $paypal_mode)
                    ->with('order_flag_reasons', $order_flag_reasons)
                    ->with('order_disapprove_reasons', $order_disapprove_reasons)
                    ->with('order_cancel_reasons', $order_cancel_reasons)
                    ->with('inquiry_reasons', $inquiry_reasons)
                    ->with('last_updated_site_id', $last_updated_site_id)
                    ->with('last_updated_task_id', $last_updated_task_id)
                    ->with('site_or_task_count', $site_or_task_count);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(Request $request)
    {
        try {
            $pixel = Setting::where('s_key', 'conversion_api_pixel_id')->first();
            if (!is_null($pixel)) {
                $pixel->s_value = $request->pixelid;
                $pixel->save();
            }
            $con_accesstoken = Setting::where('s_key', 'conversion_api_accesstoken')->first();
            if (!is_null($con_accesstoken)) {
                $con_accesstoken->s_value = $request->con_accesstoken;
                $con_accesstoken->save();
            }
            $con_event_code = Setting::where('s_key', 'conversion_api_test_event_code')->first();
            if (!is_null($con_event_code)) {
                $con_event_code->s_value = $request->event_code;
                $con_event_code->save();
            }
            $ahref_client_id = Setting::where('s_key', 'ahrefs_client_id')->first();
            if (!is_null($ahref_client_id)) {
                $ahref_client_id->s_value = $request->ahref_client_id;
                $ahref_client_id->save();
            }
            $ahref_client_secret = Setting::where('s_key', 'ahrefs_client_secret')->first();
            if (!is_null($ahref_client_secret)) {
                $ahref_client_secret->s_value = $request->ahref_client_secret;
                $ahref_client_secret->save();
            }
            $ahref_redirect_uri = Setting::where('s_key', 'ahrefs_redirect_uri')->first();
            if (!is_null($ahref_redirect_uri)) {
                $ahref_redirect_uri->s_value = $request->ahref_redirect_uri;
                $ahref_redirect_uri->save();
            }

            $paypal_client_id = Setting::where('s_key', 'paypal_client_id')->first();
            if (!is_null($paypal_client_id)) {
                $paypal_client_id->s_value = $request->paypal_clientid;
                $paypal_client_id->save();
            }
            $paypal_secret = Setting::where('s_key', 'paypal_secret')->first();
            if (!is_null($paypal_secret)) {
                $paypal_secret->s_value = $request->paypal_secret;
                $paypal_secret->save();
            }
            $paypal_mode = Setting::where('s_key', 'paypal_mode')->first();
            if (!is_null($paypal_mode)) {
                $paypal_mode->s_value = $request->paypal_mode;
                $paypal_mode->save();
            }
            $order_flag_reasons = Setting::where('s_key', 'order_flag_reasons')->first();
            if (!is_null($order_flag_reasons)) {
                $order_flag_reasons->s_value = $request->order_flag_reasons;
                $order_flag_reasons->save();
            }
            $order_disapprove_reasons = Setting::where('s_key', 'order_disapprove_reasons')->first();
            if (!is_null($order_disapprove_reasons)) {
                $order_disapprove_reasons->s_value = $request->order_disapprove_reasons;
                $order_disapprove_reasons->save();
            }
            $order_cancel_reasons = Setting::where('s_key', 'order_cancel_reasons')->first();
            if (!is_null($order_cancel_reasons)) {
                $order_cancel_reasons->s_value = $request->order_cancel_reasons;
                $order_cancel_reasons->save();
            }
            $inquiry_reasons = Setting::where('s_key', 'inquiry_reasons')->first();
            if (!is_null($inquiry_reasons)) {
                $inquiry_reasons->s_value = $request->inquiry_reasons;
                $inquiry_reasons->save();
            }
            $last_updated_site_id = Setting::where('s_key', 'last_updated_site_id')->first();
            if (!is_null($last_updated_site_id)) {
                $last_updated_site_id->s_value = $request->last_site_id;
                $last_updated_site_id->save();
            }
            $last_updated_task_id = Setting::where('s_key', 'last_updated_task_id')->first();
            if (!is_null($last_updated_task_id)) {
                $last_updated_task_id->s_value = $request->last_task_id;
                $last_updated_task_id->save();
            }
            $site_or_task_count = Setting::where('s_key', 'site_or_task_count')->first();
            if (!is_null($site_or_task_count)) {
                $site_or_task_count->s_value = $request->task_count;
                $site_or_task_count->save();
            }
            
            Session::flash('message', 'Settings has been successfully updated');
            return redirect()->back();
        } catch(\Exception $e) {
            Session::flash('error', 'Error while updating Settings');
            return redirect()->back();
        } catch(\Throwable $e) {
            Session::flash('error', 'Error while updating Settings');
            return redirect()->back();
        }
    }
}
