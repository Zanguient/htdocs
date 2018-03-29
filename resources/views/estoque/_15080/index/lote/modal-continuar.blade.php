@extends('helper.include.view.modal', ['id' => 'modal-lote-continuar'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Lote.continuarConfirm()" form-validade="true">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Continur Lote
	</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.Filtro.LOCALIZACAO_ID >0" type="submit" class="btn btn-success btn-confirmar" id="btn-confirmar-reg-componente" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>

@overwrite

@section('modal-body')
    
<fieldset>
    <legend>Selecione a Localização Estoque</legend>
    
    <div style="
        padding: 0 0 4px 10px;
        border-radius: 6px;
        background: rgb(226, 226, 226);
        margin-top: -7px;
    ">
        <label style="margin-right: 10px;" ng-repeat="localizacao in vm.Lote.LOCALIZACOES | orderBy: 'LOCALIZACAO_DESCRICAO'">
            <input 
                type="radio" 
                style="top: 5px;" 
                ng-model="vm.Filtro.LOCALIZACAO_ID" 
                ng-value="localizacao.LOCALIZACAO_ID"
                ng-click="vm.Lote.PRE_SELECTED = localizacao">
            <span ng-style="{'font-weight' : localizacao.LOCALIZACAO_ID == vm.Filtro.LOCALIZACAO_ID ? 'bold' : 'initial'}">@{{ localizacao.LOCALIZACAO_DESCRICAO }}</span>
        </label>
    </div>    
</fieldset>

@overwrite

@section('modal-end')
    </form>
@overwrite