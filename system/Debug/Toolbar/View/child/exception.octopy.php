<div id="octopy-{{ strtolower($collector->name) }}" class="tab active">
	<h2>Included Files</h2>
	<table>
		<thead>
    		<tr>
    			<th>Name</th>
    			<th>Location</th>
    			<th>Size</th>
    		</tr>
		</thead>
    	<tbody>
        @foreach($data->files as $file)
        	<tr>
            	<td>{{ basename($file) }}</td>
            	<td>{{ $file }}</td>
            	<td>{{ byteformatter(filesize($file)) }}</td>
        	</tr>
        @endforeach
        </tbody>
    </table>
</div>
