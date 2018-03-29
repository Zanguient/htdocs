<div class="panel panel-warning panel-up">
	<div class="titulo-lista">
		<span>{{ Lang::get($menu.'.up') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.produzido') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto-hoje') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.atrasado') }}</span>
	</div>
	@if (isset($up))
		@php $i = 0
		@foreach ($up as $u)
		
			<div class="panel-heading" role="tab" id="heading-up{{ $i }}">
				<a role="button" data-toggle="collapse" data-parent="#accordion-up" href="#collapse-up{{ $i }}" aria-expanded="false" aria-controls="collapse-up{{ $i }}" data-up-id="{{ $u->UP_ID }}" class="collapsed">
					<span>{{ $u->UP_ID }} - {{ $u->UP_DESCRICAO }}</span>
					<span class="text-right">{{ number_format($u->QUANTIDADE_PRODUZIDA, 4, ',', '.') }} {{ $u->UM }}</span>
					<span class="text-right">{{ number_format($u->QUANTIDADE_ABERTA, 4, ',', '.') }} {{ $u->UM }}</span>
					<span class="text-right">{{ number_format($u->QUANTIDADE_ABERTA_HOJE, 4, ',', '.') }} {{ $u->UM }}</span>
					<span class="text-right">{{ number_format($u->QUANTIDADE_ABERTA_ATRASADA, 4, ',', '.') }} {{ $u->UM }}</span>
				</a>
			</div>

			<div id="collapse-up{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-up{{ $i }}">
				<div class="panel-body">
					<div class="panel-group estacao-group" id="accordion-estacao" role="tablist" aria-multiselectable="true">

					</div>
				</div>
			</div>
			
		@php $i++
		@endforeach
	@endif
</div>