<div class="table-container table-container-por-representante">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="cliente">{{ Lang::get($menu.'.th-cliente') }}</th>
				<th class="nome-fantasia">{{ Lang::get($menu.'.th-nome-fantasia') }}</th>
				<th class="uf">{{ Lang::get($menu.'.th-uf') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr 
					tabindex="0" 
					ng-repeat="cliente in $ctrl.listaClientePorRepresentante | filter : $ctrl.filtrarCliente | orderBy : 'RAZAOSOCIAL'"
					ng-click="$ctrl.selecionarClientePorRepresentante(cliente)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionarClientePorRepresentante(cliente) : null"
					ng-class="{selected: cliente.CODIGO == $ctrl.clienteSelec.CODIGO}"
				>
					<td class="cliente">@{{ cliente.CODIGO | lpad:[5,'0'] }} - @{{ cliente.RAZAOSOCIAL }}</td>
					<td class="nome-fantasia">@{{ cliente.NOMEFANTASIA }}</td>
					<td class="uf">@{{ cliente.UF }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>