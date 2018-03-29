@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22120.titulo') }}
@endsection

@section('estilo')

    <link rel="stylesheet" href="{{ elixir('assets/css/22120.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-init="vm.remessaAction.Filtrar()" ng-cloak>
    
    
    <style>
        #form-filtro {
            background: rgba(221,221,221,.33);
            padding: 2px 10px 7px;
            border-radius: 5px
        }

        #form-filtro .consulta-container {
            margin-right: initial;
            margin-bottom: initial
        }

        #form-filtro input {
            width: calc(100% - 27px)!important
        }

        #form-filtro .label-checkbox {
            top: 9px
        }

        #form-filtro [type=submit] {
            margin-top: 16px
        }    

        #form-filtro .check-group {
            padding: 0 0 4px 10px;
            border-radius: 6px;
            background: rgb(226, 226, 226);
            margin-top: -1px;
        }

        #form-filtro .check-group .lbl {
            display: inline-block;
            margin-right: 10px;
        }

        #form-filtro .check-group .lbl input[type="checkbox"], 
        #form-filtro .check-group .lbl input[type="radio"] {
            margin-top: 0;
            margin-bottom: 0;
            top: 5px;
            position: relative;
            width: 20px!important;
            height: 20px;
            vertical-align: baseline;
            box-shadow: none;
        }

        #form-filtro .check-group .lbl [checked] ~ span {
            font-weight: bold;
        }

    </style>
    <form class="form-inline" ng-submit="vm.RemessaIntermediaria.consultarTaloesVinculo()">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">             




            <div class="form-group">
                <label>Remessa Principal:</label>
                <div class="input-group">
                    <input 
                        type="text" 
                        id="remessa" 
                        class="form-control" 
                        required 
                        autofocus 
                        autocomplete="off" 
                        ng-readonly="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED"
                        ng-model="vm.RemessaIntermediaria.FILTRO.REMESSA"
                        />

                    <button 
                        ng-if="!vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED" 
                        ng-click="vm.RemessaIntermediaria.FILTRO.REMESSA.trim().length > 0 ? vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED = true : ''" 
                        type="button" 
                        class="input-group-addon btn-filtro" 
                        tabindex="-1"
                        >
                        <span class="glyphicon glyphicon-triangle-right"></span>
                    </button>
                    <button 
                        ng-if="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED" 
                        ng-click="vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED = false; vm.RemessaIntermediaria.FILTRO.REMESSA = ''" 
                        type="button" 
                        class="input-group-addon btn-filtro" 
                        tabindex="-1"
                        >
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>                    

            <div class="consulta-remessa-vinculo"></div>
            <div class="consulta-gp"></div>


            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>
        </div>                
    </form> 
</div>
@endsection

@section('script')

    <script src="{{ elixir('assets/js/_22120.remessa-componente.ng.js') }}"></script>
@append
