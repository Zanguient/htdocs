angular
    .module('app')
    .factory('CotaCcusto', CotaCcusto);
    

	CotaCcusto.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaCcusto($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaCcusto(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        
		gScope.CotaCcusto = this; 
        
    }
    
    CotaCcusto.prototype.consultar = function() {
        
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
   
    
    CotaCcusto.prototype.merge = function(response) {
        
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
    
    CotaCcusto.prototype.checkVisibility = function(ccusto) {
        
        var periodos = ccusto.PERIODOS;
        
        ccusto.VISIBLE = false;
        
        for ( var i in periodos ) {
            var periodo = periodos[i];
            
            if ( !ccusto.VISIBLE && periodo.FILTERED != undefined && periodo.FILTERED.length > 0 ) {
                ccusto.VISIBLE = true;
                break;
            }
        }
        
    };
    
    
    CotaCcusto.prototype.toggleExpand = function(type) {
        
        
        var that = this;
        var bool = null;
        
        if ( type != undefined ) {
            bool = type;
        } else {
            if ( gScope.Filtro.EXPANDED == undefined || gScope.Filtro.EXPANDED == null || !gScope.Filtro.EXPANDED ) {
                bool = true;
            } else {
                bool = false;
            }
        }
        
        gScope.Filtro.EXPANDED = bool;
        
        for ( var i in that.DADOS ) {
            var ccusto = that.DADOS[i];
            
            ccusto.OPENED = bool;
            ccusto.VISIBLE = bool;
            
            for ( var j in ccusto.PERIODOS ) {
                var periodo = ccusto.PERIODOS[j];
                
                periodo.OPENED = bool;
            }
        }
        
    };

    /**
     * Return the constructor function
     */
    return CotaCcusto;
};