/**
 * Controller do objeto _11002 - Usuarios
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
	'IndexItens'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	IndexItens
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
	
	vm.Index.consultar();
}