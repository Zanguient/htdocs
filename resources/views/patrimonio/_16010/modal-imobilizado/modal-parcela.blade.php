@extends('helper.include.view.modal', ['id' => 'modal-imobilizado-parcela', 'class_size' => 'modal-lg'])

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
                <tr>
                    <th class="text-center">Data</th>
                    <th class="text-right">Valor Parcela</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="parcela in vm.Imobilizado.PARCELAS">
                    <td class="text-center">@{{ parcela.DATA_TEXT }}</td>
                    <td class="text-right">R$ @{{ parcela.VALOR | number : 2 }}</td>                    
                    <td class="text-right">R$ @{{ parcela.SALDO | number : 2 }}</td>                    
                </tr>
            </tbody>
        </table>                                    
    </div>

@overwrite
