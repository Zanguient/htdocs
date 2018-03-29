@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31040.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31040.css') }}" />
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
                ng-click="vm.RateioTipo.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.RateioTipo.SELECTED.TIPO_ID == undefined || vm.RateioTipo.ALTERANDO" 
                ng-click="vm.RateioTipo.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.RateioTipo.SELECTED.TIPO_ID == undefined || vm.RateioTipo.ALTERANDO" 
                ng-click="vm.RateioTipo.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                                          
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                ng-click="vm.RateioTipo.confirmar()">
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
                <label>Tipo:</label>           
                <select ng-model="vm.RateioTipo.TIPO" autofocus>
                    <option
                        ng-repeat="tipo in vm.RateioTipo.TIPOS_DETALHES | orderBy : ['TIPO_ID']"
                        ng-value="tipo"
                        >@{{ tipo.TIPO_ID }} - @{{ tipo.TIPO_DESCRICAO }}</option>
                </select>    
            </div>             

        </div>                
    </form>    
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.RateioTipo.ORDER_BY">
                    <th field="CCUSTOA">C. Custo</th>                    
                    <th field="VALOR*1" class="text-right">@{{ vm.RateioTipo.TIPO.UM_DESCRICAO }}</th>
                    <th ng-if="vm.RateioTipo.TIPO.UM != '%'" class="text-right">%</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.RateioTipo.DADOS_RENDER = ( vm.RateioTipo.TIPO.CCUSTOS | filter : { EXCLUIDO : false } | orderBy : vm.RateioTipo.ORDER_BY )"
                    ng-click="vm.RateioTipo.SELECTED = item"
                    ng-focus="vm.RateioTipo.SELECTED = item"
                    ng-class="{ 'selected' : vm.RateioTipo.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.RateioTipo.alterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            <span style="float: left; width: 70px;">@{{ item.CCUSTO_MASK }}</span> @{{ item.CCUSTO_DESCRICAO }}
                        </span>
                    </td>
                    <td class="text-right text-lowercase">
                        <span>
                            @{{ item.VALOR | number : 4 }} @{{ item.UM }}
                        </span>
                    </td>
                    <td ng-if="vm.RateioTipo.TIPO.UM != '%'" class="text-right">
                        <span>
                            @{{ (item.VALOR / vm.RateioTipo.TIPO.VALOR_TOTAL) * 100 | number : 2 }} %
                        </span>
                    </td>
                </tr>
            </tbody>
            <tfoot style="font-size:12px">
                <tr>
                    <td>Totalizador</td>
                    <td class="text-right text-lowercase">@{{ vm.RateioTipo.totalizador() | number : 4 }} @{{ vm.RateioTipo.TIPO.UM }}</td>
                    <td ng-if="vm.RateioTipo.TIPO.UM != '%'" class="text-right">100,00 %</td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @include($menu.'.modal-rateio-tipo.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31040.js') }}"></script>
@append
