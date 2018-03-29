@extends('helper.include.view.modal', ['id' => 'modal-despesas', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Despesas
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
    
    <ul id="tab" class="nav nav-tabs" role="tablist"> 

        <li role="presentation" class="tab-detalhamento active">
            <a href="#tab4-container" id="tab4-tab" role="tab" data-toggle="tab" aria-controls="tab4-container" aria-expanded="true">
                Agrupamento por Conta
            </a>
        </li> 
        <li role="presentation" class="tab-detalhamento">
            <a href="#tab5-container" id="tab5-tab" role="tab" data-toggle="tab" aria-controls="tab5-container" aria-expanded="true">
                Agrupamento por C. Custo
            </a>
        </li>

    </ul>

    <div id="tab-content" class="tab-content" style="height: 75%; min-height: 300px;">

        <div  style="height: 100%;" role="tabpanel" class="tab-pane fade active in" id="tab4-container" aria-labelledby="tab4-tab">
            
            <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-despesa1','Despesa.csv')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para CSV
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-despesa1','Despesa.xls')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para XLS
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-despesa1','Despesa / Conta Contabil')">
                <span class="glyphicon glyphicon-print"></span> 
                Imprimir
            </button>

            <div class="table-ec" style="height: 95%; margin-top: 5px;" id="div-despesa1">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-despesa1">
                        <thead>
                            <tr style="background-color: #3479b7;">
                                <th></th>
                                <th title="Conta" ng-click="vm.Ficha.OrdemD1('ID')">
                                    <span style="display: inline-flex;">Conta
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'ID'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-ID'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Descrição da Conta" ng-click="vm.Ficha.OrdemD1('DESCRICAO')">
                                    <span style="display: inline-flex;">Descrição
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Valor da Conta" ng-click="vm.Ficha.OrdemD1('VALOR_CONTA')">
                                    <span style="display: inline-flex;">Valor Conta
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'VALOR_CONTA'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-VALOR_CONTA'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="% de Despesa da Conta" ng-click="vm.Ficha.OrdemD1('PERCENTUAL')">
                                    <span style="display: inline-flex;">%
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Despesa da Conta" ng-click="vm.Ficha.OrdemD1('VALOR')">
                                    <span style="display: inline-flex;">Despesa
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Despesa do Modelo" ng-click="vm.Ficha.OrdemD1('VALOR')">
                                    <span style="display: inline-flex;">Despesa do Modelo
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Despesa Total do Modelo" ng-click="vm.Ficha.OrdemD1('VALOR')">
                                    <span style="display: inline-flex;">Despesa Total
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa1 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat-start="item in vm.Item.Ficha.DESPESA1"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                style="background: aquamarine;" 
                                >
                                <td></td> 
                                <td auto-title colspan="2" >@{{item.ID}} - @{{item.DESCRICAO}}</td>
                                <td class="left-text" >R$ @{{item.VALOR_DESPESA | number:2}}</td>
                                <td class="left-text" >@{{(item.DESPESA / item.VALOR_DESPESA) * 100 | number:4}}</td>
                                <td class="left-text" >R$ @{{item.DESPESA       | number:2}}</td>
                                <td class="left-text" >R$ @{{item.DESPESAM      | number:6}}</td>
                                <td class="left-text" >R$ @{{item.DESPESAT      | number:6}}</td>
                                <td auto-title  class="noprint" ></td>
                            </tr>

                            <tr ng-repeat="iten in item.ITENS | orderBy:vm.Ficha.OrdemDespesa1"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                >
                                <td class="left-text" ><div ng-if="(iten.DESC_TIPO).trim() == 'PF'" class="valorpf"></div></td> 
                                <td auto-title >@{{iten.MASK}}</td> 
                                <td auto-title >@{{iten.DESCRICAO}}</td> 
                                <td class="left-text" >R$ @{{iten.VALOR_DESPESA | number:2}}</td>                     
                                <td class="left-text" >@{{iten.PERCENTUAL       | number:4}}</td>
                                <td class="left-text" >R$ @{{iten.VALOR         | number:2}}</td>
                                <td class="left-text" >R$ @{{iten.CUSTO_TOTAL1  | number:6}}</td>
                                <td class="left-text" >R$ @{{iten.CUSTO_TOTAL2  | number:6}}</td>
                                <td auto-title  class="noprint" >
                                    <button type="button" ng-if="iten.CCUSTO != null" class="btn btn-xs btn-primary " ng-click="vm.Item.DetalharDespesa(iten,1)">Detalhar</button>
                                </td>
                            </tr>

                            <tr ng-repeat-end=""
                                ng-if="false"
                                >
                            </tr>                            

                            <tr tabindex="-1"     
                                class="tr-fixed-1"
                                style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                                >
                                <td auto-title ></td>
                                <td auto-title >TOTAL</td> 
                                <td auto-title ><span title="Tempo Disponível">Tmp.: @{{(vm.Item.Ficha.FATOR) | number:0}}</td>  
                                <td auto-title ><span title="% de Faturamento da Família">Fat.: @{{(vm.PERC_FATURAMENTO.VALOR * 100) | number:2}}%</td>                     
                                <td class="left-text" ></td>
                                <td class="left-text" >R$ @{{vm.Item.DESPESA              | number:2}}</td>
                                <td class="left-text" >R$ @{{vm.Item.Ficha.DESPESA_TOTAL1 | number:6}}</td>
                                <td class="left-text" >R$ @{{vm.Item.Ficha.DESPESA_TOTAL2 | number:6}}</td>
                                <td class="noprint" ></td>                              
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div  style="height: 100%;" role="tabpanel" class="tab-pane fade" id="tab5-container" aria-labelledby="tab5-tab">
            

                <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-despesa2','Despesa.csv')">
                    <span class="glyphicon glyphicon-save"></span> 
                    Exportar para CSV
                </button>

                <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-despesa2','Despesa.xls')">
                    <span class="glyphicon glyphicon-save"></span> 
                    Exportar para XLS
                </button>

                <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-despesa2','Despesa / C. Custo')">
                    <span class="glyphicon glyphicon-print"></span> 
                    Imprimir
                </button>

            <div class="table-ec" style="height: 95%; margin-top: 5px;">
                <div class="scroll-table" id="div-despesa2">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-despesa2">
                        <thead>
                            <tr style="background-color: #3479b7;">

                                <th title="Centro de Custo" ng-click="vm.Ficha.OrdemD2('ID')">
                                    <span style="display: inline-flex;">C. Custo
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'ID'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-ID'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Descrição do Centro de Custo" ng-click="vm.Ficha.OrdemD2('DESCRICAO')">
                                    <span style="display: inline-flex;">Descrição
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="% de despesa do Centro de Custo" ng-click="vm.Ficha.OrdemD2('PERCENTUAL')">
                                    <span style="display: inline-flex;">%
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-PERCENTUAL'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="despesa do Centro de Custo" ng-click="vm.Ficha.OrdemD2('VALOR')">
                                    <span style="display: inline-flex;">Despesa
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Despesa do Modelo" ng-click="vm.Ficha.OrdemD2('VALOR')">
                                    <span style="display: inline-flex;">Despesa M.
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Despesa Total do Pedido" ng-click="vm.Ficha.OrdemD2('VALOR')">
                                    <span style="display: inline-flex;">Despesa T.
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == 'VALOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemDespesa2 == '-VALOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat="iten in vm.Item.Ficha.DESPESA2 | orderBy:vm.Ficha.OrdemDespesa2"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                >
                                <td auto-title >@{{iten.ID}}</td>
                                <td auto-title >@{{iten.DESCRICAO}}</td>
                                <td class="left-text" >@{{iten.PERCENTUAL      | number:4}}</td>
                                <td class="left-text" >R$ @{{iten.VALOR        | number:2}}</td>
                                <td class="left-text" >R$ @{{iten.CUSTO_TOTAL1 | number:6}}</td>
                                <td class="left-text" >R$ @{{iten.CUSTO_TOTAL2 | number:6}}</td>

                                <td auto-title  class="noprint" ng-if="iten.ID == ''" >
                                    <button type="button" class="btn btn-xs btn-primary " ng-click="vm.Item.DetalharDespesa(iten,4)">Detalhar</button>
                                </td>

                                <td auto-title  class="noprint" ng-if="iten.TIPO != 6 && iten.ID != ''" >
                                    <button type="button" class="btn btn-xs btn-primary " ng-click="vm.Item.DetalharDespesa(iten,2)">Detalhar</button>
                                </td>

                                <td auto-title  class="noprint"  ng-if="iten.TIPO == 6 && iten.DESCRICAO.ID != ''" >
                                    <button type="button" class="btn btn-xs btn-primary " ng-click="vm.Item.DetalharDespesa(iten,3)">Detalhar</button>
                                </td>

                            </tr>

                            <tr tabindex="-1"     
                                class="tr-fixed-1"
                                style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                                >
                                <td auto-title >TOTAL</td> 
                                <td auto-title ><span title="Tempo Disponível">Tmp.: @{{(vm.Item.Ficha.FATOR) | number:0}}</span></td>  
                                <td auto-title ><span title="% de Faturamento da Família">Fat.: @{{(vm.PERC_FATURAMENTO.VALOR * 100) | number:2}}%</span></td>                      
                                <td class="left-text" >R$ @{{vm.Item.DESPESA              | number:2}}</td>
                                <td class="left-text" >R$ @{{vm.Item.Ficha.DESPESA_TOTAL1 | number:6}}</td>
                                <td class="left-text" >R$ @{{vm.Item.Ficha.DESPESA_TOTAL2 | number:6}}</td>
                                <td class="noprint" ></td>                                
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite