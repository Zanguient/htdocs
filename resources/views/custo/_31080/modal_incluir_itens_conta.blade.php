@extends('helper.include.view.modal', ['id' => 'modal-incluir-conta'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Contas
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.MercadoItensConta.ALTERANDO == false" class="btn btn-success" ng-click="vm.MercadoItensConta.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.MercadoItensConta.ALTERANDO == true" class="btn btn-success" ng-click="vm.MercadoItensConta.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.MercadoItensConta.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')
<div style="height: 400px">
    <div class="consulta-conta"></div>

    <br>
</div>
@overwrite

@section('modal-end')
    </form>
@overwrite