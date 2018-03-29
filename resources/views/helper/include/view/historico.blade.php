@if ( !isset($no_button) )
	<button type="button" class="btn gerar-historico gerar-historico-padrao" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
		<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
	</button>
@endif

@extends('helper.include.view.modal', ['id' => 'modal-historico', 'class_size' => 'modal-lg'])
	
@section('modal-header-left')
	
	<h4 class="modal-title" id="myModalLabel">{{ Lang::get('master.historico') }}</h4>
	
@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-default btn-voltar btn-popup-right" data-hotkey="f11" data-dismiss="modal">
		<span class="glyphicon glyphicon-chevron-left"></span>
		 {{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

<div class="historico-corpo" data-tabela="{{ $tabela }}" data-id="{{ $id }}"></div>
	
@overwrite

@if ( !isset($no_script) )
    @section('script') 
        <script src="{{ elixir('assets/js/historico.js') }}"></script>
    @append
@endif