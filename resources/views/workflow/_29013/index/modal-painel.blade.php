@extends('helper.include.view.modal', ['id' => 'modal-create', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('workflow._29013.index.modal-painel-botao-acao')

@overwrite

@section('modal-body')

	@include('workflow._29013.index.modal-painel-info-geral')
	@include('workflow._29013.index.modal-painel-tarefa-base')

@overwrite