angular
    .module('app')
    .factory('Talao', Talao);
    

	Talao.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gcObject',
        'gScope'
    ];

function Talao($ajax, $q, $rootScope, $timeout, gcCollection, gcObject, gScope) {

    /**
     * Constructor, with class name
     */
    function Talao(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Talao = this; 
        
        this.DADOS = [];
        this.FILTERED = [];
        this.SELECTED = {};
    }
    
    var data = {};
    
    Talao.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        

        angular.copy(that, data);
        
        if ( this.DATA_TODOS ) {
            delete data.DATA_1;
            delete data.DATA_2;
        }
        
        data.PROGRAMACAO_STATUS = "< 3";
        data.GP_ID              = gScope.ConsultaGp.GP_ID;
        data.UP_ID              = gScope.ConsultaUp.UP_ID;
        data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;
        
        $ajax.post('/_22190/api/talao',data,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    Talao.prototype.consultarComposicao = function() {

        var that = this;
        
        return $q(function(resolve,reject){
        
            $ajax.post('/_22190/api/talao/composicao',that.SELECTED).then(function(response){

                that.mergeComposicao(response);
            
                
                resolve(response);
            },function(erro){
                reject(erro);
            });
        });
    };    
    
    Talao.prototype.mergeComposicao = function(response) {
         
  
        sanitizeJson(response.TALAO);
        
        var taloes = [];

        if ( response.CONSUMOS != undefined ) {
            sanitizeJson(response.DETALHES);
            sanitizeJson(response.CONSUMOS);
            sanitizeJson(response.HISTORICOS);   
            sanitizeJson(response.ALOCADOS);
            sanitizeJson(response.COMPONENTES);

            gcCollection.bind(response.CONSUMOS, response.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');   
            gcObject.bind(response.TALAO, response.DETALHES, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
            gcObject.bind(response.TALAO, response.CONSUMOS, 'TALAO_ID', 'CONSUMOS');
            gcObject.bind(response.TALAO, response.COMPONENTES, 'TALAO_ID', 'COMPONENTES');
            gcObject.bind(response.TALAO, response.HISTORICOS, 'PROGRAMACAO_ID', 'HISTORICOS');     

            for ( var i in taloes ) {

                var talao = taloes[i];

                talao.CONSUMO_STATUS = '1';
                talao.ESTOQUE_STATUS = '1';


                for ( var y in talao.CONSUMOS ) {

                    var consumo = talao.CONSUMOS[y];


                    if ( talao.ESTOQUE_STATUS == '1' && consumo.ESTOQUE_STATUS == 0 ) {
                        talao.ESTOQUE_STATUS = '0';
                    }  

                    talao.ULTIMO_TALAO = true;
                    var i = 0;
                    for ( var y in talao.DETALHES ) {


                        var detalhe = talao.DETALHES[y];

                        if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                            i++;    
                        }

                        if ( i > 1 ) {
                            talao.ULTIMO_TALAO = false;
                            break;
                        }
                    }                
                }
            }        
        }
        
        
        if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIDO' ) {
            taloes = gScope.TalaoProduzido.DADOS;
        } else
        if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIR' ) {
            taloes = gScope.TalaoProduzir.DADOS;
        }         
        
        gcCollection.merge(taloes, [response.TALAO], 'TALAO_ID',true);  
                
        
    };    


    Talao.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {

            if ( item.TALAO_ID != this.SELECTED.TALAO_ID ) {
                gScope.TalaoDetalhe.SELECTED = {};
                gScope.TalaoDetalhe.SELECTEDS = [];
                gScope.TalaoDetalhe.SELECTEDS_PRODUZIR = [];
            }
            
            this.SELECTED = item;
            
            gScope.Filtro.TALAO_ID = item.TALAO_ID;
            gScope.Filtro.uriHistory();                        

            if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIDO' ) {
                that.consultarComposicao();
            }

            if ( action == 'modal-open' ) {

                
                that.show(null,function(){

                    $('[data-talao-id="' + gScope.Filtro.TALAO_ID + '"]:focusable').focus();

                    delete gScope.Filtro.TALAO_ID;
                    gScope.Filtro.uriHistory();      
                });               
                
            }
        }

    };    


 
    
    Talao.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
            DADOS: {
                ITENS : that.ITENS_BAIXAR,
                PESO : that.PESO
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

    Talao.prototype.irPara = function (direcao) {
        
        var that = this;
        var taloes = [];
        
        switch (gScope.Filtro.TAB_ACTIVE) {
            case 'PRODUZIR':
                taloes = gScope.TalaoProduzir.FILTERED;
                break;
            case 'PRODUZIDO':
                taloes = gScope.TalaoProduzido.FILTERED;
                break;
                
        }
        
        switch (direcao) {
            case '|<':
                that.pick(taloes[0]);
                break;
                
            case '<':
                
                var idx = taloes.indexOf(that.SELECTED);
                if ( taloes[idx-1] != undefined ) {
                    that.pick(taloes[idx-1]);
                }
                break;
                
            case '>':

                var idx = taloes.indexOf(that.SELECTED);
                if ( taloes[idx+1] != undefined ) {
                    that.pick(taloes[idx+1]);
                }
                break;
                
            case '>|':
                that.pick(taloes[taloes.length-1]);
                break;
        }
    };  

    var modal = $('#modal-talao');
    
    Talao.prototype.show = function(shown,hidden) {

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

    Talao.prototype.close = function(hidden) {

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
    
    Talao.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return Talao;
};