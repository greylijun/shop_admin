<?php

namespace App\Http\Controllers\Api;

use App\Good;
use App\Image;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api
 */
class HomeController
{
	/**
	 * @return array
	 */
	public function slider()
	{
		$imageModel = new Image;
		$data = $imageModel->slider();
		return $data;
	}

	/**
	 * @return array
	 */
	public function typeScroll()
	{
		$goodModel = new Good();
		$good_id = $goodModel->listGroupByType();
		$image = [];
		foreach ($good_id as $key => $val) {
			foreach ($val as $row) {
				$res = Image::where('good_id', $row)->first();
				if ($res) {
					$image[$key][] = [
						'url' => $res->url,
						'id' => $res->id,
						'good_id' => $row
					];
				}
			}
		}
		return $image;
	}
}