/**
 * Controller do objeto _23037 - Avaliação de desempenho.
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
	'CreateColaborador',
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
	CreateColaborador,
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
	$ctrl.tipoFuncao    = 'base'; 	// resposta ou base

	// Objects.
	$ctrl.Index 			= new Index();
	$ctrl.Create 		 	= new Create();
	$ctrl.CreateColaborador = new CreateColaborador();
	$ctrl.CreateFator 	 	= new CreateFator();
	$ctrl.CreateFormacao 	= new CreateFormacao();
	$ctrl.CreateResumo 	 	= new CreateResumo();
}