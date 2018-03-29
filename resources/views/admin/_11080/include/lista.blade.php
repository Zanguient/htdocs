
@foreach ($dados as $dado)
    <tr link="{{ url('_11080', $dado->ID) }}">
        <td class="relatorio-personalizado-id">{{ $dado->ID }}</td>
        <td>{{ $dado->TITULO}}</td>
    </tr>
@endforeach
