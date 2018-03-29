/**
 * Controller do objeto _23038 - Registro de indicadores por centro de custo
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
	'CreateCCusto',
	'CreateIndicador'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateCCusto,
	CreateIndicador
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
	$ctrl.Index  			= new Index();
	$ctrl.Create 			= new Create();
	$ctrl.CreateCCusto 		= new CreateCCusto();
	$ctrl.CreateIndicador 	= new CreateIndicador();
}