<div id="octopy-{{ strtolower($collector->name) }}" class="tab active">
	<h2>Route</h2>
    <h3>Matched Route</h3>
	<table>
        <tbody>
            <tr>
                <td class="debug-bar-width30 debug-bar-txt-bold">Name</td>
                <td>{{ $data->route->name !== '' ? $data->route->name : '-' }}</td>
            </tr>
            <tr>
                <td class="debug-bar-width30 debug-bar-txt-bold">Target</td>
                <td>{{ $data->route->uri }}</td>
            </tr>
            <tr>
                <td class="debug-bar-width30 debug-bar-txt-bold">Method</td>
                <td>{{ implode(' & ', $data->route->method) }}</td>
            </tr>
            <tr>
                <td class="debug-bar-width30 debug-bar-txt-bold">Controller</td>
                <td>{{ $data->route->controller }}</td>
            </tr>
            <tr>
                <td class="debug-bar-width30 debug-bar-txt-bold">Middleware</td>
                @if(! empty($data->route->middleware))
                    @php($no = 1)
                    <td>{{ $no }}. {{ $data->route->middleware[0] }}</td>
                    @foreach(array_slice($data->route->middleware, 1) as $middleware)
                        <tr>
                            <td></td>
                            <td>{{ ++$no }}. {{ $middleware }}</td>
                        </tr>
                    @endforeach
                @else
                    -
                @endif
            </tr>
        </tbody>
    </table>
</div>
