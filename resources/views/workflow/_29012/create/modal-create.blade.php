@extends('helper.include.view.modal', ['id' => 'modal-create', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('workflow._29012.create.botao-acao')

@overwrite

@section('modal-body')

	<info-geral-29012
		tipo-tela="$ctrl.tipoTela"></info-geral-29012>

	<tarefa-29012
		tipo-tela="$ctrl.tipoTela"></tarefa-29012>

@overwrite