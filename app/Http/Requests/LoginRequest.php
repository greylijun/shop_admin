<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests
 */
class LoginRequest extends FormRequest
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
			'user' => 'required',
			'pwd' => 'required'
		];
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'user.required' => '用户名 必须输入',
			'pwd.required' => '密码 必须输入'
		];
	}
}
