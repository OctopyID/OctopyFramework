@php
	$files = array_map(static function($file) {
		return new \SplFileInfo($file);
	}, get_included_files());
@endphp

<table>
	<thead>
		<th class="center">#</th>
		<th>File Path</th>
		<th>File Size</th>
	</thead>
	<tbody>
		@foreach ($files as $i => $file)
		<tr>
			<td class="center">{{ ++$i }}.</td>
			<td>{{ $file->getPathname() }}</td>
			<td>{{ byteformatter($file->getSize()) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>