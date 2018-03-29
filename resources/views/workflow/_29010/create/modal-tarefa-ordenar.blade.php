@extends('helper.include.view.modal', ['id' => 'modal-ordenar', 'class_size' => ''])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.titulo-ordenar') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModalOrdenar()">

		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	<ul class="list-group">
		<li 
			ng-repeat="tarefa in $ctrl.tarefa | orderBy:['SEQUENCIA','ID'] track by $index"
			class="list-group-item list-tarefa-@{{ tarefa.ID }}">

			@{{ tarefa.TITULO }}
			
			<input
				type="number" 
				class="form-control input-reordenar-sequencia"
				min="1"
				ng-model="tarefa.SEQUENCIA_TMP"
				ng-keydown="$ctrl.eventoInputOrdenar($event, tarefa)"
				ng-change="$ctrl.reordenarSequencia(tarefa, '@{{ tarefa.SEQUENCIA_TMP }}')">

		</li>
	</ul>

@overwrite