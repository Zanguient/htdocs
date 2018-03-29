<div class="pesquisa-obj-container">

	<div class="input-group input-group-filtro-obj">

		<input 
			type="search" 
			class="form-control filtro-obj" 
			placeholder="{{ Lang::get('master.pesq-place') }}" 
			autocomplete="off"
			autofocus
			ng-model="vm.filtrarFormulario" 
			ng-init="vm.filtrarFormulario = ''" 
		/>

		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>

	</div>

</div>