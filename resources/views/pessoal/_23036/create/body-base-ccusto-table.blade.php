<div 
	class="table-ec table-ccusto">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="chk"></th>
				<th class="mask">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
			</tr>
		</thead>
	
		<tbody>
			<tr 
				ng-repeat="ccusto in $ctrl.Create.avaliacao.BASE.CCUSTO" 
				ng-click="$ctrl.CreateCCusto.selecionarCCustoEscolhido(ccusto)"
				ng-if="ccusto.STATUSEXCLUSAO != '1'">

				<td class="chk">

					<input 
						type="checkbox" 
						ng-checked="$ctrl.CreateCCusto.listaCCustoSelecEscolhido.indexOf(ccusto) > -1">					
				</td>

				<td 
					class="mask"
					ng-bind="ccusto.MASK"></td>

				<td 
					class="descricao"
					ng-bind="ccusto.DESCRICAO"></td>
			</tr>
		</tbody>

	</table>

</div>