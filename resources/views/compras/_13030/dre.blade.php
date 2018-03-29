@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}" />
@endsection

@section('conteudo')
	<ul class="list-inline acoes">
        <li>
            <a href="{{ url('_13030') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
                <span class="glyphicon glyphicon-chevron-left"></span> {{ Lang::get('master.voltar') }}
            </a>
        </li>
        <li>
            <button type="button" class="btn btn-warning btn-imprimir btn-print" data-hotkey="alt+i" data-loading-text="{{ Lang::get('master.imprimindo') }}">
                <span class="glyphicon glyphicon-print"></span> 
                {{ Lang::get('master.imprimir') }}
            </button>
        </li>
	</ul>

	<div class="pesquisa-obj-container">
		<div class="input-group input-group-filtro-obj">
			<input type="search" name="filtro" class="form-control filtro-obj btn-dre-filtro" placeholder="Pesquise..." data-loading-text="{{ Lang::get('master.pesquisando') }}" autocomplete="off"/>
			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj"><span class="fa fa-search"></span></button>
		</div>
	</div>   

<form class="form-inline">
	<fieldset>   
		<legend>Filtros</legend>
        <div class="row">
            <div class="form-group">
                <label>Mês Inicial:</label>
                <select name="mes_1" class="form-control" required="" >
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}" {{ 1 == $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Mês Final:</label>
                <select name="mes_2" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                    <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Ano:</label>
                <select name="ano_1" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <input type="checkbox" name="cota_zerada" id="cota_zerada" class="form-control" {{ $cota_zerada ? 'checked' : '' }}/>
                <label for="cota_zerada"  ttitle="Exibe cotas com valor igual a 0 (zero)">Cotas Zeradas</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="cota_valida" id="cota_valida" class="form-control" {{ $cota_valida ? 'checked' : '' }}/>
                <label for="cota_valida"  ttitle="Exibe cotas com valor maior que 0 (zero)">Cotas Válidas</label>
            </div>	
            <div class="form-group">
                <input type="checkbox" name="cota_totaliza" id="cota_totaliza" class="form-control cota-totaliza" {{ $cota_totaliza ? 'checked' : '' }}/>
                <label for="cota_totaliza"  ttitle="Totaliza todas as cotas">Totaliza Cotas</label>
            </div>	    
            <div class="form-group">
                <input type="checkbox" id="meses_toggle" class="form-control" checked/>
                <label for="meses_toggle"  ttitle="Exibe/Oculta Meses">Exibir Meses</label>
            </div>	    
            <div class="form-group">
                <button type="button" class="btn btn-inline btn-primary btn-dre-filtrar" data-hotkey="alt+f" data-loading-text="{{ Lang::get('master.filtrando') }}">
                    <span class="glyphicon glyphicon-filter"></span> 
                    {{ Lang::get('master.filtrar') }}
                </button>    
            </div>	 
        </div>	        
        <legend>Consumo Mensal de Cotas</legend> 
		
		{{-- Tabela --}}
        <style>
            .meses-ocultar {
                display: none;
            }
        </style>
        <div class="dre">
            {!! $var !!}    
        </div>

	</fieldset>
</form>


@include('compras._13030.show-ggf.modal')
@include('compras._13030.show-ggf-detalhe.modal')

@endsection

<div class="print" style="display: none">
    {{--   {!! $print !!}  --}}
</div>

@section('popup-form-start')
	<form action="{{ route('_13050.store') }}" url-redirect="{{ url('sucessoGravar/_13030/dre') }}" method="POST" class="form-inline js-gravar popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
@endsection

@section('popup-head-title')
    Detalhamento da Cota
@endsection

@section('popup-body') @endsection

@section('popup-form-end')
	</form>
@endsection

@include('helper.include.view.pdf-imprimir')
@include('compras._13030.show.modal')

@section('script')
	
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input.js') }}"></script>
	<script src="{{ elixir('assets/js/pdf.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
    <script src="{{ elixir('assets/js/_25700.js') }}"></script>
@append