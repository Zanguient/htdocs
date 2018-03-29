<div class="form-group">
	<div class="input-group">
		<input 
			type="search"
			class="form-control input-maior input-filtrar-cliente" 
			placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
			autocomplete="off"
			ng-model="$ctrl.filtrarCliente" 
			ng-change="$ctrl.Create.fixVsRepeatPesqCliente()"
			ng-init="$ctrl.filtrarCliente = ''">

		<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>	
	</div>
</div>