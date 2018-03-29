@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo-incluir') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25600.css') }}" />
    <link rel="stylesheet" href="{{ elixir('assets/css/22020.css') }}" />
    <link rel="stylesheet" href="{{ elixir('assets/css/15050.css') }}" />
@endsection

@section('conteudo')

	<form action="{{ route('_15010.store') }}" url-redirect="{{ url('sucessoGravar/_15050') }}" method="POST" class="form-inline form-add js-gravar">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-ok"></span> 
					{{ Lang::get('master.gravar') }}
				</button>
			</li>
		</ul>
		
		<fieldset>
			<legend>{{ Lang::get('master.info-geral') }}</legend>
			
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

                {{-- Localização --}}
                @include('estoque._15020.include.listar', [
                    'required'		=> 'required',
                    'opcao_selec'	=> 'true',
                    'chave'			=> '[]'
                ])
                
                {{-- Centro de custo --}}
				@include('financeiro._20030.include.filtrar2', [
					'campos_imputs'	=> [
						['_ccusto_id', 'ID'],
						['_ccusto_desc', 'DESCRICAO']
					],
					'required'		=> 'required'
				])
                
                {{-- Operação --}}
                @include('fiscal._21010.include.filtrar', [
                    'campos_imputs'		=> [
                        ['_operacao_cod', 'CODIGO'],
                        ['_operacao_desc', 'DESCRICAO']
                    ],
                    'consulta_filtro'	=> [['_operacao_prod_id','1']],
                    'required'			=> 'required',
                    'chave'				=> '[]'
                ])
                
                {{-- Turno --}}
				@include('pessoal._23010.include.listar', [
					'required'		=> 'required',
					'opcao_selec'	=> 'true',
				])
                
                <input type="hidden" name="flag_baixa_requisicao" id="flag_baixa_requisicao" class="flag_baixa_requisicao" value="1">
			
			</div>
	    </fieldset>
	    
	    <fieldset>
	    	<legend>{{ Lang::get($menu.'.info-prod') }}</legend>
			
			<div class="row">
				
				{{-- Produto --}}
				@include('produto._27050.include.filtrar', [
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
						['estab', ''],
                        ['saldo', '1']
					],
					'required'		=> 'required',
					'no_script'		=> 'true',
					'validate'		=> 'verifEstab',
					'chave'			=> '[]'
				])
					
				
				{{-- Tamanho --}}
				@include('produto._27040.include.listar', ['multiplo' => 'true'])
				
                
				<input type="hidden" name="flag_baixa_requisicao" id="flag_baixa_requisicao" class="flag_baixa_requisicao" value="1">
                
				<div class="form-group">
					<label for="qtd">{{ Lang::get('master.qtd') }}:</label>
                    <div class="input-group">
                        <input type="text" name="qtd[]" id="qtd" class="keyboard-numeric2 form-control input-menor mask-numero qtd" decimal="4" required />
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
			
		</fieldset>
	    
	</form>

@endsection
	
@section('script')
  
	<script src="{{ elixir('assets/js/consulta.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/table.js') }}"></script>
    <script src="{{ elixir('assets/js/_15010.js') }}"></script>
    <script src="{{ elixir('assets/js/_20030indicador-filtro.js') }}"></script>

    <link href="{{ asset('assets/keyboard/docs/css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/keyboard/css/keyboard.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.js') }}"></script>
    
    <script src="{{ elixir('assets/js/_15050.js') }}"></script>

	<!-- keyboard extensions (optional) 
	<script src="{{ asset('assets/keyboard/js/jquery.mousewheel.js') }}"></script>
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.extension-typing.js') }}"></script>
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.extension-autocomplete.js') }}"></script>
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.extension-caret.js') }}"></script>-->
    
@append
