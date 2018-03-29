@extends('master')

@section('titulo')
{{ Lang::get('compras/_13041.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13040.css') }}" />
@endsection

@section('conteudo')

<form action="{{ route('_13040.store', $id) }}" url-redirect="{{ url('sucessoGravar/_13041/' . $id) }}" method="POST" class="form-inline edit js-gravar">
	<div class="hiddens">
		<input type="hidden" name="_method" value="PATCH" />
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	    <input type="hidden" name="referencia" value="R"> {{-- Referência a tabela TBREQUISICAO_OC --}}
	    <input type="hidden" name="id" value="{{ $id }}">
	    
    </div>

	<ul class="list-inline acoes">
		<li>
			<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}" disabled>
				<span class="glyphicon glyphicon-ok"></span>
				 {{ Lang::get('compras/_13040.gerar-oc') }}
			</button>
		</li>
	</ul>
	<fieldset>   
		<legend>Produtos para Geração de Ordem de Compra</legend>
		<table class="table table-striped table-bordered table-hover lista-obj">
			<thead>
				<tr>
					<th class="t-medium">Produto</th>
					<th class="t-small">Tam.</th>
					<th class="t-small t-center">Qtd.</th>
					<th class="t-small t-center">Operação</th>
					<th class="t-small t-center">Data Saída</th>
					<th class="t-small t-center">Data Entrega</th>
					<th class="t-small t-center">% IPI</th>
					<th  coluna="{{ $requisicao->EMPRESA_ID }}" class="t-low t-max-low t-center t-ellipsis tooltip-field">{{ $requisicao->EMPRESA_DESCRICAO }}</th>
					<div  coluna="{{ $requisicao->EMPRESA_ID }}">
						<input type="hidden" field="oc[nivel][]"    value="{{ intval($requisicao->OC_NIVEL) }}" />
						<input type="hidden" field="oc[estab][]"    value="{{ $requisicao->ESTABELECIMENTO_ID }}" /> 
						<input type="hidden" field="oc[empresa][]"  value="{{ $requisicao->EMPRESA_ID }}" input-name="item[empresa][]" class="select-to-input" />
						<input type="hidden" field="oc[transp][]" />
						<input type="hidden" field="oc[forma][]"  />
						<input type="hidden" field="oc[cond][]"   />
						<input type="hidden" field="oc[frete][]"  />    	
					</div>	                        
				</tr>
			</thead>
			<tbody>
			@php $i = 0;
			@foreach($requisicao_itens as $requisicao_item)
				<tr linha="{{ $requisicao_item->PRODUTO_ID }}">
					<input type="hidden" field="_produto_id" 	value="{{ $requisicao_item->PRODUTO_ID }}" />
					<td class="t-medium t-max-medium t-ellipsis limit-width t-v-center">
						{{ $requisicao_item->PRODUTO_ID }} - {{ $requisicao_item->PRODUTO_DESCRICAO }}
						@if ( !empty($requisicao_item->OBSERVACAO) )
						<span class="glyphicon glyphicon-info-sign prod-info" title="{{ $requisicao_item->OBSERVACAO }}"></span>
						@endif
					</td>
					<td class="t-small t-v-center">
						{{ $requisicao_item->TAMANHO_DESCRICAO }}
					</td>
					<td class="t-small t-numb t-v-center">{{ $requisicao_item->QUANTIDADE }}</td>
					<td class="t-small t-input t-center">
						<div class="form-group">
							<div class="input-group input-search-small">
								<input type="search" name="operacao_descricao" class="form-control input-small operacao-descricao" autocomplete="off" value="{{ $requisicao_item->OPERACAO_CODIGO }}" autofocus />
								<button type="button" class="input-group-addon btn-filtro btn-filtro-operacao" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-operacao" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-tabela-container lista-operacao-container">
								<div class="pesquisa-res lista-operacao"></div>
							</div>							
						</div>				          	
					</td>
					<td class="t-small t-center t-v-center">
						<div class="form-group">
							<input type="date" input-name="item[saida][]" class="form-control data-saida select-to-input" value="{{ date('Y-m-d', strtotime('+1 days')) }}" />
							@if( ($i === 0) && (count($requisicao_itens) > 1) )
							<button type="button" class="btn btn-primary replicar-data replicar-data-saida" title="Replicar em todas as datas abaixo">
								<span class="glyphicon glyphicon-menu-down"></span>
								<span class="glyphicon glyphicon-menu-down"></span>
							</button>
							@endif
						</div>                            
					</td>
					<td class="t-small t-center t-v-center">
						<div class="form-group">
							<input type="date" input-name="item[entrega][]" class="form-control data-entrega select-to-input" value="{{ date('Y-m-d', strtotime('+5 days')) }}" />
							@if( ($i === 0) && (count($requisicao_itens) > 1) )
							<button type="button" class="btn btn-primary replicar-data replicar-data-entrega" title="Replicar em todas as datas abaixo">
								<span class="glyphicon glyphicon-menu-down"></span>
								<span class="glyphicon glyphicon-menu-down"></span>
							</button>
							@endif
						</div>                            
					</td>
					<td class="t-small t-center t-v-center" coluna="{{ $requisicao_item->PRODUTO_ID }}">
						<div class="input-group">
							<input type="text" input-name="item[ipi][]" class="form-control perc-ipi mask-numero input-small select-to-input" decimal="2" precisao="2" value="0,00" min="0" maxlength="5" />
							<div class="input-group-addon"><span class="porcentagem">%</span></div>
						</div>                           
					</td>					
					@php $valor_unitario = floatval(str_replace(',', '.', $requisicao_item->VALOR_UNITARIO))
					<td class="t-low {{ $requisicao_item->OC > 0 ? 't-center' : ($valor_unitario > 0 ? 't-numb' : 't-center') }} t-numb t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
					@if ( $valor_unitario <= 0 ) 
						<span
							class="glyphicon glyphicon-alert danger float-right" 
							data-toggle="tooltip" 
							title="Produto sem valor unitário.">
						</span>
					@elseif ($requisicao_item->OC > 0)
						<b><a href="{{ url('_13050', $requisicao_item->OC) }}" target="_blank" data-toggle="tooltip" title="Clique aqui para visualizar">OC Nº {{ $requisicao_item->OC }}</a></b>
					@else    
						<div class="radio">
                            <label>R$ <span>{{ $requisicao_item->VALOR_UNITARIO }}</span> <input type="checkbox" name="item{{ $requisicao_item->PRODUTO_ID }}" previousvalue="checked" checked></label>
							<input type="hidden" field="item[orcamento][]"  value="0">
							<input type="hidden" field="item[estab][]"   	value="{{ $requisicao->ESTABELECIMENTO_ID }}"       />
							<input type="hidden" field="item[empresa][]"   	value="{{ $requisicao->EMPRESA_ID }}"               />
							<input type="hidden" field="item[prod_id][]" 	value="{{ $requisicao_item->PRODUTO_ID }}"          />
							<input type="hidden" field="item[tam][]"        value="{{ $requisicao_item->TAMANHO }}"             />
							<input type="hidden" field="item[qtd][]"   		value="{{ $requisicao_item->QUANTIDADE }}"          />
							<input type="hidden" field="item[valor][]" 		value="{{ $requisicao_item->VALOR_UNITARIO }}"      />
							<input type="hidden" field="item[ipi][]"   		value="{{ $requisicao_item->PERCENTUAL_IPI }}"      />
							<input type="hidden" field="item[entrega][]" 	value="{{ date('Y-m-d', strtotime('+5 days')) }}"   />
							<input type="hidden" field="item[saida][]"		value="{{ date('Y-m-d', strtotime('+1 days')) }}"   />
							<input type="hidden" field="item[ccusto][]"     value="{{ $requisicao_item->OPERACAO_CCUSTO }}"		/>
							<input type="hidden" field="item[ccontabil][]"  value="{{ $requisicao_item->OPERACAO_CCONTABIL }}"	/>
							<input type="hidden" field="item[operacao][]"	value="{{ $requisicao_item->OPERACAO_CODIGO }}"		/>
							<input type="hidden" field="item[desconto][]"   />
						</div> 
					@endif
					</td>                        
				</tr>
				@php $i++;
			@endforeach
				<tr class="t-sugestao">
					<td class="t-v-center" colspan="7"><b>Fornecedor Sugerido</b></td>
					<td class="t-low t-input t-center t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
						<div class="form-group">
							<div class="input-group input-search-medio input-group-empresa">
								<input type="search" name="filtro" class="form-control input-medio empresa-descricao" value="{{ $requisicao->EMPRESA_DESCRICAO }}" autocomplete="off" autofocus required />
								<input type="hidden" name="status" value="1" />
								<input type="hidden" name="habilita_fornecedor" value="1" />

								<button type="button" class="input-group-addon btn-filtro btn-filtro-empresa" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-filtro-apagar" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-empresa-container">
								<div class="pesquisa-res lista-empresa"></div>
							</div>							
						</div>				
					</td>				        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center" colspan="7"><b>Frete</b></td>
					<td class="t-low t-input t-center t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
						<div class="form-group">
							<select name="frete" class="form-control frete" required="">
								<option value="1">CIF</option>
								<option value="2" selected="">FOB</option>
							</select>   
							<div class="input-group dinheiro">
							<div class="input-group-addon"><span class="fa fa-usd"></span></div>
								<input type="text" name="frete_valor" class="form-control mask-numero frete-valor input-menor" decimal="2" precisao="2" min="0" value="0,00" >
							</div>                                                 
						</div>  
					</td>
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center" colspan="7"><b>Transportadora Sugerida</b></td>
					<td class="t-low t-input t-center t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
						<div class="form-group">
							<div class="input-group input-search-medio input-group-empresa">
								<input type="search" name="filtro" class="form-control input-medio transp-descricao" autocomplete="off" autofocus required />
								<input type="hidden" name="status" value="1" />
								<input type="hidden" name="habilita_transportadora" value="1" />

								<button type="button" class="input-group-addon btn-filtro btn-filtro-transp" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-filtro-apagar" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-empresa-container">
								<div class="pesquisa-res lista-empresa"></div>
							</div>							
						</div>				
					</td>				        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center" colspan="7"><b>Forma de Pagamento Sugerida</b></td>
					<td class="t-low t-input t-center t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
						<div class="form-group">
							<div class="input-search-medio">
								<select input-name="oc[forma][]" class="form-control select-to-input" required>
									<option disabled selected></option>
									@foreach ( $pagamento_formas as $pagamento_forma)
									 <option value="{{ $pagamento_forma->ID }}">{{ $pagamento_forma->DESCRICAO }}</option>
									@endforeach
								</select>                                    
							</div>
						</div>				      	
					</td>			        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center" colspan="7"><b>Condição de Pagamento Sugerida</b></td>
					<td class="t-low t-input t-center t-v-center" coluna="{{ $requisicao->EMPRESA_ID }}">
						<div class="form-group">
							<div class="input-search-medio">
								<select input-name="oc[cond][]" class="form-control select-to-input" required>
									<option disabled selected></option>
									@foreach ( $pagamento_condicoes as $pagamento_condicao)
									 <option value="{{ $pagamento_condicao->ID }}">{{ $pagamento_condicao->DESCRICAO }}</option>
									@endforeach
								</select>                                    
							</div>
						</div>	             
					</td>		        		
				</tr>					                
			</tbody>				      
		</table> 
		<span class="label label-default label-total">Total: R$ <span class="total-calculado">0,0000</span></span>
	</fieldset>
</form>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/_13040.js') }}"></script>
@endsection