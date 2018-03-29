@extends('helper.include.view.modal', ['id' => 'modal-incluir-itens', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Itens
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.MercadoItens.ALTERANDO == false" class="btn btn-success" ng-click="vm.MercadoItens.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.MercadoItens.ALTERANDO == true" class="btn btn-success" ng-click="vm.MercadoItens.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.MercadoItens.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')

    <div class="form-group">
        <label  title="Descrição do MercadoItens">Descrição:</label>
        <input type="text" class="form-control input-maior" ng-model="vm.MercadoItens.NOVO.DESCRICAO">
    </div>

    <div class="form-group">
        <label  title="Percentual">Percentual:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.MercadoItens.NOVO.PERCENTUAL">
        </div>
    </div>

    <div class="form-group">
        <label  title="Fator Padrão do Percentual">Fator:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.MercadoItens.NOVO.FATOR">
        </div>
    </div>

    <div class="form-group">
        <label  title="Avos do Percentual">Avos:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.MercadoItens.NOVO.AVOS">
        </div>
    </div>
    
    <div class="form-group">
        <label  title="Se sim, usa o fator para calcular o percentual">Usar Fator:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.MercadoItens.NOVO.USAR_FATOR">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label  title="Se sim, é possível editar o percentual">Editável:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.MercadoItens.NOVO.EDITAVEL">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label  title="Se sim, o percentual é influenciado pelo incentivo">Incentivo:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.MercadoItens.NOVO.INCENTIVO">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label  title="Se sim, este item é um % de frete">Frete:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.MercadoItens.NOVO.FRETE">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label  title="Se sim, este item é afetado pela margem">Margem:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.MercadoItens.NOVO.MARGEM">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <br>
    <br>

    <ul  ng-if="vm.MercadoItens.ALTERANDO == true" class="list-inline">    
        <li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.MercadoItensConta.modalIncluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                                             
        <li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.MercadoItensConta.SELECTED.ID == undefined || vm.MercadoItensConta.ALTERANDO" 
                ng-click="vm.MercadoItensConta.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                     
    </ul>
    
    <div ng-if="vm.MercadoItens.ALTERANDO == true" class="table-ec table-scroll" style="height: 400px">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.MercadoItensConta.ORDER_BY">
                    <th field="ID">ID</th>
                    <th field="CONTA">Conta</th> 
                    <th field="DESCRICAO">Descrição</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.MercadoItensConta.DADOS | orderBy:vm.MercadoItensConta.ORDER_BY"
                    ng-click="vm.MercadoItensConta.SELECTED = item"
                    ng-focus="vm.MercadoItensConta.SELECTED = item"
                    ng-class="{ 'selected' : vm.MercadoItensConta.SELECTED == item }"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.CONTA}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESCRICAO}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite