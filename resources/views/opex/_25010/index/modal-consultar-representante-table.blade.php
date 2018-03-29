<div class="table-ec table-representante">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="representante">{{ Lang::get($menu.'.th-representante') }}</th>
				<th class="uf">{{ Lang::get($menu.'.th-uf') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-representante">
			<tr 
				tabindex="0" 
				ng-repeat="representante in vm.listaRepresentante | filter : vm.filtrarRepresentante | orderBy : 'RAZAOSOCIAL'"
				ng-click="vm.selecionarRepresentante(representante)"
				ng-keypress="$event.keyCode == 13 ? vm.selecionarRepresentante(representante) : null">

				<td class="representante"
					ng-bind="(representante.CODIGO | lpad:[5,'0']) +' - '+ representante.RAZAOSOCIAL"></td>

				<td class="uf"
					ng-bind="representante.UF"></td>
			</tr>
		</tbody>
	</table>
</div>