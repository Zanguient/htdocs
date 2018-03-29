@extends('helper.include.view.modal', ['id' => 'modal-rateio-ccusto'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.RateioCCusto.processarOrdem(); vm.RateioCCusto.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.RateioCCusto.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.RateioCCusto.ALTERANDO" ng-click="vm.RateioCCusto.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f2">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.RateioCCusto.ALTERANDO" ng-click="vm.RateioCCusto.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.RateioCCusto.ALTERANDO" ng-click="vm.RateioCCusto.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.RateioCCusto.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_CCUSTO" data-tabela-id="@{{ vm.RateioCCusto.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.RateioCCusto.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 150px);">
    <div class="row">
        
        <div class="form-group">
            <label ttitle="Abrangência">Abrang.:</label>
            <input 
                type="text"
                class="form-control input-menor" 
                ng-model="vm.RateioCCusto.SELECTED.ABRANGENCIA"
                required
                ng-readonly="!vm.RateioCCusto.ALTERANDO">
        </div>    
        
        <div class="form-group">
            <label>Ordem:</label>
            <input 
                type="text"
                class="form-control input-menor" 
                ng-model="vm.RateioCCusto.SELECTED.ORDEM"
                required
                ng-readonly="!vm.RateioCCusto.ALTERANDO">
        </div>    
        
        <div class="consulta-ccusto" style="display: inline-block;"></div>
        
        <div class="form-group">
            <input type="checkbox" id="chk-hierarquia" class="form-control" ng-checked="vm.RateioCCusto.SELECTED.HIERARQUIA == 1" ng-click="vm.RateioCCusto.SELECTED.HIERARQUIA = vm.RateioCCusto.SELECTED.HIERARQUIA == 1 ? 0 : 1">
            <label for="chk-hierarquia" ttitle="Abrangir toda a hierarquia do C. Custo">Hierarquia</label>
        </div>
        
    </div>
    <div class="row">
        
        <div class="form-group">
            <label>Tipo:</label>
            <select required ng-model="vm.RateioCCusto.SELECTED.TIPO_ID" ng-change="vm.RateioCCusto.tipoChange(vm.RateioCCusto.SELECTED.TIPO_ID)">    
                <option value="">-- Selecione --</option>
                <option ng-repeat="tipo in vm.rateioTipos | orderBy : ['ID*1']" ng-value="tipo.ID">@{{ tipo.ID }} - @{{ tipo.DESCRICAO }}</option>
            </select>
        </div>    
<!--
        <div class="form-group">
            <label>Regra:</label>
            <select ng-model="vm.RateioCCusto.SELECTED.REGRA_RATEAMENTO" ng-change="vm.RateioCCusto.regraChange(vm.RateioCCusto.SELECTED.REGRA_RATEAMENTO)">
                <option ng-value="1">01 - COLABORADORES</option>
                <option ng-value="2">02 - AREA</option>
                <option ng-value="3">03 - MÁQUINAS</option>
                <option ng-value="4">04 - FIXO</option>
            </select>
        </div>-->
<!--        <div class="form-group">
            <label>Origem:</label>
            <select ng-model="vm.RateioCCusto.SELECTED.VALOR_ORIGEM" ng-change="vm.RateioCCusto.origemChange(vm.RateioCCusto.SELECTED.VALOR_ORIGEM)">
                <option ng-value="1">01 - SALÁRIOS</option>
                <option ng-value="2">02 - OUTROS</option>
            </select>
        </div>-->
        <div class="form-group">
            <label>Grupo:</label>
            <select ng-model="vm.RateioCCusto.SELECTED.RATEAMENTO_GRUPO" ng-change="vm.RateioCCusto.grupoChange(vm.RateioCCusto.SELECTED.RATEAMENTO_GRUPO)">
                <option ng-value="1">01 - CUSTO DE MÃO DE OBRA INDIRETA</option>
            </select>
        </div>
    </div>
    
    <button
        type="button"
        class="btn btn-primary btn-xs" 
        style="margin-bottom: 5px;"
        ng-click="vm.CCustoAbsorcao.incluir()"
        >
        <span class="glyphicon glyphicon-plus"></span>
        Incluir C.Custo
    </button>
    
    <button
        type="button"
        class="btn btn-danger btn-xs" 
        style="margin-bottom: 5px;"
        ng-click="vm.CCustoAbsorcao.excluir()"
        ng-disabled="vm.CCustoAbsorcao.SELECTEDS.length == 0"
        >
        <span class="glyphicon glyphicon-trash"></span>
        Excluir C.Custo
    </button>
    
    <div class="table-ec table-scroll" style="    height: calc(100vh - 370px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr>
                    <th>Centro de Custo</th>
                    <th class="text-right" ttitle="Percentual de absorção">% Absor.</th>
                    <!--<th>Grupo</th>-->
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.RateioCCusto.SELECTED.CCUSTOS | filter : { EXCLUIDO : false } | orderBy : ['CCUSTO_MASKA']"
                    ng-class="{ 'selected': vm.CCustoAbsorcao.picked(item) }"
                    ng-click="vm.CCustoAbsorcao.pickToggle(item)"                    
                    >
                    <td>
                        <span style="float: left; width: 70px;">@{{ item.CCUSTO_MASK }}</span>
                         @{{ item.CCUSTO_DESCRICAO }}
                    </td>
                    <td class="text-right">@{{ item.PERC_ABSORCAO | number : 2 }}</td>
                    <!--<td>@{{ item.RATEAMENTO_GRUPO_DESCRICAO }}</td>-->      
                </tr>
            </tbody>
        </table>
    </div>    
    
</div>
</form>

@overwrite
