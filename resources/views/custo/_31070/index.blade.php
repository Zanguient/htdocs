@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31070.css') }}" />
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
                ng-click="vm.Incentivo.modalIncluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Incentivo.SELECTED.ID == undefined || vm.Incentivo.ALTERANDO" 
                ng-click="vm.Incentivo.modalAlterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.Incentivo.SELECTED.ID == undefined || vm.Incentivo.ALTERANDO" 
                ng-click="vm.Incentivo.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
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
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.Incentivo.ORDER_BY">
                    <th field="ID">ID</th> 
                    <th field="DESCRICAO">Descrição</th> 
                    <th class="text-right" field="PERCENTUAL">%</th> 
                    <th class="text-right" field="PERCENTUAL_IR">% IR</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Incentivo.DADOS | orderBy:vm.Incentivo.ORDER_BY"
                    ng-click="vm.Incentivo.SELECTED = item"
                    ng-focus="vm.Incentivo.SELECTED = item"
                    ng-class="{ 'selected' : vm.Incentivo.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.Incentivo.modalAlterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESCRICAO}}
                        </span>
                    </td>
                    <td  class="text-right" >
                        <span>
                            @{{item.PERCENTUAL}}
                        </span>
                    </td>
                    <td  class="text-right" >
                        <span>
                            @{{item.PERCENTUAL_IR}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    
    @include('custo._31070.modal_incluir')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31070.js') }}"></script>
@append
