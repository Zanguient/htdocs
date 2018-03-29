@extends('helper.include.view.modal', ['id' => 'modal-registrar-balanca'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.registrar-materia-prima') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-success btn-confirmar" id="btn-confirmar-reg-balanca" data-hotkey="enter">
		<span class="glyphicon glyphicon-ok"></span>
		{{ Lang::get('master.confirmar') }}
	</button>
	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		{{ Lang::get('master.cancelar') }}
	</button>

@overwrite

@section('modal-body')

    <input type="hidden" class="success-balanca" value="0"/>
	<div class="form-group">
		<label>Código de Barras Peça:</label>
		<input type="password" name="balanca_barra" class="form-control" id="balanca-barra" autocomplete="off" />
	</div>

@overwrite