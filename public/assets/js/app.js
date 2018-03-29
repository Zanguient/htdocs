/**
 * _22100 - Geracao de Remessas de Bojo
 */

;(function(angular) {

    var Ctrl = function($scope,$ajax,$timeout,$filter,$window,$interval,$consulta) {
        var vm = this;
        var data_table = $.extend({}, table_default);
            data_table.scrollY = 'auto';
        vm.Consulta   = new $consulta();
        vm.DADOS      = [];
        vm.dtOptions  = data_table;
        
        vm.ConsultaProduto = vm.Consulta.getNew();
        
        vm.ConsultaProduto.componente                  = '.consulta-produto';
        vm.ConsultaProduto.option.class                = 'produtoctrl';
        vm.ConsultaProduto.model                       = 'vm.ConsultaProduto';
        vm.ConsultaProduto.option.label_descricao      = 'Produto:';
        vm.ConsultaProduto.option.obj_consulta         = '/_27050/consulta/json';
        vm.ConsultaProduto.option.tamanho_input        = 'input-maior';
        vm.ConsultaProduto.option.tamanho_tabela       = 427;
        vm.ConsultaProduto.compile();
        
        var arr = vm.Consulta.getHistory();
        
        $timeout(function(){
            if ( vm.DEF_PRODUTO_ID > 0 ) {
                vm.ConsultaProduto.option.filtro_sql = { PRODUTO_ID: vm.DEF_PRODUTO_ID };
                vm.ConsultaProduto.filtrar();
                vm.ConsultaProduto.option.filtro_sql = {};
            }
        });
        
        vm.ConsultaProduto.onSelect = function(){
            vm.FILTRO.PRODUTO_ID = vm.ConsultaProduto.item.dados.ID;
        };
        
        vm.ConsultaProduto.onClear = function (){
            vm.DADOS = [];
            vm.EstoqueLocalizacao.selected = [];
        };
        
        vm.FILTRO = {
            DATA_1 : moment().startOf("month").toDate(),
            DATA_2 : moment().endOf("month").toDate()
        };
        
        vm.filtrar = function() {
                        
            vm.EstoqueLocalizacao.selected = [];
            $ajax.post('/_15060/find',vm.FILTRO)
                .then(function(response) {
                    vm.DADOS = response;
            
                    if ( vm.DEF_LOCALIZACAO_ID > 0 ) {
                        for ( var i in vm.DADOS ) {
                            var item = vm.DADOS[i];

                            if ( parseInt(item.LOCALIZACAO_ID) == parseInt(vm.DEF_LOCALIZACAO_ID) && parseInt(item.ESTABELECIMENTO_ID) == 1 ) {
                                vm.EstoqueLocalizacao.selected = item;
                            }
                        }
                    }
                }
            );
        };
        
        $scope.$watch('vm.FILTRO', function (newValue, oldValue, scope) {
            vm.DADOS = [];
            vm.EstoqueLocalizacao.selected = [];
        }, true);
        
        $scope.$watch('vm.FILTRO.PRODUTO_ID', function (newValue, oldValue, scope) {
            if (newValue > 0) {
                vm.filtrar();
            }
        }, true);
        
        vm.EstoqueLocalizacao = {
            selected : null
        };
        
                        
        $timeout(function () {
            $('.resize')
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
        });
        //Aumentar tabela com duplo clique no puxador
        $(document)
            .on('dblclick','.table-lc .ui-resizable-s', function(e) {

                var table = $(this).closest('.ui-resizable');

                if ( $(table).data('original-size') == undefined || $(table).data('original-size') == 0 ) {
                    $(table).data('original-size',$(table).height());
                }  
                
                var bool = $(table).data('height-full') || false;
                $(table).data('height-full', ! bool);
                
                if ( $(table).data('height-full') == true ) {
        
                    var datatable_scrollbody = $(table).find('.table-body');
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
                            .scrollTop( datatable_scroll.offset().top - $('nav.navbar').outerHeight() - 50 )
                        ;

                    }
                    else {
                        tbody_height = tbody_height + 35;
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
    };

    Ctrl.$inject = ['$scope','$ajax','$timeout','$filter','$window','$interval', '$consulta'];

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
        
    angular
    .module    ('app'           , ['angular.filter','vs-repeat','gc-ajax','gc-form','gc-find','gc-transform'])
    .filter    ('parseDate'     , parseData)
    .controller('Ctrl'          , Ctrl     )
    .value('gScope', {})
    ;
        
})(angular);

;(function($) {

})(jQuery);
//# sourceMappingURL=app.js.map
