<fieldset 
	id="tarefa"
	ng-if="$ctrl.tarefa.length > 0">

	<legend>{{ Lang::get($menu.'.legend-tarefa') }}</legend>

	<ul 
		class="nav nav-pills" 
		id="tab-tarefa"
		role="tablist">

	    <li 
	    	role="presentation" 
	    	ng-repeat="tarefa in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
			ng-if="tarefa.STATUSEXCLUSAO != '1'"
			ng-class="{active: $index == 0}">

	    	<a 
	    		href="#tarefa-@{{ $index }}" 
	    		class="@{{ 'tarefa-'+tarefa.ID }}" 
	    		aria-controls="tarefa-@{{ $index }}" 
	    		role="tab" 
	    		data-toggle="tab">
	    	
	    		<span ng-bind="$index+1 | lpad:[3,'0']"></span>
	    		<span ng-bind="'Seq.: '+ (tarefa.SEQUENCIA | lpad:[3,'0'])"></span>
	    		<span ng-bind="tarefa.TITULO"></span>
	    	</a>
	    </li>

	</ul>

	<button 
		type="button" 
		class="btn btn-info btn-add-tarefa"
		data-hotkey="alt+t"
		title="{{ Lang::get($menu.'.title-add-tarefa') }}"
		ng-click="$ctrl.addTarefa()"
		ng-disabled="$ctrl.tipoTela == 'exibir'">

		<span class="glyphicon glyphicon-plus"></span>
	</button>

	<div class="tab-content">
    	
    	<div 
    		role="tabpanel" 
    		ng-repeat="tarefa in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
			ng-if="tarefa.STATUSEXCLUSAO != '1'"
			ng-class="{'in active': $index == 0}"
			class="tab-pane fade tarefa-@{{ tarefa.ID }}" 
			id="tarefa-@{{ $index }}">

			<div class="row button-container">

				<button 
					type="button" 
					class="btn btn-danger btn-excluir-tarefa" 
					title="@{{ 
						{
							0: 'Excluir tarefa.', 
							1: 'Não poderá ser excluída pois é um ponto de retorno de outra tarefa.'
						}
						[tarefa.EH_PONTO_RETORNO] 
					}}"
					ng-click="$ctrl.excluirTarefa(tarefa)"
					ng-disabled="$ctrl.tipoTela == 'exibir' || tarefa.EH_PONTO_RETORNO == 1"
					ng-if="$ctrl.tarefa.length > 1">

					<span class="glyphicon glyphicon-trash"></span>
					{{ Lang::get('master.excluir') }}
				</button>

				<button 
					type="button" 
					class="btn btn-default" 
					ng-click="$ctrl.replicarTarefa(tarefa)"
					ng-disabled="$ctrl.tipoTela == 'exibir'">

					<span class="glyphicon glyphicon-copy"></span> 
					{{ Lang::get($menu.'.button-replicar') }}
				</button>

				<button 
					type="button" 
					class="btn gerar-historico" 
					data-consulta-historico 
					data-tabela="TBWORKFLOW_ITEM_TAREFA" 
					data-tabela-id="@{{ tarefa.ID }}">

			        <span class="glyphicon glyphicon-time"></span> 
			        {{ Lang::get('master.historico') }}
			    </button>

			</div>

			<div class="row">

				<div class="form-group" ng-if="tarefa.ID > 0">

					<label>{{ Lang::get($menu.'.label-id') }}:</label>

					<input 
						type="number"
						class="form-control input-menor"
						ng-model="tarefa.ID"
						ng-value="tarefa.ID | lpad:[5,'0']"
						string-to-number
						disabled>

				</div>
				
				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case input-tarefa-titulo" 
							rows="3" 
							cols="30" 
							maxlength="50" 
							ng-model="tarefa.TITULO"
							ng-disabled="$ctrl.tipoTela == 'exibir'">
						</textarea>

						<span class="contador"><span>@{{ 50 - tarefa.TITULO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
					
					</div>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case input-tarefa-descricao" 
							rows="4" 
							cols="70" 
							maxlength="200" 
							ng-model="tarefa.DESCRICAO"
							ng-disabled="$ctrl.tipoTela == 'exibir'">
						</textarea>

						<span class="contador"><span>@{{ 200 - tarefa.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
					
					</div>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-sequencia') }}:</label>

					<input 
						type="number"
						class="form-control input-menor input-tarefa-sequencia"
						min="1"
						ng-model="tarefa.SEQUENCIA"
						string-to-number
						disabled>

					<button 
						type="button" 
						class="btn btn-info button-ordenar-sequencia"
						title="{{ Lang::get($menu.'.title-ordenar') }}" 
						ng-click="$ctrl.exibirModalOrdenar(tarefa)"
						ng-disabled="$ctrl.tipoTela == 'exibir'">

						<span class="fa fa-sort"></span> 
					</button>

				</div>

				<div 
					class="form-group"
					ng-if="$index > 0">

					<label>{{ Lang::get($menu.'.label-ao-reprovar') }}:</label>

					<select
						class="form-control input-maior"
						ng-model="tarefa.PONTO_REPROVACAO"
						ng-change="$ctrl.verificarPontoRetorno()"
						ng-disabled="$ctrl.tipoTela == 'exibir'">

						<option value=""></option>

						<option
							ng-repeat="trf in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
							ng-if="((trf.STATUSEXCLUSAO != '1') && (tarefa.SEQUENCIA > trf.SEQUENCIA))"
							ng-bind="trf.TITULO"
							ng-value="trf.ORDEM"></option>

					</select>

				</div>

			</div>

			<div class="row">

				<div class="form-group form-group-tempo-previsto">

					<label>{{ Lang::get($menu.'.label-tempo-previsto') }}:</label>

					<input 
						type="number"
						class="form-control input-menor"
						min="0"
						ng-model="tarefa.TEMPO_PREVISTO_HORA"
						ng-disabled="$ctrl.tipoTela == 'exibir'"
						string-to-number>

					<span class="abrev-tempo-previsto">h</span>

					<input 
						type="number"
						class="form-control input-menor"
						min="0"
						max="59" 
						ng-model="tarefa.TEMPO_PREVISTO_MINUTO"
						ng-disabled="$ctrl.tipoTela == 'exibir'"
						string-to-number>

					<span class="abrev-tempo-previsto">m</span>

				</div>

				<div class="form-group form-group-dia-semana">

					<label>{{ Lang::get($menu.'.label-dias-e-horarios') }}:</label>

					<div class="dia-semana-container">

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.DOMINGO == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.DOMINGO = (tarefa.DOMINGO == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-domingo') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.SEGUNDA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SEGUNDA = (tarefa.SEGUNDA == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-segunda') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.TERCA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.TERCA = (tarefa.TERCA == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-terca') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.QUARTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.QUARTA = (tarefa.QUARTA == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-quarta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.QUINTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.QUINTA = (tarefa.QUINTA == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-quinta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.SEXTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SEXTA = (tarefa.SEXTA == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-sexta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								ng-checked="tarefa.SABADO == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SABADO = (tarefa.SABADO == '1') ? '0' : '1'"
								ng-disabled="$ctrl.tipoTela == 'exibir'">
							{{ Lang::get($menu.'.label-sabado') }}
						</label>

						<input 
							type="text"
							class="form-control input-maior input-horario-permitido"
							placeholder="{{ Lang::get($menu.'.placeholder-horario-permitido') }}" 
							ng-model="tarefa.HORARIO_PERMITIDO"
							ng-disabled="$ctrl.tipoTela == 'exibir'">

					</div>

				</div>

			</div>

			<div class="row">

				@include('workflow._29011.create.tarefa-destinatario')

				@include('workflow._29011.create.tarefa-notificado')
				
				@include('workflow._29011.create.tarefa-arquivo')

				@include('workflow._29011.create.tarefa-campo-dinamico')

			</div>

    	</div>
    </div>

</fieldset>

@include('workflow._29011.create.modal-tarefa-ordenar')
@include('workflow._29011.create.modal-pesq-usuario')
@include('workflow._29011.create.modal-pesq-usuario-notificado')
@include('workflow._29010.create.modal-alterar-email-usuario')