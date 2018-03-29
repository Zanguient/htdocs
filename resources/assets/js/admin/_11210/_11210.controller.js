/**
 * Controller do objeto _11210 - Cadastro de Perfil de Usuario
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Index',
	'IndexItens',
	'IndexMenus',
	'IndexGrupo',
	'$consulta'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	IndexItens,
	IndexMenus,
	IndexGrupo,
	$consulta
) {

	// Public instance.
	gScope.vm = this;

	// Local instance.
	var vm = this;

	// Global variables.
	vm.tipoTela      = 'listar';
	vm.permissaoMenu = {};
	vm.Historico     = new Historico('$ctrl.Historico', $scope);

	// Objects.
	vm.Index = new Index();
	vm.IndexItens = new IndexItens();

	vm.Consulta         = new $consulta();
	vm.Consulta_Usuario = vm.Consulta.getNew();
	vm.Consulta_Menu    = vm.Consulta.getNew();
	vm.Consulta_Grupo   = vm.Consulta.getNew();

    vm.Consulta_Usuario.componente             = '.Consulta_Usuario',
    vm.Consulta_Usuario.model                  = 'vm.Consulta_Usuario',
    vm.Consulta_Usuario.option.label_descricao = 'Usuário:',
    vm.Consulta_Usuario.option.obj_consulta    = '/_11210/ConsultaUsuario',
    vm.Consulta_Usuario.option.tamanho_input   = 'input-medio';
    vm.Consulta_Usuario.option.class           = 'ConsultaUsuario';
    vm.Consulta_Usuario.option.tamanho_tabela  = 400;
    vm.Consulta_Usuario.option.obj_ret         = ['ID','USUARIO'];
    vm.Consulta_Usuario.option.campos_tabela   = [['ID','ID'],['USUARIO','Usuário'],['NOME','Descrição']];
	vm.Consulta_Usuario.compile();

	vm.Consulta_Menu.componente             = '.Consulta_Menu',
    vm.Consulta_Menu.model                  = 'vm.Consulta_Menu',
    vm.Consulta_Menu.option.label_descricao = 'Usuário:',
    vm.Consulta_Menu.option.obj_consulta    = '/_11210/ConsultaMenu',
    vm.Consulta_Menu.option.tamanho_input   = 'input-medio';
    vm.Consulta_Menu.option.class           = 'ConsultaMenu';
    vm.Consulta_Menu.option.obj_ret         = ['ID','USUARIO'];
    vm.Consulta_Menu.option.campos_tabela   = [['ID','ID'],['USUARIO','Usuário'],['NOME','Descrição']];
    vm.Consulta_Menu.option.tamanho_tabela  = 400;
	vm.Consulta_Menu.compile();

	vm.Consulta_Grupo.componente             = '.Consulta_Grupo',
    vm.Consulta_Grupo.model                  = 'vm.Consulta_Grupo',
    vm.Consulta_Grupo.option.label_descricao = 'Usuário:',
    vm.Consulta_Grupo.option.obj_consulta    = '/_11210/ConsultaGrupo',
    vm.Consulta_Grupo.option.tamanho_input   = 'input-medio';
    vm.Consulta_Grupo.option.class           = 'ConsultaGrupo';
    vm.Consulta_Grupo.option.tamanho_tabela  = 400;
	vm.Consulta_Grupo.compile();

	vm.Index.consultar();
}