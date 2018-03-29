<div class="panel panel-success panel-gp">
	<div class="titulo-lista">
		<span>{{ Lang::get($menu.'.gp') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.produzido') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto-hoje') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.atrasado') }}</span>
	</div>
	@if (isset($gp))
		@php $i = 0
		@foreach ($gp as $g)
		
			<div class="panel-heading" role="tab" id="heading-gp{{ $i }}">
				<a role="button" data-toggle="collapse" data-parent="#accordion-gp" href="#collapse-gp{{ $i }}" aria-expanded="false" aria-controls="collapse-gp{{ $i }}" data-gp-id="{{ $g->GP_ID }}" class="collapsed">
					<span>{{ $g->GP_ID }} - {{ $g->GP_DESCRICAO }}</span>
					<span class="text-right">{{ number_format($g->QUANTIDADE_PRODUZIDA, 4, ',', '.') }} {{ $g->UM }}</span>
					<span class="text-right">{{ number_format($g->QUANTIDADE_ABERTA, 4, ',', '.') }} {{ $g->UM }}</span>
					<span class="text-right">{{ number_format($g->QUANTIDADE_ABERTA_HOJE, 4, ',', '.') }} {{ $g->UM }}</span>
					<span class="text-right">{{ number_format($g->QUANTIDADE_ABERTA_ATRASADA, 4, ',', '.') }} {{ $g->UM }}</span>
				</a>
			</div>

			<div id="collapse-gp{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-gp{{ $i }}">
				<div class="panel-body">
					<div class="panel-group up-group" id="accordion-up" role="tablist" aria-multiselectable="true">

					</div>
				</div>
			</div>
		
			@php $i++
		@endforeach
	@endif
</div>