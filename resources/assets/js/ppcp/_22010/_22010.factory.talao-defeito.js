angular
    .module('app')
    .factory('TalaoDefeito', TalaoDefeito);
    

	TalaoDefeito.$inject = [        
        '$ajax',
        '$q',
        '$timeout',
        'gScope',
        'gcObject',
        'gcCollection'
    ];

function TalaoDefeito($ajax,$q,$timeout,gScope,gcObject,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoDefeito(data) {        
        if (data) {
            this.setData(data);
        }
        
        this.dynanmicEvents();
    }

        
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/consumo';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoDefeito.prototype = {
        justificativa: null,
        justiOperador: null,
        OBJ_EDITING : {
            QUANTIDADE : null,
            OBSERVACAO : ''
        },
        openRegistrarProblema : function () {
            that = this;

            var dados_autenticacao = {
                operacao_id   : 29,
                modal_show    : true,
                verificar_up  : gScope.Filtro.UP_ID,
                success       : function(e) {

                    that.justiOperador = e.OPERADOR_ID;
                    that.justificativas();

                    $('#modal-justificar').modal();
                }
            };

            autenticacao(dados_autenticacao);
            
        },
        justificar: function(descricao, justificativa){
            var that = this;

            addConfirme('Justificativa',
                'Observação para o registro <b>' + descricao + '<b>:'+
                '<p>'+
                '<input type="search" class="form-control input-medio justificativa_ineficiencia_reg" maxlength="90" autocomplete="off">'+
                ''

                ,[obtn_ok,obtn_cancelar],
            [
                {ret:1,func:function(e){

                    var ds = {
                            TABELA_ID       : gScope.TalaoProduzir.SELECTED.REMESSA_TALAO_ID,
                            TABELA          : 'PRODUCAO',
                            STATUS          : justificativa,
                            VINCULO_ID      : gScope.TalaoProduzir.SELECTED.REMESSA_ID,
                            SUBVINCULO_ID   : gScope.TalaoProduzir.SELECTED.ID,
                            OPERADOR_ID     : that.justiOperador,
                            OBSERVACAO      : $('.justificativa_ineficiencia_reg').val()
                        };

                    $ajax.post('/_22130/justIneficiencia',JSON.stringify(ds),{contentType: 'application/json'})
                        .then(function(response) {
                            
                            if(response.length > 0){
                                if((gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA + '').length > 0 && gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA != null){
                                    gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA = gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA +',<br>'+ descricao + ' - '+ ds.OBSERVACAO ;
                                }else{
                                    gScope.TalaoProduzir.SELECTED.JUSTIFICATIVA = descricao + ' - '+ ds.OBSERVACAO ;
                                }
                            }

                            $('#modal-justificar').modal('hide');

                            showSuccess('Talão Justificado!');
                        }
                    );

                }},
                {ret:2,func:function(e){


                }},
                ]  
            );

            setTimeout(function(){$('.justificativa_ineficiencia_reg').focus();},300);
        },
        justificativas: function(){
            that = this;
            $ajax.post('/_22010/consultaJustificativa',{}).then(function(response){
                that.justificativa = response;       
            });
        },
        registrar : function () {
            
            var that          = this;
            var talao_detalhe = gScope.TalaoDetalhe.SELECTED;
            var objDefeito    = this.API.DEFEITOS;
            that.OBJ_EDITING = {};
            if ( talao_detalhe.QUANTIDADE_PRODUCAO > 0 ) {
                showErro('Para registrar defeitos, é necessário que não haja quantidade produzida. Operação cancelada.');
                return false;
                
            }
            
            that.OBJ_EDITING = {};                                     
            
            objDefeito.consultar().then(function(){
                
                if ( !(objDefeito.DADOS.length > 0) ) {
                    showErro('Não há defeitos cadastrados para esta familía de produtos');
                    return false;
                }  
            
                $('#modal-registrar-defeito')
                    .modal('show')
                    .one('shown.bs.modal', function(){
                            var table = $(this).find('input').first().focus();                        
                    })
                ;
            });
       

        },
        gravar : function () {
            
            var that = this;
            
            that.OBJ_EDITING.DEFEITO_ID               = that.API.DEFEITOS.SELECTED.DEFEITO_ID;
            that.OBJ_EDITING.ESTABELECIMENTO_ID       = gScope.Filtro.ESTABELECIMENTO_ID;
            that.OBJ_EDITING.GP_ID                    = gScope.Filtro.GP_ID;
            that.OBJ_EDITING.REMESSA_ID               = gScope.TalaoDetalhe.SELECTED.REMESSA_ID;
            that.OBJ_EDITING.REMESSA_TALAO_DETALHE_ID = gScope.TalaoDetalhe.SELECTED.REMESSA_TALAO_DETALHE_ID;
            that.OBJ_EDITING.PRODUTO_ID               = gScope.TalaoDetalhe.SELECTED.PRODUTO_ID;
            that.OBJ_EDITING.TAMANHO                  = gScope.TalaoDetalhe.SELECTED.TAMANHO;
            that.OBJ_EDITING.OPERADOR_ID              = $('#_operador-id').val();
            
            return $q(function(resolve){
                $ajax.post('/_22010/api/defeitos/post',that.OBJ_EDITING).then(function(){
                    
                    gScope.TalaoProduzir.current(true).then(function(){

                        $('#modal-registrar-defeito')
                            .modal('hide');                    
                    });   
                    
                    resolve(true);
                });
            });
            

        },
        excluir : function (id) {
            var that = this;

            return $q(function(resolve){
                $ajax.post('/_22010/api/defeitos/exclude',{DEFEITO_TRANSACAO_ID : id}).then(function(){
                    
                    $('.popover').remove();
                    
                    gScope.TalaoProduzir.current(true).then(function(){

                        $('#modal-registrar-defeito')
                            .modal('hide');                    
                    });   
                    
                    resolve(true);
                });
            });
        },
        dynanmicEvents : function () {
            
            var that = this;
            
            /**
             * Ativa o evento de exclusão de defeitos
             */
            $(document).off('click', '.defeito-excluir').on('click', '.defeito-excluir', function() {
                that.excluir($(this).data('item-id'));
            });      
        },
        API : {
            DEFEITOS : {
                DADOS : [],
                SELECTED : {},
                M_FILTRO : '',
                consultar : function () {
                    var that = this;
                    var objDefeito = this;
                    return $q(function(resolve){

                        that.M_FILTRO = '';
                        that.SELECTED = null;
                        
                        var data = {
                            FAMILIA_ID : gScope.TalaoDetalhe.SELECTED.FAMILIA_ID
                        };
                        
                        $ajax.post('/_22010/api/defeitos', data).then(function(resposta){

                            objDefeito.DADOS = resposta;

                            $timeout(function(){
                                if ( that.FILTERED.length > 0 ) {
                                    that.SELECTED = that.FILTERED[0];
                                }
                            });

                            resolve(true);
                        });  
                    });   
                },
                selecionar : function (defeito) {
                    this.SELECTED = defeito;
                },
                keydown : function (defeito, $event) {
                    
                    /* Verifica se existe um evento */
                    if ( !($event === undefined) ) {

                        if ( $event.key == 'Enter' ) {
                            var table = $('#modal-registrar-defeito .table-registrar-defeito');
                            var input = table.find('input').first();
                            
                            input.focus();
                        }
                    }    
                },
                mFiltroChange : function(oldValue) {
                    var that = this;
                    
                    if ( that.M_FILTRO.length > oldValue.length ) {
                        $timeout(function(){
                            if ( that.FILTERED.length > 0 ) {
                                that.SELECTED = that.FILTERED[0];
                            }
                        });
                    }
                }
            }
        },
        selectionar : function (consumo) {
            this.SELECTED = consumo;
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function fn () {
        
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoDefeito.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoDefeito.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Defeito(
            data.first_name,
            data.last_name,
            data.role
//            Organisation.build(data.organisation) // another model
        );
    };

    /**
     * Return the constructor function
     */
    return TalaoDefeito;
};