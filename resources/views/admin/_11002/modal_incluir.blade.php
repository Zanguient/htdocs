@extends('helper.include.view.modal', ['id' => 'modal-incluir', 'class_size' => 'modal-lg'])

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

    <button ng-if="vm.Index.ALTERANDO == true" data-consulta-historico data-tabela="TBUSUARIO" data-tabela-id="@{{ vm.Index.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span>Histórico
    </button>

@overwrite

@section('modal-body')
    
    <div class="form-group">
        <label  title="Descrição do Index">Usuário:</label>
        <input type="text" class="form-control input-medio" ng-model="vm.Index.NOVO.USUARIO">
    </div>

    <div class="form-group">
        <label  title="Descrição do Index">Nome:</label>
        <input type="text" class="form-control input-medio" ng-model="vm.Index.NOVO.NOME">
    </div>

    <div class="form-group">
        <label  title="Ativo ou inativo">Status:</label>
        <div class="input-group">
            <select name="repeatSelect" id="repeatSelect" ng-model="vm.Index.NOVO.STATUS">
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
        </div>
    </div>

    <br>
    <br>

    <ul  ng-if="vm.Index.ALTERANDO == true" class="list-inline">    
        <li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.IndexItens.atualizarMenusUser(vm.Index.NOVO)">
                <span class="glyphicon glyphicon-repeat"></span> Atualizar
            </button>
        </li>                       
        <li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-click="vm.IndexItens.resetarSenha(vm.Index.NOVO)">
                <span class="glyphicon glyphicon-asterisk"></span> Resetar Senha Web
            </button>
        </li>                   
    </ul>
    
    <div ng-if="vm.Index.ALTERANDO == true" class="table-ec table-scroll" style="height: 350px">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.IndexItens.ORDER_BY">
                    <th field="ID">ID</th> 
                    <th field="DESCRICAO">Descrição</th>
                    <th field="DESCRICAO">Visualizar</th>
                    <th field="DESCRICAO">Incluir</th>
                    <th field="DESCRICAO">Alterar</th>
                    <th field="DESCRICAO">Excluir</th>
                    <th field="DESCRICAO">Grupo</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.IndexItens.DADOS | orderBy:vm.IndexItens.ORDER_BY"
                    ng-click="vm.IndexItens.SELECTED = item"
                    ng-focus="vm.IndexItens.SELECTED = item"
                    ng-class="{ 'selected' : vm.IndexItens.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.IndexItens.modalAlterar(item)"
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
                    <td>
                        <span>
                            @{{item.DESC_VISUALIZAR}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESC_INCLUIR}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESC_ALTERAR}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESC_EXCLUIR}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.GRUPO}}
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