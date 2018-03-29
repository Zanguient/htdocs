/**
 * Controller do objeto _31070 - Cadastro de Incentivos
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Incentivo'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Incentivo
) {

	// Public instance.
	gScope.vm = this;

	// Local instance.
	var vm = this;

	// Global variables.
	vm.tipoTela      = 'listar';
	vm.permissaoMenu = {};
	vm.Historico     = new Historico('vm.Historico', $scope);

	// Objects.
	vm.Incentivo = new Incentivo();
}