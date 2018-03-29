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

	@include('pessoal._23035.create.header')

@overwrite

@section('modal-body')

	@include('pessoal._23035.create.body-modelo')
	@include('pessoal._23035.create.body-fator')
	@include('pessoal._23035.create.body-formacao')
	@include('pessoal._23035.create.body-resumo')
	@include('pessoal._23035.create.body-media')

@overwrite