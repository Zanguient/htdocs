/**
 * Controller do objeto _23036 - Cadastro de avaliação de desempenho.
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
	'Create',
	'CreateModelo',
	'CreateCCusto'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateModelo,
	CreateCCusto
) {

	// Public instance.
	gScope.Ctrl = this;

	// Local instance.
	var $ctrl = this;

	// Global variables.
	$ctrl.tipoTela      = 'listar';
	$ctrl.permissaoMenu = {};
	$ctrl.Historico     = new Historico('$ctrl.Historico', $scope);

	// Objects.
	$ctrl.Index  		 	= new Index();
	$ctrl.Create 		 	= new Create();
	$ctrl.CreateModelo 	 	= new CreateModelo();
	$ctrl.CreateCCusto 	 	= new CreateCCusto();
}