@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo-alterar') }}
@endsection

@section('conteudo')

	<form action="{{ route('_15010.update', $dado->ID) }}" url-redirect="{{ url('sucessoAlterar/_15010', $dado->ID) }}" method="POST" class="form-inline edit js-gravar">
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
				<a href="{{ url('_15010', $dado->ID) }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
					<span class="glyphicon glyphicon-ban-circle"></span> 
					{{ Lang::get('master.cancelar') }}
				</a>
			</li>
		</ul>	
		
		<fieldset>
			<legend>{{ Lang::get('master.info-geral') }}</legend>
			
			<div class="row">
				
				<div class="form-group">
					<label for="id">{{ Lang::get('master.id') }}:</label>
					<input type="text" name="id" id="id" class="form-control input-menor" value="{{ $dado->ID }}" readonly />
				</div>
				
				<div class="form-group">
					<label for="data">{{ Lang::get('master.data') }}:</label>
					<input type="date" name="data" id="data" class="form-control" value="{{ date('Y-m-d', strtotime($dado->DATA)) }}" required readonly />
				</div>

				{{-- Estabelecimento --}}
				@include('admin._11020.include.listar', [
					'estab_cadastrado'	=> $dado->ESTABELECIMENTO_ID,
					'required'			=> 'required',
					'autofocus'			=> 'autofocus',
					'opcao_selec'		=> 'true'
				])
				
				{{-- Centro de custo --}}
				@include('financeiro._20030.include.filtrar-consumo-requisicao', [
					'campos_imputs'	=> [
						['_ccusto_id', 'ID', $dado->CCUSTO],
						['_ccusto_desc', 'DESCRICAO', $dado->CCUSTO_DESCRICAO]
					],
					'selecionado'	=> '1',
					'valor'			=> $dado->CCUSTO.' - '.$dado->CCUSTO_DESCRICAO,
					'required'		=> 'required'
				])
				
				{{-- Turno --}}
				@include('pessoal._23010.include.listar', [
					'turno_cadastrado'	=> $dado->TURNO_ID,
					'required'			=> 'required',
					'opcao_selec'		=> 'true',
				])

			</div>
	    </fieldset>
	    
	    <fieldset>
	    	<legend>{{ Lang::get($menu.'.info-prod') }}</legend>
			
			<div class="row">
				
				{{-- Produto --}}
				@include('produto._27050.include.filtrar', [
					'campos_imputs' => [
						['_produto_id', 'ID', $dado->PRODUTO_ID],
						['_produto_desc', 'DESCRICAO', $dado->PRODUTO_DESCRICAO],
						['_saldo', 'SALDO', $dado->PRODUTO_SALDO]
					],
					'recebe_valor'	=> [
						['qtd', 'clear'],
						['tamanho-produto', 'clear'],
						['tamanho-posicao', 'clear'],
					],
					'filtro_sql' => [	
						['estab', '']
					],
					'selecionado'	=> '1',
					'valor'			=> $dado->PRODUTO_ID.' - '.$dado->PRODUTO_DESCRICAO,
					'required'		=> 'required',
					'validate'		=> 'verifEstab'
				])
				
				{{-- Tamanho --}}
				@include('produto._27040.include.listar', [
					'tamanho_desc'  => $dado->TAMANHO_DESCRICAO,
					'tamanho'		=> $dado->TAMANHO
				])
				
				<div class="form-group">
					<label for="qtd">{{ Lang::get('master.qtd') }}:</label>
					<input type="text" name="qtd" id="qtd" class="form-control input-menor qtd mask-numero" decimal="4" value="{{ $dado->QUANTIDADE }}" required />
				</div>
				
			</div>
			
			<div class="row">
				<div class="form-group">
					<label for="obs">{{ Lang::get('master.obs') }}:</label>
					<div class="textarea-grupo">
						<textarea name="obs" id="obs" class="form-control obs" rows="5" cols="100">{{ $dado->OBSERVACAO }}</textarea>
						<span class="contador"><span></span> caracteres restantes</span>
					</div>
				</div>
			</div>
			
		</fieldset>
	    
	</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/_15010.js') }}"></script>
@append
