	@extends('helper.include.view.modal', ['id' => 'modal-detalhar-producao', 'class_size' => 'modal-big'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Produção - @{{vm.PRODUCAO.ESTACAO}}
	</h4>

	@overwrite

	@section('modal-header-right')

		<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')
	
	<div class="tabela-producao-itens">
		<div class="table-container">

			<table class="tb-itens-prod table table-bordered table-header">

				<thead>
					<tr>
						<th class="col-remessa">Remessa</th>
						<th class="col-talao">Talão</th>
						<th class="col-requisicao">Requisição</th>
						<th class="col-modelo">Modelo</th>
						<th class="col-cor">Cor</th>
						<th class="col-tamanho">Tam.</th>
						<th class="col-quantidade">Qtd.</th>
						<th class="col-ferramenta">Ferramenta</th>
						<th class="col-datainicio">Dt. Inicio</th>
						<th class="col-datafim">Dt. Fim</th>
						<th class="col-revoperacional">Tmp. Prev. Opera.</th>
						<th class="col-realoperacional">Tmp. Real. Opera.</th>
						@php /*
						<th class="col-revoperacional">Tmp. Prev.</th>
						<th class="col-realoperacional">Tmp. Real.</th>
						@php */
						<th class="col-parado">Tmp. Parado</th>
						<th class="col-parado">Est. Parada</th>
						<th class="col-setupprev">Setup Prev.</th>
						<th class="col-setupreal">Setup Real.</th>
						<th class="col-perdas">Perdas</th>
						<th class="col-efic">Eficácia</th>
					</tr>
				</thead>

			</table>

			<div class="scroll-table">
				<table class="tb-itens-prod table table-striped table-bordered table-hover table-body">

					<tbody ng-repeat="esta in vm.PRODUCAO.ESTACOES track by $index">
						
						<tr>
							<td class="col-desc-fab-agrup" colspan="18">
								@{{esta.DESCRICAO}}
							</td>
						</tr>

						<tr ng-if="esta.ID == item.ESTACAO"
							ng-click="vm.Acoes.modalProdTalao(item)" ng-repeat="item in vm.PRODUCAO.ITENS track by $index">

							<td class="col-remessa">
								@{{item.REMESSA}}	
							</td>
							<td class="col-talao">
								@{{item.REMESSA_TALAO_ID}}	
							</td>
							<td class="col-requisicao">
								@{{item.REQUISICAO_ID}}	
							</td>
							<td class="col-modelo">
								@{{item.MODELO_ID}} - @{{item.MODELO_DESCRICAO}}	
							</td>
							<td class="col-cor">
								@{{item.COR_ID}} - @{{item.COR_DESCRICAO}}	
							</td>
							<td class="col-tamanho">
								@{{item.TAMANHO_DESCRICAO}}	
							</td>
							<td class="col-quantidade">
								@{{item.QUANTIDADE | number : 0}}	
							</td>
							<td class="col-ferramenta">
								@{{item.FERRAMENTA_ID}}	
							</td>
							<td class="col-datainicio">
								@{{item.DATAHORA_REALIZADO_INICIO}}	
							</td>
							<td class="col-datafim">
								@{{item.DATAHORA_REALIZADO_FIM}}	
							</td>
							<td class="col-revoperacional">
								@{{item.TEMPO_PREVISTO_OPERACIONAL | number : 2}}	
							</td>
							<td class="col-realoperacional">
								@{{item.TEMPO_REALIZADO_OPERACIONAL | number : 2}}	
							</td>
							<td class="col-parado">
								@{{item.TEMPO_PARADO | number : 2}}	
							</td>
							<td class="col-parado">
								@{{item.TEMPO_EXTRA  | number : 2}}	
							</td>
							<td class="col-setupprev"  style="text-align: right;">
								@{{item.SETUP_PREVISTO | number : 2}}

								<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="top" 
	                            title="Setup"

	                            on-finish-render="bs-init"

	                            data-element-content="#programacao-setup3-@{{ item.PROGRAMACAO_ID }}"

								ng-class="{'ocultar-tocle' : item.SETUP_PREVISTO == 0}"

	                            ></span>


	                            <div id="programacao-setup3-@{{ item.PROGRAMACAO_ID }}" style="display: none">
	                            	<table class='table table-striped table-bordered'>
	                                    <thead>
	                                        <tr>
	                                            <th class='text-left'>Setup</th>
	                                            <th class='text-left'>Tempo</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                            <td class='text-left'>TROCA MATRIZ:</td>
	                                            <td class='text-left'>@{{item.TEMPO_PREVISTO_FERRAMENTA | number : 2}}</td>
	                                        </tr>
	                                        <tr>
	                                            <td class='text-left'>AQUECIMENTO:</td>
	                                            <td class='text-left'>@{{item.TEMPO_PREVISTO_AQUECIMENTO | number : 2}}</td>
	                                        </tr>
	                                        <tr>
	                                            <td class='text-left'>APROV. COR:</td>
	                                            <td class='text-left'>@{{item.TEMPO_PREVISTO_APROVACAO | number : 2}}</td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>	
							</td>
							<td class="col-setupreal" style="text-align: right;">
								@{{item.SETUP_REALIZADO | number : 2}}

								<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="top" 
	                            title="Setup"

	                            on-finish-render="bs-init"

	                            data-element-content="#programacao-setup4-@{{ item.PROGRAMACAO_ID }}"
								
								ng-class="{'ocultar-tocle' : item.SETUP_REALIZADO == 0}"

	                            ></span>


	                            <div id="programacao-setup4-@{{ item.PROGRAMACAO_ID }}" style="display: none">
	                            	<table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Setup</th>
                                            <th class='text-left'>Tempo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>TROCA MATRIZ:</td>
                                            <td class='text-left'>@{{item.TEMPO_REALIZADO_FERRAMENTA | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>AQUECIMENTO:</td>
                                            <td class='text-left'>@{{item.TEMPO_REALIZADO_AQUECIMENTO | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>APROV. COR:</td>
                                            <td class='text-left'>@{{item.TEMPO_REALIZADO_APROVACAO | number : 2}}</td>
                                        </tr>
                                    </tbody>
                                </table>
	                            </div>	
							</td>
							<td class="col-perdas"
								ng-class="{
									'ind-vermelho' : item.PERDAS_PERC > item.PERDAS_B,
									'ind-amarelo'  : item.PERDAS_PERC >= item.PERDAS_A && item.PERDAS_PERC <= item.PERDAS_B,
									'ind-verde'	   : item.PERDAS_PERC < item.PERDAS_A
								}">
								@{{item.PERDAS | number : 1}}
							</td>
							<td class="col-efic"
								ng-class="{
									'ind-vermelho' : (item.COR_EFIC == 1) && (item.EFICIENCIA != 0),
									'ind-amarelo'  : (item.COR_EFIC == 2) && (item.EFICIENCIA != 0),
									'ind-verde'	   : (item.COR_EFIC == 3) && (item.EFICIENCIA != 0),
									'ind-branco'   : (item.EFICIENCIA == 0)
								}">
								@{{(item.EFICIENCIA | number : 2)}}
								
								<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="left" 
	                            title="Justificativa"

	                            on-finish-render="bs-init"

	                            data-element-content="#programacao-@{{ item.PROGRAMACAO_ID }}"

	                            ng-class="{'ocultar-tocle' : (item.JUSTIFICATIVA + '').length < 5}"

	                            ></span>
	                            <div id="programacao-@{{ item.PROGRAMACAO_ID }}" style="display: none">
	                            	<div ng-bind-html="item.JUSTIFICATIVA">
	                            	</div>
	                            </div>
								
							</td>

						</tr>	

					</tbody>

						<tr class="linha-total-prod">
							<td class="col-estacao">
								TOTAL	
							</td>
							<td class="col-talao">
								-	
							</td>
							<td class="col-requisicao">
								-
							</td>
							<td class="col-modelo">
								-	
							</td>
							<td class="col-cor">
								-
							</td>
							<td class="col-tamanho">
								-	
							</td>
							<td class="col-quantidade">
								@{{vm.PRODUCAO.TOTAL.QUANTIDADE | number : 0}}	
							</td>
							<td class="col-ferramenta">
								@{{vm.PRODUCAO.TOTAL.TROCAS}} Troc.	
							</td>
							<td class="col-datainicio">
								-	
							</td>
							<td class="col-datafim">
								-
							</td>
							<td class="col-revoperacional">
								@{{vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_OPERACIONAL | number : 2}}	
							</td>
							<td class="col-realoperacional">
								@{{vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL | number : 2}}	
							</td>

							<td class="col-parado">
								@{{vm.PRODUCAO.TOTAL.TEMPO_PARADO | number : 2}}	
							</td>
							<td class="col-parado">
								@{{vm.PRODUCAO.TOTAL.TEMPO_EXTRA| number : 2}}	
							</td>
							<td class="col-setupprev"  style="text-align: right;">
								@{{vm.PRODUCAO.TOTAL.SETUP_PREVISTO | number : 2}}

								<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="top" 
	                            title="Setup"

	                            on-finish-render="bs-init"

	                            data-element-content="#programacao-setup2-@{{ item.PROGRAMACAO_ID }}"

	                            ></span>


	                            <div id="programacao-setup2-@{{ item.PROGRAMACAO_ID }}" style="display: none">
	                            	<table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Setup</th>
                                            <th class='text-left'>Tempo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>TROCA MATRIZ:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_FERRAMENTA | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>AQUECIMENTO:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_AQUECIMENTO | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>APROV. COR:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_PREVISTO_APROVACAO | number : 2}}</td>
                                        </tr>
                                    </tbody>
                                </table>
	                            </div>

							</td>
							<td class="col-setupreal"  style="text-align: right;">
								@{{vm.PRODUCAO.TOTAL.SETUP_REALIZADO | number : 2}}

								<span class="glyphicon glyphicon-info-sign"
	                            data-toggle="popover" 
	                            data-placement="top" 
	                            title="Setup"

	                            on-finish-render="bs-init"

	                            data-element-content="#programacao-setup-@{{ item.PROGRAMACAO_ID }}"

	                            ></span>


	                            <div id="programacao-setup-@{{ item.PROGRAMACAO_ID }}" style="display: none">
	                            	<table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Setup</th>
                                            <th class='text-left'>Tempo</th>
                                            <th class='text-left'>Média</th>
                                            <th class='text-left'>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>TROCA MATRIZ:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_FERRAMENTA | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_FERRAMENTA | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.QUANT_REALIZADO_FERRAMENTA | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>AQUECIMENTO:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_AQUECIMENTO | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_AQUECIMENTO | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.QUANT_REALIZADO_AQUECIMENTO | number : 2}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>APROV. COR:</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_APROVACAO | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.MEDIA_REALIZADO_APROVACAO | number : 2}}</td>
                                            <td class='text-left'>@{{vm.PRODUCAO.TOTAL.QUANT_REALIZADO_APROVACAO | number : 2}}</td>
                                        </tr>
                                    </tbody>
                                </table>
	                            </div>

							</td>
							<td  class="col-perdas"
								ng-class="{
									'ind-vermelho' : vm.PRODUCAO.TOTAL.PERDAS_PERC > vm.PRODUCAO.TOTAL.PERDAS_B,
									'ind-amarelo'  : vm.PRODUCAO.TOTAL.PERDAS_PERC >= vm.PRODUCAO.TOTAL.PERDAS_A && vm.PRODUCAO.TOTAL.PERDAS_PERC <= vm.PRODUCAO.TOTAL.PERDAS_B,
									'ind-verde'	   : vm.PRODUCAO.TOTAL.PERDAS_PERC < vm.PRODUCAO.TOTAL.PERDAS_A
								}">
								@{{vm.PRODUCAO.TOTAL.PERDAS | number : 1}}	
							</td>
							<td  class="col-efic"
								ng-class="{
									'ind-vermelho' : vm.PRODUCAO.TOTAL.EFICIENCIA < vm.PRODUCAO.TOTAL.EFICIENCIA_A,
									'ind-amarelo'  : vm.PRODUCAO.TOTAL.EFICIENCIA >= vm.PRODUCAO.TOTAL.EFICIENCIA_A && vm.PRODUCAO.TOTAL.EFICIENCIA <= vm.PRODUCAO.TOTAL.EFICIENCIA_B,
									'ind-verde'	   : vm.PRODUCAO.TOTAL.EFICIENCIA > vm.PRODUCAO.TOTAL.EFICIENCIA_B
								}">
								@{{(vm.PRODUCAO.TOTAL.EFICIENCIA | number : 2)}}
							</td>
						</tr>

				</table>
			</div>
		</div>
	</div>

	<p>
	
	<div class="group-paradas">

		<div class="tabela-paradas-a">
			<div class="table-container">

				<table class="tb-itens-parada-a table table-bordered table-header">

					<thead>
						<tr>
							<th class="col2-motivo">Motivo</th>
							<th class="col2-parado">Parado</th>
							<th class="col2-iniciado">Iniciado</th>
							<th class="col2-operador">Operador</th>
							<th class="col2-tempo">Tempo Parado</th>
						</tr>
					</thead>

				</table>

				<div class="scroll-table">
					<table class="tb-itens-parada-a table table-striped table-bordered table-hover table-body">

						<tbody ng-repeat="esta in vm.PRODUCAO.ESTACOES2 track by $index">

							<tr>
								<td class="col2-motivo col-desc-fab-agrup" colspan="5">
									@{{esta.DESCRICAO}}
								</td>
							</tr>

							<tr ng-if="esta.ID == item.ESTACAO"
								ng-class="{
									'marcar-mot-just' : item.MARCAR == 1
								}"
								ng-repeat="item in vm.PRODUCAO.PARADAS_A track by $index">

								<td class="col2-motivo">
									@{{item.MOTIVO_PARADA}}	
								</td>

								<td class="col2-parado">
									@{{item.PARADO}}	
								</td>

								<td class="col2-iniciado">
									@{{item.INICIADO}}	
								</td>

								<td class="col2-operador">
									@{{item.OPERADOR}}	
								</td>

								<td class="col2-tempo">
									@{{item.TEMPO_PARADO == null ? '' : item.TEMPO_PARADO}}	
								</td>
							</tr>

						</tbody>

						<tbody>

							<tr class="linha-total-prod">
								<td class="col2-motivo">
									TOTAL
								</td>

								<td class="col2-parado">
									-
								</td>

								<td class="col2-iniciado">
									-	
								</td>

								<td class="col2-operador">
									-	
								</td>

								<td class="col2-tempo">
									@{{ vm.PRODUCAO.TOTAL.PARADAS_A | number : 0}}	
								</td>
							</tr>

						</tbody>

					</table>
				</div>
			</div>
		</div>

		<p>

		<div class="tabela-paradas-s">
			<div class="table-container">

				<table class="tb-itens-parada-s table table-bordered table-header">

					<thead>
						<tr>
							<th class="col2-motivo">Motivo</th>
							<th class="col2-tempo" >Tempo Parado</th>
							<th class="col3-tempo" >% R. Opera.</th>
						</tr>
					</thead>

				</table>

				<div class="scroll-table">
					<table class="tb-itens-parada-s table table-striped table-bordered table-hover table-body">

						<tbody ng-repeat="esta in vm.PRODUCAO.ESTACOES2 track by $index">

							<tr>
								<td class="col2-motivo col-desc-fab-agrup" colspan="5">
									@{{esta.DESCRICAO}}
								</td>
							</tr>

							<tr ng-if="esta.ID == item.ESTACAO"
								ng-class="{
									'marcar-mot-just' : item.MARCAR == 1
								}"
								ng-repeat="item in vm.PRODUCAO.PARADAS_S track by $index">

								<td class="col2-motivo">
									@{{item.MOTIVO_PARADA}}	
								</td>

								<td class="col2-tempo">
									@{{item.TEMPO_PARADO == null ? '' : item.TEMPO_PARADO}}	
								</td>

								<td class="col3-tempo">
									@{{((item.TEMPO_PARADO / vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL) * 100)  | number : 2}}
								</td>
							</tr>
						</tbody>

						<tbody>
							<tr class="linha-total-prod">
								<td class="col2-motivo">
									TOTAL
								</td>

								<td class="col2-tempo">
									@{{ vm.PRODUCAO.TOTAL.PARADAS_S | number : 0}}	
								</td>

								<td class="col3-tempo">
									@{{((vm.PRODUCAO.TOTAL.PARADAS_S / vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL) * 100)  | number : 2}}
								</td>
							</tr>

						</tbody>

					</table>
					
					<p>
					
				<table class="tb-itens-parada-s table table-bordered table-header">

					<thead>
						<tr>
							<th class="col2-motivo">Motivo</th>
							<th class="col2-tempo">Tempo Parado</th>
							<th class="col3-tempo" >% R. Opera.</th>
						</tr>
					</thead>

				</table>

				<div class="scroll-table">
					<table class="tb-itens-parada-s table table-striped table-bordered table-hover table-body">

						<tbody>
			
							<tr ng-class="{
									'marcar-mot-just' : item.MARCAR == 1
								}"
								ng-repeat="item in vm.PRODUCAO.TOTAL.MOTIVOS track by $index">

								<td class="col2-motivo">
									@{{item.DESCRICAO}}	
								</td>

								<td class="col2-tempo">
									@{{item.SOMA}}	
								</td>

								<td class="col3-tempo">
									@{{((item.SOMA / vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL) * 100)  | number : 2}}
								</td>

							</tr>

							<tr class="linha-total-prod">
								<td class="col2-motivo">
									TOTAL
								</td>

								<td class="col2-tempo">
									@{{ vm.PRODUCAO.TOTAL.PARADAS_S | number : 0}}	
								</td>

								<td class="col3-tempo">
									@{{((vm.PRODUCAO.TOTAL.PARADAS_S / vm.PRODUCAO.TOTAL.TEMPO_REALIZADO_OPERACIONAL) * 100)  | number : 2}}
								</td>
							</tr>

						</tbody>

					</table>					
				</div>
			</div>
			</div>
		</div>
	</div>

	@overwrite