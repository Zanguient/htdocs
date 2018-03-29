<div class="table-ec table-formacao">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
				<th class="text-right ponto">{{ Lang::get($menu.'.th-ponto') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="formacao in $ctrl.Index.listaFormacao | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibir(formacao)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibir(formacao) : null">

				<td 
					class="text-right id"
					ng-bind="formacao.ID | lpad:[5,'0']"></td>

				<td 
					class="descricao"
					ng-bind="formacao.DESCRICAO"></td>

				<td 
					class="text-right ponto"
					ng-bind="formacao.PONTO"></td>
				
			</tr>
		</tbody>

	</table>

</div>