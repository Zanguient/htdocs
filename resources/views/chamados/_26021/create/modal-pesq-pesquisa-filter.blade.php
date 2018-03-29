<div class="form-group">
	<div class="input-group">
		<input 
			type="search"
			class="form-control input-maior input-filtrar-modelo-pesquisa" 
			placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
			autocomplete="off"
			ng-model="$ctrl.filtrarModeloPesquisa" 
			ng-change="$ctrl.Create.fixVsRepeatPesqPesquisa()"
			ng-init="$ctrl.filtrarModeloPesquisa = ''">

		<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>	
	</div>
</div>