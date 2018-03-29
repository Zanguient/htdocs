	@extends('helper.include.view.modal', ['id' => 'modal-mensagem', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Notificação
	</h4>

	@overwrite

	@section('modal-header-right')
		
		<button ng-click="vm.Acoes.enviarNotificacoes('sendUserNotfi','notoficação')" ng-disabled="vm.msg.TITULO.length == 0" class="btn btn-success" data-hotkey="f10" data-loading-text="Enviando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Enviar
		</button>

		<a ng-click="vm.Acoes.fecharModal()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

@overwrite

	@section('modal-body')

	<div class="arquivo-container">
		<div class="form-group" style="width: 100% !important;">
            <label>Titulo:</label>
            <div class="">
				<input style="width: 100% !important;" type="text" ng-model="vm.msg.TITULO" required="required" name="descricao" class="form-control input-maior">
            </div>       
        </div>

        <div class="form-group" style="width: 100%;">
            <label>Mensagem:</label>
            <div class="">
				<textarea name="editorHtml" id="editorHtml" class="editorHtml" ng-model=" vm.msg.MENSAGEM" class="form-control"></textarea>
            </div>       
        </div>

	</div>

@overwrite