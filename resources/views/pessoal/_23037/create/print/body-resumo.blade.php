<fieldset>

	<legend>{{ Lang::get($menu.'.legend-resumo') }}</legend>

	<table>
		<thead>
			<tr>
				<th>{{ Lang::get($menu.'.label-descricao') }}</th>
				<th class="text-right">{{ Lang::get($menu.'.label-pontuacao-geral') }}</th>
				<th class="text-right">{{ Lang::get($menu.'.label-peso') }}</th>
				<th class="text-right">{{ Lang::get($menu.'.label-resultado') }}</th>
			</tr>
		</thead>

		<tbody>
			<tr ng-repeat="resumo in $ctrl.Create.avaliacao.RESUMO">
				
				<td ng-bind="resumo.DESCRICAO"></td>
				<td class="text-right" ng-bind="resumo.PONTUACAO_GERAL | number:2"></td>
				<td class="text-right" ng-bind="resumo.PESO | number:2"></td>
				<td class="text-right" ng-bind="resumo.RESULTADO | number:2"></td>

			</tr>
		</tbody>

		<tfoot>
			<tr>
				<td class="text-center" colspan="2">Pontuação Final:</td>
				<td class="text-right" ng-bind="$ctrl.Create.avaliacao.PESO_FINAL_RESUMO | number:2"></td>
				<td class="text-right" ng-bind="$ctrl.Create.avaliacao.RESULTADO_FINAL_RESUMO | number:2"></td>
			</tr>
		</tfoot>
	</table>

</fieldset>