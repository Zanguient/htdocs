{{-- Variável para identificar se é componente  --}}
@php $componente = '0';
			
<fieldset class="materia-prima">
		
	<legend>{{ Lang::get($menu.'.consumo') }}</legend>
	
	<div class="botao-container">
		@if ( isset($p209) && $p209 === '1' )
		<button type="button" id="registrar-materia-prima"	class="btn btn-sm btn-warning" data-hotkey="Alt+M" data-toggle="modal" data-target="#modal-registrar-materia" disabled>
			<span class="glyphicon glyphicon-edit"></span>
			{{ Lang::get($menu.'.registrar-materia-prima') }}
		</button>
		@endif
		@if ( isset($p210) && $p210 === '1' )
		<button type="button" id="registrar-componente"		class="btn btn-sm btn-warning" data-hotkey="Alt+C" data-toggle="modal" data-target="#modal-registrar-componente" disabled>
			<span class="glyphicon glyphicon-edit"></span>
			{{ Lang::get($menu.'.registrar-componente') }}
		</button>
		@endif
	</div>
	
	<table class="table table-striped table-bordered table-hover materia-prima">
		<thead>
            <tr>
				<th></th>
				<th></th>
                <th title="Id do Consumo">Id. Cons.</th>
                <th>{{ Lang::get('master.produto') }}</th>
				<th class="text-right">{{ Lang::get('master.tamanho-abrev') }}</th>
                <th class="text-right" title="Quantidade projetada à consumir na unidade de medida padrão do produto.">{{ Lang::get('master.qtd-abrev') }}</th>
                <th class="text-right" title="Quantidade projetada à consumir na unidade de medida alternativa do produto.">{{ Lang::get($menu.'.qtd-alternativa-abrev') }}</th>
                <th class="text-right qtd-alocada" title="Quantidade alocada para consumir na unidade de medida padrão do produto.">{{ Lang::get($menu.'.qtd-alocada-abrev') }}</th>
                <th class="text-right qtd-alternativa-aloc" title="Quantidade alocada para consumir na unidade de medida alternativa do produto.">{{ Lang::get($menu.'.qtd-alternativa-alocada-abrev') }}</th>
                <th class="text-right" title="Sobra de matéria-prima na unidade de medida padrão do produto">{{ Lang::get($menu.'.sobra') }}</th>
            </tr>
		</thead>
		<tbody>
			
			@if ( isset( $taloes_materia_prima ) )
				@foreach ( $taloes_materia_prima as $materia_prima )
				
					@php $componente = trim($materia_prima->COMPONENTE);
				<tr data-componente="{{ $componente }}" consumo-id="{{ $materia_prima->CONSUMO_ID }}">
					<td><input type="radio" class="radio-materia" /></td>
					@if ( $componente == '1' )
					{{-- <td class="t-status status-componente-{{ $materia_prima->STATUS_COMPONENTE }}" title="{{ $materia_prima->STATUS_COMPONENTE_DESCRICAO }}"></td> --}}
					<td class="t-status status-componente-reduzido-{{ $materia_prima->STATUS_COMPONENTE_REDUZIDO }}" title="{{ $materia_prima->STATUS_COMPONENTE_REDUZIDO_DESC }}"></td>
					@else
					<td class="t-status status-materia-prima-{{ $materia_prima->STATUS_MATERIA_PRIMA }}" title="{{ $materia_prima->STATUS_MATERIA_PRIMA_DESCRICAO }}"></td>
					@endif
                    <td class="id-consumo" >{{ $materia_prima->CONSUMO_ID }}</td>
					<td class="produto" title="{{ $materia_prima->PRODUTO_ID }} - {{ $materia_prima->PRODUTO_DESCRICAO }}">{{ $materia_prima->PRODUTO_ID }} - {{ $materia_prima->PRODUTO_DESCRICAO }}
                        @if ( $materia_prima->QUANTIDADE_ALOCADA > 0 )
                            <span
                                class="glyphicon glyphicon-alert danger float-right alocado-show" 
                                data-toggle="popover" 
                                data-placement="top" 
                                title="Itens Alocados" 
                                data-content="
                                <div class='alocado-content'>
                                @foreach ( $taloes_vinculo as $talao_vinculo )
                                        @php $tipo = trim($talao_vinculo->TIPO)
                                        
                                        @if ( $talao_vinculo->CONSUMO_ID == $materia_prima->CONSUMO_ID )
                                            <div class='alocado-row'>
												<span>
                                                <b>{{ $talao_vinculo->TABELA_ID . '/' . $tipo }}</b>
                                                ({{ number_format($talao_vinculo->QUANTIDADE, 2, ',', '.') }} {{ $talao_vinculo->UM }}
                                                 {{ ( $talao_vinculo->QUANTIDADE_ALTERNATIVA > 0 ) ? ' / ' . number_format($talao_vinculo->QUANTIDADE_ALTERNATIVA, 2, ',', '.') . ' ' . $talao_vinculo->UM_ALTERNATIVA : '' }})
												{{ empty($talao_vinculo->OB) ? '' : 'OB: '.$talao_vinculo->OB }}
												</span>

                                                @if ( $talao_vinculo->STATUS == 0 )
                                                    <button type='button' class='btn btn-danger btn-xs alocado-excluir' title='Excluir item alocado' data-talao-vinculo-id='{{ $talao_vinculo->ID }}' disabled>
                                                        <span class='glyphicon glyphicon-trash'></span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                @endforeach
                                </div>
                                ">
                            </span>
                    	@endif
                    </td>
					<td class="text-right tamanho"	>{{ $materia_prima->TAMANHO_DESCRICAO }}</td>
					<td class="text-right qtd-total">
						@if ( $status_talao == '0' && $ver_peca_disponivel == '1' && $componente == '0' && $materia_prima->STATUS_MATERIA_PRIMA > 0 )
                            <span
                                class="glyphicon glyphicon-info-sign pecas-disponiveis" 
                                data-toggle="popover" 
                                data-placement="top" 
                                title="Peças disponíveis" 
                                data-content="
                                <div class='pecas-disponiveis-container'>
									<table class='table table-striped table-bordered'>
										<thead>
											<tr>
												<th class='text-right'>Id</th>
												<th class='text-right'>Remessa</th>
												<th class='text-right'>Talão</th>
												<th class='text-right'>Talão Det.</th>
												<th class='text-right'>Qtd.</th>
											</tr>
										</thead>
										<tbody>
										@foreach ( $pecas_disponiveis as $peca )
											@foreach ( $peca as $p )											
												@if ( ($p->PRODUTO_ID == $materia_prima->PRODUTO_ID) )

												<tr>
													<td class='text-right'>{{ $p->REFERENCIA_ID or '-' }}</td>
													<td class='text-right remessa'>{{ $p->REMESSA or '-' }}</td>
													<td class='text-right'>{{ $p->REMESSA_TALAO_ID or '-' }}</td>
													<td class='text-right'>{{ $p->REMESSA_TALAO_DETALHE_ID or '-' }}</td>
													<td class='text-right'>{{ number_format($p->SALDO, 4, ',', '.') }} {{ $materia_prima->UM }}</td>
												</tr>

												@endif
											@endforeach
										@endforeach
										</tbody>
                                    </table>
                                </div>
                                ">
                            </span>
                    	@endif
						{{ number_format($materia_prima->QUANTIDADE, 4, ',', '.') }} {{ $materia_prima->UM }}
					</td>
					<td class="text-right qtd-alternativa">{{ number_format($materia_prima->QUANTIDADE_ALTERNATIVA, 4, ',', '.') }} {{ $materia_prima->UM_ALTERNATIVA }}</td>
					<td class="text-right qtd-alocada">
						<span class="valor">{{ number_format($materia_prima->QUANTIDADE_ALOCADA, 4, ',', '.') }}</span>
						<span class="um"> {{ $materia_prima->UM }}</span>
						
						<input type="number" name="quantidade_alocada" class="qtd-alocada" 
							   min="0.0001" max="{{ number_format($materia_prima->QUANTIDADE_ALOCADA, 4) }}" step="0.0001"
							   value="{{ number_format($materia_prima->QUANTIDADE_ALOCADA, 4) }}" 
						/>
						
						<button type="button" class="btn btn-sm btn-primary qtd-editar"	 title="Editar"	 disabled><span class="glyphicon glyphicon-edit"></span></button>
						<button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>
						<button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>
					</td>
					<td class="text-right qtd-alternativa-aloc">
						<span class="valor">{{ number_format($materia_prima->QUANTIDADE_ALTERNATIVA_ALOCADA, 4, ',', '.') }}</span>
						<span class="um"> {{ $materia_prima->UM_ALTERNATIVA_ALOCADA }}</span>
						
						<input type="number" name="quantidade_alternativa_aloc" class="qtd-alternativa-aloc" 
							   min="0.0001" max="{{ number_format($materia_prima->QUANTIDADE_ALTERNATIVA_ALOCADA, 4) }}" step="0.0001"
							   value="{{ number_format($materia_prima->QUANTIDADE_ALTERNATIVA_ALOCADA, 4) }}" 
						/>
						
						<button type="button" class="btn btn-sm btn-primary qtd-editar"	 title="Editar"	 disabled><span class="glyphicon glyphicon-edit"></span></button>
						<button type="button" class="btn btn-sm btn-success qtd-gravar"	 title="Gravar"	 ><span class="glyphicon glyphicon-ok"></span></button>
						<button type="button" class="btn btn-sm btn-danger qtd-cancelar" title="Cancelar"><span class="glyphicon glyphicon-ban-circle"></span></button>
					</td>
					<td class="text-right">
						{{ ($materia_prima->QUANTIDADE_SOBRA < 0) ? '0,0000' : number_format($materia_prima->QUANTIDADE_SOBRA, 4, ',', '.') }}
					</td>
                    
                    <input type="hidden" name="_sobra_material" class="_sobra-material" value="{{ $materia_prima->QUANTIDADE_SOBRA }}" />						
                    <input type="hidden" name="_produto_id"	class="_produto-id"	value="{{ $materia_prima->PRODUTO_ID }}"	/>
                    <input type="hidden" name="_talao_detalhe_id"	class="_talao-detalhe-id"	value="{{ $materia_prima->REMESSA_TALAO_DETALHE_ID }}"	/>
                    <input type="hidden" name="_consumo_id"	class="_consumo-id"	value="{{ $materia_prima->CONSUMO_ID }}"	/>
                    <input type="hidden" class="_tamanho" value="{{ $materia_prima->TAMANHO_DESCRICAO }}" />
                    <input type="hidden" class="_tamanho_id" value="{{ $materia_prima->TAMANHO }}" />
                    <input type="hidden" class="_quantidade-total" value="{{ $materia_prima->QUANTIDADE }}" />
					<input type="hidden" name="_quantidade-alocada" class="_quantidade-alocada" value="{{ $materia_prima->QUANTIDADE_ALOCADA }}" />
					<input type="hidden" name="_quantidade-alternativa-aloc" class="_quantidade-alternativa-aloc" value="{{ $materia_prima->QUANTIDADE_ALTERNATIVA_ALOCADA }}" />
				</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
	@php /*
	@if ( $componente == '1' )
	<ul class="legenda status-componente">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-parado') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-ini-par') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-andamento') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-finalizado') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-encerrado') }}</div>
		</li>
	</ul>
	@else
	@php */
	<ul class="legenda status-materia-prima">
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-sem-estoque') }}</div>
		</li>
		<li>
			<div class="cor-legenda"></div>
			<div class="texto-legenda">{{ Lang::get($menu.'.status-com-estoque') }}</div>
		</li>
	</ul>
	{{-- @endif --}}
	
</fieldset>