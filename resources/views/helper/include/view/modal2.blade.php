@php /**
$class_size - Valores padrão: modal-sm, modal-lg, modal-big
@php */

@php $class = isset($id) ? ' ' . $id : ''
@php $id    = isset($id) ? $id : 'modal'

@yield('estilo')

<div class="modal fade{{ $class }}" id="{{ $id }}" tabindex="-1" role="dialog">

	<div class="modal-dialog {{ $class_size or '' }}" role="document">

		<div class="modal-content" @if (isset($angular_controller)) ng-controller="{{ $angular_controller }}" @endif>

			<div class="modal-header">
				
				<div class="modal-header-left">
					@yield('modal-header-left')
				</div>
				
				<div class="modal-header-center">
					@yield('modal-header-center')
				</div>
				
				<div class="modal-header-right">
					@if ( View::hasSection('modal-header-right') )

						@yield('modal-header-right')

					@else

					@endif
				</div>
					
			</div>
			
			<form action="{{ route('_13050.store') }}" url-redirect="{{ url('sucessoGravar/_13050') }}" method="POST" class="form-inline js-gravar popup-form">
				<div class="modal-body">

						@yield('modal-body')

				</div>
			</form>
			
			@if ( View::hasSection('modal-footer') )
			<div class="modal-footer">
				
				@yield('modal-footer')
				
			</div>
			@endif
			
		</div>

	</div>
	
</div>