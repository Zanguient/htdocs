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
        this.CALLBACK     = null;
        
    }
    
    
    Operador.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            $ajax.post('/_22050/autenticacao',that)
                .then(function(response) {

                    that.SELECTED = response[0];
                    that.AUTENTICADO  = true;
                    that.close();
                    
                    that.CALLBACK(response);
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
   
    Operador.prototype.open = function(callback) {
        
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
    
    Operador.prototype.logoff = function() {
        
        var that = this;

        that.SELECTED = {};
        that.AUTENTICADO = false;
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
        
        
        this.CONSULTA_FILTRO = '';
        this.DADOS = [];
        this.FILTERED = [];
        this.SELECTED = {};
        this.TALOES_LIBERAR = [];
        this.TALOES_LIBERAR_LISTA = [];
        this.BARRAS = '';
    }
    
    var data = {};
    
    
    Talao.prototype.consultar = function(data) {

        var that = this;
        
        return $q(function(resolve,reject){
        
            $ajax.post('/_22200/api/talao',data).then(function(response){

                resolve(response);
            },function(erro){
                reject(erro);
            });
        });
    };    
    
    Talao.prototype.consultarBarras = function() {
        
        var that = this;
                
        return $q(function(resolve,reject){
            that.consultar({
                REMESSA_ID       : that.BARRAS.substring(0,6),
                REMESSA_TALAO_ID : that.BARRAS.substring(6,10)
            }).then(function(response){
                
                if ( response.length == 1 ) {
                    
                    gcCollection.merge(that.TALOES_LIBERAR, response, 'TALAO_ID',true);                     
                    if ( response[0].STATUS == 2 ) {
                        gcCollection.merge(that.TALOES_LIBERAR_LISTA, response, 'TALAO_ID',true);                     
                    }
                    
                } else {
                    showErro('Talão inválido.');
                }
                
                that.BARRAS = '';
                resolve(response);
            },function(erro){
                that.BARRAS = '';
                reject(erro);
            });
        });
    };
    
    Talao.prototype.liberar = function() {
        
        var that = this;
        
        gScope.Operador.open(function(){
            gScope.Gp.open(function(){

                var data = {};

                data.OPERADOR_ID    = gScope.Operador.SELECTED.OPERADOR_ID;
                data.GP_ID          = gScope.Gp.SELECTED.GP_ID;
                data.TALOES_LIBERAR = that.TALOES_LIBERAR_LISTA;

                $ajax.post('/_22200/api/talao/liberar',data).then(function(){
                    that.TALOES_LIBERAR = [];
                    that.TALOES_LIBERAR_LISTA = [];
                });
                
                gScope.Operador.logoff();
                gScope.Gp.logoff();
            });  
        });
        
    };
    
    Talao.prototype.liberarLimpar = function() {
        this.TALOES_LIBERAR = [];
        this.TALOES_LIBERAR_LISTA = [];
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
        
        this.FAMILIA_ID = 3;
        this.PRODUTO_ID = '> 0';
        this.STATUS     = '2';
        this.TURNO      = '';
        this.DATA_1     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
        this.DATA_2     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = false;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;

        var dados = {};

        angular.copy(that, dados);

        if ( !that.DATA_TODOS ) {
            var data = "BETWEEN '" + moment(dados.DATA_1).format('DD.MM.YYYY') + "' AND '" + moment(dados.DATA_2).format('DD.MM.YYYY') + "'";
       
            switch (dados.STATUS) {
                case '2':
                    dados.DATA_PRODUCAO = data;
                    break;
                case '3':
                    dados.DATA_LIBERACAO = data;
                    break;

                default:
                    dados.DATA_REMESSA = data;

                    break;
            }
        }          
        delete dados.DATA_1;
        delete dados.DATA_2;
        
        if ( dados.STATUS.trim() != '' ) {
            dados.STATUS = '= ' + dados.STATUS;
        } else {
            delete dados.STATUS;
        }
        
        if ( dados.TURNO.trim() != '' ) {
            dados.TURNO = "= '" + dados.TURNO + "'";
        } else {
            delete dados.TURNO;
        }
        

        gScope.Talao.consultar(dados).then(function(response){

            gcCollection.merge(gScope.Talao.DADOS, response, 'TALAO_ID');
        });
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
        'Gp',
        'Operador'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Filtro,
        Talao,
        Gp,
        Operador
    ) {

		var vm = this;
        
        vm.Clock      = Clock;
//        vm.Consulta   = new $consulta();
//        
//        vm.ConsultaEstabelecimento                             = vm.Consulta.getNew(true);
//        vm.ConsultaEstabelecimento.componente                  = '.consulta-estabelecimento';
//        vm.ConsultaEstabelecimento.model                       = 'vm.ConsultaEstabelecimento';
//        vm.ConsultaEstabelecimento.option.label_descricao      = 'Estabelecimento:';
//        vm.ConsultaEstabelecimento.option.obj_consulta         = '/_11020/api/estabelecimento';
//        vm.ConsultaEstabelecimento.option.tamanho_input        = 'input-maior';
//        vm.ConsultaEstabelecimento.option.tamanho_tabela       = 427;
//        vm.ConsultaEstabelecimento.option.campos_tabela        = [['ESTABELECIMENTO_ID', 'ID'],['ESTABELECIMENTO_NOMEFANTASIA','NOME FANTASIA']];
//        vm.ConsultaEstabelecimento.option.obj_ret              = ['ESTABELECIMENTO_ID', 'ESTABELECIMENTO_NOMEFANTASIA'];
//        vm.ConsultaEstabelecimento.compile();
//        gScope.ConsultaEstabelecimento = vm.ConsultaEstabelecimento;
//        
//        vm.ConsultaGp                             = vm.Consulta.getNew(true);
//        vm.ConsultaGp.componente                  = '.consulta-gp';
//        vm.ConsultaGp.model                       = 'vm.ConsultaGp';
//        vm.ConsultaGp.option.label_descricao      = 'GP:';
//        vm.ConsultaGp.option.obj_consulta         = '/_22030/api/gp';
//        vm.ConsultaGp.option.tamanho_input        = 'input-maior';
//        vm.ConsultaGp.option.tamanho_tabela       = 427;
//        vm.ConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
//        vm.ConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
//        vm.ConsultaGp.require                     = vm.ConsultaEstabelecimento;
//        vm.ConsultaGp.vincular();
//        vm.ConsultaGp.compile();
//        gScope.ConsultaGp = vm.ConsultaGp;
//        
//        vm.ConsultaUp                             = vm.Consulta.getNew(true);
//        vm.ConsultaUp.componente                  = '.consulta-up';
//        vm.ConsultaUp.model                       = 'vm.ConsultaUp';
//        vm.ConsultaUp.option.label_descricao      = 'UP:';
//        vm.ConsultaUp.option.obj_consulta         = '/_22030/api/up';
//        vm.ConsultaUp.option.tamanho_input        = 'input-maior';
//        vm.ConsultaUp.option.tamanho_tabela       = 427;
//        vm.ConsultaUp.option.campos_tabela        = [['UP_ID', 'ID'],['UP_DESCRICAO','GRUPO DE PRODUÇÃO']];
//        vm.ConsultaUp.option.obj_ret              = ['UP_ID', 'UP_DESCRICAO'];
//        vm.ConsultaUp.require                     = vm.ConsultaGp;
//        vm.ConsultaUp.vincular();
//        vm.ConsultaUp.setRequireRequest({GP_ID: [vm.ConsultaGp, 'GP_ID']});
//        vm.ConsultaUp.compile();
//        gScope.ConsultaUp = vm.ConsultaUp;
//        
//        vm.ConsultaEstacao                             = vm.Consulta.getNew(true);
//        vm.ConsultaEstacao.componente                  = '.consulta-estacao';
//        vm.ConsultaEstacao.model                       = 'vm.ConsultaEstacao';
//        vm.ConsultaEstacao.option.label_descricao      = 'Estação:';
//        vm.ConsultaEstacao.option.obj_consulta         = '/_22030/api/estacao';
//        vm.ConsultaEstacao.option.tamanho_input        = 'input-maior';
//        vm.ConsultaEstacao.option.tamanho_tabela       = 427;
//        vm.ConsultaEstacao.option.campos_tabela        = [['ESTACAO', 'ID'],['ESTACAO_DESCRICAO','GRUPO DE PRODUÇÃO']];
//        vm.ConsultaEstacao.option.obj_ret              = ['ESTACAO', 'ESTACAO_DESCRICAO'];
//        vm.ConsultaEstacao.require                     = vm.ConsultaUp;
//        vm.ConsultaEstacao.vincular();
//        vm.ConsultaEstacao.setRequireRequest({UP_ID: [vm.ConsultaUp, 'UP_ID']});
//        vm.ConsultaEstacao.compile();
//        gScope.ConsultaEstacao = vm.ConsultaEstacao;
        
		vm.Filtro         = new Filtro();
		vm.Talao          = new Talao();
		vm.Operador       = new Operador();
		vm.Gp             = new Gp();
        

//        $scope.$watch('vm.ConsultaEstacao.ESTACAO', function (newValue, oldValue, scope) {
//            if ( newValue == undefined || newValue <= 0 ) {
//                vm.SSE.close();
//                
//                vm.TalaoProduzir.DADOS = [];
//                vm.TalaoProduzido.DADOS = [];
//            }
//        }, true);
//
//        $scope.$watch('vm.Filtro.TAB_ACTIVE', function (newValue, oldValue, scope) {
//            
//            if ( newValue == 'PRODUZIDO' ) {
//                vm.Filtro.DATA_TODOS = false;
//                vm.Filtro.DATA_TODOS_DISABLED = true;
//                vm.Filtro.consultar();
////                if ( $('#filtrar-toggle').hasClass('collapsed') ) {
////                    $('#filtrar-toggle').click();
////                }
//            } else
//            if ( newValue == 'PRODUZIR' ) {
//                vm.Filtro.DATA_TODOS = true;
//                vm.Filtro.DATA_TODOS_DISABLED = false;
//                vm.Filtro.consultar();
//                
////                if ( !$('#filtrar-toggle').hasClass('collapsed') ) {
////                    $('#filtrar-toggle').click();
////                }                
//            } 
//
//        }, true);

	}   
  
//# sourceMappingURL=_11200.js.map
