angular
    .module('app')
    .factory('CotaExtra', CotaExtra);
    

	CotaExtra.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaExtra($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaExtra(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        
		gScope.CotaExtra = this; 
        

        
        this.events();
    }
    
    CotaExtra.prototype.gravar = function() {
        
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
        $ajax.post('/_13030/api/cota/extra/insert',dados).then(function(response){

            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
            
            that.reset();
        });        
    };    
    
    CotaExtra.prototype.excluir = function(item) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta cota extra?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : item,
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : gScope.Cota.SELECTED
                    };

                    $ajax.post('/_13030/api/cota/extra/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
                    });

                });
            }}]     
        );        
        
    };
    
    CotaExtra.prototype.reset = function() {
        
        var dados = {
            VALOR : null,
            OBSERVACAO : null
        };
        
        angular.extend(this.DADOS, dados);
    
    };
    
    CotaExtra.prototype.events = function($event) {
    
    };
        
    
    /**
     * Return the constructor function
     */
    return CotaExtra;
};