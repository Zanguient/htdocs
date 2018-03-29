@extends('helper.include.view.modal', ['id' => 'modal-gp'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Autenticar Grupo de Produção
	</h4>

@overwrite

@section('modal-header-right')
    <button type="submit" id="imprimir-consumo" class="btn btn-success" data-hotkey="f10" data-loading-text="Confirmando...">
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>
    <button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span>
        Cancelar
    </button>
@overwrite

@section('modal-body')
	
	<div class="form-group">
		<label>{{ Lang::get('master.operador') }}:</label>
	</div>
    <input 
        style="width: 100%;"
        type="password" 
        placeholder="Insira o código do crachá" 
        autocomplete="new-password"
        form-validade="true"
        required
        ng-model="vm.Gp.BARRAS"/>


@overwrite

@section('modal-end')
    </form>
@overwrite