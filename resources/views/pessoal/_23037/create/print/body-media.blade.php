<fieldset class="fieldset-media">

	<legend>{{ Lang::get($menu.'.legend-media-geral') }}</legend>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-media-geral') }}:</label>

			<span ng-bind="$ctrl.Create.avaliacao.META_MEDIA_GERAL"></span>

		</div>

		<div class="form-group">

			<span 
				class="alcancou-meta alcancou" 
				ng-if="$ctrl.Create.avaliacao.ALCANCOU_META_MEDIA_GERAL == '1'">

				<span class="span-icon-check">&#10004;</span>
				{{ Lang::get($menu.'.label-alcancou') }}

			</span>

			<span 
				class="alcancou-meta nao-alcancou" 
				ng-if="$ctrl.Create.avaliacao.ALCANCOU_META_MEDIA_GERAL == '0'">

				<span class="span-icon-check">&#10006;</span>
				{{ Lang::get($menu.'.label-nao-alcancou') }}

			</span>

		</div>

	</div>

</fieldset>