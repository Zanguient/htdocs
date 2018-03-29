@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11080.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11080.css') }}" />

   <link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />
@endsection

@section('conteudo')

<div ng-controller="Ctrl as vm" ng-cloak>

  <ul class="list-inline acoes">

      <li>
      <a href="{{ $permissaoMenu->INCLUIR ? url('/_11080/create') : '#' }}" class="btn btn-primary" data-hotkey="f6" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }}  {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }}>
        <span class="glyphicon glyphicon-plus"></span>
        {{ Lang::get('master.incluir') }}
      </a>
    </li>
    
  </ul>

  <div class="pesquisa-obj-container">
    <div class="input-group input-group-filtro-obj">
      <input type="search" name="filtro_obj" class="form-control pesquisa filtro-obj" ng-model="vm.FILTRO_REL" placeholder="Pesquise..." autocomplete="off" autofocus />
      <button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
        <span class="fa fa-search"></span>
      </button>
    </div>
  </div>

  <fieldset>
    <legend>{{ Lang::get('admin/_11080.legenda') }}</legend>
    
    <div style="max-height: calc(100vh - 186px);" class="table-ec">
        <div class="scroll-table">
            <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body">
                <thead>
                  <tr>
                      <th>ID</th>
                      <th>Descrição</th>
                  </tr>
              </thead>
                <tbody>
                    <tr ng-click="vm.openRel('{{url('_11080') }}',iten.ID)" tabindex="0" ng-repeat="iten in vm.DADOS | filter:vm.FILTRO_REL track by $index">
                        <td auto-title >@{{iten.ID}}</td>
                        <td auto-title >@{{iten.TITULO}}</td>
                    </tr>               
                </tbody>
            </table>
        </div>
    </div>

    <div id="grid" style="width: 100%; height: 400px;"></div>
    
  </fieldset>

</div>
@endsection

@section('script')

  <script src="{{ elixir('assets/js/_11080.js') }}"></script>

@endsection
