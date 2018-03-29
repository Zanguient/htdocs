<fieldset>
    <table class="table table-striped table-bordered table-hover table-22040">
        <thead>
            <tr>
				<th></th>
                <th class="text-right densidade">{{ Lang::get($menu.'.densidade') }}</th>
                <th class="text-right espessura">{{ Lang::get($menu.'.espessura') }}</th>
                <th class="modelo">{{ Lang::get('master.modelo') }}</th>
                <th class="cor">{{ Lang::get('master.cor') }}</th>
				<th class="perfil">{{ Lang::get('master.perfil') }}</th>
				<th class="text-right tamanho">{{ Lang::get('master.tamanho') }}</th>
                <th class="text-right qtd-total">{{ Lang::get('master.qtd-abrev') }}</th>
				@if ( !empty($um_alternativa) )
					<th class="text-right qtd-alternativa">{{ Lang::get($menu.'.qtd_alternativa') }}</th>
				@endif

                @if ( isset($remessa[0]->QUANTIDADE_TALOES) )
				<th class="text-right qtd-taloes-total">{{ Lang::get($menu.'.qtd-taloes') }}</th>
                @endif
                
				<th class="text-right qtd-prog">{{ Lang::get($menu.'.qtd_a_prog') }}</th>
                                
                @if ( isset($remessa[0]->QUANTIDADE_TALOES) )
				<th class="text-right qtd-taloes" title="{{ Lang::get($menu.'.qtd-taloes-prog-desc') }}">{{ Lang::get($menu.'.qtd-taloes-prog') }}</th>
                @endif
                @if ( isset($remessa[0]->QUANTIDADE_TALOES) || trim($remessa[0]->TIPO) == 'PEDIDO' )
				<th class="text-right cota-talao">{{ Lang::get($menu.'.cota-talao') }}</th>
                @endif
				<th class="text-right tempo">{{ Lang::get('master.tempo') }}</th>
            </tr>
        </thead>
        <tbody>
			@php $i = 0
            @foreach ( $remessa as $rem )
            <tr data-item-consumo="{{ $i }}">
				<td><input type="radio" name="item-consumo" class="selec-item-consumo" /></td>
                <td class="text-right densidade">{{ $rem->DENSIDADE }}</td>
                <td class="text-right espessura">{{ number_format($rem->ESPESSURA, 2, ',', '.') }}</td>
                <td class="modelo" title="Id Prod.: {{ $rem->PRODUTO_ID }} - Id Mod.: {{ $rem->MODELO_ID }}">{{ $rem->MODELO_DESCRICAO }}
                
                    <span 
                        style="float:right"
                        ng-if="vm.gScope.DADOS.remessa == 'REP'" 
                        ng-click="vm.Reposicao.consultarOrigem({{ $rem->PRODUTO_ID }},{{ $rem->TAMANHO }})"
                        class="glyphicon glyphicon-info-sign" 
                        ttitle="Informações da Origem" 
                        ></span>

                </td>
                <td class="cor" title="{{ $rem->COR_DESCRICAO }}">{{ $rem->COR_DESCRICAO }}</td>
				<td class="perfil">
					<span>{{ $rem->PERFIL_SKU }} - {{ $rem->PERFIL_SKU_DESCRICAO }}</span>
					
					@php /*
					<input type="text" class="editar-perfil" value="{{ $rem->PERFIL_SKU }}" />
					<button type="button" class="btn btn-xs btn-primary btn-editar-perfil">
						<span class="glyphicon glyphicon-edit"></span>
					</button>
					<button type="button" class="btn btn-xs btn-success btn-confirmar-editar-perfil" disabled>
						<span class="glyphicon glyphicon-ok"></span>
					</button>
					<button type="button" class="btn btn-xs btn-danger btn-cancelar-editar-perfil" disabled>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</button>
					@php */
				</td>
                <td class="text-right tamanho" title="Id Tam.: {{ $rem->TAMANHO }}">{{ $rem->TAMANHO_DESCRICAO }}</td>
                <td class="text-right qtd-total">{{ number_format($rem->QUANTIDADE, 4, ',', '.') }}</td>
				@if ( !empty($um_alternativa) )
					<td class="text-right qtd-alternativa">{{ number_format($rem->QUANTIDADE_ALTERNATIVA, 4, ',', '.') }}</th>
				@endif

                @if ( isset($rem->QUANTIDADE_TALOES) )
				<td class="text-right qtd-taloes-total">{{ $rem->QUANTIDADE_TALOES }}</td>
                @endif
                
				<td class="text-right qtd-prog">
					<span>{{ number_format($rem->QUANTIDADE, 4, ',', '.') }}</span>
					
					@php /*
					<span>{{ number_format(round($rem->QUANTIDADE, 1), 1, ',', '.') }}</span>
					
					<input type="number" class="editar-qtd" min="1" max="{{ round($rem->QUANTIDADE, 1) }}" step="0.1" value="{{ round($rem->QUANTIDADE, 1) }}" />
					<button type="button" class="btn btn-xs btn-primary btn-editar-qtd">
						<span class="glyphicon glyphicon-edit"></span>
					</button>
					<button type="button" class="btn btn-xs btn-success btn-confirmar-editar-qtd" disabled>
						<span class="glyphicon glyphicon-ok"></span>
					</button>
					<button type="button" class="btn btn-xs btn-danger btn-cancelar-editar-qtd" disabled>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</button>
					@php */					
				</td>
                @if ( isset($rem->QUANTIDADE_TALOES) )
				<td class="text-right qtd-taloes">
					<span>{{ $rem->QUANTIDADE_TALOES }}</span>
					
					<input type="number" class="editar-qtd-taloes" min="0" max="{{ $rem->QUANTIDADE_TALOES }}" value="{{ $rem->QUANTIDADE_TALOES }}" />
					<button type="button" class="btn btn-xs btn-primary btn-editar-qtd-taloes">
						<span class="glyphicon glyphicon-edit"></span>
					</button>
					<button type="button" class="btn btn-xs btn-success btn-confirmar-editar-qtd-taloes" disabled>
						<span class="glyphicon glyphicon-ok"></span>
					</button>
					<button type="button" class="btn btn-xs btn-danger btn-cancelar-editar-qtd-taloes" disabled>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</button>
					
				</td>
                @endif
                
                @if ( isset($rem->QUANTIDADE_TALOES) )
                <td class="text-right cota-talao">{{ number_format($rem->FATOR_DIVISAO, 4, ',', '.') }}</td>
                @endif
                
                @if ( trim($rem->TIPO) == 'PEDIDO' )
				<td class="text-right cota-talao" field-js="alterar-input">
                    <span class="span">{{ round($rem->FATOR_DIVISAO_DETALHE,1) }}</span>
					
					<input type="text" data-input-hidden="._cota-detalhe" class="editar-qtd-cota input text-right" min="0" value="{{ round($rem->FATOR_DIVISAO_DETALHE,1) }}" style="display: none; width: 65px;     height: 21px;" />
                    <button 
                        type="button" 
                        class="btn btn-primary btn-xs btn-alterar" 
                        title="Alterar"
                    >
                        <span class="glyphicon glyphicon-edit"></span>
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-success btn-xs btn-confirm" 
                        title="Gravar" 
                        style="display: none;"
                    >
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-danger btn-xs btn-cancel" 
                        title="Cancelar" 
                        style="display: none;"
                    >
                        <span class="glyphicon glyphicon-ban-circle"></span>
                    </button>
                    
                </td>
                @endif
				<td class="text-right tempo"></td>

            {{-- Se o controle da remessa for acumulado, carrega todos os talões --}}
            @foreach ($consumos as $consumo)
            
                @if (
                   ($consumo->ID               == $rem->ID               &&
                    $consumo->PRODUTO_ID       == $rem->PRODUTO_ID       &&
                    $consumo->TAMANHO          == $rem->TAMANHO          &&
                    $consumo->DENSIDADE        == $rem->DENSIDADE        &&
                    $consumo->ESPESSURA        == $rem->ESPESSURA)       ||
                   ($rem->ID                   == ""                     &&
                    $consumo->PRODUTO_ID       == $rem->PRODUTO_ID       &&
                    $consumo->TAMANHO          == $rem->TAMANHO          &&
                    $consumo->DENSIDADE        == $rem->DENSIDADE        &&
                    $consumo->ESPESSURA        == $rem->ESPESSURA) 
                )
                    <input type="hidden" name="_consumo_id[]"	   class="_consumo-id   " value="{{ $consumo->ID }}"         />
                    <input type="hidden" name="_consumo_ref[]"	   class="_consumo-ref  " value=""                           />
                    <input type="hidden" name="_consumo_talao[]"   class="_consumo-talao" value=""                           />
                    <input type="hidden" name="_consumo_qtd[]"     class="_consumo-qtd  " value="{{ $consumo->QUANTIDADE }}" />
                @endif
            @endforeach
            
				<input type="hidden" name="_up[]"				class="_up"                                                   />
				<input type="hidden" name="_estacao[]"			class="_estacao"                                              />
				<input type="hidden" name="_remessa_talao_id[]"	class="_remessa-talao-id" value="{{ $rem->REMESSA_TALAO_ID }}"/>            
                <input type="hidden" name="_prod_id[]"			class="_prod-id"		  value="{{ $rem->PRODUTO_ID }}"      />
				<input type="hidden" name="_densidade[]"		class="_densidade"		  value="{{ $rem->DENSIDADE }}"       />
				<input type="hidden" name="_espessura[]"		class="_espessura"		  value="{{ $rem->ESPESSURA }}"       />
				<input type="hidden" name="_modelo[]"			class="_modelo-id"		  value="{{ $rem->MODELO_ID }}"       />
				<input type="hidden" name="_cor[]"				class="_cor-id"			  value="{{ $rem->COR_ID }}"          />
				<input type="hidden" name="_perfil_sku[]"		class="_perfil-sku"		  value="{{ trim($rem->PERFIL_SKU) }}"/>
				<input type="hidden" name="_tamanho[]"			class="_tamanho" 		  value="{{ $rem->TAMANHO }}"         />
				<input type="hidden" name="_um[]"				class="_um"				  value="{{ $rem->UM }}"              />
				<input type="hidden" name="_localizacao[]"		class="_localizacao"	  value="{{ $rem->LOCALIZACAO_ID }}"  />
				<input type="hidden" name="_qtd_total[]"		class="_qtd-total"		  value="{{ $rem->QUANTIDADE }}"      />    
				<input type="hidden" name="_qtd_prog[]"			class="_qtd-prog"		  value="{{ $rem->QUANTIDADE }}"      />    
				<input type="hidden" name="_qtd_alternativa[]"	class="_qtd-alternativa"  value="{{ $rem->QUANTIDADE_ALTERNATIVA }}" />    
				@if ( isset($rem->QUANTIDADE_TALOES) )
                <input type="hidden" name="_qtd_taloes-total[]"	class="_qtd-taloes-total" value="{{ $rem->QUANTIDADE_TALOES }}" />    
				<input type="hidden" name="_qtd_taloes[]"		class="_qtd-taloes"		  value="{{ $rem->QUANTIDADE_TALOES }}" />    
				<input type="hidden" name="_qtd-taloes-max[]"	class="_qtd-taloes-max"	  value="{{ $rem->QUANTIDADE_TALOES }}" />    
                @endif
                
				@if ( trim($rem->TIPO) == 'PEDIDO' && isset($rem->CLIENTE_ID) )
                <input type="hidden" name="_cliente-id[]"	class="_cliente-id" value="{{ $rem->CLIENTE_ID }}" />        
                @endif
				<input type="hidden" name="_fator_divisao[]"	class="_fator-divisao _qtd-cota"	  value="{{ $rem->FATOR_DIVISAO }}"   />
                <input type="hidden" name="_cota_detalhe[]"	    class="_cota-detalhe"                 value="{{ $rem->FATOR_DIVISAO_DETALHE }}"   />
                <input type="hidden" name="_controle_seq[]"     class="_controle-seq"                 value="{{ $rem->CONTROLE }}"        />
				<input type="hidden" name="_bloco_divisor[]"	class="_bloco-divisor"                                        />
				<input type="hidden" name="_talao_divisor[]"	class="_talao-divisor"                                        />

            </tr>
			@php $i++
            @endforeach		
        </tbody>
    </table>
</fieldset>
<fieldset>
    <legend>{{ Lang::get('master.ups') }}</legend>
    <div class="up-container">
		
		{{-- Esse bloco de campos hidden precisa estar aqui para ser levado ao popup --}}
		<input type="hidden" name="_remessa"	   class="_remessa" />
		<input type="hidden" name="_requisicao"	   class="_requisicao" />
		<input type="hidden" name="_estab"		   class="_estab" />
		<input type="hidden" name="_gp"			   class="_gp" />
		<input type="hidden" name="_data_producao" class="_data-producao" />
		<input type="hidden" name="_perfil"		   class="_perfil" />
		<input type="hidden" name="_familia"	   class="_familia" />
	
        <div class="up-bloco dist-auto" data-up="999999" style="display: none;">
            <label>{{ Lang::get('master.up') }}: Todas</label>
            <div class="estacao-container">
                <div class="estacao-bloco dist-auto" data-estacao="9999">
                    <div class="acoes-ordenar-estacao">
                        <button type="button" class="btn btn-xs btn-default btn-subir" title="{{ Lang::get($menu.'.subir-title') }}" disabled>
                            <span class="glyphicon glyphicon-chevron-up"></span>
                        </button>
                        <button type="button" class="btn btn-xs btn-default btn-descer" title="{{ Lang::get($menu.'.descer-title') }}" disabled>
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </div>

                    <div class="estacao-header-text" >
                        <label>{{ Lang::get('master.estacao') }}: 
                            <span>tESTE</span>
                        </label>

                        <label class="estacao-perfil">{{ Lang::get('master.perfil') }}: 
                            <span>J,K,L,M,N,O,P,Q,R,S,T</span>
                        </label>
                    </div>

                    <div class="acoes-estacao">
                        <button type="button" class="btn btn-xs btn-primary btn-incluir-consumo btn-incluir-auto" title="{{ Lang::get($menu.'.incluir-title') }}" data-perfil="J,K,L,M,N,O,P,Q,R,S,T" disabled>
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        <button type="button" class="btn btn-xs btn-danger btn-excluir-consumo btn-excluir-auto" title="{{ Lang::get($menu.'.excluir-title') }}" disabled>
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </div>
                    <table class="table table-striped table-bordered table-hover estacao" data-estacao="{{$i}}">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-right densidade">{{ Lang::get($menu.'.densidade-abrev') }}</th>
                                <th class="text-right espessura">{{ Lang::get($menu.'.espessura-abrev') }}</th>
                                <th class="modelo">{{ Lang::get('master.modelo') }}</th>
                                <th class="cor">{{ Lang::get('master.cor') }}</th>
                                <th class="text-right tamanho">{{ Lang::get('master.tamanho-abrev') }}</th>
                                @if ( !empty($um_alternativa) )
                                    <th class="text-right qtd-alternativa">{{ Lang::get($menu.'.qtd_alternativa') }}</th>
                                @endif
                                <th class="text-right qtd-prog">{{ Lang::get($menu.'.qtd_prog') }}</th>
                                <th class="text-right tempo">{{ Lang::get('master.tempo') }}</th>
                            </tr>
                        </thead>
                        <tbody class="taloes-automatico">

                        </tbody>
                    </table>
                    <input type="hidden" class="_qtd-restante" value="0" />
                    <input type="hidden" class="_bloco-ultimo" value="0" />
                    <input type="hidden" class="_densidade-ultimo" value="0" />
                    <input type="hidden" class="_espessura-ultimo" value="0" />
                    <input type="hidden" class="_modelo-ultimo" value="0" />
                    <input type="hidden" class="_tamanho-ultimo" value="@" />
                    <input type="hidden" class="_cor-ultimo" value="@" />
                    <input type="hidden" class="_perfil-ultimo" value="" />
                </div>
            </div>
        </div>

    @foreach ( $ups as $up )
		<div class="up-bloco" data-up="{{ $up->ID }}">
			<label>{{ Lang::get('master.up') }}: {{ $up->ID }} - {{ $up->DESCRICAO }}</label>
			<div class="estacao-container">
			@php $i = 0
			@foreach ( $estacoes as $estacao )
				@if ( $estacao->UP_ID == $up->ID )
				<div class="estacao-bloco" data-estacao="{{ $estacao->ESTACAO }}" data-perfil="{{ trim($estacao->PERFIL_SKU) }}" data-perfil-auto="{{ $estacao->PERFIL_SKU_AUTO }}">
					<div class="acoes-ordenar-estacao">
						<button type="button" class="btn btn-xs btn-default btn-subir" title="{{ Lang::get($menu.'.subir-title') }}" disabled>
							<span class="glyphicon glyphicon-chevron-up"></span>
						</button>
						<button type="button" class="btn btn-xs btn-default btn-descer" title="{{ Lang::get($menu.'.descer-title') }}" disabled>
							<span class="glyphicon glyphicon-chevron-down"></span>
						</button>
					</div>
					
					<div class="estacao-header-text" title="{{ Lang::get('master.estacao') }}: {{ $estacao->ESTACAO }} - {{ $estacao->ESTACAO_DESCRICAO }} {{ Lang::get('master.perfil') }}: {{ $estacao->PERFIL_SKU }}">
						<label>{{ Lang::get('master.estacao') }}: 
							<span>{{ $estacao->ESTACAO }} - {{ $estacao->ESTACAO_DESCRICAO }}</span>
						</label>

						<label class="estacao-perfil">{{ Lang::get('master.perfil') }}: 
							<span>{!! $estacao->PERFIL_SKU_HTML !!}</span>
						</label>
					</div>
					
					<div class="acoes-estacao">
						<button type="button" class="btn btn-xs btn-primary btn-incluir-consumo" title="{{ Lang::get($menu.'.incluir-title') }}" data-perfil="{{ trim($estacao->PERFIL_SKU) }}" data-perfil-auto="{{ $estacao->PERFIL_SKU_AUTO }}" disabled>
							<span class="glyphicon glyphicon-plus"></span>
						</button>
						<button type="button" class="btn btn-xs btn-danger btn-excluir-consumo" title="{{ Lang::get($menu.'.excluir-title') }}" disabled>
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
					<table class="table table-striped table-bordered table-hover estacao" data-estacao="{{$i}}">
						<thead>
							<tr>
								<th></th>
								<th class="text-right densidade">{{ Lang::get($menu.'.densidade-abrev') }}</th>
								<th class="text-right espessura">{{ Lang::get($menu.'.espessura-abrev') }}</th>
								<th class="modelo">{{ Lang::get('master.modelo') }}</th>
								<th class="cor">{{ Lang::get('master.cor') }}</th>
								<th class="text-right tamanho">{{ Lang::get('master.tamanho-abrev') }}</th>
								@if ( !empty($um_alternativa) )
									<th class="text-right qtd-alternativa">{{ Lang::get($menu.'.qtd_alternativa') }}</th>
								@endif
								<th class="text-right qtd-prog">{{ Lang::get($menu.'.qtd_prog') }}</th>
								<th class="text-right tempo">{{ Lang::get('master.tempo') }}</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<input type="hidden" class="_qtd-restante" value="0" />
					<input type="hidden" class="_bloco-ultimo" value="0" />
					<input type="hidden" class="_densidade-ultimo" value="0" />
					<input type="hidden" class="_espessura-ultimo" value="0" />
					<input type="hidden" class="_modelo-ultimo" value="0" />
					<input type="hidden" class="_perfil-ultimo" value="" />
				</div>
				@endif
			@php $i++
			@endforeach
			</div>
		</div>
    @endforeach
    </div>
</fieldset>