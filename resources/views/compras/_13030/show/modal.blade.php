@extends('helper.include.view.modal', ['id' => 'cota-modal', 'class_size' => 'modal-big'])

@section('modal-header-left')

	<h4 class="modal-title">
		Detalhamento da Cota
	</h4>

@overwrite

@section('modal-header-right')

    <button id="dre-historico" data-consulta-historico data-tabela="TBCCUSTO_COTA" data-tabela-id="" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')

@overwrite