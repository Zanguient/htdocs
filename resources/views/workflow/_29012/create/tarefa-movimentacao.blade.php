<div class="movimentacao-container">

	<label>{{ Lang::get($menu.'.label-movimentacao') }}:</label>

	<span class="tempo-realizado">{{ Lang::get($menu.'.label-tempo-realizado') }}: @{{ tarefa.TEMPO_CONCLUSAO_HUMANIZE }}</span>

	<div class="row">

		@include('workflow._29012.create.tarefa-movimentacao-table')

	</div>

</div>