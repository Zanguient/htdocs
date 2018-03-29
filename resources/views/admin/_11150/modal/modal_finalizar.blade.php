@extends('helper.include.view.modal', ['id' => 'modal-finalizar', 'class_size' => 'modal-lg'])

@section('modal-header-left')

<h4 class="modal-title">
	Caso - @{{vm.caso_id}}
</h4>

@overwrite

@section('modal-header-right')
	
		<button  ng-click="vm.Acoes.fimCaso()" ng-disabled="vm.btnGravar.disabled == true" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<button  ng-click="vm.Acoes.CanselarFinalizar()"  type="button" class="btn btn-danger btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="f11">
		  <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
		</button>

@overwrite

@section('modal-body')

	<div class="form-group" style="width: 100%;">
		<label>Descrição técnica do caso:</label>
		<textarea style="height: 130px;" name="editor2" id="editor2" rows="10" cols="80" ng-model=" vm.Arquivos.comentario" class="form-control"></textarea>
	</div>

	<div class="form-group" style="width: 100%;">
		<label>Solução para este caso:</label>
		<textarea style="height: 130px;" name="editor3" id="editor3" rows="10" cols="80" ng-model=" vm.Arquivos.comentario" class="form-control"></textarea>
	</div>

@overwrite