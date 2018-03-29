<div class="table-container table-container-notificado">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
				<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
				<th class="setor">{{ Lang::get($menu.'.th-setor') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-body">						
			<tbody>
				<tr 
					ng-repeat="notificado in tarefa.NOTIFICADO">
					
					<td class="text-right id">@{{ notificado.USUARIO_ID | lpad:[5,'0'] }}</td>
					<td class="usuario">@{{ notificado.USUARIO }}</td>
					<td class="nome">@{{ notificado.NOME }}</td>
					<td class="setor">@{{ notificado.SETOR }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>