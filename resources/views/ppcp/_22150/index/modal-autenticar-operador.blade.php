@extends('helper.include.view.modal', ['id' => 'modal-autenticar-operador'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Ferramenta.ListarDisponiveis()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get('master.autenticacao') }}
	</h4>

@overwrite

@section('modal-header-right')
    <button type="submit" id="imprimir-consumo" class="btn btn-success" data-hotkey="f10" data-loading-text="Confirmando...">
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
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
        pattern=".{12,12}"
        ng-model="vm.OPERADOR_BARRAS"/>


@overwrite

@section('modal-end')
    </form>
@overwrite