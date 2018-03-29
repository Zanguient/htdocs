@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/15040.css') }}" />
@endsection

@section('conteudo')
<ul class="list-inline acoes">

	<li>
		<a href="{{ url('_15040') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{{ Lang::get('master.voltar') }}
		</a>
		<script type="text/javascript">

			// Se foi feito um filtro antes, 
			// troca as URL's que voltam para a página anterior 
			// pela URL que contém os parâmetros do filtro.
			if (localStorage.getItem('15040FiltroUrl') != null) {

				$(".btn-voltar").attr("href", localStorage.getItem("15040FiltroUrl"));
			}
		</script>
	</li>
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">

		<input 
			type="search" 
			name="filtro_obj" 
			class="form-control pesquisa filtro-obj" 
			placeholder="{{ Lang::get('master.pesq-place') }}" 
			autocomplete="off" 
			autofocus
			value="{{ $filtro_obj }}">

		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
			<span class="fa fa-search"></span>
		</button>

	</div>
</div>

<fieldset>
	<legend>{{ Lang::get($menu.'.baixas-realizadas') }}</legend>
	
	<div id="table-filter" class="table-filter">
				
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
	
	<table class="table table-striped table-bordered table-hover lista-obj-15040 table-baixa-realizada">
		<thead>
		<tr>
			<th>Id</th>
			<th title="Id da Requisição">Req.</th>
			<th>Data</th>
			<th class="text-right" title="Estabelecimento">Est.</th>
			<th class="text-right" title="Localização">Loc.</th>
			<th>Requerente</th>
			<th>C.Custo</th>
			<th>Produto</th>
			<th title="Tamanho">Tam.</th>
			<th class="text-right" title="Quantidade Baixada">Qtd.</th>
			<th title="Operação">Op.</th>
			<th title="Usuário que realizou a baixa">Usuário</th>
			<th class="text-right">Id Estoque</th>
		</tr>
		</thead>
		<tbody>

		@php /*

		@foreach ($dados as $dado)
			<tr link="{{ url('_15040', $dado->ID) }}">
				<td>{{ $dado->ID }}</td>
				<td>{{ $dado->REQUISICAO_ID }}</td>
				<td>{{ date_format(date_create($dado->DATAHORA), 'd/m/Y H:i:s') }}</td>
				<td class="text-right">{{ $dado->ESTABELECIMENTO_ID }}</td>
				<td class="text-right">{{ $dado->LOCALIZACAO_ID }}</td>
				<td>{{ $dado->REQUERENTE_DESCRICAO }}</td>
				<td>{{ $dado->CCUSTO }}</td>
				<td>{{ $dado->PRODUTO_ID }} - {{ $dado->PRODUTO_DESCRICAO }} ({{ $dado->UM }})</td>
				<td class="text-right">{{ $dado->TAMANHO_DESCRICAO }}</td>
				<td class="text-right">{{ $dado->QUANTIDADE }}</td>
				<td>{{ $dado->OPERACAO_CODIGO }}</td>
				<td>{{ $dado->USUARIO_DESCRICAO }}</td>
				<td class="text-right">{{ $dado->ESTOQUE_ID }}</td>
			</tr>
		@endforeach

		@php */

		</tbody>
	</table>
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_15040.js') }}"></script>
@append
