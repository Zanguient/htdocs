	@extends('helper.include.view.modal', ['id' => 'modal-producao-talao', 'class_size' => 'modal-big'])

	@section('modal-header-left')

	<h4 class="modal-title">
		@{{vm.MODAL.PRODUTO_ID}} - @{{vm.MODAL.PRODUTO}}
	</h4>

	@overwrite

	@section('modal-header-right')

		<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')

		<div class="erros-talao" style="margin-top:0px;">
			<div class="group-info-modal">

			<div class="info-talao-modal" style="width: 100%;">
					<div class="descricao">Talão</div>
					<div>
						<table class="tabela-items-info-talao table table-striped table-bordered table-hover lista-obj">

							<colgroup>
								<col style="width: 8%">
								<col style="width: 12%">
								<col style="width: 13%">
								<col style="width: 20%">
								<col style="width: 30%">
								<col style="width: 17%">
							</colgroup>

							<tr>
								<th>TAMANHO</th>
								<th>SKU</th>
								<th>FERRAMENTA</th>
								<th>MODELO</th>
								<th>LINHA</th>
								<th>OB</th>
							</tr>
							<tr>
								<td>
									@{{vm.MODAL.TAMANHO}}
								</td>
								<td>
									@{{vm.MODAL.INFO_TALAO.SKU.DESCRICAO}} 	
								</td>
								<td>
									@{{vm.MODAL.SERIE}}
								</td>
								<td>
									@{{vm.MODAL.MODELO_ID}} - @{{vm.MODAL.MODELO_DESCRICAO}}	
								</td>
								<td>
									@{{vm.MODAL.LINHA_DESCRICAO}}		
								</td>
								<td>
									@{{vm.MODAL.INFO_TALAO.TECIDO}}		
								</td>
							</tr>
						</table>

						<table class="tabela-items-info-talao table table-striped table-bordered table-hover lista-obj">
							
							<colgroup>
								<col style="width: 10%">
								<col style="width: 20%">
								<col style="width: 15%">
								<col style="width: 20%">
								<col style="width: 15%">
								<col style="width: 10%">
								<col style="width: 10%">
							</colgroup>

							<tr>
								<th>DENSIDADE</th>
								<th>ESPESSURA</th>
								<th>QUANTIDADE</th>
								<th>COR</th>
								<th>TALÃO ID</th>
								<th>LEGENDAS</th>
								<th>DATA REMESSA</th>
							</tr>
							<tr>
								<td>
									@{{vm.MODAL.DENSIDADE}}	
								</td>
								<td>
									@{{vm.MODAL.ESPESSURA}}	
								</td>
								<td>
									@{{ vm.MODAL.QUANTIDADE | number : 0}}
								</td>
								<td>
									@{{vm.MODAL.COR_CODIGO}} - @{{vm.MODAL.COR_DESCRICAO}}		
								</td>
								<td>
									@{{vm.MODAL.ID}}		
								</td>
								<td>
									<strong class="f-preto"> @{{vm.MODAL.TROCA_AMOSTRA}}</strong>
									<strong class="f-preto"> @{{vm.MODAL.TROCA_MATRIZ}}</strong>
									<strong class="f-preto"> @{{vm.MODAL.TROCA_VIP}}</strong>	
								</td>
								<td>
									@{{vm.MODAL.DATA_REMESSA}}
								</td>
							</tr>
						</table>

					</div>
				</div>

				<p>

				<div class="info-talao-modal"  style="width: 33%;">
					<div class="descricao">Origem</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<th>Pedido</th>
								<th>Data Cliente</th>
								<th>Cliente</th>
							</tr>
							<tr ng-repeat="pedido in vm.MODAL.INFO_TALAO.PEDIDOS track by $index" >
								<td>@{{pedido.PEDIDO}}</td>
								<td>@{{pedido.DATA  | toDate | date:'dd/MM/yyyy'}}</td>
								<td>@{{pedido.NOMEFANTASIA}}</td>
							</tr>
						</table>
					</div>
				</div>

				<div ng-if="vm.MODAL.TALAO_EXTRA > 0"
					class="info-talao-modal"  style="width: 33%;">
					<div class="descricao">Talão Extra</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<th>Talão</th>
								<th>Quantidade</th>
								<th>%</th>
							</tr>
							<tr>
								<td>@{{vm.MODAL.TALAO_EXTRA}}</td>
								<td>@{{vm.MODAL.QUANTIDADE_EXTRA | number : 0}}</td>
								<td>@{{vm.MODAL.PERCENTUAL_EXTRA | number : 3}}</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="info-talao-modal" ng-if="vm.MODAL.STATUS_REQUISICAO == 1" style="width: 33%;">
					<div class="descricao">Requisições</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<th>Requisição</th>
								<th>Remessa</th>
								<th>Talão</th>
								<th>Qtd.</th>
								<th>Status</th>
							</tr>
							<tr ng-repeat="item in vm.MODAL.REQUISICAO.ITENS track by $index" >
								<td>@{{item.ID}}</td>
								<td>@{{item.REMESSA}}</td>
								<td>@{{item.TALAO}}</td>
								<td>@{{item.QUANTIDADE | number : 0}}</td>
								<td>@{{item.DESC_STATUS}}</td>
							</tr>
						</table>
					</div>
				</div>

				<p>

				<div class="info-talao-modal"  style="width: 28%;">
					<div class="descricao">Programação</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<td>Inicio/Fim:</td>
								<td>@{{ vm.MODAL.DATAHORA_INICIO  | toDate | date:'dd/MM HH:mm'}} a @{{vm.MODAL.DATAHORA_FIM  | toDate | date:'dd/MM HH:mm'}}</td>
							</tr>
							<tr>
								<td>Tempo Previsto:</td>
								<td>@{{vm.MODAL.TEMPO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Tempo Operacional:</td>
								<td>@{{vm.MODAL.TEMPO_OPERACIONAL | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Troca Matriz:</td>
								<td>@{{vm.MODAL.TEMPO_SETUP_FERRAMENTA | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Aquecimento:</td>
								<td>@{{(vm.MODAL.TEMPO_SETUP_AQUECIMENTO | number : 0 + vm.MODAL.TEMPO_SETUP_COR | number : 0)}} Min.</td>
							</tr>
							<tr>
								<td>Setup Aprov. Cor:</td>
								<td>@{{vm.MODAL.TEMPO_SETUP_APROVACAO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Tempo Maquina:</td>
								<td>@{{vm.MODAL.TEMPO_MAQUINA | number : 0}} Seg.</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="info-talao-modal"  style="width: 28%;">
					<div class="descricao">Realizado</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<td>Inicio/Fim:</td>
								<td>@{{ vm.MODAL.ITEM_PRODUCAO.DATAHORA_REALIZADO_FIM}} a @{{vm.MODAL.ITEM_PRODUCAO.DATAHORA_FIM}}</td>
							</tr>
							<tr>
								<td>Tempo Realizado:</td>
								<td>@{{vm.MODAL.ITEM_PRODUCAO.TEMPO_REALIZADO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Tempo Operacional:</td>
								<td>@{{vm.MODAL.ITEM_PRODUCAO.TEMPO_REALIZADO_OPERACIONAL | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Troca Matriz:</td>
								<td>@{{vm.MODAL.ITEM_PRODUCAO.TEMPO_REALIZADO_FERRAMENTA | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Aquecimento:</td>
								<td>@{{vm.MODAL.ITEM_PRODUCAO.TEMPO_REALIZADO_AQUECIMENTO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Aprov. Cor:</td>
								<td>@{{vm.MODAL.ITEM_PRODUCAO.TEMPO_REALIZADO_APROVACAO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Tempo Maquina:</td>
								<td></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="info-talao-modal">
					<div class="descricao">Rastreabilidade</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<th>Descrição</th>
								<th>Remessa</th>
								<th>Talão</th>
								<th>Data/Hora Produção</th>
							</tr>
							<tr ng-repeat="componente in vm.MODAL.INFO_TALAO.COMPONENTE track by $index" >
								<td><span style="cursor: pointer;" title="Ficha de Produção" ng-click="vm.Acoes.infoComponentes(componente)" class="glyphicon glyphicon-new-window"></span> - @{{componente.DESCRICAO}}</td>
								<td>@{{componente.REMESSA_ID}}</td>
								<td>@{{componente.REMESSA_TALAO_ID}}</td>
								<td>@{{componente.DATA_PRODUCAO  | toDate | date:'dd/MM/yyyy HH:mm'}}</td>
							</tr>
						</table>

						<table class="tabela-items-info-talao">
							<tr>
								<th>Data Corte:</th>
								<th>Data Liberação:</th>
							</tr>
								<td>@{{vm.MODAL.DATAHORA_PRODUCAO}}</td>
								<td>@{{vm.MODAL.DATAHORA_LIBERACAO}}</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="info-talao-modal">
					<div class="descricao">Histórico</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<th>Status</th>
								<th>Data/Hora</th>
								<th>Operador</th>
								<th>Justificativa</th>
								<th>Estação</th>
								<th>Tipo</th>
								<th>TEMPO</th>
							</tr>
							<tr ng-repeat="historico in vm.MODAL.INFO_TALAO.HISTORICO track by $index" >
								<td>@{{historico.STATUS}}</td>
								<td>@{{historico.DATAHORA}}</td>
								<td>@{{historico.OPERADOR}}</td>
								<td>@{{historico.JUSTIFICATIVA}}</td>
								<td>@{{historico.ESTACAO}}</td>
								<td>@{{historico.TIPO}}</td>
								<td ng-class="{
										'ind-vermelho' : historico.FLAG == 0,
										'ind-amarelo'  : historico.FLAG == 1,
										'ind-verde'	   : historico.FLAG == 2,
										'ind-branco'   : historico.FLAG == 3
									}">@{{ historico.FLAG == 3 ? 'REC' : historico.TEMPO}}</td>
							</tr>
						</table>
					</div>
				</div>

			</div>
		</div>
@overwrite