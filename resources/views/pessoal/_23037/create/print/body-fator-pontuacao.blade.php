<div class="row row-fator-pontuacao">

	<div class="form-group">
		
		<label>{{ Lang::get($menu.'.label-pontuacao-total') }}:</label>

		<span ng-bind="$ctrl.Create.avaliacao.PONTUACAO_TOTAL_FATOR | number:2"></span>
	
	</div>

	<div class="form-group">
		
		<label>{{ Lang::get($menu.'.label-pontuacao-final') }}:</label>

		<span ng-bind="$ctrl.Create.avaliacao.PONTUACAO_MEDIA_FATOR | number:2"></span>
	
	</div>

</div>