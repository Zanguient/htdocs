<div class="table-ec table-modelo">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="modelo in $ctrl.Index.listaModelo | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibir(modelo)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibir(modelo) : null">

				<td 
					class="text-right id"
					ng-bind="modelo.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="modelo.TITULO"></td>
				
			</tr>
		</tbody>

	</table>

</div>