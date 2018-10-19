<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GoodImageListRequest
 * @package App\Http\Requests
 */
class GoodImageListRequest extends FormRequest
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
			'good_id' => 'required'
		];
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'good_id.required' => '商品id 必须输入'
		];
	}
}
