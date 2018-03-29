@extends('helper.include.view.modal', ['id' => 'modal-autenticar-up'])

@section('modal-header-left')

<h4 class="modal-title">
	{{ Lang::get($menu.'.autenticar-up') }}
</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-up" data-hotkey="enter">
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
	<label>{{ Lang::get($menu.'.cod-barras-up') }}:</label>
	<input type="password" name="up_barra" id="up-barra" class="form-control" autocomplete="off" />
</div>

@overwrite