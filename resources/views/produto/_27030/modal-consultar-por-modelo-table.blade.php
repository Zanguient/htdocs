<div class="table-container table-container-cor-por-modelo">
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th class="cor-amostra" title="{{ Lang::get($menu.'.th-amostra-title') }}">{{ Lang::get($menu.'.th-amostra') }}</th>
				<th class="cor-id text-right" title="{{ Lang::get($menu.'.th-id-title') }}">{{ Lang::get($menu.'.th-id') }}</th>
				
				@if ($pu218 != 1)
				<th class="cor-descricao" title="{{ Lang::get($menu.'.th-descricao-title') }}">{{ Lang::get($menu.'.th-descricao') }}</th>
				@endif

				@php $class_maior = '';
				
				@if ($pu218 == 1)
					@php $class_maior = 'col-maior';
				@endif

				<th class="cor-condicao {{ $class_maior }}" title="{{ Lang::get($menu.'.th-condicao-title') }}">{{ Lang::get($menu.'.th-condicao') }}</th>
				
				@php /*
				<!-- <th class="cor-classe text-right" title="{{ Lang::get($menu.'.th-classe-title') }}">{{ Lang::get($menu.'.th-classe') }}</th>
				<th class="cor-perfil text-right" title="{{ Lang::get($menu.'.th-perfil-title') }}">{{ Lang::get($menu.'.th-perfil') }}</th>
				<th class="cor-tonalidade" title="{{ Lang::get($menu.'.th-tonalidade-title') }}">{{ Lang::get($menu.'.th-tonalidade') }}</th>
				<th class="cor-tonalidade-fornecedor" title="{{ Lang::get($menu.'.th-tonalidade-fornecedor-title') }}">{{ Lang::get($menu.'.th-tonalidade-fornecedor') }}</th>
				<th class="cor-observacao" title="{{ Lang::get($menu.'.th-observacao-title') }}">{{ Lang::get($menu.'.th-observacao') }}</th>
				<th class="cor-quantidade text-right" title="{{ Lang::get($menu.'.th-quantidade-cor-title') }}">{{ Lang::get($menu.'.th-quantidade-cor') }}</th> -->
				@php */
			</tr>
		</thead>
	</table>

	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<tbody vs-repeat vs-scroll-parent=".table-container">
				<tr
					tabindex="0"
					ng-repeat="cor in $ctrl.listaCorPorModelo | orderBy : ['MAIS_PEDIDO', 'DESCRICAO'] | filter : $ctrl.filtrarCorPorModelo"
					ng-click="$ctrl.selecionarCorPorModelo(cor)"
					ng-keypress="$event.keyCode == 13 ? $ctrl.selecionarCorPorModelo(cor) : null"
					ng-class="{'selected': cor.CODIGO == $ctrl.corPorModeloSelec.CODIGO, 'mais-pedido': cor.MAIS_PEDIDO == 0}"
				>
					<td class="cor-amostra">
						<span 
							style="background-color: @{{ cor.AMOSTRA | toColor }}"
							ng-if="cor.AMOSTRA > 0"
						></span>
					</td>
					<td class="cor-id text-right">@{{ cor.CODIGO }}</td>
					
					@if ($pu218 != 1)
					<td class="cor-descricao">@{{ cor.DESCRICAO }}</td>
					@endif

					<td class="cor-condicao {{ $class_maior }}">@{{ cor.CONDICAO }}</td>

					@php /*
					<!-- <td class="cor-classe text-right">@{{ cor.CLASSE }}</td>
					<td class="cor-perfil text-right">@{{ cor.PERFIL }}</td>
					<td class="cor-tonalidade">@{{ cor.TONALIDADE }}</td>
					<td class="cor-tonalidade-fornecedor">@{{ cor.TON_FORNECEDOR }}</td>
					<td class="cor-observacao">@{{ cor.OBSERVACAO }}</td>
					<td class="cor-quantidade text-right">@{{ cor.QTD_CORES }}</td> -->
					@php */
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