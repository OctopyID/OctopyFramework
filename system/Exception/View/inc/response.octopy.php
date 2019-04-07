<table>
	<tr>
		<td style="width: 15em">Response Status</td>
		<td>{{ $code . ' - ' . ($message != null ? $message : $app->response->reason($code)) }}</td>
 	</tr>
</table>

@php($headers = $app->response->headers(); ksort($headers))
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
		<tr>
			<td>{{ strip_tags($name) }}</td>
			<td>{{ strip_tags(implode(', ', $value)) }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
@endif