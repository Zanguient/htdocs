@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11110.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11110.css') }}" />
@endsection

@section('conteudo')
<div class="conteiner-tela" ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">    
	    <li>
	        <a href="{{ $permissaoMenu->INCLUIR ? url('/_11010/create') : '#' }}" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-primary btn-incluir" data-hotkey="f6">
	            <span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}
	        </a>
	    </li>               
	</ul>
	<div class="pesquisa-obj-container">
		<div class="input-group input-group-pesquisa">
			<input type="search" name="filtro_pesquisa" class="form-control filtro-obj" id="filter-btn-find" placeholder="Pesquise..." autocomplete="off" autofocus />
			<button type="button" class="input-group-addon btn-filtro btn-filtrar">
				<span class="fa fa-search"></span>
			</button>
		</div>
	</div>

    <fieldset id="index-list">
        <legend>Usuário:</legend>

		<div class="tabela-producao-itens">
			<div class="table-container">
			
				<table class="tb-itens-prod table table-bordered table-header">
					<thead>
						<tr>
							<th class="col-remessa">Remessa</th>
							<th class="col-talao">Talão</th>
							<th class="col-requisicao">Requisição</th>
						</tr>
					</thead>
				</table>

				<div class="scroll-table">
					<table class="tb-itens-prod table table-striped table-bordered table-hover table-body">
						<tbody>
							<tr  ng-repeat="usuario in vm.DADOS.USUARIOS track by $index">
								<td class="col-remessa">@{{usuario.USUARIO}}</td>
								<td class="col-talao">@{{usuario.USUARIO}}</td>
								<td class="col-requisicao">@{{usuario.USUARIO}}</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>

    </fieldset>
<div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11110.js') }}"></script>
@append
