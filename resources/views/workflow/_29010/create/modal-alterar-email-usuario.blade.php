@extends('helper.include.view.modal', ['id' => 'modal-alterar-email-usuario'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-email') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-success"
		data-hotkey="alt+g"
		ng-click="$ctrl.gravarEmailUsuario()">

		<span class="glyphicon glyphicon-ok"></span> 
		{{ Lang::get('master.gravar') }}
	</button>

	<button 
		type="button" 
		class="btn btn-danger btn-cancelar"
		data-hotkey="f11"
		ng-click="$ctrl.cancelarAlterarEmailUsuario()">

		<span class="glyphicon glyphicon-ban-circle"></span> 
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">

		<div class="row">

			<div class="form-group">

				<label>{{ Lang::get($menu.'.label-nome') }}: </label>
				
				<input 
					type="text" 
					class="form-control input-maior" 
					autocomplete="off"
					ng-model="$ctrl.emailUsuario.NOME"
					readonly>
			</div>

		</div>
		
		<div class="row">

			<div class="form-group">
				
				<label>{{ Lang::get($menu.'.label-email') }}: </label>

				<input 
					type="email" 
					class="form-control input-maior" 
					autocomplete="off"
					ng-model="$ctrl.emailUsuario.EMAIL">
			</div>

		</div>

	</form>

@overwrite