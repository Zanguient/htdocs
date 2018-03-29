<div class="table-container table-container-representante">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="representante">{{ Lang::get($menu.'.th-representante') }}</th>
				<th class="uf">{{ Lang::get($menu.'.th-uf') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr 
					tabindex="0" 
					ng-repeat="representante in $ctrl.listaRepresentante | filter : $ctrl.filtrarRepresentante | orderBy : 'RAZAOSOCIAL'"
					ng-click="$ctrl.selecionarRepresentante(representante)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionarRepresentante(representante) : null"
					ng-class="{selected: representante.CODIGO == $ctrl.representanteSelec.CODIGO}"
				>
					<td class="representante">@{{ representante.CODIGO | lpad:[5,'0'] }} - @{{ representante.RAZAOSOCIAL }}</td>
					<td class="uf">@{{ representante.UF }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>