<div class="table-ec table-resposta">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="colaborador">{{ Lang::get($menu.'.th-colaborador') }}</th>
				<th class="cargo">{{ Lang::get($menu.'.th-cargo') }}</th>
				<th class="ccusto">{{ Lang::get($menu.'.th-ccusto') }}</th>
				<th class="data">{{ Lang::get($menu.'.th-data-avaliacao') }}</th>
				<th class="data-resposta">{{ Lang::get($menu.'.th-data-resposta') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="resp in $ctrl.Index.listaResposta | orderBy : 'ID' : true | filter : $ctrl.filtroTabelaResposta track by $index"
				ng-click="$ctrl.Index.exibirResposta(resp)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirResposta(resp) : null">

				<td 
					class="text-right id"
					ng-bind="resp.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="resp.TITULO"></td>

				<td 
					class="colaborador"
					ng-bind="resp.COLABORADOR_NOME"></td>

				<td 
					class="cargo"
					ng-bind="resp.COLABORADOR_CARGO"></td>

				<td 
					class="ccusto"
					ng-bind="resp.COLABORADOR_CCUSTO_MASK +' - '+ resp.COLABORADOR_CCUSTO_DESCRICAO"></td>

				<td 
					class="data"
					ng-bind="resp.DATA_AVALIACAO_HUMANIZE"></td>

				<td 
					class="data-resposta"
					ng-bind="resp.DATAHORA_INSERT_HUMANIZE"></td>
			</tr>
		</tbody>

	</table>

</div>