<ul class="list-inline acoes">

	<li>
		<button 
			type="button" 
			class="btn btn-default" 
			data-hotkey="alt+a"
			ng-click="$ctrl.Index.verListaResposta()">

			<span class="glyphicon glyphicon-th-list"></span> 
			{{ Lang::get($menu.'.button-ver-resposta') }}
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