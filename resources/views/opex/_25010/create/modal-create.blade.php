@extends('helper.include.view.modal', ['id' => 'modal-create', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title" ng-if="vm.tipoTela == 'incluir'">
		{{ Lang::get($menu.'.titulo-incluir') }}
	</h4>
	<h4 class="modal-title" ng-if="vm.tipoTela == 'exibir'">
		{{ Lang::get($menu.'.titulo-exibir') }}
	</h4>
	<h4 class="modal-title" ng-if="vm.tipoTela == 'alterar'">
		{{ Lang::get($menu.'.titulo-alterar') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('opex._25010.create.botao-acao')

@overwrite

@section('modal-body')

	@include('opex._25010.create.bloco-formulario')
	@include('opex._25010.create.bloco-destinatario')
	@include('opex._25010.create.bloco-pergunta')

@overwrite
