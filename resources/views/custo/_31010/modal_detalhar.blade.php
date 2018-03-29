@extends('helper.include.view.modal', ['id' => 'modal-detalhar', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		@{{vm.Item.DADOS[0].DESCRICAO}}
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default btn-cancelar" data-dismiss="modal" data-hotkey="esc" ng-click="vm.FecharCusto()">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Voltar
    </button>
@overwrite

@section('modal-body')

    @include('custo._31010.info_detalhamento')

    <br>
    <div class="kpi-div ficha" style="width: 100%; background-color: white; overflow: scroll;">
        <div style="width: 99%; height: 100%;" class="img-loading">
            <div class="img-fundo"></div>
        </div>

        <div style="width: 99%; height: 100%;" id="chart_div"></div> 
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite