<?php

namespace App\Http\Controllers\Web;


use App\Http\Middleware\Auth;
use App\Http\Requests\LoginRequest;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController
{
	/**
	 * @param LoginRequest $request
	 * @return bool|\Illuminate\Http\JsonResponse|string
	 */
	public function login(LoginRequest $request)
	{
		$credentials = $request->only('user', 'pwd');

		if (!$token = Auth::attempt($credentials)) {
			return response()->json(['message' => '账号或密码不正确'], 400);
		}

		return $token;
	}

	/**
	 * 注销
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		if (Auth::clean() === false) {
			return response()->json(['message' => '注销失败'], 400);
		}

		return [];
	}
}