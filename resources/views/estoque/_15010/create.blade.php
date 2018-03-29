@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/15010.css') }}" />
@endsection

@section('conteudo')

	<form action="{{ route('_15010.store') }}" url-redirect="{{ url('sucessoGravar/_15010') }}" method="POST" class="form-inline form-add js-gravar">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-ok"></span> 
					{{ Lang::get('master.gravar') }}
				</button>
			</li>
            <li>
				<a href="{{ url('_15010') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
					<span class="glyphicon glyphicon-ban-circle"></span> 
					{{ Lang::get('master.cancelar') }}
				</a>

				<script type="text/javascript">

					// Se foi feito um filtro antes, 
					// troca as URL's que voltam para a página anterior 
					// pela URL que contém os parâmetros do filtro.
					if (localStorage.getItem('15010FiltroUrl') != null) {

						$("form.js-gravar").attr("url-redirect", localStorage.getItem("15010FiltroUrl"));
						$(".btn-cancelar").attr("href", localStorage.getItem("15010FiltroUrl"));
					}
				</script>

			</li>
		</ul>
		
		<fieldset>
			<legend>{{ Lang::get('master.info-geral') }}</legend>
            <div class="alert alert-warning">
                <p><b>Você possui permissão para requisitar produtos das seguintes famílias:</b></p>
                @forelse($familias_requisicao as $familia)
                <span class="familia-permitida">{{ $familia->FAMILIA_ID }} - {{ $familia->FAMILIA_DESCRICAO }}</span>
                @empty
                <p>Você não possui permissão de requisitar em nenhuma família de produto.</p>
                @endforelse
            </div>    
			<div class="row">
				<div class="form-group">
					<label for="data">{{ Lang::get('master.data') }}:</label>
					<input type="date" name="data" id="data" class="form-control" value="{{ date('Y-m-d') }}" required readonly />
				</div>
				
				{{-- Estabelecimento --}}
				@include('admin._11020.include.listar', [
					'required'		=> 'required',
					'autofocus'		=> 'autofocus',
					'opcao_selec'	=> 'true'
				])
				
				{{-- Centro de custo --}}
				@include('financeiro._20030.include.filtrar-consumo-requisicao', [
					'campos_imputs'	=> [
						['_ccusto_id', 'ID'],
						['_ccusto_desc', 'DESCRICAO']
					],
					'required'		=> 'required'
				])
				
				{{-- Turno --}}
				@include('pessoal._23010.include.listar', [
					'required'		=> 'required',
					'opcao_selec'	=> 'true'
				])

			</div>
	    </fieldset>
	    
	    <fieldset>
	    	<legend>{{ Lang::get($menu.'.info-prod') }}</legend>
			
			<div class="produto-container item-dinamico-container">
				<div class="produto item-dinamico">
			
					<div class="row">

						{{-- Produto --}}
						@include('produto._27050.include.filtrar-consumo-requisicao', [
							'campos_imputs' => [
								['_produto_id', 'ID'],
								['_produto_desc', 'DESCRICAO'],
								['_saldo', 'SALDO'],
								['_medida-prod', 'UNIDADEMEDIDA_SIGLA']
							],
							'recebe_valor'	=> [
								['qtd', 'clear'],
								['tamanho-produto', 'clear'],
								['tamanho-posicao', 'clear'],
								['medida-prod','clear'],
								['medida-prod','UNIDADEMEDIDA_SIGLA']
							],
							'filtro_sql' => [	
								['estab', '']
							],
							'required'		=> 'required',
							'no_script'		=> 'true',
							'validate'		=> 'verifEstab',
							'chave'			=> '[]'
						])


						{{-- Tamanho --}}
						@include('produto._27040.include.listar', ['multiplo' => 'true'])

						<input type="hidden" name="flag_baixa_requisicao" id="flag_baixa_requisicao" class="flag_baixa_requisicao" value="0">

						<div class="form-group">
							<label for="qtd">{{ Lang::get('master.qtd') }}:</label>
							<div class="input-group">
								<input type="text" name="qtd[]" id="qtd" class="form-control input-menor mask-numero qtd" decimal="4" required />
								<button type="button" id="_medida-prod" class="input-group-addon btn-filtro medida-prod"/>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="form-group">
							<label for="obs">{{ Lang::get('master.obs') }}:</label>
							<div class="textarea-grupo">
								<textarea name="obs[]" id="obs" class="form-control obs" rows="5" cols="100"></textarea>
								<span class="contador"><span></span> caracteres restantes</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-danger excluir-item-dinamico remove"><span class="glyphicon glyphicon-trash remove"></span></button>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-danger excluir-item-dinamico excluir-produto trash"><span class="glyphicon glyphicon-trash trash"></span></button>
					</div>
					
				</div>				
			</div>
			
			<button type="button" class="btn btn-info add-produto add-item-dinamico" title="Adicionar produto"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
			
		</fieldset>
	    
	</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/input-dinamic.js') }}"></script>
	<script src="{{ elixir('assets/js/_15010.js') }}"></script>
@append
