@extends('helper.include.view.modal', ['id' => 'modal-vinculo-modelos'])

@section('modal-header-left')

	<h4 class="modal-title">
		Modelos de Origem
	</h4>

@overwrite

@section('modal-header-right')

    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc" tabindex="-1">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>

@overwrite

@section('modal-body')
	
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Modelo</th>
            <th class="text-center">Tam.</th>
            <th>Talões</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="modelo in vm.TalaoComposicao.VINCULO_MODELOS track by $index">
            <td>@{{ modelo.MODELO_ID }} - @{{ modelo.MODELO_DESCRICAO }}</td>
            <td class="text-center">@{{ modelo.TAMANHO_DESCRICAO }}</td>
            <td>@{{ modelo.TALOES }}</td>
        </tr>
    </tbody>
</table>

@overwrite