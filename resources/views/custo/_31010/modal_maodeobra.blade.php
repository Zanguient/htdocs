@extends('helper.include.view.modal', ['id' => 'modal-maodeobra', 'class_size' => 'modal-full'])

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
    
    <ul id="tab" class="nav nav-tabs" role="tablist"> 

        <li role="presentation" class="tab-detalhamento active">
            <a href="#tab1-container" id="tab1-tab" role="tab" data-toggle="tab" aria-controls="tab1-container" aria-expanded="true">
                Agrupamento por C. Custo
            </a>
        </li> 
        <li role="presentation" class="tab-detalhamento">
            <a href="#tab2-container" id="tab2-tab" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="true">
                Agrupamento por Cargo
            </a>
        </li> 
        <li role="presentation" class="tab-detalhamento">
            <a href="#tab3-container" id="tab3-tab" role="tab" data-toggle="tab" aria-controls="tab3-container" aria-expanded="true">
                Agrupamento por Colaborador
            </a>
        </li> 

    </ul>

    <div id="tab-content" class="tab-content" style="height: 80%; min-height: 300px;">

        <div  style="height: 100%;" role="tabpanel" class="tab-pane fade active in" id="tab1-container" aria-labelledby="tab1-tab">
            
            <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-mao-de-obra1','mao_de_obra.csv')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para CSV
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-mao-de-obra1','mao_de_obra.xls')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para XLS
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-mao-de-obra1','Mão de Obra / C. Custo')">
                <span class="glyphicon glyphicon-print"></span> 
                Imprimir
            </button>

            <div class="table-ec" style="height: 95%; margin-top: 5px;" id="div-mao-de-obra1">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-mao-de-obra1">
                        <thead>
                            <tr style="background-color: #3479b7;">

                                <th title="Centro de Custo" ng-click="vm.Ficha.OrdemMaoDeObra1('CCUSTO_DESCRICAO')">
                                    <span style="display: inline-flex;">C. CUSTO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == 'CCUSTO_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == '-CCUSTO_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th title="Colaborador" ng-click="vm.Ficha.OrdemMaoDeObra1('COLABORADOR')">
                                    <span style="display: inline-flex;">COLABORADOR
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == 'COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == '-COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Salário" ng-click="vm.Ficha.OrdemMaoDeObra1('SALARIO')">
                                    <span style="display: inline-flex;">SALÁRIO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == 'SALARIO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == '-SALARIO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional + Custo Setup" ng-click="vm.Ficha.OrdemMaoDeObra1('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONAL / SETUP 
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional Total" ng-click="vm.Ficha.OrdemMaoDeObra1('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONA T.
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo1 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat="iten in vm.Item.Ficha.MAO_DE_OBRA1 | orderBy:vm.Ficha.OrdemMdo1"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                >
                                <td auto-title >@{{iten.ID}} - @{{iten.CCUSTO_DESCRICAO}}</td>
                                <td class="left-text" auto-title >@{{iten.COLABORADOR | number:0}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{(iten.SALARIO) | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL1 | number:6}}</td>                        
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL2 | number:6}}</td>
                            </tr>

                            <tr tabindex="-1"     
                                class="tr-fixed-1"
                                style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                                >
                                <td auto-title >TOTAL</td>
                                <td auto-title ><span title="Quantidade de Colaboradores">Qtd.: @{{(vm.Item.Ficha.TOTAL_MAO_DE_OBRA.COLABORADORES) | number:0}}</span> / <span title="Tempo Disponível dos Colaboradores">Tmp.: @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.MINUTOS_DIA | number:0}}</span></td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{(vm.Item.Ficha.TOTAL_MAO_DE_OBRA.SALARIO) | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL1 | number:6}}</td> 
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL2 | number:6}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div  style="height: 100%;" role="tabpanel" class="tab-pane fade" id="tab2-container" aria-labelledby="tab2-tab">
            

                <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-mao-de-obra2','mao_de_obra.csv')">
                    <span class="glyphicon glyphicon-save"></span> 
                    Exportar para CSV
                </button>

                <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-mao-de-obra2','mao_de_obra.xls')">
                    <span class="glyphicon glyphicon-save"></span> 
                    Exportar para XLS
                </button>

                <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-mao-de-obra2','Mão de Obra / Cargo')">
                    <span class="glyphicon glyphicon-print"></span> 
                    Imprimir
                </button>

            <div class="table-ec" style="height: 95%; margin-top: 5px;">
                <div class="scroll-table" id="div-mao-de-obra2">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-mao-de-obra2">
                        <thead>
                            <tr style="background-color: #3479b7;">
                                <th title="Cargo" ng-click="vm.Ficha.OrdemMaoDeObra2('CARGO')">
                                    <span style="display: inline-flex;">CARGO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == 'CARGO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMd2o == '-CARGO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th title="Colaborador" ng-click="vm.Ficha.OrdemMaoDeObra2('COLABORADOR')">
                                    <span style="display: inline-flex;">COLABORADOR
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == 'COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == '-COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Salário" ng-click="vm.Ficha.OrdemMaoDeObra2('SALARIO')">
                                    <span style="display: inline-flex;">SALÁRIO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == 'SALARIO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == '-SALARIO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional + Custo Setup" ng-click="vm.Ficha.OrdemMaoDeObra2('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONAL / SETUP 
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional Total" ng-click="vm.Ficha.OrdemMaoDeObra2('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONA T.
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo2 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat="iten in vm.Item.Ficha.MAO_DE_OBRA2 | orderBy:vm.Ficha.OrdemMdo2"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                >
                                <td auto-title >@{{iten.CARGO}}</td>
                                <td class="left-text" auto-title > @{{iten.COLABORADOR | number:0}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{(iten.SALARIO) | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL1 | number:6}}</td>                        
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL2 | number:6}}</td>
                            </tr>

                            <tr tabindex="-1"     
                                class="tr-fixed-1"
                                style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                                >
                                <td auto-title >TOTAL</td>
                                <td auto-title ><span title="Quantidade de Colaboradores">Qtd.: @{{(vm.Item.Ficha.TOTAL_MAO_DE_OBRA.COLABORADORES) | number:0}}</span> / <span title="Tempo Disponível dos Colaboradores">Tmp.: @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.MINUTOS_DIA | number:0}}</span></td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.SALARIO      | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL1 | number:6}}</td> 
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL2 | number:6}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div role="tabpanel"  style="height: 100%;" class="tab-pane fade" id="tab3-container" aria-labelledby="tab3-tab">
            
            <button type="button" style="" class="btn btn-primary" ng-click="vm.export1('tabela-mao-de-obra3','mao_de_obra.csv')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para CSV
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.export2('tabela-mao-de-obra3','mao_de_obra.xls')">
                <span class="glyphicon glyphicon-save"></span> 
                Exportar para XLS
            </button>

            <button type="button" style="" class="btn btn-primary" ng-click="vm.Imprimir('div-mao-de-obra3','Mão de Obra / Colaborador')">
                <span class="glyphicon glyphicon-print"></span> 
                Imprimir
            </button>

            <div class="table-ec" style="height: 95%; margin-top: 5px;">
                <div class="scroll-table" id="div-mao-de-obra3">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo" id="tabela-mao-de-obra3">
                        <thead>
                            <tr style="background-color: #3479b7;">

                                <th title="Centro de Custo" ng-click="vm.Ficha.OrdemMaoDeObra3('CCUSTO_DESCRICAO')">
                                    <span style="display: inline-flex;">C. CUSTO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'CCUSTO_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-CCUSTO_DESCRICAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>

                                <th title="Cargo" ng-click="vm.Ficha.OrdemMaoDeObra3('CARGO')">
                                    <span style="display: inline-flex;">CARGO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'CARGO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-CARGO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th title="Colaborador" ng-click="vm.Ficha.OrdemMaoDeObra3('COLABORADOR')">
                                    <span style="display: inline-flex;">COLABORADOR
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-COLABORADOR'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th title="Data de Admissão" ng-click="vm.Ficha.OrdemMaoDeObra3('DATA_ADMISSAO')">
                                    <span style="display: inline-flex;">DT. ADMISSÃO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'DATA_ADMISSAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-DATA_ADMISSAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th title="Data de Demissão" ng-click="vm.Ficha.OrdemMaoDeObra3('DATA_DEMISSAO')">
                                    <span style="display: inline-flex;">DT. DEMISSÃO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'DATA_DEMISSAO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-DATA_DEMISSAO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Salário" ng-click="vm.Ficha.OrdemMaoDeObra3('SALARIO')">
                                    <span style="display: inline-flex;">SALÁRIO
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'SALARIO'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-SALARIO'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional + Custo Setup" ng-click="vm.Ficha.OrdemMaoDeObra3('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONAL / SETUP 
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                                <th class="left-text" title="Custo Opeacional Total" ng-click="vm.Ficha.OrdemMaoDeObra3('CUSTO_TOTAL1')">
                                    <span style="display: inline-flex;">C. OPERACIONA T.
                                        <span style="margin-left: 5px ;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == 'CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes"></span>
                                        <span style="margin-left: 5px; float: right;margin-right: -5px;" ng-if="vm.Ficha.OrdemMdo3 == '-CUSTO_TOTAL1'" class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat="iten in vm.Item.Ficha.MAO_DE_OBRA3 | orderBy:vm.Ficha.OrdemMdo3"
                                tabindex="-1"     
                                class="tr-fixed-1"
                                >
                                <td auto-title >@{{iten.ID2}} - @{{iten.CCUSTO_DESCRICAO}}</td>
                                <td auto-title >@{{iten.CARGO}}</td>
                                <td auto-title >@{{iten.ID}} - @{{iten.COLABORADOR}}</td>
                                <td auto-title >@{{iten.DATA_ADMISSAO | date:'dd/MM/yyyy'}}</td>
                                <td auto-title >@{{iten.DATA_DEMISSAO | date:'dd/MM/yyyy'}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.SALARIO      | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL1 | number:6}}</td>                        
                                <td class="left-text" auto-title class="text-ringt">R$ @{{iten.CUSTO_TOTAL2 | number:6}}</td>
                            </tr>

                            <tr tabindex="-1"     
                                class="tr-fixed-1"
                                style="font-weight: bold; font-size: 14px; background-color: aliceblue;"
                                >
                                <td auto-title >TOTAL</td>
                                <td auto-title ></td>
                                <td auto-title ><span title="Quantidade de Colaboradores">Qtd.: @{{(vm.Item.Ficha.TOTAL_MAO_DE_OBRA.COLABORADORES) | number:0}}</span></td>
                                <td auto-title ><span title="Tempo Disponível dos Colaboradores">Tmp.: @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.MINUTOS_DIA | number:0}}</span></td>
                                <td auto-title ></td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.SALARIO      | number:2}}</td>
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL1 | number:6}}</td> 
                                <td class="left-text" auto-title class="text-ringt">R$ @{{vm.Item.Ficha.TOTAL_MAO_DE_OBRA.CUSTO_TOTAL2 | number:6}}</td>
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