@extends('master')

@section('titulo')
    {{ Lang::get('logistica/_14020.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/14020.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

  
    <ul class="nav nav-tabs">
        <li class="active" ng-click="vm.Cte.FILTRO.TIPO = 'SIMULADOR'">
            <a data-toggle="tab" href="#tab-simulador">Simulador</a>
        </li>
        <li ng-click="vm.Cte.FILTRO.TIPO = 'CTE'">
            <a data-toggle="tab" href="#tab-cte">CT-e</a>
        </li>
<!--        <li ng-click="vm.Cte.FILTRO.TIPO = 'NF'">
            <a data-toggle="tab" href="#tab-nf">NF</a>
        </li>-->
    </ul>      

    <style>
        .form-filtro {
            background: rgba(221,221,221,.33);
            padding: 2px 10px 7px;
            border-radius: 5px
        }

        .form-filtro .consulta-container {
            margin-right: initial;
            margin-bottom: initial
        }

        .form-filtro input {
            width: calc(100% - 27px)!important
        }

        .form-filtro .label-checkbox {
            top: 9px
        }

        .form-filtro [type=submit] {
            margin-top: 16px
        }    

        .form-filtro .check-group {
            padding: 0 0 4px 10px;
            border-radius: 6px;
            background: rgb(226, 226, 226);
            margin-top: -1px;
        }

        .form-filtro .check-group .lbl {
            display: inline-block;
            margin-right: 10px;
        }

        .form-filtro .check-group .lbl input[type="checkbox"], 
        .form-filtro .check-group .lbl input[type="radio"] {
            margin-top: 0;
            margin-bottom: 0;
            top: 5px;
            position: relative;
            width: 20px!important;
            height: 20px;
            vertical-align: baseline;
            box-shadow: none;
        }

        .form-filtro .check-group .lbl [checked] ~ span {
            font-weight: bold;
        }

    </style>    
    
    <div class="tab-content">
        <div id="tab-simulador" class="tab-pane fade in active">

        <form class="form-inline" ng-submit="vm.Simulador.calcular()">
            <div class="table-filter collapse in form-filtro" aria-expanded="true">


                <div class="consulta-frete-transportadora-simulador-itens"></div>    
                <div class="consulta-cliente-simulador-itens"></div>    
                <div class="consulta-cidade-simulador-itens"></div>    
                
                <button type="submit" class="btn btn-xs btn-success btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                    <span class="glyphicon glyphicon-ok"></span> Processar Frete
                </button>
            </div>                

        <style>
            #tab-simulador .divTable .consulta-container {
                width: 100%;
            }     
            
            #tab-simulador .divTable .consulta-container .consulta {
                width: 100%;
            }     
            
            #tab-simulador .divTable .consulta-container .form-group {
                width: 100%;
            }     
            
            #tab-simulador .divTable .form-group label {
                display: none;
            }      
            
            #tab-simulador .divTable .form-group {
                margin: 0; /*-3px -4px 0px -4px;*/
                /*padding: 2px 0px 2px 2px;*/
            }            
            
            #tab-simulador .divTable .form-group input[type="search"]{
                width: calc(100% - 39px) !important;
            }      
            
            
            
            .divTable{
                display: table;
                border-collapse: collapse;
                width: 100%;
            }
            .divTableRow {
                display: table-row;
            }
            .divTableHeading {
                background-color: #EEE;
                display: table-header-group;
            }
            .divTableCell, .divTableHead {
                border: 1px solid rgb(221, 221, 221);
                display: table-cell;
                /*padding: 3px 10px;*/
                padding: 3px;/* 5px 1px 5px;*/
            }
            .divTableHeading {
                background-color: rgb(51, 122, 183);
                color: white;
                display: table-header-group;
            }
            .divTableFoot {
                background-color: #EEE;
                display: table-footer-group;
                font-weight: bold;
            }
            .divTableBody {
                display: table-row-group;
            }
            
            .divTableBody .divTableRow:nth-of-type(odd) {
                background-color: rgb(249, 249, 249);
            }

            
            .wd-modelo {
                min-width: 275px;
            }
            
            .wd-cor {
                min-width: 275px;
            }
            
            .wd-tamanho {
                min-width: 110px;
                width: 10%;
            }
            
            .wd-quantidade {
                min-width: 110px;
                width: 10%;
            }
            
            .wd-valor-unitario {
                min-width: 110px;
                width: 10%;
            }
            
        </style>

        <div class="divTable">
            <div class="divTableHeading">
                <div class="divTableRow">
                    <div class="divTableCell wd-modelo">Modelo</div>
                    <div class="divTableCell wd-cor">Cor</div>
                    <div class="divTableCell wd-tamanho" ttitle="Tamanho">Tam.</div>
                    <div class="divTableCell wd-quantidade  text-right" ttitle="Quantidade">Qtd.</div>
                    <div class="divTableCell wd-valor-unitario  text-right" ttitle="Valor Unitário">Val.Unit.</div>
                </div>
            </div>
            <div class="divTableBody">
                <div class="divTableRow" ng-repeat="item in vm.Simulador.ITENS track by $index">
                    <div class="divTableCell"><div class="consulta-modelo-@{{ item.DOM_ID }}"></div></div>
                    <div class="divTableCell"><div class="consulta-cor-@{{ item.DOM_ID }}"></div></div>
                    <div class="divTableCell"><div class="consulta-tamanho-@{{ item.DOM_ID }}"></div></div>
                    <div class="divTableCell">
                        <input 
                            type="number" 
                            step="40" 
                            string-to-number
                            class="form-control text-right" 
                            ng-required="item.REQUIRED"
                            ng-model="item.QUANTIDADE" 
                            >
                    </div>
                    <div class="divTableCell">
                        <input 
                            type="number" 
                            step="0.0001" 
                            string-to-number
                            class="form-control text-right" 
                            ng-required="item.REQUIRED"
                            ng-model="item.VALOR_UNITARIO" 
                            >
                    </div>
                </div>
            </div>
        </div>    
                
                
        </form>       
        
<!--        <div class="table-ec table-scroll">
         <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
               <tr>
                  <th>Modelo</th>
                  <th>Cor</th>
                  <th ttitle="Tamanho">Tam.</th>
                  <th ttitle="Quantidade">Qtd.</th>
                  <th ttitle="Valor Unitário">Val.Unit.</th>
               </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in vm.Simulador.ITENS track by $index">
                    <td>
                        <div class="consulta-modelo-@{{ $index }}"></div>
                    </td>
                   <td>
                        <div class="consulta-cor-@{{ $index }}"></div>
                   </td>
                    <td class="ellipsis">
                        <div class="consulta-tamanho-@{{ $index }}"></div>
                    </td> 
                   <td>@{{ item.QUANTIDADE }}</td>
                   <td>@{{ item.VALOR_UNITARIO }}</td>
               </tr>
            </tbody>
         </table>
        </div>            -->
            
        </div>
        <div id="tab-cte" class="tab-pane fade">
    

            <ul class="list-inline acoes">    
                <li>
                    <button 
                        type="button" 
                        class="btn btn-primary btn-success" 
                        data-hotkey="f10" no-focus
                        ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                        ng-click="vm.Cte.calcular('CTE',vm.Cte.SELECTED.TRANSPORTADORA_ID+'|'+vm.Cte.SELECTED.NFE+'|'+vm.Cte.SELECTED.NFE_SERIE)">
                        <span class="glyphicon glyphicon-ok"></span> Processar Frete
                    </button>
                </li>                       
            </ul>  
            <form class="form-inline" ng-submit="vm.Cte.consultar()">
                <div class="table-filter collapse in form-filtro" aria-expanded="true">
                    
                    
                    <div class="consulta-frete-transportadora"></div>    
                    
                    <div class="form-group">
                        <label>Documento:</label>
                        <div class="input-group check-group" style="width: calc(100%);padding: 0 0 3px 3px;">
                            <input type="text" class="input-control"
                                   style="width: calc(100% - 69px) !important;"
                                   form-validate="true"
                                   ng-model="vm.Cte.FILTRO.DOCUMENTO" 
                                   ng-change="vm.Cte.FILTRO.DOCUMENTO_TODOS = vm.Cte.FILTRO.DOCUMENTO.length > 0 ? false : true"
                                   ng-required="vm.Cte.FILTRO.DOCUMENTO.length > 0 && vm.Cte.FILTRO.DOCUMENTO_TODOS != true"
                                   >
                            
                            <label class="lbl">
                                <input type="checkbox" 
                                       ng-model="vm.Cte.FILTRO.DOCUMENTO_TODOS" 
                                       ng-init="vm.Cte.FILTRO.DOCUMENTO_TODOS = true">
                                <span>Todos</span>
                            </label>
                            
                        </div>
                    </div>                    
                    
<!--                    <div class="form-group">
                        <label>Tipo:</label>             
                        <div class="check-group">
                            <label class="lbl">
                                <input 
                                    type="radio" 
                                    ng-click="vm.Cte.FILTRO.CONSUMO_PERCENTUAL = '< 1'; vm.Cte.FILTRO.DATA_1 = '01.01.1989'; vm.Cte.FILTRO.DATA_2 = '01.01.2500';"
                                    ng-checked="vm.Cte.FILTRO.CONSUMO_PERCENTUAL == '< 1'">
                                <span>Pendente</span>
                            </label>
                            <label class="lbl">
                                <input 
                                    type="radio" 
                                    ng-click="vm.Cte.FILTRO.CONSUMO_PERCENTUAL = '>= 1'"
                                    ng-checked="vm.Cte.FILTRO.CONSUMO_PERCENTUAL == '>= 1'">
                                <span>Completa</span>
                            </label>
                        </div>
                    </div>             -->
                             
                    
                    <div class="form-group">
                        <label title="Data para produção da remessa">Data Entrada:</label>
                        <div class="check-group" style="width: calc(100%);padding: 3px;">
                            <div class="input-group">
                                <input type="date" 
                                       class="form-control"
                                       toDate 
                                       max="@{{ vm.Cte.FILTRO.DATA_2 | date: 'yyyy-MM-dd' }}"  required
                                       ng-model="vm.Cte.FILTRO.DATA_1" 
                                       ng-change="vm.Cte.FILTRO.DATA_TODOS = false" 
                                       ng-required="!vm.Cte.FILTRO.DATA_TODOS"
                                       />
                                <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                                    <span class="fa fa-close"></span>
                                </button>
                            </div>
                            <div class="input-group">
                                <input type="date" 
                                       class="form-control" 
                                       toDate 
                                       required
                                       ng-model="vm.Cte.FILTRO.DATA_2" 
                                       ng-change="vm.Cte.FILTRO.DATA_TODOS = false" 
                                       ng-required="!vm.Cte.FILTRO.DATA_TODOS"
                                       />
                                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                    <span class="fa fa-close"></span>
                                </button>
                            </div>  
<!--      
                            <label class="lbl" style="margin-top: -1px;">
                                <input type="checkbox" ng-model="vm.Cte.FILTRO.DATA_TODOS" ng-init="vm.Cte.FILTRO.DATA_TODOS = true">
                                <span>Todos</span>
                            </label>                            -->
                        </div>
                    </div>
                                     
                    
                    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                        <span class="glyphicon glyphicon-filter"></span> Filtrar
                    </button>
                </div>                
            </form>            
            <style>
                .wid-cliente {
                    width: 25%;
                    min-width: 200px;
                    max-width: 200px;
                }
                .wid-cliente-documento {
                    width: 12%;
                    min-width: 160px;
                    max-width: 160px;
                }
            </style>
            
            <div class="table-ec table-scroll" style="height: calc(100vh - 267px); min-height: 250px;">
                <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                    <thead>
                        <tr gc-order-by="vm.Cte.ORDER_BY">
                            <th field="TRANSPORTADORA_RAZAOSOCIAL">Transportadora</th>
                            <th field="NFE*1,NFE_SERIE*1">NF-e</th>
                            <th field="DATA_ENTRADA_JS">Dt. Entrada</th>
                            <th field="DATA_EMISSAO_JS">Dt. Emissão</th>
                            <th class="wid-cliente" field="CLIENTE_RAZAOSOCIAL">Cliente</th>
                            <th field="CLIENTE_UF,CLIENTE_CIDADE,CLIENTE_RAZAOSOCIAL">UF / Cidade</th>
                            <th class="wid-cliente-documento" ttitle="Nota Fiscal de Saida do Cliente">NF-s</th>
                            <th field="VALOR_TOTAL*1" ttitle="Valor do Conhecimento de Transporte Eletrônico"  class="text-right">Valor CTE</th>
                            <th field="FRETE_VALOR_TOTAL*1" ttitle="Valor gerado a partir da simulação"  class="text-right">Valor Sim.</th>
                            <th field="FRETE_DIFERENCA*1" ttitle="Divergencia de valores"  class="text-right">Dif.</th>
                            <th field="FRETE_DIFERENCA_PERCENTUAL*1" ttitle="Divergencia de valores em percentual"  class="text-right">Dif.%</th>
                        </tr>
                    </thead>
                    <tbody vs-repeat vs-scroll-parent=".table-ec">
                    <!--<tbody>-->
                        <tr ng-repeat="item in vm.Cte.DADOS | orderBy: vm.Cte.ORDER_BY"
                            ng-dblclick="item.FRETE_ID > 0 ? vm.Frete.consultar(item.FRETE_ID) : '';"
                            ng-keydown="vm.Frete.keydown(item,$event)"
                            ng-class="{
                                'selected' : vm.Cte.SELECTED == item
                            
                            }"
                            ng-focus="vm.Cte.SELECTED = item"
                            tabindex="0">
                            <td title="Transportadora CNPJ: @{{ item.TRANSPORTADORA_CNPJ_MASK }}">@{{ item.TRANSPORTADORA_ID | lpad: [4,0] }} - @{{ item.TRANSPORTADORA_RAZAOSOCIAL }}</td>
                            <td title="NF-e Id: @{{ item.ID }}">@{{ item.NFE }}-@{{ item.NFE_SERIE }}</td>
                            <td>@{{ item.DATA_ENTRADA_JS | date: 'dd/MM/yy' }}</td>                            
                            <td>@{{ item.DATA_EMISSAO_JS | date: 'dd/MM/yy' }}</td>     
                            <td class="wid-cliente" autotitle>@{{ item.CLIENTE_ID | lpad: [4,0] }} - @{{ item.CLIENTE_RAZAOSOCIAL }}</td>
                            <td>@{{ item.CLIENTE_UF }} / @{{ item.CLIENTE_CIDADE }}</td>                       
                            <td class="wid-cliente-documento" autotitle>@{{ item.CLIENTE_DOCUMENTO }}</td>                       
                            <td class="text-right">R$ @{{ item.VALOR_TOTAL | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.FRETE_VALOR_FINAL | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.FRETE_DIFERENCA | number : 2 }}</td>
                            <td class="text-right">@{{ item.FRETE_DIFERENCA_PERCENTUAL * 100 | number : 2 }} %</td>
                        </tr>
                    </tbody>
                </table>
            </div>         
            
        </div>
        <div id="tab-nf" class="tab-pane fade">
            N
        </div>
        <div id="tab-pedido" class="tab-pane fade">
            p
        </div>
    </div>
        

    
<!--    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr>
                    <th ttitle="Agrupamento da Família">Agrup. Fam.</th>  
                    <th ttitle="Sequência">Seq.</th>  
                    <th>Família</th>  
                    <th ttitle="Grupo de Produção">GP</th>  
                    <th ttitle="Perfil UP">Perfil UP</th>  
                    <th ttitle="Unidade Produtiva Primária"> 1ª UP.</th>  
                    <th ttitle="Unidade Produtiva Secundária">2ª UP</th>  
                    <th ttitle="Habilita cálculo de rebobinamento">Rebob.</th>  
                    <th ttitle="Habilita cálculo da conformação">Confor.</th>  
                    <th ttitle="Centro de Logistica">C. Logistica</th>
                    <th class="text-right" ttitle="Fator de Conversão">Fator Conv.</th>  
                    <th class="text-center" ttitle="Número de remessas para contabilização de defeitos">Rem. Def.</th>  
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Regra.DADOS_RENDER = ( vm.Regra.DADOS | filter : { EXCLUIDO : false } | orderBy: ['FAMILIA_PRODUCAO_DESCRICAO','SEQUENCIA*1'] | orderBy : vm.Regra.ORDER_BY )"
                    ng-click="vm.Regra.SELECTED = item"
                    ng-focus="vm.Regra.SELECTED = item"
                    ng-class="{ 'selected' : vm.Regra.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.Regra.alterar()"
                    tabindex="0"
                    >           
                    <td>@{{ item.FAMILIA_PRODUCAO || 0 | lpad: [3,0] }} - @{{ item.FAMILIA_PRODUCAO_DESCRICAO }}</td>
                    <td>@{{ item.SEQUENCIA  || 0 | lpad: [2,0] }}</td>      
                    <td>@{{ item.FAMILIA_ID || 0 | lpad: [3,0] }} - @{{ item.FAMILIA_DESCRICAO }}</td>
                    <td>@{{ item.GP_ID      || 0 | lpad: [3,0] }} - @{{ item.GP_DESCRICAO }}</td>
                    <td>@{{ item.PERFIL_UP }} - @{{ item.PERFIL_UP_DESCRICAO }}
                    <td>@{{ item.UP_PADRAO1 || 0 | lpad: [3,0] }} - @{{ item.UP_PADRAO1_DESCRICAO }}</td>
                    <td>@{{ item.UP_PADRAO2 || 0 | lpad: [3,0] }} - @{{ item.UP_PADRAO2_DESCRICAO }}</td>
                    <td>@{{ item.CALCULO_REBOBINAMENTO_DESCRICAO }}</td>
                    <td>@{{ item.CALCULO_CONFORMACAO_DESCRICAO }}</td>
                    <td><span style="float: left; width: 70px;">@{{ item.CLOGISTICA_MASK }}@{{ item.CLOGISTICA_HIERARQUIA == 1 ? '*' : '' }}</span> - @{{ item.CLOGISTICA_DESCRICAO }}</td>
                    <td class="text-right" >@{{ item.FATOR | number : 2 }}</td>
                    <td class="text-center">@{{ item.REMESSAS_DEFEITO }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    -->
    @include($menu.'.modal-frete.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_14020.js') }}"></script>
@append
