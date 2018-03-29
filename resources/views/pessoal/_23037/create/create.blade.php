@extends('helper.include.view.modal', ['id' => 'modal-avaliacao', 'class_size' => 'modal-big'])

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
	<h4 class="modal-title" ng-if="$ctrl.tipoTela == 'responder'">
		{{ Lang::get($menu.'.titulo-responder') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('pessoal._23037.create.header')

@overwrite

@section('modal-body')

	@include('pessoal._23037.create.body-avaliacao')
	@include('pessoal._23037.create.body-fator')
	@include('pessoal._23037.create.body-formacao')
	@include('pessoal._23037.create.body-resumo')
	@include('pessoal._23037.create.body-media')
	@include('pessoal._23037.create.body-aspecto')

	{{-- PARA IMPRIMIR --}}
	<div id="print-avaliacao-desempenho" style="display: none;">

		@include('pessoal._23037.create.print.body-avaliacao')
		@include('pessoal._23037.create.print.body-fator')
		@include('pessoal._23037.create.print.body-formacao')
		@include('pessoal._23037.create.print.body-resumo')
		@include('pessoal._23037.create.print.body-media')
		@include('pessoal._23037.create.print.body-aspecto')

	</div>

@overwrite