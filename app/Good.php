<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Good
 * @package App
 */
class Good extends Model
{
	/**
	 * @var array
	 */
	protected $fillable
		= [
			'id',
			'name',
			'price',
			'introduction',
			'type_id',
			'detail',
			'inventory'
		];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type()
	{
		return $this->belongsTo(Type::class)->withDefault();
	}

	/**
	 * 按类型分组列表
	 * @return array
	 */
	public function listGroupByType()
	{
		$res = Good::with('type')->get();
		$data = [];
		foreach ($res as $row) {
			$data[$row->type->name][] = $row->id;
		}
		return $data;
	}

	/**
	 * 列表
	 * @return array
	 */
	public function phoneLists()
	{
		$res = Image::with('good')->where('is_main', 1)->get();
		$data = [];
		foreach ($res as $row) {
			$data[] = [
				'id' => $row->good->id,
				'title' => $row->good->name,
				'mainImage' => $row->url,
			];
		}

		return $data;
	}

	/**
	 * 搜索
	 * @param $name
	 * @return array
	 */
	public function search($name)
	{
		$res = Good::whereRaw('instr(name,?)', [$name])->get();
		$data = [];
		foreach ($res as $row) {
			$data[] = [
				'id' => $row->id,
				'name' => $row->name,
			];
		}

		return $data;
	}

	/**
	 * 查看商品详情
	 * @param $id
	 * @return array
	 */
	public function detail($id)
	{
		$res = Good::where('id', $id)->first();
		$data = [];
		if ($res) {
			$image_res = Image::where('good_id', $id)->get();
			$image = [];
			foreach ($image_res as $row) {
				$image[] = [
					'id' => $row->id,
					'url' => $row->url
				];
			}
			$data = [
				'id' => $res->id,
				'name' => $res->name,
				'price' => $res->price,
				'introduction' => $res->introduction,
				'detail' => $res->detail,
				'inventory' => $res->inventory,
				'image' => $image
			];
		}
		return $data;
	}
}
