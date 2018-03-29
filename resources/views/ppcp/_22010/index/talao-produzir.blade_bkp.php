{{-- Variável para identificar se é componente --}}
@php /* $componente = '0'; */

<input type="hidden" id="_pu212" value="{{ $pu212 or '' }}" />
<input type="hidden" id="_pu213" value="{{ $pu213 or '' }}" />





<table class="table table-striped table-bordered table-hover table-talao-produzir">
	<thead>
		<tr>
			<th></th>
			<th class="t-status" title="{{ Lang::get($menu.'.status-talao-title') }}"></th>
			<th class="t-status" title="{{ Lang::get($menu.'.status-consumos-title') }}"></th>	
			@if (isset($ver_up_todos) && $ver_up_todos == '1')
				<th class="up">{{ Lang::get('master.up') }}</th>
			@endif				
			<th class="estacao">{{ Lang::get('master.estacao') }}</th>
			<th class="text-center data-remessa" title="{{ Lang::get($menu.'.data-remessa') }}">{{ Lang::get($menu.'.data-remessa-abrev') }}</th>
			<th class="remessa">{{ Lang::get($menu.'.remessa') }}</th>
			<th class="text-right talao">{{ Lang::get($menu.'.talao') }}</th>
			<th class="text-center data-remessa-origem" title="{{ Lang::get($menu.'.data-remessa-origem') }}">{{ Lang::get($menu.'.data-remessa-origem-abrev') }}</th>
			<th class="text-right talao-origem">{{ Lang::get($menu.'.talao-origem') }}</th>
			<th class="up-origem">{{ Lang::get($menu.'.up-origem') }}</th>
			<th class="modelo">{{ Lang::get('master.modelo') }}</th>
			<th class="text-right densidade">{{ Lang::get($menu.'.densidade-abrev') }}</th>
			<th class="text-right espessura">{{ Lang::get($menu.'.espessura-abrev') }}</th>
			<th class="text-right qtd">{{ Lang::get('master.qtd-abrev') }}</th>
			{{-- <th class="text-right qtd-alternativa" title="{{ Lang::get($menu.'.qtd-alternativa') }}">{{ Lang::get($menu.'.qtd-alternativa-abrev') }}</th> --}}
			@if($ver_pares == '1')
			<th class="text-right pares">{{ Lang::get($menu.'.pares') }}</th>
			@endif
			<th class="text-right tempo-prev" title="{{ Lang::get($menu.'.tempo-prev') }}">{{ Lang::get($menu.'.tempo-prev-abrev') }}</th>
			<th class="text-center data-ini-prev" title="{{ Lang::get($menu.'.data-ini-prev') }}">{{ Lang::get($menu.'.data-ini-prev-abrev') }}</th>
		</tr>
	</thead>
	<tbody>
			
		@foreach ( $taloes_produzir as $talao )
		
			@php /* $componente = trim($talao->COMPONENTE); */
		
			<tr tabindex="0" id="{{ $talao->ID }}" class="tipo-{{ $talao->REMESSA_TIPO }}" 
				data-id="#id-{{ $talao->ID }}" 
				data-tipo="{{ trim($talao->REMESSA_TIPO) }}" 
				data-consumo-componente="{{ trim($talao->COMPONENTE) }}"
				data-status-componentes="{{ trim($talao->STATUS_COMPONENTE) }}"
				data-status-materias-primas="{{ trim($talao->STATUS_MATERIA_PRIMA) }}"
				data-status-programacao="{{ trim($talao->PROGRAMACAO_STATUS) }}"
				data-up-origem="{{ $talao->UP_DESTINO }}"
				data-remessa-data="{{ $talao->REMESSA_DATA }}">
				<td>
					<input type="radio" name="talao_produzir" class="radio-talao-produzir" />
				</td>
				<td class="t-status status-programacao status{{ $talao->PROGRAMACAO_STATUS }}" title="{{ $talao->PROGRAMACAO_STATUS_DESCRICAO }}"></td>
				
				@php /*
				@if ( $componente == '1' )
				<td class="t-status status-componentes-{{ $talao->STATUS_COMPONENTE }}" title="{{ $talao->STATUS_COMPONENTE_DESCRICAO }}"></td>
				@else
				<td class="t-status status-materias-primas-{{ $talao->STATUS_MATERIA_PRIMA }}" title="{{ $talao->STATUS_MATERIA_PRIMA_DESCRICAO }}"></td>
				@endif
				@php */
				
				<td class="t-status status-materias-primas-{{ $talao->STATUS_MP_CP }}" title="{{ $talao->STATUS_MATERIA_PRIMA_DESCRICAO }}"></td>
				
				@if (isset($ver_up_todos) && $ver_up_todos == '1')
					<td class="up" title="{{ $talao->UP_DESCRICAO }}">{{ $talao->UP_DESCRICAO }}</td>
				@endif
				
				<td class="estacao">{{ $talao->ESTACAO }} - {{ $talao->ESTACAO_DESCRICAO }}</td>
				<td class="text-center data-remessa">{{ date('d/m', strtotime($talao->REMESSA_DATA)) }}</td>
				<td class="remessa">
					{{ $talao->REMESSA }} 
					@if ( $talao->REMESSA_TIPO != '1')
					<span class="tipo">{{ $talao->REMESSA_TIPO_DESCRICAO }}</span>
					@endif
				</td>
				<td class="text-right talao">{{ $talao->REMESSA_TALAO_ID }}</td>
				<td class="text-center data-remessa-origem">{{ empty($talao->DATA_REMESSA_ORIGEM) ? '-' : date('d/m', strtotime($talao->DATA_REMESSA_ORIGEM)) }}</td>
				<td class="text-right talao-origem" title="{{ $talao->TALOES_ORIGEM }}">
                    @if( !empty($talao->TALOES_ORIGEM) )
					<span
						class="glyphicon glyphicon-info-sign" 
						data-toggle="popover" 
						data-placement="top" 
						title="Informações da Origem" 
						data-content="
						<div class='origem-container'>
							<table class='table table-striped table-bordered'>
								<thead>
									<tr>
										<th class='text-left'>GP</th>
										<th class='text-left'>Qtd.</th>
									</tr>
								</thead>
								<tbody>
									@foreach(explode(',', $talao->PARES_POR_GP) as $pr)
									<tr>
										@php $p = explode('/', $pr);
										<td class='text-left'>{{ $p[0] or '-' }}</td>
										<td class='text-left'>{{ $p[1] or '-' }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						">
					</span>
                    @endif
					<span>{{ $talao->TALOES_ORIGEM }}</span>
				</td>
				<td class="up-origem">{{ $talao->UP_DESTINO }}</td>
				<td class="modelo">{{ $talao->MODELO_ID }} - {{ $talao->MODELO_DESCRICAO }}</td>
				<td class="text-right densidade">{{ number_format($talao->DENSIDADE, 2, ',', '.') }}</td>
				<td class="text-right espessura">{{ number_format($talao->ESPESSURA, 2, ',', '.') }}</td>
				
				@if( $talao->QUANTIDADE_ALTERNATIVA > 0 )
					@php $class_qtd = 'qtd-alternativa';
					@php $qtd		= number_format($talao->QUANTIDADE_ALTERNATIVA, 4, ',', '.')
					@php $um		= $talao->UM_ALTERNATIVA
				@else
					@php $class_qtd = 'qtd';
					@php $qtd		= number_format($talao->QUANTIDADE, 4, ',', '.')
					@php $um		= $talao->UM
				@endif			
				
				<td class="text-right {{ $class_qtd }}">{{ $qtd }} {{ $um }}</td>
				@if($ver_pares == '1')
				<td class="text-right pares">{{ number_format($talao->PARES, 0, ',', '.') }}</td>
				<input type="hidden" class="_pares" value="{{ $talao->PARES or '0' }}" />				
				@endif
				<td class="text-right tempo-prev">{{ number_format($talao->TEMPO, 2, ',', '.') }} min</td>
				<td class="text-center data-ini-prev">{{ empty($talao->DATAHORA_INICIO) ? '' : date('d/m H:i', strtotime($talao->DATAHORA_INICIO)) }}</td>

				<input type="hidden" name="_id"					  class="_id"					value="{{ $talao->ID }}"				/>
				<input type="hidden" name="_programacao_id"		  class="_programacao-id"		value="{{ $talao->PROGRAMACAO_ID }}"	/>
				<input type="hidden" name="_remessa_id"			  class="_remessa-id"			value="{{ $talao->REMESSA_ID }}"		/>
				<input type="hidden" name="_remessa_talao_id"	  class="_remessa-talao-id"     value="{{ $talao->REMESSA_TALAO_ID }}"	/>
			</tr>
		@endforeach

	</tbody>
</table>

<div class="legenda-container">
	<label class="legenda-label">{{ Lang::get($menu.'.legenda-status-talao') }}</label>
	<ul class="legenda talao">
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
</div>


<div class="legenda-container">
	<label class="legenda-label">{{ Lang::get($menu.'.legenda-status-consumos') }}</label>
	@php /*
	@if ( $componente == '1' )
	<ul class="legenda talao-status-componentes">
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-parado') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-andamento') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-produzido') }}</div>
			</li>
			<li>
				<div class="cor-legenda"></div>
				<div class="texto-legenda">{{ Lang::get($menu.'.status-encerrado') }}</div>
			</li>
		</ul>
	@else
	@php */
		<ul class="legenda status-materias-primas">
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
</div>

<div class="totalizador-produzir">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<label>{{ Lang::get('master.qtd-abrev') }}</label>
			@if($ver_pares == '1')
			<label>{{ Lang::get($menu.'.pares') }}</label>
			@endif
			<label>{{ Lang::get($menu.'.tempo-prev-abrev') }}</label>
		</div>
		<div class="panel-body">
			<label class="qtd"></label>
			@if($ver_pares == '1')
			<label class="pares"></label>
			@endif
			<label class="tempo"></label>
		</div>
	</div>
</div>
