@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11010.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11010.titulo') }}
@endsection

@section('conteudo')

<ul class="list-inline acoes">    
    <li>
        <a href="{{ $permissaoMenu->INCLUIR ? url('/_11010/create') : '#' }}" class="btn btn-primary btn-hotkey btn-incluir" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-primary btn-incluir" data-hotkey="f6">
            <span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}
        </a>
    </li>               
</ul>
<div class="pesquisa-obj-container">
	<div class="input-group input-group-pesquisa">
		<input type="search" name="filtro_pesquisa" class="form-control filtro-obj" id="filter-btn-find" placeholder="Pesquise..." autocomplete="off" autofocus />
		<button type="button" class="input-group-addon btn-filtro btn-filtrar">
			<span class="fa fa-search"></span>
		</button>
	</div>
</div>
    <fieldset id="index-list">
        <legend>Usuário Cadastrados</legend>
        
        <div id="table-filter" class="table-filter">
            <div>
                <label>Status:</label>
                <select id="filter-status">
                    <option value="">Todos</option>
                    <option selected value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
            </div>
            <button class="btn btn-sm btn-primary" data-hotkey="alt+f" id="filter-btn">
                <span class="glyphicon glyphicon-filter"></span>
                {{ Lang::get('master.filtrar') }}
            </button>
        </div>

		<table class="table table-striped table-bordered table-hover lista-obj">
			<thead>
			<tr>
                <th class="col-min-small-normal"></th>
				<th class="col-min-small-extra">Cód.</th>
				<th class="col-min-normal">Usuário</th>
				<th class="col-min-big-normal">Nome</th>
				<th class="col-min-big-normal">Email</th>
			</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		<button type="button" class="btn btn-default carregar-pagina"><span class="glyphicon glyphicon-triangle-bottom"></span> Carregar mais...</button>

    </fieldset>

@section('popup-form-start')

	<form class="form-inline popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	
@endsection

@section('popup-head-title')
    Visualização Usuário
@endsection

@section('popup-body') @endsection

@section('popup-form-end')
	</form>
@endsection

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js'  ) }}"></script>
    <script src="{{ elixir('assets/js/_11010.js') }}"></script>
@endsection