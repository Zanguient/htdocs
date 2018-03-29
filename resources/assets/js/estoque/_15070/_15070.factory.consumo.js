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