<table>
	<tbody>
		<tr>
			<td>Memory Limit</td>
			<td>{{ byteformatter(trim(ini_get('memory_limit'), 'M') * 1024 * 1024) }}</td>
		</tr>
		<tr>
			<td>Memory Usage</td>
			<td>{{ byteformatter(memory_get_usage(true)) }}</td>
		</tr>
		<tr>
			<td style="width: 12em">Peak Memory Usage</td>
			<td>{{ byteformatter(memory_get_peak_usage(true)) }}</td>
		</tr>
	</tbody>
</table>