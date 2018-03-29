<ul class="list-inline acoes">    
    <li>
        <button class="btn btn-primary" data-hotkey="insert" ng-click="vm.Ferramenta.RegistrarEntrada()">
            <span class="fa fa-arrow-circle-o-down"></span> Registrar Entrada
        </button>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL = !vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL"
            ng-class="{'item-active' : vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL}"
            >
        <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL ? 'fa-eye' : 'fa-eye-slash'"></i>
            Disponíveis
        </div>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.FerramentaProgramada.CHECK_OCULTAR_SEPARADA = !vm.FerramentaProgramada.CHECK_OCULTAR_SEPARADA"
            ng-class="{'item-active' : vm.FerramentaProgramada.CHECK_OCULTAR_SEPARADA}"
            >
        <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_OCULTAR_SEPARADA ? 'fa-eye' : 'fa-eye-slash'"></i>
            Separadas
        </div>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.FerramentaProgramada.CHECK_EM_PRODUCACAO = !vm.FerramentaProgramada.CHECK_EM_PRODUCACAO"
            ng-class="{'item-active' : vm.FerramentaProgramada.CHECK_EM_PRODUCACAO}"
            >
        <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_EM_PRODUCACAO ? 'fa-eye' : 'fa-eye-slash'"></i>
            Em Produção
        </div>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.FerramentaProgramada.CHECK_EM_DESUSO = !vm.FerramentaProgramada.CHECK_EM_DESUSO"
            ng-class="{'item-active' : vm.FerramentaProgramada.CHECK_EM_DESUSO}"
            >
        <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_EM_DESUSO ? 'fa-eye' : 'fa-eye-slash'"></i>
            Em Desuso
        </div>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.FerramentaProgramada.CHECK_RESERVADA = !vm.FerramentaProgramada.CHECK_RESERVADA"
            ng-class="{'item-active' : vm.FerramentaProgramada.CHECK_RESERVADA}"
            >
        <i class="check fa" ng-class="vm.FerramentaProgramada.CHECK_RESERVADA ? 'fa-eye' : 'fa-eye-slash'"></i>
            Reservada
        </div>
    </li>               
    <li style="float: right">
        <div 
            class="btn btn-default gerar-historico" 
            data-hotkey="alt+h"
            ng-click="vm.Ferramenta.getHistorico(vm.FerramentaProgramada.SELECTED.FERRAMENTA_ID)"
            >
            <span class="glyphicon glyphicon-time"></span>
            Histórico
        </div>
    </li>               
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-filtro-obj">
		<input 
            type="search" 
            name="filtro_obj" 
            class="form-control pesquisa filtro-obj ng-pristine ng-valid ng-empty ng-touched" 
            placeholder="Pesquise..." 
            autocomplete="off" 
            title="Filtragem por: Ferramenta, GP, Estação" 
            ng-model="vm.FerramentaProgramada.FILTRO"
            ng-keydown="vm.FerramentaProgramada.Keydown($event)"
            ng-change="(vm.FerramentaProgramada.FILTERED.length > 0) ? vm.FerramentaProgramada.Select(vm.FerramentaProgramada.FILTERED[0]) : ''"
            >
		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>

<div class="panel panel-left">
<div class="table-container table-container-painel table-ferramenta-programada">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="tw-ferramenta">Ferramenta</th>
				<th class="tw-enderecamento">End.</th>
				<th class="tw-estacao">Alocação</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody>

				<tr ng-repeat="item in vm.FerramentaProgramada.FILTERED = (vm.FerramentaProgramada.ITENS
                    | filter: vm.FerramentaProgramada.Filter
                    | orderBy:['GP_DESCRICAO','ESTACAO_DESCRICAO', 'DATAHORA_INICIO', 'FERRAMENTA_DESCRICAO'])"
                    ng-class="{
                        'selected'    : (vm.FerramentaProgramada.SELECTED == item),
                        'em-producao' : item.FERRAMENTA_RESERVA.trim() == '1',
                        'em-desuso' : item.FERRAMENTA_RESERVA.trim() == '2',
                        'em-utilizacao' : ( item.FERRAMENTA_RESERVA.trim() == '0' && item.FERRAMENTA_SITUACAO.trim() == '.' )
                    }"
                    ng-click="vm.FerramentaProgramada.Select(item)"
                    ng-focus="vm.FerramentaProgramada.Select(item)"
                    tabindex="0"
                    >
					<td class="tw-ferramenta">
                        @{{ item.FERRAMENTA_SERIE }} - @{{ item.FERRAMENTA_DESCRICAO }}
                    </td>
					<td class="tw-enderecamento">
                        @{{ item.FERRAMENTA_ENDERECAMENTO }}
                    </td>
                    <td class="tw-estacao">
                        <div ng-if="item.FERRAMENTA_SITUACAO.trim() == '.'">
                            @{{ item.FERRAMENTA_GP_DESCRICAO }} - @{{ item.FERRAMENTA_ESTACAO_DESCRICAO }}
                        </div>
                        <div ng-if="item.FERRAMENTA_SITUACAO.trim() != '.'">
                            @{{ item.FERRAMENTA_SITUACAO_DESCRICAO }}
                        </div>
                    </td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>
</div>
<div class="panel panel-right">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="tw-ferramenta">Cronograma de Utilização</th>
				<th class="tw-estacao">GP / Estação</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody>
				<tr ng-repeat="item in vm.FerramentaProgramada.SELECTED.HORARIOS
                    | orderBy:['DATAHORA_INICIO','GP_DESCRICAO','UP_DESCRICAO', 'ESTACAO_DESCRICAO']"
                    tabindex="0"
                    >
					<td class="tw-ferramenta">
                        @{{ item.DATAHORA_INICIO | toDate | date:'HH:mm dd/MM' }} - 
                        @{{ item.DATAHORA_FIM | toDate | date:'HH:mm dd/MM' }}
                    </td>
					<td class="tw-estacao">
                        @{{ item.GP_DESCRICAO }} - @{{ item.ESTACAO_DESCRICAO }}
                    </td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>
<div class="legenda-container">
	<label class="legenda-label">Legenda de alocação da ferramenta</label>
	<ul class="legenda talao">
		<li>
			<div class="cor-legenda btn-primary"></div>
			<div class="texto-legenda">Em produção |</div>
		</li>
		<li>
			<div class="cor-legenda btn-danger"></div>
			<div class="texto-legenda">Em desuso | </div>
		</li>
		<li>
			<div class="cor-legenda btn-success"></div>
			<div class="texto-legenda">Reservada para utilização em até 90 minutos</div>
		</li>
    </ul>
</div>