@extends('master')

@section('titulo')
    {{ Lang::get('estoque/_15080.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/15080.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak class="main-ctrl" style="display: none">

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
                    Kanban
                </a>
            </li> 
     
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab2-container" id="tab2-tab" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="false">
                    Lotes
                </a>
            </li>
        </ul>

        <div id="tab-content" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab1-container" aria-labelledby="tab1-tab">   

                <ul class="list-inline">
                    <li>
                        <button ng-click="vm.Lote.iniciar()"  type="button" class="btn btn-success" id="iniciar" ng-disabled="!vm.Lote.acaoCheck('iniciar').status">
                            <span class="glyphicon glyphicon-play"></span>
                            Iniciar Lote
                        </button>
                    </li>
                    <li>
                        <button ng-click="vm.Lote.finalizar()" type="button" class="btn btn-danger" id="finalizar" ng-disabled="!vm.Lote.acaoCheck('finalizar').status">
                            <span class="glyphicon glyphicon-stop"></span>
                            Finalizar Lote
                        </button>
                    </li>
                    <li>
                        <button ng-click="vm.Lote.continuar()" type="button" class="btn btn-primary" id="pausar" ng-disabled="!vm.Lote.acaoCheck('pausar').status">
                            <span class="fa fa-step-forward"></span>
                            Continuar Lote
                        </button>
                    </li>
                    <li>
                        <button ng-click="vm.Lote.cancelar()" type="button" class="btn btn-info" id="cancelar" ng-disabled="!vm.Lote.acaoCheck('finalizar').status">
                            <span class="fa fa-ban"></span>
                            Cancelar Lote
                        </button>
                    </li>
                </ul>    

                <div class="info-destaque" ng-if="vm.Lote.SELECTED.KANBAN_LOTE_ID > 0">
                    <div class="label label-primary" id="estacao-destaque">
                        <span>Lote:</span>
                        <span class="valor">@{{ vm.Lote.SELECTED.KANBAN_LOTE_ID }}</span>
                    </div>    
                    <div class="label label-warning" id="data-destaque">
                        <span>Localização:</span>
                        <span class="valor">@{{ vm.Lote.SELECTED.LOCALIZACAO_ID }} - @{{ vm.Lote.SELECTED.LOCALIZACAO_DESCRICAO }}</span>
                    </div>
                    <div class="label label-default" id="data-destaque">
                        <span>Iniciado em:</span>
                        <span class="valor">@{{ vm.Lote.SELECTED.DATAHORA_INICIADO | toDate | date : 'dd/MM/yyyy HH:mm:ss' }}</span>
                    </div>
                </div>     

                <button 
                    type="button" 
                    class="btn btn-xs btn-default btn-toggle-filter" 
                    id="filtrar-toggle" 
                    data-toggle="collapse" 
                    data-target="#form-filtro" 
                    aria-expanded="true" 
                    aria-controls="form-filtro"
                    style="margin-bottom: 4px;">
                    Filtro<span class="caret"></span>
                </button>

                <form 
                    class="form-inline"
                    ng-submit="vm.Filtro.consultar()" 
                >
                    <style>
                        #form-filtro {
                            background: rgba(221,221,221,.33);
                            padding: 2px 10px 7px;
                            border-radius: 5px;
                        }
                    </style>
                    <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">

                        <div style="
                            margin-top: 2px;
                            margin-bottom: 0;
                        ">
                            <label>Produtos:</label>        
                            <div style="
                                padding: 0 0 4px 10px;
                                border-radius: 6px;
                                background: rgb(226, 226, 226);
                                margin-top: -7px;
                            ">
                                <label style="margin-right: 10px;">
                                    <input 
                                        type="radio" 
                                        style="top: 5px;" 
                                        ng-model="vm.Filtro.NECESSIDADE" 
                                        value="todos">
                                    <span ttitle="Todos os produtos habilitados para o Kanban no estoque mínimo">Todos</span>
                                </label>
                                <label style="margin-right: 10px;">
                                    <input 
                                        type="radio" 
                                        style="top: 5px;" 
                                        ng-model="vm.Filtro.NECESSIDADE" 
                                        ng-click="vm.Filtro.maiorQueZero()"
                                        value="maior-que-zero">
                                    <span ttitle="Exibir produtos com necessidade de reposição maior que zero">Com Neces.</span>
                                </label>    
                            </div>
                        </div>


                        <div style="
                            margin-top: 2px;
                            margin-bottom: 0;
                        ">
                            <label>Famílias de Produto:</label>             
                            <div style="
                                padding: 0 0 4px 10px;
                                border-radius: 6px;
                                background: rgb(226, 226, 226);
                                margin-top: -7px;
                            ">
                                <label style="margin-right: 10px;" ng-repeat="familia in vm.Produto.FAMILIAS | orderBy: 'FAMILIA_DESCRICAO'">
                                    <input 
                                        type="checkbox" 
                                        style="top: 5px;" 
                                        ng-click="familia.CHECKED = familia.CHECKED ? false : true;"
                                        ng-checked="familia.CHECKED">
                                    <span>@{{ familia.FAMILIA_DESCRICAO }}</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>	

                <div class="main-container">
                    <input type="text" class="form-control" ng-model="vm.Produto.FILTRO" placeholder="Filtragem por Produto...">
                    <div class="table-ec">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Tam.</th>
                                    <th class="text-right">
                                        <span ttitle="Quantidade transferida neste lote">Qtd. Lote.</span>
                                    </th>
                                    <th class="text-right">
                                        <span ttitle="Estoque mínimo do produto"> Est. Mín.</span>
                                    </th>
                                    <th class="text-right">
                                        <span ttitle="Estoque máximo do produto">Est. Máx.</span>
                                    </th>
                                    <th class="text-right">
                                        <span ttitle="Estoque físico do produto">Est. Fís.</span>
                                    </th>  
                                    <th class="text-right">
                                        <span ttitle="Quantidade necessária à ser reposta do produto">Neces.</span>
                                    </th>
                                    <th class="text-right">
                                        <span ttitle="Estoque disponível na localização padrão do produto">Est. Disp.</span>
                                    </th>  
                                </tr>
                        </thead>
                        <tbody>
                                <tr ng-repeat-start="
                                    localizacao in vm.Produto.LOCALIZACOES
                                    | orderBy : ['LOCALIZACAO_DESCRICAO']
                                    "
                                    tabindex="-1"     
                                    class="tr-fixed-1"
                                    ng-if="localizacao.FILTERED.length > 0"
                                    >
                                    <td class="row-fixed row-fixed-1" colspan="8">@{{ localizacao.LOCALIZACAO_ID }} - @{{ localizacao.LOCALIZACAO_DESCRICAO }}</td>
                                </tr>                  
                                <tr ng-repeat="
                                    item in localizacao.FILTERED = (localizacao.PRODUTOS
                                    | filter : vm.Produto.filtrarMaiorQueZero
                                    | find: {
                                        model : vm.Produto.FILTRO,
                                        fields : [    
                                            'PRODUTO_ID',
                                            'PRODUTO_DESCRICAO',
                                            'TAMANHO_DESCRICAO'
                                        ]
                                    }
                                    | orderBy : ['PRODUTO_DESCRICAO','TAMANHO_DESCRICAO*1'])
                                    "
                                    tabindex="0" 
                                    ng-focus="vm.Produto.SELECTED != item ? vm.Produto.pick(item) : ''"
                                    ng-click="vm.Produto.SELECTED != item ? vm.Produto.pick(item) : ''"
                                    ng-class="{'selected' : vm.Produto.SELECTED == item }"
                                    ng-keypress="vm.Produto.keypress($event)"                            
                                    ng-dblclick="vm.Lote.SELECTED.LOCALIZACAO_ID > 0 && vm.Reposicao.Modal.open()"                            
                                    ng-if="vm.Produto.checkVisibility(item)"
                                    >
                                    <td class="wid-produto" autotitle>
                                        <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ item.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ item.PRODUTO_LOCALIZACAO_ID }}" target="_blank">@{{ item.PRODUTO_ID }}</a>
                                        - 
                                        @{{ item.PRODUTO_DESCRICAO }}
                                    </td>
                                    <td class="text-center" title="Grade: @{{ item.GRADE_ID }} - Id: @{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                                    <td class="text-right um">
                                        <span>
                                            @{{ item.QUANTIDADE_LOTE || 0 | number : 4 }} @{{ item.UM }}
                                        </span>                                      
                                    </td>
                                    <td class="text-right um">
                                        <span>
                                            @{{ item.ESTOQUE_MIN | number : 4 }} @{{ item.UM }}
                                        </span>                                      
                                    </td>
                                    <td class="text-right um">
                                        <span>
                                            @{{ item.ESTOQUE_MAX | number : 4 }} @{{ item.UM }}
                                        </span>         
                                    </td>
                                    <td class="text-right um">
                                        <span
                                            ng-class="{'text-alert' : item.ESTOQUE_FISICO < item.ESTOQUE_MIN}"
                                            ng-if="item.ESTOQUE_FISICO < item.ESTOQUE_MIN"
                                            >
                                            <b ttitle="Estoque abaixo do mínimo">
                                                @{{ item.ESTOQUE_FISICO | number : 4 }} @{{ item.UM }}
                                            </b>
                                        </span>
                                        <span 
                                            ttitle="Estoque acima do mínimo e abaixo do máximo"
                                            style="color:blue" 
                                            ng-if="item.ESTOQUE_FISICO < item.ESTOQUE_MAX && item.ESTOQUE_FISICO >= item.ESTOQUE_MIN"
                                            >
                                            @{{ item.ESTOQUE_FISICO | number : 4 }} @{{ item.UM }}
                                        </span>
                                        <span 
                                            ttitle="Estoque igual ao acima do máximo"
                                            style="color:green"
                                            ng-if="item.ESTOQUE_FISICO >= item.ESTOQUE_MAX"
                                            >     
                                            @{{ item.ESTOQUE_FISICO | number : 4 }} @{{ item.UM }}
                                        </span>     
                                    </td>
                                    <td class="text-right um">
                                        <span >
                                            @{{ item.ESTOQUE_NECESSIDADE | number : 4 }} @{{ item.UM }}
                                        </span>         
                                    </td>                            
                                    <td class="text-right um">
                                        <span>
                                            <span
                                                ng-style="{
                                                    'color' : 
                                                    ((item.PRODUTO_ESTOQUE_FISICO  > 0 && item.PRODUTO_ESTOQUE_FISICO < item.ESTOQUE_MAX) ? 'blue' :
                                                    ((item.PRODUTO_ESTOQUE_FISICO >= item.ESTOQUE_MAX) ? 'green' : 
                                                    ((item.PRODUTO_ESTOQUE_FISICO <= 0) ? 'rgb(217, 83, 79)' : 'initial')))                                                       
                                                }"
                                                >
                                                @{{ item.PRODUTO_ESTOQUE_FISICO | number : 4 }} @{{ item.UM }}
                                            </span>
                                        </span>         
                                    </td>                            
                                </tr>  
                                <tr ng-repeat-end ng-if="false"></tr>             
                            </tbody>            
                        </table>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab2-container" aria-labelledby="tab2-tab">  
                
                <form class="form-inline table-filter" ng-submit="vm.ConsumoBaixadoFiltro.consultar()" style="background: rgba(221,221,221,.33); padding: 5px; border-radius: 4px;">    
                    {{-- ConsumoBaixadoProduto --}}
                    
                    <div class="form-group">
                        <label title="Data para produção da remessa">Data Inicio:</label>
                        <div class="input-group">
                            <input style="width: auto !important;"  type="date" ng-model="vm.Lote.DATA_1" toDate max="@{{ vm.Lote.DATA_2 | date: 'yyyy-MM-dd' }}" class="form-control" required />
                            <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                <span class="fa fa-close"></span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label title="Data para produção da remessa">Data Fim:</label>
                        <div class="input-group">
                            <input style="width: auto !important;" type="date" ng-model="vm.Lote.DATA_2" toDate id="data-prod" class="form-control" required />
                            <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                <span class="fa fa-close"></span>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" ng-click="vm.Lote.getLotes()" class="btn btn-xs btn-primary btn-filtrar btn-table-filter" data-hotkey="alt+f">
                        <span class="glyphicon glyphicon-filter"></span>
                        {{ Lang::get('master.filtrar') }}
                    </button>
                </form>

                <div class="main-container">
                    <input type="text" class="form-control" ng-model="vm.lote.FILTRO2" placeholder="Filtragem...">
                    <div class="table-ec">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Produto</th>
                                    <th>Estoque Min. ID</th>
                                    <th>Peça ID</th>
                                    <th>Quantidade</th>
                                    <th class="text-center">Data/Hora</th>
                                    <th>Usuario</th> 
                                    <th></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat-start="item in vm.Lote.LOTES_GERADOS.LOTE | orderBy : ['-KANBAN_LOTE_ID*1'] | filter:vm.lote.FILTRO2
                                    "
                                    tabindex="-1"     
                                    class="tr-fixed-1"
                                    ng-click="item.VISIVEL = item.VISIVEL == 1 ? 0 : 1"
                                    >
                                    <td class="row-fixed row-fixed-1" colspan="9">

                                        <span class="lote-status fechado"  ng-if="item.STATUS == '1'" title="Fechado" ></span>
                                        <span class="lote-status aberto"   ng-if="item.STATUS == '0'" title="Aberto"  ></span>

                                        LOTE:@{{ item.KANBAN_LOTE_ID }}  - @{{item.LOCALIZACAO_DESCRICAO}}

                                        <button style="float: right;" type="button" class="btn btn-default btn-xs" title="Imprimir lote:@{{item.KANBAN_LOTE_ID}}" ng-if="item.STATUS == '1'" ng-click="vm.Lote.imprimir(item)">
                                            <span class="glyphicon glyphicon-print"></span>
                                        </button>

                                    </td>
                                </tr>  

                                <tr
                                ng-repeat="iten in item.LOTE_DETALHE | orderBy : ['-KANBAN_LOTE_DETALHE_ID*1']"
                                tabindex="0"
                                ng-if="item.VISIVEL == 1">
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.KANBAN_LOTE_DETALHE_ID }}
                                    </td>
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.TIPO}}
                                    </td>
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.PRODUTO_ID + ' - ' + iten.PRODUTO_DESCRICAO }}
                                    </td>
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.ESTOQUE_MINIMO_ID}}
                                    </td>
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.PECA_ID == 0 ? '' : iten.PECA_ID}}
                                    </td>
                                    <td class="wid-produto text-right normal-case" autotitle>
                                        @{{ iten.QUANTIDADE | number:4}}  @{{ iten.UM }}
                                    </td>
                                    <td class="text-center" autotitle>
                                        @{{ iten.DATAHORA_TEXT }}
                                    </td>
                                    <td class="wid-produto" autotitle>
                                        @{{ iten.USUARIO_DESCRICAO }}
                                    </td>   
                                    <td class="wid-produto" autotitle>
                                        <button type="button" class="btn btn-danger btn-xs" title="Já conferido" ng-if="iten.CONFERENCIA != 1 && item.STATUS == 1" disabled="">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-xs" title="Excluir" ng-if="iten.CONFERENCIA == 1 && item.STATUS == 1" ng-click="vm.Lote.excluirItem(iten)">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>                       
                                </tr>

                                <tr ng-repeat-end ng-if="false"></tr>             
                            </tbody>            
                        </table>
                    </div>
                </div>
            </div>   
        </div>
    </fieldset>

    @include('estoque._15080.index.reposicao.modal')
    @include('estoque._15080.index.lote.modal-iniciar')
    @include('estoque._15080.index.lote.modal-continuar')

</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/direct-print.js') }}"></script>
    <script src="{{ elixir('assets/js/_15080.js') }}"></script>
@append
