<div id="resumo-producao" class="scroll-dark collapse" aria-expanded="false">
	
	<label class="abrir-auto" title="Auto">Auto:</label>
	<input type="checkbox" class="chk-switch" checked="true" 
		   data-size="small" data-on-color="success" data-off-color="primary" data-on-text="on" 
		   data-off-text="off" data-label-width="16"
	/>
	
	<button type="button" class="btn btn-sm btn-default btn-voltar" id="fechar-resumo"  data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span>
		Voltar
	</button>

	<div class="status-container-resumo">
		<span id="status-resumo">@{{vm.FILTRO.UP_DESCRICAO}}</span>
	</div>

	<div class="modelo-container-resumo"  ng-repeat="estacao in vm.DADOS.ESTACOES track by $index">

		<div class="info-talao-modal2"
			 ng-repeat="talao in estacao.TALOES track by $index"
			 ng-if="talao.PROGRAMACAO_STATUS == 2"
			>
			<div class="descricao">@{{estacao.DESCRICAO}}</div>
			<div>
				<table class="tabela-items-info-talao">
					<colgroup>
						<col style="width: 10vw">
						<col style="width: 10vw">
						<col style="width: 20vw">
						<col style="width: 20vw">
						<col style="width: 10vw">
						<col style="width: 10vw">
						<col style="width: 10vw">
					</colgroup>
					<tr>
						<th>Remessa</th>
						<th>Talao</th>
						<th>Modelo</th>
						<th>Cor</th>
						<th>Tamanho</th>
						<th>Quantidade</th>
						<th>Tempo realizado</th>
					</tr>
					<tr>
						<td>@{{talao.REMESSA | number : 0}}</td>
						<td>@{{talao.REMESSA_TALAO_ID | number : 0}}</td>
						<td>@{{talao.MODELO_DESCRICAO}}</td>
						<td>@{{talao.COR_DESCRICAO}}</td>
						<td>@{{talao.TAMANHO | number : 0}}</td>
						<td>@{{talao.QUANTIDADE | number : 0}}</td>
						<td class="itens_tempo" data-data="@{{talao.SEGUNDOS[0].HORA}}">
							<span class="tempo-corrido"></span>
							<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="left" 
	                            title="Hora"
								style="float: right;" 
	                            on-finish-render="bs-init"

	                            data-element-content="#hora-info-estacao-@{{ talao.REMESSA_TALAO_ID }}"

								ng-class="{'ocultar-tocle' : item.SETUP_PREVISTO == 0}"

	                            ></span>


	                            <div id="hora-info-estacao-@{{ talao.REMESSA_TALAO_ID }}" style="display: none">
	                            	<table class='table table-striped table-bordered'>
	                                    <thead>
	                                        <tr>
	                                            <th class='text-left'>Data/Hora</th>
	                                            <th class='text-left'>Status</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr
	                                        	ng-repeat="item in talao.SEGUNDOS track by $index"
	                                        	ng-if="($index > 0) && (($index +1) < talao.SEGUNDOS.length)"
	                                        	>
	                                            <td class='text-left'>@{{item.HORA | toDate | date : 'dd/MM/yyyy hh:mm'}}</td>
	                                            <td class='text-left'>@{{item.STATUS}}</td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>	
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>

    <div class="contador-Atualizar">
	</div>
</div>

