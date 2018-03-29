    
<ul class="list-inline acoes">    
    <li>
        <button class="btn btn-primary" data-hotkey="insert" ng-click="vm.Ferramenta.RegistrarEntrada()">
            <span class="fa fa-arrow-circle-o-down"></span> Registrar Entrada
        </button>
    </li>               
    <li>
        <div 
            class="btn btn-default" 
            ng-click="vm.Estacao.CHECK_OCULTAR_PARADA = !vm.Estacao.CHECK_OCULTAR_PARADA"
            ng-class="{'item-active' : !vm.Estacao.CHECK_OCULTAR_PARADA}"
            >
        <i 
            class="check fa" 
            ng-class="vm.Estacao.CHECK_OCULTAR_PARADA ? 'fa-eye-slash' : 'fa-eye'"
            ></i>
            Estações Paradas
        </div>
    </li>   
    <li style="float: right">
        <div 
            class="btn btn-default gerar-historico" 
            data-hotkey="alt+h"
            ng-click="vm.Ferramenta.getHistorico(vm.Ferramenta.SELECTED.FERRAMENTA_ID)"
            >
            <span class="glyphicon glyphicon-time"></span>
            Histórico
        </div>
    </li>  
</ul>
<div style="
    position: absolute;
    top: 59px;
    right: 15px;
    width: 110px;
    z-index: 99;
    border: 1px solid;
    padding: 5px;
    " class="alert alert-warning last-update ng-binding inserted"> <div class="inserted" style="
    font-size: 9px;
">Útima atualização</div>
    <div class="inserted" style="
    font-size: 11px;
">@{{ vm.LAST_UPDATE | toDate | date : 'dd/MM/yy HH:mm:ss' }}</div>
</div>
<div class="last-update">
    
</div>
<div class="table-container table-container-painel">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="tw-estacao" title="Grupo de Produção - Estação de Trabalho">Estação</th>
				<th class="tw-ferramenta">Ferramenta</th>
                <th class="tw-enderecamento" title="Endereçamento">End.</th>
				<th class="tw-datahora" title="Data e Hora em que a ferramenta deverá está na Estação">Data/Hora</th>
				<th class="tw-tempo-restante">Tempo Restante</th>
				<th class="tw-acoes">Ações</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody>

				<tr ng-repeat="item in vm.DADOS 
                    | filter : (vm.Estacao.CHECK_OCULTAR_PARADA || '') && { ESTACAO_STATUS : 0 } 
                    | orderBy:['DATAHORA_INICIO','GP_DESCRICAO','UP_DESCRICAO', 'ESTACAO_DESCRICAO']"
                    ng-keydown="vm.Ferramenta.Keydown($event,item)"
                    ng-class="{'selected' : (vm.Ferramenta.SELECTED == item)}"
                    ng-click="vm.Ferramenta.Select(item)"
                    ng-focus="vm.Ferramenta.Select(item)"
                    tabindex="0"
                    >
                    <td 
                        class="tw-estacao" 
                        ng-class="{'parada' : item.ESTACAO_STATUS != 0}"
                        >
                        <div class="estacao">@{{ item.GP_DESCRICAO }} - @{{ item.ESTACAO_DESCRICAO }}</div>
                        <div 
                            class="descricao"
                            data-toggle="tooltip"
                            title="@{{ item.ESTACAO_STATUS_DESCRICAO }}"
                            ng-if="item.ESTACAO_STATUS != 0" 
                            >@{{ item.ESTACAO_STATUS_DESCRICAO }}</div>
                    </td>
					<td class="tw-ferramenta">
                        <div
                            class="esfera"
                            ng-class="{
                                'danger' :  (item.TIME <  11),
                                'warning' : (item.TIME >= 11 && item.TIME < 41),
                                'success' : (item.TIME >= 41 && item.TIME < 61),
                                'default' : (item.TIME >= 61)
                            }">
                            <span ng-if="item.REQUISICAO.trim() == '1'" title="Ferramenta para requisição">R</span>
                            <div class="box-ligth"><div></div></div>
                            <div class="box-color"></div>
                        </div>
                        
                        @{{ item.FERRAMENTA_SERIE }} - @{{ item.FERRAMENTA_DESCRICAO }}
                    </td>
					<td class="tw-enderecamento">@{{ item.FERRAMENTA_ENDERECAMENTO }}</td>
					<td class="tw-datahora">@{{ item.DATAHORA_INICIO | parseDate | date:'dd/MM HH:mm' }}</td>
                    <td class="tw-tempo-restante text-low">
                        @{{ item.TIME_STRING }}
                    </td>
                    <td class="tw-acoes">
                        <div ng-if="(item.FERRAMENTA_STATUS.trim() != '1')">
                            <div 
                                class="btn btn-danger"
                                data-toggle="tooltip" 
                                title="Inativada"
                                >
                                <span class="fa fa-times-circle"></span>
                            </div>   
                            <div 
                                class="btn btn-primary ng-scope" 
                                ng-click="vm.Ferramenta.RegistrarTroca()" 
                                data-toggle="tooltip" 
                                title="Alterar Ferramenta (Atalho: *)"
                                >
                                <span class="glyphicon glyphicon-random"></span>
                            </div>
                        </div>
                        <div ng-if="(item.FERRAMENTA_SERIE <= 0)">
                            <div 
                                class="btn btn-danger"
                                data-toggle="tooltip" 
                                title="Bloqueada"
                                >
                                <span class="fa fa-times-circle"></span>
                            </div>   
                            <div 
                                class="btn btn-primary ng-scope" ng-click="vm.Ferramenta.RegistrarTroca()" 
                                data-toggle="tooltip" 
                                title="Alterar Ferramenta (Atalho: *)"
                                >
                                <span class="glyphicon glyphicon-random"></span>
                            </div>
                        </div>
                        <div ng-if="(item.FERRAMENTA_STATUS.trim() == '1' && item.FERRAMENTA_SERIE > 0)">
                            <div ng-if="(item.FERRAMENTA_SITUACAO.trim() == 'E' || item.FERRAMENTA_SITUACAO.trim() == 'R') && item.FERRAMENTA_SITUACAO.trim() == item.FERRAMENTA_SITUACAO_TALAO.trim()" >
                                <div 
                                    class="btn btn-warning" 
                                    data-toggle="tooltip" 
                                    title="Separar"
                                    ng-click="vm.Ferramenta.RegistrarAcao()" 
                                    ng-if="( item.FERRAMENTA_SITUACAO_TALAO.trim() == 'E')">
                                    <span class="fa fa-exchange"></span>
                                </div>
                                <div 
                                    class="btn btn-success" 
                                    data-toggle="tooltip" 
                                    title="Registrar Saída"
                                    ng-click="vm.Ferramenta.RegistrarAcao()" 
                                    ng-if="( item.FERRAMENTA_SITUACAO_TALAO.trim() == 'R')">
                                    <span class="fa fa-arrow-circle-o-up"></span>
                                </div>
                                <div 
                                    class="btn btn-primary ng-scope" 
                                    ng-click="vm.Ferramenta.RegistrarTroca()" 
                                    data-toggle="tooltip" 
                                    title="Alterar Ferramenta (Atalho: *)">
                                    <span class="glyphicon glyphicon-random"></span>
                                </div>
                            </div>
                            <div ng-if="(item.FERRAMENTA_SITUACAO.trim() != 'E' || item.FERRAMENTA_SITUACAO.trim() != 'R') && item.FERRAMENTA_SITUACAO.trim() != item.FERRAMENTA_SITUACAO_TALAO.trim()">
                                <div 
                                    class="btn btn-danger"
                                    data-toggle="tooltip" 
                                    title="Indisponível"
                                    >
                                    <span class="glyphicon glyphicon-ban-circle"></span>
                                </div>
                                <div 
                                    class="btn btn-primary ng-scope" 
                                    ng-click="vm.Ferramenta.RegistrarTroca()" 
                                    data-toggle="tooltip" 
                                    title="Alterar Ferramenta (Atalho: *)"
                                    >
                                    <span class="glyphicon glyphicon-random"></span>
                                </div>
                            </div>
                        </div>
                    </td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>
<div class="legenda-container">
	<label class="legenda-label">Legenda de situação da estação</label>
	<ul class="legenda talao">
		<li>
			<div class="texto-legenda"><span class="texto-legenda" style="text-decoration: line-through; color: rgb(212, 63, 58);">Estação</span> Parada</div>
		</li>
    </ul>
</div>
<div class="legenda-container">
	<label class="legenda-label">Legenda de minutos restante para ínicio do talão</label>
	<ul class="legenda talao">
		<li>
			<div class="cor-legenda btn-danger"></div>
			<div class="texto-legenda">10 ou menos |</div>
		</li>
		<li>
			<div class="cor-legenda btn-warning"></div>
			<div class="texto-legenda">10 à 40 |</div>
		</li>
		<li>
			<div class="cor-legenda btn-success"></div>
			<div class="texto-legenda">40 à 60 |</div>
		</li>
		<li>
			<div class="cor-legenda btn-default"></div>
			<div class="texto-legenda">60 ou mais</div>
		</li>
    </ul>
</div>