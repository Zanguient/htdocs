<div class="table-ec table-nivel">

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
				ng-repeat="nivel in $ctrl.Index.listaNivel | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibirNivel(nivel)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirNivel(nivel) : null">

				<td 
					class="text-right id"
					ng-bind="nivel.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="nivel.TITULO"></td>
				
			</tr>
		</tbody>

	</table>

</div>