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
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
        
//        this.DATA_1 = new Date(Clock.DATETIME_SERVER);
//        this.DATA_2 = new Date(Clock.DATETIME_SERVER);

		gScope.Filtro = this; 
        
    }
    
    Filtro.prototype.consultar = function(progress) {
        
        var that = this;
        
        return $q(function(resolve){
    //        loading('.main-ctrl');     

//            this.DATAHORA = {
//                DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
//                DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
//            };

            $ajax.post('/_13030/api/cotas',that,{progress: progress == undefined ? false : progress}).then(function(response){

                that.merge(response);
                resolve(response);
    //            loading('hide');

            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
        sanitizeJson(response);

        gcCollection.merge(gScope.Cota.DADOS, response, 'ID');
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var periodos = gcCollection.groupBy(gScope.Cota.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO',
            'MES',
            'ANO',
            'PERIODO_DESCRICAO'
        ], 'CCONTABEIS',function(periodo,ccontabil){
            
            periodo.VALOR       == undefined ? periodo.VALOR       = 0 : '';
            periodo.EXTRA       == undefined ? periodo.EXTRA       = 0 : '';
            periodo.TOTAL       == undefined ? periodo.TOTAL       = 0 : '';
            periodo.OUTROS      == undefined ? periodo.OUTROS      = 0 : '';
            periodo.UTIL        == undefined ? periodo.UTIL        = 0 : '';
//            periodo.PERC_UTIL   == undefined ? periodo.PERC_UTIL   = 0 : '';
            periodo.SALDO       == undefined ? periodo.SALDO       = 0 : '';
            periodo.CUSTO_SETOR == undefined ? periodo.CUSTO_SETOR = 0 : '';

            if ( ccontabil.TIPO != 'GGF' ) {
                periodo.VALOR       += ccontabil.VALOR      ;
                periodo.EXTRA       += ccontabil.EXTRA      ;
                periodo.TOTAL       += ccontabil.TOTAL      ;
                periodo.OUTROS      += ccontabil.OUTROS     ;
                periodo.UTIL        += ccontabil.UTIL       ;
    //            periodo.PERC_UTIL   += ccontabil.PERC_UTIL  ;
                periodo.SALDO       += ccontabil.SALDO      ;        
                periodo.CUSTO_SETOR += ccontabil.CUSTO_SETOR;        


                if ( periodo.TOTAL > 0 ) {
                    periodo.PERC_UTIL = ((1-(periodo.SALDO/periodo.TOTAL))*100);
                } else {
                    if ( periodo.TOTAL == 0 && periodo.SALDO < 0 ) {
                        periodo.PERC_UTIL = 100;
                    } else {
                        periodo.PERC_UTIL = 0;  
                    }
                }
            }
//            if ( (periodo.VALOR + periodo.EXTRA) = 0 && periodo.SALDO )
//            IIF(A.VALOR+A.EXTRA = 0 AND A.SALDO < 0, 100, 0))

        });
        
        gcCollection.merge(gScope.CotaPeriodo.DADOS, periodos, ['CCUSTO','MES','ANO']);
        
        /////
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var ccustos = gcCollection.groupBy(gScope.CotaPeriodo.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO'
        ], 'PERIODOS',function(ccusto,periodo){
            
            ccusto.VALOR     == undefined ? ccusto.VALOR     = 0 : '';
            ccusto.EXTRA     == undefined ? ccusto.EXTRA     = 0 : '';
            ccusto.TOTAL     == undefined ? ccusto.TOTAL     = 0 : '';
            ccusto.OUTROS    == undefined ? ccusto.OUTROS    = 0 : '';
            ccusto.UTIL      == undefined ? ccusto.UTIL      = 0 : '';
//            ccusto.PERC_UTIL == undefined ? ccusto.PERC_UTIL = 0 : '';
            ccusto.SALDO     == undefined ? ccusto.SALDO     = 0 : '';    
            ccusto.CUSTO_SETOR == undefined ? ccusto.CUSTO_SETOR = 0 : ''; 

            ccusto.VALOR     += periodo.VALOR    ;
            ccusto.EXTRA     += periodo.EXTRA    ;
            ccusto.TOTAL     += periodo.TOTAL    ;
            ccusto.OUTROS    += periodo.OUTROS   ;
            ccusto.UTIL      += periodo.UTIL     ;
//            ccusto.PERC_UTIL += periodo.PERC_UTIL;
            ccusto.SALDO     += periodo.SALDO    ;      
            ccusto.CUSTO_SETOR += periodo.CUSTO_SETOR;         

            if ( ccusto.TOTAL > 0 ) {
                ccusto.PERC_UTIL = ((1-(ccusto.SALDO/ccusto.TOTAL))*100);
            } else {
                if ( ccusto.TOTAL == 0 && ccusto.SALDO < 0 ) {
                    ccusto.PERC_UTIL = 100;
                } else {
                    ccusto.PERC_UTIL = 0;  
                }
            }
        });
        
        gcCollection.merge(gScope.CotaCcusto.DADOS, ccustos, ['CCUSTO','MES','ANO','CCONTABIL']);
        
        /////
                
        
//        console.log(gScope.CotaCcusto.DADOS);
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_13030/ng?'+$httpParamSerializer(this)));        
    };    

    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .factory('Cota', Cota);
    

	Cota.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Cota($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Cota(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.SELECTED = {};
        this.ITENS = [];
        this.ALTERANDO = false;
		gScope.Cota = this; 
        this.COTA_BACKUP = {};
        
        this.events();
    }
    
    Cota.prototype.consultar = function() {
        
        var that = this;
        
        $ajax.post('/_13030/api/cota',that.SELECTED,{progress:false}).then(function(response){
            
            angular.extend(that.SELECTED, response);
        });
    };
        
    Cota.prototype.gravarAlteracao = function() {
        var that = this;
        var dados = {
            DADOS : {
                ITENS : [that.SELECTED]
            },
            FILTRO : gScope.Filtro,
            FILTRO_COTA : that.SELECTED
        };        
        $ajax.post('/_13030/api/cota/update',dados).then(function(response){

        
            that.ALTERANDO = false;
            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(that.SELECTED, response.DATA_RETURN.COTA);
        });        
    };    
    
    Cota.prototype.cancelar = function() {
        
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

    Cota.prototype.alterar = function() {
        this.ALTERANDO = true;
        angular.copy(this.SELECTED, this.COTA_BACKUP);
    };
    
    Cota.prototype.excluir = function() {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta cota?',
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

                    $ajax.post('/_13030/api/cota/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(that.SELECTED, response.DATA_RETURN.COTA);

                        that.ModalClose();
                    });

                });
            }}]     
        );        
        
    };
    
    Cota.prototype.dblPick = function(item,action) {
        
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

    
    Cota.prototype.pick = function(cota,setfocus) {
        
        var that = this;

        if ( cota != undefined ) {
        
            this.SELECTED = cota;

            gScope.Filtro.COTA_ID = cota.ID;
            gScope.Filtro.uriHistory();
            
            if ( setfocus ) {
                that.setFocus();
            }
        }

    };  
    
    var modal = $('#modal-cota');
    
    Cota.prototype.ModalShow = function(shown,hidden) {

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

    Cota.prototype.ModalClose = function(hidden) {

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
    
    Cota.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Cota.prototype.events = function($event) {
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
    return Cota;
};
angular
    .module('app')
    .factory('CotaGgf', CotaGgf);
    

	CotaGgf.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaGgf($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaGgf(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        this.SELECTED = {};
		gScope.CotaGgf = this; 
        
    }
    
    CotaGgf.prototype.consultarDetalhe = function(item,tipo) {
            
        var that = this;
        
        that.SELECTED = item;
        
        var url = tipo == 'inv' ? 'ajuste-inventario' : 'ggf';
        $ajax.post('/_13030/api/cota/'+url+'/detalhe',item).then(function(response){

            that.SELECTED.ITENS = {};
            angular.extend(that.SELECTED.ITENS, response);
            
            that.ModalShow();
        });        
    };    
    
    var modal = $('#modal-cota-ggf-detalhe');
    
    CotaGgf.prototype.ModalShow = function(shown,hidden) {

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

    CotaGgf.prototype.ModalClose = function(hidden) {

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
    return CotaGgf;
};
angular
    .module('app')
    .factory('CotaExtra', CotaExtra);
    

	CotaExtra.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaExtra($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaExtra(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        
		gScope.CotaExtra = this; 
        

        
        this.events();
    }
    
    CotaExtra.prototype.gravar = function() {
        
        var dados = {
            ID : gScope.Cota.SELECTED.ID
        };
        
        angular.extend(dados, this.DADOS);
        
        
        var that = this;
        var dados = {
            DADOS : dados,
            FILTRO : gScope.Filtro,
            FILTRO_COTA : gScope.Cota.SELECTED
        };        
        $ajax.post('/_13030/api/cota/extra/insert',dados).then(function(response){

            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
            
            that.reset();
        });        
    };    
    
    CotaExtra.prototype.excluir = function(item) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta cota extra?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : item,
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : gScope.Cota.SELECTED
                    };

                    $ajax.post('/_13030/api/cota/extra/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
                    });

                });
            }}]     
        );        
        
    };
    
    CotaExtra.prototype.reset = function() {
        
        var dados = {
            VALOR : null,
            OBSERVACAO : null
        };
        
        angular.extend(this.DADOS, dados);
    
    };
    
    CotaExtra.prototype.events = function($event) {
    
    };
        
    
    /**
     * Return the constructor function
     */
    return CotaExtra;
};
angular
    .module('app')
    .factory('CotaReducao', CotaReducao);
    

	CotaReducao.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaReducao($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaReducao(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = {};
        
		gScope.CotaReducao = this; 
        

        
        this.events();
    }
    
    CotaReducao.prototype.gravar = function() {
        
        var dados = {
            ID : gScope.Cota.SELECTED.ID
        };
        
        angular.extend(dados, this.DADOS);
        
        
        var that = this;
        var dados = {
            DADOS : dados,
            FILTRO : gScope.Filtro,
            FILTRO_COTA : gScope.Cota.SELECTED
        };        
        $ajax.post('/_13030/api/cota/reducao/insert',dados).then(function(response){

            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
            
            that.reset();
        });        
    };    
    
    CotaReducao.prototype.excluir = function(item) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta redução?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : item,
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : gScope.Cota.SELECTED
                    };

                    $ajax.post('/_13030/api/cota/reducao/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(gScope.Cota.SELECTED, response.DATA_RETURN.COTA);
                    });

                });
            }}]     
        );        
        
    };
    
    CotaReducao.prototype.reset = function() {
        
        var dados = {
            VALOR : null,
            OBSERVACAO : null
        };
        
        angular.extend(this.DADOS, dados);
    
    };
    
    CotaReducao.prototype.events = function($event) {
    
    };
        
    
    /**
     * Return the constructor function
     */
    return CotaReducao;
};
angular
    .module('app')
    .factory('CotaIncluir', CotaIncluir);
    

	CotaIncluir.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaIncluir($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaIncluir(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS         = {};
        this.INCLUINDO     = false;
		gScope.CotaIncluir = this; 
        
        this.COTA_BACKUP = {
            CCUSTO : null,
            CCONTABIL : null,
            VALOR : 0,
            BLOQUEIA : 0,
            NOTIFICA : 0,
            TOTALIZA : 0,
            DESTACA : 0
        };
        
        this.events();
    }
    
    CotaIncluir.prototype.gravar = function() {
        
        var that = this;
        
        that.DADOS.CCUSTO    = gScope.ConsultaCcusto.ID;
        that.DADOS.CCONTABIL = gScope.ConsultaCcontabil.CONTA;
        
        var dados = {
            DADOS : that.DADOS,
            FILTRO : gScope.Filtro,
            FILTRO_COTA : that.SELECTED
        };        
        $ajax.post('/_13030/api/cota/insert',dados).then(function(response){

        
            that.INCLUINDO = false;
            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            that.ModalClose();
        });        
    };    
    
    CotaIncluir.prototype.cancelar = function() {
        
        var that = this;
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente cancelar esta operação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){        
        
                    that.INCLUINDO = false;
                    that.ModalClose();

                });
            }}]     
        );      
    };
    
    CotaIncluir.prototype.incluir = function() {
        
        angular.extend(this.DADOS, this.COTA_BACKUP);
        this.INCLUINDO = true;
        this.ModalShow(null,function(){
            gScope.ConsultaCcusto.apagar();
            gScope.ConsultaCcontabil.apagar();
        });
    };

    
    var modal = $('#modal-cota-incluir');
    
    CotaIncluir.prototype.ModalShow = function(shown,hidden) {

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

    CotaIncluir.prototype.ModalClose = function(hidden) {

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
    
    CotaIncluir.prototype.events = function($event) {
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
            if ( that.INCLUINDO && cancel_bf_unload == false ) {
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
    return CotaIncluir;
};
angular
    .module('app')
    .factory('CotaCcusto', CotaCcusto);
    

	CotaCcusto.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaCcusto($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaCcusto(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        
		gScope.CotaCcusto = this; 
        
    }
    
    CotaCcusto.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        
        this.DATAHORA = {
            DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_22160/api/consumo-baixado',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    CotaCcusto.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixadoTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };
    
    CotaCcusto.prototype.checkVisibility = function(ccusto) {
        
        var periodos = ccusto.PERIODOS;
        
        ccusto.VISIBLE = false;
        
        for ( var i in periodos ) {
            var periodo = periodos[i];
            
            if ( !ccusto.VISIBLE && periodo.FILTERED != undefined && periodo.FILTERED.length > 0 ) {
                ccusto.VISIBLE = true;
                break;
            }
        }
        
    };
    
    
    CotaCcusto.prototype.toggleExpand = function(type) {
        
        
        var that = this;
        var bool = null;
        
        if ( type != undefined ) {
            bool = type;
        } else {
            if ( gScope.Filtro.EXPANDED == undefined || gScope.Filtro.EXPANDED == null || !gScope.Filtro.EXPANDED ) {
                bool = true;
            } else {
                bool = false;
            }
        }
        
        gScope.Filtro.EXPANDED = bool;
        
        for ( var i in that.DADOS ) {
            var ccusto = that.DADOS[i];
            
            ccusto.OPENED = bool;
            ccusto.VISIBLE = bool;
            
            for ( var j in ccusto.PERIODOS ) {
                var periodo = ccusto.PERIODOS[j];
                
                periodo.OPENED = bool;
            }
        }
        
    };

    /**
     * Return the constructor function
     */
    return CotaCcusto;
};
angular
    .module('app')
    .factory('CotaCcontabil', CotaCcontabil);
    

	CotaCcontabil.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaCcontabil($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaCcontabil(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        this.SELECTED = {};
        this.FILTRO = '';
		gScope.CotaCcontabil = this; 
        
    }
    
    CotaCcontabil.prototype.pick = function(ccontabil,setfocus) {
        
        var that = this;

        if ( ccontabil != undefined ) {
        
            this.SELECTED = ccontabil;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };    

    /**
     * Return the constructor function
     */
    return CotaCcontabil;
};
angular
    .module('app')
    .factory('CotaPeriodo', CotaPeriodo);
    

	CotaPeriodo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaPeriodo($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaPeriodo(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        
		gScope.CotaPeriodo = this; 
        
    }
    
    CotaPeriodo.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        
        this.DATAHORA = {
            DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_22160/api/consumo-baixado',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    CotaPeriodo.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixadoTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return CotaPeriodo;
};
angular
    .module('app')
    .factory('CotaDetalhe', CotaDetalhe);
    

	CotaDetalhe.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function CotaDetalhe($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function CotaDetalhe(data) {
        if (data) {
            this.setData(data);
        }
        
        this.DADOS = [];
        this.ITENS = [];
        
		gScope.CotaDetalhe = this; 
        
    }
    
    CotaDetalhe.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        
        this.DATAHORA = {
            DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_22160/api/consumo-baixado',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    CotaDetalhe.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixadoTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return CotaDetalhe;
};
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Cota',
        'CotaExtra',
        'CotaReducao',
        'CotaGgf',
        'CotaIncluir',
        'CotaCcusto',
        'CotaPeriodo',
        'CotaCcontabil',
        'CotaDetalhe',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Cota,
        CotaExtra,
        CotaReducao,
        CotaGgf,
        CotaIncluir,
        CotaCcusto,
        CotaPeriodo,
        CotaCcontabil,
        CotaDetalhe, 
        Historico
    ) {

		var vm = this;

		vm.Filtro        = new Filtro();
		vm.Cota          = new Cota();
		vm.CotaExtra     = new CotaExtra();
		vm.CotaReducao   = new CotaReducao();
		vm.CotaGgf       = new CotaGgf();
		vm.CotaIncluir   = new CotaIncluir();
		vm.CotaCcusto    = new CotaCcusto();
		vm.CotaPeriodo   = new CotaPeriodo();
		vm.CotaCcontabil = new CotaCcontabil();
		vm.CotaDetalhe   = new CotaDetalhe();
		vm.Historico     = new Historico('vm.Historico');


        vm.Consulta   = new $consulta();
        
        vm.ConsultaCcusto                        = vm.Consulta.getNew(true);
        vm.ConsultaCcusto.componente             = '.consulta-ccusto';
        vm.ConsultaCcusto.model                  = 'vm.ConsultaCcusto';
        vm.ConsultaCcusto.option.label_descricao = 'C. Custo:';
        vm.ConsultaCcusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.ConsultaCcusto.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.ConsultaCcusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcusto.compile();
        gScope.ConsultaCcusto = vm.ConsultaCcusto;
        
        vm.ConsultaCcontabil                        = vm.Consulta.getNew(true);
        vm.ConsultaCcontabil.componente             = '.consulta-ccontabil';
        vm.ConsultaCcontabil.model                  = 'vm.ConsultaCcontabil';
        vm.ConsultaCcontabil.option.label_descricao = 'C. Contábil:';
        vm.ConsultaCcontabil.option.obj_consulta    = '/_17010/api/ccontabil';
        vm.ConsultaCcontabil.option.tamanho_input   = 'input-maior';
        vm.ConsultaCcontabil.option.campos_tabela   = [['MASK', 'C. Contábil'],['DESCRICAO','Descrição']];
        vm.ConsultaCcontabil.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.ConsultaCcontabil.compile();
        vm.ConsultaCcontabil.setDataRequest({CCONTABIL_TIPO: 'analitica'});
        gScope.ConsultaCcontabil = vm.ConsultaCcontabil;
        

        loading('.main-ctrl');    
        $timeout(function(){
            vm.Filtro.consultar().then(function(){

                if ( vm.Filtro.COTA_ID > 0 || gScope.CotaCcusto.DADOS.length <= 3 ) {
                    vm.CotaCcusto.toggleExpand(true);
                }
                
                loading('hide');
                $timeout(function(){
                    if ( vm.Filtro.COTA_ID > 0 ) {
                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');

                        cota.focus();
                                       
                        var item = vm.Cota.SELECTED;

                        for ( var i in gScope.CotaCcusto.DADOS ) {
                            var cota = gScope.CotaCcusto.DADOS[i];

                            if ( cota.CCUSTO != item.CCUSTO ) {
                                cota.OPENED = false;
                                
                                for ( var j in cota.PERIODOS ) {
                                    var periodo = cota.PERIODOS[j];

                                    periodo.OPENED = false;
                                }
                            } else {
                                
                                for ( var j in cota.PERIODOS ) {
                                    var periodo = cota.PERIODOS[j];
                                    
                                    if ( periodo.PERIODO_DESCRICAO != item.PERIODO_DESCRICAO ) {
                                        periodo.OPENED = false;
                                    }
                                }                                
                            }
                        }

                        $timeout(function(){                 
                                    
                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
                                vm.Cota.dblPick(vm.Cota.SELECTED);
                            }
                        },100);
                    }
                },50);

            });

        },50);
	}   
  
//# sourceMappingURL=_13030.ng.js.map
