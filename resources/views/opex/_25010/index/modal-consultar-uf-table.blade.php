<div class="table-ec table-uf">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="uf">{{ Lang::get($menu.'.th-uf') }}</th>
				<th class="descricao">{{ Lang::get($menu.'.th-descricao') }}</th>
			</tr>
		</thead>
	
		<tbody vs-repeat vs-scroll-parent=".table-uf">
			<tr 
				tabindex="0" 
				ng-repeat="uf in vm.listaUF | filter : vm.filtrarUF | orderBy : 'UF'"
				ng-click="vm.selecionarUF(uf)"
				ng-keypress="$event.keyCode == 13 ? vm.selecionarUF(uf) : null">

				<td class="uf"
					ng-bind="uf.UF"></td>

				<td class="descricao"
					ng-bind="uf.DESCRICAO"></td>
			</tr>
		</tbody>
	</table>
</div>