@extends('master')

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13050.css') }}">
@endsection

@section('titulo')
{{ Lang::get('compras/_13050.titulo') }}
@endsection

@section('conteudo')


<ul class="list-inline acoes">
    <li><button type="button" class="btn btn-success {{ $autorizar_oc ? 'autorizar-oc' : '' }}" data-loading-text="{{ Lang::get('master.gravando') }}" {{ $autorizar_oc ? '' : 'disabled' }}><span class="glyphicon glyphicon-ok"></span> {{ Lang::get('compras/_13050.autorizar-oc') }}</button></li>
    <li><button type="button" class="btn btn-danger {{ $autorizar_oc ? 'negar-oc' : '' }}" data-loading-text="{{ Lang::get('master.gravando') }}" {{ $autorizar_oc ? '' : 'disabled' }}><span class="glyphicon glyphicon-ban-circle"></span> {{ Lang::get('compras/_13050.negar-oc') }}</button></li>
    @if ( $oc->AUTORIZACAO == 2  && $controle == 1 )
    <li><button type="button" class="btn btn-primary enviar-oc" data-loading-text="{{ Lang::get('master.enviando') }}"><span class="glyphicon glyphicon-send"></span> {{ Lang::get('compras/_13050.enviar-oc') }}</button></li>
	<li><button type="button" class="btn btn-warning imprimir-oc" data-loading-text="{{ Lang::get('master.imprimindo') }}"><span class="glyphicon glyphicon-print"></span> {{ Lang::get('compras/_13050.imprimir-oc') }}</button></li>
    @endif
    
    @if ( $oc->REFERENCIA_ID > 0 )
    <li><a href="{{ url($referencia, $oc->REFERENCIA_ID) }}" target="_blank" class="btn btn-default"><span class="glyphicon glyphicon-new-window"></span> {{ $ref_descricao }}</a></li>    
    @endif
    <li><a href="{{ url('_13050') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> {{ Lang::get('master.voltar') }}</a></li>
	<li class="status">
		@if ( $oc->AUTORIZACAO == 1 )
		<div class="label label-autorizacao espera">EM ESPERA</div>
		@endif
		@if ( $oc->AUTORIZACAO == 2 )
		<div class="label label-autorizacao autorizada">AUTORIZADA</div>
		@endif
		@if ( $oc->AUTORIZACAO == 3 )
		<div class="label label-autorizacao negada">NÃO AUTORIZADA</div>
		@endif
	</li>
</ul>
<form class="form-inline info-container" url-redirect="{{ url('sucessoAlterar/_13050') }}">
	<input type="hidden" name="_usuario_nivel" value="{-- $oc->USUARIO_NIVEL --}" />
	<input type="hidden" name="_autorizacao" value="{{ $oc->AUTORIZACAO }}" />
	<input type="hidden" name="_oc_nivel" value="{{ $oc->NIVEL_OC }}" />
    <fieldset>   
		<legend>Historico de Autorizações</legend>
        <table class="table table-striped table-bordered table-hover table-itens">
            <thead>
                <tr>
                    <th>Usuário.</th>
                    <th>Nivel</th>
                    <th>Ação</th>
                    <th>Data/Hora</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
            @foreach ( $historicos as $historico )  
                <tr>
                    <td>{{ $historico->USUARIO_NOME }}</td>
                    <td>{{ $historico->NIVEL_DESCRICAO }}</td>
                    <td>{{ $historico->AUTORIZACAO_DESCRICAO }}</td>
                    <td>{{ date_format(date_create($historico->DATAHORA), 'd/m/Y H:i:s') }}</td>
                    <td>{{ $historico->OBSERVACAO }}</td>
                </tr>
            @endforeach
            </tbody>				      
        </table> 
	</fieldset>        
	<fieldset readonly>
        <div class="row">
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label for="oc">OC:</label>
                <input type="text" name="oc" id="oc" class="form-control input-menor input-bold input-110" value="{{ $id }}" readonly required />
            </div>
			<div class="form-group">
				<label for="estab">Estabelecimento:</label>
				<input type="search" name="estab" id="estab" class="form-control input-maior" value="{{ $oc->ESTABELECIMENTO_ID }} - {{ $oc->ESTABELECIMENTO_DESCRICAO }}"  autocomplete="off" required readonly />
				<input type="hidden" name="_estab_id" value="{{ $oc->ESTABELECIMENTO_ID }}" />
			</div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" name="data" id="data" class="form-control" required value="{{ $oc->DATA }}" readonly/>
            </div>
			<div class="form-group">
                <label for="comprador">Comprador:</label>
                <input type="text" name="comprador" id="comprador" class="form-control" required value="{{ $oc->USUARIO_COMPRADOR_ID }} - {{ $oc->USUARIO_COMPRADOR }}" readonly />
				<input type="hidden" name="_comprador_id" value="{{ $oc->USUARIO_COMPRADOR_ID }}" />
				<input type="hidden" name="_comprador_desc" value="{{ $oc->USUARIO_COMPRADOR }}" />
            </div>
			<div class="form-group">
				<label for="fornecedor-descricao">Fornecedor:</label>
				<input type="search" name="fornecedor_descricao" id="fornecedor-descricao" class="form-control input-maior" value="{{ $oc->FORNECEDOR_ID }} - {{ $oc->FORNECEDOR_DESCRICAO }}"  autocomplete="off" required readonly />
				<input type="hidden" name="_fornecedor_id" value="{{ $oc->FORNECEDOR_ID }}" />
			</div>
        </div>
        <div class="row">
            
			<div class="form-group">
				<label for="transp-descricao">Transportadora:</label>
				<input type="search" name="transp_descricao" id="transp-descricao" class="form-control input-maior" value="{{ $oc->TRANSPORTADORA_ID }} - {{ $oc->TRANSPORTADORA_DESCRICAO }}"  autocomplete="off" required readonly />
				<input type="hidden" name="_transp_id" value="{{ $oc->TRANSPORTADORA_ID }}" />
				<input type="hidden" name="_transp_desc" value="{{ $oc->TRANSPORTADORA_DESCRICAO }}" />
			</div>            
            <div class="form-group">
                <label for="frete">Frete:</label>
                <input type="text" name="frete" id="frete" class="form-control input-medio" required value="{{ $oc->FRETE_ID }} - {{ $oc->FRETE_DESCRICAO }}" readonly />
				<input type="hidden" name="_frete" value="{{ $oc->FRETE_DESCRICAO }}" />
            </div>  
            <div class="form-group">
                <label for="valor-frete">Valor do Frete:</label>
                <div class="input-group left-icon required readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="valor_frete" id="valor-frete" class="form-control input-menor input-text-right valor-frete" required min="0" value="{{ $oc->FRETE_VALOR }}" readonly />
                </div>
            </div>            
            <div class="form-group">
                <label for="pag-forma">Forma de Pagamento:</label>
                <input type="text" name="pag_forma" id="pag-forma" class="form-control input-medio" required value="{{ $oc->PAGAMENTO_FORMA_ID }} - {{ $oc->PAGAMENTO_FORMA_DESCRICAO }}" readonly />
				<input type="hidden" name="_pag_forma_desc" value="{{ $oc->PAGAMENTO_FORMA_DESCRICAO }}" />
            </div>  
            <div class="form-group">
                <label for="pag-cond">Condição de Pagamento:</label>
                <input type="text" name="pag_cond" id="pag-cond" class="form-control input-medio" required value="{{ $oc->PAGAMENTO_CONDICAO_DESCRICAO }}" readonly />
				<input type="hidden" name="_pag_cond_desc" value="{{ $oc->PAGAMENTO_CONDICAO_DESCRICAO }}" />
            </div> 
        </div>
    </fieldset>
	<fieldset>   
		<legend>Itens</legend>
		
        <table class="table table-striped table-bordered table-hover table-itens">
            <thead>
                <tr>
                    <th class="text-center">Seq.</th>
                    <th>Produto</th>
                    <th class="text-center">Qtd.</th>
                    <th class="text-center">Valor Unit.</th>
                    <th class="text-center">IPI</th>
                    <th class="text-center">Acresc.</th>
                    <th class="text-center">Desc.</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Prev. Saída</th>
                    <th class="text-center">Prev. Entrega</th>
                    <th>C. Custo</th>
                </tr>
            </thead>
            <tbody>
            @foreach ( $itens as $item )  
                <tr>
                    <td class="text-center">{{ $item->SEQUENCIA }}</td>
                    <td>{{ $item->PRODUTO_ID }} - {{ $item->PRODUTO_DESCRICAO }}
                    @foreach ( $pendencias as $pendencia )
                        @if ( $item->PRODUTO_ID == $pendencia->PRODUTO_ID && $pendencia->VALOR < $item->VALOR ) 
                        <span
                            class="glyphicon glyphicon-alert danger float-right" 
                            data-toggle="popover" 
                            data-placement="top" 
                            title="Dados da Última Compra" 
                            data-content="
                            <b>OC:</b>        <span style='margin-left:56px'><a href='{{ url('_13050', $pendencia->ID) }}' target='_blank'><b>{{ $pendencia->ID }}</b></a></span><br/>
                            <b>Fornecedor:</b> {{ $pendencia->FORNECEDOR_ID }} - {{ $pendencia->FORNECEDOR_DESCRICAO }}<br/>
                            <b>Data:</b>        <span style='margin-left:47px'>{{ $pendencia->DATA }}</span><br/>
                            <b>Valor Unit.:</b> <span style='margin-left:8px'>R$ {{ $pendencia->VALOR }}</span>
                            ">
                        </span>
                        @endif
                    @endforeach
                    </td>
                    <td class="text-right">{{ $item->QUANTIDADE }}</td>
                    <td class="text-right">R$ {{ $item->VALOR }}</td>
                    <td class="text-right">{{ $item->IPI }} %</td>
                    <td class="text-right">R$ {{ $item->ACRESCIMO }}</td>
                    <td class="text-right">R$ {{ $item->DESCONTO }}</td>
                    <td class="text-right">R$ {{ $item->TOTAL }}</td>
                    <td class="text-center">{{ $item->DATA_SAIDA }}</td>
                    <td class="text-center">{{ $item->DATA_ENTREGA }}</td>
                    <td>{{ $item->CCUSTO_MASK }} - {{ $item->CCUSTO_DESCRICAO }}</td>
                    <input type="hidden" name="_tab_prod_id[]" value="{{ $item->PRODUTO_ID }}" />
                    <input type="hidden" name="_tab_prod_desc[]" value="{{ $item->PRODUTO_DESCRICAO }}" />
                    <input type="hidden" name="_tab_qtd[]" value="{{ $item->QUANTIDADE }}" />
                    <input type="hidden" name="_tab_valor[]" value="{{ $item->VALOR }}" />
                    <input type="hidden" name="_tab_ipi[]" value="{{ $item->IPI }}" />
                    <input type="hidden" name="_tab_acresc[]" value="{{ $item->ACRESCIMO }}" />
                    <input type="hidden" name="_tab_desconto[]" value="{{ $item->DESCONTO }}" />
                    <input type="hidden" name="_tab_total[]" value="{{ $item->TOTAL }}" />
                    <input type="hidden" name="_tab_data_saida[]" value="{{ $item->DATA_SAIDA }}" />
                    <input type="hidden" name="_tab_data_entrega[]" value="{{ $item->DATA_ENTREGA }}" />
                </tr>
            @endforeach
            </tbody>				      
        </table> 
		
	</fieldset>    
	<fieldset>   
		<legend>Totalizadores</legend>
            <div class="form-group">
                <label for="qtd-item">Qtd. Itens:</label>
                <input type="text" name="qtd_item" id="qtd-item" class="form-control input-menor input-text-right" required value="{{ floatval($oc->QTD_ITENS) }}" readonly />
            </div>  
            <div class="form-group">
                <label for="subtotal">Subtotal:</label>
                <div class="input-group left-icon readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="subtotal" id="subtotal" class="form-control input-text-right valor" min="0" value="{{ $oc->VALOR_SUBTOTAL }}" readonly/>
                </div>
            </div>  
            <div class="form-group">
                <label for="desconto-total">Desconto:</label>
                <div class="input-group left-icon readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="desconto_total" id="desconto-total" class="form-control input-menor input-text-right valor" min="0" value="{{ $oc->VALOR_DESCONTO }}" readonly/>
                </div>
            </div>  
            <div class="form-group">
                <label for="acresc-total">Acréscimo:</label>
                <div class="input-group left-icon readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="acresc_total" id="acresc-total" class="form-control input-menor input-text-right valor" min="0" value="{{ $oc->VALOR_ACRESCIMO }}" readonly/>
                </div>
            </div>  
            <div class="form-group">
                <label for="ipi-total">IPI:</label>
                <div class="input-group left-icon readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="ipi_total" id="ipi-total" class="form-control input-menor input-text-right valor" min="0" value="{{ $oc->VALOR_IPI }}" readonly/>
                </div>
            </div>  
            <div class="form-group">
                <label for="tot-geral">Total Geral:</label>
                <div class="input-group left-icon readonly">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="total_geral" id="tot-geral" class="form-control input-text-right valor" min="0" value="{{ $oc->VALOR_TOTAL_GERAL }}" readonly/>
                </div>
            </div>  
    </fieldset>
	
	<fieldset>
		<legend>Informações adicionais</legend>			
		<div class="form-group">
			<label>Observação:</label>
			<div class="textarea-grupo">
                <textarea name="obs" class="form-control obs" rows="5" cols="100" readonly="">{{ $oc->OBSERVACAO }}</textarea>
				<span class="contador"><span></span> caracteres restantes</span>
			</div>
		</div>
	</fieldset>
    
</form>    

@include('helper.include.view.pdf-imprimir')
@include('helper.include.view.historico',['tabela' => 'TBOC', 'id' => $id])


@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13050.js') }}"></script>
@append