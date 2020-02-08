<div id="octopy-{{ strtolower($collector->name) }}" class="tab active">
	<h2>Templates</h2>
	<table>
		<thead>
    		<tr>
    			<th>NAME</th>
    			<th>LOCATION</th>
    			<th>SIZE</th>
    		</tr>
		</thead>
    	<tbody>
        @foreach($data->views as $template)
        	<tr>
            	<td>{{ basename($template) }}</td>
            	<td>{{ $template }}</td>
            	<td>{{ byteformatter(filesize($template)) }}</td>
        	</tr>
        @endforeach
        </tbody>
    </table>
</div>
