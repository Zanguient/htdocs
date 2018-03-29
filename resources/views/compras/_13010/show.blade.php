@extends('master')

@section('titulo')
{{ Lang::get('compras/_13010.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13010.css') }}" />
@endsection

@section('conteudo')

<ul class="list-inline acoes">
  	<li><a href="{{ $permissaoMenu->ALTERAR ? route('_13010.edit', $dado->ID) : '#' }}" class="btn btn-primary btn-alterar" data-hotkey="f9" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}><span class="glyphicon glyphicon-edit"></span> {{ Lang::get('master.alterar') }}</a></li>
	<li>
		<form action="{{ $permissaoMenu->EXCLUIR ? route('_13010.destroy', $dado->ID) : '#' }}" method="POST" class="form-deletar">
		    <input type="hidden" name="_method" value="DELETE">
		    <input type="hidden" name="_token" value="{{ csrf_token() }}">
		    <button type="button" class="btn btn-danger btn-excluir {{ $permissaoMenu->EXCLUIR ? 'excluir' : '' }}" data-hotkey="f12" data-toggle="modal" data-target="{{ $permissaoMenu->EXCLUIR ? '#confirmDelete' : '' }}" {{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }}><span class="glyphicon glyphicon-trash"></span> {{ Lang::get('master.excluir') }}</button>
		</form>
	</li>
	@if ($dado->NECESSITA_LICITACAO == 0 && $controle == 1)
    <li><a href="{{ url('_13041', $dado->ID) }}" target="_blank" class="btn btn-default btn-gerar-oc" data-hotkey="alt+g"><span class="glyphicon glyphicon-new-window"></span> {{ Lang::get('compras/_13010.gerar-oc') }}</a></li>
	@endif
	<li>
		<a 
			href="{{ url('_13010') }}" 
			class="btn btn-default btn-voltar" 
			data-hotkey="f11">

			<span class="glyphicon glyphicon-chevron-left"></span>
			 {{ Lang::get('master.voltar') }}
		</a>

		<script type="text/javascript">
				
			// Se foi feito um filtro antes, 
			// troca as URL's que voltam para a página anterior 
			// pela URL que contém os parâmetros do filtro.
			if (localStorage.getItem('13010FiltroUrl') != null)
				$(".btn-voltar").attr("href", localStorage.getItem("13010FiltroUrl"));

		</script>
	</li>
	<li class="align-right">
		<button type="button" class="btn btn-grey160 gerar-historico" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
			<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
		</button>
	</li>
</ul>

@include('helper.include.view.historico',['tabela' => 'TBREQUISICAO_OC', 'id' => $dado->ID, 'no_button' => 'true'])

<form class="form-inline info-container">

	<fieldset readonly>
		<legend>Informações gerais</legend>
		<div class="row">
			<div class="form-group">
				<label for="requisicao">Requisição:</label>
				<input type="text" name="requisicao" class="form-control input-menor" value="{{ $dado->ID }}" readonly autofocus />
			</div>
			<div class="form-group">
				<label>Descrição:</label>
				<input type="text" name="descricao" class="form-control input-maior" value="{{ $dado->DESCRICAO }}" required readonly />
			</div>
			<div class="ccusto-container">
				<div class="ccusto">
					<div class="form-group">
						<label for="ccusto-descricao">Centro de custo:</label>
						<div class="input-group">
							<input type="search" name="ccusto_descricao" id="ccusto-descricao" class="form-control input-medio" value="{{ $dado->CCUSTO_DESCRICAO }}"  autocomplete="off" required readonly/>
							<button type="button" class="input-group-addon btn-filtro btn-filtro-ccusto" disabled tabindex="-1"><span class="fa fa-search"></span></button>
							<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccusto" disabled tabindex="-1"><span class="fa fa-close"></span></button>
						</div>
						<div class="pesquisa-res-container lista-ccusto-container"> <div class="pesquisa-res lista-ccusto"></div> </div>
						<input type="hidden" name="_ccusto_id" value="{{ $dado->CCUSTO }}" />
					</div>
				</div>
			</div>
			<div class="gestor-container">
				<div class="gestor">
					<div class="form-group">
						<label for="gestor-descricao">Gestor:</label>
						<div class="input-group">
							<input type="search" name="gestor_descricao" id="gestor-descricao" class="form-control input-medio" autocomplete="off" required value="{{ $dado->GESTOR }}" readonly />
							<button type="button" class="input-group-addon btn-filtro btn-filtro-gestor" disabled tabindex="-1"><span class="fa fa-search"></span></button>
							<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-gestor" disabled tabindex="-1"><span class="fa fa-close"></span></button>
						</div>
						<div class="pesquisa-res-container lista-gestores-container"> <div class="pesquisa-res lista-gestores"></div> </div>
						<input type="hidden" name="_gestor_id" value="{{ $dado->USUARIO_GESTOR_ID }}" />
						<input type="hidden" name="_gestor_email" value="{{ $dado->GESTOR_EMAIL }}" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="data" title="Estabelecimento">Estab.:</label>
				<input type="number" name="estabelecimento_id" class="form-control" min="1" max="6" required value="{{ $dado->ESTABELECIMENTO_ID }}" readonly />
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label for="data">Data:</label>
				<input type="date" name="data" id="data" class="form-control" required value="{{ $dado->DATA }}" readonly/>
			</div>
			<div class="form-group">
				<label for="data-utilizacao" title="Data prevista para utilização do material">Data para utilização:</label>
				<input type="date" name="data_utilizacao" id="data-utilizacao" class="form-control" value="{{ $dado->DATA_UTILIZACAO }}" readonly/>
			</div>
			<div class="form-group">
				<input type="checkbox" name="urgencia" id="urgencia" class="form-control" <?php if ($dado->URGENCIA == 1) echo 'checked'; ?> disabled/>
				<label for="urgencia">Urgência?</label>
			</div>
			<div class="form-group">
				<input type="checkbox" name="necessita_licitacao" id="necessita-licitacao" class="form-control" <?php if ($dado->NECESSITA_LICITACAO == 1) echo 'checked'; ?> disabled />
				<label for="necessita-licitacao">Necessita licitação?</label>
			</div>
		</div>
    </fieldset>
    
    <fieldset readonly>
    	<legend>Informações sobre o produto</legend>
	    <div class="produto-container item-dinamico-container">
            @if (count($dado_itens) > 0)
            @foreach ($dado_itens as $dado_item)
			<div class="produto item-dinamico">
				<div class="row">
					<div class="form-group">
						<label for="produto-id">Produto:</label>
						<div class="input-group">
							<input type="search" name="produto_descricao" class="form-control input-maior produto-descricao" autocomplete="off" required value="{{ $dado_item->PRODUTO_ID }} - {{ $dado_item->PRODUTO_DESCRICAO }}" readonly />
							<button type="button" class="input-group-addon btn-filtro btn-filtro-produto" disabled tabindex="-1"><span class="fa fa-search"></span></button>
							<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-produto" disabled tabindex="-1"><span class="fa fa-close"></span></button>
						</div>
						<div class="pesquisa-res-container lista-produtos-container"> <div class="pesquisa-res lista-produtos"></div> </div>
						<input type="hidden" name="_produto_id[]" class="_produto-id" value="{{ $dado_item->PRODUTO_ID }}" />
						<input type="hidden" name="_produto_descricao[]" value="{{ $dado_item->PRODUTO_DESCRICAO }}" />
						<input type="hidden" name="_req_item_id[]" />
					</div>
					<div class="form-group">
						<label for="um">UM:</label>
						<input type="text" name="um[]" class="form-control input-menor" maxlength="3" required value="{{ $dado_item->UM}}" readonly/>
					</div>
					<div class="form-group">
						<label for="tamanho">Tamanho:</label>
						<input type="text" name="tamanho[]" class="form-control input-menor" value="{{ $dado_item->TAMANHO_DESCRICAO }}" readonly/>
					</div>
					<div class="form-group">
						<label for="quantidade">Quantidade:</label>
						<input type="text" name="quantidade[]" class="form-control qtd" min="1" required value="<?php  echo str_replace('.', ',', $dado_item->QUANTIDADE) ; ?>" readonly/>
					</div>		
				</div>
				<div class="row">
					<div class="form-group">
						<label>Operação:</label>
						<div class="input-group">
							<input type="search" name="operacao_descricao" class="form-control input-small operacao-descricao" autocomplete="off" value="{{ $dado_item->OPERACAO_CODIGO }}" readonly />
							<button type="button" class="input-group-addon btn-filtro btn-filtro-operacao" disabled tabindex="-1"><span class="fa fa-search"></span></button>
							<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-operacao" disabled tabindex="-1"><span class="fa fa-close"></span></button>
						</div>
						<div class="pesquisa-res-container lista-tabela-container lista-operacao-container">
							<div class="pesquisa-res lista-operacao"></div>
						</div>

						<input type="hidden" name="_operacao_codigo[]"		field="item[operacao][]"	value="{{ $dado_item->OPERACAO_CODIGO }}" />
						<input type="hidden" name="_operacao_ccusto[]"		field="item[ccusto][]"		value="{{ $dado_item->OPERACAO_CCUSTO }}" />
						<input type="hidden" name="_operacao_ccontabil[]"	field="item[ccontabil][]"	value="{{ $dado_item->OPERACAO_CCONTABIL }}" />
					</div>
					<div class="form-group">
						<label for="valor-unitario">Valor unitário:</label>
						<div class="input-group left-icon">
							<div class="input-group-addon"><span class="fa fa-usd"></span></div>
							<input type="text" name="valor_unitario[]" class="form-control valor" min="0" value="<?php  echo str_replace('.', ',', $dado_item->VALOR_UNITARIO) ; ?>" readonly/>
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
							<textarea name="observacao_item[]" class="form-control obs" rows="1" cols="50" readonly>{{ $dado_item->OBSERVACAO }}</textarea>
							<span class="contador"><span></span> caracteres restantes</span>
						</div>
					</div>
				</div>
		    </div>
		    @endforeach
            @else
                <div class="produto item-dinamico">
					<div class="row">
						<div class="form-group">
							<label for="produto-id">Produto:</label>
							<div class="input-group">
								<input type="search" name="produto_descricao" class="form-control input-maior produto-descricao" autocomplete="off" required value="" disabled/>
								<div class="input-group-addon"><span class="fa fa-search"></span></div>
							</div>
							<div class="pesquisa-res-container lista-produtos-container"> <div class="pesquisa-res lista-produtos"></div> </div>
							<input type="hidden" name="_produto_id[]" value="" />
							<input type="hidden" name="_produto_descricao[]" value="" />
							<input type="hidden" name="_req_item_id[]" value="" />
							<input type="hidden" class="marcaexcluiritem" name="_req_item_excluir[]" value="0" />
							<input type="hidden" class="marcaeditaritem" name="_req_item_editar[]" value="0" />
						</div>
						<div class="form-group">
							<label for="um">UM:</label>
							<input type="text" name="um[]" class="form-control input-menor" maxlength="3" required value="" readonly/>
						</div>
						<div class="form-group">
							<label for="tamanho">Tamanho:</label>
							<input type="number" name="tamanho[]" class="form-control input-menor" min="0" value="" readonly/>
						</div>
						<div class="form-group">
							<label for="quantidade">Quantidade:</label>
							<input type="text" name="quantidade[]" class="form-control qtd" min="1" required value="" readonly/>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="valor-unitario">Valor unitário:</label>
							<div class="input-group left-icon">
								<div class="input-group-addon"><span class="fa fa-usd"></span></div>
								<input type="text" name="valor_unitario[]" class="form-control dinheiro valor" min="0" value="" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label for="valor-unitario">Total:</label>
							<input type="text" class="form-control valor-total NoEnableR" readonly/>
						</div>
						<div class="form-group">
							<label>Observação:</label>
							<div class="textarea-grupo">
								<textarea name="observacao_item[]" class="form-control obs" rows="1" cols="50" readonly></textarea>
								<span class="contador"><span></span> caracteres restantes</span>
							</div>
						</div>
					</div>
			@endif
		</div>
	    <button type="button" class="btn btn-info add-produto add-item-dinamico" title="Adicionar produto" disabled><span class="glyphicon glyphicon-plus" ></span> {{ Lang::get('master.adicionar') }}</button>
	</fieldset>

    <fieldset readonly>
    	<legend>Indicação de possíveis fornecedores</legend>
		{{--
    	<div class="empresa-container item-dinamico-container">
	    	<div class="empresa item-dinamico">
		--}}
			    <div class="form-group">
			    	<label for="empresa_descricao">Empresa:</label>
			    	<div class="input-group">
				        <input type="search" name="empresa_descricao" class="form-control input-maior empresa-descricao" autocomplete="off" value="{{ $dado->EMPRESA_DESCRICAO }}" readonly/>
						<button type="button" class="input-group-addon btn-filtro btn-filtro-empresa" disabled><span class="fa fa-search"></span></button>
						<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-empresa" disabled><span class="fa fa-close"></span></button>
			        </div>
			        <div class="pesquisa-res-container lista-empresas-container"> <div class="pesquisa-res lista-empresas"></div> </div>
			        <input type="hidden" name="_empresa_id" />
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
		{{--	
			</div>
		</div>
		--}}
	</fieldset>

	<fieldset readonly>
        <legend>Anexos</legend>

        <div class="anexo-container item-dinamico-container" ID='AA'>

        @if (count($arquivo_itens) > 0)
            @foreach ($arquivo_itens as $arquivo_itens)
            <div class="anexo item-dinamico" ID='BB'>
                <div class="form-group" ID='ARQ'>
                    <label for="anexo_descricao">Descrição:</label>
                    <input type="text" name="anexo_descricao" class="form-control" value="{{ $arquivo_itens->OBSERVACAO}}" readonly/>
                    <input type="file" name="anexo_arquivo" class="form-control CLArquivo" value="{{ $arquivo_itens->OBSERVACAO}}" disabled />
                    <input type="hidden" name="_vinculo_id[]" value="{{ $arquivo_itens->ID}}" readonly/>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-info view-arquivo" ><span class="glyphicon glyphicon-eye-open"></span></button>
                </div>
            </div>
            @endforeach
        @else
            <div class="anexo item-dinamico" ID='BB'>

                <div class="form-group" ID='ARQ'>
                    <label for="anexo_descricao">Descrição:</label>
                    <input type="text" name="anexo_descricao" class="form-control" readonly/>
                    <input type="file" name="anexo_arquivo" class="form-control CLArquivo" disabled/>
                    <input type="hidden" name="_vinculo_id[]" value="0" />
                    {{-- Deve ser adicionada a id do vinculo do arquivo para que ele possa ser excluido --}}

                </div>

            </div>
        @endif
            <progress class="progres" value="0" max="100" ID="progress"></progress>
        </div>
        <button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo" disabled><span class="glyphicon glyphicon-plus" disabled></span> {{ Lang::get('master.adicionar') }}</button>
        <!-- </form> -->
	</fieldset>
	
    <div class="visualizar-arquivo">
		<a class="btn btn-default download-arquivo" href="" download data-hotkey="alt+b">
			<span class="glyphicon glyphicon-download"></span>
			{{ Lang::get('master.download') }}
		</a>
		<input type="hidden" class="arquivo_nome_deletar" name="_arquivo_nome[]" />
		<button type="button" class="btn btn-default esconder-arquivo" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{{ Lang::get('master.voltar') }}
		</button>
		<object></object>
	</div>
		
@include('helper.include.view.delete-confirm')

@endsection

@section('script')
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input-dinamic.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13010.js') }}"></script>
    <script src="{{ elixir('assets/js/file.js') }}"></script>
@append
