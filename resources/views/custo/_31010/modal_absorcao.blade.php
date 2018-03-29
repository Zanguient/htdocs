@extends('helper.include.view.modal', ['id' => 'modal-absorcao', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Absorvido

        <span  ng-repeat="item in vm.Ficha.ItensAbsorvidos">
           <span ng-if="item.BASE == 0"> => </span>
           <span ng-if="item.BASE == 0" ng-click="vm.Ficha.DetalharAbsorcao2(item,1)" style="cursor: pointer; text-decoration: underline; color: blue;">@{{item.ORIGEM_DESCRICAO}}</span>
           <span ng-if="item.BASE == 1" ng-click="vm.Ficha.ConsultarAbsorcao2(09)" style="cursor: pointer; text-decoration: underline; color: blue;">@{{item.ORIGEM_DESCRICAO}}</span>
        </span>

	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default  btn-cancelar" ng-click="vm.Ficha.voltarItem()" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Voltar
    </button>
@overwrite

@section('modal-body')
    
    @include('custo._31010.info_detalhamento')

    <br>
    
        <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-absorvido','Custo_Absorvido.csv')">
            <span class="glyphicon glyphicon-save"></span> 
            Exportar para CSV
        </button>

        <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-absorvido','Custo_Absorvido.xls')">
            <span class="glyphicon glyphicon-save"></span> 
            Exportar para XLS
        </button>

        <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-absorvido','Custo Absorvido - ' + vm.Ficha.ItensAbsorvidos[vm.Ficha.ItensAbsorvidos.length - 1].ORIGEM_DESCRICAO)">
            <span class="glyphicon glyphicon-print"></span> 
            Imprimir
        </button>

    <div class="table-ec"  style="height: 80%; min-height: 300px; margin-top: 5px;">

        <div class="scroll-table" id="div-absorvido" style="">
            <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-absorvido">
                <thead>
                    <tr  style="background-color: #3479b7;">

                        <th title="" ng-click="vm.Ficha.OrdemAbsorvido('ORIGEM')">
                            <span style="display: inline-flex;">Origem
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'ORIGEM'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-ORIGEM'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th title="" ng-click="vm.Ficha.OrdemAbsorvido('ORIGEM_DESCRICAO')">
                            <span style="display: inline-flex;">Descrição
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'ORIGEM_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-ORIGEM_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="" ng-click="vm.Ficha.OrdemAbsorvido('ABRANGENCIA')">
                            <span style="display: inline-flex;">Abrangência
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'ABRANGENCIA'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-ABRANGENCIA'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="" ng-click="vm.Ficha.OrdemAbsorvido('RATEAMENTO')">
                            <span style="display: inline-flex;">%
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'RATEAMENTO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-RATEAMENTO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" title="" ng-click="vm.Ficha.OrdemAbsorvido('VALOR')">
                            <span style="display: inline-flex;">Rateado
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" title="Custo Absorvido" ng-click="vm.Ficha.OrdemAbsorvido('CUSTO_ABSORVIDO')">
                            <span style="display: inline-flex;">C. Absorvido
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'CUSTO_ABSORVIDO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-CUSTO_ABSORVIDO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th class="left-text" ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" title="Custo Absorvido Total" ng-click="vm.Ficha.OrdemAbsorvido('CUSTO_ABSORVIDOT')">
                            <span style="display: inline-flex;">C. Absorvido T.
                                <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == 'CUSTO_ABSORVIDOT'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemAbs == '-CUSTO_ABSORVIDOT'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                            </span>
                        </th>
                        <th title="" class="noprint"></th>

                    </tr>
                </thead>
                <tbody>

                    <tr ng-repeat="iten in vm.Ficha.DADOS_ABSORCAO | orderBy:vm.Ficha.OrdemAbs"
                        tabindex="-1"     
                        class="tr-fixed-1"
                        ng-click=""
                        >
                        <td auto-title >@{{iten.ORIGEM}}</td>
                        <td auto-title >@{{iten.ORIGEM_DESCRICAO}}</td>

                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" >@{{iten.ABRANGENCIA}}</td>
                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG != 1" >@{{0}}</td> 

                        <td class="left-text" auto-title >@{{(iten.RATEAMENTO * 100 | number:4)}}</td>
                        
                        <td class="left-text" auto-title class="text-ringt">R$ @{{iten.VALOR | number:2}}</td>
                        
                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" class="text-ringt">R$ @{{iten.CUSTO_ABSORVIDO  | number:5}}</td> 
                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" class="text-ringt">R$ @{{iten.CUSTO_ABSORVIDOT | number:5}}</td> 
                        
                        <td auto-title  class="noprint" >
                            <button type="button" ng-if="iten.ABRANGENCIA > -1" class="btn btn-xs btn-primary " ng-click="vm.Ficha.DetalharAbsorcao(iten,1)">Detalhar</button>
                        </td>
                    </tr>

                    <tr 
                        tabindex="-1"     
                        class="tr-fixed-1"
                        style="background-color: aliceblue; font-weight: bold; font-size: 14px;"
                        >
                        <td auto-title >Total</td>
                        <td auto-title >@{{(vm.Ficha.TOTALAbs.RATEAMENTO * 100 | number:2)}}% de Rateamento</td>
                        <td class="left-text" auto-title ><span title="Tempo Disponível">Tmp.: @{{(vm.Item.Ficha.FATOR) | number:0}}</span></td>
                        <td class="left-text" auto-title ></td>

                        <td class="left-text" auto-title class="text-ringt">R$ @{{(vm.Ficha.TOTALAbs.VALOR) | number:2}}</td>
                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" class="text-ringt">R$ @{{vm.Ficha.TOTALAbs.CUSTO  | number:5}}</td>
                        <td class="left-text" auto-title ng-if="vm.Ficha.DADOS_ABSORCAO.FLAG == 1" class="text-ringt">R$ @{{vm.Ficha.TOTALAbs.CUSTOT | number:5}}</td>

                        
                        <td auto-title  class="noprint" ></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>


@overwrite

@section('modal-end')
    </form>
@overwrite