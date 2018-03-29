@extends('helper.include.view.modal', ['id' => 'confirmDelete'])

@section('modal-header-left')
	
	<h4 class="modal-title" id="myModalLabel">Excluir registro</h4>
	
@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-danger btn-popup-left btn-confirmar-excluir" data-hotkey="alt+e" id="confirm">
		<span class="glyphicon glyphicon-trash"></span> 
		{{ Lang::get('master.excluir') }}
	</button>
	
	<button type="button" class="btn btn-default btn-voltar btn-popup-right" data-hotkey="f11" data-dismiss="modal">
		<span class="glyphicon glyphicon-chevron-left"></span>
		 {{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	<p>Tem certeza que deseja excluir este registro?</p>
	
@overwrite

@if ( !isset($no_script) )
    @section('script') 
        <script src="{{ elixir('assets/js/delete-confirm.js') }}"></script>
    @append
@endif