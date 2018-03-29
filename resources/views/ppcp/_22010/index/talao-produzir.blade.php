<input type="hidden" id="_pu212" value="{{ userControl(212) or '' }}" />
<input type="hidden" id="_pu213" value="{{ userControl(213) or '' }}" />
<input type="hidden" ng-init="vm.MENU_22120 = '{{ userMenu(22120,0,null,false) or ''}}'" />

<style>
    .sequencia-producao {
        color: red
    }
</style>

<div class="recebe-puxador-talao">
    <div class="table-container">
        <table class="table table-bordered table-header table-lc table-talao-produzir">
            <thead>
                <tr>
                    <th class="t-status" title="Status do Talão"></th>
                    <th class="t-status" title="Status dos consumos"></th>	
                    <th class="wid-up up" ng-if="vm.Filtro.UP_ID == ''">UP</th>
                    <th class="wid-estacao estacao">Estação</th>
                    <th class="text-center wid-remessa-data data-remessa" title="Data da remessa">Dt. Rem.</th>
                    <th class="wid-remessa remessa">Remessa</th>
                    <th class="text-center wid-talao talao">Talão</th>
                    <!--<th class="text-center data-remessa-origem" title="Data da Remessa de Origem">Dt. Rem. Orig.</th>-->
                    <th class="text-right talao-origem" title="Informações de Origem">Inf.Orig.</th>
                    <!--<th class="up-origem">UP Origem</th>-->
                    <th class="modelo">Modelo</th>
                    <th class="text-right densidade">Dens.</th>
                    <th class="text-right espessura">Esp.</th>                    
                    <th class="text-right qtd" title="Quantidade a produzir"
                        ng-class="{
                            'qtd-alternativa' : vm.TalaoProduzir.DADOS[0].QUANTIDADE_ALTERNATIVA > 0,
                            'qtd'             : !(vm.TalaoProduzir.DADOS[0].QUANTIDADE_ALTERNATIVA > 0)
                        }"                        
                        >Qtd.</th>
                    <th class="text-right pares" ng-if="vm.Filtro.VER_PARES">Pares</th>
                    <th class="text-right tempo-prev"     title="Tempo previsto">Tempo Prev.</th>
                    <th class="text-center data-ini-prev" title="Data e hora prevista para iniciar">Dt. Ini. Prev.</th>
                </tr>
            </thead>
        </table>
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-talao-produzir">
                <tbody>
                    
                    <tr ng-repeat="talao in vm.TalaoProduzir.DADOS         
                        | filter : (vm.TalaoProduzir.EM_PRODUCAO || '') && { ID : vm.TalaoProduzir.SELECTED.ID } 
                        | orderBy : ['PROGRAMACAO_DATA', '+REMESSA_TIPO', '+DATAHORA_INICIO', 'REMESSA_ID', 'REMESSA_TALAO_ID']"
                        tabindex="0" 
                        class="tipo-@{{ talao.REMESSA_TIPO }}" 
                        data-talao-id="#id-@{{ talao.ID }}" 
                        data-tipo="@{{ talao.REMESSA_TIPO }}" 
                        data-consumo-componente="@{{ talao.COMPONENTE }}"
                        data-status-componentes="@{{ talao.STATUS_COMPONENTE }}"
                        data-status-materias-primas="@{{ talao.STATUS_MATERIA_PRIMA }}"
                        data-status-programacao="@{{ talao.PROGRAMACAO_STATUS }}"
                        data-up-origem="@{{ talao.UP_DESTINO }}"
                        data-remessa-data="@{{ talao.REMESSA_DATA }}"
                        id="@{{ talao.ID }}"
                        ng-focus="vm.TalaoProduzir.SELECTED != talao ? vm.TalaoProduzir.selectionar(talao) : ''"
                        
                        ng-class="{'selected' : vm.TalaoProduzir.SELECTED == talao }"
                        >
                        <td class="t-status t-status status-programacao status@{{ talao.PROGRAMACAO_STATUS }}" title="@{{ talao.PROGRAMACAO_STATUS_DESCRICAO }}"></td>
                        <td class="t-status t-status status-materias-primas-@{{ talao.STATUS_MP_CP }}" title="@{{ talao.STATUS_MATERIA_PRIMA_DESCRICAO }}"></td>
                        <td ng-if="vm.Filtro.UP_ID == ''" class="wid-up up" title="@{{ talao.UP_DESCRICAO }}">@{{ talao.UP_DESCRICAO }}</td>
                        <td class="wid-estacao estacao">@{{ talao.ESTACAO }} - @{{ talao.ESTACAO_DESCRICAO }}</td>
                        <td class="wid-remessa-data text-center data-remessa">@{{ talao.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                        <td class="wid-remessa remessa">
                            
                            
                            <a ng-if="vm.MENU_22120 == 1" title="Id da remessa: @{{ talao.REMESSA_ID }} | Clique aqui para consultar a remessa deste talão" href="{{ url('/_22120?remessa=') }}@{{ talao.REMESSA_PRINCIPAL }}" target="_blank">
                            @{{ talao.REMESSA }}
                            </a>                            
                            
                            <span ng-if="vm.MENU_22120 != 1" title="Id da remessa: @{{ talao.REMESSA_ID }}">
                            @{{ talao.REMESSA }}
                            </span>
                            
                            <span class="tipo" ng-if="talao.REMESSA_TIPO != '1'">@{{ talao.REMESSA_TIPO_DESCRICAO }}</span>
                        </td>
                        <td class="wid-talao text-center talao" title="Id do Talão: @{{ talao.ID }}">@{{ talao.REMESSA_TALAO_ID }}</td>
                        
                        
                        
                        
                        <!--<td class="text-center data-remessa-origem">@{{ talao.DATA_REMESSA_ORIGEM == '' ? '-' : talao.DATA_REMESSA_ORIGEM | toDate | date:'dd/MM' : '+0' }}</td>-->
                        <td class="text-right talao-origem">
                            <span
                                ng-if="talao.TALOES_ORIGEM.length > 0"
                                class="glyphicon glyphicon-info-sign" 
                                data-toggle="popover" 
                                data-placement="right" 
                                title="Informações"
                                data-element-content="#info-origem-@{{ talao.ID }}"
                                on-finish-render="bs-init"
                                ng-class="{'sequencia-producao' : talao.SEQUENCIA_PRODUCAO != null }"
                            ></span>
                            <div id="info-origem-@{{ talao.ID }}" style="display: none">
                                <div class='origem-container'>
                                    <fieldset ng-if="talao.TALOES_VINCULO.length > 0">
                                        <legend style="font-size: 12px; font-weight: bold;">Talões Vinculados</legend>
                                        <p ng-bind-html="vm.trustedHtml(talao.TALOES_VINCULO)"></p>
                                    </fieldset>
                                    <fieldset ng-if="talao.SEQUENCIA_PRODUCAO != null">
                                        <legend style="font-size: 12px; font-weight: bold;">Sequência de Produção</legend>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">GP</th>
                                                    <th class="text-left">Estação</th>
                                                    <th class="text-left">Remessa/Talão</th>
                                                    <th class="text-center">Dt/Hr</th>
                                                    <th class="text-center">Seq.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="item in talao.SEQUENCIA_PRODUCAO track by $index">
                                                    <td class="text-left">@{{ item.GP_DESCRICAO }}</td>
                                                    <td class="text-left">@{{ item.ESTACAO_DESCRICAO }}</td>
                                                    <td class="text-right">@{{ item.REMESSA_ID }} / @{{ item.REMESSA_TALAO_ID }}</td>
                                                    <td class="text-center">@{{ item.DATAHORA_INICIO }}</td>
                                                    <td class="text-center">@{{ item.SEQUENCIA }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                    <fieldset>
                                        <legend style="font-size: 12px; font-weight: bold;">Quantidades por GP</legend>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">GP</th>
                                                    <th class="text-left">Qtd.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="item in talao.PARES_POR_GP.split(',')">
                                                    <td class="text-left">@{{ item.split('/')[0] || '-' }}</td>
                                                    <td class="text-right">@{{ item.split('/')[1] || '-' | number: 0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>
                            <span ng-if="talao.TALOES_ORIGEM.length > 0" class="vinculo-modelos" ttitle="Modelos de Origem" ng-click="vm.TalaoComposicao.consultarVinculoModelos(talao.ID)">M</span>
                            
                            <span ng-if="talao.JUSTIFICATIVA.length > 0" class="justificativa-alerta " ttitle="@{{talao.JUSTIFICATIVA}}" >!</span>
                            

                        </td>
                        <!--<td class="up-origem">@{{ talao.UP_DESTINO }}</td>-->
                        <td class="modelo">@{{ talao.MODELO_ID }} - @{{ talao.MODELO_DESCRICAO }}</td>
                        <td class="text-right densidade">@{{ talao.DENSIDADE | number: 2 }}</td>
                        <td class="text-right espessura">@{{ talao.ESPESSURA | number: 2 }}</td>
                        
                        <td class="text-right"
                            ng-class="{
                                'qtd-alternativa' : talao.QUANTIDADE_ALTERNATIVA > 0,
                                'qtd'             : !(talao.QUANTIDADE_ALTERNATIVA > 0)
                            }"
                            > @{{ talao.QUANTIDADE_ALTERNATIVA > 0 ? talao.QUANTIDADE_ALTERNATIVA : talao.QUANTIDADE | number: 4 }} @{{ talao.QUANTIDADE_ALTERNATIVA > 0 ? talao.UM_ALTERNATIVA : talao.UM }}</td>
                        
                        <td ng-if="vm.Filtro.VER_PARES" class="text-right pares">@{{ talao.PARES | number : 0 }}</td>
                        <td class="text-right tempo-prev">@{{ talao.TEMPO | number: 2 }} min</td>
                        <td class="text-center data-ini-prev">@{{ !talao.DATAHORA_INICIO ? '' : talao.DATAHORA_INICIO | toDate | date:'dd/MM HH:mm' }}</td>
                        
                        <input type="hidden" name="_id"					  class="_id"					value="@{{ talao.ID }}"               />
                        <input type="hidden" name="_programacao_id"		  class="_programacao-id"		value="@{{ talao.PROGRAMACAO_ID }}"	  />
                        <input type="hidden" name="_remessa_id"			  class="_remessa-id"			value="@{{ talao.REMESSA_ID }}"		  />
                        <input type="hidden" name="_remessa_talao_id"	  class="_remessa-talao-id"     value="@{{ talao.REMESSA_TALAO_ID }}" />                        
                        <input ng-if="vm.Filtro.VER_PARES" type="hidden" class="_pares" value="@{{ talao.PARES || 0 }}" />
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>








<div class="legenda-container">
	<label class="legenda-label">Status do Talão (1ª coluna)</label>
	<ul class="legenda talao">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-parado') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-ini-par') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-andamento') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-finalizado') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-encerrado') }}</div>
		</li>
	</ul>
</div>


<div class="legenda-container">
	<label class="legenda-label">Status dos Consumos (2ª coluna)</label>
	@php /*
	@if ( $componente == '1' )
	<ul class="legenda talao-status-componentes">
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-parado') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-andamento') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-produzido') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-encerrado') }}</div>
			</li>
		</ul>
	@else
	@php */
		<ul class="legenda status-materias-primas">
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-sem-estoque') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-com-estoque') }}</div>
			</li>
		</ul>
	{{-- @endif --}}
</div>

<div class="totalizador-produzir">
	<div class="panel panel-warning">
		<div class="panel-heading">
            <label title="Quantidade a produzir">Qtd. a Prod.</label>
			<label ng-if="vm.Filtro.VER_PARES" title="Pares a produzir">Pares a Prod.</label>
			<label title="Tempo previsto">Temp. Prev.</label>
		</div>
		<div class="panel-body">
			<label class="qtd">@{{ vm.TalaoProduzir.TOTALIZADOR.QUANTIDADE_PROJETADA | number: 4 }} @{{ vm.TalaoProduzir.TOTALIZADOR.QUANTIDADE_UM }}</label>
			<label ng-if="vm.Filtro.VER_PARES" class="pares">@{{ vm.TalaoProduzir.TOTALIZADOR.PAR_PRODUZIR | number : 0 }} prs</label>
			<label class="tempo">@{{ vm.TalaoProduzir.TOTALIZADOR.TEMPO_PREVISTO | number: 4 }} min</label>
		</div>
	</div>
</div>
