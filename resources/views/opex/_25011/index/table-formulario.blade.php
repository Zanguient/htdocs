<div class="table-container table-container-formulario">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="status"></th>
				<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
				<th class="titulo">{{ Lang::get($menu.'.th-titulo') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
				<th class="tipo">{{ Lang::get($menu.'.th-tipo') }}</th>
				<th class="usuario">{{ Lang::get($menu.'.th-responsavel') }}</th>
				<th class="periodo-form">{{ Lang::get($menu.'.th-periodo') }}</th>
				<th class="scroll"></th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-body">						
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr 
					tabindex="0"
					ng-repeat="formulario in vm.listaFormulario.FORMULARIO | orderBy : 'ID' : true | filter : vm.filtrarFormulario track by $index"
					ng-click="vm.exibirFormulario(formulario)" 
					ng-keypress="vm.atalhoFormulario($event, formulario)"
				>

					<td class="status status-@{{ formulario.STATUS }}">
						<span class="fa fa-circle"></span>
					</td>
					<td class="text-right id">@{{ formulario.ID }}</td>
					<td class="titulo">@{{ formulario.TITULO }}</td>
					<td class="descricao">@{{ formulario.DESCRICAO }}</td>
					<td class="tipo">@{{ formulario.FORMULARIO_TIPO_DESCRICAO }}</td>
					<td class="usuario">@{{ formulario.USUARIO_DESCRICAO }}</td>
					<td class="periodo-form">@{{ formulario.PERIODO_INI | date : "dd/MM/yyyy" }} à @{{ formulario.PERIODO_FIM | date : "dd/MM/yyyy" }}</td>
					
				</tr>
			</tbody>
		</table>
	</div>
</div>