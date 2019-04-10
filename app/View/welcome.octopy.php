@parent('parent')
@section('content')

<div id="octopy"></div>
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
			<img src="img/octopy.png" title="Octopy Framework">
		</div>
		<h2>{{ $app->name() }} - {{ $app->version() }}</h2>
		<p>lightweight php framework with laravel look like</p>
	</div>
</div>

@endsection
