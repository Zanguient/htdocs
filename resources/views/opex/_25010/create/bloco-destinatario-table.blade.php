<div 
	class="table-ec table-container-destinatario table-destinatario-usuario"
	ng-if="vm.formulario.DESTINATARIO_TIPO == 'usuario'">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="chk"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
				<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
				<th class="peso">{{ Lang::get($menu.'.th-peso') }}</th>
				<th 
					class="reabilitar"
					ng-if="vm.tipoTela != 'incluir'"
				>
					{{ Lang::get($menu.'.th-reabilitar') }}
				</th>
				<th class="visualiza-cadastro">{{ Lang::get($menu.'.th-visualiza-cadastro') }}</th>
			</tr>
		</thead>

		<tbody>
			<tr ng-repeat="usuario in vm.listaUsuarioSelec" ng-click="vm.selecionarUsuarioEscolhido($event, usuario)">
				
				<td class="chk">

					<input 
						type="checkbox" 
						ng-checked="vm.listaUsuarioSelecEscolhido.indexOf(usuario) > -1" 
					/>

				</td>

				<td class="text-right id">@{{ usuario.ID }}</td>
				<td class="usuario">@{{ usuario.USUARIO }}</td>
				<td class="nome">@{{ usuario.NOME }}</td>

				<td class="peso">

					<input type="number" class="form-control input-small peso" min="1" max="5" value="1" 
						ng-model="usuario.PESO"
						ng-click="$event.stopPropagation()"
						string-to-number
					/>

				</td>

				<td 
					class="reabilitar"
					ng-if="vm.tipoTela != 'incluir'"
				>

					<input 
						type="checkbox" 
						class="chk-reabilitar-usuario"
						ng-checked="usuario.STATUS_RESPOSTA == '1'"
						ng-click="vm.reabilitarUsuario(usuario)"
						ng-disabled="usuario.VISUALIZA_CADASTRO == '1'"
					/>

				</td>

				<td class="visualiza-cadastro">

					<input 
						type="checkbox" 
						class="chk-visualiza-cadastro"
						ng-model="usuario.VISUALIZA_CADASTRO"
						ng-true-value="'1'"
						ng-false-value="'0'"
					/>

				</td>

			</tr>
		</tbody>

	</table>

</div>

<div 
	class="table-ec table-container-destinatario table-destinatario-ccusto"
	ng-if="vm.formulario.DESTINATARIO_TIPO == 'ccusto'">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="chk"></th>
				<th class="ccusto-mask">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="ccusto-descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
				<th class="peso">{{ Lang::get($menu.'.th-peso') }}</th>
			</tr>
		</thead>
	
		<tbody>
			<tr ng-repeat="ccusto in vm.listaCCustoSelec" ng-click="vm.selecionarCCustoEscolhido(ccusto)">
				<td class="chk">

					<input type="checkbox" ng-checked="vm.listaCCustoSelecEscolhido.indexOf(ccusto) > -1" />
					
				</td>
				<td class="ccusto-mask">@{{ ccusto.MASK }}</td>
				<td class="ccusto-descricao">@{{ ccusto.DESCRICAO }}</td>
				<td class="peso">

					<input type="number" class="form-control input-small peso" min="1" max="5" value="1" 
						ng-model="ccusto.PESO"
						ng-click="$event.stopPropagation()" 
						string-to-number
					/>

				</td>
			</tr>
		</tbody>

	</table>

</div>