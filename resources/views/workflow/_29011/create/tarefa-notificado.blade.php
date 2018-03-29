<div class="notificado-container">

	<label>{{ Lang::get($menu.'.label-notificados') }}:</label>

	<div class="button-container">

		<button 
			type="button" 
			class="btn btn-sm btn-primary" 
			data-toggle="modal" 
			data-target="#modal-pesq-usuario-notificado"
			ng-click="$ctrl.incluirNotificado(tarefa)"
			ng-disabled="$ctrl.tipoTela == 'exibir'"
		>
			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get('master.incluir') }}
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-danger"
			ng-click="$ctrl.excluirNotificadoEscolhido(tarefa)"
			ng-disabled="$ctrl.tipoTela == 'exibir'"
		>
			<span class="glyphicon glyphicon-trash"></span> 
			{{ Lang::get('master.excluir') }}
		</button>

	</div>

	<div class="row">

		@include('workflow._29011.create.tarefa-notificado-table')

	</div>

	<div class="row">

		<label class="label-sem-email">{{ Lang::get($menu.'.label-sem-email') }}</label>
		
	</div>

</div>