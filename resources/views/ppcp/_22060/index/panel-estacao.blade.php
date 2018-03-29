@if( $dividir_estacao === 'true' )
<div class="panel panel-danger panel-estacao">
	<div class="titulo-lista">
		<span>{{ Lang::get($menu.'.estacao') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.produzido') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto-hoje') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.atrasado') }}</span>
	</div>
	@if (isset($estacao))
		@php $i = 0
		@foreach ($estacao as $est)
		
			<div class="panel-heading" role="tab" id="heading-estacao{{ $i }}">
				<a role="button" data-toggle="collapse" data-parent="#accordion-estacao" href="#collapse-estacao{{ $i }}" aria-expanded="false" aria-controls="collapse-estacao{{ $i }}" data-estacao-id="{{ $est->ESTACAO_ID }}" class="collapsed">
					<span>{{ $est->ESTACAO_ID }} - {{ $est->ESTACAO_DESCRICAO }}</span>
					<span class="text-right">{{ number_format($est->QUANTIDADE_PRODUZIDA, 4, ',', '.') }} {{ $est->UM }} </span>
					<span class="text-right">{{ number_format($est->QUANTIDADE_ABERTA, 4, ',', '.') }} {{ $est->UM }}</span>
					<span class="text-right">{{ number_format($est->QUANTIDADE_ABERTA_HOJE, 4, ',', '.') }} {{ $est->UM }}</span>
					<span class="text-right">{{ number_format($est->QUANTIDADE_ABERTA_ATRASADA, 4, ',', '.') }} {{ $est->UM }}</span>
				</a>
			</div>

			<div id="collapse-estacao{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-estacao{{ $i }}">
				<div class="panel-body">
					<div class="panel-group talao-group" id="accordion-talao" role="tablist" aria-multiselectable="true">

					</div>			
				</div>
			</div>
		
		@php $i++
		@endforeach
	@endif
</div>
@else
	
	<div class="panel-body">
		<div class="panel-group talao-group" id="accordion-talao" role="tablist" aria-multiselectable="true">

		</div>			
	</div>

@endif