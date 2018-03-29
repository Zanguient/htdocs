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
		<a href="{{ route('_15040.edit', $dado->ID) }}" class="btn btn-primary btn-alterar" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span> 
			{{ Lang::get('master.alterar') }}
		</a>
	</li>
	<li>
		<form action="{{ route('_15040.destroy', $dado->ID) }}" method="POST" class="form-deletar">
		    <input type="hidden" name="_method" value="DELETE">
		    <input type="hidden" name="_token" value="{{ csrf_token() }}">
		    <button type="button" class="btn btn-danger excluir" data-hotkey="f12" data-toggle="modal" data-target="#confirmDelete">
				<span class="glyphicon glyphicon-trash"></span> 
				{{ Lang::get('master.excluir') }}
			</button>
		</form>
	</li>
	<li>
		<a href="{{ url('_15040/create') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span> 
			{{ Lang::get('master.voltar') }}
		</a>
	</li>
	<li class="align-right">
		<button type="button" class="btn btn-grey160 gerar-historico" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
			<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
		</button>
	</li>	

</ul>

@include('helper.include.view.historico',['tabela' => 'TBESTOQUE_BAIXA', 'id' => $dado->ID, 'no_button' => 'true'])
	
<form class="form-inline">

	<fieldset readonly>
		<legend>Dados da Baixa</legend>
		
		<div class="row">
			<div class="form-group">
				<label>Id:</label>
				<input type="text" name="id" class="form-control input-menor" value="{{ $dado->ID }}" />
			</div>
			<div class="form-group">
				<label>Requisição:</label>
				<input type="text" name="requisicao_id" class="form-control input-menor" value="{{ $dado->REQUISICAO_ID }}" />
			</div>
			<div class="form-group">
				<label>Id Estoque:</label>
				<input type="text" name="estoque" class="form-control" value="{{ $dado->ESTOQUE_ID }}" />
			</div>
			<div class="form-group">
				<label>Data:</label>
				<input type="date" name="data" class="form-control" value="{{ date('Y-m-d', strtotime($dado->DATAHORA)) }}" />
			</div>
			
			<div class="form-group">
				<label>Centro de Custo:</label>
				<input type="text" name="ccusto" class="form-control" value="{{ $dado->CCUSTO.' - '.$dado->CCUSTO_DESCRICAO }}" />
			</div>

			<div class="form-group">
				<label>Usuário:</label>
				<input type="text" name="usuario" class="form-control" value="{{ $dado->USUARIO_DESCRICAO }}" />
			</div>
		</div>
		<div class="row">

			{{-- Estabelecimento --}}
			@include('admin._11020.include.listar', [
				'estab_cadastrado'	=> $dado->ESTABELECIMENTO_ID,
				'opcao_selec'		=> 'true'
			])

			{{-- Localização --}}
			@include('estoque._15020.include.listar', [
				'loc_cadastrado'	=> $dado->LOCALIZACAO_ID,
				'required'			=> 'required',
				'opcao_selec'		=> 'true'
			])
			
			<div class="form-group">
				<label>Operação:</label>
				<input type="text" name="qtd" class="form-control" value="{{ $dado->OPERACAO_CODIGO.' - '.$dado->OPERACAO_DESCRICAO }}" required />
			</div>

			<div class="form-group">
				<label>Qtd. Requisição:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->QUANTIDADE_REQUISICAO }}" min="0.0001" max="" step="0.0001" />
			</div>
			
			<div class="form-group">
				<label>Saldo da Requisição:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->SALDO_REQUISICAO }}" min="0.0001" max="" step="0.0001" />
			</div>
			
			<div class="form-group">
				<label>Qtd. Baixada:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->QUANTIDADE }}" min="0.0001" max="{{ $dado->SALDO_REQUISICAO }}" step="0.0001" required />
			</div>
		</div>
		
	</fieldset>
	
	@include('helper.include.view.delete-confirm')
	
</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/form.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
@append
