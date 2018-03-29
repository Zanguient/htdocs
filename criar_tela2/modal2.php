@extends('helper.include.view.modal', ['id' => 'modal-incluir-itens', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Itens
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.IndexItens.ALTERANDO == false" class="btn btn-success" ng-click="vm.IndexItens.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.IndexItens.ALTERANDO == true" class="btn btn-success" ng-click="vm.IndexItens.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.IndexItens.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')

    <div class="form-group">
        <label  title="Descrição">Descrição:</label>
        <input type="text" class="form-control input-maior" ng-model="vm.IndexItens.NOVO.DESCRICAO">
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite