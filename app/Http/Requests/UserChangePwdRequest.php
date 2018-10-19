<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserChangePwdRequest
 * @package App\Http\Requests
 */
class UserChangePwdRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'password' => 'required',
			'new_password' => 'required',
			'rep_password' => 'required'
		];
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'password.required' => '原始密码 必须输入',
			'new_password.required' => '新密码 必须输入',
			'rep_password.required' => '确认密码 必须输入'
		];
	}
}
