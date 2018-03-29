@extends('helper.include.view.modal', ['id' => 'modal-parada-justificativa'])

@section('modal-header-left')

<h4 class="modal-title">
	Justificativa de Parada
</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span>
		Cancelar
	</button>

@overwrite

@section('modal-body')

<div class="div-row">

    <table class="table table-striped table-lc table-hover">
        <tbody>
            <tr class="div-justificativa" style="cursor: pointer"
                ng-repeat="justificativa in vm.Acao.API.JUSTIFICATIVA.DADOS"

                ng-class="{
                            'marcar-justificativa' : justificativa.MARCAR == 1
                        }"
                ng-click="vm.Acao.API.JUSTIFICATIVA.selecionar(justificativa)"            
                >
                <td>
                    @{{justificativa.DESCRICAO}}
                </td>
            </tr>
        </tbody>
    </table>

</div>

@overwrite