
<div style="margin-top: 3px; margin-bottom: 3px;">
	<button type="button" style="" class="btn btn-primary" ng-click="$ctrl.export(1)">
		<span class="glyphicon glyphicon-save"></span> 
		Exportar para XLS
	</button>
</div>

<div style="max-height: calc(100vh - 315px);height: calc(100vh - 315px);min-height: calc(300px);" class="table-ec">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="pedido text-right">{{ Lang::get($menu.'.th-pedido') }}</th>
				<th class="pedido text-right">N. Fiscal</th>
				<th 
					class="pedido-cliente text-right"
					title="{{ Lang::get($menu.'.th-pedido-cliente-title') }}" 
				>
					{{ Lang::get($menu.'.th-pedido-cliente') }}
				</th>
				<th style="min-width: 300px;" 
					class="modelo"
					title="Razão Social" 
				>
					Cliente
				</th>
				<th 
					class="data-inclusao"
					title="Tipo do pedido" 
				>
					Tipo
				</th> 
				<th 
					class="data-inclusao text-center"
					title="{{ Lang::get($menu.'.th-data-inclusao-title') }}" 
				>
					{{ Lang::get($menu.'.th-data-inclusao')	}}
				</th> 
				<th 
					class="data-cliente text-center"
					title="{{ Lang::get($menu.'.th-data-cliente-title') }}" 
				>
					{{ Lang::get($menu.'.th-data-cliente') }}
				</th> 
				<th 
					class="programado text-center"
					title="{{ Lang::get($menu.'.th-programado-title') }}" 
				>
					{{ Lang::get($menu.'.th-programado') }}
				</th>
				<th 
					class="confirmado text-center" 
					title="{{ Lang::get($menu.'.th-confirmado-title') }}"
				>
					{{ Lang::get($menu.'.th-confirmado') }}
				</th>
				<th 
					class="qtd text-right"
					title="{{ Lang::get($menu.'.th-quantidade-title') }}"
				>
					{{ Lang::get($menu.'.th-quantidade-abrev') }}
				</th>

				<th class="data-cliente  text-right" itle="Qunatidade Encerrada"   >Faturado</th>

				<th 
					class="data-cliente text-right"
					title="{{ Lang::get($menu.'.th-saldo-faturar-title') }}" 
				>
					{{ Lang::get($menu.'.th-saldo-faturar')	}}
				</th>

				<th class="data-cliente  text-right" itle="Qunatidade a produzir"  >A Produzir</th>
				<th class="data-cliente  text-right" itle="Qunatidade em produção" >Em Produção</th>
				<th class="data-cliente text-right" itle="Qunatidade alocada"      >Alocado</th>
				<th class="data-cliente  text-right" itle="Qunatidade Encerrada"   >Encerrado</th>

				<th class="valor text-right" title="valor do ICMS por substituição tributária"	   >Valor ST</th>
				<th class="valor text-right" title="valor do pedido sem o ICMS por substituição tributária" >{{ Lang::get($menu.'.th-valor-total')		}}</th>
				<th style="min-width: 300px;"  class="modelo"						>{{ Lang::get($menu.'.th-modelo')			}}</th>
				<th style="min-width: 300px;"  class="obs"							>{{ Lang::get($menu.'.th-obs') 				}}</th>
			</tr>
		</thead>

		<tbody>

			<tr ATRASADO
				ng-repeat="pedido in $ctrl.pedido | filter:$ctrl.filtrarPedido | orderBy:'PEDIDO':true track by $index"
				ng-click="$ctrl.exibirPedido(pedido)"
				ng-class="{'pedido-atrasado': pedido.ATRASADO == 1 && pedido.SALDO_FATURAR > 0}"
			>
				<td class="pedido text-right"			>@{{ pedido.PEDIDO 			}}</td>
				<td class="pedido text-right">
					<span
						ng-if="pedido.NFS[0] > 0"
						class="glyphicon glyphicon-info-sign ng-scope"
						data-toggle="popover"
						data-placement="right"
						title=""
						data-element-content="#info-@{{pedido.PEDIDO}}"
						on-finish-render="bs-init"
						data-original-title="Notas Fiscais"
						style="margin-right: 22px;"
					></span>

					<div id="info-@{{pedido.PEDIDO}}" style="display: none">
                        <div class="origem-container">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-left"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="iten in pedido.NFS.split(',')">
                                        <td class="text-left ng-binding"><a href="/_12100?nota=@{{iten}}&cliente=@{{pedido.CLIENTE_CODIGO}}&representante=@{{pedido.REPRESENTANTE_CODIGO}}" target="_blank" >@{{iten}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

				</td>
				<td class="pedido-cliente text-right" autotitle style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">@{{ pedido.PEDIDO_CLIENTE 	               }}</td>
				<td class="modelo"          autotitle >@{{ pedido.CLIENTE_RAZAOSOCIAL              }}</td>
				<td class="data-inclusao"          	  >@{{ pedido.TIPO                             }}</td>
				<td class="data-inclusao text-center" >@{{ pedido.DATA | date:'dd/MM/yyyy'         }}</td>
				<td class="data-cliente text-center"  >@{{ pedido.DATA_CLIENTE | date:'dd/MM/yyyy' }}</td>
				<td class="programado text-center">
					<span class="fa fa-times" ng-if="pedido.PROGRAMADO == 0"></span>
					<span class="fa fa-check" ng-if="pedido.PROGRAMADO == 1"></span>
				</td>
				
				<td class="confirmado text-center">

					<span 
						class="fa fa-times" 
						title="{{ Lang::get($menu.'.title-pedido-nao-confirmado') }}"
						ng-if="pedido.SITUACAO == 0 && pedido.FORMA_ANALISE == 1"></span>

					<span 
						class="fa fa-check" 
						title="{{ Lang::get($menu.'.title-pedido-confirmado') }}"
						ng-if="pedido.SITUACAO == 1"></span>

					<span 
						class="fa fa-exclamation-triangle" 
						title="{{ Lang::get($menu.'.title-pedido-em-analise') }}"
						ng-if="pedido.SITUACAO == 0 && pedido.FORMA_ANALISE == 0"></span>
				</td>

				<td class="qtd text-right"			>@{{ pedido.QUANTIDADE_TOTAL | number }}</td>
				
				<td class="data-cliente text-right"	>@{{ pedido.FATURADO      | number }}</td>
				<td class="data-cliente text-right"	>@{{ pedido.SALDO_FATURAR | number }}</td>
				<td class="data-cliente text-right"	>@{{ pedido.PRODUZIR      | number }}</td>
				<td class="data-cliente text-right"	>@{{ pedido.EMPRODUCAO    | number }}</td>
				<td class="data-cliente text-right"	>@{{ pedido.ALOCADO       | number }}</td>
				<td class="data-cliente text-right"	>@{{ pedido.ENCERRADO     | number }}</td>

				<td class="valor text-right"		>@{{ pedido.VALOR_ST    | currency:'R$ ':2 }}</td>
				<td class="valor text-right"		>@{{ pedido.VALOR_TOTAL | currency:'R$ ':2 }}</td>
				<td class="modelo"  autotitle       >@{{ pedido.MODELO }}</td>
				<td class="obs" autotitle           >@{{ pedido.OBSERVACAO }}</td>
			</tr>

		</tbody>
	</table>
</div>

<div style="display: inline-flex;">
	<div class="pedido-atrasado" style="border-radius: 5px;width: 20px; height: 20px;" ></div><div> Pedido Atrasado</div>
</div>