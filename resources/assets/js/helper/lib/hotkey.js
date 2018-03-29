/**
 * Script para atalhos do teclado.
 */
(function($) {
		
	/**
	 * Desabilitar voltar página caso pressione 'backspace'.
	 */
	function desabBackspace() {
		
//		$('body, select, input').bind('keydown', 'backspace', function() {
//			if( !$('input:not(:read-only)').is(':focus') )
//				return false;
//		});
		
	}
		
	/**
	 * Desabilitar submit a partir do 'Enter' nos formulários.
	 */
    
	function desabSubmitEnter() {

		$(document)
            .on('keypress', 'form', function (e) {
                var code = e.keyCode || e.which;

                var elmt = $(e.target);

                var is_textarea = $(e.delegateTarget.activeElement).is('textarea');
                if ( !is_textarea ) {
                    if (code === 13 && !elmt.is('[form-validate="true"]') ) {
                        e.preventDefault();
                        return false;
                    }
                }
            })
			.on('keydown', 'table tr[tabindex="0"]', 'down', function(e) {
                e.preventDefault();
                
                var scroll_parent = $(this).scrollParent();
                                
			})
			.on('keydown', 'table tr[tabindex="0"]', 'up', function(e) {
                e.preventDefault();
                
                var scroll_parent = $(this).scrollParent();
                
			})            
        ;
		
	}
		
	/**
	 * Ativar clique do botão com 'Enter'.
	 */
	function ativarCliqueEnter() {
	
		$('button, select').bind('keydown', 'return', function() {
			$(this).click();
		});
		
	}
		
	/**
	 * Sair do sistema.
	 */
	function ativarAtalhoSair() {
		
		$('body, input, select').bind('keydown', 'alt+s', function() {
			$('#logout')[0].click();
		});
		
	}

	/**
	 * Ativar atalhos com 'Esc'.
	 */
	function ativarEsc() {
//		
//		$(document)
//			.on('keydown', 'body, #menu-filtro', 'esc', function() {
//
//				//fecha alerta
//				if( $('.alert-principal').is(':visible') ) 
//					$('.alert-principal').slideUp();
//
//				//fecha filtro de menu
//				else if( $('#menu-filtro-resultado').hasClass('ativo') ) 
//					$('#menu-fechar').click();
//
//				//fecha menu
//				else if( $('#menu').hasClass('aberto') ) 
//					$('.navbar-toggle').click();
//
//				//se o visualizador de pdf estiver visível
//				else if ( $('.pdf-ver').is(':visible') )
//					$('.pdf-ver .pdf-fechar')[0].click();
//
//				//se o visualizador de arquivo estiver visível
//				else if ( $('.visualizar-arquivo').is(':visible') )
//					$('.esconder-arquivo')[0].click();
//
//				//fecha modal
//				else if( $('.modal').is(':visible') ) {
//				
//					// Esconde o último modal visível.
//					$('.modal:visible')
//						.last()
//						.find('.btn-voltar, .btn-cancelar')
//						.click()
//					;
//				}
//
//				//se o popup estiver visível
//				//else if ( $('.popup').is(':visible') && $('.btn-voltar.popup-close').length > 0 )
//				//	$('.btn-voltar.popup-voltar')[0].click();
//                
//				else if($('.popup').is(':visible')){
//                    
//                    if($('.modal.fade.in.confirm.carregado').is(':visible')){
//                        //se confirmação estiver ativa
//                    }else{
//                        if($('.popup-voltar').length > 0 ){
//                            
//                            if($('.popup-voltar').is(':visible')){
//                                $('.popup-voltar').trigger( "click" );
//                            }
//                            
//                        }else{
//                            
//                            if($('.popup-voltar2').length > 0 ){
//                                if($('.popup-voltar2').is(':visible')){
//                                    $('.popup-voltar2').trigger( "click" );
//                                }
//                            
//                            }else{
//                                if($('.popup-close').attr('oculto') != 1){
//                                    $('.popup-close').trigger( "click" );
//                                }
//                            }
//
//                        }
//                        
//                    }
//
//                    }
//				
//				else {
//					$('.btn-voltar').trigger( "click" );
//				}
//
//				return false;
//				
//			});
	}

	/** 
	 * Atalhos relacionados ao menu.
	 */
	function ativarAtalhoMenu() {
		
		//abrir/fechar menu
		$(document)
			.on('keydown', 'body, input[type="text"], textarea, select, #menu-filtro', 'alt+z', function() {
			
				if( $('.navbar-toggle').css('display') !== 'none' ) 
					$('.navbar-toggle').click();

			})
			.on('keydown', 'body, input[type="text"], textarea, select, #menu-filtro', 'pause', function() {
			
				if( $('.navbar-toggle').css('display') !== 'none' ) 
					$('.navbar-toggle').click();
			});
		
		/**
		 * Abrir submenu de acordo com o item passado.
		 * @param {int} submenu
		 */
//		function abrirSubmenu(submenu) {
//			
//			if ( $('#menu').hasClass('aberto') ) {
//				
//				$('#menu-itens button:nth-child('+submenu+')')
//					.click();
//				
//				setTimeout(function() { 
//					$('#menu-filtro-itens ul li:first-child a').focus(); 
//				}, 500);
//			}
//		}
//		
//		//abrir submenu Admin
//		$('body, #menu-filtro').bind('keydown', 'alt+1', function() {
//			abrirSubmenu(1);
//		});
		
	}
	
	/**
	 * Passar entre itens com as teclas up/down.
	 */
	function ativarTabSeta() {

		$(document)
			.on('keydown', 
				'table tbody tr',
                
				'down',
				function(e) {
				
					var res = true;

                    if ( $(this).hasClass('vs-repeat-repeated-element') && (! $($(this).next()[0]).hasClass('vs-repeat-repeated-element')) ) {
                        res = false;
                    } 
                    else if ( $(this).is(':last-of-type') ) {
                        res = false;
                    }
                    else if ( e.target.tagName == 'INPUT' ) {
                    	res = false;
                    }
                    
                    if (res)
                    	$.tabNext();
				}
			)
			.on('keydown', 
				'table tbody tr', 
                
				'up', 
				function(e) {
					
					var res = true;

                    if ( $(this).hasClass('vs-repeat-repeated-element') && (! $($(this).prev()[0]).hasClass('vs-repeat-repeated-element')) ) {
                        res = false;
                    }
                    else if ( $(this).is(':first-of-type') ) {
                        res = false;
                    }
                    else if ( e.target.tagName == 'INPUT' ) {
                    	res = false;
                    }
                    
                    if (res)                    
                    	$.tabPrev();
				}
			)
			.on('keydown', 
	
				'#menu-filtro, \n\
				 .consulta-descricao,\n\
				 #menu-filtro-itens ul li a,\n\
				 .lista-consulta ul li a',
		
				'down',
				function() {
				
					if( !$(this).parent().is(':last-of-type') )
						$.tabNext();
					
					return false;
				}
			)
			.on('keydown', 
				'#menu-filtro-itens ul li a, \n\
				 .lista-consulta ul li a', 
        
				'up', 
				function() {
					
					$.tabPrev();	
                    
					return false;
				}
			);
			
	}
    
    function validarAcao(tecla){
        
        var objetos = $('[data-hotkey="'+tecla+'"]:enabled');
        var i = 0;
        for (i = 0; i < $(objetos).length; i++) {
            
            var obj = $(objetos)[i];
            
            if( $(obj).is(':visible') ){
                
                if ( $(obj).is(':focusable') && $(obj).attr('no-focus') == undefined ) {
                    $(obj).focus();
                }
                
                obj.click();
                
            }
        }
        return false;
    }
		
	/** 
	 * Atalhos gerais.
	 */
	function ativarAtalhoGeral() {

		var recebe_acao = 'body, select, input, textarea, #menu-filtro';
		
		$(document)
			.on('keydown', recebe_acao, 'alt+1', function() {
				return validarAcao('alt+1');
			})
            .on('keydown', recebe_acao, 'alt+2', function() {
				return validarAcao('alt+2');
			})
            .on('keydown', recebe_acao, 'alt+3', function() {
				return validarAcao('alt+3');
			})
            .on('keydown', recebe_acao, 'alt+4', function() {
				return validarAcao('alt+4');
			})
            .on('keydown', recebe_acao, 'alt+5', function() {
				return validarAcao('alt+5');
			})
            .on('keydown', recebe_acao, 'alt+6', function() {
				return validarAcao('alt+6');
			})
			.on('keydown', recebe_acao, 'alt+7', function() {
				return validarAcao('alt+7');
			})
			.on('keydown', recebe_acao, 'alt+8', function() {
				return validarAcao('alt+8');
			})
			.on('keydown', recebe_acao, 'alt+9', function() {
				return validarAcao('alt+9');
			})
			.on('keydown', recebe_acao, 'alt+0', function() {
				return validarAcao('alt+0');
            })        
			.on('keydown', recebe_acao, 'f1', function() {
				return validarAcao('f1');
			})
            .on('keydown', recebe_acao, 'f2', function() {
				return validarAcao('f2');
			})
            .on('keydown', recebe_acao, 'f3', function() {
				return validarAcao('f3');
			})
            .on('keydown', recebe_acao, 'f4', function() {
				return validarAcao('f4');
			})
//            .on('keydown', recebe_acao, 'f5', function() {
//				return validarAcao('f15');
//			})
            .on('keydown', recebe_acao, 'f6', function() {
				return validarAcao('f6');
			})
			.on('keydown', recebe_acao, 'f7', function() {
				return validarAcao('f7');
			})
			.on('keydown', recebe_acao, 'f8', function() {
				return validarAcao('f8');
			})
			.on('keydown', recebe_acao, 'f9', function() {
				return validarAcao('f9');
			})
			.on('keydown', recebe_acao, 'f10', function() {
				return validarAcao('f10');
			})
			.on('keydown', recebe_acao, 'f11', function() {
		
				//se o visualizador de pdf estiver visível
				if ( $('.pdf-ver').is(':visible') )
					$('.pdf-ver .pdf-fechar')[0].click();
				
				//se o visualizador de arquivo estiver visível
				else if ( $('.visualizar-arquivo').is(':visible') )
					$('.esconder-arquivo')[0].click();
				
				//se o popup estiver visível
				else if ( $('.popup').is(':visible') && $('.popup .btn-voltar').length > 0 )
					$('.popup .btn-voltar')[0].click();

				//se o modal estiver visível
				else if ( $('.modal').is(':visible') ) {

					// esconde o último modal visível
					$('.modal:visible')
						.last()
						.find('.btn-voltar, .btn-cancelar')
						.click()
					;
				}

				//voltar padrão
				else if ( $('ul.acoes .btn-voltar').length > 0 )
					$('ul.acoes .btn-voltar')[0].click();
				
				//cancelar
				else if ( $('.btn-cancelar').length > 0 )
					$('.btn-cancelar')[0].click();

				//voltar
				else if ( $('[data-hotkey="f11"]').is(':visible') ){
					validarAcao('f11');
                }
				return false;
				
			})
			.on('keydown', recebe_acao, 'f12', function() {
				return validarAcao('f12');
			})
			.on('keydown', recebe_acao, 'alt+a', function() {
				return validarAcao('alt+a');
			})
			.on('keydown', recebe_acao, 'alt+b', function() {
				return validarAcao('alt+b');
			})
			.on('keydown', recebe_acao, 'alt+c', function() {
				return validarAcao('alt+c');
			})
			.on('keydown', recebe_acao, 'alt+d', function() {
				return validarAcao('alt+d');
			})
			.on('keydown', recebe_acao, 'alt+e', function() {
				$('[data-hotkey="alt+e"]')[0].click();
				return false;
			})
			.on('keydown', recebe_acao, 'alt+f', function() {
				return validarAcao('alt+f');
			})
			.on('keydown', recebe_acao, 'alt+g', function() {
				return validarAcao('alt+g');
			})
			.on('keydown', recebe_acao, 'alt+h', function() {
				return validarAcao('alt+h');
			})
			.on('keydown', recebe_acao, 'alt+i', function() {
				return validarAcao('alt+i');
			})
			.on('keydown', recebe_acao, 'alt+j', function() {
				return validarAcao('alt+j');
			})
			.on('keydown', recebe_acao, 'alt+k', function() {
				return validarAcao('alt+k');
			})
			.on('keydown', recebe_acao, 'alt+l', function() {
				return validarAcao('alt+l');
			})
			.on('keydown', recebe_acao, 'alt+m', function() {
				return validarAcao('alt+m');
			})
			.on('keydown', recebe_acao, 'alt+n', function() {
				return validarAcao('alt+n');
			})
			.on('keydown', recebe_acao, 'alt+o', function() {
				return validarAcao('alt+o');
			})
			.on('keydown', recebe_acao, 'alt+p', function() {
				return validarAcao('alt+p');
			})
			.on('keydown', recebe_acao, 'alt+q', function() {
				return validarAcao('alt+q');
			})
			.on('keydown', recebe_acao, 'alt+r', function() {
				return validarAcao('alt+r');
			})
			.on('keydown', recebe_acao, 'alt+s', function() {
				return validarAcao('alt+s');
			})
			.on('keydown', recebe_acao, 'alt+t', function() {
				return validarAcao('alt+t');
			})
			.on('keydown', recebe_acao, 'alt+u', function() {
				return validarAcao('alt+u');
			})
			.on('keydown', recebe_acao, 'alt+v', function() {
				return validarAcao('alt+v');
			})
			.on('keydown', recebe_acao, 'alt+w', function() {
				return validarAcao('alt+w');
			})
			.on('keydown', recebe_acao, 'alt+x', function() {
				return validarAcao('alt+x');
			})
			.on('keydown', recebe_acao, 'alt+y', function() {
				return validarAcao('alt+y');
			})
			.on('keydown', recebe_acao, 'alt+z', function() {
				return validarAcao('alt+z');
			})
			.on('keydown', recebe_acao, 'alt+f10', function() {
				return validarAcao('alt+f10');
			})
			.on('keydown', recebe_acao, 'alt+f11', function() {
				return validarAcao('alt+f11');
			})
			.on('keydown', recebe_acao, 'alt+left', function() {
				return validarAcao('alt+left');
			})
			.on('keydown', recebe_acao, 'alt+right', function() {
				return validarAcao('alt+right');
			})
			.on('keydown', recebe_acao, 'alt+up', function() {
				return validarAcao('alt+up');
			})
			.on('keydown', recebe_acao, 'alt+down', function() {
				return validarAcao('alt+down');
			})
			.on('keydown', recebe_acao, 'ctrl+left', function() {
				return validarAcao('ctrl+left');
			})
			.on('keydown', recebe_acao, 'ctrl+right', function() {
				return validarAcao('ctrl+right');
			})
			.on('keydown', recebe_acao, 'ctrl+up', function() {
				return validarAcao('ctrl+up');
			})
			.on('keydown', recebe_acao, 'ctrl+down', function() {
				return validarAcao('ctrl+down');
			})
			.on('keydown', recebe_acao, 'home', function() {
				if( !$('select, input, textarea').is(':focus') ) {
					return validarAcao('home');
				}
			})
			.on('keydown', recebe_acao, 'end', function() {
				if( !$('select, input, textarea').is(':focus') ) {
					return validarAcao('end');
				}
			})
//			.on('keydown', recebe_acao, 'pause', function() {
//				return validarAcao('pause');
//			})
            .keypress(function(event) {
                if($('.modal.fade.in.confirm.carregado').is(':visible')){
                    var keycode = event.keyCode || event.which;
                    if(keycode == '13') {

                            var i = 0;
                            for (i = 0; i < $('[data-hotkey="enter"]').length; i++) {
                                var obj = $('[data-hotkey="enter"]')[i];
                                if($(obj).is(':visible')){
                                    $(obj).click();
                                }
                            }
                    }
                }
            }).on('keydown', recebe_acao, 'esc', function() {
                if($('.modal.fade.in.confirm.carregado').is(':visible')){
                    var i = 0;
                    for (i = 0; i < $('[data-hotkey="esc"]').length; i++) {
                        var obj = $('[data-hotkey="esc"]')[i];
                        if($(obj).is(':visible')){
                            $(obj).click();
                        }
                    }
                }
                

				//fecha alerta
				if( $('.alert-principal').is(':visible') ) 
					$('.alert-principal').slideUp();

				//fecha filtro de menu
				else if( $('#menu-filtro-resultado').hasClass('ativo') ) 
					$('#menu-fechar').click();

				//fecha menu
				else if( $('#menu').hasClass('aberto') ) 
					$('.navbar-toggle').click();

				//se o visualizador de pdf estiver visível
				else if ( $('.pdf-ver').is(':visible') )
					$('.pdf-ver .pdf-fechar')[0].click();

				//se o visualizador de arquivo estiver visível
				else if ( $('.visualizar-arquivo').is(':visible') )
					$('.esconder-arquivo')[0].click();

				//fecha modal
				else if( $('.modal').is(':visible') ) {
				
					// Esconde o último modal visível.
					$('.modal:visible')
						.last()
						.find('.btn-voltar, .btn-cancelar')
						.click()
					;
				}

				//se o popup estiver visível
				//else if ( $('.popup').is(':visible') && $('.btn-voltar.popup-close').length > 0 )
				//	$('.btn-voltar.popup-voltar')[0].click();
                
				else if($('.popup').is(':visible')){
                    
                    if($('.modal.fade.in.confirm.carregado').is(':visible')){
                        //se confirmação estiver ativa
                    }else{
                        if($('.popup-voltar').length > 0 ){
                            
                            if($('.popup-voltar').is(':visible')){
                                $('.popup-voltar').trigger( "click" );
                            }
                            
                        }else{
                            
                            if($('.popup-voltar2').length > 0 ){
                                if($('.popup-voltar2').is(':visible')){
                                    $('.popup-voltar2').trigger( "click" );
                                }
                            
                            }else{
                                if($('.popup-close').attr('oculto') != 1){
                                    $('.popup-close').trigger( "click" );
                                }
                            }

                        }
                        
                    }

                    }
				
//				else {
//					$('.btn-voltar').trigger( "click" );
//				}                
                
                
                
                
            })
            .on('keydown', 
                'input',
                'del',
                function() {
//                    if ( $(this).prop('readonly') || $(this).prop('disabled') ) return false;
////                    $(this).val('').triggerHandler('change');
//                    return false;
                }
            )
		;
        
        $('[form-validade="true"]').keypress(function( event ) {
            if ( event.which == 13 ) {
                $(this).closest('form').find('button[type="submit"]').click();
            }
        });
                
//        $(document).on('keypress', function(e) {
//            var elmt = $(e.target);
//            
//            if ( e.which == 13 && elmt.is('[form-validate="true"]') ) {
//                elmt.closest('form').find('button[type="submit"]').click();
//            }
//        });        

	}
	
	$(function() {
		
		desabBackspace();
		desabSubmitEnter();
		ativarCliqueEnter();
		ativarAtalhoSair();
		ativarEsc();
		ativarAtalhoMenu();
		ativarTabSeta();
		ativarAtalhoGeral();
		
	});
	
})(jQuery);