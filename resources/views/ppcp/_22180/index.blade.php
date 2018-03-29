@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22180.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22180.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

<button gc-pessoal-colaborador-centro-de-trabalho="" type="button" class="btn btn-info btn-xs" style="position: absolute;z-index: 99;top: 65px;left: 65px;">
    <span class="fa fa-briefcase"></span>
    Centro de Trabalho
</button>
    
    @include('ppcp._22180.index.panel-destaque')
    @include('ppcp._22180.index.form-filtro')

 
    
    <div ng-if="vm.Filtro.TAB_ACTIVE == 'PRODUZIR'">
    <br/>
    <fieldset>
        <legend>
            Indicadores de Eficiência
        </legend>
        <style>
            #table-indicador td {
                font-size: 30px;
                font-weight: bold;
            }
        </style>
        <table id="table-indicador" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-right">Meta</th>
                    <th class="text-right">Qtd. Prod.</th>
                    <th class="text-right">Eficiência</th>
                    <th class="text-right">Eficácia</th>
                    <th class="text-right">Defeitos</th>
                    <th class="text-right">% Defeitos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.QUANTIDADE_PROJETADA | number }} prs</td>
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.QUANTIDADE_PRODUZIDA | number }} prs</td>
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.EFICIENCIA | number : 2 }}%</td>
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.EFICACIA | number : 2 }}%</td>                    
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.QUANTIDADE_DEFEITO | number }}</td>
                    <td class="text-right text-lowercase">@{{ vm.Talao.INDICADOR.PERCENTUAL_DEFEITO | number : 2 }}%</td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    </div>
    <br/>
    
    
    <ul class="nav nav-tabs">
        <li class="active">
            <a 
                ng-click="vm.Filtro.TAB_ACTIVE = 'PRODUZIR'"
                data-toggle="tab" 
                href="#tab-produzir">
                Talões a Produzir
            </a>
        </li>
        <li>
            <a 
                ng-click="vm.Filtro.TAB_ACTIVE = 'PRODUZIDO'"
                data-toggle="tab" 
                href="#tab-produzido">
                Talões Produzidos
            </a>
        </li>
    </ul>    
    
    <div class="tab-content">
        <div id="tab-produzir" class="tab-pane fade in active">    

                <div class="estacao" style="
                background: rgb(236, 236, 236);
                padding: 4px 10px 11px 10px;
                margin-bottom: 10px;
                ">
                    <div class="titulo" style="
                        background: rgb(51, 122, 183);
                        color: rgb(255, 255, 255);
                        box-shadow: 0px 4px 8px 1px rgb(202, 202, 202);
                        margin-bottom: 7px;
                        text-align: center;
                        padding: 3px;
                        width: calc(100% + 20px);
                        margin-left: -10px;
                        margin-top: -4px;
                        font-weight: bold;                 
                         ">
                        @{{ vm.ConsultaUp.UP_DESCRICAO }} / @{{ vm.ConsultaEstacao.ESTACAO_DESCRICAO }}
                    </div>
                    <div class="card-container">                      
                        <div 
                            ng-click="vm.Talao.pick(talao,'modal-open')"
                            ng-repeat="talao in vm.Talao.FILTERED = (vm.TalaoProduzir.DADOS  | limitTo: 7 | orderBy : ['-DATAHORA_INICIO*1'])"
                            class="card" 
                            data-talao-id="@{{ talao.TALAO_ID }}"
                            tabindex="0"
                            >
                            <div 
                                class="col-1 programacao-status-@{{ talao.PROGRAMACAO_STATUS }}">
                                <div class="modelo">
                                    @{{ talao.MODELO_DESCRICAO }}
                                </div>
                                <div class="cor">
                                    @{{ talao.COR_DESCRICAO }}
                                </div>
                                <div class="talao">
                                    @{{ talao.REMESSA }} / @{{ talao.REMESSA_TALAO_ID }}
                                </div>      
                            </div>
                            <div class="col-2">     
                                <div ng-if="talao.CONSUMO_STATUS == '0'" style="
                                    color: red;
                                    font-size: 14px;
                                    font-weight: bold;
                                "
                                ttitle="Talão com o consumo de <b>COLA</b> pendente">C</div>                        
                                <div ng-if="talao.ESTOQUE_STATUS == '0'" style="
                                    color: red;
                                    font-size: 14px;
                                    font-weight: bold;
                                "
                                ttitle="Talão sem estoque de para consumo">E</div>   
                                <div ng-if="talao.REMESSA_LIBERADA == '0'" style="
                                    color: red;
                                    font-size: 14px;
                                    font-weight: bold;
                                "
                                ttitle="Remessa bloqueada para produção">B</div>                         
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="legenda">
                    <li>
                        <div class="texto-legenda">Fonte:</div>
                    </li>
                    <li>
                        <div class="cor-legenda programacao-status-0"></div>
                        <div class="texto-legenda">Não Iniciado</div>
                    </li>
                    <li>
                        <div class="cor-legenda programacao-status-1"></div>
                        <div class="texto-legenda">Iniciado/Parado</div>
                    </li>
                    <li>
                        <div class="cor-legenda programacao-status-2"></div>
                        <div class="texto-legenda">Em Andamento</div>
                    </li>
                </ul>            
                <ul class="legenda">
                    <li>
                        <div class="texto-legenda">Letra:</div>
                    </li>
                    <li>
                        <div class="cor-legenda" style="
                            color: rgb(255, 0, 0);
                            font-size: 14px;
                            font-weight: bold;
                            margin-top: -1px;     
                        ">C</div>
                        <div class="texto-legenda"> - Consumo de Cola Pendente</div>
                    </li>
                    <li>
                        <div class="cor-legenda" style="
                            color: rgb(255, 0, 0);
                            font-size: 14px;
                            font-weight: bold;
                            margin-top: -1px;     
                        ">E</div>
                        <div class="texto-legenda"> - Estoque de Consumo Indisponível</div>
                    </li>
                    <li>
                        <div class="cor-legenda" style="
                            color: rgb(255, 0, 0);
                            font-size: 14px;
                            font-weight: bold;
                            margin-top: -1px;     
                        ">B</div>
                        <div class="texto-legenda"> - Remessa bloqueada para produção</div>
                    </li>
                </ul>            
            
        </div>
        <div id="tab-produzido" class="tab-pane fade" style="height: calc(100vh - 365px);">    
            <div class="table-ec">
                <table class="table table-striped table-bordered table-middle table-condensed table-no-break">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" ttitle="Data da demessa">Dt. Rem.</th>
                            <th>Remessa</th>
                            <th class="text-center">Talão</th>
                            <th>Modelo</th>
                            <th class="text-right" ttitle="Quantidade a Produzir">Qtd.</th>
                            <th class="text-center" ttitle="Data e hora em que o talão foi iniciado">Dt./Hr. Inici.</th>
                            <th class="text-center" ttitle="Data e hora em que o talão foi finalizado">Dt./Hr. Final.</th>
                            <th class="text-right" ttitle="Tempo previsto">TP. Prev.</th>
                            <th class="text-right" ttitle="Tempo realizado">TP. Realiz.</th>
                            <!--<th class="text-center" ttitle="Previsão para início">Prev. Ini.</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            ng-repeat="talao in vm.TalaoProduzido.FILTERED = (vm.TalaoProduzido.DADOS | orderBy : ['-DATAHORA_FINALIZADO'])"
                            ng-click="vm.Talao.pick(talao,'modal-open')"
                            ng-class="{'selected': vm.Talao.SELECTED == talao }"
                            data-talao-id="@{{ talao.TALAO_ID }}"
                            tabindex="0"
                            >
                            <td style="text-align: center; padding: 0; margin: 0;">
                                <i 
                                    class="fa status-programacao-@{{ talao.PROGRAMACAO_STATUS }}" 
                                    style="font-size: 17px;"
                                    ng-class="{
                                        'fa-minus-circle' : talao.ESTOQUE_STATUS == 0 && talao.PROGRAMACAO_STATUS == 0,
                                        'fa-stop-circle'  : talao.ESTOQUE_STATUS == 1 && talao.PROGRAMACAO_STATUS == 0,
                                        'fa-pause-circle' : talao.PROGRAMACAO_STATUS == 1, 
                                        'fa-play-circle'  : talao.PROGRAMACAO_STATUS == 2, 
                                        'fa-check-circle' : talao.PROGRAMACAO_STATUS == 3
                                    }"
                                ></i>
                            </td>  
                            <td class="text-center">@{{ talao.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                            <td>@{{ talao.REMESSA }}</td>
                            <td class="text-center">@{{ talao.REMESSA_TALAO_ID }}</td>
                            <td>@{{ talao.MODELO_ID }} - @{{ talao.MODELO_DESCRICAO }}</td>
                            <td class="text-right text-lowercase">@{{ talao.QUANTIDADE_PROJETADA }} @{{ talao.UM }}</td>
                            <td class="text-center">@{{ talao.DATAHORA_INICIADO | toDate | date:'dd/MM HH:mm:ss' }}</td>
                            <td class="text-center">@{{ talao.DATAHORA_FINALIZADO | toDate | date:'dd/MM HH:mm:ss' }}</td>
                            <td class="text-right text-lowercase">@{{ talao.TEMPO_PREVISTO | number: 2 }} min</td>
                            <td class="text-right text-lowercase">@{{ talao.TEMPO_REALIZADO | number: 2 }} min</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
    @include('ppcp._22180.index.modal-talao')
    @include('ppcp._22180.index.modal-operador')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    <script src="{{ elixir('assets/js/pessoal/helper/factory.colaborador-centro-de-trabalho.js') }}"></script>
    <script src="{{ elixir('assets/js/_22180.js') }}"></script>
@append
