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
        this.UPDATED = true;
        this.LAST_UPDATE = null;
    }

    
    ServerEvent.prototype.connect = function() {

        var that = this;
        var url = '_22190/sse/taloes/composicao?'+$httpParamSerializer(gScope.Filtro);
        
        if(typeof(EventSource) !== "undefined") {
        var evtSource = new EventSource(url);
        } else {
            showErro('Opss... Ocorreu uma falha!<br/>Seu navegador não possui suporte para eventos dinâmicos.<br><b>Recomendamos a utilização do Google Chrome.</b><br/>Entre em contato com suporte técnico para esclarecer demais dúvidas.');
        }

        evtSource.onmessage = function(e) {
            $rootScope.$apply(function(){
                var response = JSON.parse(event.data);
                
                if ( !that.UPDATED ) {
                    showSuccess('Os dados foram atualizados!');
                }
                
                gScope.Filtro.merge(response,true);
                
                
                that.LAST_UPDATE = Clock.DATETIME_SERVER;
                that.UPDATED     = false;
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