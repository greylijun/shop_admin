<?php

namespace App\Http\Controllers\Web;


use App\Image;
use App\Libraries\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
	public function image(Request $request)
	{
		$upload_file = Upload::uploadFile($request, 'upload/file');
		if ($upload_file['status']) {
			$post['url'] = '/upload/file/' . $upload_file['true_name'];
			$post['good_id'] = $request->good_id;

			return Image::create($post) ? 'true' : 'false';
		} else {
			return response()->json(['message' => $upload_file['message']], 400);
		}
	}
}