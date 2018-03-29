angular
    .module('app')
    .factory('Operador', Operador);
    

	Operador.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Operador($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Operador(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Operador = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.BARRAS       = '';
        this.OPERACAO_ID  = 7;
        this.VALOR_EXT    = 1;
        this.ABORT        = true;
        this.VERIFICAR_UP = true;
        this.AUTENTICADO  = false;
        
    }
    
    
    Operador.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            $ajax.post('/_22050/autenticacao',that)
                .then(function(response) {

                    that.SELECTED = response[0];
                    that.AUTENTICADO  = true;
                    that.close();

                    resolve(that.SELECTED);
                },function(erro){
                    that.BARRAS = '';
                    modal.find('input:focusable').first().focus();
                    
                    reject(erro);
                }
            );        
        });
    };
   
    Operador.prototype.open = function() {
        
        var that = this;
        if ( isEmpty(this.SELECTED) ) {        
            this.show(function(){
                modal.find('input:focusable').first().focus();
            },function(){
                that.BARRAS = '';
            });
        } else {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja sair da sessão do operador <b>' + that.SELECTED.OPERADOR_NOME + '</b>?',
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

    var modal = $('#modal-operador');
    
    Operador.prototype.show = function(shown,hidden) {

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

    Operador.prototype.close = function(hidden) {

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
    return Operador;
};