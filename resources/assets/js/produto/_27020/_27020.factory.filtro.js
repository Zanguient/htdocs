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
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
        
//        this.DATA_1 = new Date(Clock.DATETIME_SERVER);
//        this.DATA_2 = new Date(Clock.DATETIME_SERVER);

		gScope.Filtro = this; 
        
    }
    
    Filtro.prototype.consultar = function(progress) {
        
        var that = this;
        
        return $q(function(resolve){
    //        loading('.main-ctrl');     

//            this.DATAHORA = {
//                DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
//                DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
//            };

            $ajax.post('/_13030/api/cotas',that,{progress: progress == undefined ? false : progress}).then(function(response){

                that.merge(response);
                resolve(response);
    //            loading('hide');

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

        gcCollection.merge(gScope.Cota.DADOS, response, 'ID');      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var periodos = gcCollection.groupBy(gScope.Cota.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO',
            'MES',
            'ANO',
            'PERIODO_DESCRICAO'
        ], 'CCONTABEIS',function(periodo,ccontabil){

            periodo.VALOR     == undefined ? periodo.VALOR     = 0 : '';
            periodo.EXTRA     == undefined ? periodo.EXTRA     = 0 : '';
            periodo.TOTAL     == undefined ? periodo.TOTAL     = 0 : '';
            periodo.OUTROS    == undefined ? periodo.OUTROS    = 0 : '';
            periodo.UTIL      == undefined ? periodo.UTIL      = 0 : '';
//            periodo.PERC_UTIL == undefined ? periodo.PERC_UTIL = 0 : '';
            periodo.SALDO     == undefined ? periodo.SALDO     = 0 : '';

            periodo.VALOR     += ccontabil.VALOR    ;
            periodo.EXTRA     += ccontabil.EXTRA    ;
            periodo.TOTAL     += ccontabil.TOTAL    ;
            periodo.OUTROS    += ccontabil.OUTROS   ;
            periodo.UTIL      += ccontabil.UTIL     ;
//            periodo.PERC_UTIL += ccontabil.PERC_UTIL;
            periodo.SALDO     += ccontabil.SALDO    ;        


            if ( periodo.TOTAL > 0 ) {
                periodo.PERC_UTIL = ((1-(periodo.SALDO/periodo.TOTAL))*100);
            } else {
                if ( periodo.TOTAL == 0 && periodo.SALDO < 0 ) {
                    periodo.PERC_UTIL = 100;
                } else {
                    periodo.PERC_UTIL = 0;  
                }
            }

//            if ( (periodo.VALOR + periodo.EXTRA) = 0 && periodo.SALDO )
//            IIF(A.VALOR+A.EXTRA = 0 AND A.SALDO < 0, 100, 0))

        });
        
        gcCollection.merge(gScope.CotaPeriodo.DADOS, periodos, ['CCUSTO','MES','ANO']);
        
        /////
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var ccustos = gcCollection.groupBy(gScope.CotaPeriodo.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO'
        ], 'PERIODOS',function(ccusto,periodo){

            ccusto.VALOR     == undefined ? ccusto.VALOR     = 0 : '';
            ccusto.EXTRA     == undefined ? ccusto.EXTRA     = 0 : '';
            ccusto.TOTAL     == undefined ? ccusto.TOTAL     = 0 : '';
            ccusto.OUTROS    == undefined ? ccusto.OUTROS    = 0 : '';
            ccusto.UTIL      == undefined ? ccusto.UTIL      = 0 : '';
//            ccusto.PERC_UTIL == undefined ? ccusto.PERC_UTIL = 0 : '';
            ccusto.SALDO     == undefined ? ccusto.SALDO     = 0 : '';     

            ccusto.VALOR     += periodo.VALOR    ;
            ccusto.EXTRA     += periodo.EXTRA    ;
            ccusto.TOTAL     += periodo.TOTAL    ;
            ccusto.OUTROS    += periodo.OUTROS   ;
            ccusto.UTIL      += periodo.UTIL     ;
//            ccusto.PERC_UTIL += periodo.PERC_UTIL;
            ccusto.SALDO     += periodo.SALDO    ;        

            if ( ccusto.TOTAL > 0 ) {
                ccusto.PERC_UTIL = ((1-(ccusto.SALDO/ccusto.TOTAL))*100);
            } else {
                if ( ccusto.TOTAL == 0 && ccusto.SALDO < 0 ) {
                    ccusto.PERC_UTIL = 100;
                } else {
                    ccusto.PERC_UTIL = 0;  
                }
            }
        });
        
        gcCollection.merge(gScope.CotaCcusto.DADOS, ccustos, ['CCUSTO','MES','ANO','CCONTABIL']);
        
        /////
                
        
//        console.log(gScope.CotaCcusto.DADOS);
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_13030/ng?'+$httpParamSerializer(this)));        
    };    

    /**
     * Return the constructor function
     */
    return Filtro;
};