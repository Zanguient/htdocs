angular
    .module('app')
    .factory('Gp', Gp);
    

	Gp.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Gp($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Gp(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Gp = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.BARRAS       = '';
        this.OPERACAO_ID  = 7;
        this.VALOR_EXT    = 1;
        this.ABORT        = true;
        this.VERIFICAR_UP = true;
        this.AUTENTICADO  = false;
        this.CALLBACK     = null;
        
    }
    
    
    Gp.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            $ajax.post('/_22030/api/gp/autenticacao',that)
                .then(function(response) {

                    that.SELECTED = response;
                    that.AUTENTICADO  = true;
                    that.close();

                    that.CALLBACK();
                    that.CALLBACK = null;
                    resolve(that.SELECTED);
                },function(erro){
                    that.BARRAS = '';
                    modal.find('input:focusable').first().focus();
                    
                    reject(erro);
                }
            );        
        });
    };
   
    Gp.prototype.open = function(callback) {
        
        var that = this;
        if ( isEmpty(this.SELECTED) ) {        
            this.show(function(){
                
                that.CALLBACK = callback;
                
                modal.find('input:focusable').first().focus();
            },function(){
                that.BARRAS = '';
            });
        } else {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja sair da sessão do grupo de produção <b>' + that.SELECTED.GP_DESCRICAO + '</b>?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){

                        that.SELECTED = {};
                        that.AUTENTICADO = false;
                    });
                }}]     
            );
        }
    };
    
    
    Gp.prototype.logoff = function() {
        
        var that = this;

        that.SELECTED = {};
        that.AUTENTICADO = false;
    };
    
    

    var modal = $('#modal-gp');
    
    Gp.prototype.show = function(shown,hidden) {

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

    Gp.prototype.close = function(hidden) {

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
    return Gp;
};