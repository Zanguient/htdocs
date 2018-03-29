<div class="table-ec table-container-formulario">

	<table class="table table-bordered table-header">

		<thead>
			<tr>
				<th class="chk"></th>
				<th class="status" ng-if="vm.formulario.TIPO != 3"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
				<th class="data-criacao">{{ Lang::get($menu.'.th-data-criacao') }}</th>
				<th 
					class="periodo-form"
					ng-if="vm.formulario.TIPO != 3">{{ Lang::get($menu.'.th-periodo') }}</th>
			</tr>
		</thead>

		<tbody>
			<tr 
				tabindex="0"
				ng-repeat="formulario in vm.listaFormulario.FORMULARIO | orderBy : 'ID' : true | filter : vm.filtrarFormulario track by $index"
				ng-click="vm.exibirFormulario($event, formulario)" 
				ng-keypress="vm.atalhoFormulario($event, formulario)"
				ng-class="{ selected: formulario.selected }">

				<td 
					class="chk" 
					ng-click="vm.selecionarFormulario(formulario)">

					<input 
						type="checkbox" 
						class="chk-selec-form" 
						tabindex="-1" 
						ng-checked="formulario.selected">
						
				</td>

				<td 
					class="status status-@{{ formulario.STATUS }}"
					ng-if="vm.formulario.TIPO != 3">
					
					<span class="fa fa-circle"></span></td>

				<td class="text-right id">@{{ formulario.ID }}</td>
				<td class="titulo">@{{ formulario.TITULO }}</td>
				<td class="descricao">@{{ formulario.DESCRICAO }}</td>
				<td class="usuario">@{{ formulario.USUARIO_DESCRICAO }}</td>
				<td class="data-criacao">@{{ formulario.DATAHORA_INSERT_HUMANIZE }}</td>

				<td 
					class="periodo-form"
					ng-if="vm.formulario.TIPO != 3">@{{ formulario.PERIODO_INI | date : "dd/MM/yyyy" }} à @{{ formulario.PERIODO_FIM | date : "dd/MM/yyyy" }}</td>
				
			</tr>
		</tbody>

	</table>

</div>