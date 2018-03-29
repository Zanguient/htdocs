@extends('helper.include.view.modal', ['id' => 'modal-proprio', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Próprio
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

    <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-proprio','Custo_Proprio.csv')">
        <span class="glyphicon glyphicon-save"></span> 
        Exportar para CSV
    </button>

    <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-proprio','Custo_Proprio.xls')">
        <span class="glyphicon glyphicon-save"></span> 
        Exportar para XLS
    </button>

    <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-proprio','Custo Proprio')">
        <span class="glyphicon glyphicon-print"></span> 
        Imprimir
    </button>

    <div class="table-ec"  style="height: 80%; min-height: 300px; margin-top: 5px; ">

        <div class="scroll-table" id="div-proprio" style="">
            <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-proprio">
                <thead>
                    <tr  style="background-color: #3479b7;">

                        <th title="" ng-click="vm.Ficha.OrdemProprio('ORIGEM')">
                            <span style="display: inline-flex;">Origem
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th title="" ng-click="vm.Ficha.OrdemProprio('ORIGEM_DESCRICAO')">
                            <span style="display: inline-flex;">Descrição
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'ORIGEM_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-ORIGEM_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="% Recebido Do Total" ng-click="vm.Ficha.OrdemProprio('RATEAMENTO')">
                            <span style="display: inline-flex;">%
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'RATEAMENTO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-RATEAMENTO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="Recebido Das Contas Para o C. Custo" ng-click="vm.Ficha.OrdemProprio('VALOR')">
                            <span style="display: inline-flex;">Rateado
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="Custo Indireto Proprio" ng-click="vm.Ficha.OrdemProprio('CUSTO_PROPRIO')">
                            <span style="display: inline-flex;">C. Indireto
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'CUSTO_PROPRIO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-CUSTO_PROPRIO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="Custo Indireto Proprio Total" ng-click="vm.Ficha.OrdemProprio('CUSTO_PROPRIOT')">
                            <span style="display: inline-flex;">C. Ind. Total
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == 'CUSTO_PROPRIOT'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemPro == '-CUSTO_PROPRIOT'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr ng-repeat="iten in vm.Ficha.DADOS_PROPRIO | orderBy:vm.Ficha.OrdemPro"
                        tabindex="-1"     
                        class="tr-fixed-1"
                        >
                        <td auto-title >@{{iten.ORIGEM}}</td>
                        <td auto-title >@{{iten.ORIGEM_DESCRICAO}}</td>
                        <td class="left-text" auto-title class="text-ringt">@{{iten.RATEAMENTO * 100  | number:4}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{iten.VALOR          | number:5}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_PROPRIO  | number:5}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_PROPRIOT | number:5}}</td>
                        
                    </tr>

                    <tr tabindex="-1"     
                        class="tr-fixed-1"
                        style="font-weight: bold; font-size: 14px; background-color: aliceblue;" 
                        >
                        <td auto-title >TOTAL</td>
                        <td auto-title ><span title="Tempo Disponível">Tmp.: @{{(vm.Item.Ficha.FATOR) | number:0}}</span></td>
                        <td class="left-text" auto-title class="text-ringt">@{{iten.RATEAMENTO * 100       | number:4}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Ficha.TOTALProp.VALOR | number:5}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Ficha.TOTALProp.CUSTO | number:5}}</td>
                        <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Ficha.TOTALProp.CUSTOT| number:5}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>


@overwrite

@section('modal-end')
    </form>
@overwrite