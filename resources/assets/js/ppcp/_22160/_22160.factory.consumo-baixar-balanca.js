angular
    .module('app')
    .factory('ConsumoBaixarBalanca', ConsumoBaixarBalanca);
    

	ConsumoBaixarBalanca.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarBalanca($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarBalanca(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixarBalanca = this; 
        
        var that = this;
        
        this.DADOS           = [];
        this.SELECTED        = {};
        this.PESO            = null;
        this.PESO_AUTOMATICO = false;
        this.FILTRO          = '';
        this.ITENS_BAIXAR    = [];
        this.OPERADOR_BARRAS = '';
        this.OPERADOR_NOME   = '';
        this.OPERADOR_ID     = 0;
        
        var balanca_timeout = null;     
        
        $('.gc-print-recebe-peso').click(function(){
                        
            var str = $(this).val();
            var res = str.replace(",", ".");                        
            var value = parseFloat(res);
            
            var changed = false;

            if ( that.PESO != value ) {
                changed = true;
            }
                
            $rootScope.$apply(function(){
                                
                that.PESO_AUTOMATICO = true;
                that.PESO = parseFloat(value.toFixed(4));
                
                if ( changed ) {
                    that.setItens();
                }
            });
            
            clearTimeout( balanca_timeout );

            balanca_timeout = setTimeout(function(){
                $rootScope.$apply(function(){                
                    that.PESO_AUTOMATICO = false;
                    that.PESO = null;
                    
                    if ( changed ) {
                        that.setItens();
                    }                    
                });
            },3000);            
        });        
        
    }
    
    var modal = $('#modal-balanca');
    var modalOperador = $('#modal-autenticar-operador');
    
    ConsumoBaixarBalanca.prototype.open = function() {
        
        var that = this;
        if ( gScope.ConsumoBaixarTalao.SELECTED != undefined && gScope.ConsumoBaixarTalao.SELECTED != {} ) {
            
            this.SELECTED = gScope.ConsumoBaixarTalao.SELECTED;
            comunicacao.conectar();
            this.show(function(){
                $('#balanca-quantidade-baixar:focusable').first().focus();
            },function(){
                $('.table-container.table-taloes tr.selected').first().focus();                
                comunicacao.desconectar();
                that.PESO_AUTOMATICO = false;
                that.PESO = null;                
            });
        }
        
    };
  

    ConsumoBaixarBalanca.prototype.confirm = function () {

        var that = this;

        var dados = {
            FILTRO   : gScope.ConsumoBaixarFiltro,
            OPERADOR : {NOME: that.OPERADOR_NOME , ID: that.OPERADOR_ID},
            DADOS    : {
               ITENS : that.ITENS_BAIXAR,
               PESO  : that.PESO
            }
        };
        
        
        that.enableButton(false);
        
        $ajax.post('/_22160/api/consumo-baixar/post',dados,{complete: function(){
                
            that.enableButton(true);
            
        }}).then(function(response){
        
            postprint(response.ETIQUETAS);        
        
            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN);
            that.close();
            
        });        
    };

    ConsumoBaixarBalanca.prototype.operadorButton = function(bool) {
        modalOperador.find('button').prop('disabled',!bool);
    };

    ConsumoBaixarBalanca.prototype.altenticarOperador = function (flag) {
        var that = this;

        var dados = {
            COD_BARRAS : that.OPERADOR_BARRAS
        };
        
        that.operadorButton(false);

        $ajax.post('/_22160/api/operador',dados,{complete: function(){
                
            that.operadorButton(true);
            
        }}).then(function(response){
            
            if(Object.keys(response).length > 0){

                if(response.PERMICAO == 1){

                    that.OPERADOR_BARRAS = '';
                    that.OPERADOR_ID     = response.CODIGO;
                    that.OPERADOR_NOME   = response.NOME;

                    modalOperador.modal('hide');

                    if(flag = 1){
                        that.confirm();
                    }

                }else{
                    showErro('Operador não tem permissão.<br>' + response.DESCRICAO);
                    that.OPERADOR_BARRAS = '';
                    modalOperador.find('input').focus();   
                }

            }else{
                showErro('Operador não encontrado!');
                that.OPERADOR_BARRAS = '';
                modalOperador.find('input').focus();
            }           
            
        });    
    };

    ConsumoBaixarBalanca.prototype.modalOperador = function () {
        that.OPERADOR_ID     = 0;
        that.OPERADOR_BARRAS = '';
        that.OPERADOR_NOME   = '';
        
        modalOperador.modal();

        setTimeout(function(){
            modalOperador.find('input').focus();
        },300); 
    };

    ConsumoBaixarBalanca.prototype.setItens = function () {
        
        this.ITENS_BAIXAR = [];
        var array = this.ITENS_BAIXAR;
        
        if ( this.SELECTED.FILTERED == undefined ) {
            array.push(this.SELECTED);
        } else {
            
            var quantidade = 0;
            for ( var i in this.SELECTED.FILTERED ) {
                
                var item = this.SELECTED.FILTERED[i];
                
                quantidade += item.QUANTIDADE_SALDO;
                
                if ( quantidade <= (this.PESO + item.QUANTIDADE_SALDO) ) {
                    array.push(item);                    
                } else {
                    break;
                }
            }
        }
    };  

    ConsumoBaixarBalanca.prototype.show = function(shown,hidden) {

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

    ConsumoBaixarBalanca.prototype.close = function(hidden) {

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
    
    ConsumoBaixarBalanca.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    ConsumoBaixarBalanca.prototype.enableButton = function(bool) {
        modal.find('button').prop('disabled',!bool);
    };
 
       
    function isNumber(o) {
        return o == '' || !isNaN(o - 0);
    }    
    
    
    var time = null;
    var comunicacao = {
        conectar: function (){
            
            console.log('conectou');
            $( ".gc-print-open-com" )
                .trigger( "click" )
            ;

            $( ".gc-print-set-config" )
                .trigger( "click" )
            ;

            time = setInterval(function(){ 
                $( ".gc-print-set-config" )
                    .trigger( "click" )
                ;
            },1000);
        },
        desconectar: function (){
            
            console.log('desconectou');
            $( ".gc-print-close-com" )
                .trigger( "click" )
            ;
            clearInterval(time);
        }
    }    
    
    /**
     * Return the constructor function
     */
    return ConsumoBaixarBalanca;
};