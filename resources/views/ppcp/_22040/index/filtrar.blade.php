
@foreach ( $dados as $item )
<tr tabindex="0" data-remessa="{{ $item->REMESSA_ID }}" data-familia="{{ $item->FAMILIA_ID }}" data-status="{{ trim($item->STATUS) }}" data-status-descricao="{{ trim($item->STATUS_DESCRICAO) }}">
	<td class="t-status {{ (trim($item->STATUS) === '1') ? 'ativo' : 'inativo' }}"></td>
    <td>{{ $item->REMESSA }}</td>
    <td>{{ $item->TIPO_DESCRICAO }}</td>
    <td>{{ $item->FAMILIA_ID }} - {{ $item->FAMILIA_DESCRICAO }}</td>
    <td>{{ date_format(date_create($item->DATA), 'd/m/Y') }}</td>
    <td>
        <a href="{{ url('/_22040/create?remessa=' . $item->REMESSA) }}" class="btn btn-sm btn-warning btn-componente" title="Gerar Remessa de Componente">
			<span class="fa fa-level-up"></span>
		</a>
    </td>
</tr>
@endforeach
