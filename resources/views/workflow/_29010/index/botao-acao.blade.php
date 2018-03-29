<ul class="list-inline acoes">

	<li>
		<button 
			type="button" 
			class="btn btn-primary" 
			data-hotkey="f6" 
			ng-click="$ctrl.ativarIncluir()"
			ng-disabled="$ctrl.permissaoMenu.INCLUIR != 1">

			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get('master.incluir') }}
		</button>
	</li>
	
</ul>

<div class="pesquisa-obj-container">

	<div class="input-group input-group-filtro-obj">

		<input 
			type="search" 
			class="form-control filtro-obj" 
			placeholder="Pesquise..." 
			autocomplete="off" 
			autofocus
			ng-model="$ctrl.filtrarWorkflow">

		<button 
			type="button" 
			class="input-group-addon btn-filtro btn-filtro-obj">
			
			<span class="fa fa-search"></span>
		</button>

	</div>
	
</div>