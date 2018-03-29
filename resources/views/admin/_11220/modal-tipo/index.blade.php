@extends('helper.include.view.modal', ['id' => 'modal-tipo', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.Tipo.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.Tipo.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.Tipo.ALTERANDO" ng-click="vm.Tipo.cancelar()" type="button" class="btn btn-danger btn-cancelar" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.Tipo.ALTERANDO" ng-click="vm.Tipo.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.Tipo.ALTERANDO" ng-click="vm.Tipo.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.Tipo.INCLUINDO" data-consulta-historico data-tabela="TBIMOBILIZADO_TIPO" data-tabela-id="@{{ vm.Tipo.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.Tipo.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 230px);">
    <div class="row">
        
        <div class="form-group">
            <label>ID:</label>
            <input 
                type="text"
                class="form-control input-menor" 
                ng-model="vm.Tipo.SELECTED.ID"
                required
                disabled />
        </div>            
        
        <div class="form-group">
            <label>Descrição:</label>
            <input 
                type="text"
                class="form-control input-maior" 
                required
                ng-model="vm.Tipo.SELECTED.DESCRICAO"
                ng-readonly="!vm.Tipo.ALTERANDO"
                form-validate="true">
        </div>        
    </div>
    
    <div class="row">
        <div class="form-group">
            <label>Taxa Depr.%:</label>
            <input 
                type="number"
                step="0.0001"
                string-to-number
                class="form-control text-right" 
                required
                ng-model="vm.Tipo.SELECTED.TAXA_DEPRECIACAO_CALC"
                ng-change="vm.Tipo.SELECTED.VIDA_UTIL = (100 / vm.Tipo.SELECTED.TAXA_DEPRECIACAO_CALC).toFixed(4); vm.Tipo.SELECTED.TAXA_DEPRECIACAO = (vm.Tipo.SELECTED.TAXA_DEPRECIACAO_CALC / 100).toFixed(4)"
                ng-readonly="!vm.Tipo.ALTERANDO"
                form-validate="true">
        </div>        
        <div class="form-group">
            <label>Vida Útil (em anos):</label>
            <input 
                type="number"
                step="0.0001"
                string-to-number
                class="form-control text-right" 
                required
                ng-change="vm.Tipo.SELECTED.TAXA_DEPRECIACAO_CALC = (100 / vm.Tipo.SELECTED.VIDA_UTIL).toFixed(4); vm.Tipo.SELECTED.TAXA_DEPRECIACAO = (vm.Tipo.SELECTED.TAXA_DEPRECIACAO_CALC / 100).toFixed(4)"
                ng-model="vm.Tipo.SELECTED.VIDA_UTIL"
                ng-readonly="!vm.Tipo.ALTERANDO"
                form-validate="true">
        </div>        
    </div>    
    
    <div class="row">
        <div class="form-group">
            <label>Tipo:</label>
            <select required ng-model="vm.Tipo.SELECTED.TIPO_GASTO" ng-change="vm.Tipo.SELECTED.TIPO_GASTO_SELECT = vm.selectById(vm.TIPOS_GASTO,vm.Tipo.SELECTED.TIPO_GASTO)">    
                <option ng-repeat="tipo in vm.TIPOS_GASTO | orderBy : ['ID*1']" ng-value="tipo.ID">@{{ tipo.ID }} - @{{ tipo.DESCRICAO }}</option>
            </select>
        </div>
    </div>
    
    <div class="row">
        
        <div class="consulta-ccontabil" style="display: inline-block;"></div>
       
    </div>
    
</div>
</form>

@overwrite
