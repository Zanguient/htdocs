
@php $form_group      = isset($form_group)      ? $form_group                                          : true
@php $style           = isset($style)           ? 'style=' . $style                                    : ''
@php $model           = isset($model)           ? 'ng-model=' . $model . ' ng-update-hidden'           : ''
@php $model_descricao = isset($model_descricao) ? 'ng-model=' . $model_descricao . ' ng-update-hidden' : ''

@php $group_html    = ($form_group == true) ? '<div class="form-group"><label for="estab">' . Lang::get('master.estab') . ':</label>' : ''
@php $endgroup_html = ($form_group == true) ? '</div>' : ''

{!! $group_html !!}
	
    <select name="estab" id="estab" class="form-control estab {{ $class or '' }}" {{ $required or '' }} {{ $autofocus or '' }} {{ $style or '' }} {{ $disabled or '' }}>
		@if ( !empty($opcao_selec) )
			<option disabled selected value="">- {{ Lang::get('master.selecione') }} -</option>
		@endif
		@if ( !empty($opcao_todos) )
			<option value="">{{ Lang::get('master.todos') }}</option>
		@endif
	</select>

	<input type="hidden" class="_estab_cadastrado" value="{{ $estab_cadastrado or '' }}" />
    <input type="hidden" class="_input_estab" id="_input_estab" name="_input_estab" {{ $model }}/>
    <input type="hidden" class="_input_estab_descricao" id="_input_estab_descricao" name="_input_estab_descricao" {{ $model_descricao }}/>
    
{!! $endgroup_html !!}

@if ( !isset($no_script) )
    @section('script')
        <script src="{{ elixir('assets/js/_11020-listar.js') }}"></script>
    @append
@endif