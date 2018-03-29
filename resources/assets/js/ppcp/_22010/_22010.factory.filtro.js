
        
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Filtro.prototype = {
        submit : function() {
            $timeout(function(){
                $('.btn-filtrar').click();
            });
        },
        setData: function(data) {
            angular.extend(this, data);
        },        
        consultar : function (args) {
            this.AUTO_LOAD = 1;
            this.uriHistory();
            
            switch(this.GUIA_ATIVA) {
                case 'TALAO_PRODUZIR':
                    
                    if ( gScope.TalaoProduzir.EM_PRODUCAO ) {
                        gScope.TalaoProduzir.current(true);
                    } else {
                        gScope.TalaoProduzir.all();
                    }
                    
                    break;
                case 'TALAO_PRODUZIDO':
                    gScope.TalaoProduzido.all();                    
                    break;
                case 'TOTALIZADOR_DIARIO':
                    gScope.TotalizadorDiario.consultar();                    
                    break;
            }
        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_22010?'+$httpParamSerializer(this)));
        }
    };

    /**
     * Private function
     */
    function checkRole(role) {
      return possibleRoles.indexOf(role) !== -1;
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Filtro.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Filtro.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Filtro(data);
    };

    /**
     * Return the constructor function
     */
    return Filtro;
};