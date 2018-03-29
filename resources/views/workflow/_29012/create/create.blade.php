<form class="form-inline" ng-submit="$ctrl.gravar()">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	@include('workflow._29012.create.modal-create')
	@include('workflow._29012.create.modal-create-arquivo-todos')

</form>