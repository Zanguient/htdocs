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
        
        this.DATA_1     = new Date(Clock.DATETIME_SERVER);
        this.DATA_2     = new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = true;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, erro){
    //        loading('.main-ctrl');     

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

            $ajax.post('/_22170/api/taloes/composicao',data,{progress: false}).then(function(response){

                that.merge(response);
                that.uriHistory();
                
                gScope.SSE.connect();
    //            loading('hide');
    
                $timeout(function(){
                    $('#filtrar-toggle[aria-expanded="true"]').click(); 
                });    
    
                resolve(response);

            },function(erro){
                reject(erro);
            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
        gScope.SSE.CURRENT_RESPONSE = angular.copy(response);

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

        arrayClean(response.CONSUMOS);
        arrayClean(response.HISTORICOS);
        arrayClean(response.TALOES);

        gcCollection.merge(gScope.TalaoConsumo.DADOS, response.CONSUMOS, 'CONSUMO_ID');      
        gcCollection.merge(gScope.TalaoHistorico.DADOS, response.HISTORICOS, 'ID');      
        gcCollection.merge(gScope.TalaoProduzir.DADOS, response.TALOES, 'TALAO_ID');      
        
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoConsumo.DADOS, 'TALAO_ID', 'CONSUMOS');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoHistorico.DADOS, 'PROGRAMACAO_ID', 'HISTORICOS');
        
        for ( var i in gScope.TalaoProduzir.DADOS ) {
            
            var talao = gScope.TalaoProduzir.DADOS[i];
            
            talao.CONSUMO_STATUS = '1';
            talao.ESTOQUE_STATUS = '1';
            
            for ( var y in talao.CONSUMOS ) {
                
                var consumo = talao.CONSUMOS[y];
                
                if ( talao.CONSUMO_STATUS == '1' && consumo.FAMILIA_ID == 6 && consumo.CONSUMO_STATUS == '0' ) {
                    talao.CONSUMO_STATUS = '0';
                }   
                
                if ( talao.ESTOQUE_STATUS == '1' && consumo.FAMILIA_ID != 6 && parseFloat(consumo.ESTOQUE_SALDO) < parseFloat(consumo.QUANTIDADE_SALDO) ) {
                    talao.ESTOQUE_STATUS = '0';
                }   
            }
        }
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_22170?'+$httpParamSerializer(this)));
        
    };


    /**
     * Return the constructor function
     */
    return Filtro;
};