@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/22020.css') }}" />
@endsection

@section('conteudo')

<input type="hidden" id="ps227" value="{{ $ps227 }}" />

@include('ppcp._22020.index.botao-acao')
@include('ppcp._22020.index.info-destaque')
@include('ppcp._22020.index.resumo-producao')

<form class="form-inline">
	
	<fieldset class="programacao">
		<legend>{{ Lang::get($menu.'.talao-title') }}</legend>
		
		@include('ppcp._22020.index.filtro')
		
		<button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
			{{ Lang::get($menu.'.filtro-toggle') }}
			<span class="caret"></span>
		</button>
		
		<div id="talao-container" >
			
			<div id="talao" class="obj_resizable">
				
				<fieldset class="tab-container">
					<ul id="tab" class="nav nav-tabs" role="tablist"> 
						<li role="presentation" class="active">
							<a href="#talao-produzir" id="talao-produzir-tab" role="tab" data-toggle="tab" aria-controls="talao-produzir" aria-expanded="true">
								{{ Lang::get($menu.'.taloes-produzir') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li> 
						<li role="presentation">
							<a href="#talao-produzido" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="talao-produzido" aria-expanded="false">
								{{ Lang::get($menu.'.taloes-produzidos') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li>
						<li role="presentation">
							<a href="#totalizador-diario" id="totalizador-diario-tab" role="tab" data-toggle="tab" aria-controls="totalizador-diario" aria-expanded="false">
								{{ Lang::get($menu.'.totalizador-diario') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li>
					</ul>
					<div id="tab-content" class="tab-content">
						<div role="tabpanel" class="tab-pane fade active in" id="talao-produzir" aria-labelledby="talao-produzir-tab">
							@include('ppcp._22020.index.talao-produzir')
						</div>
						<div role="tabpanel" class="tab-pane fade" id="talao-produzido" aria-labelledby="talao-produzido-tab">
							@include('ppcp._22020.index.talao-produzido')
						</div>
						<div role="tabpanel" class="tab-pane fade" id="totalizador-diario" aria-labelledby="totalizador-diario-tab">
							@include('ppcp._22020.index.totalizador-diario')
						</div>
					</div>
				</fieldset>
				
			</div>
			
		</div>

	</fieldset>
	
    <div id="detalhe-container">
			
		<div id="detalhe">
			@include('ppcp._22020.index.talao-detalhe')
		</div>

		<ul class="legenda talao-detalhe">
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get('master.em-aberto') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get('master.em-producao') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get('master.produzido') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get('master.encerrado') }}</div>
			</li>
		</ul>

	</div>
	
	@php /*
	<div id="totalizador">
		@include('ppcp._22020.index.talao-totalizador')
	</div>
	@php */
	
	<div id="materia-prima">
        @include('ppcp._22020.index.talao-materia')	
	</div>
	
	<div id="historico">
		@include('ppcp._22020.index.talao-historico')	
	</div>
	
	<div id="defeito">
		@include('ppcp._22020.index.talao-defeito')		
	</div>
	
</form>

<div class="info-Atualizar">
	<div class="info-Atualizar-fraze" style="display: none;">
	  s para actualizar a pagina e melhorar o desempenho do aplicativo. 
	</div>
</div>

@include('helper.include.view.autenticar')
@include('ppcp._22020.index.talao-registro')		
@include('ppcp._22020.index.modal-autenticar-up')
@include('ppcp._22020.index.modal-registrar-aproveitamento')
@include('ppcp._22020.index.modal-registrar-balanca')
@include('ppcp._22020.index.modal-registrar-materia')		
@include('ppcp._22020.index.modal-registrar-componente')		
@include('ppcp._22020.index.modal-balanca')

@endsection    

@section('script')

	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input.js') }}"></script>
	<script src="{{ asset('assets/js/jquery-dateFormat.min.js') }}"></script>
	<script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ elixir('assets/js/_22020-Pronta-Entrega.js') }}"></script>
	<script src="{{ elixir('assets/js/_22020.js') }}"></script>
    
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    
    <script src="{{ elixir('assets/js/AutoUpdate.js') }}"></script>
   
@append
