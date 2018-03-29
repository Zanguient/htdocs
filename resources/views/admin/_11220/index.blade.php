@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11220.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11220.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>
    
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
    
    <form class="form-inline" ng-submit="vm.PeriodoSumbit()">
        <div class="table-filter collapse in form-filtro" aria-expanded="true">

                
            <div class="form-group filtro-periodo">
                <style>
                    .input-group:not(.left-icon) input:not(.input-small):not(.input-menor):not(.input-maior):not(.input-maior-min):not(.filtro-obj):not(.input-resize) {
                        width: calc(100% - 27px) !important;
                    }                    
                </style>
                <label>Alterar Período dos Módulos Visíveis:</label>
                <div class="input-group">
                    <input 
                        ng-model="vm.Filtro.DATA_1" 
                        max="@{{ vm.Filtro.DATA_2 | date: 'yyyy-MM-dd' }}"
                        required
                        toDate
                        type="date" 
                        class="form-control" 
                        required />
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>      
                à
                <div class="input-group">
                    <input 
                        ng-model="vm.Filtro.DATA_2" 
                        required
                        toDate
                        type="date" 
                        class="form-control" 
                        required />                
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>            

            </div>

            <button type="submit" class="btn btn-xs btn-success btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-ok"></span> Confirmar
            </button>
        </div>      
    </form>
    
        
    
    
    <div class="tables" style="min-width: 920px;">
        <div 
            class="table-ec table-scroll" 
            style="
                height: calc(100vh - 205px);
                float: left;
                min-width: 250px;
                margin-right: 10px;                
            ">
            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                <thead>
                    <tr>
                        <th>Estabelecimento</th>  
                    </tr>
                </thead>
                <tbody>
                    <tr
                        ng-click="vm.Estabelecimento.SELECTED = undefined"
                        ng-focus="vm.Estabelecimento.SELECTED = undefined"
                        ng-class="{ 'selected' : vm.Estabelecimento.SELECTED == undefined }"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                Todos
                            </span>
                        </td>
                    </tr>
                    <tr 
                        ng-repeat="item in vm.ESTABELECIMENTOS | orderBy : vm.ESTABELECIMENTO_ORDER_BY"
                        ng-click="vm.Estabelecimento.SELECTED = item"
                        ng-focus="vm.Estabelecimento.SELECTED = item"
                        ng-class="{ 'selected' : vm.Estabelecimento.SELECTED == item }"
                        ng-dblclick="{{ userMenu($menu)->ALTERAR }} == 1 && vm.Estabelecimento.alterar()"
                        ng-keydown="$event.key == 'Enter' && {{ userMenu($menu)->ALTERAR }} == 1 && vm.Estabelecimento.alterar()"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                @{{ item.ID || 0 | lpad: [3,0] }} - @{{ item.NOMEFANTASIA }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form class="form-inline" ng-submit="vm.Modulo.gravar()">
        <div 
            class="table-ec table-scroll" 
            style="
                height: calc(100vh - 205px);
                float: left;
                min-width: 250px;
                margin-right: 10px;                
            ">
            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                <thead>
                    <tr>
                        <th>Módulo</th>  
                        <th></th>  
                    </tr>
                </thead>
                <tbody>
                    <tr
                        ng-click="vm.Modulo.SELECTED = undefined"
                        ng-focus="vm.Modulo.SELECTED = undefined"
                        ng-class="{ 'selected' : vm.Modulo.SELECTED == undefined }"
                        tabindex="0"
                        >
                        <td colspan="2">
                            <span>
                                Todos
                            </span>
                        </td>
                    </tr>
                    <tr 
                        ng-repeat="item in vm.MODULOS | orderBy : vm.MODULO_ORDER_BY"
                        ng-click="vm.Modulo.SELECTED = item"
                        ng-focus="vm.Modulo.SELECTED = item"
                        ng-class="{ 'selected' : vm.Modulo.SELECTED == item }"
                        ng-dblclick="{{ userMenu($menu)->ALTERAR }} == 1 && vm.Modulo.alterar()"
                        ng-keydown="$event.key == 'Enter' && {{ userMenu($menu)->ALTERAR }} == 1 && vm.Modulo.alterar()"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                @{{ item.ID || 0 | lpad: [3,0] }} - @{{ item.DESCRICAO }}
                            </span>
                        </td>
                        <td class="text-center" style="padding: 2px;">
                            <button tabindex="-1" type="button" data-consulta-historico data-tabela="TBMODULO" data-tabela-id="@{{ item.ID }}"  class="btn btn-xs">
                                Histórico
                            </button>
                        </td>
                    </tr>
                    <tr ng-if="vm.Modulo.INSERINDO != true && {{ $permissaoMenu->INCLUIR }} == 1">
                        <td class="text-center" colspan="2" style="padding: 2px;">
                            
                            <button ng-click="vm.Modulo.INSERINDO = true" tabindex="-1" type="button" class="btn btn-default btn-xs">
                                Incluir Módulo
                            </button>
                        </td>
                    </tr>
                    <tr ng-if="vm.Modulo.INSERINDO == true">
                        <td class="text-center" style="padding: 2px;">
                            
                            <input type="text" ng-model="vm.Modulo.DESCRICAO" placeholder="Descrição"  style="
                                padding: 0 3px;
                                margin: 0;
                                height: 22px;
                                width: 100%;
                                " required/>
                        </td>
                        <td class="text-center" style="padding: 2px;">
                            <button tabindex="-1" type="submit" class="btn btn-success btn-xs">
                                Gravar
                            </button>
                        </td>     
                    </tr>
                </tbody>
            </table>
        </div>

        </form>

        <div 
            class="table-ec table-scroll" 
            style="
                height: calc(100vh - 205px);        
            ">
            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                <thead>
                    <tr ng-init="vm.PERIODO_ORDER_BY = ['ESTABELECIMENTO_ID','MODULO_ID','DATAINICIAL']">
                        <th >Estab.</th>  
                        <th >Mód.</th>  
                        <th>Período</th>  
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        ng-repeat="item in vm.PERIODO_FILTERED = (vm.PERIODOS | filter : vm.PeriodoFilter)"
                        ng-click="vm.Periodo.SELECTED = item"
                        ng-focus="vm.Periodo.SELECTED = item"
                        ng-class="{ 'selected' : vm.Periodo.SELECTED == item }"
                        ng-dblclick="{{ userMenu($menu)->ALTERAR }} == 1 && vm.Periodo.alterar()"
                        ng-keydown="$event.key == 'Enter' && {{ userMenu($menu)->ALTERAR }} == 1 && vm.Periodo.alterar()"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                @{{ item.ESTABELECIMENTO_ID | lpad: [3,0] }} - @{{ item.ESTABELECIMENTO_SELECT.NOMEFANTASIA }}
                            </span>
                        </td>
                        <td>
                            <span>
                                @{{ item.MODULO_ID | lpad: [3,0] }} - @{{ item.MODULO_SELECT.DESCRICAO }}
                            </span>
                        </td>
                        <td class="text-normal">
                            <span ng-if="item.DATAINICIAL != null">
                                @{{ item.DATAINICIAL  | toDate | date:'dd/MM/yyyy' : '+0'  }} à @{{ item.DATAFINAL  | toDate | date:'dd/MM/yyyy' : '+0'  }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
        
    @include($menu.'.modal-tipo.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11220.js') }}"></script>
@append
