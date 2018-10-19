<?php

namespace App\Http\Controllers\Web;


use App\Good;
use Illuminate\Support\Facades\DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
	/**
	 * 各类型商品占比图
	 * @return array
	 */
	public function typePie()
	{
		$res = Good::with('type')
			->select(DB::raw('count(id) as num,type_id'))
			->groupBy('type_id')
			->get();
		$data_temp = [];
		$legend = [];
		foreach ($res as $row) {
			$legend[] = $row->type->name;
			$data_temp[] = [
				'value' => $row->num,
				'name' => $row->type->name,
			];
		}

		$data = [
			'legend' => $legend,
			'data' => $data_temp
		];
		return $data;
	}

	/**
	 * 库存前十排行
	 * @return array
	 */
	public function inventoryTopTen()
	{
		$res = Good::select('inventory', 'name')->orderBy('inventory', 'desc')->take(10)->get();
		$data_temp = [];
		$yAxis = [];
		foreach ($res as $row) {
			$yAxis[] = $row->name;
			$data_temp[] = $row->inventory;
		}
		$data = [
			'yAxis' => array_reverse($yAxis),
			'data' => array_reverse($data_temp)
		];
		return $data;
	}
}