<!DOCTYPE html>
<html lang="{{ $app->locale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Octopy Framework">
		<meta name="keywords" content="octopy, framework, laravel, lighweight framework">
		<title>Octopy Framework</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito">
		<link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
	</head>
	<body>
		<div class="container">
			<div class="head">
				<div class="buttons">
					<a href="javascript:;" class="close" title="Close"></a>
					<a href="javascript:;" class="minimize" title="Minimize"></a>
					<a href="javascript:;" class="enlarge" title="Enlarge"></a>
				</div>
			</div>
			<div class="content">		
				<div class="logo">
					<img src="{{ url('img/octopy.svg') }}" title="Octopy Framework">
				</div>
				<h2>{{ $app->name() }}</h2>
				<div class="links">
            		<a href="https://framework.octopy.xyz/docs/welcome/">Docs</a>
					<a href="https://framework.octopy.xyz/blog/">News</a>
					<a href="https://github.com/SupianIDz/OctopyFramework">Github</a>
				<div>
			</div>
		</div>
	</body>
</html>