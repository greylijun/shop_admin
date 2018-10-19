<?php

namespace App\Http\Middleware;

use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class Auth
{
	public function handle($request, Closure $next)
	{
		if (!self::token()) {
			return response()->json(['error' => 'token_not_provided'], 406);
		}

		if (!self::authenticate()) {
			return response()->json(['error' => 'login_timeout'], 401);
		}

		return $next($request);
	}

	/**
	 * 获取 token
	 * @return array|string
	 */
	private static function token()
	{
		return request()->input('_token') ?: request()->header('X-CSRF-TOKEN');
	}

	/**
	 * 由 token 返回用户信息（缓存）
	 * @return bool
	 */
	public static function authenticate()
	{
		$token = self::token();
		// 由token返回用户
		if (Cache::has('user.' . $token)) {
			return Cache::get('user.' . $token);
		}

		return false;
	}

	/**
	 * 验证登录信息，通过后生成 token
	 * @param $credentials
	 * @return bool|string
	 */
	public static function attempt($credentials)
	{
		// 验证账号密码，若正确返回token
		if (!$user = User::where(['user' => $credentials['user']])->first()) {
			return false;
		} else {
			if (Hash::check($credentials['pwd'], $user->password)) {
				// 更新用户表时间
				User::where('id', $user->id)->update([
					'updated_at' => Carbon::now(),
				]);
				$token = sha1($user->id . '.' . $user->user . '.' . env('APP_KEY') . '.' . time());
				$expiresAt = Carbon::now()->addMinutes(env('CACHE_TIMEOUT', 300));
				Cache::put('user.' . $token, $user, $expiresAt);
				$data['token'] = $token;
				$data['grade'] = $user->grade;
				return $data;
			}

			return false;
		}
	}

	/**
	 * 清除 token 及缓存
	 * @return bool
	 */
	public static function clean()
	{
		$token = self::token();
		if (Cache::has('user.' . $token)) {
			return Cache::forget('user.' . $token);
		}

		return false;
	}
}