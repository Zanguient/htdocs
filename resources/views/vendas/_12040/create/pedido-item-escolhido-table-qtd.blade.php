<div class="container-qtd-minima">

	<legend>Resumo por cor</legend>

	<div style="max-height: 300px;min-height: 300px;" class="table-ec">

		<table class="table table-bordered table-header">
			<thead>
				<tr>
					<th class="status"></th>
					
					@if ($pu218 != 1)
					<th class="cor">{{ Lang::get($menu.'.th-cor') }}</th>
					@else
					<th class="cor col-menor">{{ Lang::get($menu.'.th-cor') }}</th>
					@endif

					<th class="quantidade text-right"		>{{ Lang::get($menu.'.th-quantidade-abrev') }}</th>
					<th class="quantidade-min text-right"	>{{ Lang::get($menu.'.th-quantidade-min') }}</th>
					<th class="quantidade-mult text-right"	>{{ Lang::get($menu.'.th-quantidade-mult') }}</th>
					<th class="atualizar-qtd-liberada"		></th>
				</tr>
			</thead>

			<tbody>
				<tr 
					ng-repeat="cor in $ctrl.corEscolhida"
				>
					<td class="status">
						<span class="fa fa-check" ng-if="(cor.quantidade >= cor.quantidadeMinima) && (cor.quantidade % cor.quantidadeMultipla == 0)"></span>
						<span class="fa fa-times" ng-if="(cor.quantidade < cor.quantidadeMinima) || (cor.quantidade % cor.quantidadeMultipla != 0)"></span>
					</td>

					@if ($pu218 != 1)
					<td class="cor">@{{ cor.codigo | lpad:[5,'0'] }} - @{{ cor.descricao }}</td>
					@else
					<td class="cor col-menor">@{{ cor.codigo | lpad:[5,'0'] }}</td>
					@endif

					<td class="quantidade text-right"		>@{{ cor.quantidade | number }}</td>
					<td class="quantidade-min text-right"	>@{{ cor.quantidadeMinima | number }}</td>
					<td class="quantidade-mult text-right"	>@{{ cor.quantidadeMultipla | number }}</td>
					<td class="atualizar-qtd-liberada">
						<button 
							type="button" 
							class="btn btn-xs btn-warning"
							ng-click="$ctrl.atualizarQtdLiberada(cor)">
							<span class="fa fa-refresh"></span>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>