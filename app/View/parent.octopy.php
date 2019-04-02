<!DOCTYPE html>
<html lang="{{ $app->locale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Octopy Framework">
		<title>Octopy Framework</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Monaco">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		@yield('content')
		<script type="text/javascript" src="js/particle.js"></script>
		<script type="text/javascript">
			particlesJS.load('octopy', 'js/particle.json', function() {
				console.log('Octopy Loaded');
			});
		</script>
	</body>
</html>