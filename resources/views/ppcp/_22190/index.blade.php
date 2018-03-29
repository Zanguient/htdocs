@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22190.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22190.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

  
    
    @include('ppcp._22190.index.panel-destaque')
    @include('ppcp._22190.index.form-filtro')

 
    
    
    <br/>
    <fieldset>
        <legend>
            Indicadores de Eficiência
        </legend>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Meta</th>
                    <th>Qtd. Prod.</th>
                    <th>Efic.</th>
                    <th>Qtd. Def.</th>
                    <th>% Def.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <br/>
    <fieldset>

        <ul class="nav nav-tabs">
            <li class="active">
                <a 
                    data-toggle="tab" 
                    href="#tab-produzir"
                    ng-click="vm.Filtro.TAB_ACTIVE = 'PRODUZIR'">
                    Talões a Produzir
                </a>
            </li>
            <li>
                <a 
                    data-toggle="tab" 
                    href="#tab-produzido"
                    ng-click="vm.Filtro.TAB_ACTIVE = 'PRODUZIDO';">
                    Talões Produzidos
                </a>
            </li>
        </ul>        
    <div class="tab-content">
        <div id="tab-produzir" class="tab-pane fade in active" style="height: 100%;">
            <div class="table-ec" style="height: calc(100vh - 532px); min-height: 74px;">
                <table class="table table-striped table-bordered table-middle table-condensed table-no-break">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" ttitle="Data da demessa">Dt. Rem.</th>
                            <th>Remessa</th>
                            <th class="text-center">Talão</th>
                            <th>Modelo</th>
                            <th class="text-right" ttitle="Quantidade a Produzir">Qtd.</th>
                            <th>Componentes</th>
<!--                            <th class="text-right" ttitle="Tempo previsto">Tp. Prev.</th>
                            <th class="text-center" ttitle="Previsão para início">Prev. Ini.</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            ng-click="vm.Talao.pick(talao,'modal-open')"
                            ng-repeat="talao in vm.TalaoProduzir.FILTERED = (vm.TalaoProduzir.DADOS | orderBy : ['REMESSA_DATA', '+REMESSA_TIPO', 'REMESSA_ID', 'REMESSA_TALAO_ID'])"
                            ng-class="{'selected': vm.Talao.SELECTED == talao }"
                            data-talao-id="@{{ talao.TALAO_ID }}"
                            tabindex="0"
                            >
                            <td style="text-align: center; padding: 0; margin: 0;">
                                <i 
                                    class="fa status-programacao-@{{ talao.ESTOQUE_STATUS == 0 && talao.PROGRAMACAO_STATUS == 0 ? -1 : talao.PROGRAMACAO_STATUS }}" 
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

                            <td>
                                <div
                                ng-repeat="componente in talao.COMPONENTES"
                                class="label status-programacao-@{{ componente.PROGRAMACAO_STATUS }}" 
                                >
                                    @{{ componente.REMESSA_TALAO_ID }} / @{{ componente.PROGRAMACAO_STATUS_DESCRICAO }} 
                                </div>
                            </td>                            
<!--                            <td class="text-right text-lowercase">@{{ talao.TEMPO_PREVISTO | number: 2 }} min</td>
                            <td class="text-center">@{{ talao.DATAHORA_INICIO }}</td>-->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
        <div id="tab-produzido" class="tab-pane fade" style="height: 100%;">
            <div class="table-ec" style="height: calc(100vh - 532px); min-height: 74px;">
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
    </div>
       
        <ul class="legenda">
            <li>
                <div class="texto-legenda">Status:</div>
            </li>           
            <li>
                <i class="fa fa-minus-circle status-programacao-" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
                <div class="texto-legenda">Sem estoque de consumo |</div>  
            </li>
            <li>
                <i class="fa fa-stop-circle status-programacao-0" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
                <div class="texto-legenda">Não iniciado |</div>  
            </li>
            <li>
                <i class="fa fa-pause-circle status-programacao-1" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
                <div class="texto-legenda">Pausado | </div> 
            </li>
            <li>
                <i class="fa fa-play-circle status-programacao-2" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></i>  
                <div class="texto-legenda">Em Andamento |</div>  
            </li>
            <li>
            <span class="fa fa-check-circle status-programacao-3" style="font-size: 14px;float: left;margin-right: 2px;margin-top: -1px;"></span>
                <div class="texto-legenda">Finalizado</div>
            </li>            
        </ul>     
        
    </fieldset>        

    
    @include('ppcp._22190.index.modal-talao')
    @include('ppcp._22190.index.modal-operador')
    @include('ppcp._22190.index.modal-registrar-componente')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_22190.js') }}"></script>
@append
