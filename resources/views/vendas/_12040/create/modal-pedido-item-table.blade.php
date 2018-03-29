<div class="table-container table-container-tamanho">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="tamanho text-right">{{ Lang::get($menu.'.th-tamanho') }}</th>
				<th class="tamanho-valor-unitario text-right">{{ Lang::get($menu.'.th-valor-unitario') }}</th>
				<th class="tamanho-quantidade">{{ Lang::get($menu.'.th-quantidade') }}</th>
				<th 
					class="tamanho-quantidade-min text-right"
					title="{{ Lang::get($menu.'.th-quantidade-min-title') }}"
				>
					{{ Lang::get($menu.'.th-quantidade-min') }}
				</th>
				<th 
					class="tamanho-quantidade-mult text-right"
					title="{{ Lang::get($menu.'.th-quantidade-mult-title') }}"
				>
					{{ Lang::get($menu.'.th-quantidade-mult') }}
				</th>
				<th 
					class="tamanho-estoque-min text-center"
					title="{{ Lang::get($menu.'.th-estoque-min-title') }}"
				>
					{{ Lang::get($menu.'.th-estoque-min') }}
				</th>
			</tr>
		</thead>
	</table>

	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody>

				<tr 
					ng-repeat="tam in $ctrl.tamanhoPreco | orderBy:'TAMANHO_DESCRICAO'" 
					ng-if="tam.BLQ_PED === '0'"
				>
					<td class="tamanho text-right">@{{ tam.TAMANHO_DESCRICAO }}</td>
					<td class="tamanho-valor-unitario text-right">@{{ tam.TAMANHO_PRECO | currency:"R$ ":2 }}</td>
					<td class="tamanho-quantidade">
						<input 
							type="number" 
							class="form-control input-menor quantidade" 
							min="0" 
							ng-model="tam.quantidade" 
							ng-change="$ctrl.somaQuantidade();"
							ng-blur="$ctrl.arredondarQuantidadeModelo(tam);"
							ng-disabled="tam.BLQ_PED === '1'"
							title="@{{ (tam.BLQ_PED === '1') ? 'Tamanho bloqueado para pedido.' : '' }}"
						/>
					</td>
					<td class="tamanho-quantidade-min text-right">@{{ (tam.QTD_MIN_LIBERADA > 0) ? tam.QTD_MIN_LIBERADA : tam.QTD_MIN_MODELO }}</td>
					<td class="tamanho-quantidade-mult text-right">@{{ tam.QTD_MULT_MODELO }}</td>
					<td class="tamanho-estoque-min text-center">
						<span class="fa fa-check" ng-if="tam.EST_MIN === '1'"></span>
						<span class="fa fa-times" ng-if="tam.EST_MIN === '0'"></span>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>