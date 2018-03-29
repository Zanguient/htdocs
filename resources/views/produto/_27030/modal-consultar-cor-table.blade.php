<div class="table-container table-container-consultar-cor">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th 
					class="cor-amostra" 
					title="{{ Lang::get($menu.'.th-amostra-title') }}">
					{{ Lang::get($menu.'.th-amostra') }}</th>

				<th 
					class="cor-id text-right" 
					title="{{ Lang::get($menu.'.th-id-title') }}">
					{{ Lang::get($menu.'.th-id') }}</th>

				<th 
					class="cor-descricao" 
					title="{{ Lang::get($menu.'.th-descricao-title') }}">
					{{ Lang::get($menu.'.th-descricao') }}</th>

				<th 
					class="cor-condicao" 
					title="{{ Lang::get($menu.'.th-condicao-title') }}">
					{{ Lang::get($menu.'.th-condicao') }}</th>
			</tr>
		</thead>
	</table>

	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">			
				<tr
					tabindex="0"
					ng-repeat="cor in $ctrl.listaCor | orderBy : ['DESCRICAO'] | filter : $ctrl.filtrarCor"
					ng-click="$ctrl.selecionarCor(cor)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionarCor(cor) : null">

					<td 
						class="cor-amostra">

						<span 
							style="background-color: @{{ cor.AMOSTRA | toColor }}"
							ng-if="cor.AMOSTRA > 0"></span>
					</td>

					<td 
						class="cor-id text-right" 
						ng-bind="cor.CODIGO"></td>

					<td 
						class="cor-descricao" 
						ng-bind="cor.DESCRICAO"></td>

					<td 
						class="cor-condicao" 
						ng-bind="cor.CONDICAO"></td>
				</tr>					
			</tbody>
		</table>
	</div>

</div>