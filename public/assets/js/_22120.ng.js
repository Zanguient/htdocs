'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'ngSanitize',
        'angular.filter',
        'datatables'        
	])
;
     
     

angular.module('app').directive('bsInit', function() {
    return function(scope, element, attrs) {         
        bootstrapInit();
    };
});

angular.module('app').directive('stringToNumber', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(value) {
                return '' + value;
            });
            ngModel.$formatters.push(function(value) {
                return parseFloat(value);
            });
        }
    };
});

angular.module('app').directive('ngUpdateHidden', function () {
    return {
        restrict: 'AE', //attribute or element
        scope: {},
        replace: true,
        require: 'ngModel',
        link: function (vm, elem, attr, ngModel) {
            vm.$watch(ngModel, function (nv) {
                elem.val(nv);
            });
            elem.change(function () { //bind the change event to hidden input
                vm.$apply(function () {
                    ngModel.$setViewValue(  elem.val());
                });
            });
        }
    };
});

angular.module('app').directive('ngRightClick', ['$parse',function($parse) {
    return function(scope, element, attrs) {
        var fn = $parse(attrs.ngRightClick);
        element.bind('contextmenu', function(event) {
            scope.$apply(function() {
                event.preventDefault();
                fn(scope, {$event:event});
            });
        });
    };
}]);


angular.module('app').filter('parseDate', function() {
    return function(input) {
        if ( input ) return new Date(input);
    };
});     
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
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$ajax',
        '$filter',
        '$timeout',
        '$sce',
        '$consulta',
        'gcCollection', 
        'Historico',
        'gScope',
        'RemessaIntermediaria',
        'RemessaComponente'
    ];

	function Ctrl( 
        $scope, 
        $ajax,
        $filter,
        $timeout, 
        $sce,
        $consulta,
        gcCollection, 
        Historico,
        gScope,
        RemessaIntermediaria,
        RemessaComponente
    ) {

		var vm = this;
        
		vm.RemessaIntermediaria = new RemessaIntermediaria();
		vm.RemessaComponente    = new RemessaComponente();
        vm.Consulta             = new $consulta();
        
        
        vm.ConsultaRemessaVinculo                             = vm.Consulta.getNew(true);
        vm.ConsultaRemessaVinculo.componente                  = '.consulta-remessa-vinculo';
        vm.ConsultaRemessaVinculo.model                       = 'vm.ConsultaRemessaVinculo';
        vm.ConsultaRemessaVinculo.option.label_descricao      = 'Remessa Vinculada:';
        vm.ConsultaRemessaVinculo.option.obj_consulta         = '/_22120/api/remessas-vinculo';
//        vm.ConsultaRemessaVinculo.option.tamanho_input        = '';
//        vm.ConsultaRemessaVinculo.option.tamanho_tabela       = 427;
        vm.ConsultaRemessaVinculo.option.campos_tabela        = [['REMESSA', 'REMESSA']];
        vm.ConsultaRemessaVinculo.option.obj_ret              = ['REMESSA'];
//        vm.ConsultaRemessaVinculo.require                     = vm.Remessa;
//        vm.ConsultaRemessaVinculo.vincular();
        vm.ConsultaRemessaVinculo.setDataRequest({REMESSA: [vm.RemessaIntermediaria.FILTRO, 'REMESSA']});
        vm.ConsultaRemessaVinculo.compile();
        gScope.ConsultaRemessaVinculo = vm.ConsultaRemessaVinculo;


        vm.ConsultaGp                             = vm.Consulta.getNew(true);
        vm.ConsultaGp.componente                  = '.consulta-gp';
        vm.ConsultaGp.model                       = 'vm.ConsultaGp';
        vm.ConsultaGp.option.label_descricao      = 'GP:';
        vm.ConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.ConsultaGp.option.tamanho_input        = 'input-maior';
        vm.ConsultaGp.option.tamanho_tabela       = 427;
        vm.ConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.ConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.ConsultaGp.require                     = vm.ConsultaRemessaVinculo;
        vm.ConsultaGp.vincular();
        vm.ConsultaGp.compile();
        gScope.ConsultaGp = vm.ConsultaGp;
        

        vm.RcConsultaGp                             = vm.Consulta.getNew(true);
        vm.RcConsultaGp.componente                  = '.rc-consulta-gp';
        vm.RcConsultaGp.model                       = 'vm.RcConsultaGp';
        vm.RcConsultaGp.option.label_descricao      = 'GP:';
        vm.RcConsultaGp.option.obj_consulta         = '/_22030/api/gp';
        vm.RcConsultaGp.option.tamanho_input        = 'input-maior';
        vm.RcConsultaGp.option.tamanho_tabela       = 427;
        vm.RcConsultaGp.option.campos_tabela        = [['GP_ID', 'ID'],['GP_DESCRICAO','GRUPO DE PRODUÇÃO']];
        vm.RcConsultaGp.option.obj_ret              = ['GP_ID', 'GP_DESCRICAO'];
        vm.RcConsultaGp.setDataRequest({GP_FAMILIAS_ID: [vm.RemessaComponente.FILTRO, 'FAMILIAS_ID']});
        vm.RcConsultaGp.compile();
        vm.RcConsultaGp.disable(true);
        gScope.RcConsultaGp = vm.RcConsultaGp;        

        vm.ConsConsultaProduto                             = vm.Consulta.getNew(true);
        vm.ConsConsultaProduto.componente                  = '.cons-consulta-produto';
        vm.ConsConsultaProduto.model                       = 'vm.ConsConsultaProduto';
        vm.ConsConsultaProduto.option.label_descricao      = 'Produto:';
        vm.ConsConsultaProduto.option.obj_consulta         = '/_27050/api/produto';
        vm.ConsConsultaProduto.option.tamanho_input        = 'input-maior';
        vm.ConsConsultaProduto.option.tamanho_tabela       = 427;
        vm.ConsConsultaProduto.option.campos_tabela        = [['PRODUTO_ID', 'ID'],['PRODUTO_DESCRICAO','PRODUTO']];
        vm.ConsConsultaProduto.option.obj_ret              = ['PRODUTO_ID', 'PRODUTO_DESCRICAO'];
        vm.ConsConsultaProduto.setDataRequest({STATUS: 1,MODELO_ID: '> 0'});
        vm.ConsConsultaProduto.compile();
        gScope.ConsConsultaProduto = vm.ConsConsultaProduto;        
       

        vm.ConsConsultaModeloTamanho                             = vm.Consulta.getNew(true);
        vm.ConsConsultaModeloTamanho.componente                  = '.cons-consulta-modelo-tamanho';
        vm.ConsConsultaModeloTamanho.model                       = 'vm.ConsConsultaModeloTamanho';
        vm.ConsConsultaModeloTamanho.option.label_descricao      = 'Tamanho:';
        vm.ConsConsultaModeloTamanho.option.obj_consulta         = '/_27020/api/modelo/tamanho';
        vm.ConsConsultaModeloTamanho.option.tamanho_input        = 'input-menor';
        vm.ConsConsultaModeloTamanho.option.tamanho_tabela       = 100;
        vm.ConsConsultaModeloTamanho.option.campos_tabela        = [['TAMANHO_DESCRICAO', 'Tam.']];
        vm.ConsConsultaModeloTamanho.option.obj_ret              = ['TAMANHO_DESCRICAO'];
        vm.ConsConsultaModeloTamanho.require                     = vm.ConsConsultaProduto;
        vm.ConsConsultaModeloTamanho.vincular();
        vm.ConsConsultaModeloTamanho.setDataRequest({MODELO_ID: [vm.ConsConsultaProduto, 'MODELO_ID']});
        vm.ConsConsultaModeloTamanho.compile();
        gScope.ConsConsultaModeloTamanho = vm.ConsConsultaModeloTamanho;        
       
        

        $scope.$watch('vm.RemessaComponente.REMESSA_TIPO_AUTO', function (newValue, oldValue, scope) {

            if ( newValue != undefined && newValue.trim() != '' ) {
                $timeout(function(){
                    vm.RemessaComponente.modalOpen();
                    vm.RemessaComponente.FILTRO.REMESSA_TIPO = newValue;
                });
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.REMESSA_ORIGEM_AUTO', function (newValue, oldValue, scope) {

            if ( newValue != undefined && newValue.trim() != '' ) {
                $timeout(function(){
                    vm.RemessaComponente.FILTRO.ORIGEM       = newValue;
                    vm.RemessaComponente.FILTRO.AUTO_FILTER = true;
                    vm.RemessaComponente.consultarOrigemDados();
                });
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.FILTRO.REMESSA_TIPO', function (newValue, oldValue, scope) {

            vm.RemessaComponente.FILTRO.ORIGEM          = '';
            vm.RemessaComponente.FILTRO.ORIGEM_SELECTED = false;

            if ( newValue == 3 || newValue == 4 ) {
                
                vm.RemessaComponente.consultarOrigemDados();
            }
        }, true);

        $scope.$watch('vm.RemessaComponente.FILTRO.ORIGEM_SELECTED', function (newValue, oldValue, scope) {

            vm.RcConsultaGp.apagar();
            vm.RcConsultaGp.disable(!newValue);
        }, true);        
            

        $scope.$watch('vm.RemessaIntermediaria.FILTRO.REMESSA_SELECTED', function (newValue, oldValue, scope) {
            if ( newValue ) {
                vm.ConsultaRemessaVinculo.filtrar();
            } else 
            if ( !newValue ) {
                vm.ConsultaRemessaVinculo.apagar();
            } 
        }, true);

        vm.trustedHtml = function (plainText) {
            return $sce.trustAsHtml(plainText);
        };
        
        
        
        
        var data_table = $.extend({}, table_default);
            data_table.scrollY = 'calc(100% - 35px)';
        
        vm.Historico           = new Historico();
        vm.Math = window.Math;
        vm.DADOS    = [];
        vm.dtOptions           = data_table;
        vm.remessa             = "";
        vm.TALAO_ORDER_BY      = '';
        vm.remessas            = [];
        vm.itens               = [];
        vm.selected_itens_acao = [];
        vm.class               = [];
        vm.filtrar_arvore      = false;
        vm.familias_consumo    = [];
        vm.consumo_dados       = [];
        vm.gerar_consumo_familias = [];
        vm.gerar_consumo_familia = [];
        vm.filtro              = {
            data_1 : moment().subtract(2, "month").toDate(),
            data_2 : moment().add(2, "month").toDate()
        };
        
        
        
        vm.RemessaKeypress = function(item,tipo,$event) {

            if ( $event.key == ' ' ) {

                $event.preventDefault();

                vm.selectItemAcao(item,tipo,'ID');
            }
        };

        vm.remessaAction = {
            Filtrar: function() {
                
                loading($('.table-remessas'));
                var dados = {
                    data_1 : moment(vm.filtro.data_1).format('DD.MM.YYYY'),
                    data_2 : moment(vm.filtro.data_2).format('DD.MM.YYYY')
                };
                
                var remessa = String($filter('uppercase')(vm.filtrar_remessa));
                
                if ( remessa != "undefined" && String(vm.filtrar_remessa+'').trim() != '' ) {
                    dados = { remessa : remessa };
                }
                
                $ajax.post('/_22120/remessas',dados)
                    .then(function(response) {
                        vm.remessas = response;
                
                        $timeout(function(){

                            if ( vm.filtrar_remessa != '' && vm.remessas_filtered.length == 1) {
                                vm.remessaAction.VisualizarItem(vm.remessas_filtered[0]);
                            }
                        },100);
                        
                        $('.pesquisa.filtro-obj').select();
                        loading('hide');
                    }
                );
            },
            VisualizarItem: function(r) {
                vm.remessa = r;
                
                var link = encodeURI(urlhost + '/_22120?remessa='+vm.remessa.REMESSA);
                window.history.pushState('Delfa - GC', 'Title', link);
                vm.estruturaAction.ConsultarRemessa(function(){
                    vm.filtrar_arvore      = false;
                    vm.selected_itens_acao = [];
                    vm.class               = [];
                });
            },
            RepeatFilter: function(row) {
                
                var res = false;
                
                if ( vm.filtro.status == '' || row.STATUS_PRODUCAO == vm.filtro.status ) {
                    res = true;
                }
                
                if ( res ) {
                    if ( vm.filtro.familia == '' || row.FAMILIA_ID == vm.filtro.familia ) {
                        res = true;
                    } else {
                        res = false;
                    }
                }
                
                return res;
            }
        };

        vm.estruturaAction = {
            ConsultarRemessa: function(callback) {
                var dados = {
                    remessa : vm.remessa.REMESSA
                };

                $ajax.post('/_22120/find',dados)
                    .then(function(response) {
                        
                        callback ? callback() : null;
                        
//                        vm.itens = response;
//                        resizable();

                        vm.estruturaAction.loadData(response);
                        
                        if ( $('#modal-remessa').is(":visible") ) {
                            showSuccess('Os dados foram atualizados!');
                        }

                        $('#modal-remessa').modal();
                    }
                );
            },
            loadData : function (response) {
                
                    gcCollection.merge(vm.itens, response);
                
                for ( var i in  vm.itens ) {
                    var remessa = vm.itens[i];
                    
                    gcCollection.bind(remessa.CONSUMOS, remessa.CONSUMO_ALOCACOES, 'ID|CONSUMO_ID', 'ALOCACOES');
                }
            
            }
        };
                
        vm.Acao = function (action,msg,remessa,key,name) {
            addConfirme('<h4>Confirmação</h4>',
                msg,
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $scope.$apply(function(){

                        var itens = vm.selected_itens_acao[name];

                        var array = [];

                        for ( var i in itens ) {
                            var item = itens[i];

                            if ( item[key] == remessa[key] ) {
                                array.push(item);
                            }
                        }

                        var dados = {
                            dados: array,
                            retorno: true,
                            param: {
                                remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                            }
                        };

                        $ajax.post('/_22120/'+action+'/'+name,JSON.stringify(dados),{contentType: 'application/json'})
                            .then(function(response) {
                                showSuccess(response.success);
//                                vm.itens = response.dados;
//                                resizable();
                                vm.estruturaAction.loadData(response.dados);
                                
                                if ( vm.itens.length <= 0 ) {
                                    
                                    var idx = vm.remessas.indexOf(vm.remessa);

                                    vm.remessas.splice(idx, 1);
                                    
                                    $('#modal-remessa').modal('hide');
                                }
                            })
                        ;    
                    });
                }}]     
            );
        };
        
        vm.winPopUp = function (url,id,params) {
            var modal = winPopUp(url,id,params);
            
            $(modal).unload( function() {
                vm.estruturaAction.ConsultarRemessa();
            });
        };
        
        vm.setRemessaHistorico = function (remessa) {
            $('.historico-corpo').data('id',remessa.REMESSA_ID);
        };
        
        vm.getConsumo = function ()
        {            
            var dados = {
                remessa_id : vm.consumo_dados.remessa_id, 
                familia_id_consumo: vm.consumo_dados.familia_id_consumo
            };
            
            $ajax.post('/_22040/getPdfConsumo',dados)
                .then(function(response) {  
                    if (response) {
                        printPdf(response);
                    }
                })
            ; 
        };
        
        vm.Remessa = {
            SELECTED : null
        };
        
        vm.Consumo = {
            FAMILIAS : [],
            FAMILIA_SELECTED : null,
            Gerar : function () {   
                
                addConfirme('<h4>Confirmação</h4>',
                    'Confirma a geração de consumo para esta remessa?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var dados = {
                                dados: {
                                    REMESSA_ID : vm.Remessa.SELECTED.REMESSA_ID,
                                    MP_FAMILIA_ID: vm.Consumo.FAMILIA_SELECTED
                                },
                                retorno: true,
                                param: {
                                    remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                                }
                            };

                            $ajax.post('/_22120/gerar-consumo',JSON.stringify(dados),{contentType: 'application/json'})
                                .then(function(response) {  
                                    showSuccess(response.success);
//                                    vm.itens = response.dados;
//                                    resizable();
                                    vm.estruturaAction.loadData(response.dados);
                                    
                                    $('#modal-gerar-consumo').modal('hide');
                                })
                            ;    
                        });
                    }}]     
                );
                
            },
            ListarFamilias : function(dados) {
                
                $ajax.post('/_27010/familia-modelo-alocacao',dados)
                    .then(function(response) {  
                        if (response) {
                            vm.Consumo.FAMILIAS = response;
                    
                            if ( vm.Consumo.FAMILIAS.length == 1 ) {
                                vm.Consumo.FAMILIA_SELECTED = vm.Consumo.FAMILIAS[0].FAMILIA_ID;
                            } else {
                                vm.Consumo.FAMILIA_SELECTED = '';
                            }
                            
                            $('#modal-gerar-consumo').modal('show');
                        }
                    })
                ; 
            },
            alterar : function() {
                
                
    
                var data = {
                    DADOS : {
                        CONSUMOS: vm.selected_itens_acao['CONSUMO'],
                        PRODUTO_ID : vm.ConsConsultaProduto.PRODUTO_ID,
                        TAMANHO    : vm.ConsConsultaModeloTamanho.TAMANHO
                    },
                    FILTRO : { remessa : String($filter('uppercase')(vm.remessa.REMESSA)) }
                };                
  
                $ajax.post('/_22120/api/consumo/alterar',data)
                    .then(function(response) {  
                        vm.ConsConsultaProduto.apagar();
                        vm.estruturaAction.loadData(response.DATA_RETURN.DADOS);
                        $('#modal-alterar-consumo').modal('hide');                      
                    })
                ; 
            }
        };
                
                

        
        vm.IndexOfAttr = function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        };
        
        vm.selectItemAcao = function (item,name,key)
        {    
            if ( vm.selected_itens_acao[name] == undefined ) vm.selected_itens_acao[name] = [];
            var colletion = vm.selected_itens_acao[name];
            
            var idx = vm.IndexOfAttr(colletion,key,item[key]);
            
            if (idx > -1) colletion.splice(idx, 1);
            else          colletion.push(item);
        };
        
        vm.selectedItemAcao = function (item,name,key)
        {    
            if ( vm.selected_itens_acao[name] == undefined ) vm.selected_itens_acao[name] = [];
            var colletion = vm.selected_itens_acao[name];
            
            return ( vm.IndexOfAttr(colletion,key,item[key]) > -1 ) ? true : false;
        };        
        
        vm.selectTalao = function (item) {
            vm.class = []; 
            vm.changeClass(item);
            vm.marcarFilhos(item);
            vm.marcarPais(item);
        };
        
        vm.changeClass = function(item){

            if (vm.IndexOfAttr(vm.class,item,item.ID) == -1) {
                vm.class.push(item);
            }    
        };
        
        vm.marcarMaes = function (item) {

            var item_filtro = '[' + item.GP_PERFIL + '/' + item.REMESSA_TALAO_ID + ']';
            //var filtro = '[' + talao.REMESSA_ID + '/' + talao.REMESSA_TALAO_ID +']';

            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var talao_filtro = talao.VINCULOS;

                    if ( talao_filtro != undefined && talao_filtro.indexOf(item_filtro) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarMaes(talao);

                    }
                }
            }
        };
        
        vm.marcarPais = function (item) {
            
            var item_filtro = '[' + item.GP_PERFIL + '/' + item.REMESSA_TALAO_ID + ']';
            //var filtro = '[' + talao.REMESSA_ID + '/' + talao.REMESSA_TALAO_ID +']';
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var talao_filtro = talao.VINCULOS;
                    
                    if ( talao_filtro != undefined && talao_filtro.indexOf(item_filtro) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarPais(talao);
                        
                        if ( vm.marcar_irmaos ) {
                            vm.marcarIrmaos(talao);
                        }
                    }
                }
            }
        };
       
        vm.marcarFilhos = function (item) {
            
            var item_filtro = item.VINCULOS;
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES)
                {
                    var talao = remessa.TALOES[j];
                    var vinculo = '[' + talao.GP_PERFIL + '/' + talao.REMESSA_TALAO_ID +']';
                    
                    if ( item_filtro != undefined && item_filtro.indexOf(vinculo) >= 0 ) {
                        vm.changeClass(talao);
                        vm.marcarFilhos(talao);
                    }
                }
            }
        };
        
        vm.marcarIrmaos = function (item) {
            
            var item_filtro = item.VINCULOS;// '[' + item.REMESSA_ID + '/' + item.REMESSA_TALAO_ID +']';
            
            for(var i in vm.itens)
            {
                var remessa = vm.itens[i]; 
                for(var j in remessa.TALOES) {
                    var talao = remessa.TALOES[j];
                    var vinculo = '[' + talao.GP_PERFIL + '/' + talao.REMESSA_TALAO_ID +']';
                    
                    if ( item_filtro != undefined && item_filtro.indexOf(vinculo) >= 0 ) {
                        vm.changeClass(talao);
                       
                        vm.marcarFilhos(talao);
   
                        if ( vm.marcar_irmaos ) {
                            vm.marcarMaes(talao);
                        }
                    }
                }
            }
        };
          
        vm.FiltrarArvore = function (row) {
            var result = true;
            if ( vm.filtrar_arvore == true ) {
                vm.filtrar_talao = '';
                result = false;
                
                if ( vm.IndexOfAttr(vm.class,'ID',row.ID) >= 0 ) {
                    result = true;
                }
            }
            return result;
        };
                
        vm.FiltrarTalaoDetalhe = function(talao) {
            
            var itens_selecionados = vm.class;
            var result = false;

            if ( itens_selecionados.length > 0 ) {
                
                for(var i in itens_selecionados) {
                    if (itens_selecionados[i].REMESSA_ID == talao.REMESSA_ID && itens_selecionados[i].REMESSA_TALAO_ID == talao.REMESSA_TALAO_ID) {
                        result = true;
                        break;
                    }
                }                
            }
            
            return result;
        };
        
        vm.somaTaloes = function(remessa){
            var itens = vm.class;
            var summ     = 0;
            var summ_alt = 0;
            
            for(var i in itens)
            {
                if (remessa.REMESSA_ID == itens[i].REMESSA_ID) {
                    var qtd = (itens[i].QUANTIDADE == undefined) ? 0 : parseFloat(itens[i].QUANTIDADE);
                    summ += qtd;
                    
                    var qtd_alt = (itens[i].QUANTIDADE_ALTERNATIVA == undefined) ? 0 : parseFloat(itens[i].QUANTIDADE_ALTERNATIVA);
                    summ_alt += qtd_alt;
                }
            }
            
            remessa.QUANTIDADE_SOMA = summ;
            remessa.QUANTIDADE_ALTERNATIVA_SOMA = summ_alt;
        };
                
		vm.limparFiltro = function() {
            if ( vm.filtrar_arvore ) {
                vm.filtrar_arvore = false;
            }
        };
        
        vm.FiltrarChange = function() {
            $timeout(function(){
                $('.talao .scroll-table').scrollTop(0);
            }, 10);
        };
        
        vm.Talao = {
            verificarSobras : function (remessa_id) {

                var dados = {
                    dados: {
                        remessa_id : remessa_id
                    },
                    retorno: true,
                    param: {
                        remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                    }
                };
                $ajax.post('/_22120/post-aproveitamento-sobra',dados)
                    .then(function(response) {  
                        vm.estruturaAction.loadData(response.dados);
                    }
                );
            },
            liberacaoCancelar : function () {
                
                addConfirme('<h4>Confirmação</h4>',
                    'Confirma o cancelamento da liberação dos talões selecionados?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var data = {
                                DADOS : {
                                    TALOES: vm.selected_itens_acao['TALAO']
                                },
                                FILTRO : { remessa : String($filter('uppercase')(vm.remessa.REMESSA)) }
                            };                

                            $ajax.post('/_22120/api/talao/liberacao/cancelar',data)
                                .then(function(response) {  
                                    vm.estruturaAction.loadData(response.DATA_RETURN.DADOS);            
                                })
                            ; 
                        });
                    }}]     
                );                  
                
            }
        };
        
        vm.TaloesExtra = {
            DADOS : {
                SKUS : [],
                TALOES_EXTRA : [],
                SELECTED : {}
            },
            Consultar : function(remessa_id) {
                var that = this;
                
                var dados = {
                    remessa_id : remessa_id
                };
                
                $ajax.post('/_22120/get-taloes-extras',dados)
                    .then(function(response) {  
                        if (response) {

                            
                            gcCollection.merge(that.DADOS.SKUS        , response.SKUS        );
                            gcCollection.merge(that.DADOS.TALOES_EXTRA, response.TALOES_EXTRA);
                            
                            var taloes = that.DADOS.TALOES_EXTRA;
                            for ( var i in taloes ) {
                                var talao_extra = taloes[i];
                                
                                var skus = that.DADOS.SKUS;
                                for ( var y in skus ) {
                                    var sku = skus[y];
                                    if ( 
                                        talao_extra.MODELO_ID == sku.MODELO_ID && 
                                        talao_extra.COR_ID == sku.COR_ID && 
                                        talao_extra.TAMANHO == sku.TAMANHO
                                    ) {
                                        talao_extra.EXTEND = sku;
                                    }
                                }
                            }

                            $('#modal-taloes-extra').modal('show');
                        }
                    })
                ; 
            },
            Gravar : function() {
                var that = this;

                addConfirme('<h4>Confirmação</h4>',
                    'Confirma a geração dos Talões Extras?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var dados = {
                                dados: that.DADOS.TALOES_EXTRA,
                                retorno: true,
                                param: {
                                    remessa: String($filter('uppercase')(vm.remessa.REMESSA))
                                }
                            };

                            $ajax.post('/_22120/post-taloes-extras',JSON.stringify(dados),{contentType: 'application/json'})
                                .then(function(response) {  
                                    showSuccess(response.success);

                                    vm.estruturaAction.loadData(response.dados);
                                    
                                    $('#modal-taloes-extra').modal('hide');
                                })
                            ;    
                        });
                    }}]     
                );                
                
                
            }
        };
        
        vm.fixVsRepeatRemessa = function() {
            $timeout(function(){
                $('.table-remessas .scroll-table').scrollTop(0);
            }, 10);
        };
        
        function resizable() {
            $timeout(function () {
                $('.recebe-puxador-talao, .recebe-puxador-comum')
                    .resizable({
                        resize  : function( event, ui ) {
                            $scope.$apply(function(){
                                $(document).resize();
                            });

                        },
                        handles  : 's',
                        minHeight : 48
                    })
                ;
            }, 500);
        }
        
        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });        
        
        $('#modal-remessa')
            .on('show.bs.modal', function(){
                resizable();
            })
            .on('hide.bs.modal', function () {
                window.history.pushState('Delfa - GC', 'Title', encodeURI(urlhost + '/_22120'));
            })
            .on('hidden.bs.modal', function(){
                $('.pesquisa.filtro-obj').select();
            })
        ;
        
        $('#modal-taloes-extra')
            .on('shown.bs.modal', function(){
                $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
//                $(document).resize();
            })
        ;
        
        
        
        gScope.Estrutura = vm.estruturaAction;
	}   
  
//# sourceMappingURL=_22120.ng.js.map
