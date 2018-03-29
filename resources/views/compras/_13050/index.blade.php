@extends('master')

@section('titulo')
{{ Lang::get('compras/_13050.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13050.css') }}" />
@endsection

@section('conteudo')

<div class="pesquisa-obj-container">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj btn-oc-filtro" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtrar" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>
	
<form class="form-inline">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <fieldset class="oc">
        <legend>Filtros</legend>
        
            <div class="form-group">
                <label>Data Inicial:</label>
                <select name="filtro_mes_inicial" class="form-control filtro-mes-inicial" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}" {{ date('n',strtotime('-1 Month'))== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select name="filtro_ano_inicial" class="form-control filtro-ano-inicial" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}" {{ date('Y',strtotime('-1 Month'))== $i ? 'selected' : ''}}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Data Final:</label>
                <select name="filtro_mes_final" class="form-control filtro-mes-final" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                    <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select name="filtro_ano_final" class="form-control filtro-ano-final" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            @if ( $usuario->NIVEL_OC > 0 )
            <div class="form-group">
                <input type="checkbox" name="filtro_pendencia" id="filtro_pendencia" class="form-control filtro-pendencia" checked />
                <label for="filtro_pendencia" data-toggle="tooltip" title="Exibe OC's que ainda não foram autorizadas por você">Minhas Pendências</label>
            </div>
            @endif
            <div class="form-group">
                <input type="checkbox" name="filtro_pendentes" id="filtro_pendentes" class="form-control filtro-pendentes" {{ ($usuario->NIVEL_OC > 0) ? '' : 'checked' }} />
                <label for="filtro_pendentes" data-toggle="tooltip" title="Exibe OC's que ainda não foram autorizadas">Pendentes</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="filtro_autorizadas" id="filtro_autorizadas" class="form-control filtro-autorizadas" />
                <label for="filtro_autorizadas"  data-toggle="tooltip" title="Exibe OC's que foram autorizadas">Autorizadas</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="filtro_reprovadas" id="filtro_reprovadas" class="form-control filtro-reprovadas" />
                <label for="filtro_reprovadas"  data-toggle="tooltip" title="Exibe OC's que não foram autorizadas">Reprovadas</label>
            </div>	     
            <div class="form-group">
                <button type="button" class="btn btn-inline btn-primary btn-oc-filtrar" data-hotkey="alt+f" data-loading-text="{{ Lang::get('master.filtrando') }}">
                    <span class="glyphicon glyphicon-filter"></span> 
                    {{ Lang::get('master.filtrar') }}
                </button>    
            </div>           
        
        <legend>Ordens de Compra Pendentes de Autorização</legend>
    
    <div class="table-container table-ec" style="height: calc(100vh - 350px);">
		<table class="table table-striped table-bordered table-hover lista-obj selectable table-def table-condensed">
			<thead>
			<tr>
                <th class="t-min-medium-normal"></th>
				<th class="t-min-small-normal">OC</th>
				<th class="t-min-small-extra">Data</th>
				<th class="t-min-medium-normal">Fornecedor</th>
				<th class="t-min-big-normal">Família de Prod.</th>
				<th class="t-min-small-normal text-right">Qtd. Itens</th>
				<th class="t-min-small-normal text-right">Valor Total</th>
                <th class="t-min-big-extra">Status</th>
                <th class="t-min-big-extra">Obs. Interna</th>
			</tr>
			</thead>
			<tbody>
                
				{!! $itens !!}

			</tbody>
		</table>
	</div>

    </fieldset>
</form>

@section('popup-form-start')

	<form action="{{ route('_13050.store') }}" url-redirect="{{ url('sucessoGravar/_13050') }}" method="POST" class="form-inline js-gravar popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	
@endsection

@section('popup-head-title')
    Visualização de Ordem de Compra
@endsection

@section('popup-body') @endsection

@section('popup-form-end')
	</form>
@endsection


@endsection
@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/date.js') }}"></script>
	<script src="{{ elixir('assets/js/_13050.js') }}"></script>
@append