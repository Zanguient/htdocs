angular
    .module('app')
    .factory('TalaoProduzido', TalaoProduzido);
    

	TalaoProduzido.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoProduzido($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzido(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoProduzido = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.FILTRO = {};
              
    }
    
    
    TalaoProduzido.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            
            var data = {};

            angular.copy(gScope.Filtro, data);

            if ( !data.DATA_TODOS ) {                
                data.DATA_PRODUCAO      = "BETWEEN '" + moment(data.DATA_1).format('YYYY.MM.DD') + "' AND '" + moment(data.DATA_2).format('YYYY.MM.DD') + "'";
            }
            
            delete data.DATA_1;
            delete data.DATA_2;
                
            data.PROGRAMACAO_STATUS = "= 3";
            data.GP_ID              = gScope.ConsultaGp.GP_ID;
            data.UP_ID              = gScope.ConsultaUp.UP_ID;
            data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;

            $ajax.post('/_22190/api/talao',data).then(function(response){

                that.merge(response);

            });
            
        });
    };
   

    TalaoProduzido.prototype.merge = function(response,auto) {


        var taloes = [];
        if ( response.TALAO != undefined ) {
            taloes = [response.TALAO];
        } else {
            taloes = response;
        }
        
        sanitizeJson(response);
        gcCollection.merge(this.DADOS, taloes, 'TALAO_ID');  
        

        if ( response.CONSUMOS != undefined ) {
            sanitizeJson(response.DETALHES);
            sanitizeJson(response.CONSUMOS);
            sanitizeJson(response.HISTORICOS);   
            sanitizeJson(response.ALOCADOS);

            gcCollection.bind(response.CONSUMOS, response.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');   
            gcCollection.bind(this.DADOS, response.DETALHES, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
            gcCollection.bind(this.DADOS, response.CONSUMOS, 'TALAO_ID', 'CONSUMOS');
            gcCollection.bind(this.DADOS, response.HISTORICOS, 'PROGRAMACAO_ID', 'HISTORICOS');     

            for ( var i in this.DADOS ) {

                var talao = this.DADOS[i];

                talao.CONSUMO_STATUS = '1';
                talao.ESTOQUE_STATUS = '1';


                for ( var y in talao.CONSUMOS ) {

                    var consumo = talao.CONSUMOS[y];


                    if ( talao.ESTOQUE_STATUS == '1' && consumo.ESTOQUE_STATUS == 0 ) {
                        talao.ESTOQUE_STATUS = '0';
                    }  

                    talao.ULTIMO_TALAO = true;
                    var i = 0;
                    for ( var y in talao.DETALHES ) {


                        var detalhe = talao.DETALHES[y];

                        if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                            i++;    
                        }

                        if ( i > 1 ) {
                            talao.ULTIMO_TALAO = false;
                            break;
                        }
                    }                
                }
            }        
        }
    };   
    

    /**
     * Return the constructor function
     */
    return TalaoProduzido;
};