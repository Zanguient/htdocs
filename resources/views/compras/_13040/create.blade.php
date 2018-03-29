@extends('master')

@section('titulo')
{{ Lang::get('compras/_13040.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13040.css') }}" />
@endsection

@section('conteudo')

<form action="{{ route('_13040.store', $licitacao_id) }}" url-redirect="{{ url('sucessoGravar/_13040/' . $licitacao_id) }}" method="POST" class="form-inline edit js-gravar">
	<div class="hiddens">
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    <input type="hidden" name="referencia" value="L"> {{-- Referência a tabela TBLICITACAO --}}
	    <input type="hidden" name="id" value="{{ $licitacao_id }}">
	    
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
					<th class="t-small t-center">Qtd.</th>
					<th class="t-small t-center">Operação</th>
				@foreach($orcamentos as $orcamento)
					<th  coluna="{{ $orcamento->EMPRESA_ID }}" class="t-low t-max-low t-center t-ellipsis tooltip-field">{{ $orcamento->EMPRESA_DESCRICAO }}</th>
					<div  coluna="{{ $orcamento->EMPRESA_ID }}">
						<input type="hidden" field="oc[nivel][]"    value="{{ floatval($orcamento->OC_NIVEL) }}" />
						<input type="hidden" field="oc[estab][]"    value="1" /> 
						<input type="hidden" field="oc[empresa][]"  value="{{ $orcamento->EMPRESA_ID }}" />
						<input type="hidden" field="oc[transp][]"   value="{{ $orcamento->TRANSPORTADORA_ID }}" />
						<input type="hidden" field="oc[forma][]"    value="{{ $orcamento->PAGAMENTO_FORMA_ID }}" />
						<input type="hidden" field="oc[cond][]"     value="{{ $orcamento->PAGAMENTO_CONDICAO_ID }}" />
						<input type="hidden" field="oc[frete][]"    value="{{ (($orcamento->FRETE_VALOR > 0) ? $orcamento->FRETE_VALOR : ($orcamento->FRETE == 1 ? 'CIF' : 'FOB')) }}">    	
					</div>			          	
				@endforeach
				</tr>
			</thead>
			<tbody>
			@foreach($orcamento_itens_unicos as $orcamento_item_unico)
				<tr linha="{{ $orcamento_item_unico->PRODUTO_ID }}">
					<input type="hidden" field="_produto_id" 	value="{{ $orcamento_item_unico->PRODUTO_ID }}" />
					<td class="t-medium t-max-medium t-ellipsis limit-width t-v-center">
						{{ $orcamento_item_unico->PRODUTO_ID }} - {{ $orcamento_item_unico->PRODUTO_DESCRICAO }}
						@if ( !empty($orcamento_item_unico->PRODUTO_INFO) )
							<span class="glyphicon glyphicon-info-sign prod-info" title="{{ $orcamento_item_unico->PRODUTO_INFO }}"></span>
						@endif
					</td>
					<td class="t-small t-numb t-v-center">{{ $orcamento_item_unico->QUANTIDADE }}</td>
					<td class="t-small t-input t-center">
						<div class="form-group">
							<div class="input-group input-search-small">
								<input type="search" name="operacao_descricao" class="form-control input-small operacao-descricao" autocomplete="off" value="{{ $orcamento_item_unico->OPERACAO_CODIGO }}" autofocus/>
								<button type="button" class="input-group-addon btn-filtro btn-filtro-operacao" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-operacao" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-tabela-container lista-operacao-container">
								<div class="pesquisa-res lista-operacao"></div>
							</div>							
						</div>				          	
					</td>
				@foreach($orcamentos as $orcamento)
					@foreach($orcamento_itens as $orcamento_item)        
						@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low {{ $orcamento_item->OC > 0 ? 't-center' : ($orcamento_item->VALOR_UNITARIO > 0 ? 't-numb' : 't-center') }} t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if ($orcamento_item->OC > 0)
						<b><a href="{{ url('_13050', $orcamento_item->OC) }}" target="_blank" data-toggle="tooltip" title="Clique aqui para visualizar">OC Nº {{ $orcamento_item->OC }}</a></b>
						@elseif($orcamento_item->VALOR_UNITARIO > 0) 
						<span class="glyphicon glyphicon-info-sign prod-info" title="{{ $orcamento_item->OBS_PRODUTO }}"></span>
						<div class="radio">
							<label>R$ <span>{{ $orcamento_item->VALOR_UNITARIO }}{{ $orcamento_item->PERCENTUAL_IPI > 0 ? ' + (' . $orcamento_item->PERCENTUAL_IPI . '%)' : '' }}</span> <input type="radio" name="item{{ $orcamento_item_unico->PRODUTO_ID }}"></label>
							<input type="hidden" field="item[estab][]"   	value="1" />
							<input type="hidden" field="item[empresa][]"   	value="{{ $orcamento->EMPRESA_ID }}" />
							<input type="hidden" field="item[prod_id][]" 	value="{{ $orcamento_item->PRODUTO_ID }}" />
							<input type="hidden" field="item[tam][]"        value="{{ $orcamento_item->TAMANHO }}" />
							<input type="hidden" field="item[orcamento][]"  value="{{ $orcamento->ID }}" />
							<input type="hidden" field="item[qtd][]"   		value="{{ $orcamento_item->QUANTIDADE }}" />
							<input type="hidden" field="item[valor][]" 		value="{{ $orcamento_item->VALOR_UNITARIO }}" />
							<input type="hidden" field="item[ipi][]"   		value="{{ $orcamento_item->PERCENTUAL_IPI }}" />
							<input type="hidden" field="item[entrega][]" 	value="{{ $orcamento_item->DATA_ENTREGA }}" />
							<input type="hidden" field="item[ccusto][]"		name="_operacao_ccusto[]"		value="{{ $orcamento_item->OPERACAO_CCUSTO }}"	/>
							<input type="hidden" field="item[ccontabil][]"	name="_operacao_ccontabil[]"	value="{{ $orcamento_item->OPERACAO_CCONTABIL }}" />
							<input type="hidden" field="item[operacao][]"	name="_operacao_codigo[]"		value="{{ $orcamento_item->OPERACAO_CODIGO }}" />
							<input type="hidden" field="item[desconto][]"	/>
						</div> 
						@else
						-
						@endif
					</td>
						@endif				          
					@endforeach
				@endforeach
				</tr>
			@endforeach
				<tr class="t-fornecedor">
					<td class="t-v-center"><b>Frete</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)						
						<td class="t-low t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)							
							@if($orcamento->FRETE_VALOR > 0)
								R$ {{ $orcamento->FRETE_VALOR }}
							@else
								{{ $orcamento->FRETE == 1 ? 'CIF' : 'FOB' }}						  		
							@endif							  	
						@else-@endif
						</td>	
					@endif		          
				@endforeach
			@endforeach	
				</tr>
				<tr class="t-fornecedor">
					<td class="t-v-center" ><b>Forma de Pagamento</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low t-input t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)
						<input type="text" class="form-control limit-width" value="{{ $orcamento->PAGAMENTO_FORMA }}" readonly/>						      	
						@else
						-
						@endif
					</td>		
					@endif		          
				@endforeach
			@endforeach				        		
				</tr>
				<tr class="t-fornecedor">
					<td class="t-v-center"><b>Condição de Pagamento</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low t-input t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)
						<input type="text" class="form-control" value="{{ $orcamento->PAGAMENTO_CONDICAO }}" readonly/>						      	
						@else
						-
						@endif
					</td>		
					@endif		          
				@endforeach
			@endforeach				        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center"><b>Transportadora Sugerida</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low t-input t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)
						<div class="form-group">
							<div class="input-group input-search-medio input-group-empresa">
								<input type="search" name="filtro" class="form-control input-medio transp-descricao" autocomplete="off" autofocus value="{{ $orcamento->TRANSPORTADORA_DEFAULT }}" />
								<input type="hidden" name="status" value="1" />
								<input type="hidden" name="habilita_transportadora" value="1" />

								<button type="button" class="input-group-addon btn-filtro btn-filtro-transp" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-filtro-apagar" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-empresa-container">
								<div class="pesquisa-res lista-empresa"></div>
							</div>							
						</div>					      	
						@else
						-
						@endif
					</td>		
					@endif		          
				@endforeach
			@endforeach			        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center"><b>Forma de Pagamento Sugerida</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low t-input t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)                            
						<div class="form-group">
							<div class="input-search-medio">
								<select input-name="oc[forma][]" class="form-control select-to-input">
									<option disabled selected></option>
									@foreach ( $pagamento_formas as $pagamento_forma)
									 <option value="{{ $pagamento_forma->ID }}" {{ floatval($pagamento_forma->ID) == floatval($orcamento->PAGAMENTO_FORMA_DEFAULT) ? 'selected' : ''}}>{{ $pagamento_forma->DESCRICAO }}</option>
									@endforeach
								</select>                                    
							</div>
						</div>				      	
						@else
						-
						@endif
					</td>		
					@endif		          
				@endforeach
			@endforeach			        		
				</tr>
				<tr class="t-sugestao">
					<td class="t-v-center"><b>Condição de Pagamento Sugerida</b></td>
					<td></td>
					<td></td>
			@foreach($orcamentos as $orcamento)
				@foreach($orcamento_itens as $orcamento_item)        
					@if($orcamento->ID === $orcamento_item->ORCAMENTO_ID && $orcamento_item_unico->PRODUTO_ID === $orcamento_item->PRODUTO_ID)
					<td class="t-low t-input t-center t-v-center" coluna="{{ $orcamento->EMPRESA_ID }}">
						@if($orcamento->STATUS_RESPOSTA == 1)
						<div class="form-group">
							<div class="input-search-medio">
								<select input-name="oc[cond][]" class="form-control select-to-input">
									<option disabled selected></option>
									@foreach ( $pagamento_condicoes as $pagamento_condicao)
									 <option value="{{ $pagamento_condicao->ID }}" {{ floatval($pagamento_condicao->ID) == floatval($orcamento->PAGAMENTO_CONDICAO_DEFAULT) ? 'selected' : ''}}>{{ $pagamento_condicao->DESCRICAO }}</option>
									@endforeach
								</select>                                    
							</div>
						</div>	                            		                    					      	
						@else
						-
						@endif
					</td>		
					@endif		          
				@endforeach
			@endforeach			        		
				</tr>
			</tbody>				      
		</table> 
		<span class="label label-default label-total">Total: R$ <span class="total-calculado">0,0000</span></span>
	</fieldset>
	<fieldset>
		<legend>Detalhamento por Fornecedor</legend>
		<div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
		@php $i = 0
	@foreach($orcamentos as $orcamento)
		@php $i++
			@if ( $orcamento->STATUS_RESPOSTA == 1)
			<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="heading{{ $i }}">
					<a role="button" data-toggle="collapse" href="#collapse{{ $i }}" data-parent="#accordion" aria-controls="collapse{{ $i }}">
						{{ $orcamento->EMPRESA_ID }} - {{ $orcamento->EMPRESA_DESCRICAO }}
					</a>
			    </div>
			    <div id="collapse{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $i }}">
			    	<div class="panel-body">
			        	


						<legend>Dados gerais</legend>
						<div class="row">
						    <div class="form-group">
						    	<label>Orç.:</label>
						        <input type="text" class="form-control input-menor" value="{{ $orcamento->ID }}" readonly required />
						        <input type="hidden" name="orcamento_id" value="{{ $orcamento->ID }}" />
						    </div>
						    <div class="form-group">
						    	<label>Nome do Contato:</label>
						        <input type="text" name="contato" class="form-control" value="{{ $orcamento->CONTATO }}" readonly required />
						    </div>
						    <div class="form-group">
						    	<label>Validade da proposta:</label>
						        <input type="date" name="validade" class="form-control" value="{{ $orcamento->VALIDADE_PROPOSTA }}" readonly required />
						    </div>
						    <div class="form-group">
						    	<label>Prazo de entrega:</label>
						        <input type="date" name="prazo" class="form-control" value="{{ $orcamento->PRAZO_ENTREGA }}" readonly required />
						    </div>
					    </div>
					    <div class="row">
						    <div class="form-group"  readonly>
						    	<label>Frete:</label>
						    	<input type="text" class="form-control input-menor" value="{{ $orcamento->FRETE == 1 ? 'CIF' : 'FOB' }}" readonly required />
						    </div>
						    <div class="form-group">
						    	<label>Forma de pagamento:</label>
		                        <input type="text" name="pag_forma" class="form-control" value="{{ $orcamento->PAGAMENTO_FORMA }}" readonly required />
						    </div>
						    <div class="form-group">
						    	<label>Condição de pagamento:</label>
		                        <input type="text" name="pag_cond" class="form-control" value="{{ $orcamento->PAGAMENTO_CONDICAO }}" readonly required />
						    </div>
					    </div>
				    
				   		<legend>Informações adicionais</legend>			
					    <div class="form-group">
							<label>Observação:</label>
					    	<div class="textarea-grupo">
								<textarea name="observacao" class="form-control obs" rows="5" cols="100" readonly>{{ $orcamento->OBSERVACAO }}</textarea>
							</div>
					    </div>
			
						<legend>Anexos</legend>
						<div class="anexo-container item-dinamico-container" ID='AA'>		
						    @foreach ($arquivo_itens as $arquivo_item)
                                @if ( $arquivo_item->TABELA_ID == $orcamento->VINCULO_ID )
							<div class="anexo item-dinamico" ID='BB'>
								<div class="form-group" ID='ARQ'>
									<label for="anexo_descricao">Descrição:</label>
							        <input type="text" name="anexo_descricao" class="form-control" value="{{ $arquivo_item->OBSERVACAO}}" readonly/>
									<input type="file" name="anexo_arquivo" class="form-control CLArquivo" value="{{ $arquivo_item->OBSERVACAO}}" disabled />
									<input type="hidden" name="_vinculo_id[]" value="{{ $arquivo_item->ID}}" readonly/>										
								</div>
							    <div class="form-group">
							    	<button type="button" class="btn btn-info view-arquivo" >
							    		<span class="glyphicon glyphicon-eye-open"></span>
							    	</button>
							    </div>
							</div>
                                @endif  
							@endforeach	
						</div>			  
                        <div class="visualizar-arquivo">
							<a class="btn btn-default download-arquivo" href="" download>
								<i class="glyphicon glyphicon-download"></i>
								{{ Lang::get('master.download') }}
							</a>
							<input type="hidden" class="arquivo_nome_deletar" name="_arquivo_nome[]" />
							<button type="button" class="btn btn-default esconder-arquivo">
								<i class="glyphicon glyphicon-chevron-left"></i>
								{{ Lang::get('master.voltar') }}
							</button>
							<object></object>
						</div>
                        
			    	</div>
			    </div>
			</div>	
			@endif  
	@endforeach	
		</div>
	</fieldset>
</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/_13040.js') }}"></script>
@append