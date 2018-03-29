<ul class="list-inline acoes">

	<li>
		<button 
			type="button" 
			class="btn btn-primary" 
			data-hotkey="f6" 
			data-toggle="modal" 
			data-target="#modal-create"
			ng-click="$ctrl.Index.habilitarIncluir()"
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
			placeholder="{{ Lang::get('master.pesq-place') }}" 
			autocomplete="off"
			autofocus
			ng-model="$ctrl.filtroTabela" 
			ng-init="$ctrl.filtroTabela = ''">

		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>

	</div>

</div>