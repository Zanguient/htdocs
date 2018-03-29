@extends('helper.include.view.modal', ['id' => 'modal-menu', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Menus
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.IndexMenus.ALTERANDO == false" class="btn btn-success" ng-click="vm.IndexMenus.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.IndexMenus.ALTERANDO == true" class="btn btn-success" ng-click="vm.IndexMenus.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.IndexMenus.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')
    <div style="height: 400px;">
        <div class="Consulta_Menu"></div>
    </div>
@overwrite

@section('modal-end')
    </form>
@overwrite