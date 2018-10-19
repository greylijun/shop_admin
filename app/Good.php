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
}
