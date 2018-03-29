<div class="conteiner-estacao">
	<table class="tg" style="undefined;table-layout: fixed; width: 399px">
		<colgroup>
			<col style="width: 7.066vw">
			<col style="width: 6vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
		</colgroup>
		<tr  ng-repeat="estacao in vm.PRODUCAO track by $index">
			<th class="tg-031g"><div style="text-overflow: ellipsis;width: 9vw;overflow: hidden;text-align: left;">@{{estacao.DESCRICAO}}</div>
				<button
					ng-if="estacao.ESTACAO > 0"
					ng-click="vm.Acoes.detalharProducao(estacao.ESTACAO,estacao.DESCRICAO);"
					type="button"
					class="btn btn-primary btn-confirmar"
					>
					<span class="glyphicon glyphicon-info-sign"></span>
				</button>
			</th>
			<th class="tg-031e f-verde">
				<div>@{{estacao.META | number : 0}}</div>				
			</th>
			<th class="tg-031f"></th>
			<th class="tg-031e f-verde">
				<div style="text-align: end; padding: 3px;">@{{estacao.PRODUCAO_T | number : 0}}
					<span class="glyphicon glyphicon-info-sign"
	                      data-toggle="popover" 
	                      data-placement="right" 
	                      title="Informações"
						  style="font-size: 1.5vw;" 
	                      on-finish-render="bs-init"
	                      data-element-content="#estacao-t@{{ estacao.ESTACAO }}"
		            ></span>
				</div>
				<div id="estacao-t@{{ estacao.ESTACAO }}" style="display: none">
                	<table class='table table-striped table-bordered'>
	                    <thead>
	                        <tr>
	                            <th class='text-left'></th>
	                            <th class='text-left'></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr ng-repeat="iteminfo in estacao.INFO_T track by $index">
	                            <td class='text-left'>@{{iteminfo[0]}}</td>
	                            <td class='text-left'>@{{iteminfo[1] | number : 2}}</td>
	                        </tr>
	                    </tbody>
	                </table>
                </div>			
            </th>
			<th class="tg-031e f-verde"
						ng-class="{
							'ind-vermelho' : (estacao.COR_EFIC_T   == 1) && (estacao.EFICIENCIA_T != 0),
							'ind-amarelo'  : (estacao.COR_EFIC_T   == 2) && (estacao.EFICIENCIA_T != 0),
							'ind-verde'	   : (estacao.COR_EFIC_T   == 3) && (estacao.EFICIENCIA_T != 0),
							'ind-branco'   : (estacao.EFICIENCIA_T == 0)
						}"
						><div>@{{estacao.EFICIENCIA_T | number : 2}}%</div></th>
			<th class="tg-031h f-verde"
						ng-class="{
							'ind-vermelho' : estacao.PERDAP_T > estacao.PERDA_B1,
							'ind-amarelo'  : estacao.PERDAP_T >= estacao.PERDA_A1 && estacao.PERDAP_T <= estacao.PERDA_B1,
							'ind-verde'	   : estacao.PERDAP_T < estacao.PERDA_A1
						}"><div>@{{estacao.PERDAP_T     | number : 1}}%</div><div>@{{estacao.PERDA_T | number : 0}}</div></th>
			<th class="tg-031f"></th>
			<th class="tg-031e f-verde">
				<div style="text-align: end; padding: 3px;">@{{estacao.PRODUCAO_G | number : 0}}
					<span class="glyphicon glyphicon-info-sign"
	                      data-toggle="popover" 
	                      data-placement="right" 
	                      title="Informações"
						  style="font-size: 1.5vw;" 
	                      on-finish-render="bs-init"
	                      data-element-content="#estacao-g@{{ estacao.ESTACAO }}"
		            ></span>
				</div>
				<div id="estacao-g@{{ estacao.ESTACAO }}" style="display: none">
                	<table class='table table-striped table-bordered'>
	                    <thead>
	                        <tr>
	                            <th class='text-left'></th>
	                            <th class='text-left'></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr ng-repeat="iteminfo in estacao.INFO_G track by $index">
	                            <td class='text-left'>@{{iteminfo[0]}}</td>
	                            <td class='text-left'>@{{iteminfo[1] | number : 2}}</td>
	                        </tr>
	                    </tbody>
	                </table>
                </div>
            </th>		
			<th class="tg-031e f-verde"
						ng-class="{
							'ind-vermelho' : (estacao.COR_EFIC_G   == 1) && (estacao.EFICIENCIA_G != 0),
							'ind-amarelo'  : (estacao.COR_EFIC_G   == 2) && (estacao.EFICIENCIA_G != 0),
							'ind-verde'	   : (estacao.COR_EFIC_G   == 3) && (estacao.EFICIENCIA_G != 0),
							'ind-branco'   : (estacao.EFICIENCIA_G == 0)
						}"
						><div>@{{estacao.EFICIENCIA_G | number : 2}}%</div></th>
			<th class="tg-031h f-verde"
						ng-class="{
							'ind-vermelho' : estacao.PERDAP_G > estacao.PERDA_B2,
							'ind-amarelo'  : estacao.PERDAP_G >= estacao.PERDA_A2 && estacao.PERDAP_G <= estacao.PERDA_B2,
							'ind-verde'	   : estacao.PERDAP_G < estacao.PERDA_A2
						}"><div>@{{estacao.PERDAP_G     | number : 1}}%</div><div>@{{estacao.PERDA_G | number : 0}}</div></th>
		</tr>

		<tr>
			<th class="tg-031g"><div>GERAL</div>
				<button
					ng-click="vm.Acoes.detalharProducao(vm.FILTRO.ESTACAO_ID,'GERAL');"
					type="button"
					class="btn btn-primary btn-confirmar"
					>
					<span class="glyphicon glyphicon-info-sign"></span>
				</button>
			</th>
			<th class="tg-031e f-verde"><div>@{{vm.PRODUCAO_TOTAL.T_META | number : 0}}</div></th>
			<th class="tg-031f"></th>
			<th class="tg-031e f-verde">
				<div style="text-align: end; padding: 3px;">@{{vm.PRODUCAO_TOTAL.T_PRODUCAO_T | number : 0}}
					<span class="glyphicon glyphicon-info-sign"
	                      data-toggle="popover" 
	                      data-placement="right" 
	                      title="Informações"
						  style="font-size: 1.5vw;" 
	                      on-finish-render="bs-init"
	                      data-element-content="#estacao-tt"
		            ></span>
				</div>
				<div id="estacao-tt" style="display: none">
                	<table class='table table-striped table-bordered'>
	                    <thead>
	                        <tr>
	                            <th class='text-left'></th>
	                            <th class='text-left'></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr ng-repeat="iteminfo in vm.PRODUCAO_TOTAL.INFO_T track by $index">
	                            <td class='text-left'>@{{iteminfo[0]}}</td>
	                            <td class='text-left'>@{{iteminfo[1] | number : 2}}</td>
	                        </tr>
	                    </tbody>
	                </table>
                </div>			
			</th>
			<th class="tg-031e f-verde"
						ng-class="{
							'ind-vermelho' : vm.PRODUCAO_TOTAL.T_EFICIENCIA_T < vm.PRODUCAO_TOTAL.EFICIENCIA_A1 && vm.PRODUCAO_TOTAL.T_EFICIENCIA_T != 0,
							'ind-amarelo'  : vm.PRODUCAO_TOTAL.T_EFICIENCIA_T >= vm.PRODUCAO_TOTAL.EFICIENCIA_A1 && vm.PRODUCAO_TOTAL.T_EFICIENCIA_T <= vm.PRODUCAO_TOTAL.EFICIENCIA_B1  && vm.PRODUCAO_TOTAL.T_EFICIENCIA_T != 0,
							'ind-verde'	   : vm.PRODUCAO_TOTAL.T_EFICIENCIA_T > vm.PRODUCAO_TOTAL.EFICIENCIA_B1  && vm.PRODUCAO_TOTAL.T_EFICIENCIA_T != 0,
							'ind-branco'   : vm.PRODUCAO_TOTAL.T_EFICIENCIA_T == 0
						}"><div>@{{vm.PRODUCAO_TOTAL.T_EFICIENCIA_T | number : 2}}%</div></th>
			<th class="tg-031h f-verde"
						ng-class="{
							'ind-vermelho' : vm.PRODUCAO_TOTAL.T_PERDAP_T > vm.PRODUCAO_TOTAL.PERDA_B1,
							'ind-amarelo'  : vm.PRODUCAO_TOTAL.T_PERDAP_T >= vm.PRODUCAO_TOTAL.PERDA_A1 && vm.PRODUCAO_TOTAL.T_PERDAP_T <= vm.PRODUCAO_TOTAL.PERDA_B1,
							'ind-verde'	   : vm.PRODUCAO_TOTAL.T_PERDAP_T < vm.PRODUCAO_TOTAL.PERDA_A1
						}"><div>@{{vm.PRODUCAO_TOTAL.T_PERDAP_T     | number : 1}}%</div><div>@{{vm.PRODUCAO_TOTAL.T_PERDA_T | number : 0}}</div></th>
			<th class="tg-031f"></th>
			<th class="tg-031e f-verde">
				<div style="text-align: end; padding: 3px;">@{{vm.PRODUCAO_TOTAL.T_PRODUCAO_G | number : 0}}
					<span class="glyphicon glyphicon-info-sign"
	                      data-toggle="popover" 
	                      data-placement="right" 
	                      title="Informações"
						  style="font-size: 1.5vw;" 
	                      on-finish-render="bs-init"
	                      data-element-content="#estacao-tg"
		            ></span>
				</div>
				<div id="estacao-tg" style="display: none">
                	<table class='table table-striped table-bordered'>
	                    <thead>
	                        <tr>
	                            <th class='text-left'></th>
	                            <th class='text-left'></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr ng-repeat="iteminfo in vm.PRODUCAO_TOTAL.INFO_G track by $index">
	                            <td class='text-left'>@{{iteminfo[0]}}</td>
	                            <td class='text-left'>@{{iteminfo[1] | number : 2}}</td>
	                        </tr>
	                    </tbody>
	                </table>
                </div>		


			</th>
			<th class="tg-031e f-verde" ng-class="{
							'ind-vermelho' : vm.PRODUCAO_TOTAL.T_EFICIENCIA_G < vm.PRODUCAO_TOTAL.EFICIENCIA_A2 && vm.PRODUCAO_TOTAL.T_EFICIENCIA_G != 0,
							'ind-amarelo'  : vm.PRODUCAO_TOTAL.T_EFICIENCIA_G >= vm.PRODUCAO_TOTAL.EFICIENCIA_A2 && vm.PRODUCAO_TOTAL.T_EFICIENCIA_G <= vm.PRODUCAO_TOTAL.EFICIENCIA_B2  && vm.PRODUCAO_TOTAL.T_EFICIENCIA_G != 0,
							'ind-verde'	   : vm.PRODUCAO_TOTAL.T_EFICIENCIA_G > vm.PRODUCAO_TOTAL.EFICIENCIA_B2  && vm.PRODUCAO_TOTAL.T_EFICIENCIA_G != 0,
							'ind-branco'   : vm.PRODUCAO_TOTAL.T_EFICIENCIA_G == 0
						}"><div>@{{vm.PRODUCAO_TOTAL.T_EFICIENCIA_G | number : 2}}%</div></th>
			<th class="tg-031h f-verde" 
						ng-class="{
							'ind-vermelho' : vm.PRODUCAO_TOTAL.T_PERDAP_G > vm.PRODUCAO_TOTAL.PERDA_B2,
							'ind-amarelo'  : vm.PRODUCAO_TOTAL.T_PERDAP_G >= vm.PRODUCAO_TOTAL.PERDA_A2 && vm.PRODUCAO_TOTAL.T_PERDAP_G <= vm.PRODUCAO_TOTAL.PERDA_B2,
							'ind-verde'	   : vm.PRODUCAO_TOTAL.T_PERDAP_G < vm.PRODUCAO_TOTAL.PERDA_A2
						}"><div>@{{vm.PRODUCAO_TOTAL.T_PERDAP_G     | number : 1}}%</div><div>@{{vm.PRODUCAO_TOTAL.T_PERDA_G | number : 0}}</div></th>
		</tr>
	</table>

</div>

<br>

<table class="tg" style="table-layout: fixed; overflow: hidden;">
<colgroup>
	<col style="width: 7.066vw">
	<col style="width: 6vw">
	<col style="width: 3px">
	<col style="width: 6.533vw">
	<col style="width: 6.533vw">
	<col style="width: 6.533vw">
	<col style="width: 6.533vw">
	<col style="width: 6.533vw">
	<col style="width: 6.533vw">
	<col style="width: 3px">
</colgroup>
  <tr>
  	<td class="tg-1sci" colspan="2">ESTAÇÃO</td>
    <td class="tg-fqys"></td>
    <td class="tg-1sci" colspan="7">TALÕES EM PRODUÇÃO</td>
  </tr>
</table>
<div class="conteiner-estacao">
	<table class="tg" style="undefined;table-layout: fixed; width: 399px">
	<colgroup>
		<col style="width: 7.066vw">
		<col style="width: 6vw">
		<col style="width: 3px">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 3px">
	</colgroup>
		<tr ng-repeat="estacao in vm.DADOS.ESTACOES track by $index">

			<th class="tg-031g" colspan="2">
				<div style="width: 24vw;"><span style="text-overflow: ellipsis;width: 10vw;overflow: hidden; text-align: left;">@{{estacao.DESCRICAO}}</span>

				<button
					ng-click="vm.Acoes.confirmeJornada(estacao.ESTACAO);"
					type="button"
					class="btn btn-primary btn-confirmar"
					id="btn-confirmar-up"
					>
					Jornada
				</button>
    
				<button
					ng-if="estacao.STATUS_PARADA > 0"
					ng-click="vm.Acoes.modalLogin2(estacao);"
					type="button"
					class="btn btn-danger btn-confirmar"
					id="btn-confirmar-up">
					Parada
				</button>

				<button
					ng-if="estacao.STATUS_PARADA == 0"
					ng-click="vm.Acoes.pararEstacao(estacao);"
					type="button"
					class="btn btn-success btn-confirmar"
					id="btn-confirmar-up"
					>
					Iniciada
				</button>

				<button
					ng-click="vm.Acoes.telaSetup(estacao,true);"
					ng-if="(estacao.TALOES[0].PROGRAMACAO_STATUS == 2) && (estacao.TALOES[0].SETUP_TOTAL > 0)"
					type="button"
					class="btn"
					ng-class="{
						'btn-success btn-confirmar'	 : estacao.TALOES[0].SETUP.STATUS < 3,
						'btn-default btn-confirmar'	 : estacao.TALOES[0].SETUP.STATUS == 3
					}"
					>
					Setup
				</button>

				</div>
			</th>

			<th class="tg-031f"></th>

			<th class="tg-031j" id="basic" colspan="6">
				<div class="linha-talao">

					<div 
					class="scoll-talao  "

						ng-class="{
							'f-preto'	  : talao.STATUS == 1 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'	  : talao.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2,
							'f-verde'	  : talao.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'	  : talao.PROGRAMACAO_STATUS == 2,	
							'b-cinza'	  : talao.ATRASADO  == 1 && talao.CONFLITO == 0 && talao.FERRAMENTA_ID > 1 && talao.TALAO_ENCERRADO == 0,
							'b-creme'	  : talao.ATRASADO  == 0 && talao.ATRASADO2 == 1 && talao.CONFLITO == 0  && talao.FERRAMENTA_ID > 1  && talao.TALAO_ENCERRADO == 0,
							'b-vermelho'  : talao.CONFLITO  == 1 && talao.TALAO_ENCERRADO == 0,
							'b-sobra'     : talao.CONFLITO  == 0 && talao.TALAO_ENCERRADO == 0 && talao.FERRAMENTA_ID == 1,
							'b-encerrado' : talao.TALAO_ENCERRADO == 1
							}"

						ng-click="vm.Acoes.initModal(talao,estacao)"

						ng-repeat="talao in estacao.TALOES track by $index"
						ng-if="$index < 6">

						<div class="table-cell">
							<div class="grup-valores">
								<div class="div-info-1">
									<div>@{{talao.MODELO_DESCRICAO}}</div>
									<div>@{{talao.COR_DESCRICAO}}</div>
								</div>
								<div class="div-info-2">
									<div class="remessa">@{{talao.REMESSA}}</div>
						    		<div class="talao"  >@{{talao.REMESSA_TALAO_ID}}</div>
						    		<div class="tamanho">@{{talao.TAMANHO}}/@{{ talao.STATUS_REQUISICAO == 1 ? talao.QTD_REQUISICAO : talao.QUANTIDADE | number : 0}}</div>
									<span ng-if="talao.JUSTIFICATIVA_ORIGEM.length > 0" class="justificativa-alerta " ttitle="@{{talao.JUSTIFICATIVA_ORIGEM}}" >!</span>	
								</div>
							</div>
							<div class="grup-legenda">
							    <table>
									<tr>
										<td class="f-preto">
										<span class="glyphicon glyphicon-plus" style="font-size: 0.5vw;"
										ng-class="{
										'f-preto'	  : talao.STATUS_EXTRA == 1 && talao.PROGRAMACAO_STATUS != 2,
										'f-amarelo'	  : talao.STATUS_EXTRA == 2 && talao.PROGRAMACAO_STATUS != 2,
										'f-verde'	  : talao.STATUS_EXTRA == 3 && talao.PROGRAMACAO_STATUS != 2,
										'f-azul'	  : talao.PROGRAMACAO_STATUS == 2,
										}"
										ng-if="talao.TALAO_EXTRA > 0 && talao.STATUS_REQUISICAO == 0"></span></td>
										<td class="@{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ? 'f-verde' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'S' ? 'f-azul' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ? 'f-preto' : ''}}">@{{talao.TROCA_MATRIZ}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_VIP}}</td>
										<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
										<td class="f-preto">@{{talao.TROCA_REQUISICAO}}</td>
									</tr>
								</table>
							</div>
						</div>	
					</div>

				</div>
			</th>

			<th class="tg-031f"></th>

		</tr>
	</table>

</div>

<div class="conteiner-estacao">
	<table class="tg" style="undefined;table-layout: fixed; width: 399px">
	<colgroup>
		<col style="width: 7.066vw">
		<col style="width: 6vw">
		<col style="width: 3px">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 3px">
	</colgroup>
	<tr>
		<th class="tg-031g" colspan="2" rowspan="2"><div>Requisições</div></th>
		<th class="tg-031f"></th>

		<th class="tg-031j" id="basic" colspan="6">
			<div class="linha-talao">

				<div 
				class="scoll-talao"

					ng-class="{
							'f-preto'	 : talao.REQUISICAO.STATUS < 2 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'	 : talao.REQUISICAO.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2,
							'f-verde'	 : talao.REQUISICAO.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'	 : talao.PROGRAMACAO_STATUS == 2,	
							'b-cinza'	 : talao.ATRASADO  == 1,
							'b-creme'	 : talao.ATRASADO  == 0 && talao.ATRASADO2 == 1
							}"

					ng-click="vm.Acoes.initModal(talao,null)"

					ng-repeat="talao in vm.DADOS.PARADOS1 | orderBy:'-REQUISICAO_STATUS' track by $index"
					ng-if="$index < 6">

					<div class="table-cell">
						<div class="grup-valores">
							<div class="div-info-1">
								<div>@{{talao.MODELO_DESCRICAO}}</div>
								<div>@{{talao.COR_DESCRICAO}}</div>
							</div>
							<div class="div-info-2">
								<div class="remessa">@{{talao.REMESSA}}</div>
					    		<div class="talao"  >@{{talao.REMESSA_TALAO_ID}}</div>
					    		<div class="tamanho">@{{talao.TAMANHO}}@{{talao.QUANTIDADE > 0 ? '/' : ''}}@{{ talao.STATUS_REQUISICAO == 1 ? talao.QTD_REQUISICAO : talao.QUANTIDADE | number : 0}}</div>
							</div>
						</div>
						<div class="grup-legenda">
					    	<div>
					    		<table>
									<tr>
										<td class="f-preto"><span class="glyphicon glyphicon-plus" style="font-size: 0.5vw;" ng-if="talao.TALAO_EXTRA > 0 && talao.STATUS_REQUISICAO == 0"></span></td>
										<td class="f-preto">@{{talao.TROCA_MATRIZ}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_VIP}}</td>
										<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
										<td class="f-preto">@{{talao.TROCA_REQUISICAO}}</td>
									</tr>
								</table>
					    	</div>
						</div>
					</div>	
				</div>
			</div>
		</th>

		<th class="tg-031f"></th>
	</tr>

	<tr>

		<th class="tg-031f"></th>

		<th class="tg-031j" id="basic" colspan="6">
			<div class="linha-talao">

				<div 
				class="scoll-talao"

				ng-class="{
							'f-preto'	 : talao.REQUISICAO.STATUS < 2 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'	 : talao.REQUISICAO.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2,
							'f-verde'	 : talao.REQUISICAO.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'	 : talao.PROGRAMACAO_STATUS == 2,	
							'b-cinza'	 : talao.ATRASADO  == 1,
							'b-creme'	 : talao.ATRASADO  == 0 && talao.ATRASADO2 == 1
							}"

					ng-click="vm.Acoes.initModal(talao,null)"

					ng-repeat="talao in vm.DADOS.PARADOS2 | orderBy:'-REQUISICAO_STATUS' track by $index"
					ng-if="$index < 6">

					<div class="table-cell">
						<div class="grup-valores">
							<div class="div-info-1">
								<div>@{{talao.MODELO_DESCRICAO}}</div>
								<div>@{{talao.COR_DESCRICAO}}</div>
							</div>
							<div class="div-info-2">
								<div class="remessa">@{{talao.REMESSA}}</div>
					    		<div class="talao"  >@{{talao.REMESSA_TALAO_ID}}</div>
					    		<div class="tamanho">@{{talao.TAMANHO}}@{{talao.QUANTIDADE > 0 ? '/' : ''}}@{{ talao.STATUS_REQUISICAO == 1 ? talao.QTD_REQUISICAO : talao.QUANTIDADE | number : 0}}</div>
							</div>
						</div>
						<div class="grup-legenda">
					    	<div>
					    		<table>
									<tr>
										<td class="f-preto"><span class="glyphicon glyphicon-plus" style="font-size: 0.5vw;" ng-if="talao.TALAO_EXTRA > 0 && talao.STATUS_REQUISICAO == 0"></span></td>
										<td class="f-preto">@{{talao.TROCA_MATRIZ}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_VIP}}</td>
										<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
									</tr>
									<tr>
										<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
										<td class="f-preto">@{{talao.TROCA_REQUISICAO}}</td>
									</tr>
								</table>
					    	</div>
						</div>
					</div>	
				</div>
			</div>
		</th>

		<th class="tg-031f"></th>
	</tr>

	</table>

</div>


