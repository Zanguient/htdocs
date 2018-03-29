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

    <button ng-if="!vm.RateioTipo.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_TIPO" data-tabela-id="@{{ vm.RateioTipo.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
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
            <label>Descrição:</label>
            <input 
                type="text"
                class="form-control input-maior" 
                required
                ng-model="vm.RateioTipo.SELECTED.DESCRICAO"
                ng-readonly="!vm.RateioTipo.ALTERANDO"
                form-validate="true">
        </div>        
    </div>
    <div class="row">
        <div class="form-group">
            <label>Data Inicial:</label>
            <div class="input-group">
                <input 
                    type="date" 
                    ng-model="vm.RateioTipo.SELECTED.DATA_INICIAL_MODEL" 
                    ng-change="vm.RateioTipo.changeDataInicial()"
                    max="@{{ vm.RateioTipo.SELECTED.DATA_FINAL_MODEL | date: 'dd/MM/yyyy' }}" 
                    class="form-control" 
                    required />
                <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>      
        <div class="form-group">
            <label>Data Final:</label>
            <div class="input-group">
                <input 
                    type="date" 
                    ng-model="vm.RateioTipo.SELECTED.DATA_FINAL_MODEL" 
                    ng-change="vm.RateioTipo.changeDataFinal()"
                    ng-disabled="vm.RateioTipo.SELECTED.DATA_CORRENTE" 
                    toDate 
                    class="form-control" 
                    required />
                <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>    
 
        <div class="form-group">
            <input 
                type="checkbox" 
                id="chk-data-corrente" 
                class="form-control" 
                ng-model="vm.RateioTipo.SELECTED.DATA_CORRENTE" 
                ng-change="vm.RateioTipo.changeDataCorrente()"
                >
            <label for="chk-data-corrente" ttitle="Abrangir toda a hierarquia do C. Custo">Data Atual</label>
        </div>
        
    </div>
    <div class="row">
        <div class="consulta-unidade-medida" style="display: inline-block;"></div>
    </div>
    
</div>
</form>

@overwrite
