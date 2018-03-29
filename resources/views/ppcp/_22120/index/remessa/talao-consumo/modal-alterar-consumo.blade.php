@extends('helper.include.view.modal', ['id' => 'modal-alterar-consumo',])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Consumo.alterar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Alterar Consumo
	</h4>

@overwrite

@section('modal-header-right')

    <button
        ng-disabled="vm.selected_itens_acao['CONSUMO'] == undefined || vm.selected_itens_acao['CONSUMO'].length == 0"
        type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
		<span class="glyphicon glyphicon-ok"></span> 
		Gravar
	</button>
	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>

@overwrite

@section('modal-body')
<div style="height: 500px">
    
    <div
        class="alert alert-danger"
        ng-if="vm.selected_itens_acao['CONSUMO'] == undefined || vm.selected_itens_acao['CONSUMO'].length == 0"
        >
        Selecione um produto de consumo
    </div>
    
    <div class="cons-consulta-produto"></div>
    <div class="cons-consulta-modelo-tamanho"></div>
        
</div>
@overwrite

@section('modal-end')
    </form>
@overwrite