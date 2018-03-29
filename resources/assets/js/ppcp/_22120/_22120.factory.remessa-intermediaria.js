angular
    .module('app')
    .factory('RemessaIntermediaria', RemessaIntermediaria);
    

	RemessaIntermediaria.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$filter',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function RemessaIntermediaria($ajax, $httpParamSerializer, $rootScope, $filter, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function RemessaIntermediaria(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.RemessaIntermediaria = this; 
        
        this.DADOS          = [];
        this.UPS            = [];
        this.ESTACOES       = [];
        this.TALOES         = [];
        this.TALOES_BKP     = [];
        this.TALOES_DETALHE = [];
        this.SELECTED  = {};
        this.PENDENTES = [];
        this.GRUPOS    = [];
        this.FILTRO    = {};

        this.OPERADOR_BARRAS = '';
    }
    

    RemessaIntermediaria.prototype.modalOpen = function(remessa) {
        
        this.FILTRO.REMESSA = remessa;
        this.FILTRO.REMESSA_SELECTED = true;
        
        
        $('#modal-remessa-intermediaria').modal('show');

    };     
    

    RemessaIntermediaria.prototype.modalClose = function(remessa) {
        
        this.FILTRO.REMESSA          = undefined;
        this.FILTRO.REMESSA_SELECTED = false;
        
        this.TALOES = [];
        this.TALOES_DETALHE = [];
        
        this.UPS            = [];
        this.ESTACOES       = [];
        
        
        $('#modal-remessa-intermediaria').modal('hide');

    };     
       

    RemessaIntermediaria.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };     
       
       
       

    RemessaIntermediaria.prototype.consultarRemessaIntermediariasVinculo = function(remessa) {
        
        var that = this;

        return $q(function(resolve,reject){

            $ajax.get('/_22120/api/remessas-vinculo?REMESSA_ID='+remessa)
                .then(function(response) {

                    that.merge(response);                
            
                    resolve(response);
                })
                .catch(function(e) {
                    reject(e);
                })
            ;
 
        });
    };     

    RemessaIntermediaria.prototype.consultarTaloesVinculo = function(remessa) {
        
        var that = this;

        var data = {};
        
        angular.copy(gScope.ConsultaRemessaVinculo, data);
        
        data.GP_ID = gScope.ConsultaGp.GP_ID;
        
        return $q(function(resolve,reject){

            $ajax.post('/_22120/api/taloes-vinculo',data)
                .then(function(response) {

                    that.dataMerge( response );
            
                    resolve(response);
                })
                .catch(function(e) {
                    reject(e);
                })
            ;
 
        });
    };     
       

    RemessaIntermediaria.prototype.dataMerge = function(response) {
        
        sanitizeJson(response.ESTACOES);
        sanitizeJson(response.TALOES);
        
        //////////////////////////////////////////////////////////
        gcCollection.merge(this.ESTACOES, response.ESTACOES, [
            'UP_ID', 'ESTACAO'
        ]);
        
        for ( var i in this.ESTACOES ) {
            this.ESTACOES[i].TALOES = [];
        }

        var ups = gcCollection.groupBy(this.ESTACOES, [
            'UP_ID',
            'UP_DESCRICAO'
        ], 'ESTACOES'); 

        gcCollection.merge(this.UPS, ups, ['UP_ID']);
 
        //////////////////////////////////////////////////////////
        
        
        
        //////////////////////////////////////////////////////////
        gcCollection.merge(this.TALOES_DETALHE, response.TALOES, [
            'ID'
        ]);

        var taloes = gcCollection.groupBy(response.TALOES, [
            'TALAO_CONTROLE',
            'TALAO_MODELO_ID',
            'TALAO_MODELO_DESCRICAO',
            'TALAO_TAMANHO',
            'TALAO_TAMANHO_DESCRICAO',
            'TALAO_COR_CLASSE',
            'TALAO_COR_SUBCLASSE',
            'TALAO_PERFIL_SKU',
            'TALAO_PERFIL_SKU_DESCRICAO',
            'TALAO_QUANTIDADE',
            'UM'
        ], 'ITENS'); 

        gcCollection.merge(this.TALOES, taloes, ['TALAO_CONTROLE']);
        //////////////////////////////////////////////////////////
        
    };     

    RemessaIntermediaria.prototype.processarAuto = function() {
                
        var that = this;
        
        
        var taloes = [];
         

        taloes = $filter('orderBy')(that.TALOES,['TALAO_COR_CLASSE*1','TALAO_COR_SUBCLASSE*1','-TALAO_QUANTIDADE*1']);

            for ( var j in taloes ) {
                var talao = taloes[j];


                var estacao_receiver = null;

                // Busca a estacao com menor quantidade
                for ( var i in that.UPS ) {
                    var up = that.UPS[i];

                    for ( var y in up.ESTACOES ) {
                        var estacao = up.ESTACOES[y];

                        // Verifica se o perfil da estação é compatível com do talão
                        if ( estacao.PERFIL_SKU_AUTO.indexOf( talao.TALAO_PERFIL_SKU ) != -1 ) {
                            if ( estacao_receiver == null ) {
                                estacao_receiver = estacao;
                            } 
                            else 
                            if ( estacao.QUANTIDADE < estacao_receiver.QUANTIDADE ) {
                                estacao_receiver = estacao;
                            }
                        }
                    }
                }

                if ( estacao_receiver != null ) {
                    
                   
                    talao.PROGRAMADO = true;
                    estacao_receiver.TALOES.push(talao);
                    that.estacaoQuantidade(estacao_receiver);
                }
            }
            
        taloes = $filter('orderBy')(taloes,['TALAO_CONTROLE']);
        
        angular.extend(that.TALOES,taloes);
        
        
        
//        var taloes = $filter('orderBy')(remessas_normais,['PROGRAMACAO_DATA', '+DATAHORA_INICIO', 'REMESSA_ID', 'REMESSA_TALAO_ID']);        ;
    };
    
    

    RemessaIntermediaria.prototype.checkGravar = function(estacao) {
        
        var that = this;
        
        var ret = true;
        
        for ( var i in that.TALOES ) {
            var talao = that.TALOES[i];
            
            if ( talao.PROGRAMADO ) {
                ret = false;
                break;
            }
        }
        
        return ret;
    };
    

    RemessaIntermediaria.prototype.gravar = function() {
        var that = this;

        var data = {};
        
        angular.copy(gScope.ConsultaRemessaVinculo, data);
        
        data.GP_ID  = gScope.ConsultaGp.GP_ID;
        data.UPS    = that.UPS;
        
        var data = {
            DADOS : data,
            FILTRO : { remessa : that.FILTRO.REMESSA }
        };
        
        $ajax.post('/_22120/api/remessa/intermediaria',data).then(function(response){

            gScope.Estrutura.loadData(response.DATA_RETURN.DADOS);
            that.modalClose();
            

        });

    };
    
    RemessaIntermediaria.prototype.estacaoQuantidade = function(estacao) {
        
        var taloes = estacao.TALOES;
        
        estacao.QUANTIDADE = 0;
        estacao.QUANTIDADE_UM = '';
        
        for ( var i in taloes ) {
            var talao = taloes[i];
            
            estacao.QUANTIDADE += talao.TALAO_QUANTIDADE;
            
            if ( estacao.QUANTIDADE_UM == '' ) {
                estacao.QUANTIDADE_UM = talao.UM;
            }
        }

        return estacao.QUANTIDADE;
    };


         
    
    
    RemessaIntermediaria.prototype.clearData = function() {
        gScope.Filtro.CODIGO_BARRAS = ''; 
        this.DADOS = []; 
        this.ITENS = [];
        
        $('.input-codigo-barras:focusable').first().focus();
    };     
    
    

    RemessaIntermediaria.prototype.keypress = function(item,$event) {
        
        if ( $event.key == ' ' ) {
            
            $event.preventDefault();
            
            this.toggleCheck(item);
        } else        
        if ( $event.key == 'Enter' ) {
            
            $event.preventDefault();
            
            
            if ( this.ITENS.length > 0) {
                this.modalOperador.show();
            }
        }
    };     

    RemessaIntermediaria.prototype.modalOperador = {
        _modal : function () {
            return $('#modal-autenticar-operador');
        },
        show : function(shown,hidden) {

            this._modal()
                .modal('show')
            ;                         

            
            this._modal()
                .one('shown.bs.modal', function(){

                    $(this).find('input:focusable').first().focus();

                    if ( shown ) {
                        shown(); 
                    }
                })
            ;    

                this._modal()
                    .one('hidden.bs.modal', function(){
                        gScope.RemessaIntermediaria.OPERADOR_BARRAS = '';
                
                        if ( hidden ) {
                            hidden();      
                        }
                    })
                ;        
        },
        hide : function(hidden) {

            this._modal()
                .modal('hide')
            ;

            if ( hidden ) {
                this._modal()
                    .one('hidden.bs.modal', function(){
                        hidden ? hidden() : '';
                    })
                ;                      
            }
        }
    };     
    
    
    /**
     * Return the constructor function
     */
    return RemessaIntermediaria;
};