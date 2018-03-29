@extends('helper.include.view.modal', ['id' => 'modal-create', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title" ng-if="$ctrl.tipoTela == 'incluir'">
		{{ Lang::get($menu.'.titulo-incluir') }}
	</h4>

	<h4 class="modal-title" ng-if="$ctrl.tipoTela == 'exibir'">
		{{ Lang::get($menu.'.titulo-exibir') }}
	</h4>

	<h4 class="modal-title" ng-if="$ctrl.tipoTela == 'alterar'">
		{{ Lang::get($menu.'.titulo-alterar') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('vendas._12040.create.botao-acao')

@overwrite

@section('modal-body')

	<div class="alert alert-info" ng-if="$ctrl.situacaoPedido == '1'">
		{{ Lang::get($menu.'.msg-pedido-confirmado') }}
	</div>
	
	<div class="alert alert-info" ng-if="$ctrl.situacaoPedido == '0' && $ctrl.infoGeral.infoGeral.FORMA_ANALISE == '0'">
		{{ Lang::get($menu.'.msg-pedido-forma-imediat') }}
	</div>

	<info-geral-12040
		tipo-tela="$ctrl.tipoTela"
	></info-geral-12040>

	<pedido-item-escolhido-12040
		tipo-tela="$ctrl.tipoTela"
	></pedido-item-escolhido-12040>

@overwrite
