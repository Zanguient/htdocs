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

	@include('workflow._29010.create.botao-acao')

@overwrite

@section('modal-body')

	<info-geral-29010
		tipo-tela="$ctrl.tipoTela"></info-geral-29010>

	<tarefa-29010
		tipo-tela="$ctrl.tipoTela"></tarefa-29010>

@overwrite