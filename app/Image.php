<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App
 */
class Image extends Model
{
	/**
	 * @var array
	 */
	protected $fillable
		= [
			'good_id',
			'is_main',
			'url'
		];

	/**
	 * 首页滑动图片
	 * @return array
	 */
	public function slider()
	{
		$res = Image::where('is_main', 1)->get();

		$data = [];
		foreach ($res as $row) {
			$data[] = [
				'id' => $row->good_id,
				'url' => $row->url
			];
		}

		return $data;
	}
}
