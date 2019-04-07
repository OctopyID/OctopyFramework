@php($files = get_included_files())
<table>
	<thead>
		<th class="center">#</th>
		<th>File Path</th>
	</thead>
	<tbody>
		@foreach ($files as $i => $file)
		<tr>
			<td class="center">{{ ++$i }}.</td>
			<td>{{ $file }}</td>
		</tr>
		@endforeach
	</tbody>
</table>