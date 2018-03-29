<span 
	ng-init="$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }}"></span>

@include('workflow._29010.index.botao-acao')

@include('workflow._29010.index.index-filtro')

@include('workflow._29010.index.index-table')

<workflow-create-29010
	permissao-menu="$ctrl.permissaoMenu"
	tipo-tela="$ctrl.tipoTela"
	fechar-modal="$ctrl.fecharModal()"
></workflow-create-29010>