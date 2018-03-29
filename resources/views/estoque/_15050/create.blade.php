@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('conteudo')
<ul class="list-inline acoes">

	<li>
		<a href="{{ url('_15040') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{{ Lang::get('master.voltar') }}
		</a>
	</li>
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="{{ Lang::get('master.pesq-place') }}" autocomplete="off" autofocus />
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
			'opcao_selec' => 'true',
			'opcao_todos' => 'true'
		])
		
		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-ini" id="data-ini" value="{{ date('Y-m-d', strtotime('-1 month')) }}" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-fim" id="data-fim" value="{{ date('Y-m-d') }}" />
		</div>
		
		<button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
		
	</div>
	
	<table class="table table-striped table-bordered table-hover lista-obj-15040">
		<thead>
		<tr>
			<th>Id</th>
			<th title="Id da Requisição">Req.</th>
			<th>Data</th>
			<th>C.Custo</th>
			<th class="text-right" title="Estabelecimento">Est.</th>
			<th class="text-right" title="Localização">Loc.</th>
			<th title="Operação">Op.</th>
			<th class="text-right" title="Quantidade Baixada">Qtd.</th>
			<th>Usuário</th>
			<th class="text-right">Id Estoque</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($dados as $dado)
			<tr link="{{ url('_15040', $dado->ID) }}">
				<td>{{ $dado->ID }}</td>
				<td>{{ $dado->REQUISICAO_ID }}</td>
				<td>{{ date_format(date_create($dado->DATAHORA), 'd/m/Y H:i:s') }}</td>
				<td>{{ $dado->CCUSTO }}</td>
				<td class="text-right">{{ $dado->ESTABELECIMENTO_ID }}</td>
				<td class="text-right">{{ $dado->LOCALIZACAO_ID }}</td>
				<td>{{ $dado->OPERACAO_CODIGO }}</td>
				<td class="text-right">{{ $dado->QUANTIDADE }}</td>
				<td>{{ $dado->USUARIO_DESCRICAO }}</td>
				<td class="text-right">{{ $dado->ESTOQUE_ID }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	
</fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_15040.js') }}"></script>
@append
