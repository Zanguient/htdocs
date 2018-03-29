<form class="form-inline" ng-submit="$ctrl.store()" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	@include('workflow._29010.create.modal-create')

</form>