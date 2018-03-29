@php $model            = isset($model)           ? 'ng-model=' . $model           . ' ng-update-hidden' : ''
@php $model_descricao  = isset($model_descricao) ? 'ng-model=' . $model_descricao . ' ng-update-hidden' : ''


<div class="form-group">
	<label for="turno">{{ Lang::get('master.turno') }}:</label>
	<select name="turno" id="turno" class="form-control" {{ $required or '' }} {{ $autofocus or '' }} {{ $disabled or '' }}>
		
		@if ( !empty($opcao_selec) )
			<option disabled selected value="">- {{ Lang::get('master.selecione') }} -</option>
		@endif
		@if ( !empty($opcao_todos) )
			<option class="turno-opcao-todos" value="" {{ isset($opcao_todos_selecionada) ? 'selected' : '' }}>{{ Lang::get('master.todos') }}</option>
		@endif
		
	</select>
	<input type="hidden" id="_turno_cadastrado" value="{{ $turno_cadastrado or '' }}" />
	<input type="hidden" id="_turno_valor" value="0" {{ $model }} />
	<input type="hidden" id="_turno_valor_descricao" value="" {{ $model_descricao }} />
</div>

@if ( !isset($no_script) )
    @section('script')
        <script src="{{ elixir('assets/js/_23010-listar.js') }}"></script>
    @append
@endif