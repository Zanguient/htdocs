@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/22010.css') }}" />
@endsection

@section('conteudo')


<div id="AppCtrl" ng-controller="Ctrl as vm" ng-cloak>
    
<input type="hidden" id="ps227" value="{{ systemControl(227) }}" />
    
@include('ppcp._22010.index.botao-acao')
@include('ppcp._22010.index.info-destaque')
@include('ppcp._22010.index.resumo-producao')


<button gc-pessoal-colaborador-centro-de-trabalho="" type="button" class="btn btn-info btn-sm" style="position: fixed;z-index: 99;top: 109px;right: 15px;">
    <span class="fa fa-briefcase"></span>
    Centro de Trabalho
</button>

    <fieldset 
        class="programacao" 
        ng-class="{
            'iniciada' : vm.TalaoProduzir.EM_PRODUCAO,
            'talao-produzir-ativo'     : vm.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR',
            'talao-produzido-ativo'    : vm.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIDO',
            'totalizador-diario-ativo' : vm.Filtro.GUIA_ATIVA == 'TOTALIZADOR_DIARIO'
        }"
        >
		<legend>{{ Lang::get($menu.'.talao-title') }}</legend>
		<form id="filtro-geral" class="form-inline" ng-submit="vm.Filtro.consultar()" name="filtroGeral" >
		@include('ppcp._22010.index.filtro')
		</form>
		<button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
			{{ Lang::get($menu.'.filtro-toggle') }}
			<span class="caret"></span>
		</button>
		
		<div id="talao-container" >
			
			<div id="talao" class="obj_resizable">

				<fieldset class="tab-container">
                    <div class="alert alert-danger" ng-if="vm.Acao.check('iniciar').descricao != ''" style="
                        position: absolute;
                        right: 0;
                        margin: 0;
                        color: rgb(255, 255, 255);
                        background: rgb(167, 45, 45);
                        font-weight: bold;
                        font-size: 11px;
                        max-width: 32%;
                        padding: 3px 8px 3px 8px;                         
                         ">
                        Atenção: @{{ vm.Acao.check('iniciar').descricao }}
                    </div>
					<ul id="tab" class="nav nav-tabs" role="tablist"> 
						<li role="presentation" class="active">
                            <a href="#talao-produzir" id="talao-produzir-tab" role="tab" data-toggle="tab" aria-controls="talao-produzir" aria-expanded="true" 
                               ng-click="vm.Filtro.GUIA_ATIVA = 'TALAO_PRODUZIR'; vm.Filtro.submit();"
                               extSubmit
                               >
								{{ Lang::get($menu.'.taloes-produzir') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li> 
						<li role="presentation">
							<a href="#talao-produzido" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="talao-produzido" aria-expanded="false"
                               ng-click="vm.Filtro.GUIA_ATIVA = 'TALAO_PRODUZIDO'; vm.Filtro.submit();"
                               >
								{{ Lang::get($menu.'.taloes-produzidos') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li>
						<li role="presentation">
							<a href="#totalizador-diario" id="totalizador-diario-tab" role="tab" data-toggle="tab" aria-controls="totalizador-diario" aria-expanded="false"
                               ng-click="vm.Filtro.GUIA_ATIVA = 'TOTALIZADOR_DIARIO'; vm.Filtro.submit();"
                               >
								{{ Lang::get($menu.'.totalizador-diario') }}
								<span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
							</a>
						</li>
					</ul>
					<div id="tab-content" class="tab-content">
						<div
                            role="tabpanel" 
                            class="tab-pane fade active in" 
                            id="talao-produzir" 
                            aria-labelledby="talao-produzir-tab">
							@include('ppcp._22010.index.talao-produzir')
						</div>
						<div 
                            role="tabpanel" 
                            class="tab-pane fade" 
                            id="talao-produzido" 
                            aria-labelledby="talao-produzido-tab"
                            >
							@include('ppcp._22010.index.talao-produzido')
						</div>
						<div 
                            role="tabpanel" 
                            class="tab-pane fade" 
                            id="totalizador-diario" 
                            aria-labelledby="totalizador-diario-tab"
                            >
							@include('ppcp._22010.index.totalizador-diario')
						</div>
					</div>
				</fieldset>
				
			</div>
			
		</div>

	</fieldset>
	
    <div id="detalhe-container">
			
		<div id="detalhe">
            @include('ppcp._22010.index.talao-composicao.detalhe')
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
	
	<div id="materia-prima">
        @include('ppcp._22010.index.talao-composicao.consumo')	
	</div>
	
	<div id="historico">
        @include('ppcp._22010.index.talao-composicao.historico')	
	</div>

	<div id="ficha">
        @include('ppcp._22010.index.talao-composicao.ficha')	
	</div>
     
    
<!--	<div id="defeito">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#tab-defeito">
                    Defeitos
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#tab-ficha-tecnica">
                    Ficha Técnica de Produção
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="tab-defeito" class="tab-pane fade in active">
                table defeitos
            </div>
            <div id="tab-ficha-tecnica" class="tab-pane fade">
                table ficha
            </div>
        </div>         
	</div>-->


<div class="info-Atualizar">
	<div class="info-Atualizar-fraze" style="display: none;">
	  s para actualizar a pagina e melhorar o desempenho do aplicativo. 
	</div>
</div>

@include('ppcp._22010.index.modal-parada-justificativa')
@include('ppcp._22010.index.justificar')
@include('helper.include.view.autenticar')
@include('ppcp._22010.index.talao-registro')		
@include('ppcp._22010.index.modal-autenticar-up')
@include('ppcp._22010.index.modal-registrar-aproveitamento')
@include('ppcp._22010.index.modal-registrar-balanca')
@include('ppcp._22010.index.modal-registrar-materia')		
@include('ppcp._22010.index.modal-registrar-componente')		
@include('ppcp._22010.index.modal-balanca')
@include('ppcp._22010.index.modal-vinculo-modelos')
@include('ppcp._22010.index.talao-composicao.detalhe.modal-registrar-defeito')
</div>
@endsection    

@section('script')

	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input.js') }}"></script>
	<script src="{{ asset('assets/js/jquery-dateFormat.min.js') }}"></script>
	<script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ elixir('assets/js/pessoal/helper/factory.colaborador-centro-de-trabalho.js') }}"></script>
    <script src="{{ elixir('assets/js/_22010-Pronta-Entrega.js') }}"></script>
	<script src="{{ elixir('assets/js/_22010.js') }}"></script>
    
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    
    <script src="{{ elixir('assets/js/AutoUpdate.js') }}"></script>
    <script src="{{ elixir('assets/js/_22010.app.js') }}"></script>
   
@append
