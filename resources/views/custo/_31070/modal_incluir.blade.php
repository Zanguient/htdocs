@extends('helper.include.view.modal', ['id' => 'modal-incluir', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Incentivo
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.Incentivo.ALTERANDO == false" class="btn btn-success" ng-click="vm.Incentivo.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.Incentivo.ALTERANDO == true" class="btn btn-success" ng-click="vm.Incentivo.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.Incentivo.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="vm.Incentivo.ALTERANDO == true" data-consulta-historico data-tabela="TBCUSTO_INCENTIVO" data-tabela-id="@{{ vm.Incentivo.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

@overwrite

@section('modal-body')
    
    <div class="form-group">
        <label  title="Descrição do Incentivo">Descrição:</label>
        <input type="text" class="form-control input-maior" ng-model="vm.Incentivo.NOVO.DESCRICAO">
    </div>

    <div class="form-group">
        <label  title="Percentual do Incentivo">Percentual:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.Incentivo.NOVO.PERCENTUAL">
        </div>
    </div>

    <div class="form-group">
        <label  title="Percentual do Incentivo">Percentual IR:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.Incentivo.NOVO.PERCENTUAL_IR">
        </div>
    </div>  

@overwrite

@section('modal-end')
    </form>
@overwrite