@extends('helper.include.view.modal', ['id' => 'modal-cota-ggf-detalhe', 'class_size' => 'modal-big'])

@section('modal-start')
    <form class="form-inline">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento das Transações de Estoque
</h4>

@overwrite

@section('modal-header-right')

    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')

    <div class="form-group">
        <label for="descricao">C. Custo:</label>
        <input type="text" class="form-control" readonly="" value="@{{ vm.Cota.SELECTED.CCUSTO }} - @{{ vm.Cota.SELECTED.CCUSTO_DESCRICAO }}">
    </div>

    <div class="form-group">
        <label for="descricao">Família:</label>
        <input type="text" class="form-control input-maior" readonly="" value="@{{ vm.CotaGgf.SELECTED.DESCRICAO }}">
    </div>

    <div class="form-group">
        <label for="descricao">Período:</label>
        <input type="text" class="form-control" readonly="" value="@{{ vm.Cota.SELECTED.PERIODO_DESCRICAO }}">
    </div>
    
    <div class="form-group">
        <label for="descricao" ttitle="Total utilizado">Total Utiliz.:</label>
        <input type="text" class="form-control input-medio-min text-right" readonly="" value="R$ @{{ vm.CotaGgf.SELECTED.VALOR_UTILIZADO | number : 2 }}">
    </div>

<div class="form-group" style="
    margin-top: 32px;
    margin-bottom: 15px;
"><div class="alert alert-danger" style="
    display: inline;
    padding: 7px;
    font-weight: bold;
    ">Atenção: Lançamentos realizados hoje, serão exibidos a partir de amanhã</div></div>
    
    <div class="table-ec" style="max-height: calc(100vh - 225px);">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th ttitle="Centro de custo">C. Custo</th>
                    <th ttitle="Data e hora da saída do produto do estoque">Data/Hora</th>
                    <th ttitle="Usuário que realizou a requisição">Usuário</th>
                    <th>Produto</th>
                    <th class="text-center" ttitle="Operação">Oper.</th>
                    <th class="text-right" ttitle="Quantidade">Qtd.</th>
                    <th class="text-right" ttitle="Custo unitário">C. Unit.</th>
                    <th class="text-right" ttitle="Quantidade x Custo Unitário">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in vm.CotaGgf.SELECTED.ITENS">
                    <td>
                        <span style="float: left; width: 65px;">@{{ item.CENTRO_DE_CUSTO }}</span>
                        @{{ item.CENTRO_DE_CUSTO_DESCRICAO }}</td>
                    <td>@{{ item.DATAHORA | toDate | date:'dd/MM/yy HH:mm:ss' }}</td>
                    <td>@{{ item.USUARIO_ID }} - @{{ item.USUARIO_DESCRICAO }}</td>
                    <td>
                        @{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}
                        <span 
                            ng-if="item.REQUISICAO_OBSERVACAO.trim() != ''" 
                            class="glyphicon glyphicon-info-sign operacao-descricao"
                            style="float: right;"
                            data-toggle="popover" 
                            data-placement="auto" 
                            title="Observação da Requisição"
                            data-element-content="#transacao-@{{ item.TRANSACAO_ID }}"></span>
                        <div 
                            ng-if="item.REQUISICAO_OBSERVACAO.trim() != ''" 
                            id="transacao-@{{ item.TRANSACAO_ID }}" 
                            style="display: none">
                            @{{ item.REQUISICAO_OBSERVACAO }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span ng-if="item.TIPO_BAIXA.length > 0" ttitle="@{{ item.TIPO_BAIXA_DESCRICAO }}">
                            <b ng-if="item.TIPO_BAIXA == 'A'" style="color:red">
                                @{{ item.TIPO_BAIXA }}
                            </b>
                            <i ng-if="item.TIPO_BAIXA != 'A'">
                                @{{ item.TIPO_BAIXA }}
                            </i>
                        -
                        </span>
                        <span ttitle="@{{ item.OPERACAO_DESCRICAO }}">
                            @{{ item.OPERACAO }}
                        </span>
                    </td>
                    <td class="text-right" ng-style="{'color' : item.QUANTIDADE < 0 ? 'blue' : 'initial' }">@{{ item.QUANTIDADE | number : 2 }}</td>
                    <td class="text-right">R$ @{{ item.CUSTO_UNITARIO | number : 2 }}</td>
                    <td class="text-right">R$ @{{ item.QUANTIDADE * item.CUSTO_UNITARIO | number : 2 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@overwrite

@section('modal-end')
    </form>
@overwrite