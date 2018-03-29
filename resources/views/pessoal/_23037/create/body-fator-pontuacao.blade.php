<div class="row">
	
	{{-- <!--			
	<div 
		class="form-group"
		ng-repeat="tipo in $ctrl.Create.avaliacao.FATOR_TIPO">

		<label ng-bind="tipo.TITULO +' (média):'"></label>

		<input
			type="text"
			class="form-control" 
			readonly
			ng-model="tipo.PONTUACAO_MEDIA"
			ng-value="tipo.PONTUACAO_MEDIA | number:2">

	</div>
	--> --}}

	<div class="form-group">
		
		<label>{{ Lang::get($menu.'.label-pontuacao-total') }}:</label>

		<input
			type="text"
			class="form-control" 
			readonly
			ng-model="$ctrl.Create.avaliacao.PONTUACAO_TOTAL_FATOR"
			ng-value="$ctrl.Create.avaliacao.PONTUACAO_TOTAL_FATOR | number:2">
	
	</div>

	<div class="form-group">
		
		<label>{{ Lang::get($menu.'.label-pontuacao-final') }}:</label>

		<input
			type="text"
			class="form-control" 
			readonly
			ng-model="$ctrl.Create.avaliacao.PONTUACAO_MEDIA_FATOR"
			ng-value="$ctrl.Create.avaliacao.PONTUACAO_MEDIA_FATOR | number:2">
	
	</div>

</div>