@extends('master')

@section('titulo')
{{ Lang::get('compras/_13010.titulo') }}
@endsection

@section('conteudo')
<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_13010/create') : '#' }}" class="btn btn-primary btn-incluir" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus" ></span>
			 {{ Lang::get('master.incluir') }}
		</a>
	</li>

</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input 
			type="search" 
			name="filtro_obj" 
			class="form-control pesquisa filtro-obj" 
			placeholder="Pesquise..." 
			autocomplete="off" 
			autofocus
			value="{{ $filtro_obj }}">
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>Requisições cadastradas</legend>
	
	<div id="table-filter" class="table-filter">
		<div>
			<label>{{ Lang::get('master.status') }}:</label>
			<select id="filter-status">
				<option value="0" {{ $status == '0' ? 'selected' : '' }}>{{ Lang::get('master.todos') }}</option>
				<option value="1" {{ $status == '1' ? 'selected' : '' }}>{{ Lang::get('master.pendentes') }}</option>
				<option value="2" {{ $status == '2' ? 'selected' : '' }}>{{ Lang::get('master.baixadas') }}</option>
			</select>
		</div>

		<button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
	</div>

	<table class="table table-striped table-bordered table-hover lista-obj selectable">
		<thead>
		<tr>
			<th>Id</th>
			<th>Descrição</th>
			<th>Requerente</th>
			<th>Centro de Custo</th>
			<th>OC</th>
			<th>Urgente?</th>
			<th>Nec. Lic.?</th>
			<th>Data</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($dados as $dado)
		<tr link="{{ url('_13010', $dado->ID) }}">
			<td>{{ $dado->ID }}</td>
			<td>{{ $dado->DESCRICAO }}</td>
			<td>{{ $dado->USUARIO }}</td>
			<td>{{ $dado->CCUSTO_DESCRICAO }}</td>
			<td>{{ $dado->OC ? $dado->OC : '-' }}</td>
			<?php if($dado->URGENCIA > 0) { $classe_td = 'green'; $classe_span = 'glyphicon-ok'; } else { $classe_td = 'red'; $classe_span = 'glyphicon-remove'; } ?>
			<td class="{{ $classe_td }}"><span class="glyphicon {{ $classe_span }}"></span></td>
			<?php if($dado->NECESSITA_LICITACAO > 0) { $classe_td = 'green'; $classe_span = 'glyphicon-ok'; } else { $classe_td = 'red'; $classe_span = 'glyphicon-remove'; } ?>
			<td class="{{ $classe_td }}"><span class="glyphicon {{ $classe_span }}"></span></td>
			<td>{{ date_format(date_create($dado->DATA), 'd/m/Y') }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	<button type="button" class="btn btn-default carregar-pagina"><span class="glyphicon glyphicon-triangle-bottom"></span> Carregar mais...</button>
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13010.js') }}"></script>
@endsection
