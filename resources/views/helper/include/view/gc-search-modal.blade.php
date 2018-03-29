@extends('helper.include.view.modal', ['id' => 'model-search'])

@section('modal-header-left')

	<h4 class="modal-title">
		Consultar @{{ $ctrl.name }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		Voltar
	</button>

@overwrite

@section('modal-body')


	<form class="form-inline">
		
		<div class="row">

			<div class="form-group">
				<div class="input-group">
                    <input type="search" ng-model="$ctrl.filtrar" ng-init="$ctrl.filtrar = ''" ng-change="$ctrl.fixVsRepeat()" class="form-control input-maior" placeholder="Filtrar" autocomplete="off"/>
					<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="table-container">

				<table class="table table-bordered table-header">
					<thead>
                        <tr>
                            <th ng-repeat="column in $ctrl.columns">@{{ column.name }}</th>
                        </tr>  
					</thead>
				</table>

				<div class="scroll-table">
					<table class="table table-striped table-bordered table-body">						
						<tbody vs-repeat vs-scroll-parent=".table-container">
                            <tr ng-repeat="row in $ctrl.rows
                                | find: {
                                    model : $ctrl.filtrar,
                                    fields : $ctrl.fields_filter
                                }" tabindex="-1">
                                <td ng-repeat="column in $ctrl.columns">@{{ row[column.field] }}</td>
                            </tr>
						</tbody>
					</table>
				</div>

			</div>

		</div>

	</form>

@overwrite