angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
        this.CODIGO_BARRAS = '';
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){

            $ajax.post('/_15090/api/conferencia/itens',that).then(function(response){

                that.merge(response);

                resolve(response);
            },function(){
                reject(reject);
            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        for ( var i in response ) {
            var item = response[i];
            
            for (var k in item){
                if (item.hasOwnProperty(k)) {
                    
                    if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                        item[k] = parseFloat(item[k]);
                    }
                }
            }            
        }
        
        gcCollection.merge(gScope.Conferencia.DADOS, response, [
            'PRODUTO_ID',
            'TAMANHO',
            'PECA_ID'
        ]);

        for ( var i in gScope.Conferencia.DADOS ) {
            var item = gScope.Conferencia.DADOS[i];
            
            item.CONFERIR = item.CONFERENCIA;
        }

    };

    /**
     * Return the constructor function
     */
    return Filtro;
};