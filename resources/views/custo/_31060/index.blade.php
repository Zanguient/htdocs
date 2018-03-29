@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31060.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31060.css') }}" />
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
                ng-click="vm.Regra.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Regra.SELECTED.FAMILIA_PRODUCAO == undefined || vm.Regra.ALTERANDO" 
                ng-click="vm.Regra.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.Regra.SELECTED.FAMILIA_PRODUCAO == undefined || vm.Regra.ALTERANDO" 
                ng-click="vm.Regra.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                                          
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                ng-click="vm.Regra.confirmar()">
                <span class="glyphicon glyphicon-ok"></span> Confirmar Alterações
            </button>
        </li>                       
	</ul>  
    
 
    
    <div class="table-ec table-scroll" style="height: calc(100vh - 205px);">
        <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
            <thead>
                <tr>
                    <th ttitle="Agrupamento da Família">Agrup. Fam.</th>  
                    <th ttitle="Sequência">Seq.</th>  
                    <th>Família</th>  
                    <th ttitle="Grupo de Produção">GP</th>  
                    <th ttitle="Perfil UP">Perfil UP</th>  
                    <th ttitle="Unidade Produtiva Primária"> 1ª UP.</th>  
                    <th ttitle="Unidade Produtiva Secundária">2ª UP</th>  
                    <th ttitle="Habilita cálculo de rebobinamento">Rebob.</th>  
                    <th ttitle="Habilita cálculo da conformação">Confor.</th>  
                    <th ttitle="Centro de Custo">C. Custo</th>
                    <th class="text-right" ttitle="Fator de Conversão">Fator Conv.</th>  
                    <th class="text-center" ttitle="Número de remessas para contabilização de defeitos">Rem. Def.</th>  
                </tr>
            </thead>
            <tbody>
                <tr 
                    ng-repeat="item in vm.Regra.DADOS_RENDER = ( vm.Regra.DADOS | filter : { EXCLUIDO : false } | orderBy: ['FAMILIA_PRODUCAO_DESCRICAO','SEQUENCIA*1'] | orderBy : vm.Regra.ORDER_BY )"
                    ng-click="vm.Regra.SELECTED = item"
                    ng-focus="vm.Regra.SELECTED = item"
                    ng-class="{ 'selected' : vm.Regra.SELECTED == item }"
                    ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.Regra.alterar()"
                    tabindex="0"
                    >           
                    <td>@{{ item.FAMILIA_PRODUCAO || 0 | lpad: [3,0] }} - @{{ item.FAMILIA_PRODUCAO_DESCRICAO }}</td>
                    <td>@{{ item.SEQUENCIA  || 0 | lpad: [2,0] }}</td>      
                    <td>@{{ item.FAMILIA_ID || 0 | lpad: [3,0] }} - @{{ item.FAMILIA_DESCRICAO }}</td>
                    <td>@{{ item.GP_ID      || 0 | lpad: [3,0] }} - @{{ item.GP_DESCRICAO }}</td>
                    <td>@{{ item.PERFIL_UP }} - @{{ item.PERFIL_UP_DESCRICAO }}
                    <td>@{{ item.UP_PADRAO1 || 0 | lpad: [3,0] }} - @{{ item.UP_PADRAO1_DESCRICAO }}</td>
                    <td>@{{ item.UP_PADRAO2 || 0 | lpad: [3,0] }} - @{{ item.UP_PADRAO2_DESCRICAO }}</td>
                    <td>@{{ item.CALCULO_REBOBINAMENTO_DESCRICAO }}</td>
                    <td>@{{ item.CALCULO_CONFORMACAO_DESCRICAO }}</td>
                    <td><span style="float: left; width: 70px;">@{{ item.CCUSTO_MASK }}@{{ item.CCUSTO_HIERARQUIA == 1 ? '*' : '' }}</span> - @{{ item.CCUSTO_DESCRICAO }}</td>
                    <td class="text-right" >@{{ item.FATOR | number : 2 }}</td>
                    <td class="text-center">@{{ item.REMESSAS_DEFEITO }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @include($menu.'.modal-regra.index')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_31060.js') }}"></script>
@append
