@extends('helper.include.view.modal', ['id' => 'modal-parametro-detalhe'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Autenticar Grupo de Produção
	</h4>

@overwrite

@section('modal-header-right')


@overwrite

@section('modal-body')



@overwrite

@section('modal-end')
    </form>
@overwrite