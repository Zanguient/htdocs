/**
 * _22100 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$interval) {
        
        /**
         * Variável privadas 
         */
        var vm          = this;  
        var ferramentas = [];
        var cor         = -1;
        
        /**
         * Variáveis Públicas
         */
        vm.DADOS    = [];    
        
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
        
        /**
         * Controle do Objeto de Itens
         */
        vm.Itens = {
            ferramentaColor : function() {
                
                var cores = [
                    "rgba(1, 397, 193, 0.4)",
                    'rgba(144, 238, 144, 0.4)',
                    'rgba(139, 000, 000, 0.4)',
                    'rgba(139, 000, 139, 0.4)',
                    'rgba(000, 139, 139, 0.4)',
                    'rgba(000, 000, 139, 0.4)',
                    'rgba(079, 079, 079, 0.4)',
                    "rgba(1, 395, 898, 0.4)",
                    'rgba(238, 210, 238, 0.4)',
                    'rgba(137, 104, 205, 0.4)',
                    'rgba(139, 037, 000, 0.4)',
                    'rgba(255, 000, 000, 0.4)',
                    'rgba(255, 000, 255, 0.4)',
                    'rgba(255, 069, 000, 0.4)',
                    'rgba(139, 090, 000, 0.4)',
                    'rgba(255, 165, 000, 0.4)',
                    "rgba(255, 110, 180, 0.4)",
                    'rgba(205, 112, 084, 0.4)',
                    'rgba(139, 076, 057, 0.4)',
                    'rgba(255, 211, 155, 0.4)',
                    'rgba(139, 101, 008, 0.4)',
                    'rgba(139, 139, 000, 0.4)',
                    'rgba(255, 255, 000, 0.4)',
                    "rgba(20, 596, 144, 0.4)",
                    'rgba(000, 139, 000, 0.4)',
                    'rgba(202, 255, 112, 0.4)',
                    'rgba(084, 255, 159, 0.4)',
                    'rgba(000, 255, 255, 0.4)',
                    'rgba(102, 139, 139, 0.4)',
                    "rgba(238, 169, 184, 0.4)",
                    'rgba(191, 239, 255, 0.4)',
                    'rgba(000, 000, 139, 0.4)',
                    'rgba(131, 111, 255, 0.4)',
                    'rgba(072, 118, 255, 0.4)'
                ];
                

                var estacoes      = vm.DADOS.ESTACOES;
                for ( var i in estacoes ) {
                    var estacao = estacoes[i];
                    var taloes  = estacao.TALOES;
                    for ( var j in taloes ) {
                        var talao = taloes[j];

                        var index_of = indexOfAttr(ferramentas,'FERRAMENTA_ID', talao.FERRAMENTA_ID);
                        if ( index_of == -1 ) {
                            
                            cor++;
                            
                            ferramentas.push({
                                FERRAMENTA_ID : talao.FERRAMENTA_ID, 
                                RGB : cores[cor]
                            });
                            
                            talao.FERRAMENTA_RGB = cores[cor];
                        } else {
                            talao.FERRAMENTA_RGB = ferramentas[index_of].RGB;
                        }
                    }
                }
                
                var desconto_estacao = [];
                var descontos        = [];
                
                var estacoes      = vm.DADOS.ESTACOES;
                for ( var i in estacoes ) {
                    var estacao = estacoes[i];
                    var taloes  = estacao.TALOES;
                    
//                    var desconto_estacao = 0;
                    
                    for ( var j in taloes ) {
                        var talao = taloes[j];
                        

                        
                        
                        var datahora_inicio_fim = talao.REF_INICIO_MINUTO + '' + talao.REF_FIM_MINUTO;
                        
                        var indexof = indexOfAttr(descontos, 'DATAHORA_INICIO_FIM', datahora_inicio_fim);
                        
                        if ( indexof == -1 ) {
                            descontos.push({
                                DATAHORA_INICIO_FIM : datahora_inicio_fim,
                                MINUTOS_DESCONTO    : talao.MINUTOS_DESCONTO,
                                MINUTO_INICIO       : talao.REF_INICIO_MINUTO, 
                                MINUTO_FIM          : talao.REF_FIM_MINUTO
                            });
                        }
                    }
                }
                
                
                
                var estacoes      = vm.DADOS.ESTACOES;
                for ( var i in estacoes ) {
                    var estacao = estacoes[i];
                    var taloes  = estacao.TALOES;
                    
                    var desconto_estacao = 0;
                    
                    for ( var j in taloes ) {
                        var talao = taloes[j];
                        
                        talao.MINUTO_INICIO = parseFloat(talao.MINUTO_INICIO) - desconto_estacao;
                        
                        for ( var y in descontos ) {
                            var desconto = descontos[y];
                            
                            if ( 
                                parseFloat(talao.MINUTO_INICIO) <= parseFloat(desconto.MINUTO_INICIO) &&
                                parseFloat(talao.MINUTO_FIM) >= parseFloat(desconto.MINUTO_FIM) 
                            ) {
                                talao.TEMPO_TOTAL = parseFloat(talao.TEMPO_TOTAL) - parseFloat(desconto.MINUTOS_DESCONTO);
//                                desconto_estacao += parseFloat(desconto.MINUTOS_DESCONTO);
                            }
                        }
                    }
                }
                
                

                        
            }
        };
        
        vm.Filtro.watches();
    };

    Ctrl.$inject = ['$scope','$ajax','$timeout','$filter','$window','$interval'];

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
        'gc-transform'
    ])
    /*.config    (config                       )*/
    .filter    ('parseDate'     , parseData  )
    .directive ('gcRepeatEnd'   , gcRepeatEnd)
    .directive ('bsInit'        , bsInit     )
    .controller('Ctrl'          , Ctrl       );
        
})(angular);

;(function($) {
    
    
    var sockets = [
        {
            METHOD : 'progressoConsulta',
            FUNCTION: function(data){
               showSuccess(data.MENSAGE.DADOS);
            }
        }
    ];
    
//    createSocket(sockets);

})(jQuery);