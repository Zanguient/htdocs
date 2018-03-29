<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>{{ Lang::get('master.datahora') }}</th>
            <th>{{ Lang::get('master.operador') }}</th>
            <th>{{ Lang::get('master.status') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ( isset( $taloes_historico ) )
            @foreach ( $taloes_historico as $talao )
            <tr tabindex="0">
                <td class="data-historico" data-datahora="{{ trim($talao->DATAHORA) }}">{{ date('d/m/Y H:i:s', strtotime($talao->DATAHORA)) }}</td>
                <td class="operador-historico">{{ $talao->OPERADOR_ID }} - {{ $talao->OPERADOR_NOME }}</td>
                <td class="status-historico" data-status="{{ trim($talao->STATUS) }}">{{ $talao->STATUS_DESCRICAO }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>