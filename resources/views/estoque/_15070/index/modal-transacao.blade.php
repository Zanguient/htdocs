@extends('helper.include.view.modal', ['id' => 'modal-transacao'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Consumo.ModalPeca.confirm()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Transações Realizadas
	</h4>

@overwrite

@section('modal-header-right')

    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>

@overwrite

@section('modal-body')
    
<fieldset ng-if="vm.Consumo.ModalTransacao.DADOS.AVULSA.length > 0">
    <legend>
        Transações Avulsas
    </legend>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Talão</th>
                <th class="text-right">Qtd.</th>
                <th class="text-center" title="Data da transação de estoque">Dt. Est.</th>
                <th class="text-center" title="Data e hora da operação">Dt./Hr.</th>
                <th class="text-center" title="Ações">Ações</th>
                <th class="text-center" title="Status da Conferencia">Stts</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="transacao in vm.Consumo.ModalTransacao.DADOS.AVULSA">
                <td>@{{ transacao.REMESSA_TALAO_ID }}</td>
                <td class="text-right um">@{{ transacao.QUANTIDADE | number : 4 }} @{{ transacao.UM }}</td>
                <td class="text-center">@{{ transacao.DATA_TEXT }}</td>
                <td class="text-center">@{{ transacao.DATAHORA_TEXT }}</td>
                <td class="text-center">
                    <button 
                        type="button" 
                        class="btn btn-danger btn-xs" 
                        title="Excluir Transação"
                        ng-disabled="transacao.CONFERENCIA == 2"
                        ng-click="vm.Consumo.ModalTransacao.excluirTransacao(transacao)"
                        >
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
                <td class="text-center"><b>@{{ transacao.CONFERENCIA_DESCRICAO }}</b></td>
            </tr>
        </tbody>
    </table>
</fieldset>
    
<fieldset ng-if="vm.Consumo.ModalTransacao.DADOS.PECA.length > 0">
    <legend>
        Transações por Peça
    </legend>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Talão</th>
                <th class="text-right">Qtd.</th>
                <th class="text-center" title="Data da transação de estoque">Dt. Est.</th>
                <th class="text-center" title="Data e hora da operação">Dt./Hr.</th>
                <th class="text-center" title="Ações">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="transacao in vm.Consumo.ModalTransacao.DADOS.PECA">
                <td>@{{ transacao.REMESSA_TALAO_ID }}</td>
                <td class="text-right um">@{{ transacao.QUANTIDADE | number : 4 }} @{{ transacao.UM }}</td>
                <td class="text-center">@{{ transacao.DATA_TEXT }}</td>
                <td class="text-center">@{{ transacao.DATAHORA_TEXT }}</td>
                <td class="text-center">
                    <button 
                        type="button" 
                        class="btn btn-danger btn-xs" 
                        title="Excluir Transação"
                        ng-disabled="transacao.STATUS == 1"
                        ng-click="vm.Consumo.ModalTransacao.excluirTransacao(transacao)"
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