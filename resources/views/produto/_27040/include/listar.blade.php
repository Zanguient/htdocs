<div class="form-group">
	<label for="tamanho">{{ Lang::get('produto/_27040.tamanho') }}:</label>
	<div class="input-group">
		<div class="input-group-addon tamanho">
			<button type="button" class="btn btn-primary btn-sm sett" data-toggle="" data-target="#modal-edit">
				<span class="glyphicon glyphicon-triangle-top"></span> 
			</button>
		</div>
		
		@if( isset($multiplo) && $multiplo == 'true' )
			@php $tamanho_desc_name = 'tamanho_desc[]';
			@php $tamanho_name		= 'tamanho[]';
		@else
			@php $tamanho_desc_name = 'tamanho_desc';
			@php $tamanho_name		= 'tamanho';
		@endif
		<input type="text" name="{{ $tamanho_desc_name }}" class="form-control input-menor tamanho-produto NoEnableR" 
			   min="0" valor="10" value="{{ empty($tamanho_desc) ? '': $tamanho_desc }}" readonly />
		<input type="hidden" name="{{ $tamanho_name }}" class="tamanho-posicao" 
			   value="{{ empty($tamanho) ? '': $tamanho }}" />
	</div>
</div>

<!-- Modal -->
<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-header-left">
					<h4 class="modal-title" id="myModalLabel">{{ Lang::get('produto/_27040.tamanhos') }}</h4>
				</div>
				<div class="modal-header-right">
					<button type="button" class="btn btn-default desabilitar-tamanhos" data-dismiss="modal" style="margin-top: 0;">
						<span class="glyphicon glyphicon-chevron-left"></span>
						{{ Lang::get('master.voltar') }}
					</button>
				</div>
			</div>
			<div class="modal-body" align="center">

				<div class="form-group">
					<button type="button" class="btn btn-danger settamanho T01" tamanho="00" data-dismiss="modal" disabled> <span class="T01">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T02" tamanho="00" data-dismiss="modal" disabled> <span class="T02">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T03" tamanho="00" data-dismiss="modal" disabled> <span class="T03">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T04" tamanho="00" data-dismiss="modal" disabled> <span class="T04">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T05" tamanho="00" data-dismiss="modal" disabled> <span class="T05">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T06" tamanho="00" data-dismiss="modal" disabled> <span class="T06">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T07" tamanho="00" data-dismiss="modal" disabled> <span class="T07">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T08" tamanho="00" data-dismiss="modal" disabled> <span class="T08">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T09" tamanho="00" data-dismiss="modal" disabled> <span class="T09">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T10" tamanho="00" data-dismiss="modal" disabled> <span class="T10">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T11" tamanho="00" data-dismiss="modal" disabled> <span class="T11">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T12" tamanho="00" data-dismiss="modal" disabled> <span class="T12">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T13" tamanho="00" data-dismiss="modal" disabled> <span class="T13">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T14" tamanho="00" data-dismiss="modal" disabled> <span class="T14">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T15" tamanho="00" data-dismiss="modal" disabled> <span class="T15">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T16" tamanho="00" data-dismiss="modal" disabled> <span class="T16">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T17" tamanho="00" data-dismiss="modal" disabled> <span class="T17">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T18" tamanho="00" data-dismiss="modal" disabled> <span class="T18">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T19" tamanho="00" data-dismiss="modal" disabled> <span class="T19">00</span> </button>
					<button type="button" class="btn btn-danger settamanho T20" tamanho="00" data-dismiss="modal" disabled> <span class="T20">00</span> </button>
				</div>

			</div>
		</div>
	</div>
</div>

@if ( !isset($no_script) )
    @section('script')
        <script src="{{ elixir('assets/js/_27040-listar.js') }}"></script>
    @append
@endif