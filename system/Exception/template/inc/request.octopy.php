@php($request = $app['request'])
<table>
	<tbody>
		<tr>
			<td style="width: 10em">Path</td>
			<td>{{ $request->uri() }}</td>
		</tr>
		<tr>
			<td>Method</td>
			<td>{{ $request->method() }}</td>
		</tr>
		<tr>
			<td>Server Address</td>
			<td>{{ $request->ip(true) }}:{{ $request->port() }}</td>
		</tr>
		<tr>
			<td style="width: 10em">Ajax Request</td>
			<td>{{ $request->ajax() ? 'Yes' : 'No' }}</td>
		</tr>
		<tr>
			<td>Secure</td>
			<td>{{ $request->secure() ? 'Yes' : 'No' }}</td>
		</tr>
		<tr>
			<td>User Agent</td>
			<td>{{ $request->uagent() }}</td>
		</tr>

	</tbody>
</table>

@php($empty = true)
@foreach (['_GET', '_POST', '_COOKIE'] as $var)
 	@continue(empty($GLOBALS[$var]) || !is_array($GLOBALS[$var]))
	@php($empty = false)
	<h3>${{ $var }}</h3>
	<table style="width: 100%">
		<thead>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($GLOBALS[$var] as $key => $value)
			<tr>
				<td>{{ $key }}</td>
				<td>
					@if (!is_array($value) && !is_object($value))
						{{ $value }}
					@else
						{{ print_r($value, true) }}
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endforeach

@if ($empty)
	<div class="alert">No $_GET, $_POST, or $_COOKIE information to show.</div>
@endif

@php($headers = $request->header())
@if (!empty($headers))
<h3>Header</h3>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($headers as $name => $value)
		@continue(empty($value))
		<tr>
			<td>{{ $name }}</td>
			<td>{{ $value }}</td>
		<tr>
	@endforeach
	</tbody>
</table>
@endif