<fieldset class="destinatario-container">

	<legend>{{ Lang::get($menu.'.legend-destinatario') }}</legend>

	<div class="destinatario-bloco">

		<div class="destinatario-filtro">
			<div class="input-group">
				<input 
					type="search" 
					class="form-control" 
					placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
					autocomplete="off"
					ng-init="vm.filtrarDestinatario = ''" 
					ng-model="vm.filtrarDestinatario"
				/>
				<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>

			</div>

		</div>

		<div class="table-ec table-container-destinatario">

			<table class="table table-bordered table-header">

				<thead>
					<tr>
						<th 
							class="destinatario">{{ Lang::get($menu.'.th-destinatario-descricao') }}</th>

						<th 
							class="destinatario-uf"
							ng-if="vm.formulario.TIPO == 3">{{ Lang::get($menu.'.th-uf') }}</th>
						
						<th 
							class="destinatario-peso text-center"
							ng-if="vm.formulario.TIPO != 3">{{ Lang::get($menu.'.th-peso') }}</th>

						<th 
							class="destinatario-status-resposta text-center"
							ng-if="vm.formulario.TIPO != 3">{{ Lang::get($menu.'.th-destinatario-status-resposta') }}</th>

					</tr>
				</thead>
									
				<tbody>
					<tr 
						ng-repeat="destinatario in vm.painel.DESTINATARIO | orderBy : 'DESCRICAO' | filter : vm.filtrarDestinatario"
						ng-if="destinatario.ID"
						ng-click="vm.verDestinatarioResposta(destinatario)"
						ng-class="{ selected: destinatario.selected }"
					>
						<td 
							class="destinatario">@{{ destinatario.ID | lpad : [5, '0'] }} @{{ ' - '+ destinatario.DESCRICAO }}</td>

						<td 
							class="destinatario-uf"
							ng-if="vm.formulario.TIPO == 3">@{{ destinatario.UF }}</td>

						<td 
							class="destinatario-peso text-center"
							ng-if="vm.formulario.TIPO != 3">@{{ destinatario.PESO }}</td>

						<td 
							class="destinatario-status-resposta text-center status-@{{ destinatario.STATUS_RESPOSTA }}"
							ng-if="vm.formulario.TIPO != 3">
							
							<span class="fa @{{ destinatario.STATUS_RESPOSTA == 0 ? 'fa-times' : 'fa-check' }}"></span>
						</td>
					</tr>
				</tbody>

			</table>

		</div>

	</div>

	@include('opex._25010.index.bloco-painel-destinatario-resposta')

</fieldset>