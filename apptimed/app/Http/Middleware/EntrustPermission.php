<?php namespace App\Http\Middleware;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Trebol\Entrust
 */

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class EntrustPermission
{
	const DELIMITER = '|';

	protected $auth;

	/**
	 * Creates a new instance of the middleware.
	 *
	 * @param Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $permissions
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permissions)
	{
		if (!is_array($permissions)) {
			$permissions = explode(self::DELIMITER, $permissions);
		}

		if ($this->auth->guest() || !$request->user()->cans($permissions)) {
            switch (Config::get('entrust.type')) {
                case 'api':
                    return response()->json(Config::get('entrust.response-error'),403);
                    break;
                default:
					session()->flash('alert-unauthorized-access-class', 'Your last request has been blocked because of unauthorized access.');
                    return back();
                    break;
            }
		}

		return $next($request);
	}
}
