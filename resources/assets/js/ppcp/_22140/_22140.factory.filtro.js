
        
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gcObject',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gcObject, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        
        gScope.Filtro = this;
        
        this.DATAHORA    = moment(Clock.DATETIME_SERVER).seconds(0).milliseconds(0).toDate();
        this.AGORA       = true;
        this.EM_PRODUCAO = false;
    }
    
    
    /**
     * Private property
     */
    var url_base        = '_22140/api/programacao-estacao';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Filtro.prototype = {
        submit : function() {
            $timeout(function(){
                $('[type="sumbit"]').click();
            });
        },     
        consultar : function (args) {
            return $q(function(resolve){
                $ajax.get(url_base).then(function(response){
                    
                    gScope.Estacao.setValues(response).then(function(){    
                        resolve(response);
                    });
                });    
            });
        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(url_base + '/_22010?'+$httpParamSerializer(this)));
        },
        setData: function(data) {
            angular.extend(this, data);
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