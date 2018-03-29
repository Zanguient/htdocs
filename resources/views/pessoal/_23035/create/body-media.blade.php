<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-media-geral') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-media-geral') }}:</label>

			<input 
				type="number" 
				class="form-control"  
				min="0"
				required 
				ng-model="$ctrl.Create.modelo.META_MEDIA_GERAL">

		</div>

	</div>

</fieldset>