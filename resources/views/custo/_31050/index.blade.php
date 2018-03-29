@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31050.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31050.css') }}" />
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
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.RateioTipo.SELECTED.UM_ID == undefined || vm.RateioTipo.ALTERANDO" 
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
    
 
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr gc-order-by="vm.RateioTipo.ORDER_BY">
                    <th field="ID*1">Tipo</th>                    
                    <th field="DATA_FINAL*1" class="text-center">Período</th>
                    <th field="UM_DESCRICAO" ttitle="Unidade de Medida">Unid. Med.</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.RateioTipo.DADOS_RENDER = ( vm.RateioTipo.DADOS | filter : { EXCLUIDO : false } | orderBy : vm.RateioTipo.ORDER_BY )"
                    ng-click="vm.RateioTipo.SELECTED = item"
                    ng-focus="vm.RateioTipo.SELECTED = item"
                    ng-class="{ 'selected' : vm.RateioTipo.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.RateioTipo.alterar()"
                    tabindex="0"
                    >
                    <td>
                        <span>
                            @{{ item.ID || 0 | lpad: [4,0] }} - @{{ item.DESCRICAO }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.DATA_INICIAL_MODEL | date : 'dd/MM/yyyy' }} - @{{ item.DATA_FINAL_MODEL || 'Data atual' | date : 'dd/MM/yyyy' }}
                        </span>
                    </td>
                    <td>
                        <span>
                            @{{ item.UM_DESCRICAO }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @include($menu.'.modal-rateio-tipo.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31050.js') }}"></script>
@append
