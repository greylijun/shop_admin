<?php

namespace App\Http\Controllers\Web;


use App\Good;
use App\Http\Requests\GoodImageListRequest;
use App\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class GoodController
 * @package App\Http\Controllers
 */
class GoodController extends Controller
{
	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected static function validator(Request $request, $id = 'NULL')
	{
		return Validator::make($request->all(), [
			'name' => 'required|unique:goods,name,' . $id,
			'price' => 'required',
			'introduction' => 'required',
			'type_id' => 'required',
			'inventory' => 'required',
		], [
			'name.required' => '名称 必须输入',
			'name.unique' => '名称已存在 请重新输入',
			'price.required' => '价格 必须输入',
			'introduction.required' => '简介 必须输入',
			'type_id.required' => '类型 必须输入',
			'inventory.required' => '库存 必须输入'
		]);
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function index(Request $request)
	{
		$search = $request->get('search');
		$filter = ['1=1'];
		$params = [];

		if ($search) {
			$filter[] = 'instr(name,?)';
			$params[] = $search;
		}
		$res = Good::with('type')
			->whereRaw(implode(' and ', $filter), $params)
			->paginate(8);

		$data_temp = [];
		foreach ($res as $row) {
			$data_temp[] = [
				'id' => $row->id,
				'name' => $row->name,
				'price' => $row->price,
				'introduction' => $row->introduction,
				'type_id' => $row->type->name,
				'detail' => $row->detail,
				'inventory' => $row->inventory
			];
		}

		$data = $res->toArray();
		unset($data['data']);
		$data['data'] = $data_temp;
		return $data;
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function show($id)
	{
		$res = Good::where('id', $id)->get();
		$data = [];
		foreach ($res as $row) {
			$data = [
				'id' => $row->id,
				'name' => $row->name,
				'price' => $row->price,
				'introduction' => $row->introduction,
				'type_id' => $row->type_id,
				'detail' => $row->detail,
				'inventory' => $row->inventory
			];
		}
		return $data;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse|string
	 */
	public function store(Request $request)
	{
		$post = $request->all();
		$validator = self::validator($request);
		if ($validator->fails()) {
			return response()->json($validator->errors(), 422);
		}

		return Good::create($post) ? 'true' : 'false';
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse|string
	 */
	public function update(Request $request, $id)
	{
		$post = $request->all();
		$validator = self::validator($request, $id);
		if ($validator->fails()) {
			return response()->json($validator->errors(), 422);
		}

		return Good::findOrFail($id)
			->update(array_merge($post, ['updated_at' => Carbon::now()])) ? 'true' : 'false';
	}

	/**
	 * 删除
	 * @param Request $request
	 * @return int
	 */
	public function delete(Request $request)
	{
		$id = $request->get('id');
		return Good::destroy(explode(',', $id));
	}

	/**
	 * 图片列表
	 * @param GoodImageListRequest $request
	 * @return array
	 */
	public function imageList(GoodImageListRequest $request)
	{
		$good_id = $request->get('good_id');
		$res = Image::where('good_id', $good_id)->get();
		$data = [];
		foreach ($res as $row) {
			$data[] = [
				'id' => $row->id,
				'is_main' => $row->is_main,
				'name' => $row->name,
				'url' => $row->url
			];
		}
		return $data;
	}
}