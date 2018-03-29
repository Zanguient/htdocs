<div class="table-ec table-indicador-por-ccusto">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="id text-right">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="indicador">{{ Lang::get($menu.'.th-indicador') }}</th>
				<th class="ccusto">{{ Lang::get($menu.'.th-ccusto') }}</th>
				<th class="data">{{ Lang::get($menu.'.th-periodo') }}</th>
				<th class="percentual text-right">{{ Lang::get($menu.'.th-percentual') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="indicadorPorCCusto in $ctrl.Index.listaIndicadorPorCCusto | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibir(indicadorPorCCusto)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibir(indicadorPorCCusto) : null">

				<td 
					class="id text-right"
					ng-bind="indicadorPorCCusto.ID | lpad:[5,'0']"></td>

				<td 
					class="indicador"
					ng-bind="(indicadorPorCCusto.INDICADOR_ID | lpad:[5,'0']) +' - '+ indicadorPorCCusto.INDICADOR_TITULO"></td>

				<td 
					class="ccusto"
					ng-bind="indicadorPorCCusto.CCUSTO_MASK +' - '+ indicadorPorCCusto.CCUSTO_DESCRICAO"></td>

				<td 
					class="data"
					ng-bind="indicadorPorCCusto.DATA_INI_HUMANIZE +' à '+ indicadorPorCCusto.DATA_FIM_HUMANIZE"></td>

				<td 
					class="percentual text-right"
					ng-bind="indicadorPorCCusto.PERC_INDICADOR | number:2"></td>
				
			</tr>
		</tbody>

	</table>

</div>