@extends('master')

@section('titulo')
    {{ Lang::get('vendas/_12100.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12100.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">
		<li>
			<a href="{{ url('/') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
				<span class="glyphicon glyphicon-chevron-left"></span>
				Voltar
			</a>
		</li>
	</ul>

@php /*
	<div class="pesquisa-obj-container">
		<div class="input-group input-group-filtro-obj">
			<input type="search" ng-model="vm.filtroItens" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
				<span class="fa fa-search"></span>
			</button>
		</div>
	</div>
@php */	

	<div style="display: inline-flex; margin-right: 20px;" class="Consulta_Representante"></div>
	<div style="display: inline-flex;" class="Consulta_Cliente"></div>
	
	<div style="display: inline-flex; margin-right: 20px;" >
		<div style="display: block;" class="form-group">
			<label style="display: block;">Número:</label>
			<input type="text" class="form-control input-menor" ng-model="vm.NUMERO_NOTA" value="" >
		</div>
	</div>

	<div style="display: inline-flex; margin-right: 20px;" >
		<div style="display: block;" class="form-group">
			<label style="display: block;">Período:</label>
			<input type="date" class="data-ini ng-pristine ng-valid ng-not-empty ng-touched" ng-model="vm.DataInicio">
			<label class="periodo-a">à</label>
			<input type="date" class="data-fim ng-pristine ng-untouched ng-valid ng-not-empty" ng-model="vm.DataFim">

			<button  style="margin-top: -5px;" type="button" class="btn btn-sm btn-primary btn-filtrar" id="btn-table-filter" data-hotkey="alt+f" ng-click="vm.Acoes.consultarNotas(1)">
				<span class="glyphicon glyphicon-filter"></span>
				Filtrar
			</button>
		</div>
	</div>

	

	@php $representante = isset($_GET['representante']) ? $_GET['representante'] : 0
	@php $cliente       = isset($_GET['cliente']) ? $_GET['cliente'] : 0
	@php $nota          = isset($_GET['nota']) ? $_GET['nota'] : 0
	@php $serie         = isset($_GET['serie']) ? $_GET['serie'] : 0
	@php $pedido        = isset($_GET['pedido']) ? $_GET['pedido'] : 0

	<input type="hidden" class="_representante2" value="{{$representante2}}">

	<input type="hidden" class="_representante" value="{{$representante}}">
	<input type="hidden" class="_cliente"       value="{{$cliente}}">
	<input type="hidden" class="_nota"          value="{{$nota}}">
	<input type="hidden" class="_serie"         value="{{$serie}}">
	<input type="hidden" class="_pedido"         value="{{$pedido}}">
	
	<div style="margin-top: 3px; margin-bottom: 3px;">
		<button type="button" style="" class="btn btn-primary" ng-click="vm.Acoes.export2()">
			<span class="glyphicon glyphicon-save"></span> 
			Exportar para XLS
		</button>
	</div>

	<div id="container-de-notas">

		<div style="max-height: calc(100vh - 310px);" class="table-ec">
		    <div class="scroll-table">
		        <table  id="tabela-de-notas" class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
		            <thead>
			            <tr style="
			            background: #337ab7;
					    color: white;
					    box-shadow: 0px -1px #dddddd;">

							<th ng-click="vm.TratarOrdem('NUMERO_NOTAFISCAL')">
								<span style="display: inline-flex;">NOTA FISCAL
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'NUMERO_NOTAFISCAL'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-NUMERO_NOTAFISCAL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('EMPRESA_RAZAOSOCIAL')">
								<span style="display: inline-flex;">CLIENTE
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'EMPRESA_RAZAOSOCIAL'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-EMPRESA_RAZAOSOCIAL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('PEDIDO')">
								<span style="display: inline-flex;">PEDIDO
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'PEDIDO'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-PEDIDO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('DATA_EMISSAO')">
								<span style="display: inline-flex;">EMISSÃO
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'DATA_EMISSAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-DATA_EMISSAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('FRETE')">
								<span style="display: inline-flex;">FRETE
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'FRETE'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-FRETE'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>
		
							<th ng-click="vm.TratarOrdem('TOTAL_NF')">
								<span style="display: inline-flex;">VR. TOTAL
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'TOTAL_NF'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TOTAL_NF'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('TOTAL_QUANTIDADE')">
								<span style="display: inline-flex;">QUANTIDADE
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'TOTAL_QUANTIDADE'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TOTAL_QUANTIDADE'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('TOTAL_FRETE')">
								<span style="display: inline-flex;">VR. FRETE
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'TOTAL_FRETE'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TOTAL_FRETE'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('TRANSPORTADORA')">
								<span style="display: inline-flex;">TRANSPORTADORA
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'TRANSPORTADORA'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-TRANSPORTADORA'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>

							<th ng-click="vm.TratarOrdem('EMBARQUE')">
								<span style="display: inline-flex;">EMBARQUE / ENTREGA
									<span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.ordem == 'EMBARQUE'" class="glyphicon glyphicon-sort-by-attributes"></span>
									<span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.ordem == '-EMBARQUE'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
								</span>
							</th>
			            </tr>
			        </thead>
		            <tbody class="tabela-nota" id="itens-de-notas">

		                <tr class="lista-itens notas-itens-linha" tabindex="0" ng-repeat="nota in vm.NOTAS | orderBy:vm.ordem" ng-Keypress="vm.Acoes.keypress(nota,$event)" ng-Keydown="vm.Acoes.listaKeydown($event)">
			                <td style="border: 1px solid #ddd; text-align: center;"   ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.NUMERO_NOTAFISCAL}}</td>
			                <td style="border: 1px solid #ddd; text-align: left;  "   ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.EMPRESA_RAZAOSOCIAL}}</td>
			                <td style="border: 1px solid #ddd; " class="linha_pedido" auto-title ng-bind-html="trustAsHtml(nota.PEDIDO)"></td>
			                <td style="border: 1px solid #ddd; text-align: center;"   ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.DATA_EMISSAO}}</td>
			                <td style="border: 1px solid #ddd; text-align: center;"   ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.FRETE}}</td>
			                <td style="border: 1px solid #ddd; text-align: right;"    ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.TOTAL_NF}}</td>
			                <td style="border: 1px solid #ddd; text-align: right;"    ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.TOTAL_QUANTIDADE}}</td>
			                <td style="border: 1px solid #ddd; text-align: right;"    ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.TOTAL_FRETE}}</td>
			                <td style="border: 1px solid #ddd; text-align: left;"     ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.TRANSPORTADORA}}</td>
			                <td style="border: 1px solid #ddd; text-align: center;"   ng-click="vm.Acoes.abrirNota(nota, $event)" auto-title >@{{nota.EMBARQUE}}</td>
		                </tr>

		            </tbody>
		        </table>
		    </div>
		</div>
	</div>

	<div class="pdf-ver" ng-show="vm.NOTA.PDF.VER">
    
		<div class="pdf-acoes">
			<button type="button" class="btn btn-default esconder-arquivo" data-hotkey="f11" ng-click="vm.NOTA.PDF.VER = false">
				<span class="glyphicon glyphicon-chevron-left"></span>
				Voltar
			</button>

			<a class="btn btn-default download-arquivo" href="" download="" data-hotkey="alt+b">
				<span class="glyphicon glyphicon-download"></span>
				Baixar
			</a>

		</div>
	    
		<div class="conteudoPDF"></div>
	</div>

	@include('vendas._12100.modal.detalhar')
	@include('vendas._12100.modal.etiqueta')

</div>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    <script src="{{ elixir('assets/js/_12100.app.js') }}"></script>
@append
