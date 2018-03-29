<div class="form-group">

	<label>{{ Lang::get($menu.'.label-peso-final') }}:</label>

	<input
		type="text"
		class="form-control input-menor" 
		readonly
		ng-model="$ctrl.Create.avaliacao.PESO_FINAL_RESUMO"
		ng-value="$ctrl.Create.avaliacao.PESO_FINAL_RESUMO | number:2">

</div>

<div class="form-group">

	<label>{{ Lang::get($menu.'.label-resultado-final') }}:</label>

	<input
		type="text"
		class="form-control input-menor" 
		readonly
		ng-model="$ctrl.Create.avaliacao.RESULTADO_FINAL_RESUMO"
		ng-value="$ctrl.Create.avaliacao.RESULTADO_FINAL_RESUMO | number:2">

</div>