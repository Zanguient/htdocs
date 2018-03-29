angular
    .module('app')
    .factory('Familia', Familia);
    

	Familia.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$q',
        '$rootScope',
        '$filter',
        'gScope',
        'gcCollection'
    ];

function Familia($ajax, $httpParamSerializer, $q, $rootScope, $filter, gScope, gcCollection) {

    /**
     * Constructor, with class name
     */
    function Familia(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        
        gScope.Familia = this;
    }
    
    
    /**
     * Private property
     */
    var url_base        = '/_22010/';
    var possibleRoles   = ['admin', 'editor', 'guest'];
    

    /**
     * Public method, assigned to prototype
     */
    Familia.prototype = {   
        select : function (familia) {
            
            var bool = familia.SELECTED ? false : true;
            
            for ( var i in familia.GP ) {
                
                var gp = familia.GP[i];
                
                for ( var y in gp.ESTACAO ) {
                    var estacao = gp.ESTACAO[y];
                    
                    gScope.Estacao.select(estacao,bool);
                }
            }
        }
    };

    /**
     * Private function
     */
    function fn (param)
    {
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Familia.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Familia.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Familia(data);
    };

    /**
     * Return the constructor function
     */
    return Familia;
};