<div class="table-container table-container-workflow">

	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="status"></th>
				<th class="id text-right">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
			</tr>
		</thead>
	</table>

	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody>

				<tr 
					tabindex="0" 
					ng-repeat="workflow in $ctrl.workflow | filter:$ctrl.filtrarWorkflow | orderBy:'ID':true track by $index"
					ng-click="$ctrl.exibirWorkflow(workflow)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.exibirWorkflow(workflow) : null">
					
					<td 
						class="status">

						<span 
							class="fa fa-circle status-inativo"
							ng-if="workflow.STATUS == '0'"></span>
						<span 
							class="fa fa-circle status-ativo"
							ng-if="workflow.STATUS == '1'"></span>
					</td>

					<td 
						class="id text-right" 
						ng-bind="workflow.ID | lpad:[5,'0']"></td>

					<td 
						class="titulo" 
						ng-bind="workflow.TITULO"></td>
					
					<td 
						class="descricao" 
						ng-bind="workflow.DESCRICAO"></td>
				</tr>

			</tbody>
		</table>
	</div>

</div>