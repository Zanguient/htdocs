@extends('master')

@section('titulo')
{{ Lang::get('chamados/_26010.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/26010.css') }}">
@endsection

@section('conteudo')

<ul class="list-inline acoes">
	<li>
		<button type="button" class="btn btn-primary btn-alterar" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span>
			 {{ Lang::get('master.alterar') }}
		</button>
	</li>    
	<li>
		<button type="button" class="btn btn-warning btn-imprimir imprimir-oc" data-hotkey="alt+i" data-loading-text="{{ Lang::get('master.imprimindo') }}">
			<span class="glyphicon glyphicon-print"></span>
			 {{ Lang::get('compras/_13050.imprimir-oc') }}
		</button>
	</li>
    <li>
		<a href="{{ url('_13050') }}" class="btn btn-default btn-sub-chamado" data-hotkey="alt+1">
			<span class="glyphicon glyphicon-new-window"></span>
			Abrir Sub-chamado
		</a>
	</li>
</ul>
<div class="status right">
	<div class="label">EM ESPERA</div>
</div>
<form class="form-inline">

    <fieldset>
        <legend>
            Indicadores de Atendimento
        </legend>
        <div class="form-group">
            <label>SLA de Resposta:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->SLA_SOLUCAO_EXTENSO }}" readonly />
        </div>
        <div class="form-group">
            <label>SLA de Atendimento:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->SLA_SOLUCAO_EXTENSO }}" readonly />
        </div>
        <div class="form-group">
            <label>SLA de Solução:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->SLA_SOLUCAO_EXTENSO }}" readonly />
        </div>
        <div class="form-group">
            <label>SLA de Depenp. Externas:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->SLA_SOLUCAO_EXTENSO }}" readonly />
        </div>
        <div class="form-group">
            <label>SLA de Total:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->SLA_SOLUCAO_EXTENSO }}" readonly />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>
            Informações do Contato
        </legend>
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" class="form-control input-medio" value="{{ $chamado->ORIGEM_CONTATO }}" readonly />
        </div>
        <div class="form-group">
            <label>C. Custo:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->ORIGEM_CCUSTO_DESCRICAO }}" readonly />
        </div>
        <div class="form-group">
            <label>Turno:</label>
            <input type="text" class="form-control input-menor" value="{{ (int) $chamado->ORIGEM_TURNO }}°" readonly />
        </div>
        <div class="form-group">
            <label>Ramal:</label>
            <input type="text" class="form-control input-medio-min" value="{{ $chamado->ORIGEM_RAMAL }}" readonly />
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="text" class="form-control input-medio-extra" value="{{ $chamado->ORIGEM_EMAIL }}" readonly />
        </div>
    </fieldset>

    <fieldset>
        <legend>
            Informações do Chamado
        </legend>
        <div class="row">
            <div class="form-group">
                <label>Aberto em:</label>
                <input type="datetime-local" class="form-control input-datetime" value="{{ date_format(date_create($chamado->DATAHORA_ABERTURA), 'Y-m-d\TH:i:s') }}" readonly />
            </div>
            <div class="form-group">
                <label>Início em:</label>
                <input type="datetime-local" class="form-control input-datetime" value="{{ date_format(date_create($chamado->DATAHORA_CHAMADO), 'Y-m-d\TH:i:s') }}" readonly />
            </div>
            <div class="form-group">
                <label>Pervisão de Solução:</label>
                <input type="datetime-local" class="form-control input-datetime" value="{{ date_format(date_create($chamado->DATAHORA_PREVISAO), 'Y-m-d\TH:i:s') }}" readonly />
            </div>
            <div class="form-group">
                <label>Encerrado em:</label>
                <input type="datetime-local" class="form-control input-datetime" value="{{ date_format(date_create($chamado->DATAHORA_ENCERRADO), 'Y-m-d\TH:i:s') }}" readonly />
            </div>
            <div class="form-group">
                <label>Solucionado em:</label>
                <input type="datetime-local" class="form-control input-datetime" value="{{ date_format(date_create($chamado->DATAHORA_SOLUCAO), 'Y-m-d\TH:i:s') }}" readonly />
            </div>
            <div class="form-group">
                <label>Downtime:</label>
                <input type="text" class="form-control input-medio-extra" value="{{ $chamado->DOWNTIME_EXTENSO }}" readonly />
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label>Id:</label>
                <input type="text" class="form-control input-menor input-bold text-center" value="{{ $chamado->ID }}" readonly />
            </div>
            <div class="form-group">
                <label>Etiq. Serviço:</label>
                <input type="text" class="form-control input-menor input-bold text-center" value="{{ $chamado->ETIQUETA }}" readonly />
            </div>
            <div class="form-group">
                <label>Categoria:</label>
                <input type="text" class="form-control input-medio-extra" value="{{ $chamado->CATEGORIA_ID }} - {{ $chamado->CATEGORIA_DESCRICAO }}" readonly />
            </div>
            <div class="form-group">
                <label>Aberto para:</label>
                <input type="text" class="form-control input-medio-extra" value="{{ $chamado->DESTINO_SETOR_ID }} - {{ $chamado->DESTINO_SETOR_DESCRICAO }}" readonly />
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label>Descricao resumida:</label>
                <textarea name="observacao" style="resize: none;" class="form-control obs" rows="1" cols="113" readonly>{{ $chamado->DESCRICAO_RESUMIDA }}</textarea>                             
            </div>
        </div>                        
        <div class="row">
            <textarea name="observacao" class="form-control obs" rows="10" cols="113" readonly>{{ $chamado->DESCRICAO_DETALHADA }}</textarea>                             
        </div>
    </fieldset>    

</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/_26010.js') }}"></script>
@append


