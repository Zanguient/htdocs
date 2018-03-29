/**
 * Controller do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
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
	'CreateFator',
	'CreateFormacao',
	'CreateResumo'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create,
	CreateFator,
	CreateFormacao,
	CreateResumo
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
	$ctrl.Index 			= new Index();
	$ctrl.Create 			= new Create();
	$ctrl.CreateFator 		= new CreateFator();
	$ctrl.CreateFormacao	= new CreateFormacao();
	$ctrl.CreateResumo		= new CreateResumo();
}