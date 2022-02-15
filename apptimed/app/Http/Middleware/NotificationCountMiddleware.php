<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\CustomerSiteSearch;
use App\Models\CustomerSiteSearchField;
use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Exception;

class NotificationCountMiddleware
{
    public function handle($request ,Closure $next)
    {
        $notificationCount=0;

        $notificationCount = Notification::where('to_user',Auth::user()->id)->where('is_read',0)->count();

        \View::share([
            'notificationCount'=>$notificationCount ,
        ]);
        return $next($request);
    }
}
