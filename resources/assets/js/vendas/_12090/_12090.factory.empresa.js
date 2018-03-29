angular
    .module('app')
    .factory('Empresa', Empresa);
    

	Empresa.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Empresa($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Empresa(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Empresa = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.ITENS = [];
        this.ALTERANDO = false;
        this.COTA_BACKUP = {};
        
        this.events();
    }
    
    Empresa.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            $ajax.post('/_12090/api/empresa',that.SELECTED).then(function(response){

                angular.extend(that.SELECTED, response);
                
                resolve(that.SELECTED);
            },function(erro){
                reject(erro);
            });
        });
    };
        
    Empresa.prototype.open = function(empresa) {
          
        var that = this;
        
        this.consultar().then(function(){
            
            window.history.replaceState('', '', encodeURI(urlhost + '/_12090?EMPRESA_ID='+that.SELECTED.EMPRESA_ID));
            
            that.modalOpen(null,function(){
                
                window.history.replaceState('', '', encodeURI(urlhost + '/_12090'));
                
            });
        },function(erro){
            showErro('Ocorreu uma falha ao consultar as informações. ' + erro);
        });
    };    
    
    
    Empresa.prototype.consultarModelosPreco = function() {
        var that = this;
    
        $ajax.post('/_12090/api/modelos/preco',that.SELECTED).then(function(response){

            if ( that.SELECTED.MODELOS_PRECO == undefined ) {
                that.SELECTED.MODELOS_PRECO = [];
            }
            gcCollection.merge(that.SELECTED.MODELOS_PRECO,response,['TIPO','ID','TAMANHO']);
            
        });        
    };    
    
    Empresa.prototype.empresaRepresentate = function() {
        
        var that = this;
        
        var ret = true;
        
        if ( gScope.REPRESENTANTE_ID > 0 ) {
            if ( parseInt(that.SELECTED.REPRESENTANTE_ID) != parseInt(gScope.REPRESENTANTE_ID) ) {
                ret = false;
            }
        }
        
        return ret;
    };    
    
    Empresa.prototype.cancelar = function() {
        
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

    Empresa.prototype.alterar = function() {
        this.ALTERANDO = true;
        angular.copy(this.SELECTED, this.COTA_BACKUP);
    };
    
    Empresa.prototype.excluir = function() {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta empresa?',
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

                    $ajax.post('/_13030/api/empresa/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(that.SELECTED, response.DATA_RETURN.COTA);

                        that.ModalClose();
                    });

                });
            }}]     
        );        
        
    };
    
    Empresa.prototype.dblPick = function(item,action) {
        
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

    
    Empresa.prototype.pick = function(empresa,setfocus) {
        
        var that = this;

        if ( empresa != undefined ) {
        
            this.SELECTED = empresa;

            gScope.Filtro.COTA_ID = empresa.ID;
            gScope.Filtro.uriHistory();
            
            if ( setfocus ) {
                that.setFocus();
            }
        }

    };  
    
    var modal = $('#modal-empresa');
    
    Empresa.prototype.modalOpen = function(shown,hidden) {

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

    Empresa.prototype.modalClose = function(hidden) {

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
    
    Empresa.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Empresa.prototype.events = function($event) {
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
    return Empresa;
};