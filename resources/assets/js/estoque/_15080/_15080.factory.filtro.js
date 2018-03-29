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
        
        this.VISUALIZACAO = '1';
        this.NECESSIDADE = 'maior-que-zero';
        this.LOCALIZACAO_ID = null;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){

            $ajax.post('/_15080/api/produto-estoque-minimo',that).then(function(response){

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
                       
        
        gcCollection.merge(gScope.Produto.DADOS, response, [
            'ESTABELECIMENTO_ID',
            'LOCALIZACAO_ID',
            'PRODUTO_ID',
            'TAMANHO'
        ]);


        /**
         * Configura a dependência de talões
         */

        /**
         * Agrupa consumos para os talões
         */
        var produtos = gcCollection.groupBy(gScope.Produto.DADOS,[
            'PRODUTO_ID',
            'PRODUTO_DESCRICAO',
            'GRADE_ID',
            'TAMANHO',
            'TAMANHO_DESCRICAO',
            'PRODUTO_LOCALIZACAO_ID',
            'PRODUTO_LOCALIZACAO_DESCRICAO',
            'FAMILIA_ID',
            'FAMILIA_DESCRICAO'
        ],'ESTOQUES');

        gcCollection.merge(gScope.Produto.PRODUTOS, produtos, ['PRODUTO_ID','TAMANHO']);

        /**
         * Agrupa consumos para os talões
         */
        var localizacoes = gcCollection.groupBy(gScope.Produto.DADOS,[
            'LOCALIZACAO_ID',
            'LOCALIZACAO_DESCRICAO'
        ],'PRODUTOS');

        gcCollection.merge(gScope.Produto.LOCALIZACOES, localizacoes, ['LOCALIZACAO_ID']);

        angular.copy(gScope.Produto.LOCALIZACOES, gScope.Lote.LOCALIZACOES);

        /**
         * Agrupa consumos para os talões
         */
        var familias = gcCollection.groupBy(gScope.Produto.DADOS,[
            'FAMILIA_ID',
            'FAMILIA_DESCRICAO'
        ],'PRODUTOS');

        gcCollection.merge(gScope.Produto.FAMILIAS, familias, ['FAMILIA_ID']);

    };

    /**
     * Return the constructor function
     */
    return Filtro;
};