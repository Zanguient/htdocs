@extends('helper.include.view.modal', ['id' => 'modal-incluir', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir Mercado
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.Mercado.ALTERANDO == false" class="btn btn-success" ng-click="vm.Mercado.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.Mercado.ALTERANDO == true" class="btn btn-success" ng-click="vm.Mercado.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.Mercado.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="vm.Mercado.ALTERANDO == true" data-consulta-historico data-tabela="TBCUSTO_PADRAO" data-tabela-id="@{{ vm.Mercado.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

@overwrite

@section('modal-body')
    
    <div class="form-group">
        <label  title="Descrição do Mercado">Descrição:</label>
        <input type="text" class="form-control input-maior" ng-model="vm.Mercado.NOVO.DESCRICAO">
    </div>

    <div class="form-group">
        <label  title="Percentual do Mercado">Incentivo Padrão:</label>
        <div class="input-group left-icon" style="width: 150px;">
            <div class="input-group-addon">%</div>
            <input type="number" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="vm.Mercado.NOVO.PERC_INCENTIVO">
        </div>
    </div>
    
    <div class="form-group">
        <label  title="Se não, desabilita a mudança do incentivo">Habilitar Incentivo:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.Mercado.NOVO.INCENTIVO">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
    </div>

    <br>
    <br>

    <ul  ng-if="vm.Mercado.ALTERANDO == true" class="list-inline">    
        <li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.MercadoItens.modalIncluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
        <li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.MercadoItens.SELECTED.ID == undefined || vm.MercadoItens.ALTERANDO" 
                ng-click="vm.MercadoItens.modalAlterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
        <li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.MercadoItens.SELECTED.ID == undefined || vm.MercadoItens.ALTERANDO" 
                ng-click="vm.MercadoItens.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                     
    </ul>
    
    <div ng-if="vm.Mercado.ALTERANDO == true" class="table-ec table-scroll" style="height: 400px">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.MercadoItens.ORDER_BY">
                    <th field="ID">ID</th> 
                    <th field="DESCRICAO">Descrição</th> 
                    <th class="text-right" field="PERCENTUAL">%</th> 
                    <th class="text-right" field="FATOR">Fator</th>
                    <th class="text-right" field="AVOS">Avos</th>
                    <th class="text-center" field="DESC_FATOR">Usar Fator</th>
                    <th class="text-center" field="DESC_EDITAVEL">Editável</th>
                    <th class="text-center" field="DESC_INCENTIVO">Incentivo</th>
                    <th class="text-center" field="DESC_FRETE">Frete</th>
                    <th class="text-center" field="DESC_MARGEM">Margem</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.MercadoItens.DADOS | orderBy:vm.MercadoItens.ORDER_BY"
                    ng-click="vm.MercadoItens.SELECTED = item"
                    ng-focus="vm.MercadoItens.SELECTED = item"
                    ng-class="{ 'selected' : vm.MercadoItens.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.MercadoItens.modalAlterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESCRICAO}}
                        </span>
                    </td>
                    <td class="text-right">
                        <span>
                            @{{item.PERCENTUAL}}
                        </span>
                    </td>
                    <td  class="text-right" >
                        <span>
                            @{{item.FATOR}}
                        </span>
                    </td>
                    <td  class="text-right" >
                        <span>
                            @{{item.AVOS}}
                        </span>
                    </td>
                    <td  class="text-center" >
                        <span>
                            @{{item.DESC_FATOR}}
                        </span>
                    </td>
                    <td  class="text-center" >
                        <span>
                            @{{item.DESC_EDITAVEL}}
                        </span>
                    </td>
                    <td  class="text-center" >
                        <span>
                            @{{item.DESC_INCENTIVO}}
                        </span>
                    </td>
                    <td  class="text-center" >
                        <span>
                            @{{item.DESC_FRETE}}
                        </span>
                    </td>
                    <td  class="text-center" >
                        <span>
                            @{{item.DESC_MARGEM}}
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