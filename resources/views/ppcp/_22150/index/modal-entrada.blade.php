@extends('helper.include.view.modal', ['id' => 'modal-entrada', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="confirmarEntrada" ng-submit="vm.Ferramenta.ConfirmarEntrada()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Confirmação de Entrada da Ferramenta
	</h4>

@overwrite

@section('modal-header-right')
    <button type="submit" id="imprimir-consumo" class="btn btn-success" data-hotkey="f10" data-loading-text="Confirmando...">
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
    <div class="row">
        <table class="table table-striped table-condensed">
            <tbody>
                <tr>
                    <td class="descricao">Ferramenta:</td>
                    <td>
                        <input 
                            type="password" 
                            placeholder="Insira o código da ferramenta" 
                            autocomplete="new-password"
                            form-validade="true"
                            required
                            ng-model="vm.Ferramenta.REGISTRO.FERRAMENTA_BARRAS"/></td>
                </tr>
                <tr>
                    <td class="descricao">Operador:</td>
                    <td>
                        <input 
                            type="password" 
                            placeholder="Insira o código do crachá" 
                            autocomplete="new-password"
                            form-validade="true"
                            required
                            pattern=".{12,12}"
                            ng-model="vm.Ferramenta.REGISTRO.OPERADOR_BARRAS"/></td>
                </tr>
            </tbody>
        </table>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite