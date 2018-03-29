@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15090.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15090.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

    <fieldset class="tab-container">

        <ul class="list-inline acoes">
            <li >
                <a href="{{url('/')}}" class="btn btn-default">
                    <span class="glyphicon glyphicon-chevron-left"></span> 
                    Voltar
                </a>
            </li>
        </ul>

        <ul id="tab" class="nav nav-tabs" role="tablist"> 

            <li role="presentation" class="active tab-detalhamento">
                <a href="#tab1-container" id="tab1-tab" role="tab" data-toggle="tab" aria-controls="tab1-container" aria-expanded="true">
                    Conferência
                </a>
            </li> 
     
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab2-container" id="tab2-tab" ng-click="vm.Conferencia.pendencias()" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="false">
                    Pendentes de Conferência
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab3-container" id="tab3-tab" ng-click="vm.Conferencia.pendenciasLote()" role="tab" data-toggle="tab" aria-controls="tab3-container" aria-expanded="false">
                    Lotes de Conferência
                </a>
            </li>
        </ul>

        <div id="tab-content" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab1-container" aria-labelledby="tab1-tab">
                <ul class="">
                    <li style="display: inline-flex;">
                        <button ng-disabled="!(vm.Conferencia.ITENS.length > 0)" ng-click="vm.Conferencia.modalOperador.show()" type="button" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            Confirmar
                        </button>
                    </li>
                    <li style="display: inline-flex;">
                        <button ng-disabled="!(vm.Conferencia.DADOS.length > 0)" ng-click="vm.Conferencia.checkAll()"  type="button" class="btn btn-warning">
                            <span class="fa fa-random"></span>
                            Marcar Todos
                        </button>
                    </li>
                </ul>

                <form class="form-inline">
                    <div class="form-group">
                        <label>Tipo de conferência:</label>
                        <select ng-init="vm.Filtro.CONFERENCIA_TIPO = '1'" ng-model="vm.Filtro.CONFERENCIA_TIPO" ng-change="vm.Conferencia.clearData()" class="form-control">
                            <option disabled>-- Tipo --</option>
                            <option value="1">Conf. Kanban</option>
                            <option value="2">Conf. Prod.</option>
                            <option value="3">Conf. Abast. WIP</option>
                            <option value="4">Conf. Trans. Rem.</option>
                            <option value="6">Lote de conferência</option>
                        </select>
                    </div>   
                    <div class="form-group">
                        <label for="consulta-descricao">Código de Barras:</label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                ng-model="vm.Filtro.CODIGO_BARRAS"
                                ng-keydown="$event.key == 'Enter' && vm.Filtro.CODIGO_BARRAS != '' ? vm.Filtro.consultar() : ''" 
                                ng-readonly="vm.Conferencia.DADOS.length > 0"
                                class="form-control input-maior input-codigo-barras" 
                                autocomplete="new-password"
                                autofocus/>
                            <button 
                                type="button" 
                                ng-click="vm.Filtro.CODIGO_BARRAS != '' ? vm.Filtro.consultar() : ''" 
                                ng-if="!(vm.Conferencia.DADOS.length > 0)"
                                class="input-group-addon btn-filtro search-button" 
                                tabindex="-1" 
                                >
                                <span class="fa fa-search"></span>
                            </button>
                            <button 
                                type="button" 
                                ng-click="vm.Conferencia.clearData()" 
                                ng-if="vm.Conferencia.DADOS.length > 0"
                                class="input-group-addon btn-filtro search-button " 
                                tabindex="-1"
                                >
                                <span class="fa fa-close"></span>
                            </button>            
                        </div>        
                    </div>    
                </form>
                <style>
                    #table-itens td {
                        font-size: 16px;
                    }

                    #table-itens2 td {
                        font-size: 12px;
                    }
                    
                    .item-stts {
                        height: 15px;
                        width: 15px;
                        border-radius: 8px;
                        border: 1px solid;
                    }
                    
                    .t-status:before {
                        border-radius: 10px !important;
                        width: 20px !important;
                        height: 20px !important;
                    }
                    .item-stts-0:before {
                        background-color: rgb(51, 122, 183) !important;
                    }
                    .item-stts-1:before { 
                        background-color: rgb(217, 83, 79) !important;
                    }
                    
                    .item-stts-2:before {
                        background-color: rgb(68, 157, 68) !important;
                    }
                      
                </style>
                <div id="table-itens" class="table-ec table-scroll" style="height: calc(100vh - 355px);">
                    <table class="table table-bordered table-hover table-striped table-middle">
                        <thead>
                            <tr>
                                <th class="text-center"><span ttitle="Status da Conferencia">Stts</span></th>
                                <th>Produto</th>
                                <th>Peça</th>
                                <th class="text-center">Tam.</th>
                                <th class="text-right">Qtd.</th>
                                <th class="text-center"><span ttitle="Conferir Item">Conf.</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                tabindex="0"
                                ng-repeat="item in vm.Conferencia.DADOS"
                                ng-focus="vm.Conferencia.SELECTED != item ? vm.Conferencia.pick(item) : ''"
                                ng-click="vm.Conferencia.SELECTED != item ? vm.Conferencia.pick(item) : ''"
                                ng-class="{'selected' : vm.Conferencia.SELECTED == item }"
                                ng-keypress="vm.Conferencia.keypress(item,$event)"
                                ng-dblclick="vm.Conferencia.toggleCheck(item)"
                                >
                                <td class="t-status item-stts-@{{ item.CONFERENCIA }}"></td>
                                <td>
                                    <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ item.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ item.LOCALIZACAO_ID }}" target="_blank">@{{ item.PRODUTO_ID }}</a>
                                    - 
                                    @{{ item.PRODUTO_DESCRICAO }}
                                </td>
                                <td class="text-center">@{{ item.PECA_ID }}</td>
                                <td class="text-center">@{{ item.TAMANHO_DESCRICAO }}</td>
                                <td class="text-right text-lowercase">@{{ item.QUANTIDADE | number : 5 }} @{{ item.UM }}</td>
                                <td class="text-center" style="padding: 6px 13px 2px 0px;;">
                                    <label ng-if="item.CONFERENCIA > 0" class="switch" style="margin: -3px -6px -5px 6px;">
                                        <input 
                                            type="checkbox" 							
                                            ng-checked="item.CONFERIR == '2'"
                                            ng-true-value="'2'"
                                            ng-false-value="'1'"
                                            ng-click="vm.Conferencia.toggleCheck(item)">
                                        <div class="slider"></div>
                                    </label>
                                </td>
                            </tr>                
                        </tbody>
                    </table>
                </div>
                <div class="legenda-container" style="display: inline-block;">
                    <label class="legenda-label" style="float: left; margin-bottom: 0; font-size: 11px;">Legenda de cores do status</label>
                    <ul class="legenda talao" style="clear: left; margin-top: 0;">
                        <li>
                            <div class="cor-legenda btn-danger"></div>
                            <div class="texto-legenda">Conferencia Pendente  |</div>
                        </li>
                        <li>
                            <div class="cor-legenda btn-success"></div>
                            <div class="texto-legenda">Conferido |</div>
                        </li>
                        <li>
                            <div class="cor-legenda btn-primary"></div>
                            <div class="texto-legenda">Conferencia Não Necessária</div>
                        </li>
                    </ul>
                </div> 
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab2-container" aria-labelledby="tab2-tab">
                <div style="
                    margin-top: 5px;
                    margin-bottom: 5;
                ">
                                 
                    <div style="
                        padding: 0 0 4px 10px;
                        border-radius: 6px;
                        background: rgb(226, 226, 226);
                        margin-top: -7px;
                    "> <label>Tipos:</label>
                        <label style="margin-right: 10px;" ng-repeat="tipo in vm.Conferencia.GRUPOS | orderBy: 'FAMILIA_DESCRICAO'">
                            <input 
                                type="checkbox" 
                                style="top: 5px;" 
                                ng-click="tipo.CHECKED = tipo.CHECKED ? false : true;"
                                ng-checked="tipo.CHECKED">
                            <span>@{{ tipo.VALOR }}</span>
                        </label>
                    </div>
                </div>

                <div style="
                    margin-top: 5px;
                    margin-bottom: 5;
                ">
                                 
                    <div style="
                        padding: 0 0 4px 10px;
                        border-radius: 6px;
                        background: rgb(226, 226, 226);
                        margin-top: 0px;
                    "> <label>Famílias:</label>
                        <label style="margin-right: 10px;" ng-repeat="tipo in vm.Conferencia.GRUPOS2 | orderBy: 'FAMILIA_DESCRICAO'">
                            <input 
                                type="checkbox" 
                                style="top: 5px;" 
                                ng-click="tipo.CHECKED = tipo.CHECKED ? false : true;"
                                ng-checked="tipo.CHECKED">
                            <span>@{{ tipo.VALOR }}</span>
                        </label>
                    </div>
                </div>

                <input style=" margin-top: 5px;margin-bottom: 5px;" type="text" class="form-control" ng-model="vm.Conferencia.FILTRO" placeholder="Filtragem...">

                <div id="table-itens2" class="table-ec table-scroll" style="height: calc(100vh - 355px);">
                                        
                    <table class="table table-bordered table-hover table-striped table-middle table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Data/Hora</th>
                                <th class="text-center">Produto</th>
                                <th class="text-center">Tamanho</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-center">Peça</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat-start="
                                grupo in vm.Conferencia.PENDENTES
                                | orderBy : ['GRUPO']
                                "
                                tabindex="-1"     
                                ng-if="grupo.FILTERED.length > 0 && vm.Conferencia.checkVisibility(grupo.ITENS[0]) && vm.Conferencia.checkVisibility2(grupo.ITENS[0])"
                                >
                                <td style="font-weight: bold;    background: rgb(189, 209, 226);" colspan="6">Tipo: @{{ grupo.TIPO }} -  Doc.: @{{ grupo.DOCUMENTO | lpad: [10,0] }} - Loc.: @{{ grupo.LOCALIZACAO }}
                                    <button 
                                        style="float:right"
                                        type="button" 
                                        ng-click="vm.Conferencia.Conferir(grupo.GRUPO,grupo.TIPO_ID)"
                                        class="btn btn-default btn-xs" 
                                        >
                                        <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                    </button>                                
                                </td>
                            </tr>                                
                            
                            <tr 
                                tabindex="0"
                                ng-repeat="item in grupo.FILTERED = (grupo.ITENS | filter:vm.Conferencia.FILTRO)"
                                ng-if="vm.Conferencia.checkVisibility(item) && vm.Conferencia.checkVisibility2(item)"
                                >                               

                                <td class="ellipsis ">@{{ item.DATAHORA_DESC }}</td>
                                <td class="ellipsis "  ttitle="TRANSAÇÃO ID:@{{item.CONTROLE}}">
                                    <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ item.PRODUTO_CODIGO }}&LOCALIZACAO_ID=@{{ item.LOCALIZACAO_CODIGO }}" target="_blank">@{{ item.PRODUTO_CODIGO | lpad : [6,0] }}</a> - @{{ item.DESCRICAO }}
                                </td>
                                <td class="text-center ellipsis">@{{ item.TAMANHO }}</td>
                                <td class="text-right ellipsis text-lowercase"><span ng-if="item.QUANTIDADE_ALTERNATIVA > 0"> @{{ item.QUANTIDADE_ALTERNATIVA  | number : 5 }} @{{ item.UM_ALTERNATIVA }} / </span> @{{ item.QUANTIDADE  | number : 5 }} @{{ item.UM }} </td>
                                <td class="text-center ellipsis">@{{ item.PECA_ID }}</td>
                            </tr>
                            
                            
                            <tr ng-repeat-end ng-if="false"></tr>  
                        </tbody>
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab3-container" aria-labelledby="tab3-tab">

                <input style=" margin-top: 5px;margin-bottom: 5px;" type="text" class="form-control" ng-model="vm.Conferencia.FILTRO" placeholder="Filtragem...">

                <div id="table-itens2" class="table-ec table-scroll" style="height: calc(100vh - 355px);">
                    <table class="table table-bordered table-hover table-striped table-middle">
                        <thead>
                            <tr>
                                <th class="">ID</th>
                                <th class="text-center">Data/Hora</th>
                                <th class="text-center">Conferência</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                tabindex="0"
                                ng-repeat="item in vm.Conferencia.PENDENTESLOTE | filter:vm.Conferencia.FILTRO"
                                ng-if="vm.Conferencia.checkVisibility(item)"
                                >                               
                                <td class="">@{{ 'LT'+item.ID }}</td>
                                <td class="">@{{ item.DATA_HORA}}</td>
                                <td class="">                                    
                                    <button 
                                        type="button" 
                                        ng-click="vm.Conferencia.Conferir('LT'+item.ID,'6')"
                                        class="btn btn-default btn-xs" 
                                        >
                                        <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                    </button>

                                </td>
                            </tr>                
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
     </fieldset>

    @include('estoque._15090.modal-autenticar-operador')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_15090.js') }}"></script>
@append
