	@extends('helper.include.view.modal', ['id' => 'modal-projeto', 'class_size' => 'modal-big'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Projeto
	</h4>

	@overwrite

	@section('modal-header-right')

		<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')


        <iframe style="
            width : 100%;
            height: 99%;
            border: 0px;
            " 
            class="projeto" 
            id="iframe-projeto" 
            data-style-css="{{ elixir('assets/js/_11100.js') }}"
            data-url="{{$url}}/sense/app/" src=""></iframe>

	@overwrite