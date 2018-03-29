<span 
	ng-init="$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }}"></span>

@include('vendas._12040.index.botao-acao')

@include('vendas._12040.index.index-filtro')

@include('vendas._12040.index.index-table')

<liberacao-12040></liberacao-12040>

<pedido-create-12040
	permissao-menu="$ctrl.permissaoMenu"
	tipo-tela="$ctrl.tipoTela"
	situacao-pedido="$ctrl.situacaoPedido"
	consultar-pedido="$ctrl.consultarPedido()"
	fechar-modal="$ctrl.fecharModal()"
></pedido-create-12040>

{{-- Para o chat. Alterado apenas ao carregar página. --}}
<input type="hidden" id="usuario-representante-id" value="@{{ $ctrl.representanteId }}">
<input type="hidden" id="representante-do-cliente" value="@{{ $ctrl.represDoCliente }}">