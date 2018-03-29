<div class="consulta-container">
	<div class="consulta">
		<div class="form-group">
			
            <label for="consulta-descricao">{{ isset($label_descricao) ? $label_descricao : '0' }}</label>
			<div class="input-group  {{ isset($class2) ? $class2 : '' }}">
				<input type="search" name="consulta_descricao" id="consulta-descricao" class="form-control {{ isset($class1) ? $class1 : '' }} objConsulta" autocomplete="off" autofocus required value="{{ isset($valor) ? $valor : ''}}"/>            
				<button type="button" class="input-group-addon btn-filtro btn-filtro-consulta search-button {{ isset($class) ? $class : '' }}"><span class="fa fa-search"></span></button>
				<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button {{ isset($class) ? $class : '' }}"><span class="fa fa-close"></span></button>
			</div>
            
			<div class="pesquisa-res-container lista-consulta-container ">
				<div class="pesquisa-res lista-consulta">
                    
                    {{--<span class="glyphicon glyphicon-pushpin"></span>--}}
				</div>
			</div>
            
            <div class="_consulta_imputs">
                @if (isset($campos_imputs))
                    @foreach ($campos_imputs as $imputs)
                        @if (count($imputs) == 2)
                            <input type="hidden" class="_consulta_imputs {{ $imputs[0] }}" objCampo="{{ $imputs[1] }}"/>
                        @else
                            <input type="hidden" class="_consulta_imputs {{ $imputs[0] }}" objCampo="{{ $imputs[1] }}" value="{{ $imputs[2] }}"/>
                        @endif
                    @endforeach
                @endif
            </div>
            
            <div class="_consulta_parametros">
                <input type="hidden" class="_valida_fechar_lista" value="0"/>
                
                @if (isset($selecionado)) 
                    <input type="hidden" class="_valor_selecionado_consulta" value="1"/>
                @else
                    <input type="hidden" class="_valor_selecionado_consulta"/>
                @endif
                
                <input type="hidden" class="_consulta_limitar" value="{{ isset($limitar_registros) ? $limitar_registros : '0' }}"/>
                <input type="hidden" class="_consulta_obj" value="{{ isset($obj_consulta) ? $obj_consulta : '0' }}"/>
                
                
                @if (isset($obj_ret)) 
                    @foreach ($obj_ret as $valor)
                        <input type="hidden" class="_consulta_ret" objCampo="{{ $valor }}"/>
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
                        <input type="hidden" class="_consulta_filtro" value="{{ $valor }}"/>
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
                
                @if (isset($tabs)) 
                    @foreach ($tabs as $valor)
                        <input type="hidden" class="_consulta_tab" value="{{ $valor }}"/>
                    @endforeach
                @endif
                
                @if (isset($tabs_consulta)) 
                    @foreach ($tabs_consulta as $valor)
                        <input type="hidden" class="_consulta_tab_consulta" value="{{ $valor }}"/>
                    @endforeach
                @endif
                
            </div>
            
		</div>
	</div>
</div>	