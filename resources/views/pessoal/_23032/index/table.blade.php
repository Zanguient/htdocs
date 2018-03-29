<div class="table-ec table-fator">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="tipo">{{ Lang::get($menu.'.th-tipo') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="fator in $ctrl.Index.listaFator | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibirFator(fator)"
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirFator(fator) : null">

				<td 
					class="text-right id"
					ng-bind="fator.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="fator.TITULO"></td>
				
				<td 
					class="tipo"
					ng-bind="fator.TIPO_TITULO"></td>

			</tr>
		</tbody>

	</table>

</div>