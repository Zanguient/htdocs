angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$q',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $q, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Filtro = this; 
        
        this.TAB_ACTIVE = 'PRODUZIR';
        this.DATA_1     = new Date(Clock.DATETIME_SERVER);
        this.DATA_2     = new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = true;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
    //        loading('.main-ctrl');     

            if ( ! (parseInt(gScope.ConsultaEstacao.ESTACAO) > -1) ) {
                return false;
            }
            
            if ( that.TAB_ACTIVE == 'PRODUZIDO' ) {
                gScope.TalaoProduzido.consultar();
            } else {

                gScope.SSE.close();

                var data = {};

                angular.copy(that, data);

                if ( that.DATA_TODOS ) {
                    delete data.DATA_1;
                    delete data.DATA_2;
                }

                data.PROGRAMACAO_STATUS = "< 3";
                data.ESTABELECIMENTO_ID = gScope.ConsultaEstabelecimento.ESTABELECIMENTO_ID;
                data.GP_ID              = gScope.ConsultaGp.GP_ID;
                data.UP_ID              = gScope.ConsultaUp.UP_ID;
                data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;

                angular.extend(that, data);

                $ajax.post('/_22190/api/taloes/composicao',data).then(function(response){

                    that.merge(response);
                    that.uriHistory();

                    gScope.SSE.connect();

    //                $timeout(function(){
    //                    $('#filtrar-toggle[aria-expanded="true"]').click(); 
    //                });    

                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
    };
   
    
    Filtro.prototype.merge = function(response,auto) {
        
        if ( auto == undefined ) {
            gScope.SSE.UPDATED = true;
        }

        var arrayClean = function (response){
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
        };

        arrayClean(response.DETALHES);
        gcCollection.merge(gScope.TalaoDetalhe.DADOS, response.DETALHES, 'REMESSA_TALAO_DETALHE_ID');  
        
        arrayClean(response.CONSUMOS);
        gcCollection.merge(gScope.TalaoConsumo.DADOS, response.CONSUMOS, 'CONSUMO_ID');    
        
        arrayClean(response.COMPONENTES);
        gcCollection.merge(gScope.TalaoConsumo.COMPONENTE_DADOS, response.COMPONENTES, ['REMESSA_ID','REMESSA_TALAO_ID']);    
        
        arrayClean(response.HISTORICOS);   
        gcCollection.merge(gScope.TalaoHistorico.DADOS, response.HISTORICOS, 'ID');      
        
        if ( response.TALOES != undefined ) {
            arrayClean(response.TALOES);   
            gcCollection.merge(gScope.TalaoProduzir.DADOS, response.TALOES, 'TALAO_ID'); 
        }
        
        if ( response.TALAO != undefined ) {
            arrayClean(response.TALOES);   
            gcCollection.merge(gScope.TalaoProduzir.DADOS, response.TALAO, 'TALAO_ID',true); 
        }
        
        arrayClean(response.ALOCADOS);
        gcCollection.merge(gScope.TalaoConsumo.ALOCADOS, response.ALOCADOS, 'ID');
        
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoDetalhe.DADOS, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoConsumo.DADOS, 'TALAO_ID', 'CONSUMOS');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoConsumo.COMPONENTE_DADOS, 'TALAO_ID', 'COMPONENTES');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoHistorico.DADOS, 'PROGRAMACAO_ID', 'HISTORICOS');
        gcCollection.bind(gScope.TalaoConsumo.DADOS, gScope.TalaoConsumo.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');        
        
        for ( var i in gScope.TalaoProduzir.DADOS ) {
            
            var talao = gScope.TalaoProduzir.DADOS[i];
            
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
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_22190?'+$httpParamSerializer(this)));
        
    };


    /**
     * Return the constructor function
     */
    return Filtro;
};