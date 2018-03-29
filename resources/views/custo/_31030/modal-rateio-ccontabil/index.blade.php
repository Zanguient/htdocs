@extends('helper.include.view.modal', ['id' => 'modal-rateio-ccontabil', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.RateioCContabil.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.RateioCContabil.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.RateioCContabil.ALTERANDO" ng-click="vm.RateioCContabil.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f2">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.RateioCContabil.ALTERANDO" ng-click="vm.RateioCContabil.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.RateioCContabil.ALTERANDO" ng-click="vm.RateioCContabil.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.RateioCContabil.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_CONTA_CONTABIL" data-tabela-id="@{{ vm.RateioCContabil.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.RateioCContabil.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 150px);">
    <div class="row">
        
        <div class="consulta-ccontabil" style="display: inline-block;"></div>
       
    </div>
    <div class="row">
        
        <div class="form-group">
            <label>Tipo:</label>
            <select required ng-model="vm.RateioCContabil.SELECTED.TIPO_ID" ng-change="vm.RateioCContabil.tipoChange(vm.RateioCContabil.SELECTED.TIPO_ID)">    
                <option value="">-- Selecione --</option>
                <option ng-repeat="tipo in vm.rateioTipos | orderBy : ['ID*1']" ng-value="tipo.ID">@{{ tipo.ID }} - @{{ tipo.DESCRICAO }}</option>
            </select>
        </div> 
        
<!--        <div class="form-group">
            <label>Regra:</label>
            <select ng-model="vm.RateioCContabil.SELECTED.REGRA_RATEAMENTO" ng-change="vm.RateioCContabil.regraChange(vm.RateioCContabil.SELECTED.REGRA_RATEAMENTO)">    
                <option ng-value="1">01 - DEFINIDO PELA CONSULTA(ORIGEM)</option>
                <option ng-value="2">02 - FIXO (TBRATEAMENTO_CONTABIL_CCONTABIL)</option>
                <option ng-value="3">03 - COLABORADOR</option>
                <option ng-value="4">04 - COLABORADOR/TRANSPORTE</option>
                <option ng-value="5">05 - COLABORADOR/REFEICAO</option>
                <option ng-value="6">06 - AREA</option>
                <option ng-value="7">07 - SETORES BALANCIM HIDRAULICO</option>                
            </select>
        </div>-->
        <div class="form-group">
            <label>Origem:</label>
            <select ng-model="vm.RateioCContabil.SELECTED.VALOR_ORIGEM" ng-change="vm.RateioCContabil.origemChange(vm.RateioCContabil.SELECTED.VALOR_ORIGEM)">
                <option ng-value="1">01 - LANCAMENTO DE ESTOQUE</option>
                <option ng-value="2">02 - LANCAMENTO CONTABIL</option>
                <option ng-value="3">03 - INDEFINIDO</option>
                <option ng-value="4">04 - LANCAMENTO CONTABIL SEM CONSIDERAR O CENTRO DE CUSTO</option>
                <option ng-value="5">05 - DEPRECIACAO</option>
                
            </select>
        </div>
        <div class="form-group">
            <label>Grupo:</label>
            <select ng-model="vm.RateioCContabil.SELECTED.RATEAMENTO_GRUPO" ng-change="vm.RateioCContabil.grupoChange(vm.RateioCContabil.SELECTED.RATEAMENTO_GRUPO)">
                <option ng-value="1">01 - CUSTO DE MÃO DE OBRA INDIRETA</option>
            </select>
        </div>
    </div>
    
</div>
</form>

@overwrite
