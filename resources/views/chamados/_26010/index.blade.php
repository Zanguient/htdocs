@extends('master')

@section('titulo')
{{ Lang::get('chamados/_26010.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/26010.css') }}">   
@endsection

@section('conteudo')

<ul class="list-inline acoes">
	
</ul>

<div class="pesquisa-obj-container">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj btn-oc-filtro" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtrar">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>
<form class="form-inline">
<!--    <div class="alert alert-warning">
        <p>O serviço de internet está fora do ar sem previsão de retorno</p>
        <p>Esta licitação está vinculada a uma ou mais Ordens de Compra</p>
        <p>Esta licitação está vinculada a uma ou mais Ordens de Compra</p>        
        <div class="form-group">
            <input type="text" class="form-control input-maior">
            <button type="button" class="btn btn-primary" />Gravar</button>
        </div>
    </div>-->
    <fieldset>
        <legend>Todos os Chamados ( {{ $qtd_total }} )</legend>  
        
        <section class="chamados">
            
            <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
            @php $i = 0
            @foreach($arr_status as $status)
                @php $i++
                <div class="panel panel-default" style="border-left: 3px solid #{{ $status->RGB }};">
                    <div class="panel-heading panel-accordion-heading" role="tab" id="heading{{ $i }}">
                        <a style="color: #{{ $status->RGB }};" role="button" data-toggle="collapse" href="#collapse{{ $i }}" data-parent="#accordion" aria-controls="collapse{{ $i }}">
                            {{ $status->DESCRICAO }} ( {{ $status->QTD }} )
                        </a>
                    </div>
                    <div class="panel-collapse collapse" id="collapse{{ $i }}" role="tabpanel" aria-labelledby="heading{{ $i }}">
                        <div class="panel-body">

                    @foreach ( $chamados as $chamado )
                        @if ($chamado->STATUS_ID == $status->ID)
                            <div class="panel panel-default panel-item">
                                <div class="panel-heading panel-item-heading">                   
                                    <h1 class="panel-title">
                                        <div class="ball-prioridade {{ $chamado->PRIORIDADE_DESCRICAO }}" data-toggle="tooltip" title="PRIORIDADE {{ $chamado->PRIORIDADE_DESCRICAO }}"></div>           
                                        <a href="#" class="link win-popup">{{ $chamado->ID }}</a> - {{ $chamado->DESCRICAO_RESUMIDA }}
                                    </h1>
                                </div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="form-group">
                                            <label>Contato:</label>
                                            <input type="text" class="form-control" value="{{ $chamado->ORIGEM_CONTATO }}" readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>C.Custo:</label>
                                            <input type="text" class="form-control input-maior" value="{{ $chamado->ORIGEM_CCUSTO }} - {{ $chamado->ORIGEM_CCUSTO_DESCRICAO }}" readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>Turno:</label>
                                            <input type="text" class="form-control input-menor" value="{{ (int) $chamado->ORIGEM_TURNO }}°" readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>Etiq. Serviço:</label>
                                            <input type="text" class="form-control input-menor input-text-center input-bold" value="{{ $chamado->ETIQUETA }}" readonly />
                                        </div>
                                    </div>                              
                                    <div class="row">
                                            <textarea name="observacao" class="form-control obs" rows="2" cols="113" readonly>{{ $chamado->DESCRICAO_DETALHADA }}</textarea>                             
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach                   
                        </div>
                    </div>
                </div>	
            @endforeach	
            </div>            
        </section>
    </fieldset>
</form>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/_26010.js') }}"></script>
@append