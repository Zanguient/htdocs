@extends('helper.include.view.modal', ['id' => 'modal-pessoal-colaborador-centro-de-trabalho'])



@section('modal-header-left')

	<h4 class="modal-title" ttitle="Registro de Colaborador por Centro de Trabalho">
		Reg. de Colaborador por C. de Trabalho
	</h4>

@overwrite

@section('modal-header-right')
    <button 
        type="submit" 
        class="btn btn-success" 
        data-hotkey="f10" 
        data-loading-text="Confirmando..."
        ng-disabled="!ctrl.CONFIRMAR"
        ng-click="ctrl.confirmar()"
        >
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>


    <button 
        ng-if="ctrl.Colaborador.AUTENTICADO" 
        ng-click="ctrl.cancelar()"
        type="button" class="btn btn-danger btn-cancelar" data-hotkey="esc" tabindex="-1">
        <span class="glyphicon glyphicon-ban-circle"></span> 
        Cancelar
    </button>

    <button ng-if="!ctrl.Colaborador.AUTENTICADO" type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc" tabindex="-1">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
<div style="height: 460px">
    

    <form class="form-inline" ng-submit="ctrl.autenticar()">
        <div class="input-group" style="width: 100%;">
            <input 
                id="centro-de-trabalho-colaborador-barras"
                class="form-control"
                placeholder="Código de barras do Colaborador"
                type="@{{ ctrl.Colaborador.AUTENTICADO ? 'text' : 'password' }}" 
                required
                form-validate="true"
                ng-model="ctrl.Colaborador.BARRAS"   
                ng-value="ctrl.Colaborador.SELECTED.COLABORADOR_NOME"   
                ng-disabled="ctrl.Colaborador.AUTENTICADO" />
            <button ng-if="!ctrl.Colaborador.AUTENTICADO" type="submit" class="input-group-addon btn-filtro" tabindex="-1">
                <span class="fa fa-search"></span>
            </button>
            <button 
                ng-if="ctrl.Colaborador.AUTENTICADO" 
                ng-click="ctrl.Colaborador.AUTENTICADO = false"
                type="button" class="input-group-addon btn-filtro" tabindex="-1">
                <span class="fa fa-close"></span>
            </button>
        </div>   
    </form>


        <div class="cct-consulta-perfil"></div>
        <div class="cct-consulta-up"></div>
</div>
@overwrite
