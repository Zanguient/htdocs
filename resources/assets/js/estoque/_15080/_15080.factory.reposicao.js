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