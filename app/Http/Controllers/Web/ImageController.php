<?php

namespace App\Http\Controllers\Web;


use App\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ImageController
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{
	/**
	 * @param Request $request
	 * @return int
	 */
	public function delete(Request $request)
	{
		$id = $request->get('id');
		return Image::destroy(explode(',', $id));
	}

	/**
	 * @param Request $request
	 * @return string
	 */
	public function setMain(Request $request)
	{
		$id = $request->get('id');
		$status = $request->get('status');

		return Image::findOrFail($id)
			->update(['is_main' => $status, 'updated_at' => Carbon::now()]) ? 'true' : 'false';
	}
}