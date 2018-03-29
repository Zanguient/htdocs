@extends('helper.include.view.modal', ['id' => 'modal-pesq-cliente'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-cliente') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.Create.fecharModalPesqCliente()">

		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">

			@include('chamados._26021.create.modal-pesq-cliente-filter')

		</div>

		<div class="row">

			@include('chamados._26021.create.modal-pesq-cliente-table')

		</div>

	</form>

@overwrite