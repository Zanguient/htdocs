@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22120.titulo') }}
@endsection

@section('estilo')

    <!--<link rel="stylesheet" href="https://cdn.rawgit.com/angular-ui/bower-ui-grid/master/ui-grid.min.css" />-->
    <link rel="stylesheet" href="{{ elixir('assets/css/22120.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-init="vm.remessaAction.Filtrar()" ng-cloak>

@php $remessa = isset($_GET['remessa']) ? $_GET['remessa'] : ''

@php $remessa_tipo   = isset($_GET['REMESSA_TIPO_AUTO'  ]) ? $_GET['REMESSA_TIPO_AUTO'  ] : ''
@php $remessa_origem = isset($_GET['REMESSA_ORIGEM_AUTO']) ? $_GET['REMESSA_ORIGEM_AUTO'] : ''

<input 
    type="hidden"
    ng-init="
        vm.RemessaComponente.REMESSA_TIPO_AUTO   = '{{ $remessa_tipo }}';
        vm.RemessaComponente.REMESSA_ORIGEM_AUTO = '{{ $remessa_origem }}';
    " />
    
<ul class="list-inline acoes">

    <li>
		<a href="{{ $permissaoMenu->INCLUIR ? url('/_22100') : '' }}" class="btn btn-primary" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get('master.incluir') }}
		</a>
	</li>

<!--    <li>
        <button 
            ng-click="vm.RemessaComponente.modalOpen()"
            class="btn btn-primary" 
            data-hotkey="f6" 
            {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
			<span class="glyphicon glyphicon-plus"></span>
			Incluir Remessa de Componente
		</button>
	</li>-->
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input 
            type="search" 
            name="filtro_obj" 
            class="form-control pesquisa filtro-obj" 
            placeholder="Pesquise..." 
            autocomplete="off" 
            autofocus
            title="Filtragem por: Remessa"
            ng-model="vm.filtrar_remessa"
            ng-init="vm.filtrar_remessa = '{{ $remessa }}'; vm.filtrar_remessa != '' ? vm.remessaAction.Filtrar() : ''"
            ng-change="vm.fixVsRepeatRemessa()"
            ng-keydown="($event.keyCode === 13) ? vm.remessaAction.Filtrar() : ''"
            />
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<fieldset>
	<legend>Remessas</legend>
    <div id="table-filter" class="table-filter">       
		<div>
			<label>Família de Produto:</label>
            <select ng-init="vm.filtro.familia = ''" ng-model="vm.filtro.familia" ng-change="vm.fixVsRepeatRemessa()">
                <option value="" selected>TODAS</option>
                <option  ng-repeat="o in vm.remessas | groupBy:'[FAMILIA_DESCRICAO,FAMILIA_ID]'" value="@{{ o[0].FAMILIA_ID }}">@{{ o[0].FAMILIA_ID }} - @{{ o[0].FAMILIA_DESCRICAO }}</option>
            </select>
		</div>
<!--        <div>
			<label>Status:</label>
            <select ng-init="vm.filtro.status = '0'" ng-model="vm.filtro.status" ng-change="vm.fixVsRepeatRemessa()">
                <option value="">TODOS</option>
                <option value="0">EM PRODUÇÃO</option>
                <option value="1">PRODUZIDA</option>
            </select>
        </div>-->
		<div>
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-inicial" id="data-inicial" ng-model="vm.filtro.data_1" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-final" id="data-final" ng-model="vm.filtro.data_2" />
		</div>
<!--		<div>
			<label>Remessa:</label>
            <input type="text" ng-init="vm.filtro.remessa = ''" ng-model="vm.filtro.remessa" />
		</div>-->
        <button class="btn btn-sm btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter" ng-click="vm.remessaAction.Filtrar()">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
	</div>
 
    <div class="table-remessas table-container" style="display: none">
        <table class="table table-bordered table-header">
            <thead>
                <tr>
                    <th class="wid-status"></th>
                    <th class="wid-remessa">Remessa</th>
                    <th class="wid-tipo">Tipo</th>
                    <th class="wid-data">Data</th>
                    <th class="wid-familia">Familia</th>
                    <!--<th class="wid-perfil">Perfil Sku</th>-->
                    <!--<th class="text-right wid-qtd-programada" title="Quantidade Projetada">Proj.</th>-->
                    <!--<th class="text-right wid-qtd-produzida" title="Quantidade Produzida">Prod.</th>-->
                    <!--<th class="text-right wid-percentual" title="Percentual realizado">Perc. Real.</th>-->
                    <th class="wid-usuario" title="Percentual realizado">Usuário</th>
                </tr>
            </thead>
        </table>
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover table-body">                              
                <tbody vs-repeat vs-scroll-parent=".table-container">
                    <tr
                        ng-repeat="r in vm.remessas_filtered = (vm.remessas
                            | filter: vm.remessaAction.RepeatFilter
                            )
                        " 
                        ng-click="vm.remessaAction.VisualizarItem(r)"
                        ng-class="{'selected' : vm.remessa == r}">
                        <td class="t-status producao-@{{ r.STATUS_PRODUCAO }} wid-status" title="REMESSA @{{ r.STATUS_PRODUCAO_DESCRICAO }}"></td>
                        <td class="wid-remessa">@{{ r.REMESSA }}</td>
                        <td class="wid-tipo">@{{ r.WEB_DESCRICAO }} - @{{ r.TIPO_DESCRICAO }}</td>
                        <td class="wid-data">@{{ r.DATA | parseDate | date:'dd/MM/yy' : '+0' }}</td>
                        <td class="wid-familia">@{{ r.FAMILIA_ID }} - @{{ r.FAMILIA_DESCRICAO }}</td>
                        <!--<td class="wid-perfil" title="@{{ r.PERFIL_SKU_DESCRICAO }}">@{{ r.PERFIL_SKU_DESCRICAO }}</td>-->
                        <!--<td class="text-right wid-qtd-programada">@{{ r.QUANTIDADE | number: 2 }} @{{ r.UM }}</td>-->
                        <!--<td class="text-right wid-qtd-produzida">@{{ r.QUANTIDADE_PRODUZIDA | number: 2 }} @{{ r.UM }}</td>-->
                        <!--<td class="text-right wid-percentual">@{{ r.PERCENTUAL | number: 2 }}%</td>-->
                        <td class="wid-usuario" title="@{{ r.USUARIO_ID }} - @{{ r.USUARIO_DESCRICAO }}">@{{ r.USUARIO_ID }} - @{{ r.USUARIO_DESCRICAO }}</td>
                    </tr>             
                </tbody>
            </table>
        </div>
    </div>

<!--	<ul class="legenda">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get('master.ativo') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get('master.inativo') }}</div>
		</li>
	</ul>-->
	
</fieldset>    

@include('ppcp._22120.index.modal-remessa')
@include('ppcp._22120.index.modal-consumo-imprimir')
@include('ppcp._22120.index.modal-gerar-consumo')
@include('ppcp._22120.index.modal-taloes-extra')
@include('ppcp._22120.modal-remessa-intermediaria')
@include('ppcp._22120.modal-remessa-componente')

@include('ppcp._22120.index.remessa.talao-consumo.modal-alterar-consumo')

</div>
@include('helper.include.view.pdf-imprimir')
@endsection

@section('script')
    <!--<script src="https://cdn.rawgit.com/angular-ui/bower-ui-grid/master/ui-grid.min.js"></script>-->
    <script src="assets/js/angular-datatables.js"></script>
    <script src="{{ elixir('assets/js/pdf.js') }}"></script>
	<!--<script src="{{ elixir('assets/js/data-table.js') }}"></script>-->
    <script src="{{ elixir('assets/js/_22120.ng.js') }}"></script>
@append
