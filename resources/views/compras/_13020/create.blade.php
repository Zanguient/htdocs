@extends('master')

@section('titulo')
{{ Lang::get('compras/_13020.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13020.css') }}" />
@endsection

@section('conteudo')

	<form action="{{ route('_13020.store') }}" url-redirect="{{ url('sucessoGravar/_13020') }}" method="POST" class="form-inline form-add js-gravar">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('compras/_13020.gerandoOrc') }}">
					<span class="glyphicon glyphicon-ok"></span>
					 {{ Lang::get('compras/_13020.gerarOrc') }}
				</button>
			</li>
			<li>
				<a href="{{ url('_13020') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
					<span class="glyphicon glyphicon-chevron-left"></span>
					 {{ Lang::get('master.voltar') }}
				</a>
			</li>
		</ul>
		
		<fieldset>
			<legend>Dados da licitação</legend>
		    <div class="form-group">
		    	<label for="descricao">Descrição:</label>
                <input type="text" name="descricao" class="form-control input-maior" maxlength="40" required autofocus />
		    </div>
		    <div class="form-group">
		    	<label for="validade">Validade:</label>
		        <input type="date" name="validade" class="form-control" value="{{ date('Y-m-d', strtotime('+5 days')) }}" required />
		    </div>
	    </fieldset>
	    
	    <fieldset class="montar-orcamento">
	    	<legend>Montar orçamento</legend>
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
								<div class="panel-heading" role="tab" id="heading{{ $r->ID }}">
						      		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $r->ID }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $r->ID }}">
						        		{{ 'Req. '. $r->ID }}
						        		{{ ' do dia '.date('d/m/Y', strtotime($r->DATA)) }}
						        		{{ ' por '.ucwords(mb_strtolower($r->USUARIO)) }} 
						        		<span class="glyphicon {{ $r->URGENCIA > 0 ? 'glyphicon-alert' : '' }}" title="Urgente {{ $r->DATA_UTILIZACAO ? 'para o dia: '.date('d/m/Y', strtotime($r->DATA_UTILIZACAO)) : '' }}"></span>
						      		</a>
							  	</div>
								<div id="collapse{{ $r->ID }}" class="panel-collapse collapse {{ $i === 0 ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading{{ $r->ID }}">
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
						<!--<div class="tabela-valor"></div>-->
					</div>
				</div>
		    </div>
		</fieldset>
		
		<fieldset class="resumo-orcamento" readonly>
			<legend>Resumo dos itens</legend>
			<div class="form-group">
				<div class="tabela-valor"></div>
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
					<button type="button" class="input-group-addon btn-filtro btn-filtro-empresa" tabindex="-1">
						<span class="fa fa-search"></span>
					</button>
				</div>
				<div class="pesquisa-res-container lista-empresas-container"> 
					<div class="pesquisa-res lista-empresas"></div> 
				</div>
			</div>				
			<div class="empresas-selec vazio">
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
					</div>
				</div>
			</div>
		</fieldset>
		
		<fieldset class="observacao">
			<legend>Observação</legend>			
		    <div class="form-group">
		    	<div class="textarea-grupo">
					<textarea name="observacao" class="form-control obs" rows="5" cols="100"></textarea>
					<span class="contador"><span></span> caracteres restantes</span>
				</div>
		    </div>
		</fieldset>
	</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/_13020.js') }}"></script>
@append
