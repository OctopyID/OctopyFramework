<div id="octopy-history" class="tab">
	<h2>History</h2>
	<table>
    	<thead>
       		<tr>
            	<th>Datetime</th>
            	<th>Status</th>
            	<th>Method</th>
            	<th>URL</th>
            	<th>Content-Type</th>
            	<th>Ajax</th>
            	<th>Action</th>
        	</tr>
    	</thead>
    	<tbody>
        @foreach($collector->collect() as $json)
            @php
                foreach ($json->vars->response as $key => $contentype) {
                    if($key == 'Content-Type') {
                        break;
                    }
                }
            @endphp

            @if($json->time === $time)
                <tr id="history_{{ $json->time }}" data-active="1" class="current">
            @else
                <tr id="history_{{ $json->time }}" data-active="">
            @endif
        		<td class="debug-bar-width140p">{{ date('Y-m-d H:i:s', $json->time) }}</td>
            	<td>{{ $json->main->status }}</td>
            	<td>{{ $json->main->method }}</td>
            	<td>{{ $json->main->url }}</td>
            	<td>{{ $contentype }}</td>
            	<td>{{ $json->main->ajax ? 'Yes' : 'No' }}</td>
            	<td class="debug-bar-width70p">
            		<button class="octopy-history-load" data-time="{{ $json->time }}">Load</button>
            	</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>