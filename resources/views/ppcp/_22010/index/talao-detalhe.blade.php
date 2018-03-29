	<table class="table table-striped table-bordered table-hover table-detalhe">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th class="text-center">{{ Lang::get('master.talao') }}</th>
                <th class="text-center" title="Código do produto">Cód. Prod.</th>
                <th class="cor">{{ Lang::get('master.cor') }}</th>
                <th class="text-right qtd-projetada">{{ Lang::get('master.qtd-proj-abrev') }}</th>
                <th class="text-right qtd-projetada-alternativa" title="{{ Lang::get('master.qtd-proj-altern') }}">{{ Lang::get('master.qtd-proj-altern-abrev') }}</th>
                <th class="text-right qtd">{{ Lang::get('master.qtd-abrev') }}</th>
                <th class="text-right qtd-alternativa">{{ Lang::get($menu.'.qtd-alternativa-abrev') }}</th>
                <th class="text-center sobra-tipo" title="{{ Lang::get($menu.'.tipo-sobra') }}">{{ Lang::get($menu.'.tipo-sobra-abrev') }}</th>
                <th class="text-right ">{{ Lang::get('master.qtd-sobra') }}</th>
                <th class="text-right " title="{{ Lang::get($menu.'.qtd-aproveitamento-title') }}">{{ Lang::get($menu.'.qtd-aproveitamento') }}</th>
				<th class="text-right">{{ Lang::get('master.saldo') }}</th>
				<th class="text-right" title="{{ Lang::get('master.saldo-altern') }}">{{ Lang::get('master.saldo-altern-abrev') }}</th>
            </tr>
        </thead>
        <tbody>
			@if ( isset( $taloes_detalhe ) )
				@foreach ( $taloes_detalhe as $talao_detalhe )  
					<tr>
						<td><input type="radio" class="radio-detalhe" /></td>
						<td class="t-status text-center status{{ $talao_detalhe->STATUS }}" title="{{ $talao_detalhe->STATUS_DESCRICAO }}"></td>
						<td class="text-center">{{ $talao_detalhe->ID }}</td>
						<td class="text-center">{{ $talao_detalhe->PRODUTO_ID }}</td>
						<td class="cor cor-amostra">
                            @php $cor_1 = tcolorToRgb($talao_detalhe->COR_AMOSTRA);
                            @php $cor_2 = tcolorToRgb($talao_detalhe->COR_AMOSTRA2);
                            
                            @if ( $cor_1 != '' )
                            <span style="background-image: linear-gradient(to right top, {{ $cor_1 }} 45% , {{ $cor_2 }} 55%);"></span>
                            @else
                            <span style="box-shadow: none;"></span>
                            @endif
                            <span class="descricao ng-binding">
                                {{ $talao_detalhe->COR_ID }} - {{ $talao_detalhe->COR_DESCRICAO }}
                            </span>
                        
                            
                        </td>
                        <td class="text-right qtd-projetada">{{ number_format($talao_detalhe->QUANTIDADE, 4, ',', '.') }} {{ $talao_detalhe->UM }}</td>
                        <td class="text-right qtd-projetada-alternativa">{{ number_format($talao_detalhe->QUANTIDADE_ALTERN, 4, ',', '.') }} {{ $talao_detalhe->UM_ALTERNATIVA }}</td>
						<td class="text-right qtd">
						
							{{-- Se o talao estiver finalizado --}}
							@if ( $talao_detalhe->STATUS === '3' )
								@php $qtd_prod			= $talao_detalhe->QUANTIDADE_PRODUCAO
								@php $qtd_altern_prod	= $talao_detalhe->QUANTIDADE_ALTERN_PRODUCAO
								@php $sobra				= $talao_detalhe->QUANTIDADE_SOBRA
							@else
								@php $qtd_prod			= $talao_detalhe->QUANTIDADE_PRODUCAO_TMP
								@php $qtd_altern_prod	= $talao_detalhe->QUANTIDADE_ALTERN_PRODUCAO_TMP
								@php $sobra				= $talao_detalhe->QUANTIDADE_SOBRA_TMP
							@endif
							
							{{-- Saldo = projetado - produzido - aproveitamento --}}
							@php $saldo			= $talao_detalhe->QUANTIDADE - $qtd_prod - $talao_detalhe->APROVEITAMENTO_ALOCADO
							@php $saldo_altern	= $talao_detalhe->QUANTIDADE_ALTERN - $qtd_altern_prod - $talao_detalhe->APROVEITAMENTO_ALOCADO_ALTERN

							<span class="valor">{{ number_format($qtd_prod, 4, ',', '.') }}</span>
							<span>{{ $talao_detalhe->UM }}</span>

							<input type="number" name="quantidade" class="qtd" 
								   min="0.0001" max="{{ $qtd_prod }}" step="0.0001"
								   value="{{ number_format($qtd_prod, 4) }}" 
							/>						

							@if ( !empty($talao_detalhe->UM_ALTERNATIVA) )
								<button type="button" class="btn btn-sm btn-warning btn-balanca" title="Coletar Peso" data-toggle="modal" data-target="#modal-registrar-balanca" disabled><span class="glyphicon glyphicon-scale"></span></button>
							@else
								<button type="button" class="btn btn-sm btn-primary qtd-editar" disabled><span class="glyphicon glyphicon-edit"></span></button>
								<button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>
								<button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>
							@endif
							
						</td>
						<td class="text-right qtd-alternativa">
							
							<span class="valor">{{ number_format($qtd_altern_prod, 4, ',', '.') }}</span>
							<span>{{ $talao_detalhe->UM_ALTERNATIVA }}</span>

							<input type="number" name="quantidade_alternativa" class="qtd-alternativa" 
								   min="0.0001" max="{{ $qtd_altern_prod }}" step="0.0001"
								   value="{{ $qtd_altern_prod }}" 
							/>						

							<button type="button" class="btn btn-sm btn-primary qtd-editar"	 title="Editar"	 disabled><span class="glyphicon glyphicon-edit"></span></button>
							<button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>
							<button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>
						</td>
                        
                        <td class="text-center sobra-tipo sobra-tipo-{{$talao_detalhe->SOBRA_TIPO}}" title="{{ $talao_detalhe->SOBRA_TIPO_DESCRICAO }}">
							<span>{{ $talao_detalhe->SOBRA_TIPO }}</span>
						</td>
                        <td class="text-right sobra-prod">{{ number_format($sobra, 4, ',', '.') }}</td>                        
						<td class="text-right aproveitamento">{{ number_format($talao_detalhe->APROVEITAMENTO_ALOCADO, 4, ',', '.') }}
                        
                            @if ( $talao_detalhe->APROVEITAMENTOITENS != '' )
                                <span
                                    class="glyphicon glyphicon-alert danger float-right alocado-show" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    title="Itens do Aproveitamento" 
                                    data-content="
                                    <div class='alocado-content'>
                                    @php $itens = explode ('#@@#', $talao_detalhe->APROVEITAMENTOITENS);
                                    @foreach ( $itens as $iten )
                                        <div class='alocado-row'>
                                            <span>
                                                {{-- Quebra os itens --}}
                                                @php $prod = explode ('#@#', $iten);
                                                @php $cont = 0;
                                                @php $iditem = 0;
                                                
                                                {{-- Monta os itens --}}
                                                @foreach ( $prod as $pro )
                                                @php $cont++;    
                                                    {{-- Produto descricao --}}
                                                    @if ($cont == 1)
                                                        {{$pro}}
                                                    @endif
                                                    {{-- ID do vinculo --}}
                                                    @if ($cont == 2)
                                                        @php $iditem = $pro;
                                                    @endif
                                                    {{-- Qunatidade --}}
                                                    @if ($cont == 3)
                                                        {{' ('.number_format($pro, 4, ',', '.').')'}}
                                                    @endif
                                                    {{-- Botao de excluir --}}
                                                    @if ( ($pro == 0) and ($cont == 4) and ($iditem > 0) )
                                                        <button type='button' class='btn btn-danger btn-xs aproveitado-excluir' title='Excluir item' data-talao-vinculo-id='{{ $iditem }}' disabled>
                                                            <span class='glyphicon glyphicon-trash'></span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    @endforeach
                                    </div>
                                    ">
                                </span>
                            @endif
                        </td>
												
						<td class="text-right saldo">{{ number_format($saldo, 4, ',', '.') }}</td>
						<td class="text-right saldo-altern">{{ number_format($saldo_altern, 4, ',', '.') }}</td>
                        
						<input type="hidden" name="_REMESSA_ID"							class="_remessa-id"							value="{{ $talao_detalhe->REMESSA_ID }}" />
						<input type="hidden" name="_REMESSA_TALAO_ID"					class="_remessa-talao-id"					value="{{ $talao_detalhe->REMESSA_TALAO_ID }}" />
                        <input type="hidden" name="_talao_id"							class="_talao-id"							value="{{ $talao_detalhe->ID }}" />
						<input type="hidden" name="_produto_id"							class="_produto-id"							value="{{ $talao_detalhe->PRODUTO_ID }}" />
						<input type="hidden" name="_quantidade"							class="_quantidade"							value="{{ $qtd_prod }}" />
						<input type="hidden" name="_quantidade_alternativa"				class="_quantidade-alternativa"				value="{{ $qtd_altern_prod }}" />
                        <input type="hidden" name="_quantidade_projetada"				class="_quantidade-projetada"				value="{{ $talao_detalhe->QUANTIDADE }}"/>
                        <input type="hidden" name="_quantidade_projetada_altern"		class="_quantidade-projetada-altern"		value="{{ $talao_detalhe->QUANTIDADE_ALTERN }}"/>
                        <input type="hidden" name="_quantidade_projetada_um"			class="_quantidade-projetada-um"			value="{{ $talao_detalhe->UM }}"/>
                        <input type="hidden" name="_quantidade_aproveitamento"			class="_quantidade-aproveitamento"			value="{{ $talao_detalhe->APROVEITAMENTO_ALOCADO }}"/>
                        <input type="hidden" name="_quantidade_aproveitamento_altern"	class="_quantidade-aproveitamento-altern"	value="{{ $talao_detalhe->APROVEITAMENTO_ALOCADO_ALTERN }}"/>
                        <input type="hidden" name="_saldo_produzir"						class="_saldo-produzir"						value="{{ $saldo }}"/>
                        <input type="hidden" name="_saldo_produzir_altern"				class="_saldo-produzir-altern"				value="{{ $saldo_altern }}"/>
                        <input type="hidden" name="_um"									class="_um"									value="{{ $talao_detalhe->UM }}"/>
                        <input type="hidden" name="_um_alternativa"						class="_um-alternativa"						value="{{ $talao_detalhe->UM_ALTERNATIVA }}"/>
                        <input type="hidden" name="_tolerancia_max"						class="_tolerancia-max"						value="{{ $talao_detalhe->TOLERANCIAM }}"/>
                        <input type="hidden" name="_tolerancia_min"						class="_tolerancia-min"                     value="{{ $talao_detalhe->TOLERANCIAN }}"/>
                        <input type="hidden" name="_tolerancia_tip"						class="_tolerancia-tip"                     value="{{ $talao_detalhe->TOLERANCIA_TIPO }}"/>
                        <input type="hidden" name="_sobra_tipo"						    class="_sobra-tipo"                         value="{{ $talao_detalhe->SOBRA_TIPO }}"/>
                        <input type="hidden" name="_peca_conjunto"					    class="_peca-conjunto"                      value="{{ $talao_detalhe->PECA_CONJUNTO }}"/>
                         
					</tr>
				@endforeach
			@endif
        </tbody>
	</table>