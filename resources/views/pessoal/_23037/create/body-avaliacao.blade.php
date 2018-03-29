<fieldset 
	class="fieldset-avaliacao" 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-avaliacao') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.avaliacao.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.ID"
				ng-value="$ctrl.Create.avaliacao.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="60" 
					maxlength="100" 
					required 
					ng-model="$ctrl.Create.avaliacao.TITULO"
					readonly></textarea>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-data-avaliacao') }}:</label>

			<input 
				type="month" 
				class="form-control"  
				ng-model="$ctrl.Create.avaliacao.DATA_AVALIACAO_INPUT"
				readonly>

		</div>

		<div 
			class="form-group" 
			ng-if="$ctrl.Create.avaliacao.DATAHORA_INSERT_INPUT != null">

			<label>{{ Lang::get($menu.'.label-data-resposta') }}:</label>

			<input 
				type="datetime-local" 
				class="form-control"  
				ng-model="$ctrl.Create.avaliacao.DATAHORA_INSERT_INPUT"
				readonly>

		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-colaborador') }}:</label>

			<div class="input-group">
			
				<input 
					type="text" 
					class="form-control input-maior normal-case input-colaborador" 
					required 
					readonly 
					ng-model="$ctrl.Create.avaliacao.COLABORADOR"
					ng-value="
						($ctrl.Create.avaliacao.COLABORADOR)
							? $ctrl.Create.avaliacao.COLABORADOR.PESSOAL_NOME
							: ''
					"
					ng-class="{alterando: $ctrl.tipoTela != 'exibir'}"
					ng-click="$ctrl.CreateColaborador.exibirModal()">

				<button 
					type="button" 
					class="btn input-group-addon btn-filtro" 
					tabindex="-1"
					ng-click="$ctrl.CreateColaborador.exibirModal()">

					<span class="fa fa-search"></span>

				</button>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-cargo') }}:</label>

			<input 
				type="text" 
				class="form-control normal-case input-maior" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.COLABORADOR.CARGO_DESCRICAO">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-formacao') }}:</label>

			<input 
				type="text" 
				class="form-control normal-case input-medio-extra" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.COLABORADOR.PESSOAL_ESCOLARIDADE_DESCRICAO">

		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-gerencia') }}:</label>

			<input 
				type="text" 
				class="form-control input-maior normal-case input-gestor" 
				readonly
				value="{{ ucwords(mb_strtolower(Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO)) }}">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-setor') }}:</label>

			<input 
				type="text" 
				class="form-control normal-case input-maior" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.COLABORADOR.CENTRO_DE_CUSTO_DESCRICAO">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-admissao') }}:</label>

			<input 
				type="date" 
				class="form-control" 
				readonly 
				ng-model="$ctrl.Create.avaliacao.COLABORADOR.DATA_ADMISSAO_INPUT">

		</div>

	</div>

	<div class="row">
		
		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-instrucoes') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="6" 
					cols="110"   
					readonly 
					ng-model="$ctrl.Create.avaliacao.INSTRUCAO_INICIAL"></textarea>

			</div>

		</div>

	</div>

</fieldset>