<link rel="stylesheet" type="text/css" href="{{ route('debugbar.assets', ['css', 'vendor.min.css']) }}">
<link rel="stylesheet" type="text/css" href="{{ route('debugbar.assets', ['css', 'debugbar.css']) }}">
<script type="text/javascript" src="{{ route('debugbar.assets', ['js', 'vendor.min.js']) }}"></script>
<script type="text/javascript" src="{{ route('debugbar.assets', ['js', 'debugbar.js']) }}"></script>

<div class="debugbar">
	<div class="debugbar_logo"><i class="icon-rocket"></i></div>
  	<div class="debugbar_tab">
  	@foreach($collector as $row)
  		<button class="debugbar_tablinks" id="{{ $name = $row->name() }}">
  			<i class="{{ $row->icon() }}"></i> {{ ucfirst($name) }}
  		</button>
  	@endforeach
	</div>

	<div class="debugbar_content">
	@foreach($collector as $row)
		<div id="query" class="debugbar_tabcontent">
  			<h3>{{ $row->name() }}</h3>
  			<p>query is the capital city of England.</p>
		</div>
	@endforeach
	</div>
</div>