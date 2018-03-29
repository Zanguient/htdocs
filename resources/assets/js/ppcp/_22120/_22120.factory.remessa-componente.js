angular
    .module('app')
    .factory('RemessaComponente', RemessaComponente);
    

	RemessaComponente.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$filter',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function RemessaComponente($ajax, $httpParamSerializer, $rootScope, $filter, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function RemessaComponente(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.RemessaComponente = this; 
        
        this.DADOS          = [];
        this.UPS            = [];
        this.ESTACOES       = [];
        this.SKUS           = [];
        this.ORIGENS        = [];
        this.TALOES_DETALHE = [];
        this.SELECTED  = {};
        this.PENDENTES = [];
        this.GRUPOS    = [];
        this.FILTRO    = {};

        this.OPERADOR_BARRAS = '';
    }

    RemessaComponente.prototype.modalOpen = function(remessa) {
        
        var that = this;
        
        $('#modal-remessa-componente')
            .modal('show')
            .one('shown.bs.modal', function(){

                if ( !that.FILTRO.AUTO_FILTER ) {
                    $(this).find('#remessa-componente-tipo').first().focus();
                }
        
            })
            .one('hide.bs.modal', function(){
                $rootScope.$apply(function(){
                    
                });
            })
            .one('hidden.bs.modal', function(){
                
            })
        ;           

    };     
    

    RemessaComponente.prototype.modalClose = function(remessa) {
        
        
        this.FILTRO.REMESSA          = undefined;
        this.FILTRO.REMESSA_SELECTED = false;
        
        this.TALOES = [];
        this.TALOES_DETALHE = [];
        
        this.UPS            = [];
        this.ESTACOES       = [];
        
        
        $('#modal-remessa-componente').modal('hide');

    };     
       

    RemessaComponente.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

        }

    };     
       
       
       

    RemessaComponente.prototype.consultarOrigemDados = function(remessa) {
        
        var that = this;

        return $q(function(resolve,reject){

            var data = {};
            
            angular.copy(that.FILTRO,data);
            
            $ajax.post('/_22120/api/origem-dados',data,{progress:false})
                .then(function(response) {


                    if ( response.FAMILIAS.length > 0 ) {

                        that.FILTRO.FAMILIAS_ID = arrayToList(response.FAMILIAS,'FAMILIA_ID');
                        that.FILTRO.REMESSA_ID  = response.FAMILIAS[0].REMESSA_ID;
                        that.FILTRO.REQUISICAO  = response.FAMILIAS[0].REQUISICAO;
    //                    that.merge(response);                

                        that.FILTRO.ORIGEM_SELECTED = true;

                        gScope.RcConsultaGp.filtrar();
                    }

                    
                    resolve(response);
                })
                .catch(function(e) {
                    reject(e);
                })
            ;
 
        });
    };     

    RemessaComponente.prototype.consultarTaloesVinculo = function(remessa) {
        
        var that = this;

        var data = {};
        
        angular.copy(that.FILTRO, data);
        
        data.GP_ID      = gScope.RcConsultaGp.GP_ID;
        data.FAMILIA_ID = gScope.RcConsultaGp.GP_FAMILIA_ID;
        
        return $q(function(resolve,reject){

            $ajax.post('/_22120/api/origem-necessidade',data)
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
       

    RemessaComponente.prototype.dataMerge = function(response) {
        
        sanitizeJson(response.ESTACOES);
        sanitizeJson(response.SKUS);
        sanitizeJson(response.ORIGENS);
        
        //////////////////////////////////////////////////////////
        gcCollection.merge(this.ESTACOES, response.ESTACOES, [
            'UP_ID', 'ESTACAO'
        ]);
        
        gcCollection.merge(this.SKUS, response.SKUS, [
            'ID'
        ]);        
        
        if ( response.CONSUMOS != undefined ) {

            gcCollection.merge(this.ORIGENS, response.CONSUMOS, [
                'ID'
            ]);    
        }
        
        
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


        if ( this.ORIGENS.length > 0 ) {
            if ( this.SKUS[0].ID == undefined || this.SKUS[0].ID == null ) {
                
                gcCollection.bind(this.SKUS, this.ORIGENS, ['MODELO_ID','TAMANHO','COR_ID'], 'ORIGENS');
            } else {
                gcCollection.bind(this.SKUS, this.ORIGENS, 'ID', 'ORIGENS');
            }
        }

        
        
    };     

    RemessaComponente.prototype.processarAuto = function() {
                
        var that = this;
        
        /**
         * Insere a estação fake se não existir
         */
        if ( !(this.FAKE_IDX > 0) ) {

            var fake_up = {
                UP_ID : -1000,
                UP_DESCRICAO : 'A FAKE',
                ESTACOES : [
                    {
                        ESTACAO : -1000,
                        ESTACAO_DESCRICAO : 'FAKE',
                        TALOES: []
                    }
                ]
            };

            this.UPS.push(fake_up);

            this.FAKE_IDX = this.UPS.indexOf(fake_up);        
        }
        
        /**
         * Limpa as estações
         */
        for ( var i in that.UPS ) {
            var up = that.UPS[i];
            
            for ( var j in up.ESTACOES ) {
                var estacao = up.ESTACOES[j];
                
                estacao.TALOES = [];
            }
        }
        
        var taloes = [];
         
        /**
         * Copia os dados originais para serem tratados
         */
        angular.copy(that.SKUS,taloes);
           
        /**
         * Função para gerar um id random para o item programado
         */
        function makeid() {
          var text = "";
          var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

          for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

          return text;
        }      
        
        /**
         * Identificação da estação fake
         */
        var fake_estacao = that.UPS[that.FAKE_IDX].ESTACOES[0].TALOES;

        
        if ( gScope.RcConsultaGp.GP_HABILITA_QUEBRA_TALAO_SKU == 0 ) {
            
            var taloes = gcCollection.groupBy(that.SKUS, [
                'ACRESCIMO'            ,
                'DENSIDADE'            ,
                'ESPESSURA'            ,
                'FATOR_DIVISAO'        ,
                'FATOR_DIVISAO_DETALHE',
                'GRADE_ID'             ,
                'LOCALIZACAO_ID'       ,
                'MODELO_DESCRICAO'     ,
                'MODELO_ID'            ,
                'PERFIL_SKU'           ,
                'PERFIL_SKU_DESCRICAO' ,
                'TAMANHO'              ,
                'TAMANHO_DESCRICAO'    ,
                'TIPO'                 ,
                'UM'                   
            ], 'SKUS_GROUP',function(modelo,sku){
                
                if ( modelo.QUANTIDADE == undefined ) {
                    modelo.QUANTIDADE = 0;
                }
                
                if ( modelo.ORIGENS == undefined ) {
                    modelo.ORIGENS = [];
                }
                
                modelo.QUANTIDADE += sku.QUANTIDADE;
                
                for ( var i in sku.ORIGENS ) {
                    var origem = sku.ORIGENS[i];
                    
                    modelo.ORIGENS.push(origem);
                }
            }); 
            
        }
        
        console.log(taloes);
        
//        return false;
        
        
        /**
         * Passa em todos os itens a serem programados
         */
        for ( var i in taloes ) {
            var talao = taloes[i];
            
            
            /**
             * Captura o saldo a programar
             */
            var saldo = talao.QUANTIDADE_PROGRAMAR || talao.QUANTIDADE;
            

            var origens = [];

            angular.copy(talao.ORIGENS,origens);
       
            
            /**
             * Aplica um laço que irá programar toda a quantidade do item 
             * francionando em taloes que atendem a cota acumulada e não particionando a origem em mais de um talão
             */
            do {
//                processarSaldo(talao);

                talao.ORIGENS = [];     
                var origem_quantidade = 0;

                
                for (var j = 0; j < origens.length; j++) {
                    
                    var origem = origens[j];
                    
                    origem_quantidade += parseFloat(origem.QUANTIDADE);
                    
                    if ( origem_quantidade > talao.FATOR_DIVISAO ) {
                        origem_quantidade -= parseFloat(origem.QUANTIDADE);
                        break;                        
                    } else {
                        talao.ORIGENS.push(origem);
                        
                        origens.splice(j,1);
                        j--;
                    }
                }
                
                if ( origem_quantidade == 0 ) {
                    saldo = 0;
                } else {
                    saldo -= origem_quantidade;

                    var item_programado = {};

                    angular.copy(talao,item_programado);

                    item_programado.ID_REFER = makeid();
                    item_programado.QUANTIDADE = origem_quantidade;

                    fake_estacao.push(item_programado);
                }
                
                
               
            }
            while ( saldo > 0 );
        }


        taloes = fake_estacao;
        
        

        
        for ( var j in taloes ) {
            var talao = taloes[j];


            var estacao_receiver = null;

            // Busca a estacao com menor quantidade
            for ( var i in that.UPS ) {
                var up = that.UPS[i];

                for ( var y in up.ESTACOES ) {
                    var estacao = up.ESTACOES[y];

                    // Verifica se o perfil da estação é compatível com do talão
                    if ( estacao.PERFIL_SKU_AUTO != undefined && estacao.PERFIL_SKU_AUTO != null && estacao.PERFIL_SKU_AUTO.indexOf( talao.PERFIL_SKU ) != -1 ) {
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

    };
    
    

    RemessaComponente.prototype.checkGravar = function(estacao) {
        
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
    

    RemessaComponente.prototype.gravar = function() {
        var that = this;

        var data = {};
        
        angular.copy(gScope.ConsultaRemessaVinculo, data);
        
        data.GP_ID  = gScope.ConsultaGp.GP_ID;
        data.UPS    = that.UPS;
        
        var data = {
            DADOS : data,
            FILTRO : { remessa : that.FILTRO.REMESSA }
        };
        
        $ajax.post('/_22120/api/remessa/componente',data).then(function(response){

            gScope.Estrutura.loadData(response.DATA_RETURN.DADOS);
            that.modalClose();
            

        });

    };
    
    RemessaComponente.prototype.estacaoQuantidade = function(estacao) {
        
        var taloes = estacao.TALOES;
        
        estacao.QUANTIDADE = 0;
        estacao.QUANTIDADE_UM = '';
        
        for ( var i in taloes ) {
            var talao = taloes[i];
            
            estacao.QUANTIDADE += talao.QUANTIDADE;
            
            if ( estacao.QUANTIDADE_UM == '' ) {
                estacao.QUANTIDADE_UM = talao.UM;
            }
        }

        return estacao.QUANTIDADE;
    };


         
    
    
    RemessaComponente.prototype.clearData = function() {
        gScope.Filtro.CODIGO_BARRAS = ''; 
        this.DADOS = []; 
        this.ITENS = [];
        
        $('.input-codigo-barras:focusable').first().focus();
    };     
    
    

    RemessaComponente.prototype.keypress = function(item,$event) {
        
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

    RemessaComponente.prototype.modalOperador = {
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
                        gScope.RemessaComponente.OPERADOR_BARRAS = '';
                
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
    return RemessaComponente;
};