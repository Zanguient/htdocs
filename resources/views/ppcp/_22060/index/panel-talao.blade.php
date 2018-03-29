<div class="panel panel-default panel-talao">
	<div class="titulo-lista">
		<span></span>
		<span>{{ Lang::get($menu.'.talao') }}</span>
		<span>{{ Lang::get($menu.'.modelo') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.densidade') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.espessura') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.produzido') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.aberto-hoje') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.atrasado') }}</span>
	</div>
	@if (isset($talao))
		@php $i = 0
		@foreach ($talao as $t)		
		
			<div class="panel-heading" role="tab" id="heading-talao{{ $i }}">
				<a role="button" data-toggle="collapse" data-parent="#accordion-talao" href="#collapse-talao{{ $i }}" aria-expanded="false" aria-controls="collapse-talao{{ $i }}" data-talao-id="{{ $t->TALAO_ID }}" class="collapsed">
					<span class="t-status status-talao{{ $t->STATUS }}" title="{{ $t->STATUS_DESCRICAO }}"></span>
					<span>{{ $t->TALAO_ID }}</span>
					<span>{{ $t->MODELO_ID }} - {{ $t->MODELO_DESCRICAO }}</span> 
					<span class="text-right">{{ $t->DENSIDADE }}</span> 
					<span class="text-right">{{ number_format($t->ESPESSURA, 2, ',', '.') }}</span>
					<span class="text-right">{{ number_format($t->QUANTIDADE_PRODUZIDA, 4, ',', '.') }} {{ $t->UM }}</span>
					<span class="text-right">{{ number_format($t->QUANTIDADE_ABERTA, 4, ',', '.') }} {{ $t->UM }}</span>
					<span class="text-right">{{ number_format($t->QUANTIDADE_ABERTA_HOJE, 4, ',', '.') }} {{ $t->UM }}</span>
					<span class="text-right">{{ number_format($t->QUANTIDADE_ABERTA_ATRASADA, 4, ',', '.') }} {{ $t->UM }}</span>
				</a>
			</div>

			<div id="collapse-talao{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-talao{{ $i }}">
				<div class="panel-body">
					<div class="panel-group talao-detalhe-group" id="accordion-talao-detalhe" role="tablist" aria-multiselectable="true">
						
					</div>			
				</div>
			</div>
		
		@php $i++
		@endforeach
	@endif
	<ul class="legenda talao">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.em-aberto') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.produzido') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.liberado') }}</div>
		</li>
	</ul>
</div>