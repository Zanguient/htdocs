<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-media-geral') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-media-geral') }}:</label>

			<input 
				type="number" 
				class="form-control"  
				readonly 
				ng-model="$ctrl.Create.avaliacao.META_MEDIA_GERAL">

		</div>

		<div class="form-group">

			<label 
				class="alcancou-meta alcancou" 
				ng-if="$ctrl.Create.avaliacao.ALCANCOU_META_MEDIA_GERAL == '1'">

				<span class="glyphicon glyphicon-ok"></span>

				{{ Lang::get($menu.'.label-alcancou') }}

			</label>

			<label 
				class="alcancou-meta nao-alcancou" 
				ng-if="$ctrl.Create.avaliacao.ALCANCOU_META_MEDIA_GERAL == '0'">
				
				<span class="glyphicon glyphicon-remove"></span>

				{{ Lang::get($menu.'.label-nao-alcancou') }}

			</label>

		</div>

	</div>

</fieldset>