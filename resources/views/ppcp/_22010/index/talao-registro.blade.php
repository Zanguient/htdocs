@extends('helper.include.view.modal', ['id' => 'modal-talao-registro'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.registrar-talao') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-registro" data-hotkey="enter">
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
		<label>{{ Lang::get($menu.'.talao') }}:</label>
		<input type="password" name="talao" class="form-control" id="barras-talao" />
	</div>

@overwrite