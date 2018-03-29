@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/16010.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('patrimonio/_16010.titulo') }}
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

    <input type="hidden" ng-init="'{{ $id }}' > 0 ?  vm.Imobilizado.visualizar('{{ $id }}') : ''" />
    
	<ul class="list-inline acoes">    
		<li>
            <button ng-disabled="!{{ userMenu($menu)->INCLUIR }}" ng-click="vm.Imobilizado.incluir()" type="button" class="btn btn-primary btn-incluir" data-hotkey="f6">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>            
		<li>
            <button type="button" class="btn btn-info" data-hotkey="alt+d" data-toggle="modal" data-target="#modal-demonstrativo-depreciacao-anual">
                <span class="fa fa-table"></span> Demonstrativo de Depreciação Anual
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
<!--    <form class="form-inline" ng-submit="vm.Imobilizado.consultar()">
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
            <div class="form-group">
                <label>Tipo:</label>             
                <div class="check-group">
                    <label class="lbl">
                        <input 
                            type="radio" 
                            ng-click="vm.Imobilizado.CONSUMO_PERCENTUAL = '< 1'; vm.Imobilizado.DATA_1 = '01.01.1989'; vm.Imobilizado.DATA_2 = '01.01.2500';"
                            ng-checked="vm.Imobilizado.CONSUMO_PERCENTUAL == '< 1'">
                        <span>Pendente</span>
                    </label>
                    <label class="lbl">
                        <input 
                            type="radio" 
                            ng-click="vm.Imobilizado.CONSUMO_PERCENTUAL = '>= 1'"
                            ng-checked="vm.Imobilizado.CONSUMO_PERCENTUAL == '>= 1'">
                        <span>Completa</span>
                    </label>
                </div>
            </div>             

            <div class="form-group">
                <label>Famílias de Produto:</label>             
                <div class="check-group">
                    <label class="lbl" ng-repeat="familia in vm.Imobilizado.FAMILIAS | orderBy: 'REMESSA_FAMILIA_DESCRICAO'">
                        <input 
                            type="checkbox" 
                            ng-click="vm.Imobilizado.toggleCheckFamilia(familia)"
                            ng-checked="familia.CHECKED">
                        <span>@{{ familia.REMESSA_FAMILIA_DESCRICAO }}</span>
                    </label>
                </div>
            </div>             

            <div class="form-group" ng-if="vm.Imobilizado.CONSUMO_PERCENTUAL == '>= 1'">
                <label title="Data para produção da remessa">Data Inicio:</label>
                <div class="input-group">
                    <input type="date" ng-model="vm.Imobilizado.DATA_1" toDate max="@{{ vm.Imobilizado.DATA_2 | date: 'yyyy-MM-dd' }}" class="form-control" required />
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>
            <div class="form-group" ng-if="vm.Imobilizado.CONSUMO_PERCENTUAL == '>= 1'">
                <label title="Data para produção da remessa">Data Fim:</label>
                <div class="input-group">
                    <input type="date" ng-model="vm.Imobilizado.DATA_2" toDate id="data-prod" class="form-control" required />
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>                    

            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>
        </div>                
    </form>    
    -->
    
    <style>
        .level-1 {
            background: rgb(251, 242, 210) !important;
        }
        
        .level-2 {
            background: rgb(211, 234, 226) !important;
        }
        
        .level-3 {
            background: rgb(226, 208, 199) !important;
        }
        
        
        .level-4 {
            background: rgb(218, 215, 230) !important;
        }
        
        
        .level-5 {
            background: rgb(206, 216, 206) !important;
        }
        
    </style>
    
    <input 
        type="text" 
        class="form-control fast-filter-table" 
        ng-init="vm.Imobilizado.M_FILTRO = ''" 
        ng-model="vm.Imobilizado.M_FILTRO" 
        placeholder="Filtragem rápida..."
        style="margin-bottom: 3px; height: 23px;"    
        >
    <div class="table-ec" style="height: calc(100vh - 165px);">
        <table class="table table-striped table-bordered table-condensed table-hover table-remessa table-no-break tabela-itens-imobilizado" >
            <thead>
                <tr>
                    <!--<th class="">Status</th>-->
                    <th colspan="3" class="" style="width: 20%;">C. Custo</th>
                    <th colspan="2" class="" style="width: 35%;">Imobilizado</th>
                    <th class="text-right">Valor Total</th>
                    <th class="text-right" ttitle="Valor total restante a depreciar">Saldo Atual</th>
                    <th class="text-right">Saldo Mês</th>
<!--                    <th class="" style="width: 20%;">Tipo</th>
                    <th class="text-right" ttitle="Taxa de depreciação ao ano" style="width: 5%;">Taxa</th>-->
                    <!--<th class="text-right" ttitle="Tempo para depreciação total do imobilizado">Vida Útil</th>-->
                    <th colspan="2" class="" style="width: 20%;">Observação</th>
                </tr>               
            </thead>
            
            <tbody>
                <tr 
                    ng-repeat-start="status in vm.Imobilizado.ARR_STATUS | orderBy : ['STATUS']" 
                    ng-click="status.OPENED = status.OPENED != true ? true : false"
                    ng-show="status.VISIBLE"
                    data-execute="@{{ vm.checkVisible(status,'ANOS') }}"
                    class="level-1"
                    >
                    <td colspan="5" >@{{ status.STATUS_DESCRICAO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ status.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ status.SALDO | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ status.SALDO_MES | number : 2 }}</td>
                    <td colspan="2"</td>
                </tr>
                
                <tr 
                    ng-repeat-start="ano in status.ANOS_FILTERED = (status.ANOS | orderBy : ['-ANO*1'])" 
                    ng-click="ano.OPENED = ano.OPENED != true ? true : false"
                    ng-show="status.OPENED && ano.VISIBLE"
                    data-execute="@{{ vm.checkVisible(ano,'MESES') }}"
                    class="level-2"
                    >
                    <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;@{{ ano.ANO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ ano.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ ano.SALDO | number : 2 }}</td>                    
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ ano.SALDO_MES | number : 2 }}</td>                    
                    <td
                        colspan="2"
                    </td>
                </tr>
                
                <tr 
                    ng-repeat-start="mes in ano.MESES_FILTERED = (ano.MESES | orderBy : ['-MES*1'])" 
                    ng-click="mes.OPENED = mes.OPENED != true ? true : false"
                    ng-show="status.OPENED && ano.OPENED && mes.VISIBLE"
                    data-execute="@{{ vm.checkVisible(mes,'TIPOS') }}"
                    class="level-3"
                    >
                    <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{ mes.MES_DESCRICAO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ mes.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ mes.SALDO | number : 2 }}</td>                    
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ mes.SALDO_MES | number : 2 }}</td>                    
                    <td
                        colspan="2"
                    </td>
                </tr>
                
                <tr 
                    ng-repeat-start="tipo in mes.TIPOS_FILTERED = (mes.TIPOS | orderBy : ['TIPO_DESCRICAO'])" 
                    ng-click="tipo.OPENED = tipo.OPENED != true ? true : false"
                    ng-show="status.OPENED && ano.OPENED && mes.OPENED && tipo.VISIBLE"
                    data-execute="@{{ vm.checkVisible(tipo,'NFS_FILTERED') }}"
                    class="level-4"
                    >
                    <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{ tipo.TIPO_DESCRICAO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ tipo.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ tipo.SALDO | number : 2 }}</td>                    
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ tipo.SALDO_MES | number : 2 }}</td>                    
                    <td class="text-right text-lowercase">@{{ tipo.TAXA * 100 | number: 2 }}% a.a</td>
                    <td class="text-right text-lowercase">@{{ (1 / tipo.TAXA) * 12 | number }} meses</td>                    
                </tr>
                
                <tr
                    ng-repeat-start="nf in tipo.NFS_FILTERED = (tipo.NFS | orderBy : ['-NFE_ID*1'])" 
                    ng-click="nf.OPENED = nf.OPENED != true ? true : false"
                    ng-show="status.OPENED && ano.OPENED && mes.OPENED && tipo.OPENED && nf.VISIBLE"
                    data-execute="@{{ nf.VISIBLE = nf.IMOBILIZADOS_FILTERED.length == 0 ? false : true }}"
                    class="level-5"
                    >
                    <td ng-if="nf.NFE_ID > 0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{ nf.EMPRESA_RAZAOSOCIAL }}</td>
                    <td ng-if="nf.NFE_ID > 0" class="text-right">@{{ nf.DOC_FISCAL }}</td>
                    <td ng-if="nf.NFE_ID > 0">@{{ nf.NFE_DATA_ENTRADA_TEXT }}</td>
                    <td ng-if="!(nf.NFE_ID > 0)" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{ nf.EMPRESA_RAZAOSOCIAL }}</td>
                    <td colspan="2"></td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ nf.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ nf.SALDO | number : 2 }}</td>   
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ nf.SALDO_MES | number : 2 }}</td>                                     
                    <td colspan="2"></td>
                </tr>
                
                <tr 
                    tabindex="0" 
                    ng-repeat="imobilizado in nf.IMOBILIZADOS_FILTERED = (nf.IMOBILIZADOS
                    | find: {
                        model : vm.Imobilizado.M_FILTRO,
                        fields : [
                            'ID',
                            'DESCRICAO',
                            'OBSERVACAO',
                            'CCUSTO',
                            'CCUSTO_MASK',
                            'CCUSTO_DESCRICAO',
                            'TIPO_DESCRICAO',
                            'EMPRESA_RAZAOSOCIAL',
                            'DOC_FISCAL',
                            'MES_DESCRICAO',
                            'ANO',
                            'STATUS_DESCRICAO'
                        ]
                    }
                    | orderBy : ['IMOBILIZADO_DESCRICAO'])" 
                    ng-show="status.OPENED && ano.OPENED && mes.OPENED && tipo.OPENED && nf.OPENED"
                    ng-dblclick="vm.Imobilizado.SELECTED = imobilizado; vm.Imobilizado.visualizar();"
                    ng-focus="vm.Imobilizado.SELECTED = imobilizado; "
                    ng-class="{'selected' : vm.Imobilizado.SELECTED == imobilizado }"                    
                    
                    >
                    <td colspan="3" style="min-width: 260px; max-width: 260px" class="ellipsis" autotitle>
                        <span style="float: left; width: 65px;">@{{ imobilizado.CCUSTO_MASK }}</span>
                        @{{ imobilizado.CCUSTO_DESCRICAO }}</td>
                    <td colspan="2" style="min-width: 400px; max-width: 400px" class="ellipsis" autotitle>@{{ imobilizado.ID }} - @{{ imobilizado.DESCRICAO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ imobilizado.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ imobilizado.SALDO | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ imobilizado.SALDO_MES | number : 2 }}</td> 
                    <td colspan="2" class="">@{{ imobilizado.OBSERVACAO }}</td>
                </tr>

                <tr ng-repeat-end ng-if="false"></tr>
                <tr ng-repeat-end ng-if="false"></tr>
                <tr ng-repeat-end ng-if="false"></tr>
                <tr ng-repeat-end ng-if="false"></tr>
                <tr ng-repeat-end ng-if="false"></tr>
            </tbody>
            <tfoot>
                <tr style="background: rgb(174, 187, 197);font-weight: bold;">
                    <td colspan="5">Totalizador</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ vm.Imobilizado.VALOR_GERAL | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ vm.Imobilizado.SALDO_GERAL | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ vm.Imobilizado.SALDO_MES_GERAL | number : 2 }}</td>
                    <td colspan="2"></td>
                </tr>  
            </tfoot>
            
            
            
<!--            <tbody vs-repeat vs-scroll-parent=".table-ec">
                <tr ng-repeat="imobilizado in vm.Imobilizado.DADOS
                    | find: {
                        model : vm.Imobilizado.M_FILTRO,
                        fields : [
                            'ID',
                            'DESCRICAO',
                            'OBSERVACAO',
                            'CCUSTO',
                            'CCUSTO_MASK',
                            'CCUSTO_DESCRICAO',
                            'TIPO_DESCRICAO'
                        ]
                    }
                    | orderBy : ['ID*-1','CCUSTO_MASK']"
                    tabindex="0" 
                    class="itens-imobilizado"
                    ng-click="vm.Imobilizado.SELECTED = imobilizado; vm.Imobilizado.visualizar();"
                    ng-class="{'selected' : vm.Imobilizado.SELECTED == imobilizado }"
                    >
                    
                    <td class="ellipsis" autotitle>
                        <span ng-if="imobilizado.STATUS == 0" style="color: red;">Pendente</span>
                        <span ng-if="imobilizado.STATUS == 1" style="color: green;">Concluído</span>
                    </td>
                    <td style="min-width: 260px; max-width: 260px" class="ellipsis" autotitle>
                        <span style="float: left; width: 65px;">@{{ imobilizado.CCUSTO_MASK }}</span>
                        @{{ imobilizado.CCUSTO_DESCRICAO }}</td>
                    <td style="min-width: 400px; max-width: 400px" class="ellipsis" autotitle>@{{ imobilizado.ID }} - @{{ imobilizado.DESCRICAO }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ imobilizado.VALOR | number : 2 }}</td>
                    <td style="min-width: 100px; max-width: 100px" class="text-right">R$ @{{ imobilizado.SALDO | number : 2 }}</td>
                    <td style="min-width: 260px; max-width: 260px" class="ellipsis" autotitle>@{{ imobilizado.TIPO_DESCRICAO }}</td>
                    <td class="text-right text-lowercase">@{{ imobilizado.TAXA * 100 | number: 2 }}% a.a</td>
                    <td class="text-right text-lowercase">@{{ (1 / imobilizado.TAXA) * 12 | number }} meses</td>
                    <td class="">@{{ imobilizado.OBSERVACAO }}</td>
                </tr>
            </tbody>-->
        </table>
    </div>
    
    @include($menu.'.modal-demonstrativo-depreciacao-anual.index')
    @include($menu.'.modal-demonstrativo-depreciacao-anual.modal-parcela')
    @include($menu.'.modal-imobilizado.index')
    @include($menu.'.modal-imobilizado.modal-encerrar')
    @include($menu.'.modal-imobilizado-item.index')
    @include($menu.'.modal-imobilizado.modal-parcela')
    @include($menu.'.modal-imobilizado-item.modal-parcela')
    
</div>
@endsection

@section('script')

    <script src="{{ elixir('assets/js/_16010.js') }}"></script>
@endsection
