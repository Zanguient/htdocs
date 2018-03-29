@extends('helper.include.view.modal', ['id' => 'modal-painel', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo-painel') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('opex._25010.index.botao-acao-painel')

@overwrite

@section('modal-body')

	@include('opex._25010.index.bloco-painel')	

@overwrite