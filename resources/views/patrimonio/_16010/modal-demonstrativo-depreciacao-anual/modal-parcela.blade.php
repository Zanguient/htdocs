@extends('helper.include.view.modal', ['id' => 'modal-demonstrativo-depreciacao-anual-parcela', 'class_size' => 'modal-lg'])

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento das Percelas
</h4>

@overwrite

@section('modal-header-right')

    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
    <div class="table-ec" style="max-height: calc(100vh - 160px);">
        <table class="table table-striped table-bordered">
            <thead>
                <tr ng-init="vm.DemonstrativoDepreciacaoAnual.ORDER_BY = 'PARCELA'" gc-order-by="vm.DemonstrativoDepreciacaoAnual.ORDER_BY">
                    <th field="IMOBILIZADO_ID*1">Imobilizado</th>
                    <th field="DEPRECIACAO_INICIO" class="text-center">Depreciação Inicio</th>
                    <th field="DEPRECIACAO_FIM" class="text-center">Depreciação Fim</th>
                    <th field="PARCELA*1" class="text-center">Parcela</th>
                    <th field="VALOR*1" class="text-right">Valor Parcela</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="imobilizado in vm.DemonstrativoDepreciacaoAnual.SELECTEDS | orderBy : vm.DemonstrativoDepreciacaoAnual.ORDER_BY">
                    <td> <a href ng-click="vm.Imobilizado.visualizar(imobilizado.IMOBILIZADO_ID)">@{{ imobilizado.IMOBILIZADO_ID | lpad: [4,0] }} - @{{ imobilizado.IMOBILIZADO_DESCRICAO }}</a></td>
                    <td class="text-center">@{{ imobilizado.DEPRECIACAO_INICIO_TEXT }}</td>
                    <td class="text-center">@{{ imobilizado.DEPRECIACAO_FIM_TEXT }}</td>
                    <td class="text-center">@{{ imobilizado.PARCELA | lpad: [3,0] }}/@{{ imobilizado.PARCELAS | lpad: [3,0] }}</td>                    
                    <td class="text-right">R$ @{{ imobilizado.VALOR | number: 2 }}</td>
                </tr>
            </tbody>
        </table>                                    
    </div>

@overwrite
