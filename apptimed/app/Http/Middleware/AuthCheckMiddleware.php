<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Auth;

class AuthCheckMiddleware extends Middleware
{

    public function handle($request, $next)
	{
        // dd(Auth::check());
		if(Auth::check()){
            return redirect(route('dashboard'));
        }
        return $next($request);
	}


}
