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
    .factory('Remessa', Remessa);
    

	Remessa.$inject = [
        '$ajax',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Remessa($ajax, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Remessa(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Remessa = this; 
        
        this.DADOS                  = [];
        this.SELECTED               = {};
        this.FILTRO                 = '';
        this.FAMILIAS               = [];
        this.FAMILIAS_CHECKEDS      = [];
        this.FAMILIAS_CHECKEDS_LIST = '';
        this.CONSUMOS               = [];
        this.CONSUMO_PERCENTUAL     = '< 1';
        this.DATA_1                 = '01.01.1989'; 
        this.DATA_2                 = '01.01.2500';        
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
    
    
    Remessa.prototype.pick = function(remessa,setfocus) {
        
        var that = this;

        if ( remessa != undefined ) {

            if ( remessa != this.SELECTED ) {
                gScope.Talao.pick({});
            }        

            this.SELECTED       = remessa;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Remessa.prototype.click = function(remessa) {
        
        this.SELECTED != remessa ? this.pick(remessa) : '';
        
    };
    
    Remessa.prototype.dblClick = function() {
        $timeout(function(){
            $('#tab-visualizacao-por-consumo').click();
        });
    };
    
    Remessa.prototype.etiqueta = function(remessa,setfocus) {
        
        var that = this;

        $ajax.post('/_15070/api/etiqueta',that.SELECTED).then(function(response){
            
            postprint(response);
                        
        });

    };
    
    Remessa.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-remessa .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Remessa.prototype.consultarFamilia = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){     

            $ajax.post('/_15070/api/familia',{}).then(function(response){

                that.mergeFamilia(response);

                resolve(response);

            },function(erro){
                reject(erro);
            });
        });
    };

    Remessa.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){
            if ( that.FAMILIAS_CHECKEDS_LIST.trim() == '' ) {
                showErro('Selecione uma familia de produto.');
            } else {

                var data = {
                    REMESSA_FAMILIAS_ID : that.FAMILIAS_CHECKEDS_LIST,
                    CONSUMO_PERCENTUAL  : that.CONSUMO_PERCENTUAL,
                    PERIODO             : [moment(that.DATA_1).format('DD.MM.YYYY'),moment(that.DATA_2).format('DD.MM.YYYY')]
                };

                $ajax.post('/_15070/api/remessa',data).then(function(response){

                    that.merge(response);

                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
        
    };

    Remessa.prototype.consultarConsumos = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){
            if ( that.FAMILIAS_CHECKEDS_LIST.trim() == '' ) {
                showErro('Selecione uma familia de produto.');
            } else {

                var data = {
                    REMESSA_ID : that.SELECTED.REMESSA_ID
                };

                $ajax.post('/_15070/api/consumo',data).then(function(response){

                    that.mergeConsumo(response);
                    
                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
        
    };
    
    
    Remessa.prototype.merge = function(response) {

        gcCollection.merge(this.DADOS, response, 'REMESSA_ID');

    };    
    
    
    Remessa.prototype.mergeFamilia = function(response) {

        gcCollection.merge(this.FAMILIAS, response, 'REMESSA_FAMILIA_ID');

    };    
    
    
    Remessa.prototype.mergeConsumo = function(response) {

        gcCollection.merge(gScope.Consumo.DADOS, response, 'CONSUMO_ID');

        /**
         * Agrupa consumos para os talões
         */
        var taloes_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'REMESSA_TALAO_ID',
            'MODELO_ID',
            'MODELO_DESCRICAO',
            'COR_ID',
            'COR_DESCRICAO',
            'GRADE_ID',
            'TAMANHO',
            'TAMANHO_DESCRICAO',
            'QUANTIDADE_TALAO',
            'UM_TALAO'
        ],'CONSUMOS');

        gcCollection.merge(gScope.Talao.DADOS, taloes_consumos, ['REMESSA_ID','REMESSA_TALAO_ID']);

        /**
         * Agrupa consumos para os produtos
         */
        var produtos_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'CONSUMO_FAMILIA_ID',
            'CONSUMO_FAMILIA_DESCRICAO',
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_GRADE_ID',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'QUANTIDADE_ESTOQUE',
            'CONSUMO_UM',
            'CONSUMO_STATUS',
            'CONSUMO_STATUS_DESCRICAO',
            'CONSUMO_LOCALIZACAO_ID',
            'CONSUMO_LOCALIZACAO_ID_PROCESSO',
            'GP_CCUSTO'
        ],'TALOES');

        gcCollection.merge(gScope.Consumo.PRODUTOS, produtos_consumos, ['REMESSA_ID','CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);

        for ( var i in gScope.Consumo.PRODUTOS ) {
            var produto = gScope.Consumo.PRODUTOS[i];

            produto.QUANTIDADE          = 0;
            produto.QUANTIDADE_CONSUMO  = 0;
            produto.QUANTIDADE_SALDO    = 0;

            for ( var j in produto.TALOES ) {
                var talao = produto.TALOES[j];

                produto.QUANTIDADE          += parseFloat(talao.QUANTIDADE        );
                produto.QUANTIDADE_CONSUMO  += parseFloat(talao.QUANTIDADE_CONSUMO);
                produto.QUANTIDADE_SALDO    += parseFloat(talao.QUANTIDADE_SALDO  );

            }
        }


        gcCollection.bind(gScope.Remessa.DADOS, gScope.Talao.DADOS     , 'REMESSA_ID', 'TALOES');
        gcCollection.bind(gScope.Remessa.DADOS, gScope.Consumo.PRODUTOS, 'REMESSA_ID', 'PRODUTOS');

    };    
    
    
    Remessa.prototype.toggleCheckFamilia = function(item,type) {

        item.CHECKED = item.CHECKED ? false : true;

        if ( type != undefined ) {
            item.CHECKED = type == true ? true : false;
        }

        var index = this.FAMILIAS_CHECKEDS.indexOf(item);

        if ( index == -1 ) {
            this.FAMILIAS_CHECKEDS.push(item);   
        }                 
        else 
        if ( index > -1 ) {
            this.FAMILIAS_CHECKEDS.splice(index, 1);                      
        }
    
        this.FAMILIAS_CHECKEDS_LIST = arrayToList(this.FAMILIAS_CHECKEDS, 'REMESSA_FAMILIA_ID' );
        
    };   
    
    function arrayToList( array, field, str, val_def ) {
        
        val_def = val_def   || '';
        str     = str       || false;
        field   = field     || false;
        
        var list    = '';
        var i       = -1;

        if ( Array.isArray(array) ) {
            for ( var key in array ) {
                
                var o    = array[key];
                var item = '';

                i++;

                //Verifica se existe um campo com nome 
                if (field) {
                    o = o[field];
                }

                //Verifica se é uma string e pega o caractere
                if ( str ) {
                    item = str . o . str;
                } else {
                    item = o;
                }

                list = ( i == 0 ) ? item : list  + ', ' + item;
            }   
        } else {
            list = array;
        }

        return ( list != '' ) ? list : val_def;
    }  

    /**
     * Return the constructor function
     */
    return Remessa;
};
angular
    .module('app')
    .factory('Talao', Talao);
    

	Talao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Talao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Talao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Talao = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
    }
        
    Talao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {

            if ( talao != this.SELECTED ) {
                gScope.Consumo.pick({});
            }   

            this.SELECTED       = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Talao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-talao.table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Talao.prototype.setData = function(data) {
            angular.extend(this, data);
    };

    /**
     * Return the constructor function
     */
    return Talao;
};
angular
    .module('app')
    .factory('Consumo', Consumo);
    

	Consumo.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Consumo($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Consumo(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Consumo = this; 
        
        this.DADOS = [];
        this.PRODUTOS = [];
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



    Consumo.prototype.pick = function(consumo,setfocus) {
        
        var that = this;

        if ( consumo != undefined ) {
        
            this.SELECTED = consumo;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Consumo.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-consumo.table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Consumo.prototype.keypress = function($event) {

        var that = this;
        var is_enabled = true;
        
        $event.preventDefault();
                
        switch (gScope.Filtro.TAB_ACTIVE) {
            case 'TALAO':
                
                if ( gScope.Remessa.SELECTED.TALOES.indexOf(gScope.Talao.SELECTED) < 0 || gScope.Talao.SELECTED.CONSUMOS.indexOf(gScope.Consumo.SELECTED) < 0 || !(gScope.Consumo.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) || !(gScope.Consumo.SELECTED.QUANTIDADE_ESTOQUE > 0) ) {
                    is_enabled = false;
                }
                
                break;
                
            case 'CONSUMO':

                if ( gScope.Remessa.SELECTED.PRODUTOS.indexOf(gScope.Talao.SELECTED) < 0 || !(gScope.Talao.SELECTED.CONSUMO_LOCALIZACAO_ID_PROCESSO > 0) || !(gScope.Talao.SELECTED.QUANTIDADE_ESTOQUE > 0) ) {
                    is_enabled = false;
                }
                
                break;
        }
        
        if ( !is_enabled ) {
            return false;
        }
        
        switch ($event.key) {
            case ' ':

                that.ModalPeca.open();

                break;

            case 'Enter':

                that.ModalAvulso.open();

                break;

        }
    };
    
        
    /**
     * Extende propriedades para o objeto
     * @param {object} data
     * @returns {void}
     */
    Consumo.prototype.setData = function(data) {
            angular.extend(this, data);
    };


    Consumo.prototype.ModalPeca = {
        ITEM : {},
        PECA_BARRAS : '',
        open : function() {

            var that = this;

            switch (gScope.Filtro.TAB_ACTIVE) {
                case 'CONSUMO':

                    that.ITEM = gScope.Talao.SELECTED;
                    
                    that.show(function(){
                        that.selectInput();
                    },function(){
                        gScope.Talao.setFocus();
                        that.setEmptyInput();
                    });

                    break;
                case 'TALAO':

                    that.ITEM = gScope.Consumo.SELECTED;
                    
                    that.show(function(){
                        that.selectInput();
                    },function(){
                        gScope.Consumo.setFocus();
                        that.setEmptyInput();
                    });

                    break;

            }
        },
        confirm : function () {
            var that = this;

            var item = angular.copy(that.ITEM);
                    
            var dados = {};
            dados.DADOS = {};
            
            dados.FILTRO = gScope.Filtro;
            dados.DADOS.PECA_BARRAS = that.PECA_BARRAS;
            
            switch (gScope.Filtro.TAB_ACTIVE) {
                case 'CONSUMO':
                                        
                    dados.DADOS.ITENS = item.TALOES;
                    
                    that.enableButton(false);
                    
                    $ajax.post('/_15070/api/peca',dados,{complete: function(){
                            
                        that.enableButton(true);
                        
                    }}).then(function(response){
                        
                        gScope.Filtro.merge(response.DATA_RETURN);
                        
                        that.close(function(){
                            gScope.Talao.setFocus();
                        });
                        
                    },function(){
                        
                        that.setEmptyInput();
                        that.setFocus();
                        
                    });

                    break;
                case 'TALAO':
                 
                    dados.DADOS.ITENS = [item];
                    
                    that.enableButton(false);
                    
                    $ajax.post('/_15070/api/peca',dados,{complete: function(){
                            
                        that.enableButton(true);
                        
                    }}).then(function(response){
                        
                        gScope.Filtro.merge(response.DATA_RETURN);
                        that.close(function(){
                            gScope.Consumo.setFocus();
                        });
                        
                    },function(){
                        
                        that.setEmptyInput();
                        that.setFocus();
                        
                    });
                    
                    break;

            }
        },
        close : function (hidden) {

            $('#modal-registrar-saida-por-peca')
                .modal('hide')
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;        
        },
        show : function(shown,hidden) {

            $('#modal-registrar-saida-por-peca')
                .modal('show')
                .one('shown.bs.modal', function(){
                    shown ? shown() : '';
                })
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                         
        },
        selectInput : function() {        
            $timeout(function(){
                $('#modal-registrar-saida-por-peca input:focusable').first().select();
            },50);       
        },
        setFocusInput : function() {        
            $timeout(function(){
                $('#modal-registrar-saida-por-peca input:focusable').first().focus();
            },50);       
        },
        setEmptyInput : function() {        
            var that = this;
            
            that.PECA_BARRAS = '';
        },
        enableButton : function(bool) {
            $('#modal-registrar-saida-por-peca button').prop('disabled',!bool);
        }
    };

    Consumo.prototype.ModalAvulso = {
        ITEM : {},
        QUANTIDADE : '',
        open : function() {

            var that = this;
            
            switch (gScope.Filtro.TAB_ACTIVE) {
                case 'CONSUMO':

                    that.QUANTIDADE = gScope.Talao.SELECTED.QUANTIDADE_SALDO;
                    that.ITEM = gScope.Talao.SELECTED;
                                        
                    that.show(function(){
                        that.selectInput();
                    },function(){
                        gScope.Talao.setFocus();
                        that.setEmptyInput();
                    });

                    break;
                case 'TALAO':
                    
                    that.QUANTIDADE = gScope.Consumo.SELECTED.QUANTIDADE_SALDO;
                    that.ITEM = gScope.Consumo.SELECTED;

                    that.show(function(){
                        that.selectInput();
                    },function(){
                        gScope.Consumo.setFocus();
                        that.setEmptyInput();
                    });

                    break;

            }
            
            if ( parseFloat(that.QUANTIDADE) > parseFloat(that.ITEM.QUANTIDADE_ESTOQUE) ) {
                that.QUANTIDADE = that.ITEM.QUANTIDADE_ESTOQUE;
            }
            
        },
        confirm : function () {
            var that = this;

            var item = angular.copy(that.ITEM);
                    
            var dados = {};
            dados.DADOS = {};
            
            dados.FILTRO = gScope.Filtro;
            dados.DADOS.QUANTIDADE = that.QUANTIDADE;
            
            switch (gScope.Filtro.TAB_ACTIVE) {
                case 'CONSUMO':
                    
                    dados.DADOS.ITENS = item.TALOES;
                    
                    that.enableButton(false);
                    
                    $ajax.post('/_15070/api/avulso',dados,{complete: function(){
                            
                        that.enableButton(true);
                        
                    }}).then(function(response){
                        
                        gScope.Filtro.merge(response.DATA_RETURN);
                        
                        that.close(function(){
                            gScope.Talao.setFocus();
                        });
                    },function(){
                        that.selectInput();
                    });
                    

                    break;
                case 'TALAO':

                    dados.DADOS.ITENS = [item];
                 
                    that.enableButton(false);
                    
                    $ajax.post('/_15070/api/avulso',dados,{complete: function(){
                            
                        that.enableButton(true);
                        
                    }}).then(function(response){
                        
                        gScope.Filtro.merge(response.DATA_RETURN);
                        
                        that.close(function(){
                            gScope.Consumo.setFocus();
                        });
                    
                    },function(){
                        that.selectInput();
                    });
                    
                    break;

            }
        },
        close : function (hidden) {

            $('#modal-registrar-saida-avulsa')
                .modal('hide')
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;        
        },
        show : function(shown,hidden) {

            $('#modal-registrar-saida-avulsa')
                .modal('show')
                .one('shown.bs.modal', function(){
                    shown ? shown() : '';
                })
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                         
        },
        setFocusInput : function() {        
            $timeout(function(){
                $('#modal-registrar-saida-avulsa input:focusable').first().focus();
            },50);       
        },
        selectInput : function() {        
            $timeout(function(){
                $('#modal-registrar-saida-avulsa input:focusable').first().select();
            },50);       
        },
        setEmptyInput : function() {        
            var that = this;
            
            that.QUANTIDADE = '';
        },
        enableButton : function(bool) {
            $('#modal-registrar-saida-avulsa button').prop('disabled',!bool);
        }
    };

    Consumo.prototype.ModalTransacao = {
        DADOS : {
            AVULSA : [],
            PECA : []
        },
        FILTRO : {},
        dadosMerge : function (response) {
            
            var that = this;
            
            gcCollection.merge(that.DADOS.AVULSA,response.AVULSA);
            gcCollection.merge(that.DADOS.PECA,response.PECA);
        },
        consultar : function(remessa_id,remessa_talao_id,produto_id,tamanho){
            var that = this;
            
            that.FILTRO = {
                REMESSA_ID          : remessa_id,
                REMESSA_TALAO_ID    : remessa_talao_id,
                PRODUTO_ID          : produto_id,
                TAMANHO             : tamanho
            };
            
            $ajax.post('/_15070/api/transacao',that.FILTRO).then(function(response){
                
                that.dadosMerge(response);
                
                $('#modal-transacao').modal('show');
            });
        },
        excluirTransacao : function (transacao) {
            var that = this;
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir esta transação? <br/> <b>Atenção: A operação não poderá ser revertida!</b>',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){

                        $ajax.post('/_15070/api/transacao/delete',{
                            FILTRO: gScope.Filtro, 
                            FILTRO_TRANSACAO: that.FILTRO, 
                            DADOS: [transacao]
                        }).then(function(response){
                            
                            gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                            that.dadosMerge(response.DATA_RETURN);
                            
                            if ( !(that.DADOS.AVULSA.length > 0) && !(that.DADOS.PECA.length > 0) ) {
                                $('#modal-transacao').modal('hide');
                            }
                        });
                    });
                }}]     
            );
        }
    };

    /**
     * Return the constructor function
     */
    return Consumo;
};
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
//        this.REMESSA_FAMILIA_ID = 133;
        this.VISUALIZACAO = '1';
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        loading('.main-ctrl');        
        
        $ajax.post('/_15070/api/consumo',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
            loading('hide');
            
        });
    };
    
    
    Filtro.prototype.merge = function(response) {

        gcCollection.merge(gScope.Consumo.DADOS, response, 'CONSUMO_ID');


        /**
         * Configura a dependência de talões
         */

        /**
         * Agrupa consumos para os talões
         */
        var taloes_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'REMESSA_TALAO_ID',
            'MODELO_ID',
            'MODELO_DESCRICAO',
            'COR_ID',
            'COR_DESCRICAO',
            'GRADE_ID',
            'TAMANHO',
            'TAMANHO_DESCRICAO',
            'QUANTIDADE_TALAO',
            'UM_TALAO'
        ],'CONSUMOS');

        gcCollection.merge(gScope.Talao.DADOS, taloes_consumos, ['REMESSA_ID','REMESSA_TALAO_ID']);


        /**
         * Agrupa talões para as remessas
         */
        var taloes = gcCollection.groupBy(gScope.Talao.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO'
        ],'TALOES');

        gcCollection.merge(gScope.Remessa.DADOS, taloes, 'REMESSA_ID');



        /**
         * Configura a dependência de modelos
         */

        /**
         * Agrupa consumos para os talões
         */
        var produtos_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'CONSUMO_FAMILIA_ID',
            'CONSUMO_FAMILIA_DESCRICAO',
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_GRADE_ID',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'QUANTIDADE_ESTOQUE',
            'CONSUMO_UM',
            'CONSUMO_STATUS',
            'CONSUMO_STATUS_DESCRICAO',
            'CONSUMO_LOCALIZACAO_ID',
            'CONSUMO_LOCALIZACAO_ID_PROCESSO',
            'GP_CCUSTO'
        ],'TALOES');

        gcCollection.merge(gScope.Consumo.PRODUTOS, produtos_consumos, ['REMESSA_ID','CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);

        for ( var i in gScope.Consumo.PRODUTOS ) {
            var produto = gScope.Consumo.PRODUTOS[i];

            produto.QUANTIDADE          = 0;
            produto.QUANTIDADE_CONSUMO  = 0;
            produto.QUANTIDADE_SALDO    = 0;

            for ( var j in produto.TALOES ) {
                var talao = produto.TALOES[j];

                produto.QUANTIDADE          += parseFloat(talao.QUANTIDADE        );
                produto.QUANTIDADE_CONSUMO  += parseFloat(talao.QUANTIDADE_CONSUMO);
                produto.QUANTIDADE_SALDO    += parseFloat(talao.QUANTIDADE_SALDO  );

            }
        }

        gcCollection.bind(gScope.Remessa.DADOS, gScope.Consumo.PRODUTOS, 'REMESSA_ID', 'PRODUTOS');

        for ( var i in gScope.Remessa.DADOS ) {
            var remessa = gScope.Remessa.DADOS[i];

            remessa.QUANTIDADE          = 0;
            remessa.QUANTIDADE_CONSUMO  = 0;
            remessa.QUANTIDADE_SALDO    = 0;

            for ( var j in remessa.PRODUTOS ) {
                var produto = remessa.PRODUTOS[j];

                remessa.QUANTIDADE          += parseFloat(produto.QUANTIDADE        );
                remessa.QUANTIDADE_CONSUMO  += parseFloat(produto.QUANTIDADE_CONSUMO);
                remessa.QUANTIDADE_SALDO    += parseFloat(produto.QUANTIDADE_SALDO  );

                if ( remessa.QUANTIDADE_CONSUMO > remessa.QUANTIDADE ) {
                    remessa.QUANTIDADE_CONSUMO = remessa.QUANTIDADE;
                    remessa.QUANTIDADE_SALDO   = 0;
                }
            }
        }
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
        'Remessa',
        'Consumo',
        'Talao'
    ];

	function Ctrl( $scope, $timeout, gScope, Filtro, Remessa, Consumo,  Talao ) {

		var vm = this;

		vm.Filtro   = new Filtro();
		vm.Consumo  = new Consumo();
		vm.Remessa  = new Remessa();
		vm.Talao    = new Talao();

        loading('.main-ctrl');
        vm.Remessa.consultarFamilia().then(function(){
            loading('hide');    
        },function(){
            loading('hide');
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
  
//# sourceMappingURL=_15070.js.map
