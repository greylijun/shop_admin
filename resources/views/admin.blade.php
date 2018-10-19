<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="renderer" content="webkit">
	<title></title>
	<link rel="stylesheet" href="{{ mix('/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
</head>
<body>
<div id="app"></div>
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/admin.js') }}"></script>
</body>
</html>