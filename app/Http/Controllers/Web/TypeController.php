<?php

namespace App\Http\Controllers\Web;

use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class TypeController
 * @package App\Http\Controllers
 */
class TypeController extends Controller
{

	/**
	 * @param Request $request
	 * @param string $id
	 * @return mixed
	 */
	protected static function validator(Request $request, $id = 'NULL')
	{
		return Validator::make($request->all(), [
			'name' => 'required|unique:types,name,' . $id,
		], [
			'name.required' => '名称 必须输入',
			'name.unique' => '名称已存在 请重新输入'
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
			$filter[] = 'instr()';
			$params[] = $search;
		}

		$res = Type::whereRaw(implode(' and ', $filter), $params)->paginate(8);
		$data_temp = [];
		foreach ($res as $row) {
			$data_temp[] = [
				'id' => $row->id,
				'name' => $row->name,
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
		$res = Type::where('id', $id)->get();
		$data = [];
		foreach ($res as $row) {
			$data = [
				'id' => $row->id,
				'name' => $row->name,
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
			return response()->json($validator->error(), 422);
		}

		return Type::create($post) ? 'true' : 'false';
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse|string
	 */
	public function update(Request $request, $id)
	{
		$validator = self::validator($request, $id);
		if ($validator->fails()) {
			return response()->json($validator->error(), 422);
		}

		return Type::findOrFail($id)
			->update(array_merge($request->all(), ['updated_at' => Carbon::now()])) ? 'true' : 'false';
	}

	/**
	 * @param Request $request
	 * @return int
	 */
	public function delete(Request $request)
	{
		$id = $request->get('id');
		return Type::destroy(explode(',', $id));
	}

	/**
	 * @return array
	 */
	public function lists()
	{
		$res = Type::all();

		$data = [];
		foreach ($res as $row) {
			$data[] = [
				'id' => $row->id,
				'name' => $row->name
			];
		}
		return $data;
	}
}