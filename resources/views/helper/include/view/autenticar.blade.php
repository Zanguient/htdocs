@extends('helper.include.view.modal', ['id' => 'modal-autenticacao'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get('master.autenticacao') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-operador" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')
	
	<div class="form-group">
		<label>{{ Lang::get('master.operador') }}:</label>
		<input type="password" name="operador_barra" id="operador-barra" class="form-control" autocomplete="off" />
	</div>

@overwrite

@section('script')
	<script src="{{ elixir('assets/js/autenticar.js') }}"></script>
@append