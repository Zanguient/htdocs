<fieldset class="fieldset-fator">

	<legend>{{ Lang::get($menu.'.legend-fator') }}</legend>

	<div 
		class="fator-tipo"
		ng-repeat="tipo in $ctrl.Create.avaliacao.FATOR_TIPO">

		<h4 
			class="title" 
			ng-bind="tipo.TITULO"></h4>

		<table>
			<thead>
				<tr>
					<th class="titulo">{{ Lang::get($menu.'.label-fator') }}</th>
					<th class="descricao">{{ Lang::get($menu.'.label-descricao') }}</th>
					<th class="ponto text-right">{{ Lang::get($menu.'.label-ponto') }}</th>
					<th class="descritivo">{{ Lang::get($menu.'.label-descritivo-ponto') }}</th>
				</tr>
			</thead>

			<tbody>
				<tr
					ng-repeat="fator in $ctrl.Create.avaliacao.FATOR"
					ng-if="fator.TIPO_ID == tipo.ID">
					
					<td 
						class="titulo" 
						ng-bind="fator.TITULO"></td>

					<td 
						class="descricao" 
						ng-bind="fator.DESCRICAO"></td>

					<td 
						class="ponto text-right" 
						ng-bind="fator.PONTO | number:2"></td>

					<td 
						class="descritivo" 
						ng-bind="fator.NIVEL_PRINT.TITULO +' ('+ fator.NIVEL_PRINT.FAIXA_INICIAL +' à '+ fator.NIVEL_PRINT.FAIXA_FINAL +'): '+fator.NIVEL_PRINT.DESCRICAO"></td>

				</tr>
			</tbody>
		</table>

	</div>

	@include('pessoal._23037.create.print.body-fator-pontuacao')

</fieldset>