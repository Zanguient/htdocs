
<form class="form-inline">
    <div class="form-group">
        <label for="descricao">C. Custo:</label>
        <input type="text" class="form-control" readonly="" value="{{ $ggf->CCUSTO }} - {{ $ggf->CCUSTO_DESCRICAO }}">
    </div>

    <div class="form-group">
        <label for="descricao">Família:</label>
        <input type="text" class="form-control" readonly="" value="{{ $ggf->FAMILIA_ID }} - {{ $ggf->FAMILIA_DESCRICAO }}">
    </div>

    <div class="form-group">
        <label for="descricao">Período:</label>
        <input type="text" class="form-control" readonly="" value="{{ $ggf->MES_DESCRICAO }}/{{ $ggf->ANO }}">
    </div>
    
    <div class="form-group">
        <label for="descricao" title="Total utilizado">Total Utiliz.:</label>
        <input type="text" class="form-control input-medio-min text-right" readonly="" value="R$ {{ number_format($ggf->VALOR_UTILIZADO, 2, ',', '.') }}">
    </div>
    

<div class="form-group" style="
    margin-top: 32px;
    margin-bottom: 15px;
"><div class="alert alert-danger" style="
    display: inline;
    padding: 7px;
    font-weight: bold;
    ">Atenção: Lançamentos realizados hoje, serão exibidos a partir de amanhã</div></div>
    
    <div class="table-ec" style="height: calc(100vh - 210px);">
        <table class="table table-striped table-bordered table-hover table-condensed lista-obj table-def">
            <thead>
                <tr>
                    <th title="Centro de custo">C. Custo</th>
                    <th title="Data e hora da saída do produto do estoque">Data/Hora</th>
                    <th title="Usuário que realizou a requisição">Usuário</th>
                    <th>Produto</th>
                    <th class="text-center" title="Operação">Oper.</th>
                    <th class="text-right" title="Quantidade">Qtd.</th>
                    <th class="text-right" title="Custo unitário">C. Unit.</th>
                    <th class="text-right" title="Quantidade x Custo Unitário">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $dados as $item )
                <tr>
                    <td>
                        <span style="float: left; width: 65px;">{{ $item->CENTRO_DE_CUSTO }}</span>
                        {{ $item->CENTRO_DE_CUSTO_DESCRICAO }}</td>
                    <td>{{ date('d/m/Y H:i:s', strtotime($item->DATAHORA)) }}</td>
                    <td>{{ $item->USUARIO_ID }} - {{ $item->USUARIO_DESCRICAO }}</td>
                    <td>
                        {{ $item->PRODUTO_ID }} - {{ $item->PRODUTO_DESCRICAO }}
                        @if ( trim($item->REQUISICAO_OBSERVACAO) != '' )
                        <span class="glyphicon glyphicon-info-sign operacao-descricao"
                            style="float: right;"
                            data-toggle="popover" 
                            data-placement="auto" 
                            title="Observação da Requisição"
                            data-element-content="#transacao-{{ $item->TRANSACAO_ID }}"></span>
                        <div id="transacao-{{ $item->TRANSACAO_ID }}" style="display: none">
                            {{ $item->REQUISICAO_OBSERVACAO }}
                        </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span title="{{ $item->TIPO_BAIXA_DESCRICAO }}">
                            {!! ($item->TIPO_BAIXA == 'A') ? '<b style="color:red">' : '' !!}
                                {{ $item->TIPO_BAIXA }}
                            {!! ($item->TIPO_BAIXA == 'A') ? '</b>' : '' !!}
                        </span>
                        -
                        <span title="{{ $item->OPERACAO_DESCRICAO }}">
                            {{ $item->OPERACAO }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($item->QUANTIDADE, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($item->CUSTO_UNITARIO, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($item->QUANTIDADE * $item->CUSTO_UNITARIO, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>