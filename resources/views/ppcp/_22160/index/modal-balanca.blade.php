@extends('helper.include.view.modal', ['id' => 'modal-balanca','class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.ConsumoBaixarBalanca.modalOperador()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Capturando Peso...
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

    <div class="row">
		<div class="form-group">
			<label for="balanca-produto">Produto:</label>
			<input 
                type="text" 
                class="form-control" 
                id="balanca-produto" 
                readonly
                value="@{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_PRODUTO_ID }} - @{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_PRODUTO_DESCRICAO }}"/>
		</div>
	</div>
<!--	<div class="row">
		<div class="form-group">
			<label for="balanca-quantidade-projetada">Qtd. Proj.:</label>
			<div class="input-group">
				<input 
                    type="text" 
                    class="form-control" 
                    id="balanca-quantidade-projetada" 
                    readonly
                    value="@{{ vm.ConsumoBaixarBalanca.SELECTED.QUANTIDADE_PROJECAO | number:4 }}"
                    />
				<div class="input-group-addon um">@{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_UM }}</div>
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-quantidade-consumida">Qtd. Cons.:</label>
			<div class="input-group">
				<input 
                    type="text" 
                    class="form-control" 
                    id="balanca-quantidade-consumida" 
                    readonly
                    value="@{{ vm.ConsumoBaixarBalanca.SELECTED.QUANTIDADE_CONSUMO | number:4 }}"
                    />
				<div class="input-group-addon um">@{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_UM }}</div>
			</div>
		</div>
	</div>-->
	<div class="row">
		<div class="form-group" ng-if="vm.ConsumoBaixarBalanca.SELECTED.FILTERED == undefined">
			<label for="balanca-quantidade-saldo">Qtd. Saldo:</label>
			<div class="input-group">
				<input 
                    type="text" 
                    class="form-control" 
                    id="balanca-quantidade-saldo" 
                    readonly 
                    value="@{{ vm.ConsumoBaixarBalanca.SELECTED.QUANTIDADE_SALDO | number:4 }}"
                    />
				<div class="input-group-addon um">@{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_UM }}</div>
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-quantidade-baixar">Qtd. Baixar:</label>
			<div class="input-group">
                <input 
                    type="number" 
                    step="0.0001" 
                    min="0.0001"
                    max="@{{ vm.ConsumoBaixarBalanca.SELECTED.FILTERED == undefined ? vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_TOLERANCIA_MAX : vm.ConsumoBaixarBalanca.SELECTED.QUANTIDADE_SALDO }}"
                    class="form-control" 
                    id="balanca-quantidade-baixar"  
                    form-validade="true"
                    required
                    ng-model="vm.ConsumoBaixarBalanca.PESO"
                    ng-change="vm.ConsumoBaixarBalanca.setItens();"
                    ng-keydown="vm.ConsumoBaixarBalanca.inputKeydown($event)"
                    ng-style="{'background-color': vm.ConsumoBaixarBalanca.PESO_AUTOMATICO ? 'rgb(245, 245, 245)' : 'initial'}"
                    />
				<div class="input-group-addon um">@{{ vm.ConsumoBaixarBalanca.SELECTED.CONSUMO_UM }}</div>
                <input type="hidden" class="gc-print-recebe-peso"/>
			</div>
		</div>
	</div>

    <div class="row">
        <fieldset>
            <legend>Talões a serem baixados</legend>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Remessa / Talão</th>
                        <th>Qtd. Talão</th>
                        <th>Qtd. Cons. Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in vm.ConsumoBaixarBalanca.ITENS_BAIXAR track by $index">
                        <td>@{{ item.REMESSA }} / @{{ item.REMESSA_TALAO_ID }}</td>
                        <td class="text-right um">@{{ item.TALAO_QUANTIDADE | number: 0 }} @{{ item.TALAO_UM }}</td>
                        <td class="text-right um">@{{ item.QUANTIDADE_SALDO | number: 4 }} @{{ item.CONSUMO_UM }}</td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite