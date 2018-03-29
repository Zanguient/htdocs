@extends('helper.include.view.modal', ['id' => 'modal-frete', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.Frete.Modal.hide();">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    
</h4>

@overwrite

@section('modal-header-right')

<!--    <button ng-if="vm.Frete.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.Frete.ALTERANDO" ng-click="vm.Frete.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f2">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="!vm.Frete.ALTERANDO" ng-click="vm.Frete.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="!vm.Frete.ALTERANDO" ng-click="vm.Frete.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="!vm.Frete.INCLUINDO" data-consulta-historico data-tabela="TBRATEAMENTO_TIPO" data-tabela-id="@{{ vm.Frete.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>-->

    <button ng-if="!vm.Frete.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
<div style="height: calc(100vh - 145px);">
    
    @include('logistica._14020.frete-detalhamento')
    
</div>
</form>

@overwrite
