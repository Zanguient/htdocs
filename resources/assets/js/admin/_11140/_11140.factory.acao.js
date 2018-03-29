angular
    .module('app')
    .factory('Acao', Acao);
    

	Acao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection'
    ];

function Acao($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection) {

    /**
     * Constructor, with class name
     */
    function Acao(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    /**
     * Private property
     */
    var url_base        = '/_11140/';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Acao.prototype = {   
        openLink:function(id){
        	window.location.href = urlhost + url_base +  id;
        }		
	}


    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Acao.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Acao.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Acao(data);
    };

    /**
     * Return the constructor function
     */
    return Acao;
};