angular
    .module('app')
    .factory('CotaPeriodo', CotaPeriodo);
    

	CotaPeriodo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaPeriodo($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaPeriodo(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        
		gScope.CotaPeriodo = this; 
        
    }
    
    CotaPeriodo.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        
        this.DATAHORA = {
            DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_22160/api/consumo-baixado',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    CotaPeriodo.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixadoTalao.DADOS, [
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
        
        
        gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return CotaPeriodo;
};