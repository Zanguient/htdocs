@extends('helper.include.view.modal', ['id' => 'modal-incluir', 'class_size' => 'modal-big'])

@section('modal-start')
    <form class="form-inline" name="gravar">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Incluir
	</h4>

@overwrite

@section('modal-header-right')

    <button  ng-if="vm.Index.ALTERANDO == false" class="btn btn-success" ng-click="vm.Index.incluir()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button  ng-if="vm.Index.ALTERANDO == true" class="btn btn-success" ng-click="vm.Index.alterar()" data-hotkey="f1">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button class="btn btn-danger btn-cancelar" ng-click="vm.Index.cancelar()" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="vm.Index.ALTERANDO == true" data-consulta-historico data-tabela="TBUSUARIO_PERFIL" data-tabela-id="@{{ vm.Index.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

@overwrite

@section('modal-body')
    
    <div class="form-group">
        <label  title="Descrição do Index">Descrição:</label>
        <input type="text" class="form-control input-maior" ng-model="vm.Index.NOVO.DESCRICAO">
    </div>

    <div class="form-group">
        <label  title="Se sim, o percentual é influenciado pelo incentivo">Status:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.Index.NOVO.STATUS">
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
        </div>
    </div>

    <br>
    <br>

    <div style="
        height: 360px;
        width: 400px;
        display: inline-block;
        margin: 5px;
        border: 1px solid;
        padding: 5px;
        border-radius: 5px;">
        Usuários
        <ul  ng-if="vm.Index.ALTERANDO == true" class="list-inline">    
            <li>
                <button 
                    type="button" 
                    class="btn btn-primary btn-incluir" 
                    data-hotkey="f6"
                    ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                    ng-click="vm.IndexItens.modalIncluir()">
                    <span class="glyphicon glyphicon-plus"></span> Incluir
                </button>
            </li>                   
            <li>
                <button 
                    type="button" 
                    class="btn btn-danger" 
                    data-hotkey="f8"
                    ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.IndexItens.SELECTED.ID == undefined || vm.IndexItens.ALTERANDO" 
                    ng-click="vm.IndexItens.excluir()">
                    <span class="glyphicon glyphicon-trash"></span> Excluir
                </button>
            </li>                     
        </ul>

        <div ng-if="vm.Index.ALTERANDO == true" class="table-ec table-scroll" style="height: 280px; width: 100%">
            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                <thead>
                    <tr gc-order-by="vm.IndexItens.ORDER_BY">
                        <th field="ID">ID</th> 
                        <th field="DESCRICAO">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        ng-repeat="item in vm.IndexItens.DADOS | orderBy:vm.IndexItens.ORDER_BY"
                        ng-click="vm.IndexItens.SELECTED = item"
                        ng-focus="vm.IndexItens.SELECTED = item"
                        ng-class="{ 'selected' : vm.IndexItens.SELECTED == item }"
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div style="
        height: 360px;
        width: 400px;
        display: inline-block;
        margin: 5px;
        border: 1px solid;
        padding: 5px;
        border-radius: 5px;">
        Menus
        <ul  ng-if="vm.Index.ALTERANDO == true" class="list-inline">    
            <li>
                <button 
                    type="button" 
                    class="btn btn-primary btn-incluir" 
                    data-hotkey="f6"
                    ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                    ng-click="vm.IndexItens.modalIncluir()">
                    <span class="glyphicon glyphicon-plus"></span> Incluir
                </button>
            </li>                   
            <li>
                <button 
                    type="button" 
                    class="btn btn-danger" 
                    data-hotkey="f8"
                    ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.IndexItens.SELECTED.ID == undefined || vm.IndexItens.ALTERANDO" 
                    ng-click="vm.IndexItens.excluir()">
                    <span class="glyphicon glyphicon-trash"></span> Excluir
                </button>
            </li>                     
        </ul>

        <div ng-if="vm.Index.ALTERANDO == true" class="table-ec table-scroll" style="height: 280px; width: 100%">
            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                <thead>
                    <tr gc-order-by="vm.IndexItens.ORDER_BY">
                        <th field="ID">ID</th> 
                        <th field="DESCRICAO">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        ng-repeat="item in vm.IndexItens.DADOS | orderBy:vm.IndexItens.ORDER_BY"
                        ng-click="vm.IndexItens.SELECTED = item"
                        ng-focus="vm.IndexItens.SELECTED = item"
                        ng-class="{ 'selected' : vm.IndexItens.SELECTED == item }"
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite