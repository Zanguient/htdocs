<div class="table-container table-container-workflow-item">

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
					ng-repeat="item in $ctrl.workflowItem | filter: $ctrl.filtrarWorkflowItem | orderBy:'ID':true track by $index"
					ng-click="$ctrl.exibirItem(item)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.exibirItem(item) : null">
					
					<td 
						class="status">

						<span 
							class="fa fa-circle status-parado"
							ng-if="item.STATUS_CONCLUSAO == '0'"></span>
						<span 
							class="fa fa-circle status-iniciado"
							ng-if="item.STATUS_CONCLUSAO == '1'"></span>
						<span 
							class="fa fa-circle status-concluido"
							ng-if="item.STATUS_CONCLUSAO == '2'"></span>
						<span 
							class="fa fa-circle status-encerrado"
							ng-if="item.STATUS_CONCLUSAO == '3'"></span>
					</td>

					<td 
						class="id text-right" 
						ng-bind="item.ID | lpad:[5,'0']"></td>

					<td 
						class="titulo" 
						ng-bind="item.TITULO"></td>
					
					<td 
						class="descricao" 
						ng-bind="item.DESCRICAO"></td>
				</tr>

			</tbody>
		</table>
	</div>

</div>