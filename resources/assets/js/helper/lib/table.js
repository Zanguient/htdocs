/** 
 * Script para tabelas.
 */

/**
 * Ativa o link em linhas de tabela.<br/>
 * Ex. de uso: &lt;tr link="#" /&gt;
 */
function cliqueTabela() {
		
	$(document)
		.on('click', 'table tbody tr', function() {
			var link = $(this).attr('link');
			if( link ) 
				location.href = link;
		})
		.on('keydown', 'table tbody tr', 'return', function() {
			$(this)
				.children('td')
				.click();
		})
		.on('keydown', 'table tbody tr', 'space', function() {
			$(this)
				.children('td')
				.click();
		});
}

/**
 * Redimensionar tabela de acordo com a tela.
 */
function redimensionaTabela() {

	if( $('section.tabela').length > 0 ) {
	
		 var section = $('section.tabela');

		 //só redimensiona a tabela se ela tiver a class 'lista-obj'.
		 if( $(section).children('table').hasClass('lista-obj') ) {
			 $(section).css('max-height', $(window).height() - 220 );
		 }
		 
	}
}

/**
 * Tabela com cabeçalho fixo.
 */
function cabecalhoFixo() {

   if( $('section.tabela table').length > 0 ) {

		var section = $('section.tabela');
		var table	= $('section.tabela table');
		
		$(table).stickyTableHeaders({
			scrollableArea: $(section),
			fixedOffset: $(table).position().top
		});
   }
}

/** 
 * Esconder botão de carregar página exista apenas uma página. 
 */
function esconderBotaoCarregar() {
			
	if ( $('button.carregar-pagina').length > 0 ) {

		if ( $('table tbody tr').siblings().length < 30 ) {
			$('button.carregar-pagina').hide();
		}
	}
}




/** 
 * Filtrar itens do objeto listados 
 */
function ativarFiltrarListaObj() {
	
	/**
	 * Filtro.
	 */
	function filtrarListaObj() {

		var filtro = $('.filtro-obj').val()
			status = $('#filter-status').val();
		
		//ajax
		var type	= "POST",
			url		= pathname+"/filtraObj",
			data	= {'filtro': filtro, 'status': status},
			success	= function(data) {

				if(data) {
					
//					destruirDataTable();
					
					$('table.lista-obj tbody')
						.empty()
						.append(data);
				
					$('button.carregar-pagina')
						.hide();
					
//					checkboxInTable(true);
					
				}

				//se o campo filtro for vazio, mostra o botão de carregar mais
				if( (filtro === '') && ($('table.lista-obj tbody tr').siblings().length > 30) ) {
					$('button.carregar-pagina').show();
				}
			}
		;
		
		execAjax1(type, url, data, success);

	}

	/**
	 * Eventos da filtragem.
	 */
	function eventoFiltrarListaObj() {
	
		if( $('table').hasClass('lista-obj') ) {

			$('.btn-filtro-obj').click(function() {
				filtrarListaObj();
			});				

			$('.filtro-obj').keydown(function(e) {
				if (e.keyCode === 13) 
					filtrarListaObj();					
			});
		}
	}
	
	eventoFiltrarListaObj();
}

/**
 * Tabela com linha selecionável.
 */
function linhaSelecionavel() {
	
	function removeClasse(e){

        if ($(e).hasClass("rowselected")) {
            $(e).removeClass( 'rowselected' );   
        }    
    }

    function selecionarLinha(e){

        var cars = $('.rowselected');
        var pai1 = $(e).parent().parent();
        
        for (i = 0; i < cars.length; i++) {
            
            var pai2 = $(cars[i]).attr('tabela');
        
            if ( $(pai1).hasClass(pai2)){
                removeClasse(cars[i]);
            }
        }

        $(e).addClass('rowselected');
    }
           
    function marcaLinhas(){
    
		var cont = 0;
        
        $('.table-selectable').each(function() {
            cont++;

            var clase = 'Tabela'+cont;
            $(this).addClass(clase);
            
            $(this).find('tr').addClass('linha-selecionavel');
            $(this).find('tr').attr('tabela',clase);
            
        });
    };
	
	function eventoLinhaSelecionavel() {
		
		$(document).on('click','.linha-selecionavel', function(e) {
			selecionarLinha(this);
		});
	}
	
	marcaLinhas();
	eventoLinhaSelecionavel();
	
}


/**
* Carrega e configura tabela
* @returns {void}
*/
function ativarDataTable() {
	
	$('.table tbody tr')
		.attr('tabindex', 0);

	var data_table = $.extend({}, table_default);
		//data_table.scrollY = 'auto';             

	$('.table').DataTable(data_table);   
	
//	$('.table')
//	   .DataTable({
//            "scrollY"  : '70vh', // Altura da tabela 
//            "scrollX"  : true  , // Habitila a rolagem horizontal
//            "bSort"    : false , // Desativa a ordenação
//            "bFilter"  : false , // Desativa o filtro
//            "bInfo"    : false , // Desativa as informações de registro
//            "bPaginate": false , // Desativa a paginação
//            "language" : {"emptyTable" : "Não há registros para listar"}
//        })
//   ;

	delete data_table;

}

/**
 * Verificar checkbox desabilitado na tabela,
 * para não permitir clique.
 */
//function verifyDisableCheckboxInTable() {
//	
//	$('table.lista-obj tbody tr td.disabled').click(function(e) {			
//		e.stopPropagation();
//	});
//}

/**
 * Ativar tabela com checkbox.
 * @param {boolean} apos_filtro
 */
//function checkboxInTable(apos_filtro) {
//	
//	var table;
//	
//	if( typeof(apos_filtro) === 'undefined') {	
//		table = $('table.lista-obj').DataTable();
//		table.destroy();		
//	}
//	
//	table = $('table.lista-obj').DataTable( {
//		scrollY  : '70vh', // Altura da tabela 
//		scrollX  : true,  // Habitila a rolagem horizontal
////		bSort    : false, // Desativa a ordenação
//		bFilter  : false, // Desativa o filtro
//		bInfo    : false, // Desativa as informações de registro
//		bPaginate: false, // Desativa a paginação
//        columnDefs: [ {
//            orderable: false,
//            className: 'select-checkbox',
//            targets:   0
//        } ],
//        select: {
//            style:    'multi',
//            selector: 'td:first-child'
//        },
//        order: [[ 1, 'desc' ]]
//		
////      ordering: false,
////		deferRender:    true,
////		scroller:    true
////      scrollCollapse: true,
//    } );
//	
//	verifyDisableCheckboxInTable();
//	
//}

/**
 * Destruir objeto dataTable.
 */
function destruirDataTable() {
	
	$('.dataTables_scrollBody table')
		.dataTable()
		.fnDestroy();
	
}

/**
 * Ativar seleção de linha da tabela com checkbox.
 */
function ativarSelecLinhaCheckbox() {
	
	var chk_length	=	$('table')
							.find('input[type="checkbox"]')
							.length
						;
	
	if ( chk_length > 0 ) {
		
		$('table')
			.on('change', 'input[type="checkbox"]', function() {
	
				var tr	=	$(this)
								.closest('tr')
							;
				
				if ( $(tr).hasClass('selected') ) {
			
					$(tr)
						.removeClass('selected')
					;
					
				}
				else {
					
					$(tr)
						.addClass('selected')
					;
					
				}
				
				delete tr;
			
			})
			.on('click', 'tbody tr', function() {

				var chk	=	$(this)
								.find('input[type="checkbox"]')
							;
								
				$(chk)
					.prop('checked', !$(chk).is(':checked'))
					.change()
				;
				
				delete chk;

			})
			.on('keydown', 'tbody tr', 'return', function() {
				
				$(this)
					.click()
				;
		
			})
		;	
	}
	
	delete chk_length;
	
}

/**
 * Ativar seleção de linha da tabela com radio.
 */
function ativarSelecLinhaRadio() {
	
	var radio_length	=	$('table')
								.find('input[type="radio"]')
								.length
							;
	
	if ( radio_length > 0 ) {
		
		$('table')
			.on('change', 'tbody tr td input[type="radio"]', function() {

				if ( !$(this).is(':checked') ) {

					$(this)
						.closest('tr')
						.removeClass('selected')
						.siblings('tr')
						.removeClass('selected')
					;

				}
				else {

					$(this)
						.closest('tr')
						.addClass('selected')
						.siblings('tr')
						.removeClass('selected')
					;

				}
				
			})
			.off('click').on('click', 'tbody tr', function() {
				
				$(this)
					.find('input[type="radio"]')
					.prop('checked', true)
					.change()
				;

			})
			.on('keydown', 'tbody tr', 'return', function() {
				
				$(this)
					.click()
				;
		
			})			
		;
		
	}
	
	delete radio_length;
	
}

(function($) {
	
	/**
	* Paginação com scroll.
	*/
	function paginacaoScroll() {

	   var pagina_atual = 1;
	   var pagina_inc   = 0;
	   var qtd_por_pag  = 30;
	   var final_pag	= false;	//variável para verificar se chegou na última página
	   var status 		= 0;

	   /**
		* Carregar páginas
		*/
	   function carregarPagina() {

	   		status_ant 	= status;
	   		status 		= $('#filter-status').val();

	   		// se o status for alterado, a paginação deve ser retomada
	   		if (status != status_ant) {
	   			pagina_atual = 1;
				pagina_inc = 0;
	   			final_pag = false;
	   		}

			if ( final_pag || ($('.filtro-obj').val() !== '') ) {
			   pagina_atual = 1;
			   pagina_inc = 0;
			   return false;
			}

			pagina_atual += 1;
			pagina_inc = pagina_atual * qtd_por_pag - qtd_por_pag;
		   
			//ajax
			var type	= "POST",
				url		= pathname+"/paginacaoScroll",
				data	= {
					 'qtd_por_pagina': qtd_por_pag, 
					 'pagina'		: pagina_inc,
					 'status'		: status
				},
				success	= function(resposta) {

				   if(resposta) {						

					   $('table.lista-obj tbody')
						   .append(resposta);

				   }
				   else {
					   final_pag = true;
				   }

			   }
			;
			
			execAjax1(type, url, data, success);
	   }

	   /**
		* Eventos do scroll.
		*/
	   function eventoScroll() {

		   setTimeout(function() {		//espera necessária devido ao plugin dataTable

			   if( $('table').hasClass('lista-obj') ) {	//somente se a página tiver listagem

				   var scroll_timer = 0;	//verificar timeout

				   //carregar página com scroll
				   //$('section.tabela').scroll(function() {
				   $('.dataTables_scrollBody').scroll(function() {

					   var div = $(this);

					   clearTimeout(scroll_timer);

					   scroll_timer = setTimeout(function() {
						   if( ( div.scrollTop() + div.height() ) >= div.children('table').height() )
							   carregarPagina();
					   }, 200);

				   });

				   //carregar página com clique
				   $('.carregar-pagina').click(function() {
					   carregarPagina();
				   });

			   }
		   }, 1000);
	   }

	   eventoScroll();
	}
	
	$(function() {
		
		cliqueTabela();
		ativarSelecLinhaCheckbox();
//		redimensionaTabela();
//		cabecalhoFixo();
		esconderBotaoCarregar();
		paginacaoScroll();
		ativarFiltrarListaObj();
		linhaSelecionavel();		
		ativarDataTable();
		

//		$(document)
//			.on('DOMNodeInserted', 'table', function(e) {
//				var element = e.target;
//				//console.log('changetable: '+$(this).attr('class'));
//				$(element).closest('table').trigger('resize');
//			})
//		;

//		$(window).resize(function () {
//			redimensionaTabela();
//			cabecalhoFixo();
//		});
		
	});
})(jQuery);