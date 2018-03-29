'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils'
	])
;
     
angular
    .module('app')
    .factory('ServerEvent', ServerEvent);
    

	ServerEvent.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ServerEvent($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ServerEvent(data) {
        if (data) {
            this.setData(data);
        }

		gScope.SSE = this; 
        
        this.connection = null;
        this.SELECTED = {};
        this.UPDATED = true;
        this.LAST_UPDATE = null;
    }

    
    ServerEvent.prototype.connect = function() {

        var that = this;
        var url = '_22190/sse/taloes/composicao?'+$httpParamSerializer(gScope.Filtro);
        
        if(typeof(EventSource) !== "undefined") {
        var evtSource = new EventSource(url);
        } else {
            showErro('Opss... Ocorreu uma falha!<br/>Seu navegador não possui suporte para eventos dinâmicos.<br><b>Recomendamos a utilização do Google Chrome.</b><br/>Entre em contato com suporte técnico para esclarecer demais dúvidas.');
        }

        evtSource.onmessage = function(e) {
            $rootScope.$apply(function(){
                var response = JSON.parse(event.data);
                
                if ( !that.UPDATED ) {
                    showSuccess('Os dados foram atualizados!');
                }
                
                gScope.Filtro.merge(response,true);
                
                
                that.LAST_UPDATE = Clock.DATETIME_SERVER;
                that.UPDATED     = false;
            });
        };
        evtSource.onerror = function() {
          showErro('Opss... Ocorreu uma falha!<br/>Os eventos dinâmicos foram desconectados.<br/><b>Estamos tentando reconectar automaticamente...</b><br/>Se o erro pesistir, entre em contato com suporte técnico.');
        };
        
        this.connection = evtSource;

    };

    ServerEvent.prototype.close = function(item,action) {
        
        if ( this.connection != undefined ) {
        
            this.connection.close();    
            this.connection = undefined;
        }

    };    



    /**
     * Return the constructor function
     */
    return ServerEvent;
};
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
angular
    .module('app')
    .factory('TalaoDetalhe', TalaoDetalhe);
    

	TalaoDetalhe.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoDetalhe($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoDetalhe(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoDetalhe = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.SELECTEDS = [];
        this.SELECTEDS_PRODUZIR = [];
    }
    
    TalaoDetalhe.prototype.checkAll = function() {
        
        var taloes = gScope.Talao.SELECTED.DETALHES;
        for ( var i in taloes ) {
            var detalhe = taloes[i];
            
            this.SELECTEDS.push(detalhe);
            
            if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                this.SELECTEDS_PRODUZIR.push(detalhe);
            }            
        }
    };
    
    TalaoDetalhe.prototype.uncheckAll = function() {
        this.SELECTEDS = [];
        this.SELECTEDS_PRODUZIR = [];
    };
   
    


    TalaoDetalhe.prototype.pick = function(item,action) {

        
        
        var that = this;
        
        var idx = this.SELECTEDS.indexOf(item);
        var idx_prod = this.SELECTEDS_PRODUZIR.indexOf(item);
        
        if ( idx == -1 ) {
            this.SELECTEDS.push(item);


            if ( item.TALAO_DETALHE_STATUS < 2 ) {
                this.SELECTEDS_PRODUZIR.push(item);
            }
            
        } else {
            this.SELECTEDS.splice(idx,1);
        }
        
        if ( idx_prod > -1 ) {
            this.SELECTEDS_PRODUZIR.splice(idx_prod,1);
        }
        
    };    


    TalaoDetalhe.prototype.confirm = function () {
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

    TalaoDetalhe.prototype.setItens = function () {
        
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

    TalaoDetalhe.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return TalaoDetalhe;
};
angular
    .module('app')
    .factory('TalaoProduzir', TalaoProduzir);
    

	TalaoProduzir.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoProduzir($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzir(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoProduzir = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.FILTRO = {};
        
        var that = this;
        $timeout(function(){
            
            if ( that.FILTRO.ESTABELECIMENTO_ID > 0 ) {
                
                gScope.ConsultaGp.autoload = false;
                gScope.ConsultaEstabelecimento.option.data_request.ESTABELECIMENTO_ID = [that.FILTRO,'ESTABELECIMENTO_ID'];
                gScope.ConsultaEstabelecimento.filtrar();
                delete gScope.ConsultaEstabelecimento.option.data_request.ESTABELECIMENTO_ID;
            }
            
            gScope.ConsultaEstabelecimento.onSelect = function(){
                if ( that.FILTRO.GP_ID > 0 ) {
                    gScope.ConsultaUp.autoload = false;
                    gScope.ConsultaGp.option.data_request.GP_ID = [that.FILTRO,'GP_ID'];
                    gScope.ConsultaGp.filtrar();
                    delete gScope.ConsultaGp.option.data_request.GP_ID;   
                }
            };

            gScope.ConsultaGp.onSelect = function(){
                gScope.ConsultaGp.autoload = true;
                if ( that.FILTRO.UP_ID > 0 ) {
                    gScope.ConsultaEstacao.autoload = false;
                    gScope.ConsultaUp.option.data_request.UP_ID = [that.FILTRO,'UP_ID'];
                    gScope.ConsultaUp.filtrar();
                    delete gScope.ConsultaUp.option.data_request.UP_ID;   
                }
            };

            gScope.ConsultaUp.onSelect = function(){
                gScope.ConsultaUp.autoload = true;
                if ( that.FILTRO.ESTACAO > 0 ) {
                    gScope.ConsultaEstacao.option.data_request.ESTACAO = [that.FILTRO,'ESTACAO'];
                    gScope.ConsultaEstacao.filtrar();
                    delete gScope.ConsultaEstacao.option.data_request.ESTACAO;   
                }
            };
            
            gScope.ConsultaEstacao.onSelect = function() {
                if ( that.FILTRO.ESTACAO > 0 ) {
                    gScope.Filtro.consultar().then(function(){
                        if ( that.FILTRO.TALAO_ID > 0 ) {
                            $timeout(function(){
                                $('[data-talao-id="' + that.FILTRO.TALAO_ID + '"]:focusable').focus().click();
                            });
                        }
                    });
                }
            };
//

//                    
//
//                    if ( that.FILTRO.UP_ID > 0 ) {
//                        gScope.ConsultaUp.option.filtro_sql = { UP_ID: that.FILTRO.UP_ID };
//                        gScope.ConsultaUp.filtrar();
//                        gScope.ConsultaUp.option.filtro_sql = {};
//                        
//
//                        if ( that.FILTRO.ESTACAO > 0 ) {
//                            gScope.ConsultaEstacao.option.filtro_sql = { ESTACAO: that.FILTRO.ESTACAO };
//                            gScope.ConsultaEstacao.filtrar();
//                            gScope.ConsultaEstacao.option.filtro_sql = {};
//                        }  
//                        
//                    }   
//
//                }         
              
            
            
        });        
    }
    
    
    TalaoProduzir.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        
        var data = {};

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
   
    TalaoProduzir.prototype.acao = function (tipo) {
        var that = this;

        var talao = gScope.Talao.SELECTED;
        talao.ULTIMO_TALAO = true;
        for ( var i in talao.DETALHES ) {
            var detalhe = talao.DETALHES[i];
            
            if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                var idx = gScope.TalaoDetalhe.SELECTEDS_PRODUZIR.indexOf(detalhe);
                
                if ( idx == -1 ) {
                    talao.ULTIMO_TALAO = false;
                    break;
                }
            }
        }

        var dados = {
            FILTRO: gScope.Filtro,
            DADOS: {
                ITENS              : gScope.TalaoDetalhe.SELECTEDS_PRODUZIR,
                TALAO              : gScope.Talao.SELECTED,
                ESTABELECIMENTO_ID : gScope.ConsultaEstabelecimento.ESTABELECIMENTO_ID,
                UP_ID              : gScope.ConsultaUp.UP_ID,
                ESTACAO            : gScope.ConsultaEstacao.ESTACAO,
                OPERADOR_ID        : gScope.Operador.SELECTED.OPERADOR_ID
            }
        };
                
        $ajax.post('/_22190/api/taloes/acao/'+tipo,dados).then(function(response){

            if ( response.ETIQUETAS != undefined && response.ETIQUETAS.trim() != '' ) {
                postprint(response.ETIQUETAS);
            }          

            if ( response.DATA_RETURN != undefined ) {
                gScope.Filtro.merge(response.DATA_RETURN);
            }
            
            if ( tipo == 'finalizar' ) {
                
                gScope.TalaoDetalhe.SELECTEDS_PRODUZIR = [];
                
                if ( indexOfAttr(gScope.TalaoProduzir.DADOS,'TALAO_ID',gScope.Talao.SELECTED.TALAO_ID) == -1 ) {
                    gScope.Talao.close();
                }
            }  
        },function(){
            
        });
    };
    
    TalaoProduzir.prototype.check = function (acao,arg_talao) {

        var ret         = {
            status    : true,
            descricao : ''
        };

        var em_producao = gScope.TalaoProduzir.EM_PRODUCAO || false;
        var talao       = arg_talao != undefined ? arg_talao : gScope.Talao.SELECTED ;

        if ( talao.PROGRAMACAO_STATUS > 2 ) {
            ret.status = false;
        } else
        if ( isEmpty(gScope.Operador.SELECTED) ) {
            ret.status = false;
            ret.descricao = 'Operador não autenticado.';
        }
                        
        if ( ret.status ) {
            switch(acao) {
                case 'iniciar':

                    if ( talao.REMESSA_LIBERADA == 0 ) {
                        ret.status = false;
                        ret.descricao = 'Remessa bloqueada para produção';
                    } else
                    if ( talao.ESTOQUE_STATUS == '0' ) {
                        ret.status = false;
                        ret.descricao = 'Talão sem estoque para o consumo';
                    } else                    
                    if ( talao.PROGRAMACAO_STATUS == 1 ) {
                        ret.descricao = 'Talão parado';
                    } else                    
                    if ( talao.PROGRAMACAO_STATUS > 1 ) {
                        ret.status = false;
                    }                

                    break;
                case 'pausar':

                    // Se estiver em produção 
                    if ( talao.PROGRAMACAO_STATUS != 2 ) {
                        ret.status = false;
                    }  

                    break;
                case 'finalizar':

                    // Se estiver em produção 
                    if ( talao.PROGRAMACAO_STATUS < 2 ) {
                        ret.status = false;
                    }  
                    else                 
                    if ( gScope.TalaoDetalhe.SELECTEDS_PRODUZIR.length == 0 ) {
                        ret.status = false;
                        ret.descricao = 'Selecione um detalhamento não produzido.';
                    }
//                
//                    // Se estiver em produção 
////                    if ( gScope.Talao.SELECTED.PROGRAMACAO_STATUS < 2 ) {
//                        ret.status = false;
////                    }  

                    break;
                case 'imprimir':

                    var talao_produzido = gScope.TalaoProduzido.SELECTED;


                    // Se não estiver na tela de produção
                    if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIDO' ) {
                        ret.status    = false;
                    } else                 
                    if ( talao_produzido == undefined ) {
                        ret.status    = false;
                        ret.descricao = 'Selecione um talão';
                    }

                    break;
            }
        }
        
        return ret;
    };
    
    

    /**
     * Return the constructor function
     */
    return TalaoProduzir;
};
angular
    .module('app')
    .factory('TalaoProduzido', TalaoProduzido);
    

	TalaoProduzido.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoProduzido($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoProduzido(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoProduzido = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.FILTRO = {};
              
    }
    
    
    TalaoProduzido.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            
            var data = {};

            angular.copy(gScope.Filtro, data);

            if ( !data.DATA_TODOS ) {                
                data.DATA_PRODUCAO      = "BETWEEN '" + moment(data.DATA_1).format('YYYY.MM.DD') + "' AND '" + moment(data.DATA_2).format('YYYY.MM.DD') + "'";
            }
            
            delete data.DATA_1;
            delete data.DATA_2;
                
            data.PROGRAMACAO_STATUS = "= 3";
            data.GP_ID              = gScope.ConsultaGp.GP_ID;
            data.UP_ID              = gScope.ConsultaUp.UP_ID;
            data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;

            $ajax.post('/_22190/api/talao',data).then(function(response){

                that.merge(response);

            });
            
        });
    };
   

    TalaoProduzido.prototype.merge = function(response,auto) {


        var taloes = [];
        if ( response.TALAO != undefined ) {
            taloes = [response.TALAO];
        } else {
            taloes = response;
        }
        
        sanitizeJson(response);
        gcCollection.merge(this.DADOS, taloes, 'TALAO_ID');  
        

        if ( response.CONSUMOS != undefined ) {
            sanitizeJson(response.DETALHES);
            sanitizeJson(response.CONSUMOS);
            sanitizeJson(response.HISTORICOS);   
            sanitizeJson(response.ALOCADOS);

            gcCollection.bind(response.CONSUMOS, response.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');   
            gcCollection.bind(this.DADOS, response.DETALHES, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
            gcCollection.bind(this.DADOS, response.CONSUMOS, 'TALAO_ID', 'CONSUMOS');
            gcCollection.bind(this.DADOS, response.HISTORICOS, 'PROGRAMACAO_ID', 'HISTORICOS');     

            for ( var i in this.DADOS ) {

                var talao = this.DADOS[i];

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
    };   
    

    /**
     * Return the constructor function
     */
    return TalaoProduzido;
};
angular
    .module('app')
    .factory('TalaoHistorico', TalaoHistorico);
    

	TalaoHistorico.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoHistorico($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoHistorico(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoHistorico = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
    }
    
    TalaoHistorico.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        
        var data = {};

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
   
    


    TalaoHistorico.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

            if ( action == 'modal-open' ) {
                that.open();
            }
        }

    };    


 
    var modal = $('#modal-talao');
    
    TalaoHistorico.prototype.open = function() {
        
        var that = this;
        if ( this.SELECTED != undefined ) {
            
            this.show();
        }
        
    };
  

    TalaoHistorico.prototype.confirm = function () {
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

    TalaoHistorico.prototype.setItens = function () {
        
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

    TalaoHistorico.prototype.show = function(shown,hidden) {

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

    TalaoHistorico.prototype.close = function(hidden) {

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
    
    TalaoHistorico.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return TalaoHistorico;
};
angular
    .module('app')
    .factory('TalaoConsumo', TalaoConsumo);
    

	TalaoConsumo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoConsumo($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoConsumo(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoConsumo = this; 
        
        this.DADOS = [];
        this.COMPONENTE_DADOS = [];
        this.ALOCADOS = [];
        this.SELECTED = {};
    }
    
    TalaoConsumo.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        
        var data = {};

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
   
    


    TalaoConsumo.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

            if ( action == 'modal-open' ) {
                that.open();
            }
        }

    };    

    TalaoConsumo.prototype.componenteAlocadoDelete = function(componente) {
        var data = {
            DADOS : componente,
            FILTRO : {
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID,
                TALAO_ID : gScope.Talao.SELECTED.TALAO_ID
            }
        };        
        
        $ajax.post('/_22190/api/consumo/componente/alocado/delete',data).then(function(response){
            gScope.Talao.mergeComposicao(response.DATA_RETURN.DADOS);
        });       

    };    



    $(document).off('click', '.alocado-excluir').on('click', '.alocado-excluir', function() {
        var _this   = this;
        
        $ajax.post('/_22010/projecaoVinculoExcluir',{id: $(_this).data('talao-vinculo-id')}).then(function(response){
            $('.popover').remove();
            gScope.Talao.consultarComposicao();
            
        });        
        
    });

    TalaoConsumo.prototype.componenteRegistrar = function(item,action) {
        
        var that = this;

        var data = {
            DADOS : {
                COMPONENTE_BARRAS   : that.COMPONENTE_BARRAS,
                TALAO_ID            : gScope.Talao.SELECTED.TALAO_ID,
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID
            },
            FILTRO : {
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID,
                TALAO_ID : gScope.Talao.SELECTED.TALAO_ID
            }
        };
        
        $ajax.post('/_22190/api/consumo/componente/alocar',data).then(function(response){
            
            gScope.Talao.mergeComposicao(response.DATA_RETURN.DADOS);
            $('#modal-registrar-componente').modal('hide');
            
        },function(){
            that.COMPONENTE_BARRAS = '';
        });
    };    

    TalaoConsumo.prototype.componenteModalOpen = function(item,action) {
        
        var that = this;

        that.COMPONENTE_BARRAS = '';
        
        var modal = $('#modal-registrar-componente');
        
        modal.modal('show');
        
        modal.one('shown.bs.modal', function() {
            $(this).find('input:focusable').first().focus();
        });
    };    


    var modal = $('#modal-talao');
    
    TalaoConsumo.prototype.open = function() {
        
        var that = this;
        if ( this.SELECTED != undefined ) {
            
            this.show();
        }
        
    };
  

    TalaoConsumo.prototype.confirm = function () {
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

    TalaoConsumo.prototype.setItens = function () {
        
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

    TalaoConsumo.prototype.show = function(shown,hidden) {

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

    TalaoConsumo.prototype.close = function(hidden) {

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
    
    TalaoConsumo.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return TalaoConsumo;
};
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
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$q',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $q, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Filtro = this; 
        
        this.TAB_ACTIVE = 'PRODUZIR';
        this.DATA_1     = new Date(Clock.DATETIME_SERVER);
        this.DATA_2     = new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = true;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
    //        loading('.main-ctrl');     

            if ( ! (parseInt(gScope.ConsultaEstacao.ESTACAO) > -1) ) {
                return false;
            }
            
            if ( that.TAB_ACTIVE == 'PRODUZIDO' ) {
                gScope.TalaoProduzido.consultar();
            } else {

                gScope.SSE.close();

                var data = {};

                angular.copy(that, data);

                if ( that.DATA_TODOS ) {
                    delete data.DATA_1;
                    delete data.DATA_2;
                }

                data.PROGRAMACAO_STATUS = "< 3";
                data.ESTABELECIMENTO_ID = gScope.ConsultaEstabelecimento.ESTABELECIMENTO_ID;
                data.GP_ID              = gScope.ConsultaGp.GP_ID;
                data.UP_ID              = gScope.ConsultaUp.UP_ID;
                data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;

                angular.extend(that, data);

                $ajax.post('/_22190/api/taloes/composicao',data).then(function(response){

                    that.merge(response);
                    that.uriHistory();

                    gScope.SSE.connect();

    //                $timeout(function(){
    //                    $('#filtrar-toggle[aria-expanded="true"]').click(); 
    //                });    

                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
    };
   
    
    Filtro.prototype.merge = function(response,auto) {
        
        if ( auto == undefined ) {
            gScope.SSE.UPDATED = true;
        }

        var arrayClean = function (response){
            function isNumber(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            }

            for ( var i in response ) {
                var item = response[i];

                for (var k in item){
                    if (item.hasOwnProperty(k)) {

                        if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                            item[k] = parseFloat(item[k]);
                        }
                    }
                }            
            }
        };

        arrayClean(response.DETALHES);
        gcCollection.merge(gScope.TalaoDetalhe.DADOS, response.DETALHES, 'REMESSA_TALAO_DETALHE_ID');  
        
        arrayClean(response.CONSUMOS);
        gcCollection.merge(gScope.TalaoConsumo.DADOS, response.CONSUMOS, 'CONSUMO_ID');    
        
        arrayClean(response.COMPONENTES);
        gcCollection.merge(gScope.TalaoConsumo.COMPONENTE_DADOS, response.COMPONENTES, ['REMESSA_ID','REMESSA_TALAO_ID']);    
        
        arrayClean(response.HISTORICOS);   
        gcCollection.merge(gScope.TalaoHistorico.DADOS, response.HISTORICOS, 'ID');      
        
        if ( response.TALOES != undefined ) {
            arrayClean(response.TALOES);   
            gcCollection.merge(gScope.TalaoProduzir.DADOS, response.TALOES, 'TALAO_ID'); 
        }
        
        if ( response.TALAO != undefined ) {
            arrayClean(response.TALOES);   
            gcCollection.merge(gScope.TalaoProduzir.DADOS, response.TALAO, 'TALAO_ID',true); 
        }
        
        arrayClean(response.ALOCADOS);
        gcCollection.merge(gScope.TalaoConsumo.ALOCADOS, response.ALOCADOS, 'ID');
        
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoDetalhe.DADOS, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoConsumo.DADOS, 'TALAO_ID', 'CONSUMOS');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoConsumo.COMPONENTE_DADOS, 'TALAO_ID', 'COMPONENTES');
        gcCollection.bind(gScope.TalaoProduzir.DADOS, gScope.TalaoHistorico.DADOS, 'PROGRAMACAO_ID', 'HISTORICOS');
        gcCollection.bind(gScope.TalaoConsumo.DADOS, gScope.TalaoConsumo.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');        
        
        for ( var i in gScope.TalaoProduzir.DADOS ) {
            
            var talao = gScope.TalaoProduzir.DADOS[i];
            
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
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_22190?'+$httpParamSerializer(this)));
        
    };


    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        '$consulta',
        'Filtro',
        'Talao',
        'TalaoDetalhe',
        'TalaoConsumo',
        'TalaoHistorico',
        'TalaoProduzir',
        'TalaoProduzido',
        'Operador',
        'ServerEvent'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Filtro,
        Talao,
        TalaoDetalhe,
        TalaoConsumo,
        TalaoHistorico,
        TalaoProduzir,
        TalaoProduzido,
        Operador,
        ServerEvent
    ) {

		var vm = this;
        
        vm.Clock      = Clock;
        vm.Consulta   = new $consulta();
        
        vm.ConsultaEstabelecimento                             = vm.Consulta.getNew(true);
        vm.ConsultaEstabelecimento.componente                  = '.consulta-estabelecimento';
        vm.ConsultaEstabelecimento.model                       = 'vm.ConsultaEstabelecimento';
        vm.ConsultaEstabelecimento.option.label_descricao      = 'Estabelecimento:';
        vm.ConsultaEstabelecimento.option.obj_consulta         = '/_11020/api/estabelecimento';
        vm.ConsultaEstabelecimento.option.tamanho_input        = 'input-maior';
        vm.ConsultaEstabelecimento.option.tamanho_tabela       = 427;
        vm.ConsultaEstabelecimento.option.campos_tabela        = [['ESTABELECIMENTO_ID', 'ID'],['ESTABELECIMENTO_NOMEFANTASIA','NOME FANTASIA']];
        vm.ConsultaEstabelecimento.option.obj_ret              = ['ESTABELECIMENTO_ID', 'ESTABELECIMENTO_NOMEFANTASIA'];
        vm.ConsultaEstabelecimento.compile();
        gScope.ConsultaEstabelecimento = vm.ConsultaEstabelecimento;
        
        vm.ConsultaGp                             = vm.Consulta.getNew(true);
        vm.ConsultaGp.componente                  = '.consulta-gp';
        vm.ConsultaGp.model                       = 'vm.ConsultaGp';
        vm.ConsultaGp.option.label_descricao      = 'GP:';
        vm.ConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.ConsultaGp.option.tamanho_input        = 'input-maior';
        vm.ConsultaGp.option.tamanho_tabela       = 427;
        vm.ConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.ConsultaGp.require                     = vm.ConsultaEstabelecimento;
        vm.ConsultaGp.vincular();
        vm.ConsultaGp.compile();
        gScope.ConsultaGp = vm.ConsultaGp;
        
        vm.ConsultaUp                             = vm.Consulta.getNew(true);
        vm.ConsultaUp.componente                  = '.consulta-up';
        vm.ConsultaUp.model                       = 'vm.ConsultaUp';
        vm.ConsultaUp.option.label_descricao      = 'UP:';
        vm.ConsultaUp.option.obj_consulta         = '/_22030/api/up';
        vm.ConsultaUp.option.tamanho_input        = 'input-maior';
        vm.ConsultaUp.option.tamanho_tabela       = 427;
        vm.ConsultaUp.option.campos_tabela        = [['UP_ID', 'ID'],['UP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaUp.option.obj_ret              = ['UP_ID', 'UP_DESCRICAO'];
        vm.ConsultaUp.require                     = vm.ConsultaGp;
        vm.ConsultaUp.vincular();
        vm.ConsultaUp.setRequireRequest({GP_ID: [vm.ConsultaGp, 'GP_ID']});
        vm.ConsultaUp.compile();
        gScope.ConsultaUp = vm.ConsultaUp;
        
        vm.ConsultaEstacao                             = vm.Consulta.getNew(true);
        vm.ConsultaEstacao.componente                  = '.consulta-estacao';
        vm.ConsultaEstacao.model                       = 'vm.ConsultaEstacao';
        vm.ConsultaEstacao.option.label_descricao      = 'Estação:';
        vm.ConsultaEstacao.option.obj_consulta         = '/_22030/api/estacao';
        vm.ConsultaEstacao.option.tamanho_input        = 'input-maior';
        vm.ConsultaEstacao.option.tamanho_tabela       = 427;
        vm.ConsultaEstacao.option.campos_tabela        = [['ESTACAO', 'ID'],['ESTACAO_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaEstacao.option.obj_ret              = ['ESTACAO', 'ESTACAO_DESCRICAO'];
        vm.ConsultaEstacao.require                     = vm.ConsultaUp;
        vm.ConsultaEstacao.vincular();
        vm.ConsultaEstacao.setRequireRequest({UP_ID: [vm.ConsultaUp, 'UP_ID']});
        vm.ConsultaEstacao.compile();
        gScope.ConsultaEstacao = vm.ConsultaEstacao;
        
		vm.Filtro         = new Filtro();
		vm.Talao          = new Talao();
		vm.TalaoDetalhe   = new TalaoDetalhe();
		vm.TalaoConsumo   = new TalaoConsumo();
		vm.TalaoHistorico = new TalaoHistorico();
		vm.TalaoProduzir  = new TalaoProduzir();
		vm.TalaoProduzido = new TalaoProduzido();
		vm.Operador       = new Operador();
		vm.SSE    = new ServerEvent();


        $scope.$watch('vm.ConsultaEstacao.ESTACAO', function (newValue, oldValue, scope) {
            if ( newValue == undefined || newValue <= 0 ) {
                vm.SSE.close();
                
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
            }
        }, true);

        $scope.$watch('vm.Filtro.TAB_ACTIVE', function (newValue, oldValue, scope) {
            
            if ( newValue == 'PRODUZIDO' ) {
                vm.Filtro.DATA_TODOS = false;
                vm.Filtro.DATA_TODOS_DISABLED = true;
                vm.Filtro.consultar();
//                if ( $('#filtrar-toggle').hasClass('collapsed') ) {
//                    $('#filtrar-toggle').click();
//                }
            } else
            if ( newValue == 'PRODUZIR' ) {
                vm.Filtro.DATA_TODOS = true;
                vm.Filtro.DATA_TODOS_DISABLED = false;
                vm.Filtro.consultar();
                
//                if ( !$('#filtrar-toggle').hasClass('collapsed') ) {
//                    $('#filtrar-toggle').click();
//                }                
            } 

        }, true);

	}   
  
//# sourceMappingURL=_22190.js.map
