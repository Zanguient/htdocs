<div class="table-container table-container-movimentacao">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
				<th class="status-conclusao">{{ Lang::get($menu.'.th-status') }}</th>
				<th class="data">{{ Lang::get($menu.'.th-data') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-body">						
			<tbody>
				<tr 
					ng-repeat="mov in tarefa.MOVIMENTACAO | orderBy:'ID':true">
					
					<td 
						class="text-right id"
						ng-bind="mov.ID | lpad:[5,'0']"></td>

					<td 
						class="usuario"
						ng-bind="(mov.USUARIO_ID | lpad:[5,'0']) +' - '+ mov.USUARIO_DESCRICAO"></td>

					<td 
						class="status-conclusao"
						ng-bind="
							(mov.STATUS_CONCLUSAO == 0)
							? 'Reativado'
							: (mov.STATUS_CONCLUSAO == 1)
							? 'Iniciado'
							: (mov.STATUS_CONCLUSAO == 2)
							? 'Pausado'
							: (mov.STATUS_CONCLUSAO == 3)
							? 'Concluído'
							: (mov.STATUS_CONCLUSAO == 4)
							? 'Reprovado'
							: ''
						"></td>

					<td 
						class="data"
						ng-bind="mov.DATAHORA_FORMATADO"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>