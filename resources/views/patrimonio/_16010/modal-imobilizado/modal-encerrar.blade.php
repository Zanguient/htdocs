@extends('helper.include.view.modal', ['id' => 'modal-imobilizado-encerrar'])

@section('modal-header-left')

<h4 class="modal-title">
    Encerramento de Depreciação
</h4>

@overwrite

@section('modal-header-right')

    <button type="button" ng-click="vm.ImobilizadoItem.encerrar()" class="btn btn-success" data-hotkey="alt+1">
      <span class="fa fa-thumbs-o-down"></span> Encerrar
    </button>

    <button type="button" ng-click="vm.NFS_SELECTED = {}" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')

<form class="form-inline" ng-submit="vm.ImobilizadoItem.importarDocumentoFiscalSaida()"  style="display: inline-block;">
    <div class="form-group">
        <div class="input-group">
            <input 
                type="text" 
                class="form-control"
                placeholder="Doc. Fiscal de Saída"
                ng-model="vm.ImobilizadoItem.NFS"
                form-validate="true"
                required
                ng-disabled="vm.Imobilizado.ALTERANDO">

            <button 
                type="submit" 
                class="input-group-addon btn-filtro" 
                tabindex="-1"
                ng-disabled="vm.Imobilizado.ALTERANDO"
                >
                <span class="glyphicon glyphicon-triangle-right"></span>
            </button>
        </div>

        <div ng-if="vm.NFSS.length > 1"
            style="
                display: block;
                z-index: 9999;
                padding: 1px;
                background-color: #3479b7;
                box-shadow: 0px 1px 3px #a90f0f;
                border-radius: 0 0 5px 5px;
                transition: 0.5s;
            "

            >

            <table class="table table-bordered table-hover table-striped table-condensed table-middle">
                <thead>
                    <tr>
                        <th>NFS</th>
                        <th>Série</th>
                        <th>Empresa</th>
                        <th>Emissão</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="itemEmp in vm.NFSS | orderBy : '-DATA_EMISSAO'" ng-click="vm.NFS_SELECTED = itemEmp; vm.ImobilizadoItem.NFS = ''; vm.NFSS = []" tabindex="0">
                        <td>@{{ itemEmp.NFS | lpad: [8,0] }}</td>
                        <td>@{{ itemEmp.SERIE | lpad: [3,0] }}</td>
                        <td>@{{ itemEmp.EMPRESA_ID | lpad : [4,0] }} - @{{ itemEmp.EMPRESA_RAZAOSOCIAL }}</td>
                        <td>@{{ itemEmp.DATA_EMISSAO_TEXT }}</td>
                    </tr>
                </tbody>

            </table> 
            <button ng-click="vm.NFSS = []" type="button" class="btn btn-danger btn-xs">
                <span class="glyphicon glyphicon-ban-circle"></span> Fechar
            </button>
        </div>

    </div>
</form>

<table class="table table-striped table-bordered table-condensed" ng-if="vm.NFS_SELECTED.NFS_ID > 0" style="margin-bottom: 10px">
    <tbody>
        <tr>
            <td>NFS:</td>
            <td>@{{ vm.NFS_SELECTED.NFS | lpad: [8,0] }}-@{{ vm.NFS_SELECTED.SERIE | lpad: [3,0] }}</td>
        </tr>
        <tr>
            <td>Empresa:</td>
            <td>@{{ vm.NFS_SELECTED.EMPRESA_ID | lpad : [4,0] }} - @{{ vm.NFS_SELECTED.EMPRESA_RAZAOSOCIAL }}</td>
        </tr>
        <tr>
            <td>Dt. Emissão:</td>
            <td><b>@{{ vm.NFS_SELECTED.DATA_EMISSAO_TEXT }}</b></td>
        </tr>
    </tbody>
</table>

<div class="alert alert-warning" style="margin-bottom: 10px;">
    Atenção: A data de encerramento dos itens será a partir da data de emissão do Documento Fiscal de Saída.
</div>

<div class="check-group" ng-init="vm.ImobilizadoItem.ENCERRAR_TIPO = '1'">
        <label class="lbl">
            <input 
                type="radio" 
                ng-click="vm.ImobilizadoItem.ENCERRAR_TIPO = '1'" 
                ng-checked="vm.ImobilizadoItem.ENCERRAR_TIPO == '1'">
            <span>Encerrar Imobilizado</span>
        </label>    
        <label class="lbl">
            <input 
                type="radio" 
                ng-click="vm.ImobilizadoItem.ENCERRAR_TIPO = '2'" 
                ng-checked="vm.ImobilizadoItem.ENCERRAR_TIPO == '2'">
            <span>Encerrar Itens</span>
        </label>    
</div>
    
    <div ng-if="vm.ImobilizadoItem.ENCERRAR_TIPO == 2" class="table-ec" style="max-height: calc(100vh - 160px);">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="">Id</th>
                    <th class="">NFE</th>
                    <th class="text-center">Seq.</th>
                    <th class="">Produto</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Imobilizado.SELECTED.ITENS | orderBy : ['NFE','NFE_SEQUENCIA','PRODUTO_DESCRICAO']"                    
                    ng-click="vm.ImobilizadoItem.encerrarPickToggle(item)"
                    ng-class="{ 'selected': vm.ImobilizadoItem.encerrarPicked(item) }"
                    >
                    <td>@{{ item.ID }}</td>
                    <td>@{{ item.NFE }}</td>
                    <td class="text-center">@{{ item.NFE_SEQUENCIA || 0 | lpad : [2,0] }}</td>
                    <td>@{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}</td>
                </tr>
            </tbody>
        </table>                                    
    </div>

@overwrite
