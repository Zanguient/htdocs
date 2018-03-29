<form class="form-inline" ng-submit="$ctrl.store()">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	@include('vendas._12040.create.modal-create')
</form>

<!--
<button type="button" class="btn btn-primary" id="resumo-btn">
	<span class="fa fa-th-list"></span>
</button>
-->