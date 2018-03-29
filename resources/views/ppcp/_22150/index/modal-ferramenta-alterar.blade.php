@extends('helper.include.view.modal', ['id' => 'modal-ferramenta-alterar'])

@section('modal-header-left')

	<h4 class="modal-title">
		Confirmação de Saída da Ferramenta
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc" tabindex="-1">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
<div class="row">
    <div class="form-group">
        <label>ID - Descricao:</label>

        <button
            tabindex="0"
            ng-repeat="ferramenta in vm.Ferramenta.DISPONIVEIS track by $index"
            ng-click="vm.Ferramenta.Alterar(ferramenta)"
            style="height: 50px;margin: 5px; width: 80%;"
            type="button"
            class="btn @{{ ferramenta.STATUS_CONFLITO == 1 ? 'btn-danger' : 'btn-success'}} btn-confirmar"
            id="btn-confirmar-ferramenta">
             @{{ferramenta.SERIE | lpad : [4,'0']}} - @{{ferramenta.DESCRICAO}}
        </button>

    </div>
</div>
<div class="legenda-container">
	<label class="legenda-label">Legenda de situação da ferramenta</label>
	<ul class="legenda talao">
		<li>
			<div class="cor-legenda btn-success"></div>
			<div class="texto-legenda">Disponível | </div>
		</li>
		<li>
			<div class="cor-legenda btn-danger"></div>
			<div class="texto-legenda">Em Conflito</div>
		</li>
    </ul>
</div>

@overwrite
