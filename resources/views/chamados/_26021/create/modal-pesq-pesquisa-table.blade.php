<div class="table-ec table-modelo-pesquisa">

	<table class="table table-striped table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="data-criacao">{{ Lang::get($menu.'.th-data-criacao') }}</th>
			</tr>
		</thead>
							
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0" 
				ng-repeat="modelo in $ctrl.Create.listaModeloPesquisa | orderBy: 'ID' : true | filter: $ctrl.filtrarModeloPesquisa"
				ng-click="$ctrl.Create.selecionarModeloPesquisa(modelo)"
				ng-keypress="$event.keyCode == 13 ? $ctrl.Create.selecionarModeloPesquisa(modelo) : null">

				<td 
					class="text-right id"
					ng-bind="(modelo.ID | lpad:[5,'0'])"></td>

				<td 
					class="titulo"
					ng-bind="modelo.TITULO"></td>

				<td 
					class="data-criacao"
					ng-bind="modelo.DATAHORA_INSERT_HUMANIZE"></td>
			</tr>
		</tbody>

	</table>

</div>