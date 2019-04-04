<link rel="stylesheet" type="text/css" href="{{ route('debugbar.assets', ['css', 'vendor.min.css']) }}">
<link rel="stylesheet" type="text/css" href="{{ route('debugbar.assets', ['css', 'debugbar.css']) }}">
<script type="text/javascript" src="{{ route('debugbar.assets', ['js', 'vendor.min.js']) }}"></script>
<script type="text/javascript" src="{{ route('debugbar.assets', ['js', 'debugbar.js']) }}"></script>

<div class="debugbar">
	<div class="debugbar_logo"><i class="icon-rocket"></i></div>
  	<div class="debugbar_tab">
  		<button class="debugbar_tablinks" id="route"><i class="icon-location"></i> Route</button>
  		<button class="debugbar_tablinks" id="query"><i class="icon-database"></i> Query</button>
  		<button class="debugbar_tablinks" id="view"><i class="icon-code"></i> View</button>
	</div>

	<div class="debugbar_content">
		<div id="query" class="debugbar_tabcontent">
  			<h3>query</h3>
  			<p>query is the capital city of England.</p>
		</div>

		<div id="view" class="debugbar_tabcontent">
			<div class="debugbar_info">
				{{ count($view) }} Templates Were Rendered
			</div>
<div class="debugbar_scroll">
	<table class="debugbar_table">
		<td style="width: 10%;font-weight: bold;">No</td>
		<td style="width: 30%;font-weight: bold;">Name</td>
		<td style="width: 40%;font-weight: bold;">Location</td>
		<td style="width: 10%;font-weight: bold;">Memory Usage</td>
		<td style="font-weight: bold;">Time Elapsed</td>
		@php($i = 0)
		@foreach($view as $row)
			<tr>
				<td>{{ ++$i }}.</td>
				<td>
					{{ basename($row->template) }}
				</td>
				<td>{{ $row->template }}</td>
				<td>{{ memory($row->info['memory']) }}</td>
				<td><i class="icon-clock"></i>{{ $row->info['time'] }} s</td>
			</tr>
		@endforeach
	</table>
</div>
		</div>

		<div id="route" class="debugbar_tabcontent">
  			<h3>route</h3>
  			<p>route is the capital of Japan.</p>
		</div>
	</div>
</div>