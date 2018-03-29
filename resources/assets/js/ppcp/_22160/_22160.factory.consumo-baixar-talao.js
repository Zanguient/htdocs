angular
    .module('app')
    .factory('ConsumoBaixarTalao', ConsumoBaixarTalao);
    

	ConsumoBaixarTalao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarTalao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarTalao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixarTalao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixarTalao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    ConsumoBaixarTalao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixarTalao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.ConsumoBaixarBalanca.open();

                break;

        }
    };    
    
    ConsumoBaixarTalao.prototype.confirm = function () {

        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
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

            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixarTalao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixarTalao.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    

    /**
     * Return the constructor function
     */
    return ConsumoBaixarTalao;
};