<div class="table-ec table-cliente">

	<table class="table table-striped table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="razao-social">{{ Lang::get($menu.'.th-razao-social') }}</th>
				<th class="nome-fantasia">{{ Lang::get($menu.'.th-nome-fantasia') }}</th>
			</tr>
		</thead>
							
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0" 
				ng-repeat="cliente in $ctrl.Create.listaCliente | orderBy: 'RAZAOSOCIAL' | filter: $ctrl.filtrarCliente"
				ng-click="$ctrl.Create.selecionarCliente(cliente)"
				ng-keypress="$event.keyCode == 13 ? $ctrl.Create.selecionarCliente(cliente) : null">

				<td 
					class="text-right id"
					ng-bind="(cliente.ID | lpad:[5,'0'])"></td>

				<td 
					class="razao-social"
					ng-bind="cliente.RAZAOSOCIAL"></td>

				<td 
					class="nome-fantasia"
					ng-bind="cliente.NOMEFANTASIA"></td>
			</tr>
		</tbody>

	</table>

</div>