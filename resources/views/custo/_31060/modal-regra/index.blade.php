@extends('helper.include.view.modal', ['id' => 'modal-regra', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.Regra.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.Regra.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.Regra.ALTERANDO" ng-click="vm.Regra.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f2">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.Regra.ALTERANDO" ng-click="vm.Regra.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.Regra.ALTERANDO" ng-click="vm.Regra.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.Regra.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_TIPO" data-tabela-id="@{{ vm.Regra.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.Regra.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 145px);">
    <div class="row">
        
        <div class="consulta-familia-agrup" style="display: inline-block;"></div>
     
        <div class="form-group">
            <label>Seq.:</label>
            <input 
                type="number"
                class="form-control input-menor text-right" 
                required
                string-to-number
                ng-model="vm.Regra.SELECTED.SEQUENCIA"
                ng-readonly="!vm.Regra.ALTERANDO"
                form-validate="true">
        </div>         
        
        <div class="consulta-familia" style="display: inline-block;"></div>
       
    </div>
    <div class="row">
        <div class="consulta-gp" style="display: inline-block;"></div>
        

        <div class="form-group">
            <input 
                type="checkbox" 
                id="chk-gp" 
                class="form-control" 
                ng-checked="vm.Regra.SELECTED.GP_TODOS == 1" 
                ng-click="
                    vm.Regra.SELECTED.GP_TODOS = vm.Regra.SELECTED.GP_TODOS == 1 ? 0 : 1;
                    vm.Regra.SELECTED.GP_TODOS == 1 ? vm.ConsultaGp.apagar() : '';
                    vm.ConsultaGp.disable(vm.Regra.SELECTED.GP_TODOS == 1);
                ">
            <label for="chk-gp">Todos</label>
        </div>    
        
    </div>
    <div class="row">
        <div class="consulta-perfil" style="display: inline-block;"></div>
        
        <div class="form-group">
            <input 
                type="checkbox" 
                id="chk-perfil" 
                class="form-control" 
                ng-checked="vm.Regra.SELECTED.PERFIL_UP_TODOS == 1" 
                ng-click="
                    vm.Regra.SELECTED.PERFIL_UP_TODOS = vm.Regra.SELECTED.PERFIL_UP_TODOS == 1 ? 0 : 1;
                    vm.Regra.SELECTED.PERFIL_UP_TODOS == 1 ? vm.ConsultaPerfil.apagar() : '';
                    vm.ConsultaPerfil.disable(vm.Regra.SELECTED.PERFIL_UP_TODOS == 1);
                ">
            <label for="chk-perfil">Todos</label>
        </div>        
    </div>
    <div class="row">
        <div class="consulta-up-1" style="display: inline-block;"></div>
    </div>
    <div class="row">
        <div class="consulta-up-2" style="display: inline-block;"></div>
    </div>
    <div class="row">

        <div class="form-group">
            <input 
                type="checkbox" 
                id="chk-rebobinamento" 
                class="form-control" 
                ng-model="vm.Regra.SELECTED.CALCULO_REBOBINAMENTO_MODEL" 
                ng-change="vm.Regra.changeRebobinamento()"
                >
            <label for="chk-rebobinamento">Cálculo Rebobinamento</label>
        </div>

        <div class="form-group">
            <input 
                type="checkbox" 
                id="chk-conformacao" 
                class="form-control" 
                ng-model="vm.Regra.SELECTED.CALCULO_CONFORMACAO_MODEL" 
                ng-change="vm.Regra.changeConformacao()"
                >
            <label for="chk-conformacao">Cálculo Conformação</label>
        </div>
        
    </div>
    <div class="row">
        <div class="consulta-ccusto" style="display: inline-block;"></div>        
        
        <div class="form-group">
            <input type="checkbox" id="chk-hierarquia" class="form-control" ng-checked="vm.Regra.SELECTED.CCUSTO_HIERARQUIA == 1" ng-click="vm.Regra.SELECTED.CCUSTO_HIERARQUIA = vm.Regra.SELECTED.CCUSTO_HIERARQUIA == 1 ? 0 : 1">
            <label for="chk-hierarquia" ttitle="Abrangir toda a hierarquia do C. Custo">Hierarquia</label>
        </div>
    </div>
    <div class="row">

        <div class="form-group">
            <label>Fator Conv.:</label>
            <input 
                type="number"
                step="0.01"
                class="form-control input-menor text-right" 
                required
                string-to-number
                ng-model="vm.Regra.SELECTED.FATOR"
                ng-readonly="!vm.Regra.ALTERANDO"
                form-validate="true">
        </div>   
        

        <div class="form-group">
            <label>Remessas Def.:</label>
            <input 
                type="number"
                class="form-control input-menor text-right" 
                required
                string-to-number
                ng-model="vm.Regra.SELECTED.REMESSAS_DEFEITO"
                ng-readonly="!vm.Regra.ALTERANDO"
                form-validate="true">
        </div>   
        
    </div>
    
</div>
</form>

@overwrite
