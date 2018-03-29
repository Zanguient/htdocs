@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo-alterar') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}" />
@endsection

@section('conteudo')
<form action="{{ route('_13030.update') }}" url-redirect="{{ url('sucessoAlterar/_13030') }}" method="POST" class="form-inline edit js-gravar">
	<div class="input-hiddens">
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    <input type="hidden" name="id" value="{{ $id }}">
		<input type="hidden" name="usuario_nome" class="form-control" value="{{Auth::user()->NOME}}">
		<input type="hidden" name="data_hora" class="form-control" value="{{ date('d-m-Y H:i:s') }}">
	</div>

	<ul class="list-inline acoes">
		<li>
			<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
				<span class="glyphicon glyphicon-ok"></span>
				 {{ Lang::get('master.gravar') }}
			</button>
		</li>
		<li>
			<a href="javascript:window.history.go(-1)" class="btn btn-danger btn-cancelar" data-hotkey="f11">
				<span class="glyphicon glyphicon-ban-circle"></span>
				 {{ Lang::get('master.cancelar') }}
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
		    	<input type="checkbox" name="bloqueio" id="bloqueia" class="form-control" {{ ($cota->BLOQUEIO == 1) ? 'checked' : '' }} />
		    	<label for="bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}">{{ Lang::get('compras/_13030.bloqueio') }}</label>
		    </div>
		    <div class="form-group">
		    	<input type="checkbox" name="notificacao" id="notifica" class="form-control" {{ ($cota->NOTIFICACAO == 1) ? 'checked' : '' }} />
		    	<label for="notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}">{{ Lang::get('compras/_13030.notificacao') }}</label>
		    </div>
		    <div class="form-group">
		    	<input type="checkbox" name="destaque" id="destaque" class="form-control" {{ ($cota->DESTAQUE == 1) ? 'checked' : '' }} />
		    	<label for="destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}">{{ Lang::get('compras/_13030.destaque') }}</label>
		    </div>		    
		    <div class="form-group">
		    	<input type="checkbox" name="totaliza" id="totaliza" class="form-control" {{ ($cota->TOTALIZA == 1) ? 'checked' : '' }} />
		    	<label for="totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}">{{ Lang::get('compras/_13030.totaliza') }}</label>
		    </div>		                
		</div>
		<div class="row">
			<div class="form-group">
				<label>{{ Lang::get('compras/_13030.cota') }}:</label>
				<div class="input-group dinheiro">
					<div class="input-group-addon"><span class="fa fa-usd"></span></div>
					<input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->VALOR, 2, ',', '.') }}" required autofocus value="555"/>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="form-group" style="width: 50%;">
                <label>Observação Cota:</label>
                <textarea name="cota_observacao" rows="6" style="width: 100% !important;" class="form-control">{{ trim($cota->COTA_OBSERVACAO) }}</textarea>                
            </div>
        </div>        
        
	</fieldset>
	<fieldset>
		<legend>{{ Lang::get('compras/_13030.cota-extra') }}</legend>
		<div class="row">
        	<button type="button" class="btn btn-info btn-add-cota"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
		</div>
		<div class="group-cota-extra">
			<div class="row">
				<div class="form-group">
					<label>{{ Lang::get('master.valor') }}:</label>
					<div class="input-group">
						<div class="input-group-addon"><span class="fa fa-usd"></span></div>
						<input type="text" name="cota_extra" class="form-control mask-numero" decimal="2" min="0"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label>{{ Lang::get('master.obs') }}:</label>
					<div class="textarea-grupo">
						<textarea name="observacao" class="form-control obs-outros" rows="5" cols="100"></textarea>
						<span class="contador-outros"><span></span> {{ Lang::get('master.caract-restante') }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group cota-extra" style="display: none">
			<label>{{ Lang::get('master.historico') }}</label>
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
					<th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
					<th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
					<th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
					<th class="t-center t-low">{{ Lang::get('master.excluir') }}</th>
				</tr>
				</thead>
				<tbody class="t-body">
				@foreach( $extras as $extra )
				<tr data-id="{{ $extra->ID }}">
					<td class="t-text t-medium">{{ $extra->USUARIO_NOME }}</td>
					<td class="t-numb t-low">R$ {{ number_format($extra->VALOR, 2, ',', '.') }}</td>
					<td class="t-center t-medium">{{ date_format(date_create($extra->DATAHORA), 'd/m/Y H:i:s') }}</td>
					<td class="t-text t-extra-large limit-width">{{ $extra->OBSERVACAO }}</td>
					<td	class="t-center t-low t-btn"><button type="button" class="btn btn-danger btn-sm btn-remove-cota"><span class="glyphicon glyphicon-trash remove"></span></button></td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</fieldset>
	<fieldset>
		<legend>{{ Lang::get('compras/_13030.outros-lancamentos') }}</legend>
		<div class="row">
        	<button type="button" class="btn btn-info btn-add-outros"><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.adicionar') }}</button>
		</div>
		<div class="group-cota-outros">
			<div class="row">
				<div class="form-group">
					<label>{{ Lang::get('master.valor') }}:</label>
					<div class="input-group">
						<div class="input-group-addon"><span class="fa fa-usd"></span></div>
						<input type="text" name="outros_valor" class="form-control mask-numero" decimal="2" min="0"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label>{{ Lang::get('master.obs') }}:</label>
					<div class="textarea-grupo">
						<textarea name="outros_observacao" class="form-control obs" rows="5" cols="100"></textarea>
						<span class="contador"><span></span> {{ Lang::get('master.caract-restante') }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group cota-outros">
<!-- 		 style="display: none" -->
			<label>{{ Lang::get('master.historico') }}</label>
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
					<th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
					<th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
					<th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
					<th class="t-center t-low">{{ Lang::get('master.excluir') }}</th>
				</tr>
				</thead>
				<tbody class="outros">
				@foreach( $outros as $outro )
				<tr data-id="{{ $outro->ID }}">
					<td class="t-text t-medium">{{ $outro->USUARIO_NOME }}</td>
					<td class="t-numb t-low">R$ {{ number_format($outro->VALOR, 2, ',', '.') }}</td>
					<td class="t-center t-medium">{{ date_format(date_create($outro->DATAHORA), 'd/m/Y H:i:s') }}</td>
					<td class="t-text t-extra-large limit-width">{{ $outro->OBSERVACAO }}</td>
					<td	class="t-center t-low t-btn"><button type="button" class="btn btn-danger btn-sm btn-remove-outros"><span class="glyphicon glyphicon-trash remove"></span></button></td>
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
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
@append