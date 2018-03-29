@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31020.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31020.css') }}" />
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
                ng-click="vm.RateioCCusto.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.RateioCCusto.SELECTED.ABRANGENCIA == undefined || vm.RateioCCusto.ALTERANDO" 
                ng-click="vm.RateioCCusto.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.RateioCCusto.SELECTED.ABRANGENCIA == undefined || vm.RateioCCusto.ALTERANDO" 
                ng-click="vm.RateioCCusto.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-info" 
                data-hotkey="f9"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.RateioCCusto.processarOrdem()">
                <span class="glyphicon glyphicon-tasks"></span> Processar Ordem
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                ng-click="vm.RateioCCusto.confirmar()">
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
                    <label class="lbl" ng-repeat="grupo in vm.RateioCCusto.RATEAMENTO_GRUPOS">
                        <input 
                            type="radio" 
                            ng-click="vm.RateioCCusto.RATEAMENTO_GRUPO = grupo"
                            ng-checked="vm.RateioCCusto.RATEAMENTO_GRUPO == grupo">
                        <span>@{{ grupo.RATEAMENTO_GRUPO_DESCRICAO }}</span>
                    </label>
                </div>
            </div>             

        </div>                
    </form>    
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 250px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr>
                    <th class="text-right" ttitle="Sequência e Subsequência">Seq.</th>
                    <th>C. Custo</th>
                    <th>Tipo Rateio</th>
                    <!--<th>Origem</th>-->
                    <!--<th>Grupo</th>-->
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.RateioCCusto.DADOS_RENDER = ( vm.RateioCCusto.RATEAMENTO_GRUPO.CCUSTOS | filter : { EXCLUIDO : false } | orderBy : ['ABRANGENCIA*1','ORDEM*1'] )"
                    ng-click="vm.RateioCCusto.SELECTED = item"
                    ng-focus="vm.RateioCCusto.SELECTED = item"
                    ng-class="{ 'selected' : vm.RateioCCusto.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.RateioCCusto.alterar()"
                    tabindex="0"
                    >
                    <td class="text-right"> 
                        <span>
                            @{{ item.ABRANGENCIA == undefined ? 0 : item.ABRANGENCIA | lpad : [3,0]  }}.@{{ item.ORDEM == undefined ? 0 : item.ORDEM | lpad : [2,0] }}
                        </span>
                        
                    </td>
                    <td>
                        <span>
                            <span style="float: left; width: 70px;">@{{ item.CCUSTO_MASK }}@{{ item.HIERARQUIA == 1 ? '*' : '' }}</span> @{{ item.CCUSTO_DESCRICAO }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.TIPO_ID | lpad:[4,0] }} - @{{ item.TIPO_DESCRICAO }}
                        </span>
                    </td>
<!--                    <td>
                        <span>
                            @{{ item.VALOR_ORIGEM_DESCRICAO }}
                        </span>
                    </td>-->
<!--                    <td>
                        <span>
                            @{{ item.RATEAMENTO_GRUPO_DESCRICAO }}
                        </span>
                    </td>-->
                </tr>
            </tbody>
        </table>
    </div>
    <ul class="legenda" style="font-size: 11px;">
        <li>
            Legenda:
        </li>
        <li>
            " * " Abrange toda a hierarquia do C. Custo
        </li>
    </ul>  
    
    @include($menu.'.modal-rateio-ccusto.index')
    @include($menu.'.modal-ccusto-absorcao.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31020.js') }}"></script>
@append
