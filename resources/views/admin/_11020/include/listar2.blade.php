<div class="consulta-container">
	<div class="consulta">
		<div class="form-group">

            <label for="consulta-descricao">{{ Lang::get('master.estab') }}:</label>
            <div class="input-group  ">

                <select ng-model="estab" name="estab" style="border-radius: 3px;" id="estab" class="form-control estab {{ $class or '' }}" {{ $required or '' }} {{ $autofocus or '' }}  {{ $disabled or '' }}>
                    @if ( !empty($opcao_selec) )
                        <option disabled selected value="">- {{ Lang::get('master.selecione') }} -</option>
                    @endif
                    @if ( !empty($opcao_todos) )
                        <option value="">{{ Lang::get('master.todos') }}</option>
                    @endif
                </select>

                <input type="hidden" class="_estab_cadastrado" value="{{ $estab_cadastrado or '' }}" />
                <input type="hidden" class="_input_estab" id="_input_estab" name="_input_estab" value="" />

                @if ( !isset($no_script) )
                    @section('script')
                        <script src="{{ elixir('assets/js/_11020-listar.js') }}"></script>
                    @append
                @endif

            </div>

        </div>
    </div>
</div>