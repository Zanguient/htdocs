@extends('helper.include.view.modal', ['id' => 'modal-registrar-saida-por-peca'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Consumo.ModalPeca.confirm()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Baixa por Peça
	</h4>

@overwrite

@section('modal-header-right')

    <button type="submit" class="btn btn-success btn-confirmar" id="btn-confirmar-reg-componente" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-modal-close data-confirm="yes" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')
    <input type="text" style="display:none">
    <input type="password" style="display:none">	
	<div class="form-group">
		<label class="esconder">Peça:</label>
		<input 
            type="password" 
            class="form-control" 
            placeholder="Informe o código de barras da peça"
            ng-model="vm.Consumo.ModalPeca.PECA_BARRAS"
            string-to-number
            required
            pattern=".{12,13}"
            form-validade="true"
            autocomplete="new-password"
        />
	</div>

    <div class="alert alert-warning" ng-if="vm.Filtro.TAB_ACTIVE == 'CONSUMO'">
        Atenção: A baixa será realizada de forma dstribuida entre os talões com base na quantidade projetada do talão.
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite