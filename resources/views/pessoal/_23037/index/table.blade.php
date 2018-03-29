<div class="table-ec table-avaliacao">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="status"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="data-avaliacao">{{ Lang::get($menu.'.th-data-avaliacao') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-ec">
			<tr 
				tabindex="0"
				ng-repeat="base in $ctrl.Index.listaBase | orderBy : 'ID' : true | filter : $ctrl.filtroTabela track by $index"
				ng-click="$ctrl.Index.exibirAvaliacao(base)" 
				ng-keypress="$event.keyCode == 13 ? $ctrl.Index.exibirAvaliacao(base) : null">

				<td 
					class="status">
					
					<span 
						class="fa fa-circle status-inativo"
						ng-if="base.STATUS == '0'"></span>
					<span 
						class="fa fa-circle status-ativo"
						ng-if="base.STATUS == '1'"></span>
				</td>

				<td 
					class="text-right id"
					ng-bind="base.ID | lpad:[5,'0']"></td>

				<td 
					class="titulo"
					ng-bind="base.TITULO"></td>

				<td 
					class="data-avaliacao"
					ng-bind="base.DATA_AVALIACAO_HUMANIZE"></td>
				
			</tr>
		</tbody>

	</table>

</div>

<div class="legenda-container">

	<ul class="legenda">

		<li>
			<div class="cor-legenda ativo"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-ativo') }}</div>
		</li>
		<li>
			<div class="cor-legenda inativo"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-inativo') }}</div>
		</li>

	</ul>

</div>