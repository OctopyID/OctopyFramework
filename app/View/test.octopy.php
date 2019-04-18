<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
</head>
<body>
	<form method="POST" action="aksi">
		@csrf
		<input type="text" name="test[foo]" value="0">
		<button>Cek</button>
	</form>
</body>
</html>