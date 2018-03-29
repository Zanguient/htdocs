@extends('helper.include.view.modal', ['id' => 'modal-add-lembrete', 'class_size' => 'modal-lg'])

@section('modal-header-left')

<h4 class="modal-title">
	Lembrete
</h4>

@overwrite

@section('modal-header-right')
	
	<button ng-click="vm.lembrete.gravar()" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
		<span class="glyphicon glyphicon-ok"></span> 
		Gravar
	</button>

	<button ng-click="vm.lembrete.excluir()" class="btn btn-danger" data-hotkey="f12" data-loading-text="Excluir...">
		<span class="glyphicon glyphicon-ok"></span> 
		Excluir
	</button>

	<a  ng-click="vm.lembrete.canselar()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
		<span class="glyphicon glyphicon-ban-circle"></span> 
		Cancelar
	</a>

@overwrite

@section('modal-body')
	
	<div class="form-group">
        <label>Data e Hora:</label>
        <input type="datetime-local" ng-model="vm.lembrete.iten.AGENDAMENTO" min="vm.lembrete.min"><br>
    </div>

	<div class="form-group"
		style="width: 100%;">
		<textarea style="height: 130px;" name="editor5" id="editor5" rows="10" cols="80" ng-model="vm.lembrete.comentario" class="form-control"></textarea>
	</div>

@overwrite