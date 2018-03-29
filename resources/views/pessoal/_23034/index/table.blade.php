<div class="table-ec table-resumo">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
				<th class="text-right peso">{{ Lang::get($menu.'.th-peso') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="resumo in $ctrl.Index.listaResumo | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibir(resumo)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibir(resumo) : null">

				<td 
					class="text-right id"
					ng-bind="resumo.ID | lpad:[5,'0']"></td>

				<td 
					class="descricao"
					ng-bind="resumo.DESCRICAO"></td>

				<td 
					class="text-right peso"
					ng-bind="resumo.PESO"></td>
				
			</tr>
		</tbody>

	</table>

</div>