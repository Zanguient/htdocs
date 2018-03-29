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
		<button type="button" class="btn btn-primary baixar-estoque" data-hotkey="alt+b" title="{{ Lang::get($menu.'.baixar-est-title') }}">
			<span class="glyphicon glyphicon-edit"></span>
			{{ Lang::get($menu.'.baixar-est') }}
		</button>
	</li>
	<li>
		<a href="{{ url('_15040/create') }}" class="btn btn-baixas btn-default" data-hotkey="alt+r">
			<span class="glyphicon glyphicon-new-window"></span> 
			{{ Lang::get($menu.'.baixas-realizadas') }}
		</a>

		<script type="text/javascript">

			// Se foi feito um filtro antes, 
			// troca as URL's que voltam para a página anterior 
			// pela URL que contém os parâmetros do filtro.
			if (localStorage.getItem('15040CreateFiltroUrl') != null) {

				$(".btn-baixas").attr("href", localStorage.getItem("15040CreateFiltroUrl"));
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
	<legend>{{ Lang::get($menu.'.req-cad') }}</legend>
    <div class="alert alert-warning">
        <p><b>Você possui permissão para realizar baixa em produtos das seguintes famílias:</b></p>
        @forelse($familias_baixa as $familia)
        <span class="familia-permitida">{{ $familia->FAMILIA_ID }} - {{ $familia->FAMILIA_DESCRICAO }}</span>
        @empty
        <p>Você não possui permissão realizar baixa em nenhuma família de produto.</p>
        @endforelse
    </div>   
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
	
	<table class="table table-striped table-bordered table-hover lista-obj-15040 table-requisicao-pendente">
		<thead>
		<tr>
			<th></th>
			<th class="status"></th>
			<th class="text-right">Id</th>
			<th>Data</th>
			<th>Requerente</th>
			<th title="Estabelecimento">Est.</th>
			<th title="Documento">Doc.</th>
			<th>C.Custo</th>
			<th>Turno</th>
			<th title="Família">Fam.</th>
			<th class="produto">Produto</th>
			<th class="text-right" title="Quantidade">Qtd.</th>
			<th title="Tamanho">Tam.</th>
			<th>Observação</th>
			<th>Saldo</th>
		</tr>
		</thead>
		<tbody>

		@php /*

		@foreach ($dados as $dado)
		<tr>            
			@php $status = trim($dado->STATUS);
			
			<td class="{{ ($status != 1) ? 'disabled' : '' }}" title="{{ Lang::get($menu.'.chk-disabled-title-'.$status) }}">
				<input type="checkbox" class="chk-req-selec" {{ ($status != 1) ? 'disabled' : '' }} />
			</td>
			<td class="status status-{{ $status }}">
				<span class="fa fa-circle" title="{{ Lang::get($menu.'.status-'.$status) }}"></span>
			</td>
			<td class="req-id">{{ $dado->ID }}</td>
			<td>{{ date_format(date_create($dado->DATA), 'd/m/Y H:i:s') }}</td>
			<td>{{ $dado->USUARIO_DESCRICAO }}</td>
			<td class="text-right">{{ $dado->ESTABELECIMENTO_ID }}</td>
			<td class="text-right">{{ $dado->DOCUMENTO }}</td>
			<td>{{ $dado->CCUSTO }} - {{ $dado->CCUSTO_DESCRICAO }}</td>
			<td>{{ $dado->TURNO_ID }} - {{ $dado->TURNO_DESCRICAO }}</td>
			<td class="text-right">{{ $dado->FAMILIA_ID }}</td>
			<td class="req-produto">{{ $dado->PRODUTO_ID }} - {{ $dado->PRODUTO_DESCRICAO }} ({{ $dado->UM }})</td>
			<td class="text-right req-qtd">{{ $dado->QUANTIDADE }}</td>
			<td class="text-right">{{ $dado->TAMANHO_DESCRICAO }}</td>
			<td>{{ $dado->OBSERVACAO }}</td>
			<td class="text-right req-saldo">{{ $dado->SALDO }}</td>
            
			<input type="hidden" class="_req_produto_id" value="{{ $dado->PRODUTO_ID }}" />
			<input type="hidden" class="_req_localizacao_padrao" value="{{ $dado->LOCALIZACAO_PADRAO }}" />
			<input type="hidden" class="_operacao_requisicao" value="{{ $dado->OPERACAO_REQUISICAO }}" />
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

@section('popup-form-start')

	<form action="{{ route('_15040.store') }}" url-redirect="{{ url('sucessoGravar/_15040') }}" method="POST" class="form-inline js-gravar popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	
@endsection

@section('popup-head-button')

	<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.gravar') }}
	</button>

@endsection

@section('popup-head-title')

	{{ Lang::get($menu.'.baixar-est') }}

@endsection

@section('popup-body')
	
	<div class="row grupo-req">
		<div class="row">
			<div class="form-group">
				<label>{{ Lang::get('master.id') }}:</label>
				<input type="text" name="req_id[]" class="form-control input-menor req-id" readonly />
			</div>
			<div class="form-group">
				<label>{{ Lang::get('produto/_27050.produto') }}:</label>
				<input type="text" name="req_produto[]" class="form-control input-maior req-produto" readonly />
			</div>
			<div class="form-group">
				<label>{{ Lang::get($menu.'.qtd') }}:</label>
				<input type="text" name="req_qtd[]" class="form-control req-qtd" readonly />
			</div>
			<div class="form-group">
				<label>{{ Lang::get($menu.'.saldo') }}:</label>
				<input type="text" name="req_saldo[]" class="form-control req-saldo" readonly />
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label>{{ Lang::get($menu.'.baixar-qtd') }}:</label>
				<input type="number" name="req_baixar[]" class="form-control req-baixar" min="0.0001" max="" step="0.0001" required />
			</div>

			{{-- Operação --}}
			@php /*
			@include('fiscal._21010.include.filtrar', [
				'campos_imputs'		=> [
					['_operacao_cod', 'CODIGO'],
					['_operacao_desc', 'DESCRICAO']
				],
				'consulta_filtro'	=> [['_operacao_prod_id','1']],
				'required'			=> 'required',
				'chave'				=> '[]'
			])
			@php */

			{{-- Localização --}}
			@include('estoque._15020.include.listar', [
				'required'		=> 'required',
				'opcao_selec'	=> 'true',
				'chave'			=> '[]'
			])
		</div>
		<input type="hidden" name="_operacao_requisicao[]" class="_operacao_requisicao_modal" value="" />
	</div>

@endsection

@section('popup-form-end')
	</form>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/consulta.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_15040.js') }}"></script>
@append
