@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('conteudo')

	<ul class="list-inline acoes">
		<li>
			<a href="{{ $permissaoMenu->ALTERAR ? route('_15010.edit', $dado->ID) : '#' }}" 
				class="btn btn-primary btn-alterar" data-hotkey="f9" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }} >
				<span class="glyphicon glyphicon-edit"></span>				
				{{ Lang::get('master.alterar') }}
			</a>
		</li>
		<li>
			<form action="{{ $permissaoMenu->EXCLUIR ? route('_15010.destroy', $dado->ID) : '#' }}" method="POST" class="form-deletar">
				<input type="hidden" name="_method" value="DELETE">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<button type="button" class="btn btn-danger excluir" data-hotkey="f12" data-toggle="modal" data-target="#confirmDelete" 
					{{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }} >
					<span class="glyphicon glyphicon-trash"></span> 
					{{ Lang::get('master.excluir') }}
				</button>
			</form>
		</li>
		<li>
			@if ( $pu214 === '1' )
				@php $encerrar = trim($dado->STATUS) == 2 ? 'desencerrar' : 'encerrar';
				<button type="button" class="btn btn-grey160 encerrar {{ trim($dado->STATUS) == 2 ? 'active' : '' }}" 
						data-hotkey="alt+e" 
						data-requisicao-id="{{ $dado->ID }}" 
						data-toggle="button" 
						data-active-class="encerrado" 
						aria-pressed="{{ trim($dado->STATUS) == 2 ? 'true' : 'false' }}"
						data-text-active="{{ Lang::get('master.encerrar') }}"
						data-text-inactive="{{ Lang::get('master.desencerrar') }}">
					<span class="glyphicon glyphicon-ban-circle"></span> 
					<span class="texto">{{ Lang::get('master.'.$encerrar) }}</span>
				</button>
			@endif
		</li>
		<li>
			<a href="{{ url('_15010') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
				<span class="glyphicon glyphicon-chevron-left"></span> 
				{{ Lang::get('master.voltar') }}
			</a>
			
			<script type="text/javascript">
				
				// Se foi feito um filtro antes, 
				// troca as URL's que voltam para a página anterior 
				// pela URL que contém os parâmetros do filtro.
				if (localStorage.getItem('15010FiltroUrl') != null)
					$(".btn-voltar").attr("href", localStorage.getItem("15010FiltroUrl"));

			</script>
		</li>
		<li class="align-right">
			<button type="button" class="btn btn-grey160 gerar-historico" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
				<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
			</button>
		</li>
	</ul>

	@include('helper.include.view.historico',['tabela' => 'TBCONSUMO_REQUISICAO', 'id' => $dado->ID, 'no_button' => 'true'])
	
	<form class="form-inline">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		<fieldset readonly>
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
				@include('financeiro._20030.include.filtrar', [
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
	    
	    <fieldset readonly>
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
						['tamanho-posicao', 'clear']
					],
					'selecionado'	=> '1',
					'valor'			=> $dado->PRODUTO_ID.' - '.$dado->PRODUTO_DESCRICAO,
					'required'		=> 'required',
					'validate'		=> 'valida'
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
	
	@include('helper.include.view.delete-confirm')	

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_15010.js') }}"></script>
@append
