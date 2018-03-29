@extends('helper.include.view.modal', ['id' => 'modal-create-arquivo-todos'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo-todos-arquivos') }}
	</h4>

@overwrite

@section('modal-header-right')
	
	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.fecharModalArquivoTodos()">

		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	@include('workflow._29012.create.info-geral-arquivo')

@overwrite