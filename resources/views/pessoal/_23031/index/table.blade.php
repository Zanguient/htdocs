<div class="table-ec table-tipo">

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
				ng-repeat="tipo in $ctrl.Index.listaTipo | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibirTipo(tipo)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirTipo(tipo) : null">

				<td 
					class="text-right id"
					ng-bind="tipo.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="tipo.TITULO"></td>
				
			</tr>
		</tbody>

	</table>

</div>