@extends('master')

@section('titulo')
{{ Lang::get('compras/_13020.titulo-alterar') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13020.css') }}" />
@endsection

@section('conteudo')

<form action="{{ route('_13020.update', $licitacao->LICITACAO_ID) }}" url-redirect="{{ url('sucessoAlterar/_13020', $licitacao->LICITACAO_ID) }}" method="POST" class="form-inline edit js-gravar">
	<input type="hidden" name="_method" value="PATCH">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	
	<ul class="list-inline acoes">
		<li>
			<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
				<span class="glyphicon glyphicon-ok"></span>
				{{ Lang::get('master.gravar') }}
			</button>
		</li>
		<li>
			<a href="{{ url('_13020', $licitacao->LICITACAO_ID) }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
				<span class="glyphicon glyphicon-ban-circle"></span>
				{{ Lang::get('master.cancelar') }}
			</a>
		</li>
	</ul>
	
	<fieldset>
		<legend>Dados da licitação</legend>
		<div class="form-group">
	        <label for="licitacao">Licitação:</label>
	        <input type="number" name="licitacao" class="form-control" min="1" disabled value="{{ $licitacao->LICITACAO_ID }}" />
	        <input type="hidden" name="_licitacao" class="form-control" value="{{ $licitacao->LICITACAO_ID }}" />
	    </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" class="form-control input-maior" maxlength="40" value="{{ $licitacao->DESCRICAO }}" required />
        </div> 
	    <div class="form-group">
	    	<label for="data">Data:</label>
	        <input type="text" name="data" class="form-control" value="{{ date('d/m/Y h:i:s', strtotime($licitacao->DATAHORA)) }}" required />
	    </div>
	    <div class="form-group">
	    	<label for="validade">Validade:</label>
	        <input type="date" name="validade" class="form-control" value="{{ $licitacao->DATA_VALIDADE }}" required />
	    </div>
    </fieldset>
    
    <fieldset class="montar-orcamento">
    	<legend>Orçamento</legend>
	    <div class="form-group">
	    	<div class="panel panel-primary requisicoes">
	    		<div class="panel-heading">
					<h3 class="panel-title">Requisições pendentes</h3>
				</div>
				<div class="panel-body">
			       <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			        <?php $i = 0; ?>
			        @foreach ($req as $r)
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="req-heading{{ $r->ID }}">
					      		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#req-collapse{{ $r->ID }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="req-collapse{{ $r->ID }}">
					        		{{ 'Req. '.$r->ID }}
					        		{{ ' do dia '.date('d/m/Y', strtotime($r->DATA)) }}
					        		{{ ' por '.ucwords(mb_strtolower($r->USUARIO)) }} 
					        		<span class="glyphicon {{ $r->URGENCIA ? 'glyphicon-alert' : '' }}" title="Urgente {{ $r->DATA_UTILIZACAO ? 'para o dia: '.date('d/m/Y', strtotime($r->DATA_UTILIZACAO)) : '' }}"></span>
					      		</a>
						  	</div>
							<div id="req-collapse{{ $r->ID }}" class="panel-collapse collapse {{ $i === 0 ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading{{ $r->ID }}">
						    	<div class="panel-body">
									<div class="titulo-lista">
										<span>Produto</span>
										<span>Tam.</span>
										<span>Qtd.</span>
										<span>UM</span>
										<span>R$</span>
									</div>
						    		@foreach ($req_item as $ri)
						    			@if ($ri->REQUISICAO_ID === $r->ID)		
						    				@if ($ri->PRODUTO_ID == 0)			    				
						    					<button type="button" class="btn btn-default btn-sm produto prod-nao-cad" id-req="{{ $r->ID }}" id-prod="{{ $ri->PRODUTO_ID }}" emp-sug="{{ $r->EMPRESA_DESCRICAO }}" disabled title="Produto não cadastrado.">
						    					<i class="glyphicon glyphicon-info-sign"></i>
						    				@else
						    					<button type="button" class="btn btn-default btn-sm produto" id-req="{{ $r->ID }}" id-prod="{{ $ri->PRODUTO_ID }}" emp-sug="{{ $r->EMPRESA_DESCRICAO }}">
						    				@endif
							    					<div><span>{{ $ri->PRODUTO_ID }}</span></div>
							    					<div title="{{ $ri->PRODUTO_DESCRICAO }}">
														<span class="descricao">{{ $ri->PRODUTO_DESCRICAO }}</span>
														@if ( !empty($ri->OBSERVACAO) )
															<span class="glyphicon glyphicon-info-sign obs" title="{{ $ri->OBSERVACAO }}"></span>
														@endif
													</div>
							    					<div><span class="tamanho">{{ $ri->TAMANHO }}</span></div>
							    					<div><span class="quantidade">{{ $ri->QUANTIDADE }}</span></div>
							    					<div><span class="um">{{ $ri->UM }}</span></div>
							    					<div><span class="valor">{{ $ri->VALOR_UNITARIO }}</span></div>
													<input type="hidden" class="operacao-codigo" value="{{ $ri->OPERACAO_CODIGO }}"/>
													<input type="hidden" class="operacao-ccusto" value="{{ $ri->OPERACAO_CCUSTO }}"/>
													<input type="hidden" class="operacao-ccontabil" value="{{ $ri->OPERACAO_CCONTABIL }}"/>
							    				</button>
						    			@endif
						    		@endforeach
						    	</div>
						  	</div>
						</div>
						<?php $i++; ?>
		        	@endforeach
		        	</div>
		        </div>
	        </div>
	    </div>
	    <div class="form-group">
	        <div class="panel panel-primary orcamento">
		        <div class="panel-heading">
					<h3 class="panel-title">Itens para orçamento</h3>
				</div>
				<div class="panel-body item">
					<div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
					<?php 
			        	$i = 0;
			        	$j = 0;			        	
			        ?>
					@foreach ($requisicao as $re)
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading{{ $re->REQUISICAO_ID }}">
					      		<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse{{ $re->REQUISICAO_ID }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $re->REQUISICAO_ID }}">
					        		{{ 'Req. '.$re->REQUISICAO_ID }}
					        		{{ ' do dia '.date('d/m/Y', strtotime($re->DATA)) }}
					        		{{ ' por '.ucwords(mb_strtolower($re->USUARIO)) }} 
					        		<span class="glyphicon {{ $re->URGENCIA === '1' ? 'glyphicon-alert' : '' }}" title="Urgente {{ isset($re->DATA_UTILIZACAO) ? 'para o dia: '.date('d/m/Y', strtotime($re->DATA_UTILIZACAO)) : '' }}"></span>
					      		</a>
						  	</div>
						  	
							<div id="collapse{{ $re->REQUISICAO_ID }}" class="panel-collapse collapse {{ $j === 0 ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading{{ $re->REQUISICAO_ID }}">
						    	<div class="panel-body">
									<div class="titulo-lista">
										<span>Produto</span>
										<span>Tam.</span>
										<span>Qtd.</span>
										<span>UM</span>
										<span>R$</span>
									</div>
						    		@foreach ($orcamento_item as $r)
						  				@if ($re->REQUISICAO_ID == $r->REQUISICAO_ID)		
						    				<div class="label label-default" id-req="{{ $r->REQUISICAO_ID }}" id-prod="{{ $r->PRODUTO_ID }}">
						    					<div><span>{{ $r->PRODUTO_ID }}</span></div>
						    					<div title="{{ $r->PRODUTO_DESCRICAO }}">
													<span class="descricao">{{ $r->PRODUTO_DESCRICAO }}</span>
													@if ( !empty($r->OBSERVACAO) )
														<span class="glyphicon glyphicon-info-sign obs" title="{{ $r->OBSERVACAO }}"></span>
													@endif
												</div>
						    					<div><span class="tamanho">{{ $r->TAMANHO }}</span></div>
						    					<div><span class="quantidade">{{ $r->QUANTIDADE }}</span></div>
						    					<div><span class="um">{{ $r->UM }}</span></div>
						    					<div><span class="valor">{{ $r->VALOR_UNITARIO }}</span></div>
						    					
						    					<input type="hidden" class="orc-id" value="true" />
						    					<button type="button" class="btn btn-danger excluir"><i class="glyphicon glyphicon-trash"></i></button>
						    					<input type="hidden" name="_produto_excluir[]" class="produto-excluir" />
						    					<input type="hidden" name="_prod_id[]" class="prod-id" value="{{ $r->PRODUTO_ID }}"></input>
												<input type="hidden" name="_prod_desc[]" class="prod-desc" value="{{ $r->PRODUTO_DESCRICAO }}"></input>
												<input type="hidden" name="_prod_um[]" class="prod-um" value="{{ $r->UM }}"></input>
												<input type="hidden" name="_prod_tamanho[]" class="prod-tamanho" value="{{ $r->TAMANHO }}"></input>
												<input type="hidden" name="_prod_qtd[]" class="prod-qtd" value="{{ $r->QUANTIDADE }}"></input>
												<input type="hidden" name="_prod_valor[]" class="prod-vlr" value="{{ $r->VALOR_UNITARIO }}"></input>
												<input type="hidden" name="_prod_licitacao[]" class="prod-lic" value="{{ $r->LICITACAO_ID }}"></input>
												<input type="hidden" name="_requisicao_id[]" class="req-id" value="{{ $r->REQUISICAO_ID }}"></input>
												<input type="hidden" name="_req_item_id[]" class="req-item-id" value="{{ $r->REQUISICAO_ITEM_ID }}"></input>
												<input type="hidden" name="_oper_codigo[]" class="_oper-codigo" value="{{ $r->OPERACAO_CODIGO }}"/>
												<input type="hidden" name="_oper_ccusto[]" class="_oper-ccusto" value="{{ $r->OPERACAO_CCUSTO }}"/>
												<input type="hidden" name="_oper_ccontabil[]" class="_oper-ccontabil" value="{{ $r->OPERACAO_CCONTABIL }}"/>
							    			</div>
						    			@endif
						    		<?php $i++; ?>
						    		@endforeach
						    	</div>
						  	</div>
						</div>
					<?php $j++; ?>
					@endforeach
					</div>
				</div>
			</div>
	    </div>
	</fieldset>
	
	<fieldset class="resumo-orcamento">
		<legend>Resumo dos itens</legend>
		<div class="form-group">
			<div class="tabela-valor">
			@foreach ($orcamento_item as $item)
				<div class="prod {{ $item->PRODUTO_ID }} tam{{ $item->TAMANHO }}">
					<input type="hidden" name="_produto_id[]" class="prod-id" value="{{ $item->PRODUTO_ID }}"></input>
					<input type="hidden" name="_produto_desc[]" class="prod-desc" value="{{ $item->PRODUTO_DESCRICAO }}"></input>
					<input type="hidden" name="_produto_um[]" class="prod-um" value="{{ $item->UM }}"></input>
					<input type="hidden" name="_produto_tamanho[]" class="prod-tamanho" value="{{ $item->TAMANHO }}"></input>
					<input type="hidden" name="_produto_qtd[]" class="prod-qtd" value="{{ $item->QUANTIDADE }}"></input>
					<input type="hidden" name="_produto_valor[]" class="prod-vlr" value="{{ $item->VALOR_UNITARIO }}"></input>
					<input type="hidden" name="_produto_licitacao[]" class="prod-lic" value="{{ $licitacao->LICITACAO_ID }}"></input>
					<input type="hidden" name="_operacao_codigo[]" class="_operacao-codigo" value="{{ $item->OPERACAO_CODIGO }}"/>
					<input type="hidden" name="_operacao_ccusto[]" class="_operacao-ccusto" value="{{ $item->OPERACAO_CCUSTO }}"/>
					<input type="hidden" name="_operacao_ccontabil[]" class="_operacao-ccontabil" value="{{ $item->OPERACAO_CCONTABIL }}"/>
				</div>
			@endforeach
			</div>
			<div class="panel panel-default resumo">
				<div class="panel-body"></div>
			</div>
	    </div>
	</fieldset>
	
	<fieldset class="fornecedores">
		<legend>Fornecedores</legend>
		<div class="form-group empresa-sugestao">
			<div class="panel panel-primary">
				<div class="panel-heading">Sugestão:</div>
				<div class="panel-body"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<input type="search" name="empresa_descricao" class="form-control input-maior pesquisa empresa-descricao" autocomplete="off" placeholder="Pesquisar fornecedor..." />
				<div class="input-group-addon btn-filtro btn-filtro-empresa" tabindex="-1"><span class="fa fa-search"></span></div>
			</div>
			<div class="pesquisa-res-container lista-empresas-container"> <div class="pesquisa-res lista-empresas"></div> </div>
		</div>
		<div class="empresas-selec">
			<div class="panel panel-primary">
				<div class="panel-heading">Fornecedores selecionados:</div>
				<div class="panel-body">
					<div class="titulo-lista">
						<span>Empresa</span>
						<span>E-mail</span>
						<span>Fone</span>
						<span>Contato</span>
						<span>Cidade</span>
						<span>UF</span>
						<span>Orç.</span>
					</div>
					
					@foreach ($orcamento as $orc)
					
						<div class="label label-default">
							<span>{{ $orc->EMPRESA_ID }}</span> 
							<span title="{{ $orc->RAZAOSOCIAL }}">{{ $orc->RAZAOSOCIAL }}</span>
							<span title="{{ $orc->EMAIL }}">
								<div class="dado-atual">{{ $orc->EMAIL }}</div>
								<input type="email" name="empresa_email" class="form-control dado-editar empresa-email" value="{{ $orc->EMAIL }}" />
							</span>
							<span title="{{ $orc->FONE }}">
								<div class="dado-atual">{{ $orc->FONE }}</div>
								<input type="tel" name="empresa_fone" class="form-control fone dado-editar empresa-fone" value="{{ $orc->FONE }}" />
							</span>
							<span title="{{ $orc->CONTATO }}">
								<div class="dado-atual">{{ $orc->CONTATO }}</div>
								<input type="text" name="empresa_contato" class="form-control dado-editar empresa-contato" value="{{ $orc->CONTATO }}" />
							</span>
							<span title="{{ $orc->CIDADE }}">{{ $orc->CIDADE }}</span>
							<span title="{{ $orc->UF }}">{{ $orc->UF }}</span>
							<span class="orc-id">{{ $orc->ORCAMENTO_ID }}</span>
							<button type="button" class="btn btn-primary empresa-editar" title="Editar"><i class="glyphicon glyphicon-edit"></i></button>
							<button type="button" class="btn btn-success empresa-gravar" title="Gravar"><i class="glyphicon glyphicon-ok"></i></button>
							<button type="button" class="btn btn-danger empresa-cancelar" title="Cancelar"><i class="glyphicon glyphicon-ban-circle"></i></button>
							<button type="button" class="btn btn-danger excluir" title="Excluir"><i class="glyphicon glyphicon-trash"></i></button>
							<input type="hidden" name="_empresa_excluir[]" class="empresa-excluir" />
							<input type="hidden" name="_orcamento_id[]" value="{{ $orc->ORCAMENTO_ID }}" />
							<input type="hidden" name="_orcamento_hash" value="{{  $orc->ORCAMENTO_ENCRYPT }}" />
							<input type="hidden" name="_empresa_id[]" value="{{ $orc->EMPRESA_ID }}" />
						</div>
					
					@endforeach
					
				</div>
			</div>
		</div>
	</fieldset>
	
	<fieldset class="observacao">
		<legend>Observação</legend>			
	    <div class="form-group">
	    	<div class="textarea-grupo">
				<textarea name="observacao" class="form-control obs" rows="5" cols="100">{{ $licitacao->OBSERVACAO }}</textarea>
				<span class="contador"><span></span> caracteres restantes</span>
			</div>
	    </div>
	</fieldset>

</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/_13020.js') }}"></script>
@append