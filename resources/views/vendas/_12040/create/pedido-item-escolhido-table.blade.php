<div class="table-container table-container-item-escolhido table-ec" style="max-height: 250px;">
	
	<div class="scroll-table">
		<table class="table table-striped table-bordered table-hover table-body">
			<thead>
				<tr>
					<th class="chk"						></th>
					<th class="sequencia text-right"	>{{ Lang::get($menu.'.th-sequencia') }}</th>
					<th class="produto"					>{{ Lang::get($menu.'.th-produto') }}</th>
					<th class="perfil"					>{{ Lang::get($menu.'.th-perfil') }}</th>
					<th 
						class="tamanho text-right" 
						title="{{ Lang::get($menu.'.th-tamanho') }}"
					>
						{{ Lang::get($menu.'.th-tamanho-abrev') }}
					</th>
					<th 
						class="quantidade text-right"
						title="{{ Lang::get($menu.'.th-quantidade') }}"
					>
						{{ Lang::get($menu.'.th-quantidade-abrev') }}
					</th>

					<th class="valor-total text-right" title="Faturado">
						Faturado
					</th>
					<th class="valor-total text-right" title="Saldo">
						Saldo
					</th>
					<th class="valor-total text-right" title="A Produzir">
						A Produzir
					</th>
					<th class="valor-total text-right" title="Em Produção">
						Em Produção
					</th>
					<th class="valor-total text-right" title="Alocado">
						Alocado
					</th>
					<th class="valor-total text-right" title="Encerrado">
						Encerrado
					</th>
					<th class="valor-total text-right" title="Valor ST">
						Valor ST
					</th>

					<th 
						class="um" 
						title="{{ Lang::get($menu.'.th-um-title') }}"
					>
						{{ Lang::get($menu.'.th-um') }}
					</th>
					<th 
						class="valor-unit text-right"
						title="{{ Lang::get($menu.'.th-valor-unitario') }}"
					>
						{{ Lang::get($menu.'.th-valor-unitario-abrev') }}
					</th>
					<th class="valor-total text-right">{{ Lang::get($menu.'.th-valor-total') }}</th>
					<th 
						class="data-ideal"
						title="{{ Lang::get($menu.'.th-data-ideal-title') }}"
					>
						{{ Lang::get($menu.'.th-data-ideal') }}
					</th>
							
					

				</tr>
				</thead>

				<tbody>
					<tr 
						tabindex="0"
						ng-repeat="item in $ctrl.pedidoItemEscolhido"
						ng-click="$ctrl.selecionarPedidoItemEscolhido(item)"
						ng-class="{ selected: item.selected }"
					>
						<td class="chk">
							<input 
								type="checkbox" 
								class="chk-selec-form" 
								tabindex="-1" 
								ng-checked="item.selected"
							/>
						</td>

						<td class="sequencia text-right">@{{ item.sequencia | lpad:[3,'0'] }}</td>

						@if ($pu218 != 1)
						<td class="produto">@{{ item.produto.CODIGO | lpad:[6,'0'] }} - @{{ item.produto.DESCRICAO }}</td>
						@else
						<td class="produto">@{{ item.produto.CODIGO | lpad:[6,'0'] }} - @{{ item.modelo.MODELO_DESCRICAO }} COR @{{ item.cor.CODIGO | lpad:[5,'0'] }}</td>
						@endif

						<td class="perfil"					>@{{ item.perfilDescricao }}</td>
						<td class="tamanho text-right"		>@{{ item.tamanhoDescricao }}</td>
						<td class="quantidade text-right"	>@{{ item.quantidade | number }}</td>

						<td class="valor-total text-right" 	>@{{ item.FATURADO  	| number }}</td>
						<td class="valor-total text-right" 	>@{{ item.SALDO_FATURAR | number }}</td>
						<td class="valor-total text-right" 	>@{{ item.PRODUZIR      | number }}</td>
						<td class="valor-total text-right" 	>@{{ item.EMPRODUCAO  	| number }}</td>
						<td class="valor-total text-right" 	>@{{ item.ALOCADO    	| number }}</td>
						<td class="valor-total text-right" 	>@{{ item.ENCERRADO   	| number }}</td>
						<td class="valor-total text-right" 	>@{{ item.VALOR_SBT   	| currency:'R$ ':2 }}</td>

						<td class="um"						>@{{ item.produto.UM }}</td>
						<td class="valor-unit text-right"	>@{{ item.valorUnitario | currency:'R$ ':2 }}</td>
						<td class="valor-total text-right"	>@{{ item.valorTotal | currency:'R$ ':2 }}</td>
						<td class="data-ideal" 			    >@{{ item.dataIdeal }}</td>
						



					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>