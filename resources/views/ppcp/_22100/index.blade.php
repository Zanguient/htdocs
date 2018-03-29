@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22100.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22100.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>
<ul class="list-inline acoes">
	<li>
        <button 
            type="button" 
            class="btn btn-success btn-inline btn-distribuir-auto" 
            data-hotkey="alt+a" 
            id="btn-distribuir-auto" 
            ng-disabled="( vm.dados.length <= 0)"
            ng-click="vm.gravar()"
            >
			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get('master.gravar') }}
		</button>
	</li>
	<li>
        <a href="{{ url('_22120') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
            <span class="glyphicon glyphicon-ban-circle"></span> 
            {{ Lang::get('master.cancelar') }}
        </a>
    </li>
</ul>

        <div class="alert alert-danger" ng-if="vm.PEDIDO_BLOQUEIO_USUARIOS.length > 0"  style="padding: 5px;"> 
            Atenção, há pedidos bloqueados pelos seguintes usuários:
        <div>
            <div 
                class="btn btn-danger btn-xs" 
                style="margin-right: 4px;"
                ng-repeat="usuario in vm.PEDIDO_BLOQUEIO_USUARIOS"  
                ng-click="vm.PedidoDesbloqueio(usuario.USUARIO)"
                >
                @{{ usuario.USUARIO }}
            </div>
        </div>
            
            <span style="font-size: 80%; font-weight: bold">Clique no usuário para desbloquear os pedidos.</span>
</div>
        <fieldset class="programacao">
        <legend>Itens à serem programados</legend>
        @include('ppcp._22100.create.form-filtrar')
  
        <div class="container-programacao" style="display: none">
            <div class="container-left">
                @include('ppcp._22100.create.table-linha')
                @include('ppcp._22100.create.table-agrupamento')
            </div>
            <div class="container-right">
                @include('ppcp._22100.create.table-consumo')
                @include('ppcp._22100.create.table-ferramenta')
                @include('ppcp._22100.create.accordion-gp')
            </div>
        </div>
        

    </fieldset>

@include('ppcp._22100.create.modal-linha-remessa-historico')    
</div>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/formatter.js' ) }}"></script>
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
    <script src="{{ elixir('assets/js/_22100.js'    ) }}"></script>
@append
