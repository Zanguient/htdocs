@extends('master')

@section('titulo')
{{ Lang::get('compras/_13010.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13010.css') }}" />
@endsection

@section('conteudo')

	<form action="{{ route('_13010.store') }}" url-redirect="{{ url('sucessoGravar/_13010') }}" method="POST" class="form-inline form-add js-gravar" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button 
					type="submit" 
					class="btn btn-success js-gravar" 
					data-hotkey="f10" 
					data-loading-text="{{ Lang::get('master.gravando') }}">

					<span class="glyphicon glyphicon-ok"></span>
					 {{ Lang::get('master.gravar') }}
				</button>
			</li>
            <li>
            	<a 
            		href="{{ url('_13010') }}" 
            		class="btn btn-danger btn-cancelar" 
            		data-hotkey="f11">

            		<span class="glyphicon glyphicon-ban-circle"></span>
            		 {{ Lang::get('master.cancelar') }}
            	</a>

            	<script type="text/javascript">

					// Se foi feito um filtro antes, 
					// troca as URL's que voltam para a página anterior 
					// pela URL que contém os parâmetros do filtro.
					if (localStorage.getItem('13010FiltroUrl') != null) {

						$("form.js-gravar").attr("url-redirect", localStorage.getItem("13010FiltroUrl"));
						$(".btn-cancelar").attr("href", localStorage.getItem("13010FiltroUrl"));
					}
				</script>
            </li>
		</ul>
		
		@foreach ($vinculo as $v)
		  <input type="hidden" name="_vinculo_id" value="{{ $v->ID }}" />
		@endforeach
		
		<fieldset>
			<legend>Informações gerais</legend>
			<div class="row">
				<div class="form-group">
					<label>Descrição:</label>
					<input type="text" name="descricao" class="form-control input-maior" autofocus required />
				</div>
				<div class="ccusto-container">
					<div class="ccusto">
						<div class="form-group">
							<label for="ccusto-descricao">Centro de custo:</label>
							<div class="input-group">
								<input type="search" name="ccusto_descricao" id="ccusto-descricao" class="form-control input-medio" autocomplete="off" required />
								<button type="button" class="input-group-addon btn-filtro btn-filtro-ccusto" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccusto" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-ccusto-container"> <div class="pesquisa-res lista-ccusto"></div> </div>
							<input type="hidden" name="_ccusto_id" />
						</div>
					</div>
				</div>
				<div class="gestor-container">
					<div class="gestor">
						<div class="form-group">
							<label for="gestor-descricao">Gestor:</label>
							<div class="input-group">
								<input type="search" name="gestor_descricao" id="gestor-descricao" class="form-control input-medio" autocomplete="off" required />
								<button type="button" class="input-group-addon btn-filtro btn-filtro-gestor" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-gestor" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-gestores-container"> <div class="pesquisa-res lista-gestores"></div> </div>
							<input type="hidden" name="_gestor_id" />
							<input type="hidden" name="_gestor_email" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="data" title="Estabelecimento">Estab.:</label>
					<input type="number" name="estabelecimento_id" class="form-control" min="1" max="{{ $estab_id_max }}" required />
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label for="data">Data:</label>
					<input type="date" name="data" id="data" class="form-control" value="{{ date('Y-m-d') }}" required readonly />
				</div>
				<div class="form-group">
					<label for="data-utilizacao" title="Data prevista para utilização do material">Data para utilização:</label>
					<input type="date" name="data_utilizacao" id="data-utilizacao" class="form-control" />
				</div>
				<div class="form-group">
					<input type="checkbox" name="urgencia" id="urgencia" class="form-control" />
					<label for="urgencia">Urgência</label>
				</div>
				<div class="form-group">
					<input type="checkbox" name="necessita_licitacao" id="necessita-licitacao" class="form-control" />
					<label for="necessita-licitacao">Necessita licitação?</label>
				</div>
			</div>
	    </fieldset>
	    
	    <fieldset>
	    	<legend>Informações sobre o produto</legend>
		    <div class="produto-container item-dinamico-container">
				<div class="produto item-dinamico">
					<div class="row">
						<div class="form-group">
							<label for="produto-id">Produto:</label>
							<div class="input-group">
								<input type="search" name="produto_descricao" class="form-control input-maior produto-descricao" autocomplete="off" required />
								<button type="button" class="input-group-addon btn-filtro btn-filtro-produto" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-produto" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-produtos-container"> <div class="pesquisa-res lista-produtos"></div> </div>
							<input type="hidden" name="_produto_id[]" class="_produto-id" />
							<input type="hidden" name="_produto_descricao[]" />
							<input type="hidden" name="_req_item_id[]" />
						</div>
						<div class="form-group">
							<label for="um">UM:</label>
							<input type="text" name="um[]" class="form-control input-menor" maxlength="3" required />
						</div>
						<div class="form-group">
							<label for="tamanho">Tamanho:</label>
								<div class="input-group">
									<div class="input-group-addon tamanho"><button type="button" class="btn btn-primary btn-sm sett"  data-toggle="modal" data-target="#modal-edit"><span class="glyphicon glyphicon-triangle-top"></span> </button></div>
									<input type="text" name="tamanho_desc[]" class="form-control input-menor tamanho-produto NoEnableR" min="0" valor="10" readonly />
									<input type="hidden" name="tamanho[]" class="tam-posicao" />								
								</div>
						</div>
						<div class="form-group">
							<label for="quantidade">Quantidade:</label>
							<input type="text" name="quantidade[]" class="form-control input-menor qtd mask-numero" decimal="4" required />
						</div>		
					</div>
					<div class="row">
						<div class="form-group">
							<label>Operação:</label>
							<div class="input-group">
								<input type="search" name="operacao_descricao" class="form-control input-small operacao-descricao" autocomplete="off" autofocus />
								<button type="button" class="input-group-addon btn-filtro btn-filtro-operacao" tabindex="-1"><span class="fa fa-search"></span></button>
								<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-operacao" tabindex="-1"><span class="fa fa-close"></span></button>
							</div>
							<div class="pesquisa-res-container lista-tabela-container lista-operacao-container">
								<div class="pesquisa-res lista-operacao"></div>
							</div>
							
							<input type="hidden" name="_operacao_codigo[]"		field="item[operacao][]" />
							<input type="hidden" name="_operacao_ccusto[]"		field="item[ccusto][]" />
							<input type="hidden" name="_operacao_ccontabil[]"	field="item[ccontabil][]" />
						</div>
						<div class="form-group">
							<label for="valor-unitario">Valor unitário:</label>
							<div class="input-group left-icon">
								<div class="input-group-addon"><span class="fa fa-usd"></span></div>
								<input type="text" name="valor_unitario[]" class="form-control valor mask-numero" decimal="4" />
							</div>
						</div>
						<div class="form-group">
							<label for="valor-unitario">Total:</label>
							<input type="text" class="form-control valor-total NoEnableR" readonly/>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label>Observação:</label>
							<div class="textarea-grupo">
								<textarea name="observacao_item[]" class="form-control obs" rows="1" cols="50"></textarea>
								<span class="contador"><span></span> caracteres restantes</span>
							</div>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash"><span class="glyphicon glyphicon-trash trash"></span></button>
						</div>
					</div>
			    </div>
			</div>
		    <button type="button" class="btn btn-info add-produto add-item-dinamico" title="Adicionar produto"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
		</fieldset>
	    
	    <fieldset>
	    	<legend>Indicação de possíveis fornecedores</legend>
	    	{{--
			<div class="empresa-container item-dinamico-container">
		    	<div class="empresa item-dinamico">
			--}}
				    <div class="form-group">
				    	<label for="empresa_descricao">Empresa:</label>
				    	<div class="input-group">
					        <input type="search" name="empresa_descricao" class="form-control input-maior empresa-descricao" autocomplete="off" />
					        <button type="button" class="input-group-addon btn-filtro btn-filtro-empresa" tabindex="-1"><span class="fa fa-search"></span></button>
							<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-empresa" tabindex="-1"><span class="fa fa-close"></span></button>
				        </div>
				        <div class="pesquisa-res-container lista-empresas-container"> <div class="pesquisa-res lista-empresas"></div> </div>
				        <input type="hidden" name="_empresa_id" />
				    </div>
				    <div class="form-group">
				    	<label for="fone">Telefone:</label>
				        <input type="text" name="fone" class="form-control fone" />
				    </div>
				    <div class="form-group">
				    	<label for="email">E-mail:</label>
				        <input type="text" name="email" class="form-control email" />
				    </div>
				    <div class="form-group">
				    	<label for="contato">Contato:</label>
				        <input type="text" name="contato" class="form-control contato" />
				    </div>
				    {{--
				     <div class="form-group">
				    	<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
				    </div>
				    --}}
			{{-- 
				</div>
			</div>
			--}}
			{{-- <button type="button" class="btn btn-info add-empresa add-item-dinamico" title="Adicionar empresa"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button> --}}
		</fieldset>
		
		<fieldset>
			<legend>Anexos</legend>
				<div class="anexo-container item-dinamico-container">

					<div class="anexo item-dinamico">
						
						<div class="form-group">
							<label for="anexo_descricao" class="descricao_arqivo" >Descrição:</label>
					        <input type="text" name="anexo_descricao" class="form-control NoEnableR" readonly />
							<input type="file" name="anexo_arquivo" class="form-control CLArquivo" />
						</div>

							<div class="mudar form-group">
						    	<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
						    </div>
						    <div class="mudar form-group">
						    	<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash"><span class="glyphicon glyphicon-trash trash"></span></button>
						    </div>
					    
					</div>
					
					<progress class="progres" value="0" max="100" ID="progress"></progress>
					
				</div>
				<button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
				
						
			<!-- </form> -->
		</fieldset>

    <!-- Modal -->
	<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Grade</h4>
					<button type="button" class="btn btn-default btn-popup-right desabilitar-tamanhos" data-dismiss="modal">
						<span class="glyphicon glyphicon-chevron-left"></span>
						Voltar
					</button>
				</div>
				<div class="modal-body" align="center">

                    <div class="form-group">
                        <button type="button" class="btn btn-danger settamanho T01" tamanho="00"  data-dismiss="modal" disabled> <span class="T01">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T02" tamanho="00"  data-dismiss="modal" disabled> <span class="T02">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T03" tamanho="00"  data-dismiss="modal" disabled> <span class="T03">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T04" tamanho="00"  data-dismiss="modal" disabled> <span class="T04">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T05" tamanho="00"  data-dismiss="modal" disabled> <span class="T05">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T06" tamanho="00"  data-dismiss="modal" disabled> <span class="T06">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T07" tamanho="00"  data-dismiss="modal" disabled> <span class="T07">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T08" tamanho="00"  data-dismiss="modal" disabled> <span class="T08">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T09" tamanho="00"  data-dismiss="modal" disabled> <span class="T09">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T10" tamanho="00"  data-dismiss="modal" disabled> <span class="T10">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T11" tamanho="00"  data-dismiss="modal" disabled> <span class="T11">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T12" tamanho="00"  data-dismiss="modal" disabled> <span class="T12">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T13" tamanho="00"  data-dismiss="modal" disabled> <span class="T13">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T14" tamanho="00"  data-dismiss="modal" disabled> <span class="T14">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T15" tamanho="00"  data-dismiss="modal" disabled> <span class="T15">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T16" tamanho="00"  data-dismiss="modal" disabled> <span class="T16">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T17" tamanho="00"  data-dismiss="modal" disabled> <span class="T17">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T18" tamanho="00"  data-dismiss="modal" disabled> <span class="T18">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T19" tamanho="00"  data-dismiss="modal" disabled> <span class="T19">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T20" tamanho="00"  data-dismiss="modal" disabled> <span class="T20">00</span> </button>
                    </div>

				</div>
			</div>
		</div>
	</div>

	</form>
	
	<div id="imgLocal"></div>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input-dinamic.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/_13010.js') }}"></script>
@endsection
