@extends('helper.include.view.modal', ['id' => 'modal-registrar-componente'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.TalaoConsumo.componenteRegistrar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Registrar Componente
	</h4>

@overwrite

@section('modal-header-right')

    <button type="submit" class="btn btn-success btn-confirmar" id="btn-confirmar-reg-componente" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')
	
	<div class="form-group" style="width: calc(100% - 10px);">
		<label class="esconder">Código de Barras:</label>
        <input form-validade="true" type="password" ng-init="vm.TalaoConsumo.COMPONENTE_BARRAS = ''" ng-model="vm.TalaoConsumo.COMPONENTE_BARRAS" class="form-control" autocomplete="off"  style="width: 100%;"/>
	</div>

@overwrite

@section('modal-end')
    </form>
@overwrite