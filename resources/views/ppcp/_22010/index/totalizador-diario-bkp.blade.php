<table class="table table-striped table-bordered table-hover table-totalizador-diario">
	<thead>
		<tr>
			<th class="text-center data"		title="{{ Lang::get($menu.'.th-data-title') }}"			>{{ Lang::get($menu.'.th-data') }}			</th>
			@if (isset($ver_up_todos) && $ver_up_todos == '1')
				<th class="up">{{ Lang::get('master.up') }}</th>
			@endif
			<th class="text-right capac-disp" 	title="{{ Lang::get($menu.'.th-capac-disp-title') }}"	>{{ Lang::get($menu.'.th-capac-disp') }}	</th>
			<th class="text-right carga-prog" 	title="{{ Lang::get($menu.'.th-carga-prog-title') }}"	>{{ Lang::get($menu.'.th-carga-prog') }}	</th>
			<th class="text-right carga-pend" 	title="{{ Lang::get($menu.'.th-carga-pend-title') }}"	>{{ Lang::get($menu.'.th-carga-pend') }}	</th>
			<th class="text-right carga-util" 	title="{{ Lang::get($menu.'.th-carga-util-title') }}"	>{{ Lang::get($menu.'.th-carga-util') }}	</th>
			<th class="text-center eficiencia" 	title="{{ Lang::get($menu.'.th-eficiencia-title') }}"	>{{ Lang::get($menu.'.th-eficiencia') }}	</th>
			<th class="text-center perc-aprov" 	title="{{ Lang::get($menu.'.th-perc-aprov-title') }}"	>{{ Lang::get($menu.'.th-perc-aprov') }}	</th>
		</tr>
	</thead>
	<tbody>
		
		@php $soma_capac_disp	= 0;
		@php $soma_min_prog		= 0;
		@php $soma_qtd_prog		= 0;
		@php $soma_talao_prog	= 0;
		@php $soma_pares_prog	= 0;
		@php $soma_min_pend		= 0;
		@php $soma_qtd_pend		= 0;
		@php $soma_talao_pend	= 0;
		@php $soma_pares_pend	= 0;
		@php $soma_min_prod		= 0;
		@php $soma_qtd_prod		= 0;
		@php $soma_talao_prod	= 0;
		@php $soma_pares_prod	= 0;
		@php $um				= '';
		@php $i					= 0;
		
		@if ( isset($totalizador_diario) )
			@foreach ( $totalizador_diario as $t )
		
				@php $soma_capac_disp	+= $t->CAPACIDADE_DISPONIVEL;
				@php $soma_min_prog		+= $t->CARGA_PROGRAMADA;
				@php $soma_qtd_prog		+= $t->QUANTIDADE_CARGA_PROGRAMADA;
				@php $soma_talao_prog	+= $t->QUANTIDADE_TALAO_PROGRAMADA;
				@php $soma_pares_prog	+= $t->QUANTIDADE_PARES_PROGRAMADA;
				@php $soma_min_pend		+= $t->CARGA_PENDENTE;
				@php $soma_qtd_pend		+= $t->QUANTIDADE_CARGA_PENDENTE;
				@php $soma_talao_pend	+= $t->QUANTIDADE_TALAO_PENDENTE;
				@php $soma_pares_pend	+= $t->QUANTIDADE_PARES_PENDENTE;
				@php $soma_min_prod		+= $t->CARGA_UTILIZADA;
				@php $soma_qtd_prod		+= $t->QUANTIDADE_CARGA_UTILIZADA;
				@php $soma_talao_prod	+= $t->QUANTIDADE_TALAO_UTILIZADA;
				@php $soma_pares_prod	+= $t->QUANTIDADE_PARES_UTILIZADA;
				@php $um				 = $t->UM;
			
				@php $data = empty($t->REMESSA_DATA) ? '' : date('d/m', strtotime($t->REMESSA_DATA))

				<tr data-date="{{ $data }}">
					<td class="text-center data">{{ $data }}</td>
					@if (isset($ver_up_todos) && $ver_up_todos == '1')
						<td class="up" title="{{ $t->UP_DESCRICAO }}">{{ $t->UP_DESCRICAO }}</td>
					@endif
					<td class="text-right capac-disp">{{ number_format($t->CAPACIDADE_DISPONIVEL, 0, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</td>
					<td class="text-right carga-prog">
						<div class="label">{{ number_format($t->CARGA_PROGRAMADA, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>
						<div class="label qtd-talao">{{ $t->QUANTIDADE_TALAO_PROGRAMADA }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
						<div class="label qtd-carga">{{ number_format($t->QUANTIDADE_CARGA_PROGRAMADA, 1, ',', '.') }} {{ $t->UM }}</div>
						
						@if($ver_pares == '1')
							<div class="label qtd-pares">{{ number_format($t->QUANTIDADE_PARES_PROGRAMADA, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
						@endif
						
						@if ($t->PERC_CARGA_PROGRAMADA <= 90)
							@php $cor_perc = 'label-warning';
						@elseif ($t->PERC_CARGA_PROGRAMADA > 90 && $t->PERC_CARGA_PROGRAMADA <= 100)
							@php $cor_perc = 'label-success';
						@else
							@php $cor_perc = 'label-danger';
						@endif

						<div class="label percentual {{ $cor_perc }}">{{ number_format($t->PERC_CARGA_PROGRAMADA, 2, ',', '.') }}%</div>
					</td>
					<td class="text-right carga-pend">	
						<div class="label">{{ number_format($t->CARGA_PENDENTE, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>						
						<div class="label qtd-talao">{{ $t->QUANTIDADE_TALAO_PENDENTE }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
						<div class="label qtd-carga">{{ number_format($t->QUANTIDADE_CARGA_PENDENTE, 1, ',', '.') }} {{ $t->UM }}</div>
						
						@if($ver_pares == '1')
							<div class="label qtd-pares">{{ number_format($t->QUANTIDADE_PARES_PENDENTE, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
						@endif
					</td>
					<td class="text-right carga-util">
						<div class="label">{{ number_format($t->CARGA_UTILIZADA, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>						 
						<div class="label qtd-talao">{{ $t->QUANTIDADE_TALAO_UTILIZADA }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
						<div class="label qtd-carga">{{ number_format($t->QUANTIDADE_CARGA_UTILIZADA, 1, ',', '.') }} {{ $t->UM }}</div>
						
						@if($ver_pares == '1')
							<div class="label qtd-pares">{{ number_format($t->QUANTIDADE_PARES_UTILIZADA, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
						@endif
					</td>
					<td class="text-center eficiencia">
						
						@if ($t->EFICIENCIA <= 90)
							@php $cor_perc = 'label-danger';
						@elseif ($t->EFICIENCIA > 90 && $t->EFICIENCIA <= 100)
							@php $cor_perc = 'label-warning';
						@else
							@php $cor_perc = 'label-success';
						@endif
						<div class="label percentual {{ $cor_perc }}">{{ number_format($t->EFICIENCIA, 2, ',', '.') }}%</td>
						
					<td class="text-center perc-aprov">

						@if ($t->PERC_APROVEITAMENTO <= 90)
							@php $cor_perc = 'label-warning';
						@elseif ($t->PERC_APROVEITAMENTO > 90 && $t->PERC_APROVEITAMENTO <= 100)
							@php $cor_perc = 'label-success';
						@else
							@php $cor_perc = 'label-danger';
						@endif

						<div class="label percentual {{ $cor_perc }}">{{ number_format($t->PERC_APROVEITAMENTO, 2, ',', '.') }}%</div>
					</td>
				</tr>
				
				@php $i++;
			@endforeach
		@endif

	</tbody>
</table>

<table class="table table-bordered table-total-totalizador">
	<thead>
		<tr>
			<th class="text-right capac-disp" title="{{ Lang::get($menu.'.th-capac-disp-title') }}">{{ Lang::get($menu.'.th-capac-disp') }}</th>
			<th class="text-right carga-prog" title="{{ Lang::get($menu.'.th-carga-prog-title') }}">{{ Lang::get($menu.'.th-carga-prog') }}</th>
			<th class="text-right carga-pend" title="{{ Lang::get($menu.'.th-carga-pend-title') }}">{{ Lang::get($menu.'.th-carga-pend') }}</th>
			<th class="text-right carga-util" title="{{ Lang::get($menu.'.th-carga-util-title') }}">{{ Lang::get($menu.'.th-carga-util') }}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="text-right capac-disp">
				{{ number_format($soma_capac_disp, 0, ',', '.') }}
			</td>
			<td class="text-right carga-prog">
				<div class="label soma-min">{{ number_format($soma_min_prog, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>
				<div class="label soma-talao">{{ number_format($soma_talao_prog, 0, ',', '.') }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
				<div class="label soma-qtd">{{ number_format($soma_qtd_prog, 1, ',', '.') }} {{ $um }}</div>
				@if($ver_pares == '1')
				<div class="label soma-pares">{{ number_format($soma_pares_prog, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
				@endif
			</td>
			<td class="text-right carga-pend">
				<div class="label soma-min">{{ number_format($soma_min_pend, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>
				<div class="label soma-talao">{{ number_format($soma_talao_pend, 0, ',', '.') }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
				<div class="label soma-qtd">{{ number_format($soma_qtd_pend, 1, ',', '.') }} {{ $um }}</div>
				@if($ver_pares == '1')
				<div class="label soma-pares">{{ number_format($soma_pares_pend, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
				@endif
			</td>
			<td class="text-right carga-util">
				<div class="label soma-min">{{ number_format($soma_min_prod, 2, ',', '.') }} {{ Lang::get($menu.'.minutos-abrev') }}</div>
				<div class="label soma-talao">{{ number_format($soma_talao_prod, 0, ',', '.') }} {{ Lang::get($menu.'.taloes-abrev') }}</div>
				<div class="label soma-qtd">{{ number_format($soma_qtd_prod, 1, ',', '.') }} {{ $um }}</div>
				@if($ver_pares == '1')
				<div class="label soma-pares">{{ number_format($soma_pares_prod, 0, ',', '.') }} {{ Lang::get($menu.'.pares-abrev') }}</div>
				@endif
			</td>
		</tr>
	</tbody>
</table>

<div class="area-full-grafico" id="area-full-grafico">
<fieldset class="grafico">
	<legend>{{ Lang::get($menu.'.grafico-1-legend') }}</legend>

	<div id="totalizador-diario-grafico-dashboard" class="grafico-conteiner">

        <div class="area-filtro-grafico">
            <button type="button" class="btn btn-screem-grafico btn-screem-grafico go-fullscreen" gofullscreen="area-full-grafico" title="Tela cheia">
                <span class="glyphicon glyphicon-fullscreen"></span>
            </button>
            <select class="select-tipo-grafico">
                <option value="LineChart">Linhas</option>
                <option value="AreaChart">Áreas</option>
                <option value="SteppedAreaChart">Andares</option>
            </select>
            <ul id="totalizador-grafico-filter" class=""><ul>
        </div>

        <div id="totalizador-diario-grafico-filter"  style="display: none;"></div>

        <div id="totalizador-diario-grafico"></div>
    </div>

</fieldset>
</div>