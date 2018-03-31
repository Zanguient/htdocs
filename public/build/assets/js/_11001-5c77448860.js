/**
 * App do objeto _11001 - Usuarios
 */

'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form'
	])
;
/**
 * Controller do objeto _11001 - Usuarios
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Index'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index
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
	$ctrl.Index = new Index();
}
/**
 * Factory index do objeto _11001 - Usuarios
 */

angular
    .module('app')
    .factory('Index', Index);    

Index.$inject = [
    '$ajax',
    'gScope'
];

function Index($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function Index() {

        obj = this;

        // Public variables
        this.filtro = {
            DATA_INI_INPUT: moment().subtract(3, 'month').toDate(),
            DATA_FIM_INPUT: moment().toDate()
        };
        this.dado = {};

        // Public methods
        this.filtrar = filtrar;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        obj.filtro.DATA_INI = moment(obj.filtro.DATA_INI_INPUT).format('DD.MM.YYYY') +' 00:00:00';
        obj.filtro.DATA_FIM = moment(obj.filtro.DATA_FIM_INPUT).format('DD.MM.YYYY') +' 23:59:59';

        $ajax
            .post('/_11001/consultar', obj.filtro)
            .then(function(response){

                obj.dado = response[0];
            });
    }


    /**
     * Return the constructor function
     */
    return Index;
};
//# sourceMappingURL=_11001.js.map
