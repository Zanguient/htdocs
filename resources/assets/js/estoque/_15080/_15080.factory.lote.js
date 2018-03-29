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