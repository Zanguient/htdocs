    @foreach ($impressoras as $impressora)
        <tr link="{{ url('_11060', $impressora->ID) }}">
            <td class="req-id">{{ $impressora->ID }}</td>
            <td>{{ $impressora->DESCRICAO}}</td>
            <td>{{ $impressora->CODIGO }}</td>
        </tr>
    @endforeach
