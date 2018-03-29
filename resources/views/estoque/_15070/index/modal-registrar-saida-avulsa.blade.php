@extends('helper.include.view.modal', ['id' => 'modal-registrar-saida-avulsa'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Consumo.ModalAvulso.confirm()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Baixa Avulsa
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
	
	<div class="form-group">
		<label class="esconder">Quantidade:</label>
		<input 
            type="number" 
            step="0.0001"
            min="0.0001"
            max="@{{ vm.Consumo.ModalAvulso.ITEM.QUANTIDADE_ESTOQUE }}"
            class="form-control" 
            autocomplete="off"
            placeholder="Informe o código de barras da peça"
            ng-model="vm.Consumo.ModalAvulso.QUANTIDADE"
            string-to-number
            required
            form-validade="true"/>
	</div>

    <div class="alert alert-warning" ng-if="vm.Filtro.TAB_ACTIVE == 'CONSUMO'">
        Atenção: A baixa será realizada de forma dstribuida entre os talões com base na quantidade projetada do talão.
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite