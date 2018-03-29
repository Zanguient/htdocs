@extends('master')

@section('titulo')
    {{ Lang::get('logistica/_14020.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/14020.css') }}" />
@endsection

@section('conteudo')
<style>
    #main {
        margin-top: -45px;
    }
</style>
<div ng-controller="Ctrl as vm" ng-cloak>
    
    @php $itens                      = isset($_GET['ITENS'                     ]) ? $_GET['ITENS'                     ] : '[]'
    @php $cliente_id                 = isset($_GET['CLIENTE_ID'                ]) ? $_GET['CLIENTE_ID'                ] : ''
    @php $cidade_id                  = isset($_GET['CIDADE_ID'                 ]) ? $_GET['CIDADE_ID'                 ] : ''
    
    <form class="form-inline"
        ng-init="
            vm.Comparar.DADOS.ITENS                      =  {{ $itens                       }};
            vm.Comparar.DADOS.CLIENTE_ID                 = '{{ $cliente_id                  }}';
            vm.Comparar.DADOS.CIDADE_ID                  = '{{ $cidade_id                   }}';
            vm.Comparar.DADOS.ORIGEM                     = (vm.Comparar.DADOS.CLIENTE_ID > 0) ? 'SIMULADOR' : 'SIMULADOR_CIDADE';
            vm.Comparar.DADOS.ORIGEM_ID                  = (vm.Comparar.DADOS.CLIENTE_ID > 0) ? vm.Comparar.DADOS.CLIENTE_ID : vm.Comparar.DADOS.CIDADE_ID;
            
            vm.Comparar.autoComparar(vm.Comparar.DADOS.CIDADE_ID);
        ">
        
    </form>
    <style>
            #transportadora .form-group label {
                display: none;
            }      
            
            #transportadora .form-group {
                margin: 0; /*-3px -4px 0px -4px;*/
                /*padding: 2px 0px 2px 2px;*/
            }            
            
            #transportadora .form-group input[type="search"]{
                width: calc(100% - 39px) !important;
            }     
            
            #transportadora .card {
                width: 350px;
                float: left;
                border: 1px solid rgb(177, 177, 177);
                padding: 2px 0px 0px 2px;
                border-radius: 5px;
                margin-right: 5px;
                margin-bottom: 5px;
            }     
            
            #transportadora .card .corpo {
                padding: 2px;
            }     
            
            #transportadora fieldset legend {
                font-size: 16px;
            }     
    </style>
    <fieldset>
        <legend>Comparar Transportadoras</legend>
        <div id="transportadora" style="clear : both">
            <div class="card" ng-repeat="item in vm.Comparar.TRANSPORTADORAS">
                <div class="cabecalho">
                    <div class="consulta-transportadora-@{{ item.DOM_ID }}"></div>
                </div>
                <div class="corpo">
                    <table class="table table-striped table-condensed" style="font-weight: bold;">
                        <tr>
                            <td>Classificação:</td>
                            <td class="text-right">@{{ item.TRANSPORTADORA_CLASSIFICACAO }}</td>
                        </tr>
                        <tr>
                            <td>Valor Frete:</td>
                            <td class="text-right">R$ @{{ item.VALOR_FINAL || 0 | number : 2 }}</td>
                        </tr>
                        <tr>
                            <td>% Frete:</td>
                            <td class="text-right">@{{ (item.VALOR_FINAL / item.VALOR_TOTAL) * 100 || 0 | number : 2 }}%</td>
                        </tr>
                        <tr>
                            <td>Prazo Entrega:</td>
                            <td class="text-right">@{{ item.CIDADE_PRAZO_ENTREGA || 0 | number : 2 }} dias</td>
                        </tr>
                    </table>
                    
                    <div class="accordion-ec" style="
                        border: 1px solid rgb(177, 177, 177);
                        border-radius: 5px;
                        margin-right: 2px;
                        margin-bottom: 2px;
                    ">
                        <div class="btn btn-default accordion-ec-head" ng-click="item.DETALHAMENTO_OPENED = item.DETALHAMENTO_OPENED == true ? false : true " style="
                            margin: 2px;
                            width: calc(100% - 4px);
                            padding: 0 0 0 5px;
                            text-align: left;
                        ">
                            <i class="fa fa-@{{ item.DETALHAMENTO_OPENED ? 'minus' : 'plus' }}-square"></i> Exibir Detalhamento
                        </div>
                        <div class="accordion-ec-body" ng-if="item.DETALHAMENTO_OPENED" style="
                            padding: 5px;
                        ">
                            <fieldset ng-repeat="composicao in item.COMPOSICOES">
                                <legend>@{{ composicao.DESCRICAO }}</legend>

                                <div class="table-ec">
                                    <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                                        <thead>
                                            <tr>
                                                <th>Campo</th>
                                                <th class="text-right" ttitle="Valor gerado a partir da simulação">Val.Sim.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="detalhe in composicao.DADOS">
                                                <td>
                                                    @{{ detalhe.DESCRICAO }}

                                                    <span
                                                        ng-if="(detalhe.EXPRESSAO != undefined && detalhe.EXPRESSAO.length > 0) || (detalhe.OBSERVACAO != undefined && detalhe.OBSERVACAO.length > 0)"
                                                        style="float: right; margin-left: 5px"
                                                        class="composicao-popover glyphicon glyphicon-info-sign" 
                                                        data-toggle="popover" 
                                                        data-placement="right" 
                                                        title="Mais Informações"
                                                        data-element-content="#info-detalhe-@{{ detalhe.SEQUENCIA }}"
                                                    ></span>
                                                    <div id="info-detalhe-@{{ detalhe.SEQUENCIA }}" style="display: none"> 
                                                        <span ng-if="detalhe.OBSERVACAO != undefined && detalhe.OBSERVACAO.length > 0">
                                                            Observação: @{{ detalhe.OBSERVACAO }}
                                                            <br/><br/>
                                                        </span>
                                                        <span ng-if="detalhe.EXPRESSAO != undefined && detalhe.EXPRESSAO.length > 0">
                                                            Expressão Amigável: <br/><span ng-bind-html="vm.trustedHtml(detalhe.EXPRESSAO)"></span>
                                                            <br/><br/>
                                                            Expressão numérica: <br/><span ng-bind-html="vm.trustedHtml(detalhe.EXPRESSAO_VALOR)"></span>
                                                            <br/>
                                                        </span>
                                                    </div>

                                                </td>
                                                <td class="text-right">@{{ detalhe.VALOR | number : 4 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>               
                            </fieldset>                    
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </fieldset>  
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_14020.js') }}"></script>
@append
