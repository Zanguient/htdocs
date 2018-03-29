@extends('helper.include.view.modal', ['id' => 'modal-reposicao-origem', 'class_size' => 'modal-lg'])

@section('modal-header-left')

	<h4 class="modal-title">
		Origem da Reposição
	</h4>

@overwrite

@section('modal-header-right')

    <button ng-click="vm.Reposicao.DETALHAMENTO = []" type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc" tabindex="-1">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>

@overwrite

@section('modal-body')
<div class="table-ec">
    <table class="table table-bordered table-hover table-striped table-middle table-no-break">
        <thead>
            <tr>
                <th ttitle="Localização de Estoque">Loc.</th>
                <th class="text-right" ttitle="Quantidade em Pedidos<br/>Acrescenta na quantidade a repor">Qtd. Ped. (+)</th>
                <th class="text-right" ttitle="Quantidade Empenhada; Quantidade em consumo de remessas<br/>Acrescenta na quantidade a repor">Qtd. Emp. (+)</th>
                <th class="text-right" ttitle="Quantidade a repor do Estoque Mínimo<br/>Acrescenta a repor">Qtd. Rep. (+)</th>
                <th class="text-right" ttitle="Quantidade em Produção<br/>Subtrai da quantidade a repor">Qtd. Prod. (-)</th>
                <th class="text-right" ttitle="Quantidade em Estoque<br/>Subtrai da quantidade a repor">Qtd. Est. (-)</th>
                <th class="text-right" ttitle="Quantidade necessária a repor<br/><b>Esta quantidade será arredondada para o múltiplo da quantidade do talão ao programar este produto</b>">Qtd. Nec.</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="item in vm.Reposicao.DADOS">
                <td>@{{ item.LOCALIZACAO_ID }}</td>
                <td class="text-right"><a href ng-click="vm.Reposicao.consultarDetalhamento('pedido')">@{{ item.PEDIDO      | number : 4 }}</a></td>
                <td class="text-right"><a href ng-click="vm.Reposicao.consultarDetalhamento('empenhado')">@{{ item.EMPENHADO   | number : 4 }}</a></td>
                <td class="text-right">@{{ item.QTD_REP     | number : 4 }}</td>
                <td class="text-right"><a href ng-click="vm.Reposicao.consultarDetalhamento('producao')">@{{ item.EM_PRODUCAO | number : 4 }}</a></td>
                <td class="text-right">@{{ item.ESTOQUE     | number : 4 }}</td>
                <td class="text-right">@{{ item.NECESSIDADE | number : 4 }}</td>
            </tr>
        </tbody>
    </table>
</div>
<fieldset ng-if="vm.Reposicao.DETALHAMENTO.length > 0">
    <legend>Detalhamento para @{{ vm.Reposicao.ORIGEM_TIPO | uppercase }}</legend>
    <div class="table-ec">
        <table class="table table-bordered table-hover table-striped table-middle table-no-break">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th class="text-right">Qtd. Nec.</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in vm.Reposicao.DETALHAMENTO">
                    <td>@{{ item.DESCRICAO }}</td>
                    <td class="text-right">@{{ item.QUANTIDADE | number : 4 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</fieldset>
@overwrite