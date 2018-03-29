@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo-alterar') }}
@endsection

@section('conteudo')
	<form action="{{ route('_15040.update', $dado->ID) }}" url-redirect="{{ url('sucessoAlterar/_15040', $dado->ID) }}" method="POST" class="form-inline edit js-gravar">
	    <input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
	<ul class="list-inline acoes">
		<li>
			<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
				<span class="glyphicon glyphicon-ok"></span> 
				{{ Lang::get('master.gravar') }}
			</button>
		</li>
		<li>
			<a href="{{ url('_15040', $dado->ID) }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
				<span class="glyphicon glyphicon-ban-circle"></span> 
				{{ Lang::get('master.cancelar') }}
			</a>
		</li>
	</ul>

	<fieldset>
		<legend>Dados da Baixa</legend>
		
		<div class="row">
			<div class="form-group">
				<label>Id:</label>
				<input type="text" name="id" class="form-control input-menor" value="{{ $dado->ID }}" readonly />
			</div>
			<div class="form-group">
				<label>Requisição:</label>
				<input type="text" name="requisicao_id" class="form-control input-menor" value="{{ $dado->REQUISICAO_ID }}" readonly />
			</div>
			<div class="form-group">
				<label>Id Estoque:</label>
				<input type="text" name="estoque" class="form-control" value="{{ $dado->ESTOQUE_ID }}" readonly />
			</div>
			<div class="form-group">
				<label>Data:</label>
				<input type="date" name="data" class="form-control" value="{{ date('Y-m-d', strtotime($dado->DATAHORA)) }}" readonly />
			</div>
			
			<div class="form-group">
				<label>Centro de Custo:</label>
				<input type="text" name="ccusto" class="form-control" value="{{ $dado->CCUSTO.' - '.$dado->CCUSTO_DESCRICAO }}" readonly />
			</div>

			<div class="form-group">
				<label>Usuário:</label>
				<input type="text" name="usuario" class="form-control" value="{{ $dado->USUARIO_DESCRICAO }}" readonly />
			</div>
		</div>
		<div class="row">

			{{-- Estabelecimento --}}
			@include('admin._11020.include.listar', [
				'estab_cadastrado'	=> $dado->ESTABELECIMENTO_ID,
				'opcao_selec'		=> 'true',
				'disabled'			=> 'disabled'
			])

			{{-- Localização --}}
			@include('estoque._15020.include.listar', [
				'loc_cadastrado'	=> $dado->LOCALIZACAO_ID,
				'required'			=> 'required',
				'opcao_selec'		=> 'true'
			])

			{{-- Operação --}}
			@include('fiscal._21010.include.filtrar', [
				'campos_imputs'		=> [
					['_operacao_cod', 'CODIGO', $dado->OPERACAO_CODIGO],
					['_operacao_desc', 'DESCRICAO', $dado->OPERACAO_DESCRICAO]
				],
				'consulta_filtro'	=> [['_operacao_prod_id','0']],
				'required'			=> 'required',
				'selecionado'		=> '1',
				'valor'				=> $dado->OPERACAO_CODIGO.' - '.$dado->OPERACAO_DESCRICAO,
			])

			<div class="form-group">
				<label>Qtd. Requisição:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->QUANTIDADE_REQUISICAO }}" min="0.0001" max="" step="0.0001" readonly />
			</div>
			
			<div class="form-group">
				<label>Saldo da Requisição:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->SALDO_REQUISICAO }}" min="0.0001" max="" step="0.0001" readonly />
			</div>
			
			<div class="form-group">
				<label>Qtd. Baixada:</label>
				<input type="number" name="qtd" class="form-control" value="{{ $dado->QUANTIDADE }}" min="0.0001" max="{{ $dado->SALDO_REQUISICAO }}" step="0.0001" required />
			</div>
		</div>
		
	</fieldset>
	
</form>

@endsection

@section('script')

	<script src="{{ elixir('assets/js/consulta.js') }}"></script>
	<script src="{{ elixir('assets/js/form.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>

@append
