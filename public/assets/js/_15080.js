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
    .factory('Lote', Lote);
    

	Lote.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Lote($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Lote(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Lote = this; 
        
        this.DADOS = [];
        this.PRODUTOS = [];
        this.LOCALIZACOES = [];
        this.LOCALIZACAO_ID = null;
        this.PRE_SELECTED = {};
        this.SELECTED = {};
        this.FILTRO = '';


        this.LOTES_GERADOS = {
            LOTE : [],
            DETALHE : []
        };

        this.FILTRO2 = '';
        this.DATA_1  = new Date(Clock.DATETIME_SERVER);
        this.DATA_2  = new Date(Clock.DATETIME_SERVER);
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_15070/api';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */



    Lote.prototype.acaoCheck = function(acao) {


        var ret         = {
            status    : true,
            descricao : ''
        };

        switch(acao) {
            case 'iniciar':

//                // Se não estiver na tela de produção
                if ( gScope.Lote.SELECTED.KANBAN_LOTE_ID > 0 ) {
                    ret.status = false;
                }
//                // Se não houver talao selecionado
//                if ( talao == undefined ) {
//                    ret.status = false;
//                } else                    
//                // Se não houver estacao selecionada
//                if ( gScope.Filtro.ESTACAO == '' ) {
//                    ret.status = false;
//                    ret.descricao = 'É necessário selecionar uma estação individual para iniciar um talão.';
//                } else                    
//                // Se estiver em produção 
//                if ( em_producao ) {
//                    ret.status = false;
//                } else
//                // Se houverem consumos não disponíveis
//                if ( talao.STATUS_MP_CP == '0' ) {
//                    ret.status    = false;
//                    ret.descricao = 'Há consumos com materia prima indisponível';
//                } else
//                // Se a remessa estiver fora do prazo para produção
//                if ( talao.REMESSA_TIPO == '1' ) {
//
//                    if ( gScope.Filtro.GP_REMESSA_DIAS >= 0 || $('#_pu212').val() == '0' ) {
//                        var remessas_normais = $filter('filter')(gScope.TalaoProduzir.DADOS,{REMESSA_TIPO : '1'});
//                        var remessas_normais = $filter('orderBy')(remessas_normais,['PROGRAMACAO_DATA', '+DATAHORA_INICIO', 'REMESSA_ID', 'REMESSA_TALAO_ID']);
//
//                        if ( $('#_pu212').val() == '0' ) {
//                            var idx = remessas_normais.indexOf(talao);
//
//                            if ( idx > 0 ) {
//                                ret.status    = false;
//                                ret.descricao = 'Usuário não possui permissão para quebrar sequenciamento de talões';
//                            }
//                        }
//
//                        if ( gScope.Filtro.GP_REMESSA_DIAS >= 0 ) {
//                            var data_base = remessas_normais[0] != undefined ? remessas_normais[0].REMESSA_DATA : null;
//
//
//                            var data_limite = moment(data_base).add(gScope.Filtro.GP_REMESSA_DIAS, 'days');
//
//                            if ( moment(talao.REMESSA_DATA) > data_limite ) {
//                                ret.status    = false;
//                                ret.descricao = 'Remessa fora do prazo permitido de ' + gScope.Filtro.GP_REMESSA_DIAS + ' dias. Produza remessas normais com data até ' + data_limite.format("DD/MM");
//                            }
//                        }
//                    }
//                }

                break;
            case 'finalizar':

//                // Se não estiver na tela de produção
                if ( !(gScope.Lote.SELECTED.KANBAN_LOTE_ID > 0) ) {
                    ret.status = false;
                }
//                if ( talao == undefined ) {
//                    ret.status    = false;
//                    ret.descricao = 'Selecione um talão';
//                } else                    
//                // Se estiver em produção 
//                if ( !em_producao ) {
//                    ret.status    = false;
//                }

                break;
            case 'continuar':

//                // Se não estiver na tela de produção
//                if ( gScope.Filtro.GUIA_ATIVA != 'TALAO_PRODUZIR' ) {
//                    ret.status    = false;
//                } else                 
//                if ( talao == undefined ) {
//                    ret.status    = false;
//                    ret.descricao = 'Selecione um talão';
//                } else                    
//                // Se estiver em produção 
//                if ( !em_producao ) {
//                    ret.status    = false;
//                }

                break;
        }

        return ret;

    };

    Lote.prototype.iniciar = function() {
        
        var that = this;
        
        $ajax.get('/_15080/api/localizacoes').then(function(response){
            that.LOCALIZACOES = response;  
            $('#modal-lote-iniciar').modal('show');
        });
        
    };

    Lote.prototype.getLotes = function() {
        
        var that = this;

        var paran = {
            DATA1 : moment(that.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATA2 : moment(that.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_15080/api/lotes_gerados',paran).then(function(response){

            gcCollection.merge(that.LOTES_GERADOS.LOTE    , response.LOTE               , 'KANBAN_LOTE_ID');
            gcCollection.merge(that.LOTES_GERADOS.DETALHE , response.DETALHE            , 'KANBAN_LOTE_DETALHE_ID');
            gcCollection.bind(that.LOTES_GERADOS.LOTE     , that.LOTES_GERADOS.DETALHE  , 'KANBAN_LOTE_ID', 'LOTE_DETALHE');

        });
        
    };


    Lote.prototype.excluirItem = function(item) {
        
        var that = this;

        var paran = {
            DATA1 : moment(that.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATA2 : moment(that.DATA_2).format('YYYY.MM.DD 23:59:59'),
            KANBAN_LOTE_DETALHE_ID : item.ID
        };
        
        $ajax.post('/_15080/api/lote/excluirItem',paran).then(function(response){
            gcCollection.merge(that.LOTES_GERADOS.LOTE    , response.LOTE               , 'KANBAN_LOTE_ID');
            gcCollection.merge(that.LOTES_GERADOS.DETALHE , response.DETALHE            , 'KANBAN_LOTE_DETALHE_ID');
            gcCollection.bind(that.LOTES_GERADOS.LOTE     , that.LOTES_GERADOS.DETALHE  , 'KANBAN_LOTE_ID', 'LOTE_DETALHE');
        });
        
    };
    
    Lote.prototype.iniciarConfirm = function(acao) {
        
        var that = this;
        
        return $q(function(resolve,reject){
            var data = {
                FILTRO: gScope.Filtro,
                DADOS: {
                    LOCALIZACAO_ID : that.LOCALIZACAO_ID
                }
            };

            $ajax.post('/_15080/api/lote/iniciar',data).then(function(response){

                gScope.Filtro.merge(response.DATA_RETURN.DADOS);

                that.SELECTED = response.DATA_RETURN.LOTE;

                for ( var i in gScope.Produto.FAMILIAS ) {
                    var familia = gScope.Produto.FAMILIAS[i];

                    familia.CHECKED = true;
                }                    
                
                $('#modal-lote-iniciar').modal('hide');
                
                resolve(response.DATA_RETURN);

            },function(erro){
                reject(erro);
            });
        });
    };

    Lote.prototype.continuar = function() {
        
        var that = this;
        
        var data = {
            KANBAN_LOTE_STATUS : "= '0'"
        };
        
        
        $ajax.post('/_15080/api/lotes',data).then(function(response){
            that.LOCALIZACOES = response;   
            
            $('#modal-lote-continuar').modal('show');
        });
        
    };

    Lote.prototype.continuarConfirm = function() {
       
        var that = this;
        
        return $q(function(resolve,reject){
            var data = {
                FILTRO: gScope.Filtro,
                DADOS:  that.PRE_SELECTED
            };

            $ajax.post('/_15080/api/lote/continuar',data).then(function(response){

                gScope.Filtro.merge(response.DATA_RETURN.DADOS);

                that.SELECTED = that.PRE_SELECTED;

                for ( var i in gScope.Produto.FAMILIAS ) {
                    var familia = gScope.Produto.FAMILIAS[i];

                    familia.CHECKED = true;
                }                    
                
            $('#modal-lote-continuar').modal('hide');
                
                resolve(response.DATA_RETURN);

            },function(erro){
                reject(erro);
            });
        });        
        
    };

    Lote.prototype.finalizar = function() {
        var that = this;
        
        return $q(function(resolve,reject){
            var data = {
                FILTRO: gScope.Filtro,
                DADOS: that.SELECTED
            };

            $ajax.post('/_15080/api/lote/finalizar',data).then(function(response){


                postprint(response.DATA_RETURN.ETIQUETAS);
                
                gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                that.SELECTED = {};
                resolve(response.DATA_RETURN);

            },function(erro){
                reject(erro);
            });
        });
    };

    Lote.prototype.imprimir = function(item) {
        var that = this;
        item.VISIVEL = item.VISIVEL == 1 ? 0 : 1;

        var data = {
                FILTRO: gScope.Filtro,
                DADOS : item
            };

        addConfirme('Imprimir','Deseja realmente imprimir LOTE:'+item.KANBAN_LOTE_ID,[obtn_sim,obtn_cancelar],
                   [
                       {ret:1,func:function(){
                            $ajax.post('/_15080/api/lote/imprimirLote',data).then(function(response){
                                postprint(response.DATA_RETURN.ETIQUETAS);
                            });
                       }},
                       {ret:2,func:function(){

                       }},
                   ]   
              );

        
    };

    

    Lote.prototype.cancelar = function() {
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente cancelar este lote?<br/><b>Obs: Transações já realizadas, serão perdidas.</b>',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){
                    
                    return $q(function(resolve,reject){
                        var data = {
                            FILTRO: gScope.Filtro,
                            DADOS: that.SELECTED
                        };

                        $ajax.post('/_15080/api/lote/cancelar',data).then(function(response){

                            gScope.Filtro.merge(response.DATA_RETURN.LOTE);
                            that.SELECTED = {};
                            resolve(response.DATA_RETURN);

                        },function(erro){
                            reject(erro);
                        });
                    });
                    
                });
            }}]     
        );        
        
        

    };
    
    

    Lote.prototype.pick = function(lote,setfocus) {
        
        var that = this;

        if ( lote != undefined ) {
        
            this.SELECTED = lote;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Lote.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-lotes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Lote.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Reposicao.Modal.open();

                break;

        }
    };
    
        
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Lote.prototype.setData = function(data) {
            angular.extend(this, data);
    };


    var modal = $('#modal-lote-iniciar');
    
    Lote.prototype.modalShow = function(shown,hidden) {

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

    Lote.prototype.modalClose = function(hidden) {

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
    return Lote;
};
angular
    .module('app')
    .factory('Reposicao', Reposicao);
    

	Reposicao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Reposicao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Reposicao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Reposicao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO   = '';
        this.PECA_BARRAS = '';
        this.QUANTIDADE = null;
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_15070/api';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    
    
    Reposicao.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.Lote.SELECTED,
            FILTRO_TRANSACAO: {
                KANBAN_LOTE_ID: gScope.Lote.SELECTED.KANBAN_LOTE_ID,
                ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID
            },
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
        
        $ajax.post('/_15080/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.Reposicao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.Produto.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    
    Reposicao.prototype.deleteTransacao = function (transacao) {
        var that = this;

        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta transação? <br/> <b>Atenção: A operação não poderá ser revertida!</b>',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){
                    
                    var dados = {
                        FILTRO: gScope.Lote.SELECTED,
                        FILTRO_TRANSACAO: {
                            KANBAN_LOTE_ID: gScope.Lote.SELECTED.KANBAN_LOTE_ID,
                            ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID
                        },
                        DADOS: {
                            ITENS : [transacao]
                        }
                    };

                    that.Modal.enableButton(false);

                    $ajax.post('/_15080/api/transacao/delete',dados,{complete: function(){

                        that.Modal.enableButton(true);

                    }}).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        gcCollection.merge(gScope.Reposicao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','ESTOQUE_MINIMO_ID','TABELA_NIVEL']);

                        gScope.Reposicao.QUANTIDADE = null;   
                        gScope.Reposicao.PECA_BARRAS = '';    
                    });        


                });
            }}]     
        );        
    };
    
    Reposicao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-reposicao .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Reposicao.prototype.setData = function(data) {
            angular.extend(this, data);
    };


    Reposicao.prototype.Modal = {
        modal : function () {
            return $('#modal-reposicao');
        },
        open : function(item,localizacao) {
            var that = this;

            gScope.Reposicao.SELECTED = gScope.Produto.SELECTED;

            $ajax.post('/_15080/api/transacao',gScope.Produto.SELECTED).then(function(response){
                gcCollection.merge(gScope.Reposicao.DADOS,response,['TIPO','ESTOQUE_MINIMO_ID','TABELA_NIVEL']);

                that.show(function(){

                    var inputs = that.modal().find('input:visible');

                    if ( inputs.length == 1 ) {
                        inputs.first().select();
                    }

                },function(){
                    gScope.Reposicao.QUANTIDADE = null;   
                    gScope.Reposicao.PECA_BARRAS = '';                          
                    gScope.Produto.setFocus();
                });
            });
        },
        
        close : function (hidden) {

            this.modal()
                .modal('hide')
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;        
        },
        show : function(shown,hidden) {
            
            var that = this;
            
            that.modal()
                .modal('show')
                .one('shown.bs.modal', function(){
                    shown ? shown() : '';
                })
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                         
        },
        inputQuantidade : function() {
            return this.modal().find('.input-quantidade');
        },
        inputPeca : function() {
            return this.modal().find('.input-peca');
        },
        enableButton : function(bool) {
            this.modal().find('button').prop('disabled',!bool);
        }
    };

    /**
     * Return the constructor function
     */
    return Reposicao;
};
angular
    .module('app')
    .factory('Produto', Produto);
    

	Produto.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Produto($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Produto(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Produto = this; 
        
        this.DADOS = [];
        this.PRODUTOS = [];
        this.LOCALIZACOES = [];
        this.FAMILIAS = [];
        this.FILTRO = '';
    }
    
    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_15070/api';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */



    Produto.prototype.filtrarMaiorQueZero = function(produto) {
        
        var that = this;

        var ret = false;
        
        if ( gScope.Filtro.NECESSIDADE == 'maior-que-zero' ) {
            if ( produto.ESTOQUE_NECESSIDADE > 0 ) {
                ret = true;
            }
        } else {
            ret = true;
        }
        
        return ret;

    };

    Produto.prototype.checkVisibility = function(produto) {
        
        var that = this;
        var ret  = true;
        
        for ( var i in that.FAMILIAS ) {
            var familia = that.FAMILIAS[i];
            
            if ( familia.FAMILIA_ID == produto.FAMILIA_ID ) {
                if ( !familia.CHECKED ) {
                    ret = false;
                }
                break;
            }
        }
        
        return ret;

    };

    Produto.prototype.pick = function(produto,setfocus) {
        
        var that = this;

        if ( produto != undefined ) {
        
            this.SELECTED = produto;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Produto.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-produtos .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Produto.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Reposicao.Modal.open();

                break;

        }
    };
    
        
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Produto.prototype.setData = function(data) {
            angular.extend(this, data);
    };


    /**
     * Return the constructor function
     */
    return Produto;
};
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
        this.VISUALIZACAO = '1';
        this.NECESSIDADE = 'maior-que-zero';
        this.LOCALIZACAO_ID = null;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){

            $ajax.post('/_15080/api/produto-estoque-minimo',that).then(function(response){

                that.merge(response);

                resolve(response);
            },function(){
                reject(reject);
            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
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
                       
        
        gcCollection.merge(gScope.Produto.DADOS, response, [
            'ESTABELECIMENTO_ID',
            'LOCALIZACAO_ID',
            'PRODUTO_ID',
            'TAMANHO'
        ]);


        /**
         * Configura a dependência de talões
         */

        /**
         * Agrupa consumos para os talões
         */
        var produtos = gcCollection.groupBy(gScope.Produto.DADOS,[
            'PRODUTO_ID',
            'PRODUTO_DESCRICAO',
            'GRADE_ID',
            'TAMANHO',
            'TAMANHO_DESCRICAO',
            'PRODUTO_LOCALIZACAO_ID',
            'PRODUTO_LOCALIZACAO_DESCRICAO',
            'FAMILIA_ID',
            'FAMILIA_DESCRICAO'
        ],'ESTOQUES');

        gcCollection.merge(gScope.Produto.PRODUTOS, produtos, ['PRODUTO_ID','TAMANHO']);

        /**
         * Agrupa consumos para os talões
         */
        var localizacoes = gcCollection.groupBy(gScope.Produto.DADOS,[
            'LOCALIZACAO_ID',
            'LOCALIZACAO_DESCRICAO'
        ],'PRODUTOS');

        gcCollection.merge(gScope.Produto.LOCALIZACOES, localizacoes, ['LOCALIZACAO_ID']);

        angular.copy(gScope.Produto.LOCALIZACOES, gScope.Lote.LOCALIZACOES);

        /**
         * Agrupa consumos para os talões
         */
        var familias = gcCollection.groupBy(gScope.Produto.DADOS,[
            'FAMILIA_ID',
            'FAMILIA_DESCRICAO'
        ],'PRODUTOS');

        gcCollection.merge(gScope.Produto.FAMILIAS, familias, ['FAMILIA_ID']);

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
        'Filtro',
        'Produto',
        'Reposicao',
        'Lote'
    ];

	function Ctrl( $scope, $timeout, gScope, Filtro, Produto, Reposicao, Lote ) {

		var vm = this;

		vm.Filtro    = new Filtro();
		vm.Produto   = new Produto();
		vm.Reposicao = new Reposicao();
		vm.Lote      = new Lote();


        loading('.main-ctrl');        

        loading('hide');

        vm.Filtro.consultar().then(function(){
            
            for ( var i in vm.Produto.FAMILIAS ) {
                var familia = vm.Produto.FAMILIAS[i];
                
                familia.CHECKED = true;
            }
        });
        
        
        
//        $timeout(function () {
//            var container = $(".main-container");
//            var numberOfCol = 2;
//            $(".resize").css('height', 100/numberOfCol +'%');
//
//            var sibTotalHeight;
//            $(".resize-item").resizable({
//                handles: 's',
//                start: function(event, ui){
//                    sibTotalHeight = ui.originalSize.height + ui.originalElement.next().outerHeight();
//                },
//                stop: function(event, ui){     
//                    var cellPercentHeight=100 * ui.originalElement.outerHeight()/ container.innerHeight();
//                    
//                    ui.originalElement.css('height', cellPercentHeight + '%');  
//                    
//                    var nextCell = ui.originalElement.next();
//                    
//                    var nextPercentHeight=100 * nextCell.outerHeight()/container.innerHeight();
//                    
//                    nextCell.css('height', nextPercentHeight + '%');
//                },
//                resize: function(event, ui){ 
////                    $(this).mouseup();
//                    
//                    if ( ui.size.height > ( container.innerHeight() - 130 ) ) {
//                        $(this).mouseup();
//                        ui.originalElement.height(container.innerHeight() - 130);
//                    } else {
//                        ui.originalElement.next().height(container.innerHeight() - ui.size.height); 
//                    }
//                    console.log(ui.size.height + ' ' + ui.originalElement.next().height());
//                },
//                minHeight : 130
//            });
//            
//        });   

 
	}   
  
//# sourceMappingURL=_15080.js.map
