<?php

namespace App\Http\Controllers\Web;


use App\Http\Requests\UserChangePwdRequest;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
	/**
	 * @param UserChangePwdRequest $request
	 * @return \Illuminate\Http\JsonResponse|string
	 */
	public function changePwd(UserChangePwdRequest $request)
	{
		// 判断原密码是否正确
		if (!Hash::check($request->password, self::$user->password)) {
			return response()->json(['message' => '原密码不正确'], 400);
		}

		// 密码长度至少6位
		if (strlen($request->new_password) < 6) {
			return response()->json(['message' => '密码至少输入6位'], 400);
		}
		// 两次密码不相同
		if ($request->new_password != $request->rep_password) {
			return response()->json(['message' => '两次输入的密码不相同，请重试'], 400);
		}
		// 保存密码
		self::$user->forceFill([
			'password' => bcrypt($request->new_password),
		])->save();
		return 'true';
	}
}