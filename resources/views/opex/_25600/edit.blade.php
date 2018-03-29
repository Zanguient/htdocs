@extends('master')

@section('titulo')
13010 - Alterar Requisição de Compra
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13010.css') }}" />
@endsection

@section('topo')
<h4 class="navbar-left">13010 - Alterar Requisição de Compra</h4>
@endsection

@section('conteudo')
<input type="hidden" name="_vinculo_id" value="{{ $dado->VINCULO_ID }}" />


<form action="{{ route('_13010.update', $dado->ID) }}" url-redirect="{{ url('sucessoAlterar/_13010', $dado->ID) }}" method="POST" class="form-inline edit js-gravar">
	<input type="hidden" name="_method" value="PATCH">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="_req_info_editar" value="<?php echo $Editar ?>" />

		  <input type="hidden" name="_vinculo_id" value="{{ $vinculo }}" />

	<ul class="list-inline acoes">
		<li><button type="submit" class="btn gravar btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}" ><span class="glyphicon glyphicon-ok"></span> {{ Lang::get('master.gravar') }}</button></li>
		<li><a href="{{ url('_13010',$dado->ID) }}" class="btn gravar btn-danger btn-cancelar" data-hotkey="f11"><span class="glyphicon glyphicon-ban-circle"></span> {{ Lang::get('master.cancelar') }}</a></li>
	</ul>

	<div class="alert alert-warning" <?php if ($Editar == 0) echo 'style="display: none;"'; ?> >
	<p>Esta requisição esta vinculada a uma ou mais licitações</p>
		@foreach ($dado_edicao as $dados_edicao)
			<p><a href="/_13020/{{ $dados_edicao->LICITACAO_ID }}" target="_blank">Item de ID:{{ $dados_edicao->ID }} vinculado a licitação de ID:{{ $dados_edicao->LICITACAO_ID }}</a></p>
		@endforeach
	</div>

	<fieldset  >
		<legend>Informações gerais</legend>
		<div class="form-group">
			<label for="requisicao">Requisição:</label>
			<input type="text" name="requisicao" class="form-control input-menor" value="{{ $dado->ID }}" readonly />

		</div>
		<div class="ccusto-container">
	    	<div class="ccusto">
				<div class="form-group">
			        <label for="ccusto-descricao">Centro de custo:</label>
			        <div class="input-group">
			        	<input type="search" name="ccusto_descricao" id="ccusto-descricao" class="form-control input-medio" autocomplete="off" autofocus required value="{{ $dado->CCUSTO_DESCRICAO }}"<?php if ($Editar == 1) echo 'readonly'; ?> />
			        	<button type="button" class="input-group-addon btn-filtro btn-filtro-ccusto"><span class="fa fa-search"></span></button>
						<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccusto"><span class="fa fa-close"></span></button>
			        </div>
			        <div class="pesquisa-res-container lista-ccusto-container"> <div class="pesquisa-res lista-ccusto"></div> </div>
			        <input type="hidden" name="_ccusto_id" value="{{ $dado->CCUSTO }}" <?php if ($Editar == 1) echo 'readonly'; ?>/>
			    </div>
			</div>
		</div>
		<div class="gestor-container">
	    	<div class="gestor">
			    <div class="form-group">
			        <label for="gestor-descricao">Gestor:</label>
			        <div class="input-group">
			        	<input type="search" name="gestor_descricao" id="gestor-descricao" class="form-control" autocomplete="off" required value="{{ $dado->GESTOR }}" <?php if ($Editar == 1) echo 'readonly'; ?>/>
			        	<div class="input-group-addon"><span class="fa fa-search"></span></div>
			        </div>
			        <div class="pesquisa-res-container lista-gestores-container"> <div class="pesquisa-res lista-gestores"></div> </div>
			        <input type="hidden" name="_gestor_id" value="{{ $dado->USUARIO_GESTOR_ID }}" <?php if ($Editar == 1) echo 'readonly'; ?>/>
			    </div>
			</div>
		</div>
	    <div class="form-group">
	    	<label for="data">Data:</label>
	        <input type="date" name="data" id="data" class="form-control" required value="{{ $dado->DATA }}" <?php if ($Editar == 1) echo 'readonly'; ?>/>
	    </div>
	    <div class="form-group">
	    	<label for="data-utilizacao" title="Data prevista para utilização do material">Data para utilização:</label>
	        <input type="date" name="data_utilizacao" id="data-utilizacao" class="form-control" value="{{ $dado->DATA_UTILIZACAO }}" <?php if ($Editar == 1) echo 'readonly'; ?>/>
	    </div>
	    <div class="form-group">
	    	<input type="checkbox" name="urgencia" class="form-control" <?php if ($dado->URGENCIA == 1) echo 'checked'; ?> <?php if ($Editar == 1) echo 'disabled'; ?>/>
	    	<label for="urgencia">Urgência</label>
	    </div>
    </fieldset>

    <fieldset >
    	<legend>Informações sobre o produto</legend>
	    <div class="produto-container item-dinamico-container" >

            @if (count($dado_itens) > 0)
            @foreach ($dado_itens as $dado_item)
    	    	<div class="produto item-dinamico">

                        <div class="form-group">
        			    	<label for="produto-id">Produto:</label>

                            <div class="input-group">
        				        <input type="search" name="produto_descricao" class="form-control input-maior produto-descricao" autocomplete="off" required value="{{ $dado_item->PRODUTO_DESCRICAO }}" readonly/>
        				        <div class="input-group-addon"><span class="fa fa-search"></span></div>
        				    </div>
        				    <div class="pesquisa-res-container lista-produtos-container"> <div class="pesquisa-res lista-produtos"></div> </div>
        				    <input type="hidden" name="_produto_id[]" value="{{ $dado_item->PRODUTO_ID }}" />
        				    <input type="hidden" name="_produto_descricao[]" value="{{ $dado_item->PRODUTO_DESCRICAO }}" />
        				    <input type="hidden" name="_req_item_id[]" value="{{ $dado_item->REQ_ITEM_ID }}" />
        				    <input type="hidden" class="marc marcaexcluiritem" name="_req_item_excluir[]" value="0" />
        				    <input type="hidden" class="marc marcaeditaritem" name="_req_item_editar[]" value="0" />
        			    </div>
        			    <div class="form-group">
        			    	<label for="um">UM:</label>
        			        <input type="text" name="um[]" class="form-control input-menor" maxlength="3" required value="{{ $dado_item->UM}}" readonly/>
        			    </div>
        			    <div class="form-group">
				    	<label for="tamanho">Tamanho:</label>
                            <div class="input-group">
                                <div class="input-group-addon tamanho"><button type="button" class="btn btn-primary btn-sm sett marceditavel"  data-toggle="modal" data-target="#modal-edit" disabled><span class="glyphicon glyphicon-triangle-top"></span> </button></div>
				                <input type="text" name="tamanho[]" class="form-control input-menor tamanho-produto NoEnableR" min="0" value="{{ $dado_item->TAMANHO }}"  readonly/>
                        </div>
				        </div>
        			    <div class="form-group">
        			    	<label for="quantidade">Quantidade:</label>
        			        <input type="text" name="quantidade[]" class="form-control qtd mask-numero" min="1" decimal="0" required value="<?php  echo str_replace('.', ',', $dado_item->QUANTIDADE) ; ?>" readonly/>
        			    </div>
        			    <div class="form-group">
        			    	<label for="valor-unitario">Valor unitário:</label>
        			    	<div class="input-group">
        			    		<div class="input-group-addon"><span class="fa fa-usd"></span></div>
        			        	<input type="text" name="valor_unitario[]" class="form-control valor mask-numero" decimal="4" min="0" value="<?php  echo str_replace('.', ',', $dado_item->VALOR_UNITARIO) ; ?>"  readonly/>
        			        </div>
        			    </div>

                        <div class="form-group">
        			    	<label for="valor-unitario">Total:</label>
        			        <input type="text" class="form-control valor-total NoEnableR" readonly/>
			            </div>

                        <div class="form-group">
                	        <button type="button" class="btn btn-danger excluir-item-dinamico remove" <?php if ($Editar == 1) echo 'disabled'; ?>><span class="glyphicon glyphicon-trash remove"></span></button>
                		</div>

                	    <div class="form-group">
                			<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash" <?php if ($Editar == 1) echo 'disabled'; ?>><span class="glyphicon glyphicon-trash trash"></span></button>
                		</div>

                        <div class="form-group">
                	        <button type="button" class="btn btn-primary editar-item-dinamico pencil" <?php if ($Editar == 1) echo 'disabled'; ?>><span class="glyphicon glyphicon-edit pencil"></span></button>
                		</div>

    		    </div>
		    @endforeach
            @else
                <div class="produto item-dinamico">

                    <div class="form-group">
       			    	<label for="produto-id">Produto:</label>
    			    	<div class="input-group">
    				        <input type="search" name="produto_descricao" class="form-control input-maior produto-descricao" autocomplete="off" required value="" readonly/>
    				        <div class="input-group-addon"><span class="fa fa-search"></span></div>
    				    </div>
    				    <div class="pesquisa-res-container lista-produtos-container"> <div class="pesquisa-res lista-produtos"></div> </div>
    				    <input type="hidden" name="_produto_id[]" value="" />
    				    <input type="hidden" name="_produto_descricao[]" value="" />
    				    <input type="hidden" name="_req_item_id[]" value="" />
    				    <input type="hidden" class="marc marcaexcluiritem" name="_req_item_excluir[]" value="0" />
    				    <input type="hidden" class="marc marcaeditaritem" name="_req_item_editar[]" value="0" />
    			    </div>
    			    <div class="form-group">
    			    	<label for="um">UM:</label>
    			        <input type="text" name="um[]" class="form-control input-menor" maxlength="3" required value="" readonly/>
    			    </div>
    			    <div class="form-group">
				    	<label for="tamanho">Tamanho:</label>
                            <div class="input-group">
                                <div class="input-group-addon tamanho"><button type="button" class="btn btn-primary btn-sm sett"  data-toggle="modal" data-target="#modal-edit" disabled><span class="glyphicon glyphicon-triangle-top"></span> </button></div>
				                <input type="text" name="tamanho[]" class="form-control input-menor tamanho-produto NoEnableR" min="0" valor="10" readonly />
                        </div>
				    </div>
    			    <div class="form-group">
    			    	<label for="quantidade">Quantidade:</label>
    			        <input type="text" name="quantidade[]" class="form-control qtd" min="1" required value="" onkeypress="return SomenteNumeroePonto(this,event)" readonly/>
    			    </div>
    			    <div class="form-group">
    			    	<label for="valor-unitario">Valor unitário:</label>
    			    	<div class="input-group">
    			    		<div class="input-group-addon"><span class="fa fa-usd"></span></div>
    			        	<input type="text" name="valor_unitario[]" class="form-control valor" min="0" value="" onkeypress="return SomenteNumeroePonto(this,event)" readonly/>
    			        </div>
    			    </div>

                    <div class="form-group">
    			    	<label for="valor-unitario">Total:</label>
    			        <input type="text" class="form-control valor-total NoEnableR" readonly/>
			        </div>

                    <div class="form-group">
            	        <button type="button" class="btn btn-danger excluir-item-dinamico remove" ><span class="glyphicon glyphicon-trash remove"></span></button>
            		</div>

            	    <div class="form-group">
            			<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash" ><span class="glyphicon glyphicon-trash trash"></span></button>
            		</div>

                    <div class="form-group">
            	        <button type="button" class="btn btn-primary editar-item-dinamico pencil" ><span class="glyphicon glyphicon-edit pencil"></span></button>
            		</div>
                </div>
			@endif
        </div>
	    <button type="button" class="btn btn-info add-produto add-item-dinamico" title="Adicionar produto"><span class="glyphicon glyphicon-plus"></span> Adicionar</button>
	</fieldset>

    <fieldset>
    	<legend>Indicação de possíveis fornecedores</legend>
    	<div class="empresa-container item-dinamico-container">
	    	<div class="empresa item-dinamico">
			    <div class="form-group">
			    	<label for="empresa_descricao">Empresa:</label>
			    	<div class="input-group">
				        <input type="search" name="empresa_descricao" class="form-control input-maior empresa-descricao" autocomplete="off" value="{{ $dado->EMPRESA_DESCRICAO }}" readonly/>
				        <div class="input-group-addon"><span class="fa fa-search"></span></div>
			        </div>
			        <div class="pesquisa-res-container lista-empresas-container"> <div class="pesquisa-res lista-empresas"></div> </div>
			        <input type="hidden" name="_empresa_id" value="{{ $dado->EMPRESA_ID }}" />
			    </div>
			    <div class="form-group">
			    	<label for="fone">Telefone:</label>
			        <input type="text" name="fone" class="form-control fone" value="{{ $dado->EMPRESA_FONE }}" readonly/>
			    </div>
			    <div class="form-group">
			    	<label for="email">E-mail:</label>
			        <input type="email" name="email" class="form-control email" value="{{ $dado->EMPRESA_EMAIL }}" readonly/>
			    </div>
			    <div class="form-group">
			    	<label for="contato">Contato:</label>
			        <input type="text" name="contato" class="form-control contato" value="{{ $dado->EMPRESA_CONTATO }}" readonly/>
			    </div>

                <div class="form-group">
            	        <button type="button" class="btn btn-primary editar-item-dinamico" <?php if ($Editar == 1) echo 'disabled'; ?>><span class="glyphicon glyphicon-edit"></span></button>
            	</div>

			</div>

		</div>
		{{-- <button type="button" class="btn btn-default add-empresa add-item-dinamico" title="Adicionar empresa"><span class="glyphicon glyphicon-plus"></span> Adicionar</button> --}}
	</fieldset>

	<fieldset>
			<legend>Anexos</legend>

				<div class="anexo-container item-dinamico-container" ID='AA'>
				@if (count($arquivo_itens) > 0)
				    @foreach ($arquivo_itens as $arquivo_itens)
					<div class="anexo item-dinamico" ID='BB'>

						<div class="form-group" ID='ARQ'>
							<label for="anexo_descricao">Descrição:</label>
					        <input type="text" name="anexo_descricao" class="form-control NoEnableR" value="{{ $arquivo_itens->OBSERVACAO}}" readonly/>
							<input type="file" name="anexo_arquivo" class="form-control CLArquivo" value="{{ $arquivo_itens->OBSERVACAO}}" disabled/>
							<input type="hidden" class="marc" name="_vinculo_Arquivo_id[]" value="{{ $arquivo_itens->ID}}" />
                            <input type="hidden" class="marc marcaexcluiritem" name="_req_arquivo_excluir[]" value="0" />

						</div>

							<div class="mudar form-group">
						    <button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove" ></span></button>
						    </div>
						    <div class="mudar form-group">
						    <button type="button" class="btn btn-danger excluir-item-dinamico excluir-arquivo trash"><span class="glyphicon glyphicon-trash trash"></span></button>
						    </div>

					</div>
					@endforeach
				@else
				    <div class="anexo item-dinamico">

						<div class="form-group">
							<label for="anexo_descricao">Descrição:</label>
					        <input type="text" name="anexo_descricao" class="form-control NoEnableR" readonly/>
							<input type="file" name="anexo_arquivo" class="form-control CLArquivo" />
                            <input type="hidden" name="_vinculo_Arquivo_id[]" value="0" />
                            <input type="hidden" class="marcaexcluiritem" name="_req_arquivo_excluir[]" value="0" />

						</div>

							<div class="mudar form-group">
						    <button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
						    </div>
						    <div class="mudar form-group">
						    <button type="button" class="btn btn-danger excluir-item-dinamico excluir-arquivo trash"><span class="glyphicon glyphicon-trash trash"></span></button>
						    </div>

					</div>
				@endif


				</div>
				<button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo"><span class="glyphicon glyphicon-plus"></span> Adicionar</button>

				<div class="form-group" ID="form-group">
					<progress class="progres" value="0" max="100" ID="progress"></progress>
				</div>

			<!-- </form> -->
		</fieldset>

        <div class="div-tamanho-produto" style="display: none;">

		</div>

    <!-- Modal -->
	<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title TGRADE" id="myModalLabel">GRADE</h4>
				</div>
				<div class="modal-body" align="center">

                    <div class="form-group">
                        <button type="button" class="btn btn-danger settamanho T01" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T01">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T02" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T02">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T03" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T03">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T04" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T04">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T05" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T05">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T06" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T06">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T07" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T07">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T08" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T08">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T09" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T09">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T10" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T10">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T11" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T11">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T12" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T12">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T13" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T13">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T14" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T14">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T15" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T15">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T16" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T16">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T17" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T17">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T18" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T18">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T19" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T19">00</span> </button>
                        <button type="button" class="btn btn-danger settamanho T20" tamanho="00" style="margin-top: 20px;" data-dismiss="modal" disabled> <span class="T20">00</span> </button>
                    </div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default desabilitar-tamanhos" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span>Voltar</button>
				</div>
			</div>
		</div>
	</div>

</form>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/arquivo.js') }}"></script>
	<script src="{{ elixir('assets/js/_13010.js') }}"></script>
@endsection
