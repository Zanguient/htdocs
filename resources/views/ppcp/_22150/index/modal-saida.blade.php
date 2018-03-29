@extends('helper.include.view.modal', ['id' => 'modal-saida', 'class_size' => 'modal-lg'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Ferramenta.ConfirmarSaida()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Confirmação de Saída da Ferramenta
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
                    <td class="descricao">GP/Estação:</td>
                    <td>@{{ vm.Ferramenta.SELECTED.GP_DESCRICAO }} - @{{ vm.Ferramenta.SELECTED.ESTACAO_DESCRICAO }}</td>
                </tr>
                <tr>
                    <td class="descricao">Ferramenta:</td>
                    <td>@{{ vm.Ferramenta.SELECTED.FERRAMENTA_SERIE }} - @{{ vm.Ferramenta.SELECTED.FERRAMENTA_DESCRICAO }}</td>
                </tr>
                <tr>
                    <td class="descricao">Data/Hora:</td>
                    <td>@{{ vm.Ferramenta.SELECTED.DATAHORA_INICIO | parseDate | date:'dd/MM HH:mm' }}</td>
                </tr>
                <tr>
                    <td class="descricao">Ferramenta:</td>
                    <td>
                        <input 
                            type="password" 
                            placeholder="Insira o código da ferramenta" 
                            autocomplete="new-password"
                            form-validade="true"
                            required
                            pattern="@{{ vm.Ferramenta.SELECTED.FERRAMENTA_BARRAS }}"
                            title="O código de barras deve ser correspondente ao da ferramenta selecionada"
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