@extends('master')

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13021.css') }}" />
@endsection

@section('titulo')
{{ Lang::get('compras/_13021.titulo', ['date' => date('d/m/Y', strtotime($validade))] ) }}
@endsection

@section('conteudo')

	{{-- Verificar se a validade da licitação já expirou --}}
	@if ( isset($expirado) )
	
		<div class="jumbotron">
			<h2>Desculpe...</h2>
			<p>O período de resposta deste orçamento já expirou.</p>
		</div>
	
	@else

	<form action="{{ route('_13021.gravar', $id_hash) }}" url-redirect="{{ url('sucessoAlterar/_13021', $id_hash) }}" method="POST" enctype="multipart/form-data" class="form-inline edit js-gravar">
			<input type="hidden" name="_method" value="PATCH">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		    
		    <ul class="list-inline acoes">
				<li>
					<button type="submit" class="btn btn-success enviar-proposta js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
						<span class="glyphicon glyphicon-send"></span>
						{{ Lang::get('compras/_13021.enviarProp') }}
					</button>
				</li>
			</ul>
			
			<fieldset>
				<legend>Dados gerais</legend>
				<div class="row">
					<div class="form-group">
				    	<label>Lic.:</label>
				        <input type="text" class="form-control input-menor" value="{{ $licitacao->ID }}" readonly />
				        <input type="hidden" name="_licitacao_id" value="{{ $licitacao->ID }}" />
				    </div>
				    <div class="form-group">
				    	<label>Orç.:</label>
				        <input type="text" class="form-control input-menor" value="{{ $orcamento->ORCAMENTO_ID }}" readonly />
				        <input type="hidden" name="orcamento_id" value="{{ $orcamento->ORCAMENTO_ID }}" />
				    </div>
					<div class="form-group">
				    	<label>Comprador:</label>
				        <input type="text" class="form-control" value="{{ $licitacao->USUARIO_DESCRICAO }}" readonly />
				        <input type="hidden" name="_comprador_id" value="{{ $licitacao->USUARIO_ID }}" />
						<input type="hidden" name="_comprador_email" value="{{ $licitacao->USUARIO_EMAIL }}" />
				    </div>
				    <div class="form-group">
						<label>Observação:</label>
						<textarea class="form-control" rows="1" cols="70" readonly>{{ $licitacao->OBSERVACAO }}</textarea>
					</div>
			    </div>
			    <div class="row">
					<div class="form-group">
				    	<label>Fornecedor:</label>
				        <input type="text" name="fornecedor" class="form-control input-maior" value="{{ $orcamento->EMPRESA_ID .' - '. $orcamento->EMPRESA_DESCRICAO }}" title="{{ $orcamento->EMPRESA_ID .' - '. $orcamento->EMPRESA_DESCRICAO }}" readonly />
				    </div>
				    <div class="form-group">
				    	<label>Nome do Contato:</label>
				        <input type="text" name="contato" class="form-control" value="{{ $orcamento->CONTATO }}" required />
				    </div>
				    <div class="form-group">
				    	<label>Validade da proposta:</label>
				        <input type="date" name="validade" class="form-control" value="{{ $orcamento->VALIDADE_PROPOSTA }}" required />
				    </div>
				    <div class="form-group">
				    	<label>Prazo de entrega:</label>
				        <input type="date" name="prazo" class="form-control" value="{{ $orcamento->PRAZO_ENTREGA }}" required />
				    </div>
				</div>
				<div class="row">
					<div class="form-group">
				    	<label>Frete:</label>
				        <select name="frete" class="form-control frete" required>
				        	<option value="1" {{ $orcamento->FRETE == 1 ? 'selected' : '' }}>CIF</option>
				        	<option value="2" {{ $orcamento->FRETE == 2 ? 'selected' : '' }}>FOB</option>
				        </select>
				    </div>
					<div class="form-group">
				    	<label>Valor do Frete:</label>
						<div class="input-group left-icon readonly">
							<div class="input-group-addon"><span class="fa fa-usd"></span></div>
							<input type="text" name="frete_valor" class="form-control mask-numero frete-valor" decimal="4" precisao="4" min="0" value="{{ $orcamento->FRETE_VALOR }}" />
						</div>
				    </div>
				    <div class="form-group">
				    	<label>Forma de pagamento:</label>
                        <input type="text" name="pag_forma" class="form-control" value="{{ $orcamento->PAGAMENTO_FORMA }}" required />
				    </div>
				    <div class="form-group">
				    	<label>Condição de pagamento:</label>
                        <input type="text" name="pag_cond" class="form-control" value="{{ $orcamento->PAGAMENTO_CONDICAO }}" required />
				    </div>
				</div>
		    </fieldset>
		    
		    <fieldset class="item-orcamento">
		    	<legend>Itens do orçamento</legend>
				
				<div class="label label-primary">Caso haja algum produto que sua empresa não forneça, por favor ignore-o.</div>
						
						@foreach ($orcamento_item as $item)

                            <div class="row orc-item {{ $item->PRODUTO_ID }}">
								<div class="panel panel-default">

                                <div class="form-group">
						    		<label>Produto:</label>
			    					<input type="text" class="form-control input-maior" value="{{ $item->PRODUTO_ID .' - '. $item->PRODUTO_DESCRICAO }}" title="{{ $item->PRODUTO_ID .' - '. $item->PRODUTO_DESCRICAO }}" readonly />
			    					<input type="hidden" name="_produto_id[]" value="{{ $item->PRODUTO_ID }}" />
			    				</div>
								<div class="form-group">
									<label>Tamanho:</label>
									<input type="text" class="form-control input-menor" value="{{ $item->TAMANHO }}" readonly />
								</div>
								<div class="form-group">
									<label>UM:</label>
									<input type="text" class="form-control input-menor" value="{{ $item->UM }}" readonly />
								</div>
								<div class="form-group">
									<label>Quantidade:</label>
									<input type="text" class="form-control mask-qtd prod-qtd" value="{{ $item->QUANTIDADE }}" readonly />
								</div>
								<div class="form-group">
									<label>Info. complem.:</label>
									<input type="text" class="form-control input-maior" value="{{ $item->PRODUTO_INFO }}" readonly />
								</div>
								<br>                                
		    					<div class="form-group">
							    	<label>Valor unitário:</label>
							    	<div class="input-group left-icon">
										<div class="input-group-addon"><span class="fa fa-usd"></span></div>
							        	<input type="text" name="valor_unitario[]" class="form-control  prod-vlr mask-numero" decimal="4" precisao="4" value="{{ $item->VALOR_UNITARIO }}" min="0" />
							        </div>
							    </div>
							    <div class="form-group">
							    	<label>Percentual IPI:</label>
							    	<div class="input-group left-icon">
							        	<input type="text" name="ipi[]" class="form-control perc-ipi mask-numero" decimal="4" precisao="4" value="{{ $item->PERCENTUAL_IPI }}" min="0" />
							        	<div class="input-group-addon"><span class="porcentagem">%</span></div>
							        </div>
							    </div>
                                <div class="form-group">
                                    <label>Subtotal:</label>
                                    <div class="input-group left-icon readonly">
                                        <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                                        <input type="text" class="form-control subtotal" value="" readonly />
                                    </div>
                                </div>
								<div class="form-group">
									<label>Valor IPI:</label>
                                    <div class="input-group left-icon readonly">
                                        <div class="input-group-addon"><span class="fa fa-usd"></span></div>
									    <input type="text" class="form-control vlr-ipi" value="" readonly />
                                    </div>
								</div>
								<div class="form-group">
									<label>Total do Item:</label>
                                    <div class="input-group left-icon readonly">
                                        <div class="input-group-addon"><span class="fa fa-usd"></span></div>
									    <input type="text" class="form-control total" value="" readonly />
                                    </div>
								</div>
								<div class="form-group">
									<label>Obs. sobre o produto:</label>
									<input type="text" name="obs_produto[]" class="form-control input-maior" value="{{ $item->OBS_PRODUTO }}" />
								</div>

							</div>
							</div>
						@endforeach
							
			</fieldset>
			
			<fieldset class="info-adic">
				<legend>Informações adicionais</legend>			
			    <div class="form-group">
					<label>Observações:</label>
			    	<div class="textarea-grupo">
						<textarea name="observacao" class="form-control obs" rows="5" cols="100">{{ $orcamento->OBSERVACAO }}</textarea>
						<span class="contador"><span></span> caracteres restantes</span>
					</div>
			    </div>
				<div class="anexo-container item-dinamico-container">

					@if (count($arquivo) > 0)
						@foreach ($arquivo as $arq)
							<div class="anexo item-dinamico">

								<div class="form-group">
									<label>Descrição:</label>
									<input type="text" name="anexo_descricao" class="form-control" value="{{ $arq->OBSERVACAO }}" readonly />
									<input type="file" name="anexo_arquivo" class="form-control CLArquivo" value="{{ $arq->OBSERVACAO }} " disabled />
									<input type="hidden" class="marc" name="_vinculo_arquivo_id[]" value="{{ $arq->ID }}" />
									<input type="hidden" class="marc marcaexcluiritem" name="_req_arquivo_excluir[]" value="0" />
								</div>

								<div class="mudar form-group">
									<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
								</div>
								<div class="mudar form-group">
									<button type="button" class="btn btn-danger excluir-item-dinamico excluir-arquivo trash"><span class="glyphicon glyphicon-trash trash"></span></button>
								</div>

							</div>
						@endforeach
					@else
						<div class="anexo item-dinamico">

							<div class="form-group">
								<label>Descrição:</label>
								<input type="text" name="anexo_descricao" class="form-control" />
								<input type="file" name="anexo_arquivo" class="form-control CLArquivo" />
								<input type="hidden" name="_vinculo_arquivo_id[]" value="0" />
								<input type="hidden" class="marcaexcluiritem" name="_req_arquivo_excluir[]" value="0" />
							</div>

							<div class="mudar form-group">
								<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
							</div>
							<div class="mudar form-group">
								<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash"><span class="glyphicon glyphicon-trash trash"></span></button>
							</div>

						</div>
					@endif
					
					<progress class="progress-arq" value="0" max="100"></progress>
					
				</div>
				
				<button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
						
				<input type="hidden" name="_vinculo_id" value="{{ $vinculo }}" />
				
				<!-- </form> -->
			</fieldset>

		</form>

	@endif

@endsection

@section('script')
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input-dinamic.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
 	<script src="{{ elixir('assets/js/_13021.js') }}"></script>
@endsection