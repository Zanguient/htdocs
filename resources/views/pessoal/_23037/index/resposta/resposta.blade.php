@extends('helper.include.view.modal', ['id' => 'modal-resposta', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo-listar') }}
	</h4>

@overwrite

@section('modal-header-right')

	@include('pessoal._23037.index.resposta.header')

@overwrite

@section('modal-body')

	@include('pessoal._23037.index.resposta.body-resposta-table-filter')
	@include('pessoal._23037.index.resposta.body-resposta-table')

@overwrite