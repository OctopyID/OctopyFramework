<div id="octopy-{{ strtolower($collector->name) }}" class="tab active">
    <h2>Queries</h2>
    <table>
        <tbody>
            @foreach($data->query as $query)
            <tr>
                <td>{{ $query }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
