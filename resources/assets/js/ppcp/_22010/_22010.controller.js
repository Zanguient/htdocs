angular
    .module('app')
    .value('gScope', {
        indexOfAttr : function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        }
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$compile',
        '$timeout',
        '$sce',
        'gScope',
        'Acao', 
        'Filtro', 
        'TalaoProduzir', 
        'TalaoProduzido', 
        'TalaoComposicao',
        'TalaoDetalhe', 
        'TalaoDefeito',
        'TalaoConsumo',
        'TalaoTempo',
        'TalaoFicha',
        'TotalizadorDiario',
        'ColaboradorCentroDeTrabalho'
    ];

	function Ctrl( 
        $scope, 
        $compile, 
        $timeout, 
        $sce, 
        gScope, 
        Acao, 
        Filtro, 
        TalaoProduzir, 
        TalaoProduzido, 
        TalaoComposicao, 
        TalaoDetalhe, 
        TalaoDefeito, 
        TalaoConsumo, 
        TalaoTempo, 
        TalaoFicha, 
        TotalizadorDiario, 
        ColaboradorCentroDeTrabalho 
    ) {

		var vm = this;

        vm.Acao              = new Acao();
		vm.Filtro            = new Filtro();
        vm.TalaoProduzir     = new TalaoProduzir();
        vm.TalaoProduzido    = new TalaoProduzido();
        vm.TalaoComposicao   = new TalaoComposicao();
		vm.TalaoDetalhe      = new TalaoDetalhe();
		vm.TalaoDefeito      = new TalaoDefeito();
		vm.TalaoConsumo      = new TalaoConsumo();
        vm.TalaoTempo        = new TalaoTempo();
        vm.TalaoFicha        = new TalaoFicha();
        vm.TotalizadorDiario = new TotalizadorDiario();
        
        vm.ColaboradorCentroDeTrabalho = new ColaboradorCentroDeTrabalho();
        
        gScope.Acao              = vm.Acao             ; 
		gScope.Filtro            = vm.Filtro           ; 
        gScope.TalaoProduzir     = vm.TalaoProduzir    ; 
        gScope.TalaoProduzido    = vm.TalaoProduzido   ; 
        gScope.TalaoComposicao   = vm.TalaoComposicao  ; 
		gScope.TalaoDetalhe      = vm.TalaoDetalhe     ; 
		gScope.TalaoDefeito      = vm.TalaoDefeito     ; 
		gScope.TalaoConsumo      = vm.TalaoConsumo     ; 
        gScope.TalaoTempo        = vm.TalaoTempo       ; 
        gScope.TalaoFicha        = vm.TalaoFicha       ; 
        gScope.TotalizadorDiario = vm.TotalizadorDiario; 
               
        
        vm.trustedHtml = function (plainText) {
            return $sce.trustAsHtml(plainText);
        };        
        
        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });
        
        /**
         * Escuta do filtro que realiza o autofiltro
         */
        $scope.$watch('vm.Filtro.ESTABELECIMENTO_ID', function (newValue, oldValue, scope) {
            
            if ( newValue > 0 ) {
                $timeout(function(){
                    if ( vm.Filtro.AUTO_LOAD ) {
                        $('.btn-filtrar').click();
                    }
                },50);
            } 
        });
        
        /**
         * Escuta da guia ativa
         */
        $scope.$watch('vm.Filtro.GUIA_ATIVA', function (newValue, oldValue, scope) {
            
            if (newValue == 'TALAO_PRODUZIR') {
                
                if ( vm.TalaoProduzir.INICIADO ) {                  
                    vm.TalaoProduzir.EM_PRODUCAO = true;
                } else {
                    vm.TalaoProduzir.EM_PRODUCAO = false;
                }
                
                $('#periodo-todos').prop('disabled',false);
                $('#turno').attr('disabled', true);
            } else {
                $('#turno').attr('disabled', false);
                $('#periodo-todos').prop('disabled',true);
                
                $('#filtrar-toggle[aria-expanded="false"]').click();
            } 
            
            if ( newValue == 'TOTALIZADOR_DIARIO') {
                $('.filtro-periodo .data-ini').prop('required',true);
                $('.filtro-periodo .data-fim').prop('required',true);
            } else {
                $('.filtro-periodo .data-ini').prop('required',false);
                $('.filtro-periodo .data-fim').prop('required',false);
            }
            
            if ( oldValue != undefined && oldValue != newValue ) {
                vm.TalaoComposicao.DADOS = [];
            }
            
            if ( oldValue != undefined && oldValue != newValue && newValue == 'TALAO_PRODUZIR' ) {                
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
//                    vm.TalaoComposicao.consultar();
                },500);                 
            }
        }, true);
        

        $scope.$watch('vm.Filtro.GP_ID', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.PERFIL_UP', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.UP_ID', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);

        $scope.$watch('vm.Filtro.ESTACAO', function (newValue, oldValue, scope) {
            
            if ( oldValue != undefined && newValue == '' ) {
                vm.TalaoProduzir.DADOS = [];
                vm.TalaoProduzido.DADOS = [];
                vm.TotalizadorDiario.DADOS = [];
                vm.TalaoProduzir.SELECTED = null;
                vm.TalaoProduzido.SELECTED = null;
            }
        }, true);
        
        /**
         * Escuta do talão em produção
         */
        $scope.$watch('vm.TalaoProduzir.INICIADO', function (newValue, oldValue, scope) {
            
            if (newValue) {
                if ( vm.Filtro.GUIA_ATIVA == 'TALAO_PRODUZIR' ) {
                    vm.TalaoProduzir.EM_PRODUCAO = true;
                } else {
                    vm.TalaoProduzir.EM_PRODUCAO = false;
                }
            } else {
                vm.TalaoProduzir.EM_PRODUCAO = false;
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
                },100);
            }
            
            gScope.TalaoTempo.calcRealTime();
        }, true);
             
        /**
         * Escuta do talão selecionado
         */
        $scope.$watch('vm.TalaoProduzir.SELECTED', function (newValue, oldValue, scope) {

            if ( newValue === null ) {
                
                vm.TalaoProduzir.INICIADO = false;
                delete vm.Filtro.TALAO_SELECTED;
                
					vm.TalaoComposicao.DADOS = [];
            } else
            if ( oldValue == undefined && newValue != null ) {
                $timeout(function(){
                    $('.table-talao-produzir.table-lc-body tr.selected').focus();
                },50);  
            }        
            
        }, true);
        
        $scope.$watch('vm.TalaoProduzir.SELECTED.ID', function (newValue, oldValue, scope) {

            if ( oldValue != undefined ) {
                vm.TalaoDetalhe.SELECTED = null;
                vm.TalaoConsumo.SELECTED = null;
                vm.TalaoDetalhe.QUANTIDADE_ALTERANDO = [];
                vm.TalaoDetalhe.QUANTIDADE_ALTERNATIVA_ALTERANDO = [];  
            }          
        }, true);
        
        
        /**
         * Escuta do status EM PRODUÇÃO
         */
        $scope.$watch('vm.TalaoProduzir.EM_PRODUCAO', function (newValue, oldValue, scope) {
            
            if ( newValue ) {
                $(document).scrollTop(0);
            }
        }, true);
        
             
        /**
         * Escuta do talão selecionado
         */
        $scope.$watch('vm.TalaoProduzido.SELECTED', function (newValue, oldValue, scope) {

            if ( newValue === null ) {
                vm.TalaoComposicao.DADOS = [];
            } else
            if ( oldValue == undefined && newValue != null ) {
                $timeout(function(){
                    $('.table-talao-produzido.table-lc-body tr.selected').focus();
                },50);  
            }        
            
        }, true);
        
        $scope.$watch('vm.TalaoProduzido.SELECTED.ID', function (newValue, oldValue, scope) {

            if ( oldValue != undefined ) {
                vm.TalaoDetalhe.SELECTED = null;
                vm.TalaoConsumo.SELECTED = null;
            }          
        }, true);
             
                        
        $timeout(function () {
            $('.recebe-puxador-talao, .recebe-puxador-detalhe, .recebe-puxador-consumo, .recebe-puxador-historico, .recebe-puxador-ficha')
                .resizable({
                    resize  : function( event, ui ) {
                        $scope.$apply(function(){
                            $(document).resize();
                        });

                    },
                    handles  : 's',
                    minHeight : 80
                })
            ;
        }, 500);
        
			
        //Aumentar tabela com duplo clique no puxador
        $(document)
            .on('dblclick','.ui-resizable-s', function(e) {

                var table = $(this).closest('.ui-resizable');

                if ( $(table).data('original-size') == undefined || $(table).data('original-size') == 0 ) {
                    $(table).data('original-size',$(table).height());
                }  
                
                var bool = $(table).data('height-full') || false;
                $(table).data('height-full', ! bool);
                
                if ( $(table).data('height-full') == true ) {
        
                    var datatable_scrollbody = $(table).find('.table-lc-body');;
                    var tbody_height	     = datatable_scrollbody.height(),
                        window_height	     = $(window).height(),
                        vh_context		     = window_height * 0.01,	//converter px para vh - parte I
                        tbody_height_vh      = tbody_height / vh_context //converter px para vh - parte II
                    ;

                    //Se a altura do tbody for maior que 70vh, tbody_height terá 70vh de altura, pois esse é o valor máximo permitido (altura da tela);
                    //senão, a altura será a altura inicial + 34, que é a altura do cabeçalho da tabela.
                    if (tbody_height_vh > 70) {

                        var datatable_scroll     = $(table).find('.table-container');

                        tbody_height = '70vh';

                        //Posicionar scroll.
                        //posição da tabela - altura do cabeçalho - altura da barra de ações - 50
                        $(document)
                            .scrollTop( datatable_scroll.offset().top - $('nav.navbar').outerHeight() - $('ul.acoes').outerHeight() - 50 )
                        ;

                    }
                    else {
                        tbody_height = tbody_height + 45;
                    }
                
                    $(table)
                        .height( tbody_height )
                    ;
                } else {
                    $(table)
                        .height( $(table).data('original-size') )
                    ;                    
                }
            })
        ;
	}   
    