@extends('helper.include.view.modal', ['id' => 'modal-create'])

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

	@include('pessoal._23038.create.header')

@overwrite

@section('modal-body')

	@include('pessoal._23038.create.body')

@overwrite