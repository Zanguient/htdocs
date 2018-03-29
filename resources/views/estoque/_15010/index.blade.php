@extends('master')

@section('titulo')
{{ Lang::get('estoque/_15010.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/15010.css') }}" />
@endsection

@section('conteudo')
<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_15010/create') : '#' }}" class="btn btn-primary" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus"></span>
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

		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>{{ Lang::get('estoque/_15010.req-cad') }}</legend>
	
	<div id="table-filter" class="table-filter">
		<div>
			<label>{{ Lang::get('master.status') }}:</label>
			<select id="filter-status">
				<option disabled value="">- {{ Lang::get('master.selecione') }} -</option>
				<option value=""  {{ $status == ''  ? 'selected' : '' }}>{{ Lang::get('master.todos') }}</option>
				<option value="1" {{ $status == '1' ? 'selected' : '' }}>{{ Lang::get('master.pendentes') }}</option>
				<option value="0" {{ $status == '0' ? 'selected' : '' }}>{{ Lang::get('master.baixados') }}</option>
			</select>
		</div>
		
		{{-- Estabelecimento --}}
		@include('admin._11020.include.listar', [
			'opcao_selec' 		=> 'true',
			'opcao_todos' 		=> 'true',
			'estab_cadastrado'	=> $estab
		])
		
		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>

			<input 
				type="date" 
				class="data-ini" 
				id="data-ini" 
				value="{{ ($data_ini != '') ? $data_ini : date('Y-m-d', strtotime('-1 month')) }}">

			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>

			<input 
				type="date" 
				class="data-fim" 
				id="data-fim" 
				value="{{ ($data_fim != '') ? $data_fim : date('Y-m-d') }}">
		</div>

		<button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
	</div>
	
	<table class="table table-striped table-bordered table-hover lista-obj-15010">
		<thead>
		<tr>
			<th class="status"></th>
			<th>Id</th>
			<th>Data</th>
			<th>Requerente</th>
			<th class="text-right" title="Estabelecimento">Est.</th>
			<th>C.Custo</th>
			<th>Turno</th>
			<th class="produto">Produto</th>
			<th class="text-right" title="Quantidade">Qtd.</th>
			<th class="text-right" title="Tamanho">Tam.</th>
			<th>Observação</th>
			<th class="text-right">Saldo</th>
		</tr>
		</thead>
		<tbody>

		@php /*

		@foreach ($dados as $dado)
			
			<tr link="{{ url('_13010', $dado->ID) }}">

				<td class="status status-{{ trim($dado->STATUS) }}">
					<span class="fa fa-circle" title="{{ Lang::get($menu.'.status-'.trim($dado->STATUS)) }}"></span>
				</td>
				<td class="req-id">{{ $dado->ID }}</td>
				<td>{{ date_format(date_create($dado->DATA), 'd/m/Y H:i:s') }}</td>
				<td>{{ $dado->USUARIO_DESCRICAO }}</td>
				<td class="text-right">{{ $dado->ESTABELECIMENTO_ID }}</td>
				<td>{{ $dado->CCUSTO }} - {{ $dado->CCUSTO_DESCRICAO }}</td>
				<td>{{ $dado->TURNO_ID }} - {{ $dado->TURNO_DESCRICAO }}</td>
				<td class="req-produto">{{ $dado->PRODUTO_ID }} - {{ $dado->PRODUTO_DESCRICAO }} ({{ $dado->UM }})</td>
				<td class="text-right req-qtd">{{ $dado->QUANTIDADE }}</td>
				<td class="text-right">{{ $dado->TAMANHO_DESCRICAO }}</td>
				<td>{{ $dado->OBSERVACAO }}</td>
				<td class="text-right req-saldo">{{ $dado->SALDO }}</td>
			</tr>
			
		@endforeach
		
		@php */
		
		</tbody>
	</table>
	
	<div class="legenda-container">
		<ul class="legenda">
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-0') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-1') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-2') }}</div>
			</li>
		</ul>
	</div>
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_15010.js') }}"></script>
@append
