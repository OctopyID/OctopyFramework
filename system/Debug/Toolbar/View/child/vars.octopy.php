<div id="octopy-{{ strtolower($collector->name) }}" class="tab active">
    @foreach($data->vars as $name => $var)
    <h2>{{ ucfirst($name) }}</h2>
    @if(empty($var))
    <p class="muted">{{ ucfirst($name) }} doesn't seem to be active.</p>
    @elseif($name == 'request')
    @foreach($var as $name => $value)
    @if(is_array($value) || (is_object($value)))
    @continue(empty($value))
    <a href="javascript:void(0)" onclick="OctopyToolbar.toggleDataTable('{{ $name }}'); return false;">
        <h3>{{ $name }}</h3>
    </a>
    <table id="{{ $name }}_table">
        <tbody>
            @foreach($value as $key => $val)
            <tr>
                <td class="debug-bar-width30">{{ $key }}</td>
                <td>{{ $val }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    @else
    <table>
        <tbody>
            @foreach($var as $key => $val)
            <tr>
                <td class="debug-bar-width30">{{ $key }}</td>
                <td>{{ $val }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach
</div>
