angular
    .module('app')
    .factory('ConsumoBaixadoTransacao', ConsumoBaixadoTransacao);
    

	ConsumoBaixadoTransacao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoTransacao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoTransacao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixadoTransacao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixadoTransacao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }
    };
    
    ConsumoBaixadoTransacao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Balanca.open();

                break;

        }
    };    
    
    ConsumoBaixadoTransacao.prototype.consultar = function() {
        
        var that = this;
        
        $ajax.post('/_22160/api/consumo-baixado/transacao',gScope.ConsumoBaixadoTalao.SELECTED,{progress: false}).then(function(response){
            
            gcCollection.merge(gScope.ConsumoBaixadoTransacao.DADOS, response, ['TIPO','CONSUMO_ID']);
            
        });
    };    
    
    ConsumoBaixadoTransacao.prototype.delete = function(transacao) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta transação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){
                    
                    var dados = {
                        FILTRO: gScope.ConsumoBaixadoFiltro,
                        FILTRO_TRANSACAO: gScope.ConsumoBaixadoTalao.SELECTED,
                        DADOS: {
                            ITENS : [transacao]
                        }
                    };        

                    $ajax.post('/_22160/api/consumo-baixado/transacao/delete',dados,{progress: false}).then(function(response){

                        gScope.ConsumoBaixadoFiltro.merge(response.DATA_RETURN.DADOS);
                        gcCollection.merge(gScope.ConsumoBaixadoTransacao.DADOS, response.DATA_RETURN.TRANSACOES, ['TIPO','CONSUMO_ID']);

                    });
                    
                });
            }}]     
        );        


    };    

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoTransacao;
};