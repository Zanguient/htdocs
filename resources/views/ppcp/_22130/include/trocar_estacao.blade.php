@extends('helper.include.view.modal', ['id' => 'modal-troca-estacao'])

@section('modal-header-left')

<h4 class="modal-title">
	Trocar Estação:
</h4>

@overwrite

@section('modal-header-right')
	

	<button
	ng-click="vm.Acoes.trocarEstacao(vm.MODAL.ESTACAO)"
	type="button"
	class="btn btn-success btn-confirmar"
	id="btn-confirmar-up"
	data-hotkey="enter"
	ng-disabled="vm.MODAL.ABILITAR_TROCA == 0"
	>

		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>

	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')						

<div class="form-group">
	<label>Estacoes:</label>

	<div class="sortable conteiner-estacao conteiner-estacao-modal">
		<table class="tg" style="undefined;table-layout: fixed; width: 399px">
		<colgroup>
			<col style="width: 5vw">
			<col style="width: 5.7vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 3px">
		</colgroup>
			<tr ng-repeat="estacao in vm.DADOS_TEMP.ESTACOES track by $index">

				<th class="tg-031g" colspan="2">
					<div style="width: 16vw;">@{{estacao.DESCRICAO}}
					</div>
				</th>

				<th class="tg-031f"></th>

				<th class="tg-031j" id="basic" colspan="6">
					<div class="linha-talao">
						<div class="scoll-talao itens_nao_troca" estacao="@{{estacao.ESTACAO}}" style="width: 0px !important;border: 0px solid;" horafim="2000-01-01 00:00:00"></div>

						<div
							class="scoll-talao"

							ng-class="{
							'sortable_itens'	: talao.PROGRAMACAO_STATUS != 2,
							'itens_troca'		: vm.MODAL.PROGRAMACAO_ID == talao.PROGRAMACAO_ID,
							'itens_nao_troca'	: vm.MODAL.PROGRAMACAO_ID != talao.PROGRAMACAO_ID,
							'f-preto'			: talao.STATUS == 1 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'			: talao.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2 ,
							'f-verde'			: talao.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'			: talao.PROGRAMACAO_STATUS == 2
							}"
							
							ng-if="estacao.ESTACAO == talao.ESTACAO";

							ng-repeat="talao in estacao.TALOES | orderBy :'SEQUENCIAL' track by $index"

							estacao="@{{estacao.ESTACAO}}"
							horafim="@{{talao.DATAHORA_FIM}}"
							data-ID1="@{{vm.MODAL.PROGRAMACAO_ID}}"
							data-ID2="@{{talao.PROGRAMACAO_ID}}"
							data-SEQ="@{{talao.SEQUENCIAL}}"
							>

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
								    <table>
										<tr>
											<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
											<td class="@{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ? 'f-verde' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'S' ? 'f-azul' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ? 'f-preto' : ''}}">@{{talao.TROCA_MATRIZ}}</td>
										</tr>
										<tr>
											<td class="f-preto">@{{talao.TROCA_VIP}}</td>
											<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
										</tr>
										<tr>
											<td class="f-preto"></td>
											<td class="f-preto">@{{talao.TROCA_REQUISICAO}}</td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<div
							class="scoll-talao sortable_itens item-fantasma"
								estacao="@{{estacao.ESTACAO}}"
							>
						</div>

					</div>
				</th>

				<th class="tg-031f"></th>

			</tr>

			<tr>
				<th class="tg-031g" colspan="2" rowspan="2"><div>Requisições</div></th>
				<th class="tg-031f"></th>

				<th class="tg-031j" id="basic" colspan="6">
					<div class="linha-talao">

						<div 
							class="scoll-talao"

							ng-class="{
							'sortable_itens'	: vm.MODAL.PROGRAMACAO_ID == talao.PROGRAMACAO_ID,
							'itens_troca'		: vm.MODAL.PROGRAMACAO_ID == talao.PROGRAMACAO_ID,
							'itens_nao_troca'	: vm.MODAL.PROGRAMACAO_ID != talao.PROGRAMACAO_ID,	
							'f-preto'			: talao.REQUISICAO.STATUS == 1 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'			: talao.REQUISICAO.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2 ,
							'f-verde'			: talao.REQUISICAO.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'			: talao.PROGRAMACAO_STATUS == 2
							}"

							ng-repeat="talao in vm.DADOS.PARADOS1 | orderBy:'-REQUISICAO_STATUS' track by $index"
							
							estacao="@{{estacao.ESTACAO}}"
							horafim="@{{talao.DATAHORA_FIM}}"
							data-ID1="@{{vm.MODAL.PROGRAMACAO_ID}}"
							data-ID2="@{{talao.PROGRAMACAO_ID}}"
							data-SEQ="@{{talao.SEQUENCIAL}}"
							">

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
								    <table>
										<tr>
											<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
											<td class="@{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ? 'f-verde' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'S' ? 'f-azul' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ? 'f-preto' : ''}}">@{{talao.TROCA_MATRIZ}}</td>
										</tr>
										<tr>
											<td class="f-preto">@{{talao.TROCA_VIP}}</td>
											<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
										</tr>
										<tr>
											<td class="f-preto"></td>
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

			<tr>
				<th class="tg-031f"></th>

				<th class="tg-031j" id="basic" colspan="6">
					<div class="linha-talao">

						<div 
							class="scoll-talao"

							ng-class="{
							'sortable_itens'	: vm.MODAL.PROGRAMACAO_ID == talao.PROGRAMACAO_ID,
							'itens_troca'		: vm.MODAL.PROGRAMACAO_ID == talao.PROGRAMACAO_ID,
							'itens_nao_troca'	: vm.MODAL.PROGRAMACAO_ID != talao.PROGRAMACAO_ID,	
							'f-preto'			: talao.REQUISICAO.STATUS == 1 && talao.PROGRAMACAO_STATUS != 2,
							'f-amarelo'			: talao.REQUISICAO.STATUS == 2 && talao.PROGRAMACAO_STATUS != 2 ,
							'f-verde'			: talao.REQUISICAO.STATUS == 3 && talao.PROGRAMACAO_STATUS != 2,
							'f-azul'			: talao.PROGRAMACAO_STATUS == 2
							}"

							ng-repeat="talao in vm.DADOS.PARADOS2 | orderBy:'-REQUISICAO_STATUS' track by $index"

							estacao="@{{estacao.ESTACAO}}"
							horafim="@{{talao.DATAHORA_FIM}}"
							data-ID1="@{{vm.MODAL.PROGRAMACAO_ID}}"
							data-ID2="@{{talao.PROGRAMACAO_ID}}"
							data-SEQ="@{{talao.SEQUENCIAL}}"
							">

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
								    <table>
										<tr>
											<td class="f-preto">@{{talao.TROCA_AMOSTRA}}</td>
											<td class="@{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ? 'f-verde' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'S' ? 'f-azul' : ''}} @{{talao.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ? 'f-preto' : ''}}">@{{talao.TROCA_MATRIZ}}</td>
										</tr>
										<tr>
											<td class="f-preto">@{{talao.TROCA_VIP}}</td>
											<td class="f-preto">@{{talao.TROCA_PARADA}}</td>
										</tr>
										<tr>
											<td class="f-preto"></td>
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

</div>

@overwrite