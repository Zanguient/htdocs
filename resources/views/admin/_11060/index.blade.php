@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11060.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11060.titulo') }}
@endsection

@section('conteudo')
<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_11060/create') : '#' }}" class="btn btn-primary" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get('master.incluir') }}
		</a>
	</li>
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>{{ Lang::get('admin/_11060.legenda') }}</legend>
	
	<div id="table-filter" class="table-filter">
		
		{{-- Estabelecimento --}}
        @include('admin._11020.include.listar', [
            'required'		=> 'required',
            'autofocus'		=> 'autofocus',
            'opcao_selec'	=> 'true'
        ])
        
		<button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
	</div>
	
	<table class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th>{{ Lang::get('admin/_11060.tabela-id') }}</th>
			<th>{{ Lang::get('admin/_11060.tabela-descricao') }}</th>
			<th>{{ Lang::get('admin/_11060.tabela-serial') }}</th>
		</tr>
		</thead>
		<tbody class="lista-obj-11060">
		@foreach ($impressoras as $impressora)
			<tr link="{{ url('_11060', $impressora->ID) }}">
				<td class="req-id">{{ $impressora->ID }}</td>
				<td>{{ $impressora->DESCRICAO}}</td>
				<td>{{ $impressora->CODIGO }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_11060.js') }}"></script>
@append