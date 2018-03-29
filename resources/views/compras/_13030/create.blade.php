@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo-incluir') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}">
@endsection

@section('conteudo')

	<form action="{{ route('_13030.store') }}" method="POST" class="form-inline form-add js-gravar" url-redirect="{{ url('sucessoGravar/_13030') }}">
		<div class="input-hiddens">
	    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    	<input type="hidden" name="ccontabil_tipo" id="ccontabil-tipo" value="analitica">
        </div>

	    <ul class="list-inline acoes">
			<li>
				<button type="button" class="btn btn-success js-gravar btn-modal" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
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
                    <label for="ccusto-descricao">{{ Lang::get('master.ccusto') }}:</label>
                    <div class="input-group">
                        <input type="search" name="ccusto_descricao" id="ccusto-descricao" class="form-control input-maior js-ccusto" autocomplete="off" autofocus required/>
                        <button type="button" class="input-group-addon btn-filtro btn-filtro-ccusto"><span class="fa fa-search"></span></button>
						<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccusto"><span class="fa fa-close"></span></button>
                    </div>
                    <div class="pesquisa-res-container lista-ccusto-container">
                        <div class="pesquisa-res lista-ccusto"></div>
                    </div>
                    <input type="hidden" name="_ccusto" class="ccusto"/>
                </div>
				<div class="form-group">
					<label for="ccontabil-descricao">{{ Lang::get('master.ccontabil') }}:</label>
					<div class="input-group">
						<input type="search" name="ccontabil_descricao" id="ccontabil-descricao" class="form-control input-maior js-ccontabil" autocomplete="off" required />
						<button type="button" class="input-group-addon btn-filtro btn-filtro-ccontabil"><span class="fa fa-search"></span></button>
						<button type="button" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-ccontabil"><span class="fa fa-close"></span></button>
					</div>
                    <div class="pesquisa-res-container lista-ccontabil-container">
                        <div class="pesquisa-res lista-ccontabil"></div>
                    </div>
                    <input type="hidden" name="_ccontabil" class="ccontabil" />
                </div>
				<div class="form-group">
					<label for="valor">{{ Lang::get('compras/_13030.valor-cota') }}:</label>
					<div class="input-group left-icon required">
						<div class="input-group-addon"><span class="fa fa-usd"></span></div>
						<input type="text" name="valor" class="form-control valor mask-numero" decimal="2" min="0" required/>
					</div>
				</div>
			</div>
            <legend>{{ Lang::get('master.periodo') }}</legend>
			<div class="row">
                <div class="form-group">
                    <label for="data-utilizacao">{{ Lang::get('master.data-inicial') }}:</label>
                    <select name="mes_inicial" class="form-control mes-inicial" required="" >
                        <option disabled>{{ Lang::get('master.mes') }}</option>
                        @for ($i = 1; $i < 13; $i++)
                         <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                    <select name="ano_inicial" class="form-control ano-inicial" required="" >
                        <option disabled>{{ Lang::get('master.ano') }}</option>
                        @for ($i = 2000; $i < 2041; $i++)
                        <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="data-utilizacao">{{ Lang::get('master.data-final') }}:</label>
                    <select name="mes_final" class="form-control mes-final" required>
                        <option disabled>{{ Lang::get('master.mes') }}</option>
                        @for ($i = 1; $i < 13; $i++)
                        <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                    <select name="ano_final" class="form-control ano-final" required>
                        <option disabled>{{ Lang::get('master.ano') }}</option>
                        @for ($i = 2000; $i < 2041; $i++)
                        <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="row">
			    <div class="form-group">
			    	<input type="checkbox" name="bloqueio" id="bloqueia" class="form-control" checked />
			    	<label for="bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}">{{ Lang::get('compras/_13030.bloqueio') }}</label>
			    </div>
			    <div class="form-group">
			    	<input type="checkbox" name="notificacao" id="notifica" class="form-control" checked />
			    	<label for="notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}">{{ Lang::get('compras/_13030.notificacao') }}</label>
			    </div>
			    <div class="form-group">
			    	<input type="checkbox" name="destaque" id="destaque" class="form-control" />
			    	<label for="destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}">{{ Lang::get('compras/_13030.destaque') }}</label>
			    </div>
                <div class="form-group">
                    <input type="checkbox" name="totaliza" id="totaliza" class="form-control" checked />
                    <label for="totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}">{{ Lang::get('compras/_13030.totaliza') }}</label>
                </div>                
			</div>
	    </fieldset>

        <div id="modal-cotas-existentes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CotasExistentes" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">{{ Lang::get('compras/_13030.cota-existente') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ Lang::get('compras/_13030.cota-existente-desc') }}<br/><strong>{{ Lang::get('compras/_13030.cota-inalterada') }}</strong></p>
                        <div class="cotas-existentes">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span>{{ Lang::get('master.voltar') }}</button>
                        <button type="submit" class="btn btn-success btn-confirma" data-loading-text="{{ Lang::get('master.gravando') }}"><span class="glyphicon glyphicon-ok"></span> {{ Lang::get('master.gravar') }}</button>
                    </div>
                </div>
            </div>
        </div>
	</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
@append


