angular
    .module('app')
    .factory('CotaReducao', CotaReducao);
    

	CotaReducao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaReducao($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaReducao(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        
		gScope.CotaReducao = this; 
        

        
        this.events();
    }
    
    CotaReducao.prototype.gravar = function() {
        
        var dados = {
            ID : gScope.Cota.SELECTED.ID
        };
        
        angular.extend(dados, this.DADOS);
        
        
        var that = this;
        var dados = {
            DADOS : dados,
            FILTRO : gScope.Filtro,
            FILTRO_COTA : gScope.Cota.SELECTED
        };        
        $ajax.post('/_13030/api/cota/reducao/insert',dados).then(function(response){

            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
            
            that.reset();
        });        
    };    
    
    CotaReducao.prototype.excluir = function(item) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta redução?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : item,
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : gScope.Cota.SELECTED
                    };

                    $ajax.post('/_13030/api/cota/reducao/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
                    });

                });
            }}]     
        );        
        
    };
    
    CotaReducao.prototype.reset = function() {
        
        var dados = {
            VALOR : null,
            OBSERVACAO : null
        };
        
        angular.extend(this.DADOS, dados);
    
    };
    
    CotaReducao.prototype.events = function($event) {
    
    };
        
    
    /**
     * Return the constructor function
     */
    return CotaReducao;
};