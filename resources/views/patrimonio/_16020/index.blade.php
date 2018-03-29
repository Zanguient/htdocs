@extends('master')

@section('titulo')
    {{ Lang::get('patrimonio/_16020.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/16020.css') }}" />
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
                ng-click="vm.Tipo.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Tipo.SELECTED.EXCLUIDO || vm.Tipo.SELECTED.DESCRICAO == undefined || vm.Tipo.ALTERANDO" 
                ng-click="vm.Tipo.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.Tipo.SELECTED.EXCLUIDO || vm.Tipo.SELECTED.DESCRICAO == undefined || vm.Tipo.ALTERANDO" 
                ng-click="vm.Tipo.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                                          
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Tipo.ALTERANDO" 
                ng-click="vm.Tipo.confirmar()">
                <span class="glyphicon glyphicon-ok"></span> Confirmar Alterações
            </button>
        </li>                       
	</ul>  
    
 
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr ng-init="vm.Tipo.ORDER_BY = 'DESCRICAO'" gc-order-by="vm.Tipo.ORDER_BY">
                    <th field="DESCRICAO">Tipo</th>      
                    <th field="TAXA_DEPRECIACAO" class="text-right" ttitle="Percentual de depreciação ao ano">Taxa Depr.%</th>              
                    <th field="VIDA_UTIL*1" class="text-right" ttitle="Vida útil em anos">Vida Útil</th>
                    <th field="CCONTABIL_DESCRICAO" ttitle="Conta Contábil">C.Contabil Crédito</th>
                    <th field="CCONTABIL_DEBITO_DESCRICAO" ttitle="Conta Contábil">C.Contabil Débito</th>
                    <th field="TIPO_GASTO">Tipo Gasto</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Tipo.DADOS_RENDER = ( vm.Tipo.DADOS | filter : { EXCLUIDO : false } | orderBy : vm.Tipo.ORDER_BY )"
                    ng-click="vm.Tipo.SELECTED = item"
                    ng-focus="vm.Tipo.SELECTED = item"
                    ng-class="{ 'selected' : vm.Tipo.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->ALTERAR }} == 1 && vm.Tipo.alterar()"
                    ng-keydown="$event.key == 'Enter' && {{ userMenu($menu)->ALTERAR }} == 1 && vm.Tipo.alterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID || 0 | lpad: [4,0] }} - @{{ item.DESCRICAO }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span>
                            @{{ item.TAXA_DEPRECIACAO * 100 | number : 4 }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span>
                            @{{ item.VIDA_UTIL | number : 4 }} anos
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.CCONTABIL_MASK }} - @{{ item.CCONTABIL_DESCRICAO }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.CCONTABIL_DEBITO_MASK }} - @{{ item.CCONTABIL_DEBITO_DESCRICAO }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.TIPO_GASTO_SELECT.ID | lpad : [2,0] }} - @{{ item.TIPO_GASTO_SELECT.DESCRICAO }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @include($menu.'.modal-tipo.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_16020.js') }}"></script>
@append
