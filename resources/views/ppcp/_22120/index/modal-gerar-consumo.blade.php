@extends('helper.include.view.modal', ['id' => 'modal-gerar-consumo'])

@section('modal-header-left')

	<h4 class="modal-title">
		Geração de Consumo de Remessa
	</h4>

@overwrite

@section('modal-header-right')

    <button type="button" id="imprimir-consumo" class="btn btn-success" ng-click="vm.Consumo.Gerar()" data-hotkey="f10" data-loading-text="Gerando...">
		<span class="glyphicon glyphicon-ok"></span> 
		Gerar
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
                <label title="Familía de produto do consumo">Selecione uma familia de consumo:</label>
				<div class="input-group">
                    <select name="repeatSelect" id="repeatSelect" ng-model="vm.Consumo.FAMILIA_SELECTED">
                        <option
                            ng-repeat="familia in vm.Consumo.FAMILIAS" 
                            value="@{{ familia.FAMILIA_ID }}"
                            > @{{ familia.FAMILIA_DESCRICAO }}</option>
                    </select>
				</div>
			</div>
		</div>
	</form>

@overwrite