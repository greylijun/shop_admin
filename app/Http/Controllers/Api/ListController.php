<?php

namespace App\Http\Controllers\Api;


use App\Good;
use Illuminate\Http\Request;

/**
 * Class ListController
 * @package App\Http\Controllers\Api
 */
class ListController
{
	/**
	 * 搜索
	 * @param Request $request
	 * @return array
	 */
	public function search(Request $request)
	{
		$name = $request->get('name');

		$data = [];
		if ($name) {
			$goodModel = new Good();
			$data = $goodModel->search($name);
		}
		return $data;
	}
}