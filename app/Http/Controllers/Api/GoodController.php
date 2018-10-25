<?php

namespace App\Http\Controllers\Api;


use App\Good;

class GoodController
{
	public function lists()
	{
		$goodModel = new Good();
		$res = $goodModel->phoneLists();
		return $res;
	}

	public function show($id)
	{
		$goodModel = new Good();
		$res = $goodModel->detail($id);
		$data = [];
		if ($res) {
			$data['data'] = $res;
		}
		return $data;
	}
}