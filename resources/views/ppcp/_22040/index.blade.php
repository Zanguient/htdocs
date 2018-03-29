@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/22040.css') }}" />
@endsection

@section('conteudo')

<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_22040/create') : '#' }}" class="btn btn-primary" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get('master.incluir') }}
		</a>
	</li>
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>{{ Lang::get($menu.'.remessas-geradas') }}</legend>
	<div id="table-filter" class="table-filter">
		{{-- Estabelecimento --}}
		@include('admin._11020.include.listar', [
			'opcao_todos' => 'true'
		])
		<div>
			<label>{{ Lang::get('master.perfil') }}:</label>
            <select id="filter-perfil" class="perfil">
				<option value="" selected>{{ Lang::get('master.todos') }}</option>
				<option value="D">{{ Lang::get($menu.'.dublagem') }}</option>
				<option value="T">{{ Lang::get($menu.'.torno') }}</option>
				<option value="M">{{ Lang::get($menu.'.metradeira') }}</option>
				<option value="E">{{ Lang::get($menu.'.espumacao') }}</option>
			</select>
		</div>        
		<div>
			<label>{{ Lang::get('master.status') }}:</label>
            <select id="filter-status" class="status">
				<option selected value="-1">{{ Lang::get('master.todos') }}</option>
				<option value="1">{{ Lang::get('master.ativo') }}</option>
				<option value="0">{{ Lang::get('master.inativo') }}</option>
			</select>
		</div>
		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-inicial" id="data-inicial" value="{{ date('Y-m-d', strtotime('-1 month')) }}" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-final" id="data-final" value="{{ date('Y-m-t') }}" />
		</div>
		<button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
	</div>
    <table class="table table-striped table-bordered table-hover lista-obj">
        <thead>
        <tr>
			<th class="t-status"></th>
            <th>{{ Lang::get($menu.'.remessa') }}</th>
            <th>{{ Lang::get('master.tipo') }}</th>
            <th>{{ Lang::get('master.familia') }}</th>
            <th>{{ Lang::get('master.data') }}</th>
            <th class="gerar-rem-comp" title="{{ Lang::get($menu.'.gerar-rem-compon') }}">{{ Lang::get($menu.'.gerar-rem-compon-abrev') }}</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
	
	<ul class="legenda">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get('master.ativo') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get('master.inativo') }}</div>
		</li>
	</ul>
	
</fieldset>

@section('popup-form-start')

	<form action="{{ route('_22040.update') }}" url-redirect="{{ url('sucessoAtualizar/_22040') }}" method="POST" class="form-inline js-gravar edit popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	
@endsection

@section('popup-head-button')
<?php
/*
	<li>
		<a href="{{ route('_22040.edit', 0) }}" class="btn btn-primary btn-popup-left" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span>
			{{ Lang::get('master.alterar') }}
		</a>
	</li>
	<li>
		<button type="submit" class="btn btn-success btn-popup-left js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}" disabled>
			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get('master.gravar') }}
		</button>
	</li>
	<li>
		<button type="button" class="btn btn-danger btn-popup-left" data-hotkey="f11" data-loading-text="{{ Lang::get('master.excluindo') }}">
			<span class="glyphicon glyphicon-trash"></span>
			{{ Lang::get('master.excluir') }}
		</button>
	</li>
 */
?>

@endsection

@section('popup-head-title')
	
	<h4 class="modal-title">{{ Lang::get($menu.'.remessa-detalhes') }}</h4>
	
@endsection

@section('popup-body')

	

@endsection

@section('popup-form-end')
	</form>
@endsection


@endsection

@include('helper.include.view.pdf-imprimir')
@include('helper.include.view.historico',[
	'tabela'	=> 'TBREMESSA', 
	'id'		=> 23068, 
	'no_button'	=> 'true'
])

@section('script')
	<script src="{{ elixir('assets/js/pdf.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js'  ) }}"></script>
	<script src="{{ elixir('assets/js/_22040-index.js') }}"></script>
@append
