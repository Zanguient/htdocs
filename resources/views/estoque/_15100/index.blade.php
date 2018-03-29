@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15100.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15100.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">
		<li ng-if="vm.operador.ID == 0">
			<button 
				type="button" 
				class="btn btn-success" 
				ng-click="vm.Acoes.login()">
				<span class="glyphicon glyphicon-user"></span>
				Logar
			</button>
		</li>
		<li ng-if="vm.operador.ID != 0">
			<button 
				type="button" 
				class="btn btn-danger" 
				ng-click="vm.Acoes.logOff()">
				<span class="glyphicon glyphicon-user"></span>
				LogOff
			</button>
		</li>
		<li>
			<a href="{{ url('/') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
				<span class="glyphicon glyphicon-chevron-left"></span>
				Voltar
			</a>
		</li>
	</ul>

	<div class="form-group">
		<label for="valor-unitario">UP:</label>
			<input type="text" class="form-control" ng-model="vm.gp.DESCRICAO" disabled>
	</div>

	<div class="form-group">
		<label for="valor-unitario">Operador:</label>
			<input type="text" class="form-control"  ng-model="vm.operador.DESCRICAO" disabled>
	</div>

	<div class="form-group">
		<label for="valor-unitario">Peça/Talão:</label>
			<input type="text" ng-keydown="vm.Acoes.PecaKeydown($event)" class="form-control input-peca" ng-model="vm.peca.CODBARRAS"  ng-disabled="vm.operador.ID == 0 || vm.gp.ID == 0">
	</div>

	<div class="info_regra" ng-if="vm.peca.ABASTECER == 1">
		<span>Produto:     </span> @{{vm.regra.PRODUTO_ID + ' - ' + vm.regra.PRODUTO_DESCRICAO}}<br>
		<span>Quantidade:  </span> @{{vm.regra.QUANTIDADE}}<br>
		<span>Operação:    </span> @{{vm.regra.OPERACAO1 + ' / ' + vm.regra.OPERACAO2}}<br>
		<span>ID:          </span> @{{vm.regra.ID}}<br>
		<span>Localização: </span> @{{vm.regra.LOCALIZACAO_ID_VELHA + ' - ' + vm.regra.LOCALIZACAO_DESC_VELHA}}<br>
		<span>Para:        </span> @{{vm.regra.LOCALIZACAO_ID_NOVA + ' - ' + vm.regra.LOCALIZACAO_DESC_NOVA}}<br>
	
		<div style="width: 100%; height: 31px;" ng-if="vm.regra.DESFAZER == 0">
			<button
				style="float: right; width: 100%;" 
				type="button" 
				class="btn btn-success" 
				ng-click="vm.Acoes.Abastercer()">
				<span class="glyphicon glyphicon-ok"></span>
				Abastercer
			</button>
		</div>

		<div style="width: 100%; height: 31px;"  ng-if="vm.regra.DESFAZER == 1">
			<button
				style="float: right; width: 100%;" 
				type="button" 
				class="btn btn-danger" 
				ng-click="vm.Acoes.Abastercer()">
				<span class="glyphicon glyphicon-ok"></span>
				Desabastecer
			</button>
		</div>

	</div>

@php /*
	CONFERENCIA: "0"
	ESTAB: "1"
	FAMILIA: "74"
	GP: "19"
	ID: "RD/8503850"
	LOCALIZACAO_DESC_NOVA: "WORK IN PROCESS"
	LOCALIZACAO_DESC_VELHA: "ESPUMAS"
	LOCALIZACAO_ID_NOVA: "14"
	LOCALIZACAO_ID_VELHA: "9"
	OPERACAO1: "TRS"
	OPERACAO2: "TRE"
	PERFIL_UP: "B"
	PRODUTO_DESCRICAO: "ETC D46 12,5MM BRANCO"
	PRODUTO_ID: "45635"
	QUANTIDADE: "135.94400"
	QUANTIDADE_ALT: "0.00000"
	REGRA_ID: "99"
	REMESSA: "33464"
	SITUACAO: "2"
	TALAO: "12"
	TAMANHO: "13"
	TRATAR_ESTOQUE: "1"
@php */

	@include('estoque._15100.up')
	@include('estoque._15100.operador')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_15100.js') }}"></script>
@append
