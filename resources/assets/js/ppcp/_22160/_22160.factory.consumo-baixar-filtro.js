angular
    .module('app')
    .factory('ConsumoBaixarFiltro', ConsumoBaixarFiltro);
    

	ConsumoBaixarFiltro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarFiltro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarFiltro(data) {
        if (data) {
            this.setData(data);
        }
        
        this.CONSUMO_STATUS = "= '0'";
        
		gScope.ConsumoBaixarFiltro = this; 
        
    }
    
    ConsumoBaixarFiltro.prototype.consultar = function() {
        
        var that = this;
            
        
        return $q(function(resolve,reject){

            $ajax.post('/_22160/api/consumo-baixar',that,{progress: false}).then(function(response){

                that.merge(response);
                
                resolve(response);

            },function(e){
                reject(e);
            });

        });
    };
   
    
    ConsumoBaixarFiltro.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.ConsumoBaixarTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixarProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixarTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixarProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return ConsumoBaixarFiltro;
};