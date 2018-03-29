@extends('helper.include.view.modal', ['id' => 'modal-consumo'])

@section('modal-header-left')

	<h4 class="modal-title">
		Imprimir Consumo
	</h4>

@overwrite

@section('modal-header-right')

    <button type="button" id="imprimir-consumo" class="btn btn-warning" ng-click="vm.getConsumo()" data-hotkey="f10" data-loading-text="Imprimindo...">
		<span class="glyphicon glyphicon-ok"></span> 
		Imprimir
	</button>
	<button type="button" class="btn btn-default" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">
			<div class="form-group">
                <label title="Familía de produto do consumo">Família:</label>
				<div class="input-group">
                    <select name="repeatSelect" id="repeatSelect" ng-model="vm.consumo_dados.familia_id_consumo">
                        <option selected value="">TODOS</option>
                        <option ng-repeat="consumo in vm.familias_consumo" value="@{{ consumo.FAMILIA_ID }}">@{{ consumo.FAMILIA_DESCRICAO }}</option>
                    </select>
				</div>
			</div>
		</div>
	</form>

@overwrite