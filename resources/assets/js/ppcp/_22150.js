/**
 * _22100 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$interval, gcCollection) {
        
        /**
         * Variável privadas 
         */
        var vm          = this;  
        var ferramentas = [];
        var cor         = -1;
        var setInter;
        
        /**
         * Variáveis Públicas
         */
        vm.DADOS                   = [];
        vm.FERRAMENTA_SELECIONADA  = {};
        vm.FERRAMENTA_HISTORICO    = [];
        vm.OPERADOR_BARRAS         = '';
        vm.data_load               = false;        
        vm.LAST_UPDATE             = null;
        
        /**
         * Controle do Objeto de Filtro
         */
        vm.Filtro = {
            /**
             * Valores do Filtro
             */
            DADOS : {
                DATA_1 : moment('2017.03.27').toDate(),//.startOf('month').toDate(),
                DATA_2 : moment('2017.03.27').toDate()//.endOf('month').toDate()
            },
            /**
             * Executa a filtragem
             */
            start : function(callback) {
                
                $ajax.post('/_22140/find',JSON.stringify(this.DADOS),{contentType: 'application/json', progress: 'manual'})
                    .then(function(response) {
                        vm.DADOS = response;
                        callback ? callback() : null;
                    }
                );
            },
            /**
             * Escutas
             */
            watches : function() {
                
                $scope.$watch(this.DADOS, function () {
                    cor         = -1;
                    ferramentas = [];
                    vm.DADOS    = [];
                }, true);
            }
        };
        
        vm.Estacao = {
            CHECK_OCULTAR_PARADA : false
        };
        
        vm.Ferramenta = {
            SELECTED : null,
            INDEX : 0,
            CODE : null,
            DISPONIVEIS : [],
            DISPONIVEIS_SELECTED : {},
            REGISTRO : {
                FERRAMENTA_BARRAS : null,
                OPERADOR_BARRAS : null,
                GP_ID : null,
                UP_ID : null,
                ESTACAO : null
            },
            Select : function (item) {
                this.SELECTED = item;
                this.INDEX    = vm.DADOS.indexOf(this.SELECTED);
                
                if ( this.SELECTED != undefined ) {
                    this.CODE     = this.SELECTED.CODE;
                }
            },
            setInputEmpty : function () {
                this.REGISTRO.FERRAMENTA_BARRAS = null;
                this.REGISTRO.OPERADOR_BARRAS   = null;
            },
            RegistrarTroca : function() {
                vm.OPERADOR_BARRAS = ''; 
                vm.Ferramenta.ListarDisponiveis();  
            },
            RegistrarAcao : function() {

                var that = this;
                if ( this.SELECTED.FERRAMENTA_SITUACAO_TALAO.trim() == 'E' ) {
                    $('#modal-separacao')
                        .modal('show')
                        .one('shown.bs.modal', function(){
                            that.setInputEmpty();
                            $(this).find('input').first().focus();
                        })
                        .one('hidden.bs.modal', function(){
                            vm.Ferramenta.setInputEmpty();
                            $('.table-container-painel tr.selected')[0].focus();
                        })
                    ;
                } else
                if ( this.SELECTED.FERRAMENTA_SITUACAO_TALAO.trim() == 'R' ) {
                    $('#modal-saida')
                        .modal('show')
                        .one('shown.bs.modal', function(){
                            that.setInputEmpty();
                            $(this).find('input').first().focus();
                        })
                        .one('hidden.bs.modal', function(){
                            vm.Ferramenta.setInputEmpty();
                            $('.table-container-painel tr.selected')[0].focus();
                        })
                    ;
                }
            },
            ConfirmarSeparacao : function() {
                
                this.REGISTRO.GP_ID    = 0;
                this.REGISTRO.UP_ID    = 0;
                this.REGISTRO.ESTACAO  = 0;
                this.REGISTRO.TALAO_ID = this.SELECTED.TALAO_ID;
                this.REGISTRO.SITUACAO = 'R'; // Separacao;
                
                $ajax.post('/_22150/acao/registrar',JSON.stringify(this.REGISTRO),{contentType: 'application/json', progress: 'manual'})
                    .then(function(response) {

                        vm.Ferramenta.REGISTRO = {};
                        vm.Ferramenta.LoadData(response);      
                
                        showSuccess('Separação registrada');
                
                        $('#modal-separacao')
                            .modal('hide')
                            .one('hidden.bs.modal', function(){
                            vm.Ferramenta.setInputEmpty();
                                $('.table-container-painel tr.selected')[0].focus();          
                            })
                        ;
                    })
                    .catch(function() {
                        vm.Ferramenta.setInputEmpty();
                        $('#modal-separacao').find('input').first().focus();
                    });
                ;

            },  
            ConfirmarSaida : function() {
                
                this.REGISTRO.GP_ID    = this.SELECTED.GP_ID;
                this.REGISTRO.UP_ID    = this.SELECTED.UP_ID;
                this.REGISTRO.ESTACAO  = this.SELECTED.ESTACAO;
                this.REGISTRO.TALAO_ID = this.SELECTED.TALAO_ID;
                this.REGISTRO.SITUACAO = 'S'; // Saida;
                
                $ajax.post('/_22150/acao/registrar',JSON.stringify(this.REGISTRO),{contentType: 'application/json', progress: 'manual'})
                    .then(function(response) {

                        vm.Ferramenta.REGISTRO = {};
                        vm.Ferramenta.LoadData(response);      
                
                        showSuccess('Saida registrada');
                
                        $('#modal-saida')
                            .modal('hide')
                            .one('hidden.bs.modal', function(){
                                vm.Ferramenta.setInputEmpty();
                                $('.table-container-painel tr.selected')[0].focus();          
                            })
                        ;
                    })
                    .catch(function() {
                        vm.Ferramenta.setInputEmpty();
                        $('#modal-saida').find('input').first().focus();
                    });
                ;

            },                
            RegistrarEntrada : function() {
                                
                $('#modal-entrada')
                    .modal('show')
                    .one('shown.bs.modal', function(){
                        vm.Ferramenta.REGISTRO = {};
                        $(this).find('input').first().focus();
                    })
                    .one('hidden.bs.modal', function(){
                        vm.Ferramenta.setInputEmpty();
                        $('.table-container-painel tr.selected')[0].focus();
                    })
                ;
            },
            ConfirmarEntrada : function() {
                
                this.REGISTRO.GP_ID    = 0;
                this.REGISTRO.UP_ID    = 0;
                this.REGISTRO.ESTACAO  = 0;
                this.REGISTRO.TALAO_ID = 0;
                this.REGISTRO.SITUACAO = 'E'; // Entrada;
                
                $ajax.post('/_22150/acao/registrar',JSON.stringify(this.REGISTRO),{contentType: 'application/json', progress: 'manual'})
                    .then(function(response) {

                        vm.Ferramenta.REGISTRO = {};
                        vm.Ferramenta.LoadData(response);      
                
                        showSuccess('Entrada registrada');
                
                        $('#modal-entrada')
                            .modal('hide')
                            .one('hidden.bs.modal', function(){
                            vm.Ferramenta.setInputEmpty();
                                $('.table-container-painel tr.selected')[0].focus();          
                            })
                        ;
                    })
                    .catch(function() {
                        vm.Ferramenta.setInputEmpty();
                        $('#modal-entrada').find('input').first().focus();
                    });
                ;

            },                   
            ListarDisponiveis : function(ferramenta) {
                
                if ( vm.OPERADOR_BARRAS.length > 0 ) {
                    
                    var dados = {
                        DATAHORA_INICIO   : vm.Ferramenta.SELECTED.DATAHORA_INICIO,
                        OPERADOR_BARRAS   : vm.OPERADOR_BARRAS
                    };
                    
                    if ( vm.Ferramenta.SELECTED.FERRAMENTA_STATUS.trim() == '0' ) {
                        dados.FERRAMENTA_ID = vm.Ferramenta.SELECTED.FERRAMENTA_ID;
                    } else {
                        dados.FERRAMENTA_BARRAS = vm.Ferramenta.SELECTED.FERRAMENTA_BARRAS;                        
                    }
                        

                    $ajax.post('/_22150/ferramenta/listar-disponiveis',JSON.stringify(dados),{contentType: 'application/json'})
                        .then(function(response) {
                            
                            vm.Ferramenta.DISPONIVEIS = response;
                    
                            $('#modal-autenticar-operador').off('hidden.bs.modal').modal('hide');
                            $('#modal-ferramenta-alterar')
                                .modal('show')
                                .one('hidden.bs.modal', function(){
                                    vm.Ferramenta.setInputEmpty();
                                    $('.table-container-painel tr.selected')[0].focus(); 
                                })
                            ;
                        })
                        .catch(function() {
                            vm.OPERADOR_BARRAS = '';
                            $('#modal-autenticar-operador').find('input').first().focus();
                        })
                    ;
                } else {
                    $('#modal-autenticar-operador')
                        .modal('show')
                        .one('shown.bs.modal', function(){
                            $(this).find('input').focus();
                        })
                        .one('hidden.bs.modal', function(){
                            vm.Ferramenta.setInputEmpty();
                            $('.table-container-painel tr.selected')[0].focus(); 
                        })
                    ; 
                }
            },
            Alterar : function(ferramenta) {
                addConfirme('<h4>Confirmação</h4>',
                    'Confirma a alteração da ferramenta?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $scope.$apply(function(){

                            var dados = {
                                FERRAMENTA_ID       : vm.Ferramenta.SELECTED.FERRAMENTA_ID,
                                DEST_FERRAMENTA_ID  : ferramenta.ID,
                                DATAHORA_INICIO     : vm.Ferramenta.SELECTED.DATAHORA_INICIO,
                                OPERADOR_BARRAS     : vm.OPERADOR_BARRAS
                            };

                            $ajax.post('/_22150/ferramenta/alterar',JSON.stringify(dados),{contentType: 'application/json'})
                                .then(function(response) {  
                                    
                                    showSuccess(response.MSG);
                                    vm.Ferramenta.LoadData(response.RETORNO);
                                    
                                    $('#modal-ferramenta-alterar')
                                        .modal('hide')
                                        .one('hidden.bs.modal', function(){
                                            vm.Ferramenta.setInputEmpty();
                                            $('.table-container-painel tr.selected')[0].focus();         
                                        })
                                    ;
                                })
                            ;    
                        });
                    }}]     
                );                
            },
            getHistorico : function(ferramenta_id) {
                if ( !(parseInt(ferramenta_id) > 0)  ) {
                    showErro('Selecione uma ferramenta!');
                    return false;
                }
                
                var dados = {
                    FERRAMENTA_ID : ferramenta_id
                };
                
                $ajax.post('/_22150/ferramenta/historico',JSON.stringify(dados),{contentType: 'application/json'})
                    .then(function(response) {  

                        vm.FERRAMENTA_HISTORICO = response;

                        $('#modal-historico-movimentacao')
                            .modal('show')
                        ;
                    })
                ;            
                
            },
            Keydown : function($event,item) {
                
                if (($event.key=='Enter' || $event.key=='F1') && (item.FERRAMENTA_SITUACAO.trim() == 'E' || item.FERRAMENTA_SITUACAO.trim() == 'R') && item.FERRAMENTA_SITUACAO.trim() == item.FERRAMENTA_SITUACAO_TALAO.trim()) {
                    vm.Ferramenta.RegistrarAcao();
                } else
                if (($event.key=='*' || $event.key=='F2') && item.FERRAMENTA_SITUACAO.trim() != 'R') {
                    vm.Ferramenta.RegistrarTroca();
                } else {
                    return;
                }
            },
            /**
             * Executa o calculo do tempo corrido para a saida da ferramenta
             * @param {object} item Ferramenta
             * @returns void
             */
            CalcTempo : function(item) {

                item.CREATEAD = new Date(item.DATAHORA_INICIO);

                var cur       = Clock.DATETIME_SERVER;
                var startTime = moment(cur).format();
                var endTime   = moment(item.CREATEAD).format();

                var duration     = moment.duration(moment(endTime).diff(startTime));

                item.TIME        = duration.asMinutes();
                item.TIME_STRING = moment.duration(item.TIME, "minutes").humanize(true);
                
            },
            LoadData : function(dados) {
                
//                vm.DADOS = dados;
                gcCollection.merge(vm.DADOS, dados, 'PROGRAMACAO_ID');
                                
                /**
                 * Aplica o tempo corrido para todas as ferramentas
                 */
                for ( var i in vm.DADOS ) {
                    vm.Ferramenta.CalcTempo(vm.DADOS[i]);
                }

                /**
                 * Seleciona a ferramenta
                 */
                var index = indexOfAttr(vm.DADOS, 'CODE', vm.Ferramenta.CODE);

                if ( index != -1 ) {
                    vm.Ferramenta.Select(vm.DADOS[index]);
                } else {
                    vm.Ferramenta.Select(vm.DADOS[vm.Ferramenta.INDEX]);
                }

                setTimeout(function(){
                    if ( !$('.modal').is(":visible") ) {
                        $('.table-container-painel tr.selected')[0].focus();
                    }
                },10);
            }
        };
        
        
        vm.FerramentaProgramada = {
            ITENS : [],
            SUB_ITENS : [],
            SELECTED : {},
            SELECTED_INDEX : 0,
            FILTRO : '',
            FILTERED : [],
            CHECK_DISPONIVEL    : false,
            CHECK_SEPARADA      : false,
            CHECK_EM_PRODUCACAO : false,
            CHECK_EM_DESUSO     : true,
            CHECK_RESERVADA     : false,
            Filter : function(ferramenta) {
                
                var ret = true;
                
                if ( vm.FerramentaProgramada.FILTRO != '' ) {
                    var filtro = 
                        $filter('find')(ferramenta.HORARIOS,{
                            model : vm.FerramentaProgramada.FILTRO,
                            fields : [
                                'FERRAMENTA_SERIE',
                                'FERRAMENTA_DESCRICAO',
                                'FERRAMENTA_ENDERECAMENTO',
                                'FERRAMENTA_GP_DESCRICAO',
                                'FERRAMENTA_ESTACAO_DESCROCAO',
                                'GP_DESCRICAO',
                                'ESTACAO_DESCRICAO'
                            ]
                        })
                    ;  

                    if ( filtro.length < 1 ) {
                        ret = false;
                    }
                }
                
                if ( ret ) {
                    
                    var situacao = false;
                    
                    if ( ferramenta.FERRAMENTA_SITUACAO.trim() == 'E' && (vm.FerramentaProgramada.CHECK_OCULTAR_DISPONIVEL || '') ) {
                        situacao = true;
                    } else
                    if ( ferramenta.FERRAMENTA_SITUACAO.trim()== 'R' && (vm.FerramentaProgramada.CHECK_OCULTAR_SEPARADA || '') ) {
                        situacao = true;
                    } else
                    if ( ferramenta.FERRAMENTA_SITUACAO.trim() == '.' && ferramenta.FERRAMENTA_RESERVA.trim() == '1' && (vm.FerramentaProgramada.CHECK_EM_PRODUCACAO || '') ) {
                        situacao = true;
                    } else
                    if ( ferramenta.FERRAMENTA_SITUACAO.trim() == '.' && ferramenta.FERRAMENTA_RESERVA.trim() == '2' && (vm.FerramentaProgramada.CHECK_EM_DESUSO || '') ) {
                        situacao = true;
                    } else
                    if ( ferramenta.FERRAMENTA_SITUACAO.trim() == '.' && ferramenta.FERRAMENTA_RESERVA.trim() == '0' && (vm.FerramentaProgramada.CHECK_RESERVADA || '') ) {
                        situacao = true;
                    }
                
                    if ( !situacao ) {
                        ret = false;
                    }
                    
                }
                
                return ret;
            },
            Carregar : function() {
                
                $ajax.post('/_22150/painel/ferramenta-programada',JSON.stringify({}),{contentType: 'application/json', progress: false})
                    .then(function(response) {  
                        
                        var ferramentas = [];
                        gcCollection.merge(vm.FerramentaProgramada.SUB_ITENS, response, 'PROGRAMACAO_ID');
                
                        ferramentas = gcCollection.groupBy(vm.FerramentaProgramada.SUB_ITENS, [
                            'FERRAMENTA_ID',
                            'FERRAMENTA_DESCRICAO',
                            'FERRAMENTA_ENDERECAMENTO',
                            'FERRAMENTA_SERIE',
                            'FERRAMENTA_GP_DESCRICAO',
                            'FERRAMENTA_UP_DESCRICAO',
                            'FERRAMENTA_ESTACAO_DESCRICAO',
                            'FERRAMENTA_SITUACAO',
                            'FERRAMENTA_SITUACAO_DESCRICAO',
                            'FERRAMENTA_RESERVA',
                            'FERRAMENTA_RESERVA_DESCRICAO'
                        ], 'HORARIOS');
                        
                        gcCollection.merge(vm.FerramentaProgramada.ITENS, ferramentas, 'FERRAMENTA_ID');
                        
//
//                        /**
//                         * Seleciona a ferramenta
//                         */
//                        var index = indexOfAttr(vm.FerramentaProgramada.ITENS, 'FERRAMENTA_ID', vm.FerramentaProgramada.SELECTED.FERRAMENTA_ID);
//
//                        if ( index != -1 ) {
//                            vm.FerramentaProgramada.Select(vm.FerramentaProgramada.ITENS[index]);
//                        } else {
//                            vm.FerramentaProgramada.Select(vm.FerramentaProgramada.ITENS[vm.FerramentaProgramada.SELECTED_INDEX]);
//                        }
//
//                        if ( $('#ferramenta-programada').is(':visible') ) {
//                            setTimeout(function(){
//                                $('.table-ferramenta-programada tr.selected')[0].focus();
//                            },10);
//                        }
                    })
                ;  
            },
            Select : function (item) {
                this.SELECTED = item;
                this.INDEX    = vm.DADOS.indexOf(this.SELECTED);
            },
            Keydown : function($event) {
                var key = $event.key;
                
                if ( key == 'ArrowDown' || key == 'ArrowUp' ) {
                    $('.table-ferramenta-programada tr.selected')[0].focus();
                }
            },
            Watches : function() {
                $scope.$watch('vm.FerramentaProgramada.FILTERED', function (newValue, oldValue, scope) {
                    if ( newValue != oldValue ) {
                        
                        var index = -1;
                        
                        if ( vm.FerramentaProgramada.SELECTED != undefined ) {
                            index = indexOfAttr(newValue, 'FERRAMENTA_ID', vm.FerramentaProgramada.SELECTED.FERRAMENTA_ID);
                        }

                        if ( index != -1 ) {
                            vm.FerramentaProgramada.Select(newValue[index]);
                        } else {
                            vm.FerramentaProgramada.Select(newValue[vm.FerramentaProgramada.SELECTED_INDEX]);
                        }
                    }
                }, true);
            }
        };
        
        /**
         * Controle do Objeto de SSE
         */
        var sse = new SSE('_22150/painel/sse');
        sse.onmessage = function(event) {
            $scope.$apply(function(){
                vm.data_load = true;
                vm.Ferramenta.LoadData(JSON.parse(event.data));
                vm.FerramentaProgramada.Carregar();
                showSuccess('Os dados foram atualizados!');
                vm.LAST_UPDATE = Clock.DATETIME_SERVER;
            });
        };
        
        /**
         * Aplica a contagem do tempo segundo a segundo
         */
        setInterval(function() {
            for ( var i in vm.DADOS ) {
                $scope.$apply(function(){
                    vm.Ferramenta.CalcTempo(vm.DADOS[i]);
                }); 
            }
        },700);
        
        loading($('.ctrl'));
        
        $scope.$watch('vm.data_load', function (newValue, oldValue) {
            if ( oldValue == false && newValue == true ) {
                loading('hide');
            }
        }, true);
        
        vm.FerramentaProgramada.Watches();
        
//        vm.Filtro.watches();
    };

    Ctrl.$inject = ['$scope','$ajax','$timeout','$filter','$window','$interval', 'gcCollection'];

    var bsInit = function() {
        return function(scope, element, attrs) {         
            bootstrapInit();
        };
    };
    
    var parseData = function() {
        return function(input) {
            if ( input ) return new Date(input);
        };
    };
    
    var gcRepeatEnd = function() {
        return function(scope, element, attrs) {
            angular.element(element).css('color','blue');
            if (scope.$last){
                bootstrapInit();
            }
        };
    };  
    
    var config = function($mdThemingProvider) {
        $mdThemingProvider
            .theme('default')
            .primaryPalette('blue')
            .accentPalette('green')
        ;
    };
        
    angular
    .module('app', [
        /*'ngMaterial',*/
        'angular.filter',
        'vs-repeat',
        'gc-ajax',
        'gc-form',
        'gc-find',
        'gc-transform',
        'gc-utils'
    ])
    /*.config    (config                       )*/
    .filter    ('parseDate'     , parseData  )
    .directive ('gcRepeatEnd'   , gcRepeatEnd)
    .directive ('bsInit'        , bsInit     )
    .controller('Ctrl'          , Ctrl       );
        
})(angular);

;(function($) {
   
		$(document)
			.on('keydown', 'body', 'insert', function() {
                
                if ( !($('.modal').is(':visible')) ) {
                    $('[data-hotkey="insert"]').click();
                }
			})
        ;
})(jQuery);