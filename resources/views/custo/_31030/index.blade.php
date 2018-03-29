@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31030.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31030.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">    
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.RateioCContabil.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.RateioCContabil.SELECTED.REGRA_RATEAMENTO == undefined || vm.RateioCContabil.ALTERANDO" 
                ng-click="vm.RateioCContabil.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.RateioCContabil.SELECTED.REGRA_RATEAMENTO == undefined || vm.RateioCContabil.ALTERANDO" 
                ng-click="vm.RateioCContabil.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                                          
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                ng-click="vm.RateioCContabil.confirmar()">
                <span class="glyphicon glyphicon-ok"></span> Confirmar Alterações
            </button>
        </li>                       
	</ul>  
    

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
    <form class="form-inline" ng-submit="vm.Remessa.consultar()">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
            <div class="form-group">
                <label>Grupo:</label>             
                <div class="check-group">
                    <label class="lbl" ng-repeat="grupo in vm.RateioCContabil.RATEAMENTO_GRUPOS">
                        <input 
                            type="radio" 
                            ng-click="vm.RateioCContabil.RATEAMENTO_GRUPO = grupo"
                            ng-checked="vm.RateioCContabil.RATEAMENTO_GRUPO == grupo">
                        <span>@{{ grupo.RATEAMENTO_GRUPO_DESCRICAO }}</span>
                    </label>
                </div>
            </div>             

        </div>                
    </form>    
        
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.RateioCContabil.ORDER_BY">
                    <th field="CCONTABIL*1">C. Contábil</th>
                    <th field="TIPO_ID*1">Tipo</th>
                    <!--<th field="REGRA_RATEAMENTO_DESCRICAO">Regra</th>-->
                    <th field="VALOR_ORIGEM_DESCRICAO">Origem</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.RateioCContabil.DADOS_RENDER = ( vm.RateioCContabil.RATEAMENTO_GRUPO.CCONTABILS | filter : { EXCLUIDO : false } | orderBy : vm.RateioCContabil.ORDER_BY )"
                    ng-click="vm.RateioCContabil.SELECTED = item"
                    ng-focus="vm.RateioCContabil.SELECTED = item"
                    ng-class="{ 'selected' : vm.RateioCContabil.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.RateioCContabil.alterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            <span style="float: left; width: 93px;">@{{ item.CCONTABIL_MASK }}@{{ item.HIERARQUIA == 1 ? '*' : '' }}</span> @{{ item.CCONTABIL_DESCRICAO }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.TIPO_ID }} - @{{ item.TIPO_DESCRICAO }}
                        </span>
                    </td>
<!--                    <td>
                        <span>
                            @{{ item.REGRA_RATEAMENTO_DESCRICAO }}
                        </span>
                    </td>-->
                    <td>
                        <span>
                            @{{ item.VALOR_ORIGEM_DESCRICAO }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @include($menu.'.modal-rateio-ccontabil.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31030.js') }}"></script>
@append
