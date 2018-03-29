@extends('helper.include.view.modal', ['id' => 'modal-detalhar-despesa', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Detalhamento de Despesas - @{{vm.Item.DESPESA_ITEM.DESCRICAO}}
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default  btn-cancelar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Voltar
    </button>
@overwrite

@section('modal-body')

    @include('custo._31010.info_detalhamento')
    
    <br>

    <button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.export1('tabela-despesa3','Despesa.csv')">
        <span class="glyphicon glyphicon-save"></span> 
        Exportar para CSV
    </button>

    <button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.export2('tabela-despesa3','Despesa.xls')">
        <span class="glyphicon glyphicon-save"></span> 
        Exportar para XLS
    </button>

    <button type="button" style="margin: 1px;" class="btn btn-primary" ng-click="vm.Imprimir('div-despesa3','Detalhamento de Despesa / ' + vm.Item.DESPESA_ITEM.DESCRICAO)">
        <span class="glyphicon glyphicon-print"></span> 
        Imprimir
    </button>

    <div class="table-ec" style="height: 75%; margin-top: 5px;" id="div-despesa3">
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-despesa3">
                <thead>
                    <tr style="background-color: #3479b7;">
                        <th>
                        </th>
                        <th title="Origem" ng-click="vm.Ficha.OrdemD3('ORIGEM')">
                            <span style="display: inline-flex;">Origem
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>

                        <th title="Descrição da Origem" ng-click="vm.Ficha.OrdemD3('DESCRICAO')">
                            <span style="display: inline-flex;">Descrição
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == 'DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == '-DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>

                        <th title="% do total da origem" ng-click="vm.Ficha.OrdemD3('PERCENTUAL')">
                            <span style="display: inline-flex;">%
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == 'PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == '-PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>

                        <th title="Valor" ng-click="vm.Ficha.OrdemD3('VALOR')">
                            <span style="display: inline-flex;">Valor
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa3 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr ng-repeat="iten in vm.Item.DESPESA_DETALHE | orderBy:vm.Ficha.OrdemDespesa3"
                        tabindex="-1"     
                        class="tr-fixed-1"
                        >
                        <td class="left-text" >
                            <div ng-if="iten.TIPO == 4" class="valorpf"></div>
                        </td> 
                        <td >@{{iten.ORIGEM}}</td> 
                        <td auto-title >@{{iten.DESCRICAO}}</td>                      
                        <td class="left-text" >@{{iten.PERCENTUAL | number:6}}</td>
                        <td class="left-text" >R$ @{{iten.VALOR | number:2}}</td>
                    </tr>

                    <tr tabindex="-1"     
                        class="tr-fixed-1"
                        style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                        >
                        <td auto-title ></td> 
                        <td auto-title >TOTAL</td> 
                        <td ></td>   
                        <td class="left-text" >@{{vm.Item.DESPESA_ITEM.PERCENTUAL | number:2}}</td>                      
                        <td class="left-text" >R$ @{{vm.Item.DESPESA_ITEM.VALOR   | number:2}}</td>                        
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite