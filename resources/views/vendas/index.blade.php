@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12030.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('vendas/_12030.titulo') }}
@endsection

@section('conteudo')
    <input type="hidden" id="first" class="first" value="500">

    <ul class="list-inline acoes">

       <li>
           <a href="{{ $permissaoMenu->INCLUIR ? url('/_12030/create') : '#' }}" class="btn btn-primary btn-incluir" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} >
               <span class="glyphicon glyphicon-plus" ></span>
                {{ Lang::get('master.incluir') }}
           </a>
       </li>

   </ul>

    <div class="pesquisa-obj-container">
       <div class="input-group input-group-filtro-obj">
           <input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus />
           <button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
               <span class="fa fa-search"></span>
           </button>
       </div>
   </div>

<fieldset>
    
	<legend>Preços aprovados</legend>

	<table class="table table-striped table-bordered table-hover lista-obj selectable">
		<thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Modelo</th>
            </tr>
		</thead>
		<tbody>
            @foreach ($dados as $dado)
            <tr link="{{ url('_12030', $dado->ID) }}">
                <td>{{ $dado->DATA }}</td>
                <td>{{ $dado->CLIENTE }}</td>
                <td>{{ $dado->MODELO }}</td>
            </tr>
            @endforeach
		</tbody>
	</table>
	
</fieldset>
       
@endsection

@section('script')
    
    <script src="{{ elixir('assets/js/table.js') }}"></script>
    <script src="{{ elixir('assets/js/_12030.js') }}"></script>
    
@endsection
