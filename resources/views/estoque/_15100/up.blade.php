	@extends('helper.include.view.modal', ['id' => 'modal-up', 'class_size' => 'modal-menor'])

	@section('modal-header-left')

	<h4 class="modal-title">
		UP
	</h4>

	@overwrite

	@section('modal-header-right')

		<a ng-click="vm.Acoes.logOff()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

@overwrite

@section('modal-body')

	<div class="form-group" style="width: 100% !important;">
        <label>Código de barras da UP:</label>
        <div class="">
			<input ng-keydown="vm.Acoes.UpKeydown($event)" style="width: 100% !important;" type="password" name="{{date('D M j G:i:s T Y')}}" id="{{date('D M j G:i:s T Y')}}" ng-model="vm.gp.CODBARRAS" required="required" class="form-control input-maior">
        </div>       
    </div>


@overwrite