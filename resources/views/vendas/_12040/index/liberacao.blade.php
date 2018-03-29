<form class="form-inline" ng-submit="$ctrl.gravarLiberacao()">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	@include('vendas._12040.index.modal-liberacao')
</form>