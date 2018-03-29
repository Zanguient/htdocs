<div class="table-container table-container-modelo-por-cliente">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="amostra text-center" title="{{ Lang::get($menu.'.th-amostra') }}">{{ Lang::get($menu.'.th-amostra') }}</th>
				<th class="modelo" title="{{ Lang::get($menu.'.th-modelo') }}">{{ Lang::get($menu.'.th-modelo') }}</th>
				<th class="grade" title="{{ Lang::get($menu.'.th-grade') }}">{{ Lang::get($menu.'.th-grade') }}</th>
			</tr>
		</thead>
	</table>
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr 
					tabindex="0" 
					ng-repeat="modelo in $ctrl.listaModeloPorCliente | filter : $ctrl.filtrarModeloPorCliente"
					ng-click="$ctrl.selecionarModeloPorCliente($event, modelo)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionarModeloPorCliente($event, modelo) : null"
					ng-class="{selected: modelo.MODELO_CODIGO == $ctrl.modeloPorClienteSelec.MODELO_CODIGO, 'mais-pedido': modelo.MAIS_PEDIDO == 0}"
				>
					<td class="amostra text-center">

						<span 
							class="amostra-icone"
							style="background-image: url(/assets/temp/modelo/{{ Auth::user()->CODIGO . '-' }}@{{ modelo.MODELO_CODIGO }})"
						></span>

						{{-- ng-click="$ctrl.verArquivo(modelo.MODELO_CODIGO)" --}}
					</td>
					<td class="modelo">@{{ modelo.MODELO_CODIGO }} - @{{ modelo.MODELO_DESCRICAO }}</td>
					<td class="grade">@{{ modelo.GRADE }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<ul class="legenda">
	<li>
		<div class="cor-legenda"></div>
		<div class="texto-legenda">{{ Lang::get($menu.'.li-status-mais-pedido') }}</div>
	</li>
</ul>