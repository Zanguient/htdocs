@extends('master')

@section('titulo')
    {{ Lang::get('compras/_13030.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/13030.ng.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak class="main-ctrl" style="display: none">
	<ul class="list-inline acoes">    
		<li>
            <button ng-disabled="!{{ userMenu($menu)->INCLUIR }}" ng-click="vm.CotaIncluir.incluir()" type="button" class="btn btn-primary btn-incluir" data-hotkey="f6">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>     
		<li>
            <a href{{ $permissaoMenu->INCLUIR ? '=' . url('/_13030/replicar') : '' }} {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-default btn-replicar" data-hotkey="alt+r">
                <span class="glyphicon glyphicon-plus"></span> Replicar Cotas
            </a>
        </li>   
        @if ( userControl(198) ) {{-- // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS --}}
        <li>
            <a href="{{ url('_13030/faturamento') }}" class="btn btn-default btn-ger-fat" data-hotkey="alt+g">
                <span class="glyphicon glyphicon-plus"></span> Gerenciar Faturamento
            </a>
        </li> 
        @endif   
        <li>
            <button class="btn btn-default btn-consumo" data-action="show-dre" data-hotkey="alt+c">
                <span class="glyphicon glyphicon-new-window"></span> Consumo Mensal de Cotas
            </button>
        </li>         
	</ul>    
    <form class="form-inline" ng-submit="vm.Filtro.consultar(true)">
        
        @php $def_mes_1         = isset($_GET['MES_1'        ]) ? $_GET['MES_1'        ] : ''
        @php $def_ano_1         = isset($_GET['ANO_1'        ]) ? $_GET['ANO_1'        ] : ''
        @php $def_mes_2         = isset($_GET['MES_2'        ]) ? $_GET['MES_2'        ] : ''
        @php $def_ano_2         = isset($_GET['ANO_2'        ]) ? $_GET['ANO_2'        ] : ''
        @php $def_cota_zerada   = isset($_GET['COTA_ZERADA'  ]) ? $_GET['COTA_ZERADA'  ] : '1'
        @php $def_cota_valida   = isset($_GET['COTA_VALIDA'  ]) ? $_GET['COTA_VALIDA'  ] : '1'
        @php $def_totaliza_cota = isset($_GET['TOTALIZA_COTA']) ? $_GET['TOTALIZA_COTA'] : '0'
        @php $def_faturamento   = isset($_GET['FATURAMENTO'  ]) ? $_GET['FATURAMENTO'  ] : '0'
        @php $def_cota_ggf      = isset($_GET['COTA_GGF'     ]) ? $_GET['COTA_GGF'     ] : ''
        @php $def_cota_ajuste_inventario      = isset($_GET['COTA_AJUSTE_INVENTARIO'     ]) ? $_GET['COTA_AJUSTE_INVENTARIO'     ] : ''
        @php $def_cota          = isset($_GET['COTA_ID'      ]) ? $_GET['COTA_ID'      ] : ''
        @php $def_cota_open     = isset($_GET['COTA_OPEN'    ]) ? $_GET['COTA_OPEN'    ] : ''
        
        <input type="hidden" ng-init="vm.Filtro.COTA_ID = '{{ $def_cota }}';"  ng-model="vm.Filtro.COTA_ID" ng-update-hidden value="{{ $def_cota }}" />
        <input type="hidden" ng-init="vm.Filtro.COTA_OPEN = '{{ $def_cota_open }}';"  ng-model="vm.Filtro.COTA_OPEN" ng-update-hidden value="{{ $def_cota_open }}" />
        
        <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
            @php $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro'])
            <div class="form-group">
                <label>Data Inicial:</label>
                <select ng-init="vm.Filtro.MES_1 = '{{ $def_mes_1 == '' ? date('n',strtotime('-1 Month')) : $def_mes_1 }}'" ng-model="vm.Filtro.MES_1" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.Filtro.ANO_1 = '{{ $def_ano_1 == '' ? date('Y',strtotime('-1 Month')) : $def_ano_1 }}'" ng-model="vm.Filtro.ANO_1" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Data Final:</label>
                <select ng-init="vm.Filtro.MES_2 = '{{ $def_mes_2 == '' ? date('n') : $def_mes_2 }}'" ng-model="vm.Filtro.MES_2" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.Filtro.ANO_2 = '{{ $def_ano_2 == '' ? date('Y') : $def_ano_2 }}'" ng-model="vm.Filtro.ANO_2" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <input type="checkbox" id="filtro_cota_zerada" class="form-control" ng-init="vm.Filtro.COTA_ZERADA = '{{ $def_cota_zerada }}'" ng-checked="vm.Filtro.COTA_ZERADA == 1" ng-click="vm.Filtro.COTA_ZERADA = vm.Filtro.COTA_ZERADA == 1 ? 0 : 1"/>
                <label class="label-checkbox" for="filtro_cota_zerada" ttitle="Exibe cotas com valor igual a 0 (zero)">Cotas Zeradas</label>
            </div>
            <div class="form-group">
                <input type="checkbox" id="filtro_cota_valida" class="form-control" ng-init="vm.Filtro.COTA_VALIDA = '{{ $def_cota_valida }}'" ng-checked="vm.Filtro.COTA_VALIDA == 1" ng-click="vm.Filtro.COTA_VALIDA = vm.Filtro.COTA_VALIDA == 1 ? 0 : 1"/>
                <label class="label-checkbox" for="filtro_cota_valida" ttitle="Exibe cotas com valor maior que 0 (zero)">Cotas Válidas</label>
            </div>	    
<!--            <div class="form-group">
                <input type="checkbox" id="filtro_totaliza" class="form-control" ng-init="vm.Filtro.TOTALIZA_COTA = '{{ $def_totaliza_cota }}'" ng-checked="vm.Filtro.TOTALIZA_COTA == 1" ng-click="vm.Filtro.TOTALIZA_COTA = vm.Filtro.TOTALIZA_COTA == 1 ? 0 : 1"/>
                <label class="label-checkbox" for="filtro_totaliza" ttitle="Totaliza todas as cotas">Totaliza Cotas</label>
            </div>	 -->
            <div class="form-group">
                <input type="checkbox" id="filtro_ajuste_inventario" class="form-control" ng-init="vm.Filtro.COTA_AJUSTE_INVENTARIO = '{{ $def_cota_ajuste_inventario }}'" ng-checked="vm.Filtro.COTA_AJUSTE_INVENTARIO == 1" ng-click="vm.Filtro.COTA_AJUSTE_INVENTARIO = vm.Filtro.COTA_AJUSTE_INVENTARIO == 1 ? 0 : 1"/>
                <label class="label-checkbox" for="filtro_ajuste_inventario" ttitle="Exibir os ajustes de estoque realizados durante o período em reais">Exibir Ajustes de Inventário</label>
            </div>	 
            <div class="form-group">
                <input type="checkbox" id="filtro_ggf" class="form-control" ng-init="vm.Filtro.COTA_GGF = '{{ $def_cota_ggf }}'" ng-checked="vm.Filtro.COTA_GGF == 1" ng-click="vm.Filtro.COTA_GGF = vm.Filtro.COTA_GGF == 1 ? 0 : 1"/>
                <label class="label-checkbox" for="filtro_ggf" ttitle="Exibir os Gastos Gerais de Fabricação / Gastos Gerais Administrativos">Exibir G.G.F./G.G.A.</label>
            </div>	 
            <div class="form-group">
                <input type="checkbox" id="filtro_faturamento" class="form-control" ng-init="vm.Filtro.FATURAMENTO = '{{ $def_faturamento }}'" ng-checked="vm.Filtro.FATURAMENTO == 1" ng-click="vm.Filtro.FATURAMENTO = vm.Filtro.FATURAMENTO == 1 ? 0 : 1; vm.Filtro.FATURAMENTO == 1 ? vm.Filtro.consultar(true) : ''"/>
                <label class="label-checkbox" for="filtro_faturamento">Exibir Faturamento</label>
            </div>	 

            <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>
        </div>
    </form>   
    
    <div class="main-container" style="position: relative">
        <input type="text" class="form-control input-filter-table" ng-model="vm.CotaCcontabil.FILTRO" placeholder="Filtragem rápida..." style="width: calc(100% - 122px);">
        <button type="submit" class="btn btn-xs btn-info" ng-click="vm.CotaCcusto.toggleExpand()" style="position: absolute;width: 116px;right: 0;top: 0;">
            <span class="fa @{{ vm.Filtro.EXPANDED ? 'fa-compress' : 'fa-expand' }}"></span> @{{ vm.Filtro.EXPANDED ? 'Comprimir' : 'Expandir' }} Todos
        </button>
        <div class="table-cotas table-ec">
            <table class="table table-striped table-bordered table-condensed table-hover table-body table-lc table-lc-body table-consumo">
                <thead>
                    <tr ng-init="vm.CotaCcusto.ORDER_BY = ['CCUSTO_MASK']" gc-order-by="vm.CotaCcusto.ORDER_BY">
                        <th field="CCUSTO_MASK" class="col-fixed">C. Custo / Período / C. Contábil</th>
                        <th field="VALOR*1" class="text-right no-break">Cota/Fat.</th>
                        <th field="EXTRA*1" class="text-right no-break">Extra (+)</th>
                        <th field="TOTAL*1" class="text-right no-break" ttitle="Cota + Extra">Subtotal</th>
                        <th field="OUTROS*1" class="text-right no-break" ttitle="Reduções / Devoluções">Rd./Dv.(-)</th>
                        <th field="UTIL*1" class="text-right no-break" ttitle="Valor utilizado">Utiliz.</th>
                        <th field="PERC_UTIL*1" class="text-right no-break" ttitle="Percentual utilizado<br/>((Reduções + Utilizado) / Subtotal)*100">% Util.</th>
                        <th field="SALDO*1" class="text-right no-break" ttitle="Subtotal - (Reduções + Utilizado)">Saldo</th>
                        <th field="CUSTO_SETOR*1" class="text-right no-break" ng-show="vm.Filtro.FATURAMENTO == 1" ttitle="Percentual de custo do setor<br/>((Reduções + Utilizado) / Faturamento) * 100">% Setor</th>
                    </tr>
                </thead>                    
                <tbody>
                    <tr ng-repeat-start="
                        ccusto in vm.CotaCcusto.DADOS
                        | orderBy : vm.CotaCcusto.ORDER_BY
                        "
                        tabindex="0"     
                        ng-click="ccusto.OPENED = ccusto.OPENED != true ? true : false"
                        ng-if="ccusto.VISIBLE"
                        class="tr-fixed-1"
                        >
                        <td class="row-fixed row-fixed-1 col-fixed" autotitle>
                            <span style="float: left; width: 70px;">@{{ ccusto.CCUSTO_MASK }}</span> @{{ ccusto.CCUSTO_DESCRICAO }}
                        </td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.VALOR  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.EXTRA  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.TOTAL  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.OUTROS | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.UTIL   | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">@{{ ccusto.PERC_UTIL | number : 2 }}%</td>
                        <td class="text-right row-fixed row-fixed-1 no-break">R$ @{{ ccusto.SALDO  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-1 no-break" ng-if="vm.Filtro.FATURAMENTO == 1">@{{ ccusto.CUSTO_SETOR | number : 4 }}%</td>      
                    </tr>    
                    <tr ng-repeat-start="
                        periodo in ccusto.PERIODOS
                        | orderBy : ['ANO*1','MES*1']
                        "
                        tabindex="0"    
                        ng-show="periodo.FILTERED.length > 0 && ccusto.OPENED"
                        ng-click="periodo.OPENED = periodo.OPENED != true ? true : false"
                        class="tr-fixed-2"
                        data-calc="@{{ vm.CotaCcusto.checkVisibility(ccusto) }}"
                        >
                        <td class="row-fixed row-fixed-2 col-fixed">@{{ periodo.PERIODO_DESCRICAO }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.VALOR  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.EXTRA  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.TOTAL  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.OUTROS | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.UTIL   | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">@{{ periodo.PERC_UTIL | number : 2 }}%</td>
                        <td class="text-right row-fixed row-fixed-2 no-break">R$ @{{ periodo.SALDO  | number : 2 }}</td>
                        <td class="text-right row-fixed row-fixed-2 no-break" ng-if="vm.Filtro.FATURAMENTO == 1">@{{ periodo.CUSTO_SETOR | number : 4 }}%</td>      
                    </tr>    
                    <tr ng-repeat="
                        ccontabil in periodo.FILTERED = (periodo.CCONTABEIS
                        | find: {
                            model : vm.CotaCcontabil.FILTRO,
                            fields : [    
                                'CCUSTO',
                                'CCUSTO_MASK',
                                'CCUSTO_DESCRICAO',
                                'PERIODO_DESCRICAO',                                    
                                'CCONTABIL',
                                'CCONTABIL_MASK',
                                'CCONTABIL_DESCRICAO'
                            ]
                        }
                        | orderBy : ['CCONTABIL'])        

                        "
                        data-cota-id="@{{ ccontabil.ID }}"
                        tabindex="0"                         
                        ng-focus="vm.Cota.SELECTED != ccontabil ? vm.Cota.pick(ccontabil) : ''"
                        ng-click="vm.Cota.SELECTED != ccontabil ? vm.Cota.pick(ccontabil) : ''"
                        ng-dblclick="vm.Cota.dblPick(ccontabil)"
                        ng-class="{'selected' : vm.Cota.SELECTED == ccontabil }"      
                        ng-if="ccusto.OPENED && periodo.OPENED"
                        >
                        <td class="col-fixed">
                            <span style="width: calc(100% - 55px); display: block; @{{ ccontabil.DESTAQUE == 1 ? 'font-weight: bold;' : '' }}">
                                @{{ ccontabil.CCONTABIL_MASK }} - @{{ ccontabil.CCONTABIL_DESCRICAO }}
                            </span>

                            <span 
                                ng-if="ccontabil.OBSERVACAO_GERAL != undefined && ccontabil.OBSERVACAO_GERAL.trim() != ''"
                                class="fa fa-exclamation-circle" 
                                ttitle="Obs.: @{{ ccontabil.OBSERVACAO_GERAL }}"
                                style="
                                    position: absolute;
                                    top: calc(50% - 8px);
                                    right: 46px;
                                    font-size: 14px;                                    
                                "></span>    
                            <span 
                                ng-if="ccontabil.BLOQUEIA == 1"
                                class="fa fa-ban" 
                                ttitle="Bloqueia Cota"
                                style="
                                    position: absolute;
                                    top: calc(50% - 8px);
                                    right: 32px;
                                    font-size: 14px;                                    
                                "></span>     
                            <span 
                                ng-if="ccontabil.NOTIFICA == 1"
                                class="fa fa-flag" 
                                ttitle="Notifica Gestor"
                                style="
                                    position: absolute;
                                    top: calc(50% - 8px);
                                    right: 17px;
                                    font-size: 14px;                                    
                                "></span>     
                            <span 
                                ng-if="ccontabil.TOTALIZA == 1"
                                class="fa fa-plus-circle" 
                                ttitle="Contabiliza cota no totalizador"
                                style="
                                    position: absolute;
                                    top: calc(50% - 8px);
                                    right: 3px;
                                    font-size: 14px;                                    
                                "></span>        
                        </td>
                        <td class="text-right no-break">R$ @{{ ccontabil.VALOR  | number : 2 }}</td>
                        <td class="text-right no-break">R$ @{{ ccontabil.EXTRA  | number : 2 }}</td>
                        <td class="text-right no-break">R$ @{{ ccontabil.TOTAL  | number : 2 }}</td>
                        <td class="text-right no-break">R$ @{{ ccontabil.OUTROS | number : 2 }}</td>
                        <td class="text-right no-break">R$ @{{ ccontabil.UTIL   | number : 2 }}</td>
                        <td class="text-right no-break">@{{ ccontabil.PERC_UTIL | number : 2 }}%</td>
                        <td class="text-right no-break">R$ @{{ ccontabil.SALDO  | number : 2 }}</td>
                        <td class="text-right no-break" ng-if="vm.Filtro.FATURAMENTO == 1">@{{ ccontabil.CUSTO_SETOR | number : 4 }}%</td>
                    </tr>  
                    <tr ng-repeat-end ng-if="false"></tr>
                    <tr ng-repeat-end ng-if="false"></tr>
                </tbody>
            </table>
        </div>
        
    @include('compras._13030.ng.index.modal-cota') 
    @include('compras._13030.ng.index.modal-cota.modal-ggf-detalhe') 
    @include('compras._13030.ng.index.modal-cota-incluir') 
    
    
    @include('compras._13030.show-dre.modal')
    @include('compras._13030.show.modal')
    @include('compras._13030.show-ggf.modal')
    @include('compras._13030.show-ggf-detalhe.modal')    
    @include('compras._13030.modal')
</div>
@endsection

@include('helper.include.view.pdf-imprimir')

@section('script')
    <script src="{{ elixir('assets/js/_13030.ng.js') }}"></script>
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input.js') }}"></script>
	<script src="{{ elixir('assets/js/pdf.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
    <script src="{{ elixir('assets/js/_25700.js') }}"></script>
@append
