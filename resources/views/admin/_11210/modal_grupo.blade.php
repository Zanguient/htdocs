@extends('helper.include.view.modal', ['id' => 'modal-grupo', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Grupos de Menu
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.IndexGrupos.ALTERANDO == false" class="btn btn-success" ng-click="vm.IndexGrupos.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.IndexGrupos.ALTERANDO == true" class="btn btn-success" ng-click="vm.IndexGrupos.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.IndexGrupos.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')
    <div style="height: 400px;">
        <div class="Consulta_Grupo"></div>
    </div>
@overwrite

@section('modal-end')
    </form>
@overwrite