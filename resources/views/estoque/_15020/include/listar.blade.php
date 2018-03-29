<div class="form-group">
	<label for="loc">{{ Lang::get('master.loc') }}:</label>
	<select name="loc{{ $chave or '' }}" class="form-control loc {{ $class or '' }}" {{ $required or '' }} {{ $autofocus or '' }} {{ $disabled or '' }}>
		@if ( !empty($opcao_selec) )
			<option disabled selected value="">- {{ Lang::get('master.selecione') }} -</option>
		@endif
		@if ( !empty($opcao_todos) )
			<option value="">{{ Lang::get('master.todos') }}</option>
		@endif
	</select>

	<input type="hidden" class="_loc_cadastrado " value="{{ $loc_cadastrado or '' }}" />
</div>

@if ( !isset($no_script) )
    @section('script')
        <script src="{{ elixir('assets/js/_15020-listar.js') }}"></script>
    @append
@endif