@extends('master')

@section('titulo')
13010 - Requisição de Compra
@endsection

@section('topo')
<h4 class="navbar-left">13010 - Requisição de Compra</h4>	
@endsection

@section('conteudo')
<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_13010/create') : '#' }}" 
		   class="btn btn-primary btn-incluir" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus" ></span>
			 {{ Lang::get('master.incluir') }}
		</a>
	</li>

</ul>

<div class="pesquisa-obj-container">
		<div class="input-group">
			<input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" required autofocus />
			<div class="input-group-addon"><span class="fa fa-search"></span></div>
		</div>
</div>

<fieldset>
	<legend>Requisições cadastradas</legend>
	
	<table class="table table-striped table-bordered table-hover lista-obj">
		<thead>
		<tr>
			<th>Id</th>
			<th>Requerente</th>
			<th>Centro de Custo</th>
			<th>OC</th>
			<th>Urgente?</th>
			<th>Data</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($dados as $dado)
		<tr link="{{ url('_13010', $dado->ID) }}">
			<td>{{ $dado->ID }}</td>
			<td>{{ $dado->USUARIO }}</td>
			<td>{{ $dado->CCUSTO_DESCRICAO }}</td>
			<td>{{ $dado->OC ? $dado->OC : '-' }}</td>
			<?php if($dado->URGENCIA) { $classe_td = 'green'; $classe_span = 'glyphicon-ok'; } else { $classe_td = 'red'; $classe_span = 'glyphicon-remove'; } ?>
			<td class="{{ $classe_td }}"><span class="glyphicon {{ $classe_span }}"></span></td>
			<td>{{ date_format(date_create($dado->DATA), 'd/m/Y') }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>

</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/_13010.js') }}"></script>
@endsection
