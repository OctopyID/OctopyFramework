@foreach (['_SERVER', '_SESSION'] as $var)
	@continue(empty($GLOBALS[$var]) || !is_array($GLOBALS[$var]))
	<h3>${{ $var }}</h3>
	<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($GLOBALS[$var] as $key => $value)
		@php
			if($var === '_SERVER') {
				$key = strtoupper(str_replace('-', '_', $key));
				
				if(preg_match('/PASSWORD/i', $key)) {
					$value = preg_replace('/(.*)/', str_repeat('*', strlen($value)), $value);
				}
			}
		@endphp
		<tr>
			<td>{{ $key }}</td>
			<td>
				@if (is_string($value))
					@continue($value == '')
					{{ htmlspecialchars(strip_tags($value), ENT_SUBSTITUTE, 'UTF-8') }}
				@else
					{{ print_r($value, true) }}
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@endforeach

@php($constants = get_defined_constants(true)) 
@if (!empty($constants['user']))
@php(asort($constants['user']))
<h3>Constant</h3>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($constants['user'] as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			<td>
				@if (!is_array($value) && !is_object($value))
					{{ $value }}
				@else
					<pre>{{ print_r($value, true) }}</pre>
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@endif