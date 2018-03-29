<div class="table-container table-container-consulta-workflow">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr 
					tabindex="0" 
					ng-repeat="workflow in $ctrl.listaWorkflow | filter : $ctrl.filtrarWorkflow | orderBy : 'TITULO'"
					ng-click="$ctrl.selecionar(workflow)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionar(workflow) : null">

					<td class="id">@{{ workflow.ID | lpad:[5,'0'] }}</td>
					<td class="titulo">@{{ workflow.TITULO }}</td>
					<td class="descricao">@{{ workflow.DESCRICAO }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>