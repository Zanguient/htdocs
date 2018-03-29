@php /**
$class_size - Valores padrão: modal-sm, modal-lg, modal-big, modal-full
@php */



@php $class_master = isset($class_size) ? 'master-' . $class_size : ''
@php $class_size   = isset($class_size) ? $class_size : ''
@php $class_size   = $class_size == 'modal-full' ? 'modal-big' : $class_size
@php $class        = isset($id) ? ' ' . $id : ''
@php $id           = isset($id) ? $id : 'modal'

<div class="modal fade{{ $class }} {{ $class_master }}" id="{{ $id }}" role="dialog" data-keyboard="false" data-backdrop="static">

	<div class="modal-dialog {{ $class_size or '' }}" role="document">
        @if ( View::hasSection('modal-start') )
            @yield('modal-start')
        @endif
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

						<button type="button" class="btn btn-primary btn-toggle-acoes-modal">
							<span class="glyphicon glyphicon-option-vertical"></span>
						</button>

						<div class="acoes-modal">
							@yield('modal-header-right')
						</div>

					@else

						<button type="button" class="btn btn-danger btn-popup-right btn-cancelar" data-dismiss="modal" data-hotkey="f11">
							<span class="glyphicon glyphicon-ban-circle"></span>
							{{ Lang::get('master.cancelar') }}
						</button>

					@endif
				</div>
					
			</div>
			<div class="modal-body">
				
				@yield('modal-body')
				
			</div>
			
			@if ( View::hasSection('modal-footer') )
			<div class="modal-footer">
				
				@yield('modal-footer')
				
			</div>
			@endif
			
		</div>
        @if ( View::hasSection('modal-end') )
            @yield('modal-end')
        @endif
	</div>
</div>