<div class="panel panel-success panel-talao-detalhe">
	<div class="titulo-lista">
		<span></span>
		<span>{{ Lang::get($menu.'.id') }}</span>
		<span>{{ Lang::get($menu.'.produto') }}</span>
		<span>{{ Lang::get($menu.'.cor') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.tam') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.projetado') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.produzido') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.saldo') }}</span>
		<span class="text-right">{{ Lang::get($menu.'.sobra') }}</span>
	</div>
	@if (isset($talao_detalhe))
		@php $i = 0
		@foreach ($talao_detalhe as $t)
		
			<div class="panel-heading">
				<a class="collapsed">
				<span class="t-status status-detalhe{{ $t->STATUS_DETALHE }}" title="{{ $t->STATUS_DETALHE_DESCRICAO }}" data-talao-detalhe-id="{{ $t->ID }}"></span>
				<span>{{ $t->ID }}</span>
				<span>{{ $t->PRODUTO_ID }} - {{ $t->PRODUTO_DESCRICAO }}</span>
				<span>{{ $t->COR_ID }} - {{ $t->COR_DESCRICAO }}</span>
				<span class="text-right">{{ $t->TAMANHO }}</span>
				<span class="text-right">{{ number_format($t->QUANTIDADE_PROJETADA, 4, ',', '.') }} {{ $t->UM }}</span>
				<span class="text-right">{{ number_format($t->QUANTIDADE_PRODUZIDA, 4, ',', '.') }} {{ $t->UM }}</span>
				<span class="text-right">{{ number_format($t->SALDO, 4, ',', '.') }} {{ $t->UM }}</span>
				<span class="text-right">{{ number_format($t->QUANTIDADE_SOBRA, 4, ',', '.') }} {{ $t->UM }}</span>
				</a>
			</div>
		
		@php $i++
		@endforeach
	@endif
	<ul class="legenda talao-detalhe">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.em-aberto') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.em-producao') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.produzido') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.encerrado') }}</div>
		</li>
	</ul>
</div>