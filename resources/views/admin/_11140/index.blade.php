@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11140.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11140.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

<ul class="list-inline acoes">    
    <li>
        <a href="{{ $permissaoMenu->INCLUIR ? url('/_11140/create') : '#' }}" class="btn btn-primary btn-hotkey btn-incluir" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-primary btn-incluir" data-hotkey="f6">
            <span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}
        </a>
    </li>               
</ul>

<fieldset>
	<legend>Paineis de Casos</legend>
		<div>
			<table class="table table-striped table-bordered table-hover lista-obj selectable">

				<colgroup>
					<col style="width: 8%">
					<col style="width: 35%">
					<col style="width: 50%">
					<col style="width: 7%">
				</colgroup>
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>Descricao</th>
						<th>Status</th>
					</tr>
				</thead>
				<tr ng-click="vm.Index.openLink(painel.ID)" ng-repeat="painel in vm.Index.PAINEIS track by $index">
					<td>
						@{{painel.ID}}
					</td>
					<td>
						@{{painel.TITULO}} 	
					</td>
					<td>
						@{{painel.DESCRICAO}} 
					</td>
					<td>
						<span

							ng-class="{
								'glyphicon-ok status-ativo' : painel.STATUS == 1,
								'glyphicon-remove status-inativo' : painel.STATUS == 0
							}"

							class="glyphicon"></span> 
					</td>
				</tr>

			</table>
		</div>
	</div>


</fieldset>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11140.app.js') }}"></script>
@append
