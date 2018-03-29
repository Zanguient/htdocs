
@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}" />
@endsection

@section('conteudo')
<form action="#" class="form-inline">
    
<ul class="list-inline acoes">
	<li>
		<a href="javascript:window.history.go(-1)" class="btn btn-default btn-voltar" data-hotkey="f11">
			<span class="glyphicon glyphicon-chevron-left"></span>
			 {{ Lang::get('master.voltar') }}
		</a>
	</li>
</ul>
    
	<fieldset>
		<legend>{{ Lang::get('master.info-geral') }}</legend>
		<div class="row">
			<div class="form-group">
				<label>{{ Lang::get('master.ccusto') }}:</label>
				<input type="text" name="ccusto_descricao" class="form-control input-maior" readonly value="{{ $cota->CCUSTO_MASK . ' - ' . $cota->CCUSTO_DESCRICAO }}" required/>
				<input type="hidden" name="_ccusto" class="form-control" value="{{ floatval($cota->CCUSTO) }}" />
			</div>
			<div class="form-group">
				<label>{{ Lang::get('master.ccontabil') }}:</label>
				<input type="text" name="ccontabil_descricao" class="form-control input-maior" readonly value="{{ $cota->CCONTABIL_MASK . ' - ' . $cota->CCONTABIL_DESCRICAO }}" required/>
				<input type="hidden" name="_ccontabil" class="form-control" value="{{ floatval($cota->CCONTABIL) }}" />
			</div>
			<div class="form-group">
				<label>{{ Lang::get('master.periodo') }}:</label>
				<input type="text" name="periodo" class="form-control" readonly value="{{ $cota->PERIODO_DESCRICAO }}" required/>
				<input type="hidden" name="_mes" class="form-control" value="{{ $cota->MES }}" />
				<input type="hidden" name="_ano" class="form-control" value="{{ $cota->ANO }}" />
			</div>
		</div>
		<div class="row">
		    <div class="form-group">
		    	<input type="checkbox" name="bloqueio" id="bloqueia" class="form-control" {{ ($cota->BLOQUEIO == 1) ? 'checked' : '' }} disabled />
		    	<label for="bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}" disabled >{{ Lang::get('compras/_13030.bloqueio') }}</label>
		    </div>
		    <div class="form-group">
		    	<input type="checkbox" name="notificacao" id="notifica" class="form-control" {{ ($cota->NOTIFICACAO == 1) ? 'checked' : '' }} disabled />
		    	<label for="notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}" disabled >{{ Lang::get('compras/_13030.notificacao') }}</label>
		    </div>
		    <div class="form-group">
		    	<input type="checkbox" name="destaque" id="destaque" class="form-control" {{ ($cota->DESTAQUE == 1) ? 'checked' : '' }} disabled />
		    	<label for="destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}" disabled >{{ Lang::get('compras/_13030.destaque') }}</label>
		    </div>		    
		    <div class="form-group">
		    	<input type="checkbox" name="totaliza" id="totaliza" class="form-control" {{ ($cota->TOTALIZA == 1) ? 'checked' : '' }} disabled />
		    	<label for="totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}" disabled >{{ Lang::get('compras/_13030.totaliza') }}</label>
		    </div>		                
		</div>
		<div class="row">
			<div class="form-group">
				<label>{{ Lang::get('compras/_13030.cota') }}:</label>
				<div class="input-group dinheiro">
					<div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->VALOR, 2, ',', '.') }}" required autofocus readonly value="555"/>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>{{ Lang::get('compras/_13030.cota-extra') }}</legend>
		<div class="form-group cota-extra" style="display: none">
			<label>{{ Lang::get('master.historico') }}</label>
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
					<th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
					<th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
					<th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
				</tr>
				</thead>
				<tbody class="t-body">
				@foreach( $extras as $extra )
				<tr data-id="{{ $extra->ID }}">
					<td class="t-text t-medium">{{ $extra->USUARIO_NOME }}</td>
					<td class="t-numb t-low">R$ {{ number_format($extra->VALOR, 2, ',', '.') }}</td>
					<td class="t-center t-medium">{{ date_format(date_create($extra->DATAHORA), 'd/m/Y H:i:s') }}</td>
					<td class="t-text t-extra-large limit-width">{{ $extra->OBSERVACAO }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</fieldset>
	<fieldset>
		<legend>{{ Lang::get('compras/_13030.outros-lancamentos') }}</legend>
		<div class="form-group cota-outros" style="display: none">
<!-- 		 style="display: none" -->
			<label>{{ Lang::get('master.historico') }}</label>
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
					<th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
					<th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
					<th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
				</tr>
				</thead>
				<tbody class="outros">
				@foreach( $outros as $outro )
				<tr data-id="{{ $outro->ID }}">
					<td class="t-text t-medium">{{ $outro->USUARIO_NOME }}</td>
					<td class="t-numb t-low">R$ {{ number_format($outro->VALOR, 2, ',', '.') }}</td>
					<td class="t-center t-medium">{{ date_format(date_create($outro->DATAHORA), 'd/m/Y H:i:s') }}</td>
					<td class="t-text t-extra-large limit-width">{{ $outro->OBSERVACAO }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</fieldset>	
</form>
@include('helper.include.view.historico',['tabela' => 'TBCCUSTO_COTA', 'id' => $id])

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
@append