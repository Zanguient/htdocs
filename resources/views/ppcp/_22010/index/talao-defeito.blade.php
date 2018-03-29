<fieldset class="defeito">

	<legend>{{ Lang::get('master.defeitos') }}</legend>

	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{{ Lang::get('master.descricao') }}</th>
				<th>{{ Lang::get('master.qtd') }}</th>
				<th>{{ Lang::get('master.obs') }}</th>
			</tr>
		</thead>
		<tbody>
			@if ( isset($taloes_defeito) )
				@foreach ( $taloes_defeito as $talao )
				<tr tabindex="0">
					<td>{{ $talao->DEFEITO_ID }} - {{ $talao->DEFEITO_DESCRICAO }}</td>
					<td>{{ $talao->QUANTIDADE }}</td>
					<td>{{ $talao->DEFEITO_OBSERVACAO }}</td>
				</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
</fieldset>