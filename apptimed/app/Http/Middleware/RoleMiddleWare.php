<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Exception;

class RoleMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$roles)
    {
        try{
            if (!Auth::check()) {
                return redirect('/');
            }
            else {
                return $next($request)->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            }
        }catch(\Exception $e){
            $response["msg"] = "Unauthorized Access - Invalid Access";
            $response["status"] = "Failed";
            $response["is_success"] = false;
            return response()->json($response);
        }
    }
}
