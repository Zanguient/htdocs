@extends('helper.include.view.modal', ['id' => 'modal-rateio-tipo', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.RateioTipo.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.RateioTipo.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.RateioTipo.ALTERANDO" ng-click="vm.RateioTipo.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f2">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.RateioTipo.ALTERANDO" ng-click="vm.RateioTipo.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.RateioTipo.ALTERANDO" ng-click="vm.RateioTipo.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.RateioTipo.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_TIPO_DETALHE" data-tabela-id="@{{ vm.RateioTipo.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.RateioTipo.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 230px);">
    <div class="row">
        
        <div class="consulta-ccusto" style="display: inline-block;"></div>
       
    </div>
    <div class="row">
        <div class="form-group">
            <label>Valor:</label>
            <input 
                type="number"
                step="0.0001"
                string-to-number
                class="form-control text-right" 
                required
                ng-model="vm.RateioTipo.SELECTED.VALOR"
                ng-readonly="!vm.RateioTipo.ALTERANDO"
                form-validate="true">
        </div>        
    </div>
    <div class="row">
        <div class="form-group">
            <label>Tipo:</label>
            <select ng-model="vm.RateioTipo.SELECTED.TIPO_ID" ng-change="vm.RateioTipo.tipoChange(vm.RateioTipo.SELECTED.TIPO_ID)">    
                <option ng-repeat="tipo in vm.RateioTipo.TIPOS | orderBy : ['TIPO_ID*1']" ng-value="tipo.TIPO_ID">@{{ tipo.TIPO_ID }} - @{{ tipo.TIPO_DESCRICAO }}</option>
            </select>
        </div>
    </div>
    
</div>
</form>

@overwrite
