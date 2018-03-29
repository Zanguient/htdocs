angular
    .module('app')
    .factory('CotaIncluir', CotaIncluir);
    

	CotaIncluir.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaIncluir($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaIncluir(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS         = {};
        this.INCLUINDO     = false;
		gScope.CotaIncluir = this; 
        
        this.COTA_BACKUP = {
            CCUSTO : null,
            CCONTABIL : null,
            VALOR : 0,
            BLOQUEIA : 0,
            NOTIFICA : 0,
            TOTALIZA : 0,
            DESTACA : 0
        };
        
        this.events();
    }
    
    CotaIncluir.prototype.gravar = function() {
        
        var that = this;
        
        that.DADOS.CCUSTO    = gScope.ConsultaCcusto.ID;
        that.DADOS.CCONTABIL = gScope.ConsultaCcontabil.CONTA;
        
        var dados = {
            DADOS : that.DADOS,
            FILTRO : gScope.Filtro,
            FILTRO_COTA : that.SELECTED
        };        
        $ajax.post('/_13030/api/cota/insert',dados).then(function(response){

        
            that.INCLUINDO = false;
            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            that.ModalClose();
        });        
    };    
    
    CotaIncluir.prototype.cancelar = function() {
        
        var that = this;
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente cancelar esta operação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){        
        
                    that.INCLUINDO = false;
                    that.ModalClose();

                });
            }}]     
        );      
    };
    
    CotaIncluir.prototype.incluir = function() {
        
        angular.extend(this.DADOS, this.COTA_BACKUP);
        this.INCLUINDO = true;
        this.ModalShow(null,function(){
            gScope.ConsultaCcusto.apagar();
            gScope.ConsultaCcontabil.apagar();
        });
    };

    
    var modal = $('#modal-cota-incluir');
    
    CotaIncluir.prototype.ModalShow = function(shown,hidden) {

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

    CotaIncluir.prototype.ModalClose = function(hidden) {

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
    
    CotaIncluir.prototype.events = function($event) {
        var that = this;
        var cancel_bf_unload = false;
        //
        $(document).on('click','[type="submit"]',function(e) {
            var form = $(this).closest('form');
            var action = $(form).attr('action') == undefined ? '' : $(form).attr('action');

            if ( action != '' ) {
                cancel_bf_unload = true;
            }
        });

        var bf_load_timeout;

        function warning() {
            if ( that.INCLUINDO && cancel_bf_unload == false ) {
                return 'oi';
            }
        }

        function noTimeout() {
            clearTimeout(bf_load_timeout);
        }

        window.onbeforeunload = warning;
        window.unload = noTimeout;         
         
    };
        
    
    /**
     * Return the constructor function
     */
    return CotaIncluir;
};