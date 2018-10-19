<?php

namespace App\Http\Controllers\Web;


use App\Http\Middleware\Auth;

class Controller extends \App\Http\Controllers\Controller
{
	protected static $user;

	/**
	 * Controller constructor.
	 */
	public function __construct()
	{
		self::$user = Auth::authenticate();
	}
}