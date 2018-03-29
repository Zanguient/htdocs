	@extends('helper.include.view.modal', ['id' => 'modal-detalhar-talao', 'class_size' => 'modal-big'])

	@section('modal-header-left')

	<h4 class="modal-title">
		<a title="Clique aqui para consultar o estoque deste produto" href="{{url('_15060')}}?PRODUTO_ID=@{{vm.MODAL.PRODUTO_ID}}" target="_blank" class="ng-binding">@{{vm.MODAL.PRODUTO_ID}}</a>  - @{{vm.MODAL.PRODUTO}}
	</h4>

	@overwrite

	@section('modal-header-right')

		<button style="display: none;" type="button" class="btn btn-default" id="iniciar" ng-click="vm.Acoes.atualizarTalao()">
			<span class="glyphicon glyphicon-refresh"></span> Atualizar
		</button>

		<button type="button" class="btn btn-success" id="iniciar" data-hotkey="home"
			ng-disabled="vm.MODAL.TALAO_ENCERRADO == 0 && ((vm.MODAL.STATUS_EXTRA > 0 && vm.MODAL.STATUS_EXTRA < 3 && vm.MODAL.STATUS_REQUISICAO == 0) || vm.MODAL.ORDEM > 0   || ((vm.MODAL.PROGRAMACAO_STATUS == 2 || vm.MODAL.PROGRAMACAO_STATUS == 3 || vm.MODAL.PROGRAMACAO_STATUS == 6 || vm.OPERADOR.LOGADO == false || vm.MODAL.MAQUINA.STATUS_PARADA > 0 || vm.MODAL.STATUS != 3) && vm.MODAL.TALAO_ENCERRADO == 0))"
			ng-click="vm.Acoes.validarMatriz()"
			>
			<span class="glyphicon glyphicon-play"></span> {{ Lang::get('master.iniciar') }}
		</button>

		<button type="button" class="btn btn-primary" id="pausar" data-hotkey="pause"
			ng-disabled="vm.MODAL.ORDEM > 0  || vm.MODAL.PROGRAMACAO_STATUS == 0 || vm.MODAL.PROGRAMACAO_STATUS == 1 || vm.MODAL.PROGRAMACAO_STATUS == 3 || vm.MODAL.PROGRAMACAO_STATUS == 6 || vm.OPERADOR.LOGADO == false || vm.MODAL.MAQUINA.STATUS_PARADA > 0"
			ng-click="vm.Acoes.pararTalao(vm.MODAL)"
			>
			<span class="glyphicon glyphicon-pause"></span> {{ Lang::get('master.pausar') }}
		</button>

		<button type="button" class="btn btn-danger" id="finalizar" data-hotkey="end"
			ng-disabled="vm.MODAL.ORDEM > 0  || vm.MODAL.PROGRAMACAO_STATUS == 0 || vm.MODAL.PROGRAMACAO_STATUS == 1 || vm.MODAL.PROGRAMACAO_STATUS == 3 || vm.MODAL.PROGRAMACAO_STATUS == 6 || vm.OPERADOR.LOGADO == false || vm.MODAL.MAQUINA.STATUS_PARADA > 0"
			ng-click="vm.Acoes.finalizarTalao()"
			>
			<span class="glyphicon glyphicon-stop"></span> {{ Lang::get('master.finalizar') }}
		</button>

		<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')

		<button ng-click="vm.Acoes.justInefic(vm.MODAL)" type="button" class="btn btn-temp btn-warning">
			<span class="glyphicon glyphicon-tags"></span> Justificar
		</button>

        <div id="info-destaque">	
			<div class="label label-warning" id="operador">
				<span>Operador:</span>
				<span class="valor" ng-if="vm.OPERADOR.LOGADO == true">
					@{{vm.OPERADOR.OPERADOR_NOME}}
					<button ng-click="vm.Acoes.LogOff()" type="button" class="btn  btn-temp btn-warning" id="finalizar">
						<span class="glyphicon glyphicon-user"></span> logOff
					</button>
				</span>
				<span class="valor" ng-if="vm.OPERADOR.LOGADO == false">
					<button ng-click="vm.Acoes.modalLogin()" type="button" class="btn  btn-temp btn-warning" id="finalizar">
						<span class="glyphicon glyphicon-user"></span> Login
					</button>
				</span>
				<input type="hidden" name="_operador_id" id="_operador-id" autocomplete="off">
			</div>
			<div class="label label-primary" id="up-destaque">
				<span>UP:</span>
				<span class="valor">@{{vm.FILTRO.UP_DESCRICAO}}</span>
			</div>
			<div class="label label-danger" id="estacao-destaque">
				<span>Estação:</span>
				<span class="valor">
					@{{vm.MODAL.DESCRICAO}}
					<button ng-click="vm.Acoes.modalTrocaEstacao()" type="button" ng-if="vm.MODAL.PROGRAMACAO_STATUS != 2 && vm.OPERADOR.LOGADO == true && (vm.MODAL.REQUISICAO.STATUS == 3 || vm.MODAL.STATUS_REQUISICAO == 0)" class="btn  btn-temp btn-danger" id="finalizar">
						<span class="glyphicon glyphicon-random"></span> Trocar
					</button>
				</span>
			</div>
			<div class="label label-success" id="remessa-talao-destaque">
				<span>Remessa - Talão:</span>
				<span class="valor">
				<a title="Clique aqui para consultar" href="{{url('_22120')}}?remessa=@{{vm.MODAL.REMESSA}}" target="_blank" class="ng-binding">@{{vm.MODAL.REMESSA}}</a>
				 / @{{vm.MODAL.REMESSA_TALAO_ID}}</span>
			</div>
			<div class="label label-default" id="data-destaque">
				<span>Data Produção:</span>
				<span class="valor">@{{vm.MODAL.DATA_PRODUCAO}}</span>
			</div>
			
		</div>

		<div class="erros-talao">
			
			<div class="alert alert2 alert-danger " ng-if="vm.MODAL.JUSTIFICATIVA_ORIGEM.length > 0">
				<b>@{{vm.MODAL.JUSTIFICATIVA_ORIGEM}}</b></p>
	        </div>

			<div class="alert alert2 alert-danger " ng-if="vm.MODAL.STATUS != 3 && vm.MODAL.TALAO_ENCERRADO == 0 && vm.MODAL.TALAO_ENCERRADO == 0">
				<b>O material deste talão ainda não foi produzido</b></p>
	        </div>

	        <div class="alert alert2 alert-danger " ng-if="vm.MODAL.STATUS_EXTRA > 0 && vm.MODAL.STATUS_EXTRA < 3 && vm.MODAL.STATUS_REQUISICAO == 0 && vm.MODAL.TALAO_ENCERRADO == 0">
	        
				<b>O material do talão EXTRA ainda não foi produzido</b></p>
	        </div>

	        <div class="alert alert2 alert-danger " ng-if="vm.MODAL.ORDEM > 0 && vm.MODAL.TALAO_ENCERRADO == 0">
				<b>Só é possível iniciar o primeiro talão de cada estação</b></p>
	        </div>

	        <div class="alert alert2 alert-danger " ng-if="vm.MODAL.MAQUINA.STATUS_PARADA > 0">
	        	<p ng-if="vm.MODAL.MAQUINA.STATUS_PARADA > 0"><b>Estação esta parada por @{{vm.MODAL.MAQUINA.DESCRI_PARADA}}</b></p>
	        </div>

	        <div class="alert alert2 alert-danger " ng-if="vm.OPERADOR.LOGADO == false">
	        	<p ng-if="vm.OPERADOR.LOGADO == false"><b>O operador não esta logado</b></p>
	        </div>

	        <div class="alert alert2 alert-danger " ng-if="vm.MODAL.STATUS_PARADA >  0">
	        	<p><b>Talão parado por @{{vm.MODAL.DESCRICAO_PARADA}}</b></p>
	        </div>

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
									<button ng-click="vm.Acoes.ferramentasLivres()" type="button" ng-if="vm.MODAL.PROGRAMACAO_STATUS != 2 && vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' && vm.OPERADOR.LOGADO == true" class="btn  btn-temp btn-danger">
										<span class="glyphicon glyphicon-random"></span> Trocar
									</button>
								</td>
								<td>
									@{{vm.MODAL.MODELO_ID}} - @{{vm.MODAL.MODELO_DESCRICAO}}	
								</td>
								<td>
									@{{vm.MODAL.LINHA_DESCRICAO}}		
								</td>
								<td style="font-family: monospace">
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
								<th>APROVEITAMENTO</th>
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
									@{{ vm.MODAL.APROVEITAMENTO_SOBRA | number : 0}}
								</td>
								<td>
									@{{vm.MODAL.COR_CODIGO}} - @{{vm.MODAL.COR_DESCRICAO}}		
								</td>
								<td>
									@{{vm.MODAL.ID}}		
								</td>
								<td>
									<strong class="f-preto"> @{{vm.MODAL.TROCA_AMOSTRA}}</strong>
									<strong class="@{{vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ? 'f-verde' : ''}} @{{vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'S' ? 'f-azul' : ''}} @{{vm.MODAL.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ? 'f-preto' : ''}}"> @{{vm.MODAL.TROCA_MATRIZ}}</strong>
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
								<td t-title="ID: @{{vm.MODAL.TALAO_EXTRA}}">@{{vm.MODAL.REMESSA_TALAO_ID_EXTRA}}</td>
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

				<div class="info-talao-modal"  style="width: 33%;">
					<div class="descricao">Programação 

					</div>
					<div>
						<table class="tabela-items-info-talao">
							<tr>
								<td>Inicio/Fim:</td>
								<td>@{{ vm.MODAL.DATAHORA_INICIO  | toDate | date:'dd/MM HH:mm'}} a @{{vm.MODAL.DATAHORA_FIM  | toDate | date:'dd/MM HH:mm'}}</td>
							</tr>
							<tr>
								<td>Tempo Maquina:</td>
								<td>@{{vm.MODAL.TEMPO_MAQUINA | number : 0}} Seg.</td>
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
								<td>@{{vm.MODAL.TEMPO_SETUP_AQUECIMENTO | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Limp. Matriz:</td>
								<td>@{{vm.MODAL.TEMPO_SETUP_COR | number : 0}} Min.</td>
							</tr>
							<tr>
								<td>Setup Aprov. Cor:</td>
								<td>@{{vm.MODAL.TEMPO_SETUP_APROVACAO | number : 0}} Min.</td>
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
	        <div class="alert alert2 alert-warning" ng-repeat="requisicao in vm.MODAL.REQUISICAO.ITENS track by $index" ng-if="requisicao.REMESSA > 0">
	        	<p><b>Requisicão Remessa:@{{requisicao.REMESSA}} Talão:@{{requisicao.TALAO}} @{{requisicao.DESC_STATUS}}</b></p>
	        </div>
		</div>

	@overwrite