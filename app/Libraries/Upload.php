<?php

namespace App\Libraries;

/**
 * Class Upload
 * @package App\Libraries
 */
class Upload
{
	/**
	 * 上传文件
	 * @param $request
	 * @param $url
	 * @return array
	 */
	public static function uploadFile($request, $url)
	{
		if (!$request->hasFile('file')) {
			return ['message' => '上传文件为空', 'status' => false];
		}
		$file = $request->file('file');
		if (!$file->isValid()) {
			return ['message' => '文件上传出错', 'status' => false];
		}
		// 获取系统允许的最大上传大小
		$max_size = preg_replace('/M/', '', ini_get('upload_max_filesize')) * 1024 * 1024;
		//文件大小
		$file_size = $file->getSize();
		$extension = $file->getClientOriginalExtension();
		//获取文件名
		$file_name = $file->getClientOriginalName();
		//获取文件格式
		$file_ext = substr(strrchr($file_name, '.'), 1);
		// 上传文件保存的路径
		$public_path = public_path($url);

		//检查文件大小
		if ($file_size > $max_size) {
			return ['message' => '请上传小于' . ini_get('upload_max_filesize') . '的文件', 'status' => false];
		}
		// 如果$public_path（文件保存的目录）不存在
		if (!file_exists($public_path)) {
			// 创建一个目录
			self::_mkdirs($public_path);
		}
		// 文件名
		$trueName = self::_getFileName($extension) . '.' . $file_ext;

		// 移动一个已上传的文件
		if ($file->move($public_path, $trueName) == false) {
			return ['message' => '文件保存失败', 'status' => false];
		}
		// 上传成功返回文件名称
		return ['name' => $file_name, 'true_name' => $trueName, 'status' => true];
	}

	/**
	 * 按指定路径生成目录
	 * @param string $path 目录路径
	 * @return void
	 */
	private static function _mkdirs($path)
	{
		$adir = explode('/', $path);
		$dirlist = '';
		$rootdir = array_shift($adir);
		if (($rootdir != '.' || $rootdir != '..') && !file_exists($rootdir)) {
			@mkdir($rootdir);
		}
		foreach ($adir as $key => $val) {
			if ($val != '.' && $val != '..') {
				$dirlist .= '/' . $val;
				$dirpath = $rootdir . $dirlist;
				if (!file_exists($dirpath)) {
					@mkdir($dirpath);
					@chmod($dirpath, 0777);

					// 目录安全
					$htaccess = "Options -ExecCGI\nAddHandler cgi-script .php .pl .py .jsp .asp .htm .shtml .sh .cgi";
					file_put_contents($dirpath . '/.htaccess', $htaccess);
					file_put_contents($dirpath . '/index.html', '');
				}
			}
		}
	}

	/**
	 * 给上传的文件命名
	 * @param $extension
	 * @return string
	 */
	private static function _getFileName($extension)
	{
		// 根据当前时间加md5加密命名
		$data = date('YmdHis') . '_' . md5($extension);
		return $data;
	}
}