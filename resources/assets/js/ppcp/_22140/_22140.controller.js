angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        'gcCollection',
        'Filtro', 
        'Familia', 
        'Gp', 
        'Up', 
        'Estacao'
    ];

	function Ctrl( $scope, $timeout, gScope, gcCollection, Filtro, Familia, Gp, Up, Estacao ) {

		var vm = this;

		vm.Filtro   = new Filtro();
        vm.Familia  = new Familia();
        vm.Gp       = new Gp();
        vm.Up       = new Up();
		vm.Estacao  = new Estacao();
                
                
                
        /**
         * Inicializações
         */
        function onInit() {
            vm.Filtro.consultar();
        }
        
        
        /**
         * Starta as inicializações
         */
        onInit();
        
        
        
        /***************** INICIO DO BLOCO DE WATCHES ******************/
        
        $scope.$on('bs-init', function(ngRepeatFinishedEvent) {
            bootstrapInit();
        });
        
                        
        $timeout(function () {
            $('.recebe-puxador')
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
    