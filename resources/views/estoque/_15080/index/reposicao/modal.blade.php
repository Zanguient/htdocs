@extends('helper.include.view.modal', ['id' => 'modal-reposicao', 'class_size'=> 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Reposicao.confirm()" form-validade="true">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Registrar Reposição de Estoque
	</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.Reposicao.SELECTED.ESTOQUE_NECESSIDADE > 0 && vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO > 0" type="submit" class="btn btn-success btn-confirmar" id="btn-confirmar-reg-componente" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>

@overwrite

@section('modal-body')

<table class="table table-striped table-condensed table-bordered">
    <tbody style="
        font-size: 16px;
    ">
        <tr>
            <td>Produto:</td>
            <td colspan="2">@{{ vm.Reposicao.SELECTED.PRODUTO_ID }} - @{{ vm.Reposicao.SELECTED.PRODUTO_DESCRICAO }}</td>
        </tr>
        <tr>
            <td title="Tamanho">Tam:</td>
            <td colspan="2">@{{ vm.Reposicao.SELECTED.TAMANHO_DESCRICAO }}</td>
        </tr>
        <tr>
            <td title="Localização de Saída">Loc. Saída</td>
            <td>@{{ vm.Reposicao.SELECTED.PRODUTO_LOCALIZACAO_ID }} - @{{ vm.Reposicao.SELECTED.PRODUTO_LOCALIZACAO_DESCRICAO }}</td>
            <td class="text-right um">@{{ vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO | number: 4 }} @{{ vm.Reposicao.SELECTED.UM }}</td>
        </tr>
        <tr>
            <td title="Localização de Entrada">Loc. Entrada</td>
            <td>@{{ vm.Reposicao.SELECTED.LOCALIZACAO_ID }} - @{{ vm.Reposicao.SELECTED.LOCALIZACAO_DESCRICAO }}</td>
            <td class="text-right um">@{{ vm.Reposicao.SELECTED.ESTOQUE_FISICO | number: 4 }} @{{ vm.Reposicao.SELECTED.UM }}</td>
        </tr>
        <tr>
            <td colspan="2">Saldo a repor:</td>
            <td class="text-right um"><b>@{{ vm.Reposicao.SELECTED.ESTOQUE_NECESSIDADE | number: 4 }} @{{ vm.Reposicao.SELECTED.UM }}</b></td>
        </tr>
    </tbody>
</table>

<div 
    style="display: inline-block;width: 100%;margin-top: 10px;"
    ng-if="vm.Reposicao.SELECTED.ESTOQUE_NECESSIDADE > 0 && vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO > 0"
    >
    <div style="float: left; width: 50%;">
        <div class="form-group">
            <label class="esconder">Quantidade:</label>
            <input 
                type="number" 
                step="0.0001"
                min="0.0001"
                max="@{{ vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO }}"
                class="form-control input-quantidade" 
                autocomplete="off"
                placeholder="Informe o valor"
                string-to-number
                ng-model="vm.Reposicao.QUANTIDADE"
                ng-focus="vm.Reposicao.Modal.inputQuantidade().prop('required',true); vm.Reposicao.Modal.inputPeca().prop('disabled',true); vm.Reposicao.Modal.inputPeca().prop('required',false); vm.Reposicao.PECA_BARRAS = ''"
                ng-blur="vm.Reposicao.Modal.inputPeca().prop('disabled',false);"
                required
                form-validade="true"/>
<!--            <input 
                type="number" 
                step="0.0001"
                min="0.0001"
                max="@{{ vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO < vm.Reposicao.SELECTED.ESTOQUE_NECESSIDADE ? vm.Reposicao.SELECTED.PRODUTO_ESTOQUE_FISICO : vm.Reposicao.SELECTED.ESTOQUE_NECESSIDADE }}"
                class="form-control input-quantidade" 
                autocomplete="off"
                placeholder="Informe o valor"
                string-to-number
                ng-model="vm.Reposicao.QUANTIDADE"
                ng-focus="vm.Reposicao.Modal.inputQuantidade().prop('required',true); vm.Reposicao.Modal.inputPeca().prop('disabled',true); vm.Reposicao.Modal.inputPeca().prop('required',false); vm.Reposicao.PECA_BARRAS = ''"
                ng-blur="vm.Reposicao.Modal.inputPeca().prop('disabled',false);"
                required
                form-validade="true"/>-->
        </div>
    </div>
    <div style="float: left; width: 50%;">
        <input type="text" style="display:none">
        <input type="password" style="display:none">	
        <div class="form-group">
            <label class="esconder">Peça:</label>
            <input 
                type="password" 
                class="form-control input-peca" 
                placeholder="Informe o código de barras da peça"
                ng-model="vm.Reposicao.PECA_BARRAS"
                ng-focus="vm.Reposicao.Modal.inputPeca().prop('required',true); vm.Reposicao.Modal.inputQuantidade().prop('disabled',true); vm.Reposicao.Modal.inputQuantidade().prop('required',false); vm.Reposicao.QUANTIDADE = null"
                ng-blur="vm.Reposicao.Modal.inputQuantidade().prop('disabled',false);"       
                required
                pattern=".{12,13}"
                form-validade="true"
                autocomplete="new-password"
            />
        </div>
    </div>
</div>
<fieldset style="margin-top: 10px;" ng-if="vm.Reposicao.DADOS.length > 0">
    <legend>
        10 Últimas Transações
    </legend>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th title="Id do detalhamento do lote">Id Det.</th>
                <th>Tipo</th>
                <th class="text-right">Qtd.</th>
                <th class="text-center" title="Data e hora da operação">Dt./Hr.</th>
                <th>Usuário</th>
                <th class="text-center" title="Ações">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="transacao in vm.Reposicao.DADOS | orderBy: '-DATAHORA'">
                <td>@{{ transacao.KANBAN_LOTE_DETALHE_ID }}</td>
                <td>@{{ transacao.TIPO }}</td>
                <td class="text-right um">@{{ transacao.QUANTIDADE | number : 4 }} @{{ transacao.UM }}</td>
                <td class="text-center">@{{ transacao.DATAHORA_TEXT }}</td>
                <td>@{{ transacao.USUARIO_DESCRICAO }}</td>
                <td class="text-center">
                    <button 
                        type="button" 
                        class="btn btn-danger btn-xs" 
                        title="Excluir Transação"
                        ng-disabled="transacao.STATUS == 1"
                        ng-click="vm.Reposicao.deleteTransacao(transacao)"
                        >
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>   
</fieldset>

@overwrite

@section('modal-end')
    </form>
@overwrite