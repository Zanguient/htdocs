@extends('master')

@section('titulo')
{{ Lang::get('compras/_13020.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13020.css') }}" />
@endsection

@section('conteudo')

<ul class="list-inline acoes">
  	<li>
		<a href="{{ !$licitacao->OC ? ($permissaoMenu->ALTERAR ? route('_13020.edit', $licitacao->LICITACAO_ID) : '#') : '#' }}" 
		   class="btn btn-primary btn-alterar" data-hotkey="f9" {{ !$licitacao->OC ? ($permissaoMenu->ALTERAR ? '' : 'disabled') : 'disabled' }}>
			<span class="glyphicon glyphicon-edit"></span> 
			 {{ Lang::get('master.alterar') }}
		</a>
	</li>
  	<li>
		<button type="button" class="btn btn-primary btn-enviar-orc {{ !$licitacao->OC ? ($permissaoMenu->ALTERAR ? 'enviar-orcamento' : '') : '' }}" data-hotkey="alt+e" data-loading-text="{{ Lang::get('master.enviando') }}" {{ !$licitacao->OC ? ($permissaoMenu->ALTERAR ? '' : 'disabled') : 'disabled' }}>
			<span class="glyphicon glyphicon-send"></span>
			 {{ Lang::get('compras/_13020.enviarOrc') }}
		</button>
	</li>
	<li>
		<form action="{{ !$licitacao->OC ? ($permissaoMenu->EXCLUIR ? route('_13020.destroy', $licitacao->LICITACAO_ID) : '#') : '#' }}" method="POST" class="form-deletar">
		    <input type="hidden" name="_method" value="DELETE">
		    <input type="hidden" name="_token" value="{{ csrf_token() }}">
		    <button type="button" class="btn btn-danger excluir" data-hotkey="f12" data-toggle="modal" data-target="#confirmDelete" {{ !$licitacao->OC ? ($permissaoMenu->EXCLUIR ? '' : 'disabled') : 'disabled' }}><span class="glyphicon glyphicon-trash"></span> {{ Lang::get('master.excluir') }}</button>
		</form>
	</li>
    <li>
		<a href="{{ url('_13040',$licitacao->LICITACAO_ID) }}" target="_blank" 
		   class="btn btn-default btn-vis-prop" data-hotkey="alt+v">
			<span class="glyphicon glyphicon-new-window"></span>
			 Visualizar Propostas
		</a>
	</li>
	<li>
		<a href="{{ url('_13020') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{{ Lang::get('master.voltar') }}
		</a>
	</li>
	<li class="align-right">
		<button type="button" class="btn btn-grey160 gerar-historico" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
			<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
		</button>
	</li>
</ul>

@include('helper.include.view.historico',['tabela' => 'TBLICITACAO', 'id' => $licitacao->LICITACAO_ID, 'no_button' => 'true'])

@if ($licitacao->OC)
<div class="alert alert-warning">
    <p>Esta licitação está vinculada a uma ou mais Ordens de Compra</p>
    @foreach ($ocs as $oc)
    <p><b><a href="/_13050/{{ $oc->ID }}" target="_blank"><span class="glyphicon glyphicon-new-window"></span> OC Nº {{ $oc->ID }} - FAMÍLIAS: {{ $oc->FAMILIAS }} </a></b></p>
    @endforeach
</div>
@endif
<form class="form-inline">
	
	<fieldset readonly>
		<legend>Dados da licitação</legend>
		<div class="form-group">
	        <label for="licitacao">Licitação:</label>
	        <input type="number" name="licitacao" class="form-control" min="1" value="{{ $licitacao->LICITACAO_ID }}" readonly />
	    </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" class="form-control input-maior" maxlength="40" value="{{ $licitacao->DESCRICAO }}" required readonly />
        </div>        
	    <div class="form-group">
	    	<label for="data">Data:</label>
			<input type="text" name="data" class="form-control" value="{{ date('d/m/Y h:i:s', strtotime($licitacao->DATAHORA)) }}" required readonly />
	    </div>		    
	    <div class="form-group">
	    	<label for="validade">Validade:</label>
			<input type="date" name="validade" class="form-control" value="{{ $licitacao->DATA_VALIDADE }}" required readonly />
	    </div>
    </fieldset>
    
    <fieldset class="montar-orcamento" readonly>
    	<legend>Orçamento</legend>
	    <div class="form-group">
	    	<div class="panel panel-primary requisicoes" disabled>
	    		<div class="panel-heading">
					<h3 class="panel-title">Requisições pendentes</h3>
				</div>
				<div class="panel-body"></div>
	        </div>
	    </div>
	    <div class="form-group">
	        <div class="panel panel-primary orcamento">
		        <div class="panel-heading">
					<h3 class="panel-title">Itens para orçamento</h3>
				</div>
				<div class="panel-body item">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			        
			        <?php 
			        	$i = 0;
			        	$j = 0;
			        ?>
					@foreach ($requisicao as $re)
					
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading{{ $re->REQUISICAO_ID }}">
					      		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $re->REQUISICAO_ID }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $re->REQUISICAO_ID }}">
					        		{{ 'Req. '.$re->REQUISICAO_ID }}
					        		{{ ' do dia '.date('d/m/Y', strtotime($re->DATA)) }}
					        		{{ ' por '.ucwords(mb_strtolower($re->USUARIO)) }} 
					        		<span class="glyphicon {{ $re->URGENCIA == '1' ? 'glyphicon-alert' : '' }}" title="Urgente {{ isset($re->DATA_UTILIZACAO) ? 'para o dia: '.date('d/m/Y', strtotime($re->DATA_UTILIZACAO)) : '' }}"></span>
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
						  					
						    				<div class="label label-default" id-prod="{{ $r->PRODUTO_ID }}" id-req="{{ $r->REQUISICAO_ID }}">
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
												<input type="hidden" class="operacao-codigo" value="{{ $r->OPERACAO_CODIGO }}"/>
												<input type="hidden" class="operacao-ccusto" value="{{ $r->OPERACAO_CCUSTO }}"/>
												<input type="hidden" class="operacao-ccontabil" value="{{ $r->OPERACAO_CCONTABIL }}"/>
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
	
	<fieldset class="resumo-orcamento" readonly>
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
	
	<fieldset class="fornecedores" readonly>
		<legend>Fornecedores</legend>
		<div class="form-group empresa-sugestao">
			<div class="panel panel-primary">
				<div class="panel-heading">Sugestão:</div>
				<div class="panel-body"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<input type="search" name="empresa_descricao" class="form-control input-maior pesquisa empresa-descricao" autocomplete="off" placeholder="Pesquisar fornecedor..." readonly />
				<button type="button" class="input-group-addon btn-filtro btn-filtro-empresa" tabindex="-1" disabled>
					<span class="fa fa-search"></span>
				</button>
			</div>
			<div class="pesquisa-res-container lista-empresas-container"> 
				<div class="pesquisa-res lista-empresas"></div> 
			</div>
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
							<input type="hidden" name="_orcamento_id[]" value="{{ $orc->ORCAMENTO_ID }}" />
							<input type="hidden" name="_empresa_id[]" value="{{ $orc->EMPRESA_ID }}" />
							<input type="hidden" name="_orcamento_hash" value="{{ $orc->ORCAMENTO_ENCRYPT }}" />
						</div>
					
					@endforeach
					
				</div>
			</div>
		</div>
	</fieldset>
	
	<fieldset class="observacao" readonly>
		<legend>Observação</legend>			
	    <div class="form-group">
	    	<div class="textarea-grupo">
				<textarea name="observacao" class="form-control obs" rows="5" cols="100" readonly>{{ $licitacao->OBSERVACAO }}</textarea>
				<span class="contador"><span></span> caracteres restantes</span>
			</div>
	    </div>
	</fieldset>

</form>

@include('helper.include.view.delete-confirm')

@endsection

@section('script')
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13020.js') }}"></script>
@append
