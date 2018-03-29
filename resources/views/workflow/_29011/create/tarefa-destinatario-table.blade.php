<div class="table-container table-container-destinatario">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="chk"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
				<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
				<th class="setor">{{ Lang::get($menu.'.th-setor') }}</th>
				<th class="possui-email">{{ Lang::get($menu.'.th-possui-email') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-body">						
			<tbody>
				<tr 
					ng-repeat="destinatario in tarefa.DESTINATARIO" 
					ng-click="$ctrl.selecionarDestinatarioEscolhido(tarefa, destinatario)"
					ng-if="destinatario.STATUSEXCLUSAO != '1'">
					
					<td class="chk">

						<input 
							type="checkbox" 
							ng-checked="tarefa.DESTINATARIO_SELEC.indexOf(destinatario) > -1"
							ng-disabled="$ctrl.tipoTela == 'exibir'">

					</td>

					<td class="text-right id">@{{ destinatario.USUARIO_ID | lpad:[5,'0'] }}</td>
					<td class="usuario">@{{ destinatario.USUARIO }}</td>
					<td class="nome">@{{ destinatario.NOME }}</td>
					<td class="setor">@{{ destinatario.SETOR }}</td>

					<td 
						class="possui-email"
						title="@{{ destinatario.EMAIL }}">

						<span 
							class="glyphicon glyphicon-ok sim"
							ng-if="destinatario.EMAIL.length > 0"></span>
						<span 
							class="glyphicon glyphicon-remove nao"
							ng-if="destinatario.EMAIL.length == 0"></span>

						<button
							type="button"
							class="btn btn-xs btn-default"
							title="{{ Lang::get($menu.'.title-alterar-email') }}"
							ng-click="$ctrl.alterarEmailUsuario(destinatario)">
								
							<span class="glyphicon glyphicon-edit"></span>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>