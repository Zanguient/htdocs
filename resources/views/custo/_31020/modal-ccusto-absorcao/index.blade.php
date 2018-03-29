@extends('helper.include.view.modal', ['id' => 'modal-ccusto-absorcao'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.CCustoAbsorcao.processarOrdem(); vm.CCustoAbsorcao.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-click="vm.CCustoAbsorcao.confirmar()" type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-click="vm.CCustoAbsorcao.cancelar()" type="button" class="btn btn-danger">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 315px);">
    <div class="row">
 
        <div class="ca-consulta-ccusto" style="display: inline-block;"></div>
                
        <div class="form-group">
            <label ttitle="Percentual de absorção">% Absorção:</label>
            <input 
                type="number"
                step="0.01"
                string-to-number
                class="form-control input-menor" 
                required
                ng-model="vm.CCustoAbsorcao.SELECTED.PERC_ABSORCAO"
                ng-readonly="!vm.CCustoAbsorcao.ALTERANDO"
                form-validate="true">
        </div>   
        
    </div>
</div>
</form>

@overwrite
