<div class="table-ec table-pesquisa">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="status"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="formulario">{{ Lang::get($menu.'.th-formulario') }}</th>
				<th class="autor">{{ Lang::get($menu.'.th-autor') }}</th>
				<th class="cliente">{{ Lang::get($menu.'.th-cliente') }}</th>
				<th class="data-criacao">{{ Lang::get($menu.'.th-data-criacao') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="pesquisa in $ctrl.Index.listaPesquisa | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibirPesquisa(pesquisa)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirPesquisa(pesquisa) : null">

				<td 
					class="status status-@{{ pesquisa.STATUS }}"
					title="@{{ pesquisa.STATUS == '1' ? 'Primeira avaliação' : 'Segunda avaliação' }}">

					<span class="fa fa-circle"></span>
				</td>

				<td 
					class="text-right id"
					ng-bind="pesquisa.ID | lpad:[5,'0']"></td>

				<td 
					class="formulario"
					ng-bind="(pesquisa.FORMULARIO_ID | lpad:[5,'0']) +' - '+ pesquisa.TITULO"></td>

				<td 
					class="autor"
					ng-bind="(pesquisa.USUARIO_ID | lpad:[5,'0']) +' - '+ pesquisa.USUARIO_DESCRICAO"></td>

				<td 
					class="cliente"
					ng-bind="(pesquisa.CLIENTE_ID | lpad:[5,'0']) +' - '+ pesquisa.RAZAOSOCIAL"></td>

				<td 
					class="data-criacao"
					ng-bind="pesquisa.DATAHORA_INSERT_HUMANIZE"></td>
				
			</tr>
		</tbody>

	</table>

</div>