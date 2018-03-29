<div class="panel panel-primary panel-table panel-estab">
	@if (isset($estabelecimento))
		@php $i = 0
		@foreach ($estabelecimento as $estab)
		
			<div class="panel-heading" role="tab" id="heading-estab{{ $i }}">
				<a role="button" data-toggle="collapse" data-parent="#accordion-estab" href="#collapse-estab{{ $i }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse-estab{{ $i }}" data-estab-id="{{ $estab->CODIGO }}">
					<span>{{ Lang::get('master.estab') }}:</span> <span>{{ $estab->CODIGO }} - {{ $estab->NOMEFANTASIA }}</span>
				</a>
			</div>
			<div id="collapse-estab{{ $i }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-estab{{ $i }}">
				<div class="panel-body">
					<div class="panel-group gp-group" id="accordion-gp" role="tablist" aria-multiselectable="true">
						@include('ppcp._22060.index.panel-gp', [
							'gp' => $gp
						])
					</div>
				</div>
			</div>
		
		@php $i++
		@endforeach
	@else
		<div class="panel-vazio">
			{{ Lang::get('master.msg-filtro-vazio') }}
		</div>
	@endif
</div>