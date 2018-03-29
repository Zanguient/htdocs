angular
    .module('app')
    .factory('CotaGgf', CotaGgf);
    

	CotaGgf.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaGgf($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaGgf(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        this.SELECTED = {};
		gScope.CotaGgf = this; 
        
    }
    
    CotaGgf.prototype.consultarDetalhe = function(item,tipo) {
            
        var that = this;
        
        that.SELECTED = item;
        
        var url = tipo == 'inv' ? 'ajuste-inventario' : 'ggf';
        $ajax.post('/_13030/api/cota/'+url+'/detalhe',item).then(function(response){

            that.SELECTED.ITENS = {};
            angular.extend(that.SELECTED.ITENS, response);
            
            that.ModalShow();
        });        
    };    
    
    var modal = $('#modal-cota-ggf-detalhe');
    
    CotaGgf.prototype.ModalShow = function(shown,hidden) {

        modal
            .modal('show')
        ;                         
        
        if ( shown ) {
            modal
                .one('shown.bs.modal', function(){
                    shown();
                })
            ;     
        }
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden();
                })
            ;              
        }
    };

    CotaGgf.prototype.ModalClose = function(hidden) {

        modal
            .modal('hide')
        ;
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                      
        }
    };
    
        
    
    /**
     * Return the constructor function
     */
    return CotaGgf;
};