/**
 * Script relacionado ao menu com as seguintes funções:
 * - Abrir/fechar menu
 * - Filtrar menu
 * */
(function($) {
	$(function() {	
        //Abrir/fechar menu pelo botão de menu
        $('.navbar-toggle').click(function() {
            $('#menu').toggleClass('aberto');
            $('.navbar').toggleClass('menu-aberto');
            if ( isMobile() ) return false;
            $('#menu-filtro').focus();
        });

        //focar o campo de filtro quando estiver na página inicial
        if( window.location.pathname === '/home' && !(isMobile()) )
            $('#menu-filtro').focus();
	});
})(jQuery);


;(function(angular) {
   
    var MenuCtrl = function($scope,$ajax,$timeout,$localStorage,$window) {     
        var vm = this;
        
        vm.itens               = [];
        vm.$storage            = $localStorage;

        vm.btnCarregarMenus = function() {
            $ajax.post('/listarMenu')
                .then(function(response) {
                    vm.$storage.menus  = response.menus;
                    vm.$storage.grupos = response.grupos;
                    showSuccess('Menus atualizados com sucesso!');
                }
            );  
        };
        
        vm.CarregarMenus = function() {
            $ajax.post('/listarMenu')
                .then(function(response) {
                    vm.$storage.menus  = response.menus;
                    vm.$storage.grupos = response.grupos;
                }
            );  
        };

        vm.LoadMenus = function() {
            if ( vm.$storage.menus == undefined || vm.$storage.grupos == undefined ) {
                vm.CarregarMenus();
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
        
        vm.menuSelecionar = function($event){
            
            /* Verifica se a tecla pressionada foi 'Enter' */
            if ( $event.keyCode === 13 ) {
                if ( vm.menus_filtered.length == 1 ) {
                    var menus = $('#menu-filtro-itens');
                    var ancora = menus.find('a[href]').first();
                    var link = ancora.attr('href');
                    
                    $window.location.href = link;
                }
            }
        };
        
        //Abre resultado da filtragem
        function abreFiltroMenu() {

            $('#menu-filtro-resultado')
                .addClass('ativo');

            $('#btn-filtro-menu')
                .attr('tabindex', '-1');

        }

        //Fecha resultado da filtragem
        function fechaFiltroMenu() {
            $timeout(function () {
                $scope.$apply(function(){
                    vm.filtrar_menu = '';
                    vm.menu_grupo = ''; 
                });
            }, 100);
            
            $('#menu-filtro-resultado')
                .removeClass('ativo');

            $('#btn-filtro-menu')
                .removeAttr('tabindex');
        
            if ( isMobile() ) return false;
            $('#menu-filtro')
                .val('')
                .focus();
        }
        
        vm.DropdownMenu = function( open ) {
            var btn_filtro_menu = $('#btn-filtro-menu');
            if ( vm.filtrar_menu != '' || open ) {
				abreFiltroMenu();
            } else {
				fechaFiltroMenu();
            }
        };

        //Fechar filtro do menu ao clicar em fechar
        $('#menu-fechar').click(function() {
            fechaFiltroMenu();
        });	
        
		$(document)
            .on('keydown', 
                '#menu-filtro',
                'del',
                function() {
                    if ( $(this).prop('readonly') || $(this).prop('disabled') ) return false;
                    $(this).val('');
                    fechaFiltroMenu();
                    return false;
                }
            )
            .on('keydown', 
                function(e) {
                    
                    if ( $('#menu').hasClass('aberto') || pathname == '/home' ) {
                        
                        var letras = /[A-z]/;
                        var numeros = /[0-9]/;

                        var result_letras = letras.test(String.fromCharCode(e.keyCode));
                        var result_numeros = numeros.test(String.fromCharCode(e.keyCode));
                        var result = result_letras | result_numeros;

                        if ( result || e.keyCode == 46 || e.keyCode == 8 ) {
                            $('#menu-filtro').focus();
                            
                            if ( e.keyCode == 46 ) {
                                var e = jQuery.Event("keydown");
                                e.which = 46;
                                $('#menu-filtro').trigger(e);
                            }
                        }
                    }
                }
            )
		;
        
    };

    $(document)
        .on('click','#voltar-User',
            function() {
                
                function success(data) {
                    var user_id = $('#usuario-id').val();
                    window.localStorage.removeItem('ngStorage-menus');
                    window.location.href = urlhost + '/_11010';

                }

                execAjax1('POST','/_11010/voltarUser',[],success);
            }
        );
    
    MenuCtrl.$inject = ['$scope','$ajax','$timeout','$localStorage','$window'];
    
    angular
        .module('appMenu', ['angular.filter','vs-repeat','ngStorage','gc-ajax','gc-find','gc-transform'])
        .controller('MenuCtrl', MenuCtrl)
    ;
    
    angular.bootstrap(document.getElementById('menu'), ['appMenu']);
    
    // Servirá para evitar erro de inicialização do app
    // no caso da tela não utilizar angular.
    angular.module('app', []);

})(angular);