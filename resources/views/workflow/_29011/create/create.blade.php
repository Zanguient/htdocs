<form class="form-inline" ng-submit="$ctrl.gravar()">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	@include('workflow._29011.create.modal-create')

</form>