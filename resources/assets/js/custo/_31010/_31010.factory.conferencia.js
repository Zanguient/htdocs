angular
    .module('app')
    .factory('Modelo', Modelo);
    

	Modelo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Modelo($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Modelo(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Modelo = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'MODELO_DESCRICAO';
        this.SELECTED = [];
    }

    Modelo.prototype.consultar = function() {
        var that = this;

        var ds = {
                ID : 0
            };

        $ajax.post('/_31010/Consultar',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;

                var grupos = gcCollection.groupBy(response, [
                    'MODELO_CODIGO',
                    'MODELO_DESCRICAO'
                ], 'COR'); 
                
                gcCollection.merge(that.ITENS, grupos, ['COR_CODIGO', 'COR_DESCRICAO']);

                console.log(that.ITENS);                  
            }
        );
    }

    Modelo.prototype = {
        selectionar : function (modelo) {
        this.SELECTED = modelo;
    }

    /**
     * Return the constructor function
     */
    return Modelo;
};