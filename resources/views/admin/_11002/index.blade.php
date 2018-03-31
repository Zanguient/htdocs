@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11002.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">    
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }} || vm.ConsultaFamilia.item.selected == false" 
                ng-click="vm.Index.modalIncluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Index.SELECTED.ID == undefined || vm.Index.ALTERANDO || vm.ConsultaFamilia.item.selected == false" 
                ng-click="vm.Index.modalAlterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.Index.SELECTED.ID == undefined || vm.Index.ALTERANDO || vm.ConsultaFamilia.item.selected == false" 
                ng-click="vm.Index.excluir()">
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
    

    <div style="width: 260px;" class="consulta-familia"></div>

    <br>
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 305px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.Index.ORDER_BY">
                    <th field="ID">ID</th> 
                    <th field="USUARIO">Usuário</th>
                    <th field="NOME">Nome</th>
                    <th field="STATUS">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Index.DADOS | orderBy:vm.Index.ORDER_BY"
                    ng-click="vm.Index.SELECTED = item"
                    ng-focus="vm.Index.SELECTED = item"
                    ng-class="{ 'selected' : vm.Index.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.Index.modalAlterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.USUARIO}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.NOME}}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{item.DESC_STATUS}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @include('admin._11002.modal_incluir')
    @include('admin._11002.modal_incluir_itens')

</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11002.js') }}"></script>
@append
