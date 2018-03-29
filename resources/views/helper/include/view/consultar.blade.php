<div class="consulta-container">
	<div class="consulta">
		<div class="form-group">
			
            <label for="consulta-descricao">{{ isset($label_descricao) ? $label_descricao : '' }}</label>
			<div class="input-group  {{ isset($class2) ? $class2 : '' }}">
				<input type="search" name="consulta_descricao" class="form-control consulta-descricao {{ isset($class1) ? $class1 : '' }} objConsulta" autocomplete="off" {{ $autofocus or '' }} {{ $required or '' }} {{ isset($readonly) ? $readonly : ''}} value="{{ isset($valor) ? $valor : ''}}" />            

                @if (isset($readonly))
                    @if ($readonly == 'readonly')
						<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button {{ isset($class) ? $class : '' }}" disabled tabindex="-1" {{ !empty($selecionado) ? 'style=display:inline-block;' : '' }}><span class="fa fa-close"></span></button>
                        <button type="button" class="input-group-addon btn-filtro btn-filtro-consulta search-button {{ isset($class) ? $class : '' }}" disabled tabindex="-1" {{ !empty($selecionado) ? 'style=display:none;' : '' }}><span class="fa fa-search"></span></button>
                    @else
                        <button type="button" class="input-group-addon btn-filtro btn-filtro-consulta search-button {{ isset($class) ? $class : '' }}" tabindex="-1" {{ !empty($selecionado) ? 'style=display:inline-block;' : '' }}><span class="fa fa-search"></span></button>
                        <button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button {{ isset($class) ? $class : '' }}" tabindex="-1" {{ !empty($selecionado) ? 'style=display:none;' : '' }}>><span class="fa fa-close"></span></button>
                    @endif
                @else
                    <button type="button" class="input-group-addon btn-filtro btn-filtro-consulta search-button {{ isset($class) ? $class : '' }}"   tabindex="-1" {{ !empty($selecionado) ? 'style=display:inline-block;' : '' }}><span class="fa fa-search"></span></button>
                    <button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button {{ isset($class) ? $class : '' }}" tabindex="-1" {{ !empty($selecionado) ? 'style=display:none;' : '' }}><span class="fa fa-close"></span></button>
                @endif
                
            </div>
            
			<div class="pesquisa-res-container lista-consulta-container ">
				<div class="pesquisa-res lista-consulta">
                    
                    {{--<span class="glyphicon glyphicon-pushpin"></span>--}}
				</div>
			</div>
            
            <div class="_consulta_imputs">
                @if (isset($campos_imputs))
                    @foreach ($campos_imputs as $imputs)	
                        
                        @php $model = isset($imputs[3]) ? 'ng-model=' . $imputs[3] . ' ng-update-hidden' : ''
                    
                        @if (count($imputs) == 2)
                            <input type="hidden" name="{{ $imputs[0] }}{{ $chave or '' }}" class="_consulta_imputs {{ $imputs[0] }}" objCampo="{{ $imputs[1] }}" {{ $model }} />
                        @else
                            <input type="hidden" name="{{ $imputs[0] }}{{ $chave or '' }}" class="_consulta_imputs {{ $imputs[0] }}" objCampo="{{ $imputs[1] }}" value="{{ $imputs[2] }}" {{ $model }} />
                        @endif
                    @endforeach
                @endif
				
				<input type="hidden" name="{{ isset($class_get_todos) ? $class_get_todos : '' }}" class="_consulta_imputs {{ isset($class_get_todos) ? $class_get_todos : '' }} _todos_selecionado" value="0"/>  
			</div>
            
            <div class="_consulta_parametros">
                <input type="hidden" class="_valida_fechar_lista" value="0"/>
                <input type="hidden" name="{{ isset($class_set_todos) ? $class_set_todos : '' }} " class="_consulta_imputs {{ isset($class_set_todos) ? $class_set_todos : '' }} _todos_selecionar" objcampo="noclear" value="0"/>  
           
                @if (!empty($selecionado)) 
                    <input type="hidden" class="_valor_selecionado_consulta" value="1"/>
                @else
                    <input type="hidden" class="_valor_selecionado_consulta"/>
                @endif
				
                <input type="hidden" class="_opcao_todos" value="{{ isset($opcao_todos) ? 'true' : 'false' }}"/>                
                <input type="hidden" class="_consulta_obj" value="{{ isset($obj_consulta) ? $obj_consulta : '0' }}"/>
                
                
                @if (isset($obj_ret)) 
                    @foreach ($obj_ret as $valor)
                        <input type="hidden" class="_consulta_ret" objCampo="{{ $valor }}"/>
                    @endforeach
                @endif
				
				@if (isset($recebe_todos)) 
                    @foreach ($recebe_todos as $valor)
                        <input type="hidden" class="_recebevalor_todos" objClass="{{ $valor }}" />
                    @endforeach
                @endif
                
                @if (isset($recebe_valor)) 
                    @foreach ($recebe_valor as $valor)
                        <input type="hidden" class="_consulta_recebevalor" objClass="{{ $valor[0] }}" objCampo="{{ $valor[1] }}"/>
                    @endforeach
                @endif
                
                @if (isset($campos_sql)) 
                    @foreach ($campos_sql as $valor)
                        <input type="hidden" class="_consulta_campos" value="{{ $valor }}"/>
                    @endforeach
                @endif
                
                @if (isset($filtro_sql)) 
                    @foreach ($filtro_sql as $valor)  

                        @if (count($valor)  > 1)
                            @php $model = isset($valor[2]) ? 'ng-model=' . $valor[2] .' ng-update-hidden' : ''
                            <input type="hidden" class="_consulta_filtro" value="{{ $valor[1] }}" objCampo="{{ $valor[0] }}" {{$model}} />
                        @else
                            <input type="hidden" class="_consulta_filtro" value="{{ $valor[0] }}"/>
                        @endif

                    @endforeach
                @endif
                
                @if (isset($campos_tabela)) 
                    @foreach ($campos_tabela as $valor)
                        <input type="hidden" class="_consulta_tabela" value="{{ $valor[0] }}" objTamanho="{{ $valor[1] }}" />
                    @endforeach
                @endif
                
                @if (isset($campos_titulo)) 
                    @foreach ($campos_titulo as $valor)
                        <input type="hidden" class="_consulta_titulo" value="{{ $valor }}"/>
                    @endforeach
                @endif
        
				
                    <input type="hidden" class="_consulta_validate" obj="{{ isset($class) ? $class : '' }}" value="1"/>
               
            </div>
            
		</div>
	</div>
</div>	

@section('script2')
	
	@if (!empty($validacao)) 
		<script>
			
			/* Validar de acordo com parâmetro definido.
			 * Ex.: Produto só é pesquisado após a escolha de um Estabelecimento. */
			function validar(valor) {
				$('._consulta_validate').val(valor);
			}

			function execValidar(){
				var v = {{$validacao}}();
				validar(v);
			}

		</script>

	@else
		<script> function execValidar(){} </script>

	@endif
	
@append

@if ( !isset($no_script) )

    @section('script') 	
        <script src="{{ elixir('assets/js/consulta.js') }}"></script>
    @append
	
@endif