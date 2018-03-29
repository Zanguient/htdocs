angular
    .module('app')
    .factory('ConsumoBaixadoTalao', ConsumoBaixadoTalao);
    

	ConsumoBaixadoTalao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoTalao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoTalao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixadoTalao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixadoTalao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
            
            gScope.ConsumoBaixadoTransacao.consultar();
        }

    };
    
    ConsumoBaixadoTalao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixadoTalao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Balanca.open();

                break;

        }
    };    
    
    ConsumoBaixadoTalao.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixadoFiltro,
            FILTRO_TRANSACAO: {ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID},
            DADOS: {
                ITENS : [that.SELECTED],
                QUANTIDADE : that.QUANTIDADE,
                PECA_BARRAS : that.PECA_BARRAS
            }
        };
        
        var input = null;
        
        if ( that.PECA_BARRAS != undefined && that.PECA_BARRAS.length > 0 ) {
            that.QUANTIDADE = null;   
            input = that.Modal.inputPeca();
        }
        if ( that.QUANTIDADE != undefined && parseFloat(that.QUANTIDADE) > 0 ) {
            that.PECA_BARRAS = '';
            input = that.Modal.inputQuantidade();
        }

        that.Modal.enableButton(false);
        
        $ajax.post('/_22160/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.ConsumoBaixadoFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixadoTalao.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    
    ConsumoBaixadoTalao.prototype.imprimirEtiqueta = function() {

        if ( this.SELECTED != undefined && this.SELECTED != {} ) {

            $ajax.post('/_22160/api/etiqueta',{ITENS:[this.SELECTED]}).then(function(response){
                postprint(response);
            });        
        }
    };   

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoTalao;
};