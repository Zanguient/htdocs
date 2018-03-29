@extends('helper.include.view.modal', ['id' => 'modal-demonstrativo-depreciacao-anual', 'class_size' => 'modal-full'])


@section('modal-header-left')

<h4 class="modal-title">
    Demonstrativo de Depreciação Anual
</h4>

@overwrite

@section('modal-header-right')

    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')

    <form class="form-inline" style="height: auto;">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
            
            @php $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro'])
            <div class="form-group">
                <label>Data Inicial:</label>
                <select ng-init="vm.DemonstrativoDepreciacaoAnual.MES_1 = '{{ date('n',strtotime('-2 Month')) }}'" ng-model="vm.DemonstrativoDepreciacaoAnual.MES_1" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.DemonstrativoDepreciacaoAnual.ANO_1 = '{{ date('Y',strtotime('-2 Month')) }}'" ng-model="vm.DemonstrativoDepreciacaoAnual.ANO_1" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Data Final:</label>
                <select ng-init="vm.DemonstrativoDepreciacaoAnual.MES_2 = '{{ date('n') }}'" ng-model="vm.DemonstrativoDepreciacaoAnual.MES_2" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.DemonstrativoDepreciacaoAnual.ANO_2 = '{{ date('Y') }}'" ng-model="vm.DemonstrativoDepreciacaoAnual.ANO_2" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>            
            

            <button ng-click="vm.DemonstrativoDepreciacaoAnual.consultar()" style="margin-right: 50px;" type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>            
            
            <div class="form-group">
                <label>Visão:</label>             
                <div class="check-group" ng-init="vm.DemonstrativoDepreciacaoAnual.VISAO = 'GERAL'">
                    <label class="lbl">
                        <input 
                            type="radio" 
                            ng-click="vm.DemonstrativoDepreciacaoAnual.VISAO = 'GERAL';"
                            ng-checked="vm.DemonstrativoDepreciacaoAnual.VISAO == 'GERAL'">
                        <span>Geral</span>
                    </label>
                    <label class="lbl">
                        <input 
                            type="radio" 
                            ng-click="vm.DemonstrativoDepreciacaoAnual.VISAO = 'CCUSTO'"
                            ng-checked="vm.DemonstrativoDepreciacaoAnual.VISAO == 'CCUSTO'">
                        <span>Centro de Custo</span>
                    </label>
                    <label class="lbl">
                        <input 
                            type="radio" 
                            ng-click="vm.DemonstrativoDepreciacaoAnual.VISAO = 'TIPO'"
                            ng-checked="vm.DemonstrativoDepreciacaoAnual.VISAO == 'TIPO'">
                        <span>Tipo</span>
                    </label>
                </div>
            </div>     
           

        </div>                
    </form>    
    
<div style="display: inline-block; width: 100%">
    <div style="float: right">
        <button type="button" style="margin: 1px;" class="btn btn-primary btn-xs" ng-click="vm.exportTableToCsv('table-demonstrativo','Demonstrativo-de-Depreciação-Anual.csv')">
            <span class="glyphicon glyphicon-save"></span> 
            Exportar para CSV
        </button>

        <button type="button" style="margin: 1px;" class="btn btn-primary btn-xs" ng-click="vm.exportTableToXls('table-demonstrativo','Demonstrativo-de-Depreciação-Anual.xls')">
            <span class="glyphicon glyphicon-save"></span> 
            Exportar para XLS
        </button>

        <button type="button" style="margin: 1px;" class="btn btn-primary btn-xs" ng-click="vm.exportTableToPrint('div-table-demonstrativo','Demonstrativo de Depreciação Anual')">
            <span class="glyphicon glyphicon-print"></span> 
            Imprimir
        </button>
    </div>
</div>

<div id="div-table-demonstrativo" style="height: calc(100vh - 230px);" class="table-ec table-scroll" ng-if="vm.DemonstrativoDepreciacaoAnual.VISAO == 'GERAL'">
    <table id="table-demonstrativo" class="table table-striped table-bordered table-middle">
        <thead>
            <tr>
                <th>Tipo</th>
                <th
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    @{{ mes.MES_DESCRICAO }}/@{{ mes.ANO }}
                </th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="tipo in vm.DemonstrativoDepreciacaoAnual.GERAL | orderBy : ['TIPO_DESCRICAO']">
                <td>@{{ tipo.TIPO_DESCRICAO }}</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    <a 
                        href 
                        ng-repeat="tipo_mes in tipo.MESES" 
                        ng-if="tipo_mes.MES == mes.MES && tipo_mes.ANO == mes.ANO"
                        data-toggle="modal" 
                        data-target="#modal-demonstrativo-depreciacao-anual-parcela"
                        ng-click="vm.DemonstrativoDepreciacaoAnual.SELECTEDS = tipo_mes.IMOBILIZADOS"
                        >
                        R$ @{{ tipo_mes.VALOR | number : 2 }}
                                            
                    </a>
                    
                </td>
                <td class="text-right" style="background: rgb(196, 209, 220);font-weight: bold;">R$ @{{ tipo.VALOR | number : 2 }}    </td>                
            </tr>
        </tbody>
        <tfoot>
            <tr style="background: rgb(174, 187, 197);font-weight: bold;">
                <td>Totalizador</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                        R$ @{{ mes.VALOR | number : 2 }}                    
                </td>
                <td class="text-right">
                    R$ @{{ vm.DemonstrativoDepreciacaoAnual.TOTAL_GERAL | number : 2 }}                    
                </td>
            </tr>  
        </tfoot>
    </table>
</div>


<div id="div-table-demonstrativo" style="height: calc(100vh - 230px);" class="table-ec table-scroll" ng-if="vm.DemonstrativoDepreciacaoAnual.VISAO == 'CCUSTO'">
    <table id="table-demonstrativo" class="table table-striped table-bordered table-middle">
        <thead>
            <tr>
                <th>C. Custo</th>
                <th
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    @{{ mes.MES_DESCRICAO }}/@{{ mes.ANO }}
                </th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr 
                ng-repeat-start="ccusto in vm.DemonstrativoDepreciacaoAnual.CCUSTOS | orderBy: ['CCUSTO_MASK']" 
                ng-click="ccusto.OPENED = ccusto.OPENED != true ? true : false"
                style="background: rgb(251, 242, 210);"
                >
                <td><span style="float: left; width: 65px;">@{{ ccusto.CCUSTO_MASK }}</span>@{{ ccusto.CCUSTO_DESCRICAO }}</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    <span 
                        ng-repeat="ccusto_mes in ccusto.MESES" 
                        ng-if="ccusto_mes.MES == mes.MES && ccusto_mes.ANO == mes.ANO"
                        >
                        R$ @{{ ccusto_mes.VALOR | number : 2 }}
                                            
                    </span>
                    
                </td>
                <td class="text-right" style="background: rgb(230, 222, 192);font-weight: bold;">R$ @{{ ccusto.VALOR | number : 2 }}    </td>                
            </tr>
            <tr 
                ng-repeat="tipo in ccusto.TIPOS | orderBy : ['TIPO_DESCRICAO']"
                ng-if="ccusto.OPENED"
                >
                <td>@{{ tipo.TIPO_DESCRICAO }}</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    <a 
                        href 
                        ng-repeat="tipo_mes in tipo.MESES" 
                        ng-if="tipo_mes.MES == mes.MES && tipo_mes.ANO == mes.ANO"
                        data-toggle="modal" 
                        data-target="#modal-demonstrativo-depreciacao-anual-parcela"
                        ng-click="vm.DemonstrativoDepreciacaoAnual.SELECTEDS = tipo_mes.IMOBILIZADOS"
                        >
                        R$ @{{ tipo_mes.VALOR | number : 2 }}
                                            
                    </a>
                    
                </td>
                <td class="text-right" style="background: rgb(196, 209, 220);font-weight: bold;">R$ @{{ tipo.VALOR | number : 2 }}    </td>
            </tr>
            <tr ng-repeat-end ng-if="false"></tr>
        </tbody>
        <tfoot>
            <tr style="background: rgb(174, 187, 197);font-weight: bold;">
                <td>Totalizador</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                        R$ @{{ mes.VALOR | number : 2 }}                    
                </td>
                <td class="text-right">
                    R$ @{{ vm.DemonstrativoDepreciacaoAnual.TOTAL_GERAL | number : 2 }}                    
                </td>
            </tr>  
        </tfoot>
    </table>
</div>

<div id="div-table-demonstrativo" style="height: calc(100vh - 230px);" class="table-ec table-scroll" ng-if="vm.DemonstrativoDepreciacaoAnual.VISAO == 'TIPO'">
    <table id="table-demonstrativo" class="table table-striped table-bordered table-middle">
        <thead>
            <tr>
                <th>Tipo</th>
                <th
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    @{{ mes.MES_DESCRICAO }}/@{{ mes.ANO }}
                </th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr 
                ng-repeat-start="tipo in vm.DemonstrativoDepreciacaoAnual.TIPOS | orderBy : ['TIPO_DESCRICAO']" 
                ng-click="tipo.OPENED = tipo.OPENED != true ? true : false"
                style="background: rgb(251, 242, 210);"
                >
                <td>@{{ tipo.TIPO_DESCRICAO }}</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    <span 
                        ng-repeat="tipo_mes in tipo.MESES" 
                        ng-if="tipo_mes.MES == mes.MES && tipo_mes.ANO == mes.ANO"
                        >
                        R$ @{{ tipo_mes.VALOR | number : 2 }}
                                            
                    </span>
                    
                </td>
                <td class="text-right" style="background: rgb(230, 222, 192);font-weight: bold;">R$ @{{ tipo.VALOR | number : 2 }}    </td>                
            </tr>
            <tr 
                ng-repeat="ccusto in tipo.CCUSTOS | orderBy : ['CCUSTO_MASK']"
                ng-if="tipo.OPENED"
                >
                <td><span style="float: left; width: 65px;">@{{ ccusto.CCUSTO_MASK }}</span>@{{ ccusto.CCUSTO_DESCRICAO }}</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                    <a 
                        href 
                        ng-repeat="tipo_mes in ccusto.MESES" 
                        ng-if="tipo_mes.MES == mes.MES && tipo_mes.ANO == mes.ANO"
                        data-toggle="modal" 
                        data-target="#modal-demonstrativo-depreciacao-anual-parcela"
                        ng-click="vm.DemonstrativoDepreciacaoAnual.SELECTEDS = tipo_mes.IMOBILIZADOS"
                        >
                        R$ @{{ tipo_mes.VALOR | number : 2 }}
                                            
                    </a>
                    
                </td>
                <td class="text-right" style="background: rgb(196, 209, 220);font-weight: bold;">R$ @{{ ccusto.VALOR | number : 2 }}    </td>
            </tr>
            <tr ng-repeat-end ng-if="false"></tr>
        </tbody>
        <tfoot>
            <tr style="background: rgb(174, 187, 197);font-weight: bold;">
                <td>Totalizador</td>
                <td
                    class="text-right"
                    ng-repeat="mes in vm.DemonstrativoDepreciacaoAnual.MESES | orderBy : ['ANO','MES']"
                    >
                        R$ @{{ mes.VALOR | number : 2 }}                    
                </td>
                <td class="text-right">
                    R$ @{{ vm.DemonstrativoDepreciacaoAnual.TOTAL_GERAL | number : 2 }}                    
                </td>
            </tr>  
        </tfoot>
    </table>
</div>


@overwrite
