<fieldset 
	ng-disabled="$ctrl.Create.pesquisa.STATUS == '0' || $ctrl.tipoTela == 'exibir'"
	ng-if="$ctrl.Create.pesquisa.PERGUNTA.length > 0">

	<legend>{{ Lang::get($menu.'.legend-pergunta') }}</legend>

	<div class="item-dinamico-container">

		<div 
			class="row item-dinamico item-dinamico-pergunta" 
			ng-repeat="perg in $ctrl.Create.pesquisa.PERGUNTA track by $index"
			ng-class="{'perg-resposta-negativa': perg.RESPOSTA_NEGATIVA == 1}">

			<div class="form-group">

				<label
					class="label label-default label-indicador"
					ng-bind="perg.INDICADOR +' ('+ perg.TAG +')'"
					ng-if="perg.INDICADOR"></label>

				<br>

				<label
					class="label-pergunta-numero"
					ng-bind="(perg.ORDEM | lpad:[2, '0']) +'. '"></label>

				<label 
					class="label-pergunta"
					ng-bind="perg.DESCRICAO"></label>

				<br>

				@include('chamados._26021.create.body-pesquisa-pergunta-alternativa')

			</div>

		</div>


		<div 
			class="row item-dinamico item-dinamico-pergunta" 
			ng-if="$ctrl.Create.pesquisa.STATUS == '2'">

			<div class="form-group">

				<label class="lbl-pergunta">{{ Lang::get($menu.'.label-nota-delfa-pergunta') }}</label>

				<input 
					type="number"
					step="0.1" 
					min="0"
					max="10"
					class="form-control normal-case" 
					required
					ng-model="$ctrl.Create.pesquisa.NOTA_DELFA"
					string-to-number>

			</div>

			<div class="form-group">

				<label class="lbl-pergunta">{{ Lang::get($menu.'.label-obs-delfa') }}</label>

				<div class="textarea-grupo">

					<textarea 
						class="form-control normal-case" 
						rows="3" 
						cols="100" 
						maxlength="300" 
						ng-model="$ctrl.Create.pesquisa.OBSERVACAO_DELFA"></textarea>

					<span class="contador"><span>@{{ 300 - $ctrl.Create.pesquisa.OBSERVACAO_DELFA.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

				</div>

			</div>

		</div>

	</div>

</fieldset>