angular
    .module('app')
    .factory('Cota', Cota);
    

	Cota.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Cota($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Cota(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.SELECTED = {};
        this.ITENS = [];
        this.ALTERANDO = false;
		gScope.Cota = this; 
        this.COTA_BACKUP = {};
        
        this.events();
    }
    
    Cota.prototype.consultar = function() {
        
        var that = this;
        
        $ajax.post('/_13030/api/cota',that.SELECTED,{progress:false}).then(function(response){
            
            angular.extend(that.SELECTED, response);
        });
    };
        
    Cota.prototype.gravarAlteracao = function() {
        var that = this;
        var dados = {
            DADOS : {
                ITENS : [that.SELECTED]
            },
            FILTRO : gScope.Filtro,
            FILTRO_COTA : that.SELECTED
        };        
        $ajax.post('/_13030/api/cota/update',dados).then(function(response){

        
            that.ALTERANDO = false;
            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(that.SELECTED, response.DATA_RETURN.COTA);
        });        
    };    
    
    Cota.prototype.cancelar = function() {
        
        var that = this;
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente cancelar esta operação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){        
        
                    that.ALTERANDO = false;
                    angular.extend(that.SELECTED, that.COTA_BACKUP);

                });
            }}]     
        );      
    };

    Cota.prototype.alterar = function() {
        this.ALTERANDO = true;
        angular.copy(this.SELECTED, this.COTA_BACKUP);
    };
    
    Cota.prototype.excluir = function() {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta cota?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : {
                            ITENS : [that.SELECTED]
                        },
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : that.SELECTED
                    };

                    $ajax.post('/_13030/api/cota/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(that.SELECTED, response.DATA_RETURN.COTA);

                        that.ModalClose();
                    });

                });
            }}]     
        );        
        
    };
    
    Cota.prototype.dblPick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;
            
            gScope.Filtro.COTA_ID = item.ID;
            gScope.Filtro.COTA_OPEN = 1;
            gScope.Filtro.uriHistory();
            
            that.consultar();
            that.ModalShow(null,function(){   
                that.ALTERANDO = false;
                delete gScope.Filtro.COTA_OPEN;
                gScope.Filtro.uriHistory();
            });

        }

    };    

    
    Cota.prototype.pick = function(cota,setfocus) {
        
        var that = this;

        if ( cota != undefined ) {
        
            this.SELECTED = cota;

            gScope.Filtro.COTA_ID = cota.ID;
            gScope.Filtro.uriHistory();
            
            if ( setfocus ) {
                that.setFocus();
            }
        }

    };  
    
    var modal = $('#modal-cota');
    
    Cota.prototype.ModalShow = function(shown,hidden) {

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

    Cota.prototype.ModalClose = function(hidden) {

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
    
    Cota.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Cota.prototype.events = function($event) {
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
            if ( that.ALTERANDO && cancel_bf_unload == false ) {
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
    return Cota;
};