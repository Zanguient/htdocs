@extends('master')

@section('titulo')
{{ Lang::get('compras/_13020.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13020.css') }}">   
@endsection

@section('conteudo')
<ul class="list-inline acoes">
	<li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_13020/create') : '#' }}" class="btn btn-primary btn-incluir" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }}>
			<span class="glyphicon glyphicon-plus"></span>
			 {{ Lang::get('master.incluir') }}
		</a>
	</li>
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>Licitações cadastradas</legend>

	<table class="table table-striped table-bordered table-hover lista-obj">
		<thead>
		<tr>
			<th>Id Lic.</th>
			<th>Descrição</th>
			<th>Famílias</th>
			<th>Data</th>
			<th>Validade</th>
			<th>Requerente</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($dados as $dado)
		<tr link="{{ url('_13020', $dado->ID) }}">
			<td>{{ $dado->ID }}</td>
			<td>{{ $dado->DESCRICAO }}</td>
			<td>{{ $dado->FAMILIAS }}</td>
			<td>{{ date_format(date_create($dado->DATAHORA), 'd/m/Y') }}</td>
			<td>{{ date_format(date_create($dado->DATA_VALIDADE), 'd/m/Y') }}</td>
			<td>{{ $dado->REQUERENTE }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	<!--<button type="button" class="btn btn-default carregar-pagina"><span class="glyphicon glyphicon-triangle-bottom"></span> Carregar mais...</button>-->
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13020.js') }}"></script>
@append
