<div class="debugbar_info">
	{{ count($view) }} Templates Were Rendered
</div>
<div class="debugbar_scroll">
	<table class="debugbar_table">
		<td style="width: 5%;font-weight: bold;">No</td>
		<td style="width: 30%;font-weight: bold;">Name</td>
		<td style="width: 45%;font-weight: bold;">Location</td>
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
				<td><i class="icon-cog"></i>{{ trim(memory($row->info['memory'])) }}</td>
				<td><i class="icon-clock"></i>{{ $row->info['time'] }}s</td>
			</tr>
		@endforeach
	</table>
</div>