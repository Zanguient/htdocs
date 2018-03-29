<fieldset 
	id="tarefa"
	ng-init="$ctrl.pu224 = {{ $pu224 }}"
	ng-disabled="$ctrl.create29012.infoGeral.infoGeral.STATUS_CONCLUSAO == '3'">

	<legend>{{ Lang::get($menu.'.legend-tarefa') }}</legend>

	<ul 
		class="nav nav-pills" 
		id="tab-tarefa"
		role="tablist">

	    <li 
	    	role="presentation" 
	    	ng-repeat="tarefa in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
			ng-class="{'active': $index == 0}">

	    	<a 
	    		href="#tarefa-@{{ $index }}" 
	    		aria-controls="tarefa-@{{ $index }}" 
	    		role="tab" 
	    		data-toggle="tab"
	    		class="@{{ 'tarefa-'+tarefa.ID }}" 
	    		ng-class="{'do-usuario': tarefa.DO_USUARIO == 1}">
	    	
	    		<span
	    			ng-bind="($index+1 | lpad:[3,'0'])"></span> 
	    		<span 
	    			ng-bind="'Seq.: '+ (tarefa.SEQUENCIA | lpad:[3,'0'])"></span>
	    		<span 
	    			ng-bind="tarefa.TITULO"></span>

	    		<span
	    			class="fa fa-stop icon-reprovacao"
	    			title="{{ Lang::get($menu.'.title-icon-reprovacao') }}"
	    			ng-if="tarefa.PONTO_REPROVACAO > 0"></span>

	    		<span
	    			class="fa fa-stop"
	    			ng-if="tarefa.STATUS_CONCLUSAO == '0' || tarefa.STATUS_CONCLUSAO == '4'"></span>
	    		<span
	    			class="fa fa-play"
	    			ng-if="tarefa.STATUS_CONCLUSAO == '1'"></span>
	    		<span
	    			class="fa fa-pause"
	    			ng-if="tarefa.STATUS_CONCLUSAO == '2'"></span>
	    		<span
	    			class="fa fa-check"
	    			ng-if="tarefa.STATUS_CONCLUSAO == '3'"></span>
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
    	
    	<div 
    		role="tabpanel" 
    		class="tab-pane fade" 
    		ng-repeat="tarefa in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
			ng-if="tarefa.STATUSEXCLUSAO != '1'"
			ng-class="{'in active': $index == 0}"
			id="tarefa-@{{ $index }}">

			<div class="row button-container">

				<button 
					type="button" 
					class="btn btn-primary" 
					title="{{ Lang::get($menu.'.title-iniciar-tarefa') }}"
					ng-click="$ctrl.alterarSituacao(1, tarefa)"
					ng-disabled="tarefa.habilitarIniciar == false">

					<span class="glyphicon glyphicon-play"></span>
					{{ Lang::get('master.iniciar') }}
				</button>

				<button 
					type="button" 
					class="btn btn-warning" 
					title="{{ Lang::get($menu.'.title-pausar-tarefa') }}"
					ng-click="$ctrl.alterarSituacao(2, tarefa)"
					ng-disabled="tarefa.habilitarPausar == false">

					<span class="glyphicon glyphicon-pause"></span>
					{{ Lang::get('master.pausar') }}
				</button>

				<button 
					type="button" 
					class="btn btn-success" 
					title="{{ Lang::get($menu.'.title-concluir-tarefa') }}"
					ng-click="$ctrl.alterarSituacao(3, tarefa)"
					ng-disabled="tarefa.habilitarConcluir == false">

					<span class="glyphicon glyphicon-ok"></span>
					{{ Lang::get('master.concluir') }}
				</button>

				<button 
					type="button" 
					class="btn btn-info" 
					title="{{ Lang::get($menu.'.title-reativar-tarefa') }}"
					ng-click="$ctrl.alterarSituacao(0, tarefa)"
					ng-disabled="tarefa.STATUS_CONCLUSAO != '3'"
					ng-if="$ctrl.pu224 == '1' || $ctrl.pu224 == '2'">

					<span class="glyphicon glyphicon-repeat"></span>
					{{ Lang::get($menu.'.button-reativar') }}
				</button>

				<button 
					type="button" 
					class="btn btn-danger" 
					title="{{ Lang::get($menu.'.title-reprovar-tarefa') }}"
					ng-click="$ctrl.alterarSituacao(4, tarefa)"
					ng-disabled="tarefa.STATUS_CONCLUSAO != '3'"
					ng-if="tarefa.PONTO_REPROVACAO > 0">

					<span class="glyphicon glyphicon-ban-circle"></span>
					{{ Lang::get($menu.'.button-reprovar') }}
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

				<div class="form-group">

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

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case" 
							rows="4" 
							cols="70" 
							maxlength="200" 
							disabled 
							ng-model="tarefa.DESCRICAO">
						</textarea>

						<span class="contador"><span>@{{ 200 - tarefa.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
					
					</div>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-sequencia') }}:</label>

					<input 
						type="number"
						class="form-control input-menor"
						min="1"
						disabled 
						ng-model="tarefa.SEQUENCIA"
						string-to-number>

				</div>

				<div 
					class="form-group"
					ng-if="($index > 0) && (tarefa.PONTO_REPROVACAO > 0)">

					<label>{{ Lang::get($menu.'.label-ao-reprovar') }}:</label>

					<select
						class="form-control input-maior"
						ng-model="tarefa.PONTO_REPROVACAO"
						disabled>

						<option value=""></option>

						<option
							ng-repeat="trf in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
							ng-bind="trf.TITULO"
							ng-value="$index+1"></option>

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
						disabled
						ng-model="tarefa.TEMPO_PREVISTO_HORA"
						string-to-number>

					<span class="abrev-tempo-previsto">h</span>

					<input 
						type="number"
						class="form-control input-menor"
						min="0"
						max="59" 
						disabled
						ng-model="tarefa.TEMPO_PREVISTO_MINUTO"
						string-to-number>

					<span class="abrev-tempo-previsto">m</span>

				</div>

				<div class="form-group form-group-dia-semana">

					<label>{{ Lang::get($menu.'.label-dias-e-horarios') }}:</label>

					<div class="dia-semana-container">

						<label>
							<input 
								type="checkbox"
								disabled 
								ng-checked="tarefa.DOMINGO == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.DOMINGO = (tarefa.DOMINGO == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-domingo') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.SEGUNDA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SEGUNDA = (tarefa.SEGUNDA == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-segunda') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.TERCA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.TERCA = (tarefa.TERCA == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-terca') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.QUARTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.QUARTA = (tarefa.QUARTA == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-quarta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.QUINTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.QUINTA = (tarefa.QUINTA == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-quinta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.SEXTA == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SEXTA = (tarefa.SEXTA == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-sexta') }}
						</label>

						<label>
							<input 
								type="checkbox"
								disabled
								ng-checked="tarefa.SABADO == '1'"
								ng-true-value="'1'"
								ng-false-value="'0'"
								ng-click="tarefa.SABADO = (tarefa.SABADO == '1') ? '0' : '1'">
							{{ Lang::get($menu.'.label-sabado') }}
						</label>

						<input 
							type="text"
							class="form-control input-maior"
							placeholder="{{ Lang::get($menu.'.placeholder-horario-permitido') }}" 
							ng-model="tarefa.HORARIO_PERMITIDO"
							disabled>

					</div>

				</div>

			</div>

			<div class="row">

				@include('workflow._29012.create.tarefa-campo-dinamico')
				@include('workflow._29012.create.tarefa-arquivo-destinatario')
				@include('workflow._29012.create.tarefa-comentario')
				@include('workflow._29012.create.tarefa-arquivo')
				@include('workflow._29012.create.tarefa-movimentacao')				
				@include('workflow._29012.create.tarefa-destinatario')
				@include('workflow._29012.create.tarefa-notificado')

			</div>

    	</div>
    </div>

</fieldset>