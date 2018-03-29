angular
    .module('app')
    .factory('ServerEvent', ServerEvent);
    

	ServerEvent.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ServerEvent($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ServerEvent(data) {
        if (data) {
            this.setData(data);
        }

		gScope.SSE = this; 
        
        this.connection = null;
        this.SELECTED = {};
        this.CURRENT_RESPONSE = [];
        this.LAST_UPDATE = null;
    }

    
    ServerEvent.prototype.connect = function() {

        var that = this;
        
            var data = {};

            angular.copy(gScope.Filtro, data);

            if ( gScope.Filtro.DATA_TODOS ) {
                delete data.DATA_1;
                delete data.DATA_2;
            } else {
                data.DATA_1 = moment(data.DATA_1).format('DD.MM.YYYY');
                data.DATA_2 = moment(data.DATA_2).format('DD.MM.YYYY');                
            }

            data.PROGRAMACAO_STATUS = "< 3";
//            data.TALAO_STATUS = "< 2";
            data.ESTABELECIMENTO_ID = gScope.ConsultaEstabelecimento.ESTABELECIMENTO_ID;
            data.GP_ID              = gScope.ConsultaGp.GP_ID;
            data.UP_ID              = gScope.ConsultaUp.UP_ID;
            data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;

//            angular.extend(gScope.Filtro, data);        
        
        var url = '_22180/sse/taloes/composicao?'+$httpParamSerializer(data);
        
        if(typeof(EventSource) !== "undefined") {
        var evtSource = new EventSource(url);
        } else {
            showErro('Opss... Ocorreu uma falha!<br/>Seu navegador não possui suporte para eventos dinâmicos.<br><b>Recomendamos a utilização do Google Chrome.</b><br/>Entre em contato com suporte técnico para esclarecer demais dúvidas.');
        }

        evtSource.onmessage = function(e) {
            $rootScope.$apply(function(){
                var response = JSON.parse(event.data);
                
                if ( JSON.stringify(response) != JSON.stringify(that.CURRENT_RESPONSE) ) {
                    showSuccess('Os dados foram atualizados!');
                }
                
                gScope.Filtro.merge(response);
                
                
                that.LAST_UPDATE = Clock.DATETIME_SERVER;
            });
        };
        evtSource.onerror = function() {
          showErro('Opss... Ocorreu uma falha!<br/>Os eventos dinâmicos foram desconectados.<br/><b>Estamos tentando reconectar automaticamente...</b><br/>Se o erro pesistir, entre em contato com suporte técnico.');
        };
        
        this.connection = evtSource;

    };

    ServerEvent.prototype.close = function(item,action) {
        
        if ( this.connection != undefined ) {
        
            this.connection.close();    
            this.connection = undefined;
        }

    };    



    /**
     * Return the constructor function
     */
    return ServerEvent;
};