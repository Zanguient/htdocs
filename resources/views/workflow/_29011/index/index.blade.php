<span 
	ng-init="$ctrl.permissaoMenu = {{ json_encode($permissaoMenu) }}"></span>

@include('workflow._29011.index.botao-acao')

@include('workflow._29011.index.index-filtro')

@include('workflow._29011.index.index-table')

<create-29011
	permissao-menu="$ctrl.permissaoMenu"
	tipo-tela="$ctrl.tipoTela"
	fechar-modal="$ctrl.fecharModal()"></create-29011>