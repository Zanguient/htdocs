<div class="recebe-puxador-talao">
    <div class="table-container">
        <table class="table table-bordered table-header table-lc table-talao-produzido">
            <thead>
                <tr>
                    <th class="t-status" title="Status do Talão"></th>
                    <th class="wid-up up" ng-if="vm.Filtro.UP_ID == ''">UP</th>
                    <th class="wid-estacao estacao">Estação</th>
                    <th class="text-center wid-remessa-data data-remessa" title="Data da remessa">Dt. Rem.</th>
                    <th class="wid-remessa remessa">Remessa</th>
                    <th class="text-center wid-talao talao">Talão</th>
                    <th class="text-center data-remessa-origem" title="Data da Remessa de Origem">Dt. Rem. Orig.</th>
                    <th class="text-right talao-origem" title="Informações de Origem">Inf.Orig.</th>
                    <th class="modelo">Modelo</th>
                    <th class="text-right densidade">Dens.</th>
                    <th class="text-right espessura">Esp.</th>                    
                    <th class="text-right qtd" title="Quantidade Projetada"
                        ng-class="{
                            'qtd-alternativa' : vm.TalaoProduzido.DADOS[0].QUANTIDADE_ALTERNATIVA > 0,
                            'qtd'             : !(vm.TalaoProduzido.DADOS[0].QUANTIDADE_ALTERNATIVA > 0)
                        }" >Qtd.</th>
                    <th class="text-right qtd-produzida" title="Quantidade a produzida">Qtd. Prod.</th>
                    <th class="text-right pares" ng-if="vm.Filtro.VER_PARES">Pares</th>
                    <th class="text-right tempo-prev"     title="Tempo previsto">Tempo Prev.</th>
                    <th class="text-right tempo-realiz"     title="Tempo realizado">Tempo Real.</th>
                    <th class="text-center data-ini-realiz" title="Data e hora iniciado realizado">Dt. Ini. Real.</th>
                    <th class="text-center data-fim-realiz" title="Data e hora finalizado realizado">Dt. Fin. Real.</th>
                    <th class="text-center eficiencia" title="Percentual de Eficiência ( ((Qtd. Proj. / Min. Proj.) / (Qtd. Prod. / Min. Prod.)) * 100 )">Eficiência</th>
                </tr>
            </thead>
        </table>
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-talao-produzido">
                <tbody>
                    <tr ng-repeat="talao in vm.TalaoProduzido.DADOS | orderBy : ['-DATAHORA_REALIZADO_FIM*1']"
                        tabindex="0" 
                        class="tipo-@{{ talao.REMESSA_TIPO }}" 
                        data-tipo="@{{ talao.REMESSA_TIPO }}" 
                        ng-focus="vm.TalaoProduzido.SELECTED != talao ? vm.TalaoProduzido.selectionar(talao) : ''"
                        ng-class="{'selected' : vm.TalaoProduzido.SELECTED == talao }"
                        >
                        <td class="t-status status-programacao status@{{ talao.PROGRAMACAO_STATUS }}" title="@{{ talao.PROGRAMACAO_STATUS_DESCRICAO }}"></td>
                        <td ng-if="vm.Filtro.UP_ID == ''" class="wid-up up" title="@{{ talao.UP_DESCRICAO }}">@{{ talao.UP_DESCRICAO }}</td>
                        <td class="wid-estacao estacao">@{{ talao.ESTACAO }} - @{{ talao.ESTACAO_DESCRICAO }}</td>
                        <td class="wid-remessa-data text-center data-remessa">@{{ talao.REMESSA_DATA | toDate | date:'dd/MM' : '+0' }}</td>
                        <td class="wid-remessa remessa" title="Id da Remessa: @{{ talao.REMESSA_ID }}">
                            @{{ talao.REMESSA }} 
                            <span class="tipo" ng-if="talao.REMESSA_TIPO != '1'">@{{ talao.REMESSA_TIPO_DESCRICAO }}</span>
                        </td>
                        <td class="wid-talao text-center talao" title="Id do Talão: @{{ talao.ID }}">@{{ talao.REMESSA_TALAO_ID }}</td>
                        
                        
                        
                        
                        <td class="text-center data-remessa-origem">@{{ talao.DATA_REMESSA_ORIGEM == '' ? '-' : talao.DATA_REMESSA_ORIGEM | toDate | date:'dd/MM' : '+0' }}</td>
                        <td class="text-right talao-origem">
                            <span
                                ng-if="talao.TALOES_ORIGEM.length > 0"
                                class="glyphicon glyphicon-info-sign" 
                                data-toggle="popover" 
                                data-placement="right" 
                                title="Informações"
                                data-element-content="#info-origem-@{{ talao.ID }}"
                                on-finish-render="bs-init"
                            ></span>
                            <div id="info-origem-@{{ talao.ID }}" style="display: none">
                                <div class='origem-container'>
                                    <fieldset ng-if="talao.TALOES_VINCULO.length > 0">
                                        <legend style="font-size: 12px; font-weight: bold;">Talões Vinculados</legend>
                                        <p ng-bind-html="vm.trustedHtml(talao.TALOES_VINCULO)"></p>
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
                        <td class="modelo">@{{ talao.MODELO_ID }} - @{{ talao.MODELO_DESCRICAO }}</td>
                        <td class="text-right densidade">@{{ talao.DENSIDADE | number: 2 }}</td>
                        <td class="text-right espessura">@{{ talao.ESPESSURA | number: 2 }}</td>
                        
                        <td class="text-right qtd"
                            ng-class="{
                                'qtd-alternativa' : talao.QUANTIDADE_ALTERNATIVA > 0,
                                'qtd'             : !(talao.QUANTIDADE_ALTERNATIVA > 0)
                            }"
                            > @{{ talao.QUANTIDADE_ALTERNATIVA > 0 ? talao.QUANTIDADE_ALTERNATIVA : talao.QUANTIDADE | number: 4 }} @{{ talao.QUANTIDADE_ALTERNATIVA > 0 ? talao.UM_ALTERNATIVA : talao.UM }}</td>
                        <td class="text-right  qtd-produzida um"> @{{ talao.QUANTIDADE_PRODUZIDA | number: 4 }} @{{ talao.UM }}</td>
                        
                        <td ng-if="vm.Filtro.VER_PARES" class="text-right pares">@{{ talao.PARES | number : 0 }}</td>
                        <td class="text-right tempo-prev">@{{ talao.TEMPO | number: 2 }} min</td>
                        <td class="text-right tempo-realiz">@{{ talao.TEMPO_REALIZADO | number: 2 }} min</td>
                        <td class="text-center data-ini-realiz">@{{ !talao.DATAHORA_REALIZADO_INICIO ? '' : talao.DATAHORA_REALIZADO_INICIO | toDate | date:'dd/MM HH:mm' }}</td>
                        <td class="text-center data-fim-realiz">@{{ !talao.DATAHORA_REALIZADO_FIM ? '' : talao.DATAHORA_REALIZADO_FIM | toDate | date:'dd/MM HH:mm' }}</td>
                        <td class="text-center eficiencia">
                            <div 
                                class="label percentual"
                                ng-class="{
                                    'label-danger' : talao.EFICIENCIA <= 90,
                                    'label-warning' : talao.EFICIENCIA > 90 && talao.EFICIENCIA <= 100,
                                    'label-success' : talao.EFICIENCIA > 100
                                }"
                                >
                                @{{ talao.EFICIENCIA | number: 2 }}%
                            </div>
                        </td>
                        
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
	<label class="legenda-label">{{ Lang::get($menu.'.legenda-status-talao') }}</label>
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

<div class="totalizador-produzido">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<label title="Quantidade Projetada">Qtd. Proj.</label>
            <label title="Quantidade Produzida">Qtd. Prod.</label>
            <label title="Pares produzidos" ng-if="vm.Filtro.VER_PARES">Pares Prod.</label>
            <label title="Tempo previsto">Temp. Prev.</label>
            <label title="Tempo realizado">Temp. Real.</label>
		</div>
        <div class="panel-body" style="text-transform: lowercase;">
			<label class="qtd">@{{ vm.TalaoProduzido.TOTALIZADOR.QUANTIDADE_PROJETADA | number: 4 }} @{{ vm.TalaoProduzido.TOTALIZADOR.QUANTIDADE_UM }}</label>
			<label class="qtd-produzida">@{{ vm.TalaoProduzido.TOTALIZADOR.QUANTIDADE_PRODUZIDA | number: 4 }} @{{ vm.TalaoProduzido.TOTALIZADOR.QUANTIDADE_UM }}</label>
			<label ng-if="vm.Filtro.VER_PARES" class="pares">@{{ vm.TalaoProduzido.TOTALIZADOR.PAR_PRODUZIDO | number : 0 }} prs</label>
			<label class="tempo">@{{ vm.TalaoProduzido.TOTALIZADOR.TEMPO_PREVISTO | number: 4 }} min</label>
			<label class="tempo-producao">@{{ vm.TalaoProduzido.TOTALIZADOR.TEMPO_REALIZADO | number: 4 }} min</label>
		</div>
	</div>
</div>
