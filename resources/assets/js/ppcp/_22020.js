/* global table_default, requestPost, moment */

/**
 * Script com funções do obj _22020.
 */
(function($) {
	
	/**
	 * Indica se o talão está em andamento.
	 */
	var talao_em_andamento = false;
	
	/**
	 * Guardar tempo do interval do tempo realizado 
	 */
	var time_interval;

	/**
	 * Tempo total de produção.
	 * Está fora das funções pois é uma variável compartilhada por ambas,
	 * para uma precisão exata nos cálculos.
	 */
	var tempo_total = 0;
	
	/**
	 * Corres usadas no grafico.
	 */
 	

    /**
	 * Ativar redimensionamento das tabelas.
	 * @param {element} table
	 */
	function ativarRedimensionamento(table) {
		
		//Laço necessário para aplicar redimensionamento nas tabelas individualmente,
		//evitando que ao redimensionar uma tabela, as outras também se redimensionem.
		$(table).each(function() {	
			
			var datatable_scroll		= $(this).closest('.dataTables_scroll');
			var datatable_scrollbody	= $(datatable_scroll).find('.dataTables_scrollBody');
			var instancia_resizable		= $(datatable_scroll).resizable('instance');

			//verificar se 'resizable' já está aplicado na tabela antes de destruí-lo.
			if( typeof instancia_resizable !== 'undefined' ) {

				$(datatable_scroll).resizable('destroy');
				$(datatable_scrollbody).resizable('destroy');

			}

			$(datatable_scroll)
				.resizable({
					handles		: 's',
					alsoResize	: datatable_scrollbody,
					minHeight	: 65,
					stop		: function( event, ui ) {
						datatable_scrollbody.height(ui.size.height - 35);	//fix para remover espaçamento do puxador ao redimensionar várias vezes.
					}
				})
			;

			$(datatable_scrollbody)
				.resizable({
					handles		: 's',
					minHeight	: 33	//precisa ser aplicado no CSS também, pois esta div é redimensionada pelo 'datatable_scroll'
				})
			;
			
			//Aumentar tabela com duplo clique no puxador
			$(datatable_scroll)
				.children('.ui-resizable-s')
				.off('dblclick')
				.on('dblclick', function() {
					
					var tbody_height	= $(table).find('tbody').height(),
						window_height	= $(window).height(),
						vh_context		= window_height * 0.01,	//converter px para vh - parte I
						tbody_height_vh = tbody_height / vh_context //converter px para vh - parte II
					;
					
					
					//Se a altura do tbody for maior que 70vh, tbody_height terá 70vh de altura, pois esse é o valor máximo permitido (altura da tela);
					//senão, a altura será a altura inicial + 34, que é a altura do cabeçalho da tabela.
					if (tbody_height_vh > 70) {
						
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
					
					$(datatable_scroll)
						.height( tbody_height )
					;
					$(datatable_scrollbody)
						.height( datatable_scroll.height() - 34 )
					;
					
					////TENTATIVAS PARA QUE A TABELA SE ADAPTASSE DE ACORDO COM SUA POSIÇÃO
/////////////////
//					var distance_from_bottom = Math.floor($(window).height() - $(datatable_scroll).height() - $(document).scrollTop());
//					var distance_from_bottom = $(document).height() - $(window).height() - $(window).scrollTop();
//					var distance_from_bottom = $(datatable_scroll).height() - $(window).height() - $(window).scrollTop();
/////////////////
///////////////					
//					var link = $(datatable_scroll);

//					var offset = link.offset();
//					var top = offset.top;
//					var left = offset.left;
//					var bottom = $(window).height() - offset.top - link.height();
//					bottom -= offset.top;
//					var right = $(window).width() - link.width();
//					right = offset.left - right;
//////////////		
/*
					var offset_top		= $(datatable_scroll).offset().top;
					var actual_bottom	= ($(window).height()) - (offset_top + $(datatable_scroll).outerHeight(true));
					
					console.log('T:'+$(datatable_scroll).outerHeight(true));
					console.log('O:'+offset_top);
					console.log($(datatable_scroll).outerHeight(true) + offset_top);
					console.log('W:'+$(window).height());
					console.log('B:'+actual_bottom);
					console.log('---');
			
					$(datatable_scroll)
						.height('calc(100vh - '+ actual_bottom +'px)')
//						.height('calc(100vh - '+ distance_from_bottom +'px)')
//						.height('calc(100vh - '+ (alt+7) +'px)')
//						.height('70vh')
					;
					$(datatable_scrollbody)
						.height(datatable_scroll.height() - 35)
					;
*/			
				})
			;
			
		});
	}
	
	/**
	 * Ativar Datatable.
     * @param {element} table
     * @returns {void}
     */
	function ativarDatatable(table) {
		
		var table = table || $('.table');
        
		var data_table = $.extend({}, table_default);
			data_table.scrollY = '21vh';

		$(table).DataTable(data_table);
		
		ativarRedimensionamento(table);
		
		delete data_table;
		delete table;
	}
	
	/**
	 * Manobra para corrigir problema de alinhamento do DataTable.
	 */
	function fixDatatable() {
		
		$('#talao-produzir-tab')
			.click(function() {
				
				$('.table-talao-produzir')
					.trigger('resize')
				;
				
			})
		;
		
		$('#talao-produzido-tab')
			.click(function() {
				
				$('.table-talao-produzido')
					.trigger('resize')
				;
				
			})
		;
		
	}

	/**
	 * Definir os parâmetros de um campo a partir de outro.
	 */
	function definirParam() {
		
		/**
		 * Define o GP selecionado como parâmetro para o Perfil de UP.
		 */
		function gpParamPerfilUp() {

			$('._gp_id')
				.change(function() {

					$('.consulta_perfil_up_group, .consulta_up_group')
						.siblings('._consulta_parametros')
						.children('._consulta_filtro[objcampo="GP"]')
						.val( $(this).val() )
					;

				})
			;

		}

		/**
		 * Define o Perfil de UP selecionado como parâmetro para a UP.
		 */
		function perfilUpParamUp() {

			$('._perfil_up_id')
				.change(function() {

					$('.consulta_up_group')
						.siblings('._consulta_parametros')
						.children('._consulta_filtro[objcampo="PERFIL_UP"]')
						.val( $(this).val() )
					;

				})
			;

		}

		/**
		 * Define o UP selecionado como parâmetro para a Estação.
		 */
		function upParamEstacao() {

			$('._up_id')
				.change(function() {

					$('.consulta_estacao_group')
						.siblings('._consulta_parametros')
						.children('._consulta_filtro[objcampo="UP"]')
						.val( $(this).val() )
					;

				})
			;

		}
		
		gpParamPerfilUp();
		perfilUpParamUp();
		upParamEstacao();
	}
	
	/**
	 * Verificar se existe um talão em andamento e se o mesmo está selecionado.
	 */
	function verificarTalaoSelecEmProducao() {
		
		var ret = false;
		
		$('.table-talao-produzir')
			.find('td')
			.each(function() {

				if( $(this).hasClass('status2') && $(this).parent('tr').hasClass('selected') ) {
					
					new tempoProducao().tempoRealizadoEmAndamento(true);
					ret = true;
					return false;
					
				}
				else {
					new tempoProducao().tempoRealizadoEmAndamento(false);
				}

			})
		;
		
		return ret;
				
	}
	
	/**
	 * Verificações.
	 */
	function verificarFiltro() {
		
		/**
		 * Ação ao excluir qualquer filtro.
		 */
		function acaoExcluir() {
			
			talao_em_andamento = false;
			habilitarBtnAcao(false, false, false);
			infoDestaqueLimpar();
			
		}
		
		/**
		 * Ações ao excluir GP.
		 */
		function acaoExcluirGp() {
			
			$('.consulta_gp_grup .btn-apagar-filtro')
				.click(function() {
					
					//excluir perfil de up, up e estação
					$('.consulta_perfil_up_group, .consulta_up_group, .consulta_estacao_group')
						.children('.btn-apagar-filtro')
						.click()
					;
					
					$('table tbody')
						.empty()
					;
					
					acaoExcluir();
					
				})
			;
			
		}

		/**
		 * Ações ao excluir Perfil de UP.
		 */
		function acaoExcluirPerfilUp() {
			
			$('.consulta_perfil_up_group')
				.children('.btn-apagar-filtro')
				.click(function() {
					
					//excluir estação
					$('.consulta_up_group, .consulta_estacao_group')
						.children('.btn-apagar-filtro')
						.click()
					;
					
					$('table tbody')
						.empty()
					;
					
					acaoExcluir();
					
				})
			;
			
		}
		
		/**
		 * Ações ao excluir UP.
		 */
		function acaoExcluirUp() {
			
			$('.consulta_up_group')
				.children('.btn-apagar-filtro')
				.click(function() {
					
					//excluir estação
					$('.consulta_estacao_group')
						.children('.btn-apagar-filtro')
						.click()
					;
					
					$('table tbody')
						.empty()
					;
					
					acaoExcluir();
					
				})
			;
			
		}
		
		/**
		 * Ações ao excluir Estação.
		 */
		function acaoExcluirEstacao() {
			
			$('.consulta_estacao_group')
				.children('.btn-apagar-filtro')
				.click(function() {
					
					$('table tbody')
						.empty()
					;
					
					acaoExcluir();
					
				})
			;
			
		}
		
		acaoExcluirGp();
		acaoExcluirPerfilUp();
		acaoExcluirUp();
		acaoExcluirEstacao();
		
	}   
	
	/**
	 * Objeto para a composição do talão.
	 */
	function TalaoComposicao() {

		this.consulta			= consulta;
		this.talaoSelecionado	= talaoSelecionado;
		
		var produzir_selecionado	= true;	//verifica se a aba talão à produzir está selecionada
		
		/**
		 * Preencher tabela de detalhamento do talão.
		 * @param {view} conteudo
		 */
		function preencheDetalhe(conteudo) {

			var div_table		= $('#detalhe');
			var scr				= new Scroll();
			var scroll_posicao	= scr.getX(div_table);

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));
			ativarSelecLinhaRadio();
			editarQtdDetalhe();
			acoesTalaoDetalhe();

			scr.setX(div_table, scroll_posicao);

			if ( talao_em_andamento && produzir_selecionado ) {
				habilitarBtnDetalhe(true);
				habilitarBtnEditarQtd(true);
			}
			else {
				habilitarBtnDetalhe(false);
				habilitarBtnEditarQtd(false);
			}
			
			delete div_table;
			delete scr;
			delete scroll_posicao;

		}

		/**
		 * Preencher tabela de histórico do talão.
		 * @param {view} conteudo
		 */
		function preencheHistorico(conteudo) {

			var div_table = $('#historico');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));

			new tempoProducao().tempoRealizado(div_table, false);

			verificarTalaoSelecEmProducao();
			
			delete div_table;

		}

		/**
		 * Preencher tabela de matéria-prima.
		 * @param {view} conteudo
		 */
		function preencheMateriaPrima(conteudo) {

			var div_table = $('#materia-prima');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));
			ativarSelecLinhaRadio();
			editarQtdMateriaPrima();

			if ( talao_em_andamento && produzir_selecionado )
				habilitarBtnMateriaPrima(true);
			else
				habilitarBtnMateriaPrima(false);
			
			delete div_table;

		}

		/**
		 * Preencher tabela de defeitos do talão.
		 * @param {view} conteudo
		 */
		function preencheDefeito(conteudo) {

			var div_table = $('#defeito');

			$(div_table)
				.html(conteudo)
			;

			ativarDatatable(div_table.find('table'));
			
			delete div_table;

		}

		/**
		 * Retorna os dados de parâmetro da consulta da composição.
		 * @returns {_22020_L6.TalaoComposicao.getDados.dados}
		 */
		function getDados() {
			
			var table;
			var tr_selec;
			var dados = {};
			produzir_selecionado = $('#talao-produzir-tab').parent().hasClass('active');
			
			//definir tabela
			table		= produzir_selecionado ? $('#talao-produzir') : $('#talao-produzido');
					
			//linha selecionada
			tr_selec	= $(table).find('tbody').find('tr.selected');
			
			if ( $(tr_selec).length === 1 ) {
				
				dados = {
					retorno				: ['VIEW'],
					id					: $(tr_selec).find('._id'				).val(),
					remessa_id			: $(tr_selec).find('._remessa-id'      ).val(),
					remessa_talao_id	: $(tr_selec).find('._remessa-talao-id').val(),
					programacao_id		: $(tr_selec).find('._programacao-id'	).val(),
					status				: produzir_selecionado ? '0' : '1',
					ver_peca_disponivel : $('._ver-peca-disponivel-gp').val().trim()
				};
				
			}
			else {
				dados = {};
			}
			
			return dados;
			
			delete table;
			delete tr_selec;
			delete dados;
			
		}
		
		/**
		 * Consulta os dados do talão.
		 */
		function consulta() {

			return new Promise(function(resolve) {
				
				var url		= '/_22020/consultarTalaoComposicao',
					dados	= getDados()
				;

				execAjax1(
					'POST',
					url,
					dados,
					function(resposta) {

						preencheDetalhe(resposta.DETALHE);
						preencheHistorico(resposta.HISTORICO);
						preencheMateriaPrima(resposta.MATERIA);
						preencheDefeito(resposta.DEFEITO);
						
						resolve(true);
						
						delete url;
						delete dados;

					}
				);

			});

		}

		/**
		 * Ações ao selecionar um talão.
		 */
		function talaoSelecionado() {

			$('#talao-produzir, #talao-produzido')
				.find('tbody')
				.find('tr')
				.click(function() {

					if ( $(this).attr('disabled') ) {
						return false;
					}

					$(this)
						.addClass('selected')
						.siblings()
						.removeClass('selected')
					;

					produzir_selecionado = $('#talao-produzir-tab').parent().hasClass('active');

					if (produzir_selecionado) {

						if (!talao_em_andamento)
							habilitarBtnAcao(true, false, false, false);
					}
					else {
						habilitarBtnAcao(false, false, false, true);
					}

					consulta();
					resumoProducao();

				})
			;
		}

	}
	
	/**
	 * Marcar posição do tr com '#' na url.
	 */
	function marcarPosicaoTr() {

	   $('.table-talao-produzir tbody tr')
		   .click(function() {

				location.hash = $(this).data('id');						

		   })
	   ;

	}

	/**
	 * Focar no 'tr' que já tenha sido selecionado antes de mudar de aba.
	 */
	function focarTrTalaoProduzir() {

		var hash = location.hash;

		if ( hash ) {

			$( hash.replace('id-', '') )
				.click()
				.focus()
			;

		}

	}
	
	/**
	 * Alterar estado dos botões de ações (habilitar/desabilitar).
	 * @param {boolean} iniciar
	 * @param {boolean} pausar
	 * @param {boolean} finalizar
	 * @param {boolean} etiqueta
	 */
	function habilitarBtnAcao(iniciar, pausar, finalizar, etiqueta) {
		
		etiqueta		=	(typeof etiqueta === 'undefined') ? false : etiqueta;
		var tr_selec	=	$('#talao-produzir').find('tbody').find('tr.selected');
		
		/**
		 * Habilitar/desabilitar botões de ações.
		 */
		function habilitar(iniciar, pausar, finalizar, etiqueta) {
			
			$('#iniciar'  ).prop('disabled', !iniciar  );
			$('#pausar'   ).prop('disabled', !pausar   );
			$('#finalizar').prop('disabled', !finalizar);
			$('#etiqueta' ).prop('disabled', !etiqueta );
			$(tr_selec	  ).attr('title', '');
			
		}
		
		/**
		 * Habilitar/desabilitar botão Iniciar.
		 * @param {Boolean} habilitar
		 * @param {String} title Mensagem com o motivo do bloqueio.
		 */
		function habilitarBtnIniciar(habilitar, title) {
			
			$('#iniciar')
				.prop('disabled', !habilitar)
			;
			
			$(tr_selec)
				.attr('title', title)
			;
			
		}
		
		/**
		 * Verificar se a data do talão está fora do intervalo máximo permitido.
		 * @returns {Boolean}
		 */
		function verificarIntervalo() {
			
			var data_remessa_selec	=	$(tr_selec).data('remessa-data'),
				tipo_remessa_selec	=	$(tr_selec).data('tipo'),
				tipo_remessa_ant	=	$(tr_selec).prev().data('tipo'),
				dias_diferenca		=	$('._dias-gp').val(),
				data_subtraida		=	moment(data_remessa_selec).subtract(dias_diferenca, 'days'),
				fora_intervalo		=	false	//indica se a data do talão está fora do intervalo máximo permitido
			;
			
			//se a remessa for do tipo normal
			if( tipo_remessa_selec === 1 && tipo_remessa_ant === 1 ) {
				
				$(tr_selec)
					.prevAll('tr')
					.each(function() {

						//se a data da remessa do talão corrente 
						//for menor do que a data do talão selecionado subtraída dos dias de intervalo permitido.
						if( ($(this).data('tipo') === 1) && (moment($(this).data('remessa-data')) < data_subtraida) ) {
							fora_intervalo = true;
							return false;
						}

					})
				;
				
			}
			
			if( fora_intervalo ) {
				habilitarBtnIniciar(false, 'Talão com data de remessa fora do intervalo permitido: '+dias_diferenca+' dias.');
			}
			
			return !fora_intervalo;
			
		}
		
		/**
		 * Verificar se os consumos foram produzidos.
		 * @returns {Boolean}
		 */
		function verificarConsumo() {
			
			var produzido				=	true,
				eh_componente			=	$(tr_selec).data('consumo-componente'),
				status_componentes		=	$(tr_selec).data('status-componentes'),
				status_materias_primas	=	$(tr_selec).data('status-materias-primas')
			;
			
			//Se o consumo do talão for componente
			if( eh_componente == 1 ) {
				
				//Os componentes do talão devem estar produzidos para que o talão seja iniciado.
				if( status_componentes != 2 ) {
					habilitarBtnIniciar(false, 'Talão com componentes não produzidos.');
					produzido = false;
				}
				
			}
			//se o consumo do talão for matéria-prima
			else if( status_materias_primas == 0 ) {
				
				habilitarBtnIniciar(false, 'Talão com matérias-primas não produzidas.');
				produzido = false;
				
			}
			
			return produzido;
			
		}
		
		/**
		 * Verificar se o talão está fora da sequência de UP de Origem.
		 */
		function verificarPu213() {
			
			var up_origem_selec		=	$(tr_selec).data('up-origem'),
				fora_sequencia_up	=	false	//indica se o talão está fora da sequência de UP de Origem
			;
			
			$(tr_selec)
				.prevAll()
				.each(function() {

					//Se a UP origem do talão corrente (laço) for igual à UP origem do talão selecionado
					//e o status do talão corrente (laço) for diferente de produzido (status-3).
					if( ($(this).data('up-origem') == up_origem_selec) && ($(this).data('status-programacao') != 3) ) {
						fora_sequencia_up = true;
						return false;
					}

				})
			;

			if( fora_sequencia_up ) {
				habilitarBtnIniciar(false, 'Talão está fora da sequência de UP de Origem.');
			}
			else {			
				habilitar(iniciar, pausar, finalizar, etiqueta);
			}

		}		
		
		function init() {
			
			//Caso seja selecionada a UP TODOS ou a opção TODOS do PERÍODO não for marcada, desabilita todas as ações.
			if ( $('._up_todos').val() == '1' || !$('#periodo-todos').is(':checked') ) {
				habilitar(false, false, false, false);
				$(tr_selec).attr('title', 'Todas as UPs e/ou um Período específico foi passado no filtro, não permitindo Iniciar o Talão.');
				return false;				
			}
			
			//Se estiver na aba de talões à produzir
			if ( $('#talao-produzir-tab').parent().hasClass('active') ) {

				var tipo_corrente			=	$(tr_selec).data('tipo'),
					tipo_anterior			=	$(tr_selec).prev().data('tipo'),				
					pu212					=	$('#_pu212').val(),
					pu213					=	$('#_pu213').val()	
				;

				if( !verificarIntervalo() )
					return false;
				if( !verificarConsumo() )
					return false;

				//Verifica se o usuário tem permissão para produzir na sequência de UP.
				if( pu213 == '1' )
					verificarPu213();

				// - Verifica se o usuário NÃO tem permissão para quebrar sequenciamento de talão;
				// - Quando o tipo da remessa do talão selecionado for NORMAL 
				//		e houver um talão antes com remessa NORMAL, o corrente não pode ser iniciado
				//		por questão de ordem de tempo.
				else if( pu212 == '0' && tipo_corrente == '1' && tipo_anterior == '1' )
					habilitarBtnIniciar(false, 'Operador não tem permissão para quebrar sequenciamento.');

				else
					habilitar(iniciar, pausar, finalizar, etiqueta);

			}
			else
				habilitar(iniciar, pausar, finalizar, etiqueta);
		}
		
		init();
	}
	
	/**
	 * Desabilita os filtros para programação.
	 * @param {boolean} habilitar
	 */
	function habilitarFiltro(habilitar) {
		
		if ( habilitar === true ) {
			
			$('#programacao-filtro')
				.removeClass('desabilitado')
			;
			
		}
		else {
			
			$('#programacao-filtro')
				.addClass('desabilitado')
			;
			
		}
										
	}
    
	/**
	 * Objeto para filtragem de Talão.
	 * @returns {_22020_L6.TalaoFiltrar}
	 */
    function TalaoFiltrar()
    {		
		this.evento		= evento;
		this.filtrar	= filtrar;
		
        /**
         * Filtrar talões
         * @param {element} div_table div que receberá tabela preenchida
         * @param {string} url caminho da chamada ajax
         */
        function dados(div_table,url)
        {				
			var dados = {
				retorno				: ['VIEW'],
				estabelecimento_id	: $('.estab').val(),
				gp_id				: $('._gp_id').val(),
				up_id				: $('._up_id').val(),
				up_todos			: $('._up_todos').val(),
				up_origem			: $('._up_origem_descricao').val(),
				estacao				: $('._estacao_id').val(),
				estacao_todos		: $('._estacao_todos').val(),
				remessa				: $('#remessa').val(),
				data_producao		: $('#data-destaque').find('.valor').text(),
				data_ini			: $('.filtro-periodo .data-ini').val(),
				data_fim			: $('.filtro-periodo .data-fim').val(),
				periodo_todos		: $('#periodo-todos').is(':checked'),
				_perfil_gp			: $('._perfil-gp').val().trim(),
				ver_pares			: $('._ver-pares-gp').val().trim(),
				turno				: $('#turno').val(),
				turno_hora_ini		: $('#turno').find(':selected').data('hora-ini'),
				turno_hora_fim		: $('#turno').find(':selected').data('hora-fim')
			};
						
			execAjax1(
				'POST',
				url,
				dados,
				function(resposta) {

					var scr				= new Scroll();
					var scroll_posicao	= scr.getX(div_table);
					var talao_comp		= new TalaoComposicao();

					//carregar tabela
					$(div_table)
						.html( resposta.VIEW ? resposta.VIEW : resposta )
					;

					ativarDatatable(div_table.children('table'));						
					ativarSelecLinhaRadio();						
					talao_comp.talaoSelecionado();

					scr.setX(div_table, scroll_posicao);

					//verificar se a tabela ativa é a de talões à produzir
					if ( div_table[0].id === 'talao-produzir' ) {

						marcarPosicaoTr();
						focarTrTalaoProduzir();

						if ( talao_em_andamento ) {

							$('#talao-produzir')
								.find('.dataTables_scrollBody')
								.find('table')
								.find('tbody')
								.find('tr.selected')
								.siblings()
								.attr('disabled', 'disabled')
								.removeAttr('tabindex')
							;

							habilitarBtnAcao(false, true, true);

						}
						
						/**
						 * Definir valores do totalizador de à produzir
						 */
						var qtd_projetada	= 0,
							tempo_previsto	= 0,	
							pares			= 0,
							totaliz			= $('.totalizador-produzir')
						;

						$(div_table)
						   .find('.dataTables_scrollBody table tbody tr')
						   .each(function() {

							   qtd_projetada	+= formataPadrao( ($(this).find('td.qtd').length > 0) ? $(this).find('td.qtd').text() : $(this).find('td.qtd-alternativa').text() );
							   tempo_previsto	+= formataPadrao( $(this).find('td.tempo-prev').text() );
							   pares			+= parseFloat( $(this).find('._pares').val() );

						   })
						;

						qtd_projetada	= qtd_projetada		> 0 ? number_format(qtd_projetada, 1, ',', '.')		: 0;
						tempo_previsto	= tempo_previsto	> 0 ? number_format(tempo_previsto, 1, ',', '.')	: 0;
						pares			= pares				> 0 ? number_format(pares, 0, ',', '.')				: 0;

						$(totaliz).find('.qtd'	).text( qtd_projetada   );
						$(totaliz).find('.pares').text( pares  );
						$(totaliz).find('.tempo').text( tempo_previsto  );

					}
					//verificar se a tabela ativa é a de talões produzidos
					else if ( div_table[0].id === 'talao-produzido' ) {
						
						/**
						 * Definir valores do totalizador produzido
						 */
						var qtd_projetada	= 0,
							qtd_produzida	= 0,
							tempo_previsto	= 0,
							tempo_realizado  = 0,
							pares			= 0,
							totaliz			= $('.totalizador-produzido')
						;
						
						$(div_table)
							.find('.dataTables_scrollBody table tbody tr')
							.each(function() {

								qtd_projetada	+= formataPadrao( ($(this).find('td.qtd').length > 0) ? $(this).find('td.qtd').text() : $(this).find('td.qtd-alternativa').text() );
								qtd_produzida	+= formataPadrao( $(this).find('td.qtd-produzida').text() );
								tempo_previsto	+= formataPadrao( $(this).find('td.tempo-prev').text() );
								tempo_realizado += formataPadrao( $(this).find('td.tempo-realiz').text() );
								pares			+= parseFloat( $(this).find('._pares').val() );

							})
						;

						qtd_projetada	= qtd_projetada		> 0 ? number_format(qtd_projetada, 1, ',', '.')		: 0;
						qtd_produzida	= qtd_produzida		> 0 ? number_format(qtd_produzida, 1, ',', '.')		: 0;
						tempo_previsto	= tempo_previsto	> 0 ? number_format(tempo_previsto, 1, ',', '.')	: 0;
						tempo_realizado = tempo_realizado	> 0 ? number_format(tempo_realizado, 1, ',', '.')	: 0;
						pares			= pares				> 0 ? number_format(pares, 0, ',', '.')				: 0;

						$(totaliz).find('.qtd'				).text( qtd_projetada   );
						$(totaliz).find('.qtd-produzida'	).text( qtd_produzida   );
						$(totaliz).find('.tempo'			).text( tempo_previsto  );
						$(totaliz).find('.tempo-producao'	).text( tempo_realizado );
						$(totaliz).find('.pares'			).text( pares			);
						
					}
					//verificar se a tabela ativa é a de totalizadores diários
					else if ( div_table[0].id === 'totalizador-diario' ) {
						
						$(div_table)
							.find('.dataTables_scrollBody')
							.find('table')
							.find('tbody')
							.find('tr')
							.each(function() {

								//marcar totalizador do dia corrente
								if( $(this).data('date') === moment().format("DD/MM") ) {
									$(this).addClass('featured');
								}

							})
						;
						
						//carregar gráfico
						TotalizadorDiarioGrafico.carregar(resposta.DADO);
							
					}
					else {							
						habilitarBtnAcao(false, false, false);
						habilitarBtnEditarQtd(false);
						habilitarBtnMateriaPrima(false);
						habilitarBtnDetalhe(false);
					}
					
					delete scr;
					delete scroll_posicao;
					delete talao_comp;
					delete div_table;

				},
				null,
				null,
				true
			);	
	
			delete dados;
        }
        
		/**
		 * Limpar tabelas com os dados do talão.
		 */
		function limparTabela() {

			$('#detalhe, #materia-prima, #historico, #defeito')
				.find('table')
				.find('tbody')
				.empty();
			;

			$('#tempo-realizado')
				.text(' - ')
			;
			$('#_tempo-realizado')
				.val('')
			;

		}

		/**
		 * Verificar se algum dos campos do filtro está vazio.
		 * @param {Boolean} verif_periodo Indicar se é necessária a verificação do período.
		 * @returns {Boolean}
		 */
		function verificarFiltroVazio(verif_periodo) {

			var ret			= true;
			verif_periodo	= (typeof verif_periodo === 'undefined') ? false : verif_periodo;
			

			if ( !$('select.estab').val() ) {
				showErro('Selecione um Estabelecimento.');
				ret = false;
			}
			else if ( $('.consulta_gp_grup .consulta-descricao').val() === '' ) {
				showErro('Campo GP vazio.');
				ret = false;
			}
			else if ( $('.consulta_perfil_up_group .consulta-descricao').val() === '' ) {
				showErro('Campo Perfil UP vazio.');
				ret = false;
			}
			else if ( $('.consulta_up_group .consulta-descricao').val() === '' ) {
				showErro('Campo UP vazio.');
				ret = false;
			}
			else if ( $('.consulta_estacao_group .consulta-descricao').val() === '' ) {
				showErro('Campo Estação vazio.');
				ret = false;
			}
			
			if ( verif_periodo ) {
				
				if ( $('.filtro-periodo .data-ini').val() === '' ) {
					showErro('Campo Data Inicial vazio.');
					$('#filtrar-toggle.collapsed').click();
					ret = false;
				}
				else if ( $('.filtro-periodo .data-fim').val() === '' ) {
					showErro('Campo Data Final vazio.');
					$('#filtrar-toggle.collapsed').click();
					ret = false;
				}
				
			}

			return ret;

		}

		/**
		 * Habilitar filtrar por todos os períodos.
		 * @param {Boolean} habilitar
		 */
		function habilitarPeriodoTodos(habilitar) {
			
			$('#periodo-todos')
				.attr('disabled', !habilitar)
			;			
			
		}
		
		/**
		 * Habilitar filtrar por turno.
		 * @param {Boolean} habilitar
		 */
		function habilitarTurno(habilitar) {
			
			$('#turno')
				.attr('disabled', !habilitar)
			;			
			
		}

		function produzir()
		{
			var div_table = $('#talao-produzir');
			var url = '/_22020/talaoProduzir';

			//esconder filtro
			$('#filtrar-toggle:not(.collapsed)').click();
			
			div_table
				.closest('.programacao')
				.alterClass('*-ativo', 'talao-produzir-ativo')
			;
			
			habilitarPeriodoTodos(true);
			habilitarTurno(false);
			limparTabela();

			dados(div_table,url);
		}

		function produzido()
		{
			var div_table = $('#talao-produzido');
			var url = '/_22020/talaoProduzido';

			//exibir filtro
			$('#filtrar-toggle.collapsed').click();
			
			div_table
				.closest('.programacao')
				.alterClass('*-ativo', 'talao-produzido-ativo')
			;
			
			habilitarPeriodoTodos(false);
			habilitarTurno(true);
			limparTabela();

			dados(div_table,url);
		}
		
		function totalizadorDiario()
		{
			var div_table = $('#totalizador-diario');
			var url = '/_22020/totalizadorDiario';

			//exibir filtro
			$('#filtrar-toggle.collapsed').click();
			
			div_table
				.closest('.programacao')
				.alterClass('*-ativo', 'totalizador-diario-ativo')
			;
			
			habilitarPeriodoTodos(false);
			habilitarTurno(true);
			
			div_table
				.find('table')
				.find('tbody')
				.empty()
			;
			
			if ( !verificarFiltroVazio(true) )
				return false;
			
			limparTabela();

			dados(div_table,url);
		}

		/**
		 * Ações ao filtrar Talão.
		 * Obs.: acionado pelo botão 'Filtrar'.
		 */
		function filtrar() {
			
			if ( !verificarFiltroVazio() ) 
				return false;

			if ( $('#talao-produzir-tab').parent().hasClass('active') )
				produzir();
			else if ( $('#talao-produzido-tab').parent().hasClass('active') )
				produzido();
			else
				totalizadorDiario();

			if ( !talao_em_andamento ) {

				//indicar que o talão não está iniciado
				$('.programacao').removeClass('iniciada');
			}
			
		}
		
		function evento() {

			$('.btn-filtrar')
				.click(function() {

					filtrar();
				})
			;

			$('#talao-produzir-tab')
				.click(function() {

					if ( !verificarFiltroVazio(false) )
						return false;

					produzir();
				})
			;

			$('#talao-produzido-tab')
				.click(function() {

					if ( !verificarFiltroVazio(false) )
						return false;

					new tempoProducao().tempoRealizadoEmAndamento(false);
					produzido();
				})
			;
			
			$('#totalizador-diario-tab')
				.click(function() {
					totalizadorDiario();
				})
			;

		}
    }
	
	/**
	 * Módulo para Gráfico do Totalizador Diário.
	 */
	var TotalizadorDiarioGrafico = (function() {
		
			var cores = [
		        'rgb(0, 0, 255)',
		        'rgb(90, 170, 224)',
		        'rgb(255, 185, 0)',
		        'rgb(0, 128, 0)',
		        'rgb(255, 0, 0)',
		        'rgb(0, 255, 0)',
		        'rgb(255, 0, 255)',
		        'rgb(255, 140, 0)',
		        'rgb(105, 105, 105)',
		        'rgb(147, 112, 219)',
		        'rgb(178, 34, 34)',
		        'rgb(176, 48, 96)',
		        'rgb(255, 105, 180)',
		        'rgb(112, 128, 144)',
		        'rgb(0, 0, 128)',
		        'rgb(100, 149, 237)',
		        'rgb(102, 205, 170)',
		        'rgb(184, 134, 11)',
		        'rgb(0, 206, 209)',
		        'rgb(90, 200, 90)',
		        'rgb(0, 100, 0)',
		        'rgb(176, 48, 96)',
		        'rgb(107, 142, 35)',
		        'rgb(189, 183, 107)',
		        'rgb(255, 215, 0)',
		        'rgb(184, 134, 11)',
		        'rgb(139, 69, 19)',
		        'rgb(70, 130, 180)',
		        'rgb(200, 100, 100)',
		        'rgb(255, 105, 180)',
		        'rgb(60, 179, 113)',
		        'rgb(47, 79, 79)',
		        
		    ];

			function construir(grafico,dado,colunas){
			    var chec = '';
			    
			    var dados_producao = dado;
			    var dados_linhas   = colunas;

		        function drawChart() {
		            
		            var columns_table = new google.visualization.DataTable();
					columns_table.addColumn('number', 'colIndex');
					columns_table.addColumn('string', 'Filtro');
					
					var initState = {selectedValues: []};
					
		            chec = '';
		            for (var i = 1; i < dado.getNumberOfColumns(); i++) {
						columns_table.addRow([i, dado.getColumnLabel(i)]);
		                chec = chec + '<li><input type="checkbox" id="chk'+i+'" class="val-grafico" value="'+i+'" checked><label class="label-grafico" for="chk'+i+'">'+dado.getColumnLabel(i)+'</label></li>';
					}
		            
		            for (var i = 1; i < dado.getNumberOfColumns(); i++) {
						initState.selectedValues.push(dado.getColumnLabel(i));
					}

		            var	column_filter		= new google.visualization.ControlWrapper({		// Criando o filtro.
							
							controlType		: 'CategoryFilter',
							containerId		: 'totalizador-diario-grafico-filter',
							dataTable		: columns_table,
							options			: {
								filterColumnLabel: 'Filtro',
								
								ui				: {
									allowTyping	: false,
									caption		: 'Filtrar por...',
									label		: ''
								}
							},
							state			: initState
							
						}),
								
						chart = new google.visualization.ChartWrapper({		// Criando o gráfico.
							
							chartType	: grafico,
							containerId	: 'totalizador-diario-grafico',
							dataTable	: dado,
							options		: {
								
								allowHtml: true,
								
								chartArea: {
									width	: '90%',
		                            height  : '90%'
								},
								
								crosshair: {
									trigger: 'both'
								},
								
								enableInteractivity: true,
								
								explorer: {
		                            actions: ['dragToZoom', 'rightClickToReset'],
		                            maxZoomIn: 0,
		                            maxZoomOut:0
		                        },

		                        fontSize:11,
								legend:'none',
		                        pointSize: 3
							}
						}),
		                cores_series = cores
					;
		            
		            function setChartView () {

						var linhas  = $('.val-grafico');
		                initState = {selectedValues: []};
		                
		                var marcadas = 0;
		                $.each( linhas, function( i, linha ) {
		                    var v = $(linha).val();
		                    if($(linha).prop('checked')){
		                        marcadas = 1;
		                    }   
		                });
		                if(marcadas == 0){$(linhas).prop('checked',true);};
		                
		                $.each( linhas, function( i, linha ) {
		                    var v = $(linha).val();
		                    if($(linha).prop('checked')){
		                        initState.selectedValues.push(dado.getColumnLabel(v-1));
		                    }   
		                });
		                
		                var state = column_filter.getState(),
							row,
							view = {
								columns: [0]
							}
						;
		                
		                $.each( linhas, function( i, linha ) {
		                    var v = $(linha).val();
		                    if($(linha).prop('checked')){
		                        row = columns_table.getFilteredRows([{column: 1, value: state.selectedValues[v-1]}])[0];
		                        view.columns.push(columns_table.getValue(row, 0));
		                    }  
		                });
		                
		                view.columns.sort(function (a, b) {
							return (a - b);
						});                

		                chart.getOptions().series = [];
		                $.each( linhas, function( i, linha ) {
		                    var v = $(linha).val();
		                    $(linha).parent().css('border-color',cores_series[v-1]);
		                    
		                    if($(linha).prop('checked')){
		                        chart.getOptions().series.push({color:cores_series[v-1]});
		                        
		                        var coluna = chart.getOptions().series[chart.getOptions().series.length - 1];
		                        coluna.pos = v;
		                    }  
		                    
		                });
		                
						chart.setView(view);
						chart.draw();
		                
					}
		            
		            $('.btn-screem-grafico').off('click');
		            $(document).on('click','.btn-screem-grafico', function(e) {
		                console.log('ok');
		                setTimeout(function(){
		                    setChartView();
		                },500);
		            });
		            
		            $('.val-grafico').off('change');
		            $(document).on('change','.val-grafico', function(e) {
		                setChartView();
		            });
		            
		            $('.label-grafico2').off('mouseenter');
		            $(document).on('mouseenter','.label-grafico', function(e) {
		                try {
		                    var coll = $(this).parent().find('.val-grafico').val();
		                    var colunas = chart.getOptions().series;
		                    var cont_desmarc = 0;
		                    var linhas  = $('.val-grafico');
		                    
		                    $.each( colunas, function( i, coluna ) {
		                        var v = coluna.pos;

		                        if(v == coll){
		                            coluna.lineDashStyle = [10, 2];
		                            chart.draw();
		                        }  

		                    });
		                    
		                }catch(err) {}
		            });
		            
		            $('.label-grafico').off('mouseout');
		            $(document).on('mouseout','.label-grafico', function(e) {
		                try {
		                    var coll = $(this).parent().find('.val-grafico').val();
		                    var colunas = chart.getOptions().series;
		                    var cont_desmarc = 0;
		                    var linhas  = $('.val-grafico');
		                    
		                    $.each( colunas, function( i, coluna ) {
		                        var v = coluna.pos;

		                        if(v == coll){
		                            coluna.lineDashStyle = [0, 0];
		                            chart.draw();
		                        }  

		                    });
		                    
		                }catch(err) {}
		            });

		            $('#totalizador-grafico-filter').html(chec);
					setChartView();
					column_filter.draw();
		            
		        }

			    drawChart();
			}
		
		function dados(dado_sql) {
			
			var dado = new google.visualization.DataTable();			
			
			var coluna = [];

			dado.addColumn('string', 'Data Remessa');
			dado.addColumn('number', 'Capac. Disponível');
			dado.addColumn('number', 'Tempo Programado');
			dado.addColumn('number', 'Qtd. Programada');
			dado.addColumn('number', 'Talão Programado');			
			dado.addColumn('number', 'Tempo Produzido');
			dado.addColumn('number', 'Qtd. Produzida');
			dado.addColumn('number', 'Talão Produzido');			
			dado.addColumn('number', 'Eficiência');

			coluna = [
				'Data Remessa',
				'Capac. Disponível',
				'Tempo Programado',
				'Qtd. Programada',
				'Talão Programado',			
				'Tempo Produzido',
				'Qtd. Produzida',
				'Talão Produzido',			
				'Eficiência'
			];
			
			console.log($('._ver-pares-gp').val().trim());

			//se o GP tiver permissão para ver pares
			if ( $('._ver-pares-gp').val().trim() === '1' ) {
				
				dado.addColumn('number', 'Par Programado');
				dado.addColumn('number', 'Par Produzido');

				coluna.push('Par Programado');
				coluna.push('Par Produzido');
				
				for ( var i = 0; i < (dado_sql.length); i++ ) {

					dado.addRows([[
						moment(dado_sql[i].REMESSA_DATA).format('DD/MM'),
						parseFloat(dado_sql[i].CAPACIDADE_DISPONIVEL),
						parseFloat(dado_sql[i].CARGA_PROGRAMADA),
						parseFloat(dado_sql[i].QUANTIDADE_CARGA_PROGRAMADA),
						parseFloat(dado_sql[i].QUANTIDADE_TALAO_PROGRAMADA),
						parseFloat(dado_sql[i].CARGA_UTILIZADA),
						parseFloat(dado_sql[i].QUANTIDADE_CARGA_UTILIZADA),
						parseFloat(dado_sql[i].QUANTIDADE_TALAO_UTILIZADA),
						parseFloat(dado_sql[i].EFICIENCIA),
						Math.round(parseFloat(dado_sql[i].QUANTIDADE_PARES_PROGRAMADA)),
						Math.round(parseFloat(dado_sql[i].QUANTIDADE_PARES_UTILIZADA))
					]]);

				}
			}
			else {
				
				for ( var i = 0; i < (dado_sql.length); i++ ) {

					dado.addRows([[
						moment(dado_sql[i].REMESSA_DATA).format('DD/MM'),
						parseFloat(dado_sql[i].CAPACIDADE_DISPONIVEL),
						parseFloat(dado_sql[i].CARGA_PROGRAMADA),
						parseFloat(dado_sql[i].QUANTIDADE_CARGA_PROGRAMADA),
						parseFloat(dado_sql[i].QUANTIDADE_TALAO_PROGRAMADA),
						parseFloat(dado_sql[i].CARGA_UTILIZADA),
						parseFloat(dado_sql[i].QUANTIDADE_CARGA_UTILIZADA),
						parseFloat(dado_sql[i].QUANTIDADE_TALAO_UTILIZADA),
						parseFloat(dado_sql[i].EFICIENCIA)
					]]);

				}
			}
			
			//construir(dado);
			construir('LineChart',dado,coluna);

			$(document).on('change','.select-tipo-grafico', function(e) {
		        var graf = $(this).val();
		        construir(graf,dado,coluna);
		    });
			
		}
		
		function carregar(dado_sql) {

			google.charts.load('current', {packages: ['corechart', 'controls', 'line'], 'language': 'pt-br'});
			google.charts.setOnLoadCallback(function() { dados(dado_sql); });

		}
		
		return {
			carregar	: carregar
		};
		
	})();
	
	/**
	 * Limpar valores da área de destaque.
	 */
	function infoDestaqueLimpar() {

		//colocar talão na área de destaque
		$('#remessa-talao-destaque span.valor')
			.text('-')
		;
		//colocar operador na área de destaque
		$('#operador span.valor')
			.text('-')
		;
		$('#_operador-id')
			.val('-')
		;
		//colocar up na área de destaque
		$('#up-destaque span.valor')
			.text('-')
		;
		//colocar estação na área de destaque
		$('#estacao-destaque span.valor')
			.text('-')
		;
		//colocar data na área de destaque
//				$('#data-destaque span.valor')
//					.text('-')
//				;

	}
	
	/**
	 * Recarregar tabelas menores
	 */
	function recarregarTabelaMenor() {

		$('.table-talao-produzir')
			.find('.selected')
			.click()
		;
	   
	}
	
	/**
	 * Definir informações do resumo da produção.
	 */
	function resumoProducao() {
		
		var table			=	( $('#talao-produzir-tab').parent().hasClass('active') ) 
									? $('.dataTables_scrollBody .table-talao-produzir') 
									: $('.dataTables_scrollBody .table-talao-produzido')
								;
		var tr_selec		=	$(table)
									.find('tbody')
									.find('tr.selected')
								;
		var modelo			=	$(tr_selec)
									.find('td.modelo')
									.text()
								;
		var qtd				=	$(tr_selec)
									.find('td.qtd')
									.text()
								;
		var qtd_altern		=	$(tr_selec)
									.find('td.qtd-alternativa')
									.text()
								;
		var status			=	$(tr_selec)
									.find('td.t-status')
									.attr('class')
								;
		
		//Modelo
		$('#modelo-resumo')
			.text(modelo)
		;
		
		//Quantidade
//		$('#qtd-resumo')
//			.text(qtd)
//		;
		
		//Quantidade Alternativa
//		$('#qtd-alternativa-resumo')
//			.text(qtd_altern)
//		;
		
		$('#qtd-resumo')
			.text( (qtd_altern !== '') ? qtd_altern : qtd )
		;
							
		//Status		
		var status_label = '';
		var ul_legenda	 = $('.legenda.talao').first();
				
		if( status.indexOf('0') > -1 )		{ status = '0'; status_label = $(ul_legenda).find('li:nth-child(1)').find('.texto-legenda').text(); }
		else if( status.indexOf('1') > -1 )	{ status = '1'; status_label = $(ul_legenda).find('li:nth-child(2)').find('.texto-legenda').text(); }
		else if( status.indexOf('2') > -1 )	{ status = '2'; status_label = $(ul_legenda).find('li:nth-child(3)').find('.texto-legenda').text(); }
		else if( status.indexOf('3') > -1 )	{ status = '3'; status_label = $(ul_legenda).find('li:nth-child(4)').find('.texto-legenda').text(); }
		else if( status.indexOf('6') > -1 )	{ status = '6'; status_label = $(ul_legenda).find('li:nth-child(5)').find('.texto-legenda').text(); }
		
		$('#status-producao, #status-icone-resumo')
		   .alterClass('status-*', 'status-'+status)
		   .find('.fa')
		   .alterClass('fa-circle-thin', 'fa-circle')
		;	   
	   
		$('#status-resumo')
			.text(status_label)
		;
		
		//OBS.: Tempo realizado é definido na função de cálculo do mesmo.
		
		//Detalhamento
		setTimeout(function() {		//timeout devido ao datatables
			
			var table_detalhe = $('#detalhe')
									.find('.dataTables_wrapper')
									.clone()
								;
			$('#detalhe-resumo')
				.html(table_detalhe)
			;
			
		}, 1000);
		
	}
	
	/**
	 * Exibir resumo da produção como um descanso de tela.
	 */
	function resumoDescansoTela() {
		
		var exibir_por_tempo	=	true,
			abrir_auto			=	true,
			time_wait			=	60000,
			timeout				=	setTimeout(function() {
										collapseResumo(true);
									}, time_wait);
		
		/**
		 * Evento collapse do resumo.
		 * @param {boolean} exibir
		 */
		function collapseResumo(exibir) {
		
			if (exibir_por_tempo) {
				$('#resumo-producao').collapse(exibir ? 'show' : 'hide');
			}
		}
		
		/**
		 * Exibir/esconder resumo.
		 */
		function exibir() {
			
			clearTimeout(timeout);
			timeout = null;

			if(!abrir_auto) {
				return false;
			}
			
			//não esconder se o botão para ativar tiver sido clicado
			if ( !exibir_por_tempo ) {
				return false;
			}
			//esconder ao passar mouse
			else if ( $('#resumo-producao').hasClass('in') ) {
				collapseResumo(false);
				return false;
			}
			else {
				timeout = setTimeout(function() {

					//no caso de clicar para esconder e o foco permanecer no botão
					if ( !$('#resumo-producao').hasClass('in') ) {
						//exibir
						collapseResumo(true);
						$('#filtrar-toggle').focus();
					}

				}, time_wait);
			}
				
		}
		
		/**
		 * Definir ação no botão de fechar o resumo de produção.
		 */
		function resumoFechar() {

			$('#fechar-resumo')
				.click(function() {
					exibir_por_tempo = true;
					collapseResumo(false);
				})
			;

		}

		$(document)
			.on('mousemove', function() {
				exibir();
			})
			.on('keydown', function() {
				exibir();
			})
			.on('click', '#status-producao', function() {
				exibir_por_tempo = $(this).hasClass('collapsed') ? true : false;
			})
			.on('switchChange.bootstrapSwitch', '.chk-switch', function(event, state) {
				abrir_auto = state ? true : false;
			})
		;
		
		resumoFechar();
		
	}
		
	/**
	 * Objeto com as funções para as ações (Iniciar, Pausar e Cancelar).
	 * @returns {_22020_L5.acoesTela}
	 */
    function acoesTela() 
    {
        
        this.iniciar   = iniciar;
        this.pausar    = pausar;
        this.finalizar = finalizar;
        
        /**
         * Variável que recebe os valores do talão
         * @type type
         */
        var dados = {};
		
        /**
         * Valores para validar o status para iniciar um talão
         * @type {json}
         */
		var status_parado = 
        {
            status_talao       : [1], // 1 - Em aberto
            status_programacao : [0,1] // 0 - Parado ; 1 - Iniciado/Parado
        };
		
        /**
         * Valores para validar o status para pausar um talão
         * @type {json}
         */
		var status_andamento =
        {
            status_talao       : [1], // 1 - Em aberto
            status_programacao : [2] // 2 - Em Andamento
        };
		                
        /**
         * Coleta ou atualiza da variável dados do talão selecionado
         * @returns {void}
         */
        var dadosTalao = function()
        {
            var talao_selecionado	= $('#talao-produzir').find('.selected');
            var estabelecimento_id	= $('.estab'       ).val();
            var gp_id				= $('._gp_id'      ).val();
            var up_id				= $('._up_id'      ).val();
            var estacao				= $('._estacao_id' ).val();
            var operador_id			= $('#_operador-id').val();
            var remessa_id			= talao_selecionado.find('._remessa-id'      ).val();
            var remessa_talao_id	= talao_selecionado.find('._remessa-talao-id').val();
            var talao_id			= talao_selecionado.find('._id'              ).val();
            var programacao_id		= talao_selecionado.find('._programacao-id'  ).val();
			var tempo_realizado		= $('#_tempo-realizado').val();
            
            dados = {
                estabelecimento_id	: estabelecimento_id,
                gp_id				: gp_id,
                up_id				: up_id,
                estacao				: estacao,
                operador_id			: operador_id,
                remessa_id			: remessa_id,
                remessa_talao_id	: remessa_talao_id,
                talao_id			: talao_id,
                programacao_id		: programacao_id,
				tempo_realizado		: tempo_realizado
            };
        };

        /**
         * Realiza o registro de inicio do talão selecionado
		 * @param {json} param
         * @returns {void}
         */
        var registraAcao = function (param)
        {
            dadosTalao();

            return new Promise(function(resolve, reject) {
                execAjax1('POST',param.rota_ajax,dados,
                function() {
                    resolve(true);
                },
                function() {
                    reject(false);
                });
            });
        };
        
        /**
		 * Realiza a validação do talão
		 * @param {json} param
		 * @returns {void}
		 */
        var validaTalao = function(param)
		{   
			//Realiza a coleta dos dados do talão selecionado
			dadosTalao();
			
			//Realiza a cópia do dados do talão selecionado (mantem o original inalterado)
			var dadosAjax = $.extend({}, dados);
			dadosAjax.status_talao       = param.status_talao;
			dadosAjax.status_programacao = param.status_programacao;

			return new Promise(
				
				function(resolve) { 
				
					execAjax1(
						'POST',
						'/_22020/talaoValido',
						dadosAjax,
						function() {
							resolve(true);
						},
						null,
						null,
						false
					);
                
				}, 
				function(error) {
					
					new TalaoFiltrar().filtrar();	//atualiza o status
					reject(false);
					
				}
			);
		};
        
		/**
		 * Recarregar o status do talão.
		 * Ao alterar o status do talão, ele deve ser atualizado na tela.
		 * @returns {Promise}
		 */
		var recarregarStatus = function() {
			
            return new Promise(function(resolve, reject) {
                
				execAjax1(
					'POST',
					'_22020/recarregarStatus',
					dados,
					function(resposta) {
						
						//procura o talão ativo na tabela
						var tr_selec	= $('#talao-produzir')
											.find('.dataTables_scrollBody')
											.find('table')
											.find('tbody')
											.find('tr.selected')
										;
										
						var td_status	= $(tr_selec).find('.t-status');
										
						//remover última classe
						$(td_status).removeClass(
								
							$(td_status)
								.attr('class')
								.trim()
								.split(' ')
								.pop()
								
						);
					
						//adiciona nova classe
						$(td_status)
							.addClass('status'+resposta.PROGRAMACAO_STATUS)
							.attr('title', resposta.PROGRAMACAO_STATUS_DESCRICAO)
						;
						
						//se o talão estiver em produção
						if (resposta.PROGRAMACAO_STATUS.trim() === '2') {
							
							$(tr_selec)
								.siblings()
								.attr('disabled', 'disabled')
								.removeAttr('tabindex')
							;
							
							//indicar que o talão está iniciado
							$('.programacao').addClass('iniciada');
							
							talao_em_andamento = true;
							
						}
						else {
							
							$(tr_selec)
								.siblings()
								.removeAttr('disabled')
								.attr('tabindex', '0')
							;
							
							//indicar que o talão não está iniciado
							$('.programacao').removeClass('iniciada');
							
							talao_em_andamento = false;
							
						}
						
						resumoProducao();
					
						resolve(true);
					},
					function() {
						reject(false);
					},
					null,
					false
				);
            });
			
        };
		
		/**
		 * Autenticar UP.
		 * @returns {Promise}
		 */
		var autenticarUp = function() {

			return new Promise(function(resolve, reject) {

				var modal		= $('#modal-autenticar-up');
				var input_barra = $('#up-barra');
				
				function consultar() {
					
					//ajax
					var type	= 'POST',
						url		= '/_22020/autenticarUp',
						data	= {
							up_barra		: $(input_barra).val(),
							up_selecionada	: $('._up_id').val()
						}
					;

					return execAjax1(type, url, data);
					
				}
				
				function autenticar() {
					
					$.when(consultar())
						.done(function() {
							$(modal).modal('hide');
							resolve(true);
						})
						.fail(function() {
							$(input_barra).val('').focus();
						})
					;
				}

				$(modal)
					.modal('show')
					.off('shown.bs.modal')
					.on('shown.bs.modal', function () {
						$(input_barra).focus();
					})
					.off('hidden.bs.modal')
					.on('hidden.bs.modal', function () {
						$(input_barra).val('');
					})
					.off('keydown', '#up-barra')
					.on('keydown', '#up-barra', 'return', function () {
						autenticar();
					})
					.off('click', '#btn-confirmar-up')
					.on('click', '#btn-confirmar-up', function () {
						autenticar();
					})
				;

			});

		};
		
		/**
		 * Iniciar talão.
		 * @returns {Promise}
		 */
        function iniciar()
        {
			/**
			 * Verifica se a Estação está ocupada (em produção).
			 * @returns {ajax}
			 */
            var verificaEstacaoOcupada = function()
            {
				
				/**
				 * Consulta o estado da Estação.
				 * @returns {ajax}
				 */
				var consultar = function()
                {
					var dados = {
						estabelecimento_id : $('.estab'      ).val(),
						up_id              : $('._up_id'     ).val(),
						estacao_id         : $('._estacao_id').val()
					};

					return execAjax1(
						'POST',
						'/_22020/verificarEstacaoAtiva',
						dados,
						null,
						null,
						null,
						false
					);			
				};
                
                return new Promise(function(resolve, reject) {
                    $.when( consultar() )
                        .done(function(resposta) {

                            //se a estação estiver em produção
                            if ( resposta[0]['EM_PRODUCAO'].trim() === "1" ) {

                                var talao_id		= (typeof resposta[0]['TALAO_ID'   ] == 'string' ) ? resposta[0]['TALAO_ID'   ].trim() : resposta[0]['TALAO_ID'   ];
                                var operador_id		= (typeof resposta[0]['OPERADOR_ID'] == 'string' ) ? resposta[0]['OPERADOR_ID'].trim() : resposta[0]['OPERADOR_ID'];
                                var operador_nome	= pegarPalavra(resposta[0]['OPERADOR_NOME'], 0, 2);
                                //var data_prod		= $.format.date( resposta[0]['DATAHORA'], 'dd/MM/yyyy' );

                                //procura o talão ativo na tabela
                                $('#talao-produzir')
                                    .find('.dataTables_scrollBody')
                                    .find('table')
                                    .find('tbody')
                                    .find('tr')
                                    .each(function() {

                                        var talao_id_tr = $(this).find('._id');

                                        if ( talao_id == $(talao_id_tr).val() ) {
											
											//colocar operador na área de destaque
											$('#operador span.valor')
												.text(operador_nome)
											;
											$('#_operador-id')
												.val(operador_id)
											;

                                            //habilitarFiltro(false);

                                            //foco no talão em produção
                                            location.hash = $(this).data('id');												
                                            focarTrTalaoProduzir();
											
											showAlert('A Estação selecionada está em produção com o operador '+ operador_nome +'. O Talão em andamento será Retomado.');
											
											resolve(true);
                                        }
                                        
                                    })
                                ;
                            }
							
							else {
								resolve(false);
							}

                        })
                    ;
                });
			};
            
			/**
			 * Definir valores da área de destaque.
			 */
			var infoDestaque = function()
            {
				
				var tr_selec	=	$('#talao-produzir')
										.find('.dataTables_scrollBody')
										.find('table')
										.find('tbody')
										.find('tr.selected')
									;
									
				var remessa		=	$(tr_selec)
										.find('td.remessa')
										.text()
									;
									
				var talao		=	$(tr_selec)
										.find('td.talao')
										.text()
									;
				
				var remessa_dest	= remessa.trim()+' - '+talao.trim();
				var up_dest			= $('._up_descricao').val();
				var estacao_dest	= $('._estacao_descricao').val();
				
				//colocar talão na área de destaque
				$('#remessa-talao-destaque span.valor')
					.text(remessa_dest)
					.attr('title', remessa_dest)
				;
				
				//colocar up na área de destaque
				$('#up-destaque span.valor')
					.text(up_dest)
					.attr('title', up_dest)
				;
				//colocar estação na área de destaque
				$('#estacao-destaque span.valor')
					.text(estacao_dest)
					.attr('title', estacao_dest)
				;
				//colocar data na área de destaque
//				$('#data-destaque span.valor')
//					.text('-')
//				;
				
			};
					
            /**
             * Retorno da função
             * @param {type} resolve
             * @param {type} reject
             * @returns {undefined}
             */
            return new Promise(function(resolve, reject)
            {
                verificaEstacaoOcupada()
                    .then(function(em_producao) {
                        
						//se estiver em produção, só autentica na função pausar
						if (em_producao === true) {
						
							//pausar
							pausar(em_producao)
								.then(function() {
							
									//iniciar (sem autenticar, pois a autenticação está em pausar)
									validaTalao(status_parado)
										.then(function() {
											
											//Registra o início
											registraAcao({rota_ajax: '/_22020/acao/iniciar'})
												.then(function(){

													new tempoProducao().tempoRealizadoEmAndamento(true);
													recarregarStatus();
													recarregarTabelaMenor();
													infoDestaque();												
													habilitarBtnAcao(false, true, true);
													//habilitarFiltro(false);
													habilitarBtnMateriaPrima(true);
													habilitarBtnDetalhe(true);
													habilitarBtnEditarQtd(true);
													resolve(true);

												}, 
												function(error) {
													reject(false);
												})
											;
				
										})
									;
									
								})
							;
						
						}                        
						else {

							//iniciar
							validaTalao(status_parado)
								.then(function() {
									
									//dados da autenticação
									var dados_autenticacao = {

										modal_show		: true,
										verificar_up	: $('._up_id').val(),
										success			: function() {

											//Registra o início
											registraAcao({rota_ajax: '/_22020/acao/iniciar'})
												.then(function(){

													new tempoProducao().tempoRealizadoEmAndamento(true);
													recarregarStatus();
													recarregarTabelaMenor();
													infoDestaque();												
													habilitarBtnAcao(false, true, true);
													//habilitarFiltro(false);
													habilitarBtnMateriaPrima(true);
													habilitarBtnDetalhe(true);
													habilitarBtnEditarQtd(true);

													resolve(true);

												}, 
												function(error) {
													reject(false);
												})
											;

										}
									};
									
									//variável para verificar se o talão selecionado está parado
									var talao_pausado	=	$('.table-talao-produzir')
																.find('tbody')
																.find('tr.selected')
																.find('.status1')
																.length
															;
									
									//se o talão está pausado
									if( talao_pausado == 1 ) {
										
										//verifica permissão ps227
										if( $('#ps227').val() == '1' ) {
											
											//autenticar UP
											autenticarUp()
												.then(function() {

													//Autenticar operador
													autenticacao(dados_autenticacao);

												})
											;
											
										}
										else {
											
											//Autenticar operador
											autenticacao(dados_autenticacao);
											
										}
										
									}
									else {
										
										//autenticar UP
										autenticarUp()
											.then(function() {

												//Autenticar operador
												autenticacao(dados_autenticacao);

											})
										;
										
									}
									
								})
							;

						}
                    });					
            });			
        }
        
		/**
		 * Pausar talão.
		 * @param {boolean} em_producao Identifica se o talão está em produção
		 * @returns {Promise}
		 */
        function pausar(em_producao)
        {
            return new Promise(function(resolve)
            {
                validaTalao(status_andamento)
                    .then(function() {

						var dados_autenticacao = {
							modal_show : true,
                            success    : function() 
                            {								
                                //Registra a pausa
                                registraAcao({rota_ajax: '/_22020/acao/pausar'})
                                    .then(function(){
										
										new tempoProducao().tempoRealizadoEmAndamento(false);
                                        habilitarBtnAcao(true, false, false);
										habilitarBtnEditarQtd(false);
										habilitarBtnMateriaPrima(false);
										habilitarBtnDetalhe(false);
                                        //habilitarFiltro(true);
										
										if (em_producao !== true) {
											recarregarStatus();
											recarregarTabelaMenor();
											infoDestaqueLimpar();
											showSuccess('Pausado com sucesso.');
										}
										
                                        resolve(true);
										
                                    })
                                ;                                
                            }
						};						
						
						//Se estiver em produção, a pausa será seguida de um início, 
						//sendo assim precisa verificar a UP.
						if( em_producao ) {
							
							dados_autenticacao.verificar_up = $('._up_id').val();
							
							//verifica permissão ps227
							if( $('#ps227').val() == '1' ) {
								
								//Autenticar UP
								autenticarUp()
									.then(function() {

										//Autenticar operador
										autenticacao(dados_autenticacao);

									})
								;
								
							}
							else {
								
								//Autenticar operador
								autenticacao(dados_autenticacao);
								
							}
							
						}
						else {
							
							//Autenticar operador
							autenticacao(dados_autenticacao);
							
						}
                    })
                ;
            });
        }
        
        /**
		 * Finalizar talão.
		 * @returns {Promise}
		 */
        function finalizar()
        {
            return new Promise(function(resolve,reject)
            {
                validaTalao(status_andamento)
                    .then(function() {
                        
                        //Autenticar operador
//                        autenticacao(
//                        {
//                            modal_show : true,
//                            success    : function() 
//                            {																
                                //Registra a finalização
                                registraAcao({rota_ajax: '/_22020/acao/finalizar'})
                                    .then(function(){
										
										new tempoProducao().tempoRealizadoEmAndamento(false);										
										talao_em_andamento = false;
                                        habilitarBtnAcao(true, false, false);
										habilitarBtnEditarQtd(false);
										habilitarBtnMateriaPrima(false);
										habilitarBtnDetalhe(false);
                                        //habilitarFiltro(true);
										
                                        var id                 = $('#talao-produzir').find('.selected').find('._id').val();
                                        var operador_id        = $('#_operador-id').val();
                                        var operador_descricao = $('#operador').find('.valor').text();
                                        
                                        var dados = {
                                            id                  : id,
                                            operador_id         : operador_id,
                                            operador_descricao  : operador_descricao
                                        };
                                        
										getEtiqueta(dados)
											.then(function(result){
												
												postprint(result);
												infoDestaqueLimpar();
											})
											.catch(function(){
												
												infoDestaqueLimpar();
											})
										;
                                        
										//indicar que o talão não está iniciado
                                        $('.programacao').removeClass('iniciada');
										$('#talao-produzir-tab').click();
										showSuccess('Finalizado com sucesso.');
										
                                        resolve(true);
                                    })
                                ;
                                
//                            }
//                        });
                    })
                ;
            });
        }
    }
    
    function getEtiqueta(dados)
    {
        return new Promise(function(resolve, reject)
        {	

        	var perfil_up          = dados.perfil_up          || $('._perfil_up_id').val();
            var id                 = dados.id                 || $('#talao-produzir').find('.selected').find('._id').val();
            var operador_id        = dados.operador_id        || $('#_operador-id').val();
            var operador_descricao = dados.operador_descricao || $('#operador').find('.valor').text();
            var ret                = dados.retorno;
            
            var remessa_id         = dados.remessa_id         || $('#talao-produzir').find('.selected').find('._remessa-id').val();
            var remessa_talao_id   = dados.remessa_talao_id   || $('#talao-produzir').find('.selected').find('._remessa-talao-id').val();
            
            var retorno = [];
            
            if(ret == 'PRODUCAO'){
                retorno = ['PRODUCAO'];
            }else{if(ret == 'SOBRA'){
                retorno = ['SOBRA'];
            }else{if((ret == 'PRODUCAO,SOBRA') || (ret == 'SOBRA,PRODUCAO')){
                retorno = ['PRODUCAO','SOBRA'];
            }else{
                retorno = ['PRODUCAO','SOBRA'];
            }}}
            
            retorno = ['PRODUCAO','SOBRA'];
            
            execAjax1('POST','/_22020/etiqueta',{
                id                 : id, 
                operador_id        : operador_id,
                operador_descricao : operador_descricao,
                retorno            : retorno,
                remessa_id         : remessa_id,
                remessa_talao_id   : remessa_talao_id,
                perfil_up 		   : perfil_up
            },
            function(result) {
                resolve(result);
            },
            function(xhr) {
                reject(xhr);
            });
        });
    }
    
    
	/**
	 * Ativar eventos para os botões de ações (Iniciar, Pausar e Cancelar).
	 */
	function ativarBtnAcao()
    {
    
		var acoes = new acoesTela();

		$('#pausar').on('click',function(e)
		{
			//Para a ação default do botão, se houver
			e.preventDefault();
			e.stopPropagation();

			acoes.pausar();
		});

		$('#iniciar').on('click',function(e)
		{
			//Para a ação default do botão, se houver
			e.preventDefault();
			e.stopPropagation();

			acoes.iniciar();
		});

		$('#finalizar').on('click',function(e)
		{
			//Para a ação default do botão, se houver
			e.preventDefault();
			e.stopPropagation();

			acoes.finalizar();
		});

		$('#etiqueta').on('click',function(e)
		{
			//Para a ação default do botão, se houver
			e.preventDefault();
			e.stopPropagation();
            
            var retorno = $(this).data('retorno');

            autenticacao(
            {
                operacao_id : 22,
                label       : false,
                modal_show  : true,
                success     : function(res) 
                {
                    getEtiqueta({
                        id                 : $('#talao-produzido').find('.selected').find('._id').val(),
                        remessa_id         : $('#talao-produzido').find('.selected').find('._remessa-id').val(),
                        remessa_talao_id   : $('#talao-produzido').find('.selected').find('._remessa-talao-id').val(),
                        operador_id        : res['OPERADOR_ID'],
                        operador_descricao : res['OPERADOR_NOME'],
                        retorno            : retorno
                    })
                        .then(function(result){
                            postprint(result);
                        })
                        .catch(function(){})
                    ;
                }
            });

		});
	}
    
    /**
     * Controles da projeção
     * @returns {undefined}
     */
    function acoesProjecao()
    {
        function registrar(param)
        {
            var controle = function()
            {                
                return new Promise(function(resolve, reject) {
					
                    requestPost(param)
                        .then(function(resposta)
                        {
							//se for registro de componente, verifica se tem algum item sem registro.
							if( param.modal.attr('id') === 'modal-registrar-componente' ) {
								
								//Atualiza as tabelas de composição do talão
								new TalaoComposicao().consulta()
									.then(function() {

										var td			= $('table.materia-prima').find('td.produto');														
										var td_qtd		= $(td).length;														
										var alocado_qtd = $(td).find('span.alocado-show').length;

										//o modal só deve esconder se todos os itens forem registrados
										if( (alocado_qtd > 0) && (td_qtd === alocado_qtd) ) {

											showSuccess('Componente registrado com sucesso.');
											
											param.modal
												.modal('hide')
											;

										}
										else {

											showSuccess('Componente registrado com sucesso.<br/>Ainda há componentes para serem registrados.');
											
											param.input
												.focus()
											;

										}										

									})
								;
								
							}
							else {
								
								new TalaoComposicao().consulta();
								
								param.modal
									.modal('hide')
								;
							}
							
							param.input
								.val('')
							;

							resolve(resposta);
							
                        })
                        .catch(function()
                        {
                            param.input
                                .val('')
                                .focus()
                            ;
                            
                            reject(false);
                        })
                    ; 
                });
            };
            
            return controle();
        }

        function projecaoVinculo()
        {
            this.excluir = excluir;
                        
            function excluir(id) {
                return new Promise(function(resolve){
                    requestPost({
                        rota_ajax : '/_22020/projecaoVinculoExcluir',
                        dados     : {id : id}
                    })
                    .then(function(){
                        resolve(true);
                    });  
                });
            }
        }

        function getDadosMateriaPrima()
        {
            var modal = $('#modal-registrar-materia');
            var input = $('#materia-barra'          );  
            
            var talao_selecionado   = $('#talao-produzir').find('.selected');
            var consumo_selecionado = $('#materia-prima' ).find('.selected');

            var remessa_id        = talao_selecionado.find  ('._remessa-id'      );
            var remessa_talao_id  = talao_selecionado.find  ('._remessa-talao-id');
            var talao_id          = talao_selecionado.find  ('._id'              );
            var consumo_id        = consumo_selecionado.find('._consumo-id'      );
            var produto_id        = consumo_selecionado.find('._produto-id'      );

            return {
                rota_ajax        : '/_22020/registrarMateriaPrima',
                modal            : modal,
                input            : input,
                dados            : {
                    codigo_barras    : input.val(),
                    remessa_id       : remessa_id.val(),
                    remessa_talao_id : remessa_talao_id.val(),
                    talao_id         : talao_id.val(),
                    consumo_id       : consumo_id.val(),
                    produto_id       : produto_id.val()
                }
            };
        }
        
        function getDadosComponente()
        {
            var modal = $('#modal-registrar-componente');
            var input = $('#componente-barra'          ); 
            
            var talao_selecionado   = $('#talao-produzir').find('.selected');

            var remessa_id        = talao_selecionado.find  ('._remessa-id'      );
            var remessa_talao_id  = talao_selecionado.find  ('._remessa-talao-id');
            var talao_id          = talao_selecionado.find  ('._id'              );

            return {
                rota_ajax        : '/_22020/registrarComponente',
                modal            : modal,
                input            : input,
                dados            : {
                    codigo_barras    : input.val(),
                    talao_id         : talao_id.val(),
                    remessa_id       : remessa_id.val(),
                    remessa_talao_id : remessa_talao_id.val()
                }
            };
        }
        
        function eventos()
        {            
            function materiaPrima()
            {
                $('#modal-registrar-materia')
                    .on('shown.bs.modal', function () {
                        $('#materia-barra')
                            .val('')
                            .focus()
                        ;  
                    })
					.off('keydown', '#materia-barra')
                    .on('keydown', '#materia-barra', 'return', function() {
                        registrar(getDadosMateriaPrima());
                    })
					.off('click', '#btn-confirmar-reg-materia')
                    .on('click', '#btn-confirmar-reg-materia', function() {
                        registrar(getDadosMateriaPrima());
                    })
                ;
            }
            
            function componente()
            {
                $('#modal-registrar-componente')
                    .on('shown.bs.modal', function () {
                        $('#componente-barra')
                            .val('')
                            .focus()
                        ;  
                    })
					.off('keydown', '#componente-barra')
                    .on('keydown', '#componente-barra', 'return', function() {
                        registrar(getDadosComponente());
                    })
					.off('click', '#btn-confirmar-reg-componente')
                    .on('click', '#btn-confirmar-reg-componente', function() {
                        registrar(getDadosComponente());
                    })
                ;
            }
            
            function projecaoVinculoAlocado() {
        
                $(document).off('click', '.alocado-excluir').on('click', '.alocado-excluir', function() {
                    var _this   = this;
                    var alocado = new projecaoVinculo;
                        alocado.excluir($(_this).data('talao-vinculo-id'))
                        .then(function(){
                            
                            var produzir_selecionado = $('#talao-produzir-tab').parent().hasClass('active');
                            var talao_atual;
                            
                            if ( produzir_selecionado ) {
                                talao_atual = $('#talao-produzir').find('.selected').click();
                            } else {
                                talao_atual = $('#talao-produzido').find('.selected').click();
                            }
                        })
                    ;
                });
                
            }
            
            function projecaoVinculoAproveitado() {
        
                $(document).off('click', '.aproveitado-excluir').on('click', '.aproveitado-excluir', function() {
                    var _this   = this;
                    var alocado = new projecaoVinculo;
                        alocado.excluir($(_this).data('talao-vinculo-id'))
                        .then(function(){
                            
                            var produzir_selecionado = $('#talao-produzir-tab').parent().hasClass('active');
                            var talao_atual;
                            
                            if ( produzir_selecionado ) {
                                talao_atual = $('#talao-produzir').find('.selected').click();
                            } else {
                                talao_atual = $('#talao-produzido').find('.selected').click();
                            }
                        })
                    ;
                });
                
            }
            
            projecaoVinculoAlocado();
            projecaoVinculoAproveitado();
            materiaPrima();
            componente();
        }
		
        eventos();
    }

    /**
     * Controles dos Talões Detalhado
     * @returns {undefined}
     */
    function acoesTalaoDetalhe()
    {
		/**
		 * Passa o valor do campo 'hidden' para o 'text' correspondente.
		 * @returns {element}
		 */
		$.fn.hiddenToText = function() {
			
			var id		=	$(this)
								 .prop('id')
								 .replace('_', '')
							 ;

			var valor	=	$(this)
								 .val()
							 ;

			$(this)
				.siblings('#'+id)
				.val(formataPadraoBr(valor));
			
			return $(this);
		};
		
        function modalAjaxControl(param)
        {
            var consulta = function()
            {     
                return new Promise(function(resolve, reject) {
                    execAjax1('POST',param.rota_ajax,param.dados,
                    function(resposta) {
                        resolve(resposta);
                    },
                    function(xhr){
                        reject(xhr);
                    });  
                });
            };
        
            var controle = function()
            {                
                return new Promise(function(resolve, reject) {
                    consulta()
                        .then(function(resposta)
                        {
                            param.modal
                                .modal('hide')
                            ;
                            
                            resolve(resposta);
                        })
                        .catch(function()
                        {
                            param.input
                                .val('')
                                .focus()
                            ;
                            
                            reject(false);
                        })
                    ; 
                });
            };
            
            return controle();
        }

        function getDadosBalanca(param)
        {
            var modal = $('#modal-registrar-balanca');
            var input = $('#balanca-barra'          );  

            var talao_selecionado   = $('#talao-produzir').find('.selected');

            var remessa_id        = talao_selecionado.find  ('._remessa-id'      );
            var remessa_talao_id  = talao_selecionado.find  ('._remessa-talao-id');
            var talao_id          = talao_selecionado.find  ('._id'              );

            return {
                rota_ajax        : '/_22020/registroPesagem',
                modal            : modal,
                input            : input,
                dados            : {
                    codigo_barras            : input.val(),
                    remessa_id               : remessa_id.val(),
                    remessa_talao_id         : remessa_talao_id.val(),
                    talao_id                 : talao_id.val(),
                    remessa_talao_detalhe_id : param.remessa_talao_detalhe_id,
                    produto_id               : param.produto_id,
                    peca_conjunto            : param.peca_conjunto
                }
            };
        }

        function objBalanca()
        {
            this.conectar = conectar;
            this.desconectar = desconectar;
            
            var time;
            
            function conectar()
            {
                console.log('conectou');
                $( ".gc-print-open-com" )
                    .trigger( "click" )
                ;
                
                time = setInterval(function(){ 
                    $( ".gc-print-set-config" )
                        .trigger( "click" )
                    ;
                },1000);
            }
            
            function desconectar()
            {
                
                ('desconectou');
                $( ".gc-print-close-com" )
                    .trigger( "click" )
                ;
                clearInterval(time);
            }
        }
        
        function objBalancaResult(result)
        {
            this.inserir   = inserir;
            this.atualizar = atualizar;
            
            var input_produto							= $('#balanca-produto');
            var _input_saldo_inicial					= $('#_balanca-saldo-inicial');
            var _input_peso_bruto						= $('#_balanca-peso-bruto');
            var _input_tara								= $('#_balanca-tara');
            var _input_saldo_final						= $('#_balanca-saldo-final');
            var _input_peso_baixar						= $('#_balanca-peso-baixar');
            var _input_rendimento						= $('#_balanca-rendimento');
            var _input_metragem_calculada				= $('#_balanca-metragem-calculada');
            var _input_metragem_projetada				= $('#_balanca-metragem-projetada');
            var _input_metragem_projetada_altern		= $('#_balanca-metragem-projetada-altern');
            var div_um									= $('#modal-balanca .input-group-addon.um');
            var div_um_altern							= $('#modal-balanca .input-group-addon.um-altern');
            
            function inserir()
            {
                var saldo_inicial				= parseFloat(result.SALDO);
                var peso_bruto					= parseFloat(_input_peso_bruto.val());
                var tara						= parseFloat(result.PESO_TARA);
                var saldo_final					= parseFloat( peso_bruto - tara );
                var peso_baixar					= saldo_inicial - saldo_final;
				var rendimento					= parseFloat(result.RENDIMENTO);
				var metragem_calculada			= peso_baixar * rendimento;
				//var qtd_projetada				= parseFloat( $('#modal-balanca').data('quantidade-projetada') );
				var metragem_projetada			= parseFloat( $('#modal-balanca').data('saldo-produzir') );
				var metragem_projetada_altern	= parseFloat( $('#modal-balanca').data('saldo-produzir-altern') );
				var um							= $('#modal-balanca').data('um');
				var um_altern					= $('#modal-balanca').data('um-altern');
                
                console.log(peso_baixar);
                
                input_produto						.val(result.PRODUTO_ID + ' - ' + result.PRODUTO_DESCRICAO);
                _input_saldo_inicial				.val(saldo_inicial		.toFixed(5)).hiddenToText();
                _input_tara							.val(tara				.toFixed(5)).hiddenToText();
                _input_saldo_final					.val(saldo_final		.toFixed(5)).hiddenToText();
                _input_peso_baixar					.val(peso_baixar		.toFixed(5)).hiddenToText();
                _input_rendimento					.val(rendimento			.toFixed(5)).hiddenToText();
                _input_metragem_calculada			.val(metragem_calculada	.toFixed(5)).hiddenToText();
                _input_metragem_projetada			.val(metragem_projetada .toFixed(5)).hiddenToText();
                _input_metragem_projetada_altern	.val(metragem_projetada_altern.toFixed(5)).hiddenToText();
				div_um								.text(um);
				div_um_altern						.text(um_altern);				
            }
            
            function atualizar()
            {   
                var peso_b = _input_peso_bruto.val();
                
                var saldo_inicial      = parseFloat(_input_saldo_inicial.val());
                var peso_bruto         = parseFloat( peso_b.replace(",", "."));
                var tara               = parseFloat(_input_tara.val());
                var saldo_final        = parseFloat( peso_bruto - tara );
                var peso_baixar        = saldo_inicial - saldo_final;
				var rendimento		   = parseFloat(_input_rendimento.val());
				var metragem_calculada = peso_baixar * rendimento;

                _input_saldo_final.val(saldo_final.toFixed(5)).hiddenToText();
                _input_peso_baixar.val(peso_baixar.toFixed(5)).hiddenToText();
				_input_metragem_calculada.val(metragem_calculada.toFixed(5)).hiddenToText();                
            }
        }
		      
		function eventos()
        {
            var balanca        = new objBalanca();
                        
            function eventoBalanca()
            {
                $('.btn-balanca')
                    .on('click',function () {
						
                        $('.peso-bruto').val('0.00');
//                        balanca.conectar();
						
						var tr						= $(this).closest('tr');
                        var talao_id				= tr.find('._talao-id').val();
                        var produto_id				= tr.find('._produto-id').val();
						var qtd_projetada			= $(tr).find('._quantidade-projetada').val();
						var saldo_produzir			= $(tr).find('._saldo-produzir').val();
						var saldo_produzir_altern	= $(tr).find('._saldo-produzir-altern').val();
						var um						= $(tr).find('._um').val();
						var um_altern				= $(tr).find('._um-alternativa').val();
						var peca_conjunto   		= $(tr).find('._peca-conjunto').val();
                        
						$('#modal-registrar-balanca, #modal-balanca')
							.data('remessa-talao-detalhe-id'	, talao_id)
							.data('produto-id'					, produto_id)
							.data('quantidade-projetada'		, qtd_projetada)
							.data('saldo-produzir'				, saldo_produzir)
							.data('saldo-produzir-altern'		, saldo_produzir_altern)
							.data('um'							, um)
							.data('um-altern'					, um_altern)
							.data('peca-conjunto'				, peca_conjunto)
						;

                    })
                ;
                                
                $('#modal-balanca')
                    .on('hidden.bs.modal', function () {
                        balanca.desconectar();
                        $(this).removeClass('show-conjunto-2');
						$('#balanca-metragem-baixar'    ).val('');
						$('#balanca-metragem-baixar-2'  ).val('');
						$('#_remessa-talao-detalhe-id-2').val('');
                        $('#balanca-barra'              ).val('');
                    })
                ;
				
                $('#modal-balanca')
					.on('shown.bs.modal', function () {
						$('#balanca-metragem-baixar').focus();
					})
				;
				
                $('#modal-registrar-balanca')
                    .on('shown.bs.modal', function () {
						$('#balanca-barra')
                            .focus()
                        ;
                    })
                    .off('keydown')
                    .on('keydown', '#balanca-barra', 'return', function()	//repetido no click
                    {						
                        modalAjaxControl(getDadosBalanca({
                            remessa_talao_detalhe_id : $('#modal-registrar-balanca').data('remessa-talao-detalhe-id'),
                            produto_id               : $('#modal-registrar-balanca').data('produto-id'),
                            peca_conjunto            : $('#modal-registrar-balanca').data('peca-conjunto')
                        }))
                            .then(function(result){
                                
                                if (typeof result.PECA_CONJUNTO !== 'undefined') {
                                    $('#modal-balanca').addClass('show-conjunto-2');
                            
                                    var qtd       = parseFloat(result.PECA_CONJUNTO.QUANTIDADE);
                                    var qtd_prod  = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_PRODUCAO_TMP);
                                    var qtd_aprov = parseFloat(result.PECA_CONJUNTO.APROVEITAMENTO_ALOCADO);
                                    var qtd_saldo = qtd - qtd_prod - qtd_aprov;
                            
                                    var qtd_alt       = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_ALTERN);
                                    var qtd_prod_alt  = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_ALTERN_PRODUCAO_TMP);
                                    var qtd_aprov_alt = parseFloat(result.PECA_CONJUNTO.APROVEITAMENTO_ALOCADO_ALTERN);
                                    var qtd_saldo_alt = qtd_alt - qtd_prod_alt - qtd_aprov_alt;
                            
                                    $('#_remessa-talao-detalhe-id-2'         ).val(result.PECA_CONJUNTO.ID);
                                    $('#_balanca-metragem-projetada-2'       ).val(qtd_saldo.toFixed(5)    ).hiddenToText();
                                    $('#_balanca-metragem-projetada-altern-2').val(qtd_saldo_alt.toFixed(5)).hiddenToText();
                                    $('.um-2'       ).text(result.PECA_CONJUNTO.UM);
                                    $('.um-altern-2').text(result.PECA_CONJUNTO.UM_ALTERNATIVA);
                                }

                                balanca.conectar();
                                
                                var balanca_result = new objBalancaResult(result);
                                    balanca_result.inserir();
                        
                                $('#modal-balanca').modal('show');
								
                            })
                        ;
                    })
					.off('click', '#btn-confirmar-reg-balanca')
                    .on('click', '#btn-confirmar-reg-balanca', function()	//repetido no enter
                    {						
                        modalAjaxControl(getDadosBalanca({
                            remessa_talao_detalhe_id : $('#modal-registrar-balanca').data('remessa-talao-detalhe-id'),
                            produto_id               : $('#modal-registrar-balanca').data('produto-id'),
                            peca_conjunto            : $('#modal-registrar-balanca').data('peca-conjunto')
                        }))
                            .then(function(result){      
                                
                                if (typeof result.PECA_CONJUNTO !== 'undefined') {
                                    $('#modal-balanca').addClass('show-conjunto-2');
                            
                                    var qtd       = parseFloat(result.PECA_CONJUNTO.QUANTIDADE);
                                    var qtd_prod  = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_PRODUCAO_TMP);
                                    var qtd_aprov = parseFloat(result.PECA_CONJUNTO.APROVEITAMENTO_ALOCADO);
                                    var qtd_saldo = qtd - qtd_prod - qtd_aprov;
                            
                                    var qtd_alt       = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_ALTERN);
                                    var qtd_prod_alt  = parseFloat(result.PECA_CONJUNTO.QUANTIDADE_ALTERN_PRODUCAO_TMP);
                                    var qtd_aprov_alt = parseFloat(result.PECA_CONJUNTO.APROVEITAMENTO_ALOCADO_ALTERN);
                                    var qtd_saldo_alt = qtd_alt - qtd_prod_alt - qtd_aprov_alt;
                            
                                    $('#_balanca-metragem-projetada-2'       ).val(qtd_saldo.toFixed(5)    ).hiddenToText();
                                    $('#_balanca-metragem-projetada-altern-2').val(qtd_saldo_alt.toFixed(5)).hiddenToText();
                                    $('.um-2'       ).text(result.PECA_CONJUNTO.UM);
                                    $('.um-altern-2').text(result.PECA_CONJUNTO.UM_ALTERNATIVA);
                                }
                                
                                balanca.conectar();
                                
                                var balanca_result = new objBalancaResult(result);
                                    balanca_result.inserir();
                        
                                $('#modal-balanca').modal('show');
								
                            })
                        ;
                    })
                ;

                $('.gc-print-recebe-peso')
                    .on('click',function(){
						
						$(this)
							.hiddenToText()
						;
						
                        var balanca_result = new objBalancaResult();
                            balanca_result.atualizar();
                    })
                ;
		
            }
            
            eventoBalanca();
        }
        
        eventos();
    }
    
	/**
	 * Ações para baixar quantidade pela balança.
	 */
	function baixarBalanca() {
	
		/**
		 * Passa o valor do campo 'hidden' para o 'td span' correspondente.
		 * @returns {element}
		 */
		$.fn.hiddenToTdSpan = function() {

			var classe	=	$(this)
								 .prop('class')
								 .replace('_', '')
							 ;

			var valor	=	$(this).val();

			if (classe === 'quantidade') classe = 'qtd';
			else if (classe === 'quantidade-alternativa') classe = 'qtd-alternativa';
			else if (classe === 'quantidade-alocada') classe = 'qtd-alocada';
			else if (classe === 'quantidade-alternativa-aloc') classe = 'qtd-alternativa-aloc';

			$(this)
				.siblings('.'+classe)
				.find('span.valor')
				.text(formataPadraoBr(valor))
				.nextAll('input')
				.val(valor)
			;

			return $(this);
		};

		function baixarQuantidadeProduzida(dados) {

			return new Promise(function(resolve, reject) {

				execAjax1(
					'POST',
					'/_22020/baixarQuantidadeProduzida', 
					dados,
					function(resposta) {
						resolve(resposta);
					},
					function(xhr){
						reject(xhr);
					}
				);

			});

		}

		function getDadosBaixarQuantidadeProduzida(tipo_baixa) {

			return new Promise(

				function(resolve) {

					var qtd_altern		= parseFloat( $('#balanca-metragem-baixar').val() ).toFixed(5);
					var qtd_altern_2	= parseFloat( $('#balanca-metragem-baixar-2').val() ).toFixed(5);
					
					if( ((isNaN(qtd_altern)) || (qtd_altern <= 0)) && ((isNaN(qtd_altern_2)) || (qtd_altern_2 <= 0)) ) {
						showAlert('Metragem à Baixar deve ser maior do que 0(zero).');
						reject(false);
						return false;
					}
					
					var qtd				= (tipo_baixa === 'parcial') 
											? parseFloat( $('#_balanca-peso-baixar').val() ).toFixed(5)
											: parseFloat( $('#_balanca-saldo-inicial').val() ).toFixed(5);

					var talao_id		   = $('#talao-produzir').find('tbody').find('tr.selected').find('._id').val();
					var talao_detalhe_id   = $('#modal-balanca').data('remessa-talao-detalhe-id');
					var talao_detalhe_id_2 = $('#_remessa-talao-detalhe-id-2').val();
					var this_detalhe	   = '';
					var this_materia_tr    = '';

					$('#detalhe')
						.find('table')
						.find('._talao-id')
						.each(function() {

							if ( $(this).val() === talao_detalhe_id ) {

								this_detalhe = $(this);
								return false;

							}

						})
					;

					$('#materia-prima')
						.find('table')
						.find('._talao-detalhe-id')
						.each(function() {

							if ( $(this).val() === talao_detalhe_id ) {

								this_materia_tr = $(this).parent('tr');
								return false;

							}

						})
					;
					

					//dados para realizar a baixa
					var dados = {

						RETORNO					 : 'AMBAS',
						QUANTIDADE				 : qtd,
						QUANTIDADE_ALTERNATIVA	 : qtd_altern,
						TALAO_ID				 : talao_id,
						TALAO_DETALHE_ID		 : talao_detalhe_id,	
						CONSUMO_ID				 : $(this_materia_tr).find('._consumo-id').val(),
						TIPO					 : 'R',
						TABELA_ID				 : $('#balanca-barra').val().replace('P', ''),
						PRODUTO_ID				 : $(this_materia_tr).find('._produto-id'       ).val(),
						TAMANHO					 : $(this_materia_tr).find('._tamanho'          ).val(),
						REMESSA_ID				 : $(this_detalhe).siblings('._remessa-id'      ).val(),
						REMESSA_TALAO_ID		 : $(this_detalhe).siblings('._remessa-talao-id').val()
					};
                    

                    if ( talao_detalhe_id_2 != '' && qtd_altern_2 > 0 ) {

                        var this_materia_tr_2;
                        
                        $('#materia-prima')
                            .find('table')
                            .find('._talao-detalhe-id')
                            .each(function() {

                                if ( $(this).val() === talao_detalhe_id_2 ) {

                                    this_materia_tr_2 = $(this).parent('tr');
                                    return false;

                                }

                            })
                        ;

                        dados.TALAO_DETALHE_ID_2       = talao_detalhe_id_2;
                        dados.CONSUMO_ID_2             = $(this_materia_tr_2).find('._consumo-id').val();
						dados.QUANTIDADE_ALTERNATIVA_2 = qtd_altern_2;
                    }

					baixarQuantidadeProduzida(dados)
						.then(function() {
							
							(tipo_baixa === 'parcial')
								? showSuccess('Baixa Parcial efetuada com sucesso.')
								: showSuccess('Baixa Total efetuada com sucesso.');
								
							$('#modal-balanca')
                                .modal('hide')
                            ;

							recarregarTabelaMenor();

							resolve(true);

						})
					;

				},

				function(error) {
					reject(false);
				}
			);

		}

		function evento() {

			$('#baixar-parcial')
				.click(function() {

					getDadosBaixarQuantidadeProduzida('parcial');

				})
			;

			$('#baixar-total')
				.click(function() {

					getDadosBaixarQuantidadeProduzida('total');

				})
			;

		}

		evento();
	}

	/**
	 * Registrar aproveitamento.
	 */
	function registrarAproveitamento() {
		
		function registrar(param)
        {
            var controle = function()
            {                
                return new Promise(function(resolve, reject) {
                    requestPost(param)
                        .then(function(resposta)
                        {
                            new TalaoComposicao().consulta(); // Atualiza tabelas de composição do talão
							
                            param.input
                                .val('')
                            ;

                            param.modal
                                .modal('hide')
                            ;
							
							showSuccess('Aproveitamento registrado com sucesso.');
                            
                            resolve(resposta);
                        })
                        .catch(function()
                        {
                            param.input
                                .val('')
                                .focus()
                            ;
                            
                            reject(false);
                        })
                    ; 
                });
            };
            
            return controle();
        }
		
		function getDado(qtd_proj)
        {
            var modal = $('#modal-registrar-aproveitamento');
            var input = $('#aproveitamento-barra');  
            
            var talao_selecionado   = $('#talao-produzir').find('.selected');
            var detalhe_selecionado = $('#detalhe').find('.selected');

            var talao_id	= talao_selecionado.find('._id').val();
			var detalhe_id  = detalhe_selecionado.find('._talao-id').val();
			
            return {
                rota_ajax        : '/_22020/registrarAproveitamento',
                modal            : modal,
                input            : input,
                dados            : {
                    CODIGO_BARRAS				: input.val(),
                    TALAO_ID					: talao_id,
					REMESSA_TALAO_DETALHE_ID	: detalhe_id,
					QUANTIDADE					: qtd_proj
                }
            };
        }
		
        
		function consultarPesagem(param)
        {
            var controle = function()
            {                
                return new Promise(function(resolve, reject) {
                    requestPost(param)
                        .then(function(resposta)
                        {        
							var qtd_a_produzir = $('.table-detalhe').find('.selected').find('._saldo-produzir').val();
                            
                            var aprov = parseFloat(resposta.SALDO);
                            var produ = parseFloat(qtd_a_produzir);
                            
                            if ( aprov > produ ) {
								
								addConfirme('<h4>Registro de Produção</h4>',
									' Aproveitamento com quantidade superior ao saldo a produzir.<br />'+
                                    ' Saldo do aproveitamento: <b>' + formataPadraoBr((parseFloat(resposta.SALDO)).toFixed(5)) + '</b><br />'+
                                    ' Saldo a produzir: <b>' + formataPadraoBr((parseFloat(qtd_a_produzir)).toFixed(5)) + '</b><br/>'+
                                    ' Quantidade a alocar: <input type="number" class="aproveitamento-qtd-alocar" step="1" value="'+(parseFloat(qtd_a_produzir)).toFixed(5)+'" style="inline-block; width: 103px;" >',
                                    [
                                        {desc:'Alocar Total'  ,class:'btn-success',ret:'2',hotkey:'alt+t',glyphicon:'glyphicon-circle-arrow-down'},
                                        {desc:'Alocar Parcial',class:'btn-warning',ret:'1',hotkey:'alt+p',glyphicon:'glyphicon-download'}
                                    ],
										[
											{ret:1,func:function(){
                                                
                                                var saldo_a_produzir = parseFloat($('.aproveitamento-qtd-alocar').val());
                                                
                                                if ( saldo_a_produzir > resposta.SALDO) {
                                                    showErro('Saldo a produzir maior que o saldo do aproveitamento. Operação cancelada.');
                                                    return false;
                                                }
                                                
                                                registrar(getDado(saldo_a_produzir));
											}},
											{ret:2,func:function(){
                                                registrar(getDado(resposta.SALDO));
											}}
										]     
									);
							}
							else {
								registrar(getDado(resposta.SALDO));
							}
							resolve(resposta);
                        })
                        .catch(function()
                        {
                            param.input
                                .val('')
                                .focus()
                            ;
                            
                            reject(false);
                        })
                    ; 
                });
            };
            
            return controle();
        }
		
		function getDadoPesagem()
        {			
			var modal = $('#modal-registrar-aproveitamento');
            var input = $('#aproveitamento-barra'          );  

            var talao_selecionado   = $('#talao-produzir').find('.selected');

            var remessa_id        = talao_selecionado.find  ('._remessa-id'      );
            var remessa_talao_id  = talao_selecionado.find  ('._remessa-talao-id');
            var talao_id          = talao_selecionado.find  ('._id'              );
			
			var detalhe_selecionado = $('#detalhe').find('.selected');
			var detalhe_id  = detalhe_selecionado.find('._talao-id').val();
			var produto_id  = detalhe_selecionado.find('._produto-id').val();

            return {
                rota_ajax        : '/_22020/registroPesagem',
                modal            : modal,
                input            : input,
                dados            : {
                    codigo_barras            : input.val(),
                    remessa_id               : remessa_id.val(),
                    remessa_talao_id         : remessa_talao_id.val(),
                    talao_id                 : talao_id.val(),
                    remessa_talao_detalhe_id : detalhe_id,
                    produto_id               : produto_id,
                    not_status               : true
                }
            };
        }

		function evento() {
			
			$('#modal-registrar-aproveitamento')
				.on('shown.bs.modal', function () {
					$('#aproveitamento-barra')
						.val('')
						.focus()
					;  
				})
				.off('keydown', '#aproveitamento-barra')
				.on('keydown', '#aproveitamento-barra', 'return', function() {
					consultarPesagem(getDadoPesagem())
						.then(function() {
							
						});
					//registrar(getDado());
				})
				.off('click', '#btn-confirmar-aproveitamento')
				.on('click', '#btn-confirmar-aproveitamento', function() {
					consultarPesagem(getDadoPesagem())
						.then(function() {
							
						});
					//registrar(getDado());
				})
			;
		}
		
		evento();
	}

	/**
	 * Habilitar para que as consultas para o filtro sejam 
	 * abertas uma após a outra selecionada.
	 */
	function abrirConsultaAutom() {
		
		$('select.estab')
			.change(function() {
					
				$('.consulta_gp_grup')
					.find('.btn-filtro-consulta')
					.click()
					.siblings('.consulta-descricao')
					.focus()
				;
				
			})
		;
		
		$('._gp_id')
			.change(function() {
				
				var elem = $(this);
		
				setTimeout(function() {
					
					if ( $(elem).val() !== '' ) {

						$('.consulta_perfil_up_group')
							.find('.btn-filtro-consulta')
							.click()
							.siblings('.consulta-descricao')
							.focus()
						;

					}
					
				}, 200);
				
			})
		;

		$('._perfil_up_id, ._perfil_up_todos')
			.change(function() {
				
				var elem = $(this);
		
				setTimeout(function() {
				
					if ( $(elem).val() !== '' ) {

						$('.consulta_up_group')
							.find('.btn-filtro-consulta')
							.click()
							.siblings('.consulta-descricao')
							.focus()
						;

					}
					
				}, 200);
				
			})
		;
		
		$('._up_id, ._up_todos')
			.change(function() {
				
				var elem = $(this);
		
				setTimeout(function() {
				
					if ( $(elem).val() !== '' ) {

						$('.consulta_estacao_group')
							.find('.btn-filtro-consulta')
							.click()
							.siblings('.consulta-descricao')
							.focus()
						;

					}
					
				}, 200);
				
			})
		;
		
	}
	
	/**
	 * Habilitar eventos e funções para edição de quantidade e quantidade alternativa em Detalhe.
	 */
	function editarQtdDetalhe() {

		/**
		 * Editar quantidade.
		 * 
		 * @param {button} btn
		 */
		function editar(btn) {
			
			$(btn)
				.parent()
				.addClass('editando')
			;
			
			$(btn)
				.parent()
				.children('span')
				.hide()
			;
			
			var input = '';
			var th_qtd = '';
			
			if ( $(btn).parent('td').hasClass('qtd') ) {
				input = 'input.qtd';
				th_qtd = 'th.qtd';
			}
			else {
				input = 'input.qtd-alternativa';
				th_qtd = 'th.qtd-alternativa';
			}
			
			$('#detalhe')
				.find(th_qtd)
				.addClass('editando')
			;
			
			$(btn)
				.parent()
				.find(input)
				.show()
				.select()
			;
			
			$(btn)
				.hide()
			;
			
			$(btn)
				.nextAll('.qtd-gravar')
				.show()
			;
			
			$(btn)
				.nextAll('.qtd-cancelar')
				.show()
			;

		}

		/**
		 * Cancelar edição de quantidade.
		 * 
		 * @param {button} btn
		 */
		function cancelar(btn) {
			
			$(btn)
				.parent()
				.removeClass('editando')
			;
			
			$(btn)
				.parent()
				.children('span')
				.show()
			;
			
			var input  = '';
			var th_qtd = '';
			
			if ( $(btn).parent('td').hasClass('qtd') ) {
				input  = 'input.qtd';
				th_qtd = 'th.qtd';
			} else {
				input  = 'input.qtd-alternativa';
				th_qtd = 'th.qtd-alternativa';
			}
			
			$('#detalhe')
				.find(th_qtd)
				.removeClass('editando')
			;
			
			$(btn)
				.parent()
				.find(input)
				.val( 
				
					$(btn)
						.parent()
						.children('span.valor')
						.val()
						
				)
				.hide()
			;
			
			$(btn)
				.show()
			;
			
			$(btn)
				.next('.qtd-gravar')
				.hide()
			;
			
			$(btn)
				.nextAll('.qtd-cancelar')
				.hide()
			;

		}

		/**
		 * Gravar quantidade.
		 * 
		 * @param {button} btn
		 */
		/*
		function gravar(btn) {

			var qtd					= '';
			var talao_detalhe_id	= $(btn).parent().nextAll('._talao-id').val();
			var input				= '';
			var url					= '/_22020/alterarQtdTalaoDetalhe';
			var retorno				= '';
			
			if ( $(btn).parent('td').hasClass('qtd') ) {
				input	= 'input.qtd';
				retorno = 'QUANTIDADE';
			}
			else {
				input	= 'input.qtd-alternativa';
				retorno = 'QUANTIDADE_ALTERNATIVA';
			}
			
			qtd	= $(btn).siblings(input).val();
			
			if ( $(btn).siblings(input).attr('max') > 0 ) {
				if ( isNaN(parseFloat(qtd)) || (parseFloat(qtd) === 0) ) {
					
					showAlert('Valor deve ser maior do que 0 e menor do que '+ formataPadraoBr( $(btn).siblings(input).attr('max') ));
					return false;
					
				}
			}

			execAjax1(
				'POST',
				url, 
				{ 
					retorno				: retorno,
					qtd					: qtd,
					talao_detalhe_id	: talao_detalhe_id
				},
				function(data) {

					$(btn)
						.siblings('span.valor')
						.empty()
						.text( formataPadraoBr(qtd) )
						.show()
					;
					
					var classe_qtd	= (input === 'input.qtd') ? '._quantidade' : '._quantidade-alternativa';	
					var btn_parent	= $(btn).parent();
					var input_qtd	= $(btn_parent).siblings(classe_qtd);
					
					$(input_qtd)
						.val(qtd)
					;
					
					var qtd_projetada_val  = $(btn_parent).siblings('._quantidade-projetada').val();
					var qtd_produzida_val  = $(input_qtd).val();					
					var aproveitamento_val = $(btn_parent).siblings('._quantidade-aproveitamento').val();
					
					var saldo = qtd_projetada_val - qtd_produzida_val - aproveitamento_val;
					
					$(btn_parent)
						.siblings('.saldo')
						.text( formataPadraoBr(saldo) )
					;
					
					$(btn)
						.siblings(input)
						.attr('max', qtd)
						.hide()
					;
					
					$(btn)
						.hide()
					;
					
					$(btn)
						.siblings('button.qtd-cancelar')
						.hide()
					;
					
					$(btn)
						.siblings('button.qtd-editar')
						.show()
					;
					
					$(btn_parent)
						.removeClass('editando')
					;

					showSuccess('Quantidade alterada com sucesso.');

				}
			);

		}
		*/
	   
		/**
		 * Gravar todas as quantidades.
		 */
		function gravarTodas(tipo) {

			var tr_selec		 = $('.table-talao-produzir').find('.selected');
			var remessa_id		 = $(tr_selec).find('._remessa-id').val();
			var remessa_talao_id = $(tr_selec).find('._remessa-talao-id').val();
			var url				 = '/_22020/alterarTodasQtdTalaoDetalhe';

			execAjax1(
				'POST',
				url, 
				{
					REMESSA_ID			: remessa_id,
					REMESSA_TALAO_ID	: remessa_talao_id,
                    TIPO                : tipo
				},
				function(data) {
                    
//                    validarMaterial(data);
                    validarRet(data);
                    
					$(tr_selec).click();	//atualizar tabelas
					showSuccess('Quantidade alterada com sucesso.');

				}
			);

		}
        
        function validarMaterial(data){
        
        ret = 0;
        
        var cont = 0;
        $.each(data, function(key, value){
                           
            $('._produto-id').each(function( index ) {

                var prod  =  $( this ).val();

                if(prod == data[key]['PRODUTO_ID']){
                    var obj			= $( this ).parent().find('.qtd-total');
                    var prd			= $( this ).parent().find('.produto').attr('title');
                    var sobra		= $( this ).parent().find('._sobra-material');
                    var consumo_id	= $( this ).parent().find('._consumo-id').val();                    
                    var txt			= $(obj).text();
                    
                    if(txt.length > 0){
                        arryTxt = txt.split(' ');
                        
                        arryPrd = prd.split(' - ');

                        if(arryTxt.length > 1){
                            $(obj).text(data[key]['CONSUMO']+' '+ arryTxt[1]);
                        }else{
                            $(obj).text(data[key]['CONSUMO']);   
                        }
                        
                        var p = prd;
                        if(arryPrd.length > 1){
                            p = arryPrd[1]; 
                        }
                       
                        if(data[key]['QUANTIDADE'] > 0){
                            
                            //if (sobra_tipo == 'M'){
                                addConfirme('Registro de Produção',
                                ' Foi calculado uma sobra de '+p+' de <input type="number" name="quantidade" class="qtd qtd-sobra-material"'+
                                ' step="1" value='+data[key]['QUANTIDADE']+' style="inline-block; width: 95px;" > ',
                                [
                                 {desc:'Registrar Sobra',class:'btn-success btn-confirm-sim' ,ret:'1' ,hotkey:'alt+b',glyphicon:'glyphicon-th-large'},
                                 {desc:'Baixar total',class:'btn-primary btn-confirm-sim' ,ret:'3' ,hotkey:'alt+r',glyphicon:'glyphicon-th'},
                                 obtn_cancelar
                                ],
                                [{ret:1,func:function(){
                                            
                                    $(sobra).val($('.qtd-sobra-material').attr('valor'));
                                    gravar_SobraMaterial(consumo_id,$('.qtd-sobra-material').attr('valor'));
                                    
                                }},{ret:2,func:function(){
                                    
                                    vs = 0;
                                    vt = 0;
                                    
                                    gravarTodas(0);
                                    
                                }},{ret:3,func:function(){
                                    
                                    $(sobra).val(0);
                                    gravar_SobraMaterial(consumo_id,0);
                                    
                                }}]);
                                
                                setTimeout(function(){
                                    $('.qtd-sobra-material').focus();
                                    $('.qtd-sobra-material').trigger('change');
                                    $('.qtd-sobra-material').select();  
                                },1000);
                            
                        }else{
                            
                            if(data[key]['QUANTIDADE'] < 0){
                                var qtmp = parseFloat(data[key]['QUANTIDADE']);
                                
                                addConfirme('Registro de Produção',
                                ' Foi calculado o uso maior do que o alocado de <input type="number" name="quantidade" class="qtd qtd-sobra-material"'+
                                ' step="1" value='+qtmp+' style="inline-block; width: 95px;" > de '+p+', Esta correto?',[obtn_sim,obtn_nao],
                                [{ret:1,func:function(){
                                    $(sobra).val($('.qtd-sobra-material').attr('valor'));
                                    
                                    gravar_SobraMaterial(consumo_id,$('.qtd-sobra-material').attr('valor'));
                                        
                                }},
                                {ret: 2, func: function () {
                                        
                                }}]);

                                $('.qtd-sobra-material').trigger('change');
                                $('.qtd-sobra-material').select();
                            }else{

                            }
                        }  
                        
                    }else{
                       if(ret == 0){  
                            $('.btn-filtrar').click();
                       }
                    }
                    
                }else{
                   if(ret == 0){
                   }
                }

            });
            
            cont++;
        });
        
    }

		/**
		 * Eventos dos botões e inputs.
		 */
		function evento() {
			
			$('.table-detalhe tbody')
				.find('td.qtd, td.qtd-alternativa')
				.on('click', '.qtd-editar', function() {
					editar( $(this) );
				})
				.on('click', '.qtd-gravar', function() {
					//gravar( $(this) );
                    gravar_quantidade_produzida( $(this) );
				})
				.on('click', '.qtd-cancelar', function() {
					cancelar( $(this).siblings('.qtd-editar') );
				})
				.on('keydown', 'input', 'return', function() {
					$(this).siblings('.qtd-gravar').click();
				})
				.on('keydown', 'input', 'esc', function() {
					cancelar( $(this).siblings('.qtd-editar') );
				})
			;
			
			$(document)
				.off('click', '#qtd-gravar-todos')
				.on('click', '#qtd-gravar-todos', function() {
					
					gravarTodas(1);
					
				})
			;
			
		}
		
		evento();

	}
	
	/**
	 * Habilitar eventos e funções para edição de quantidade alocada e quantidade alternativa em Matéria-prima.
	 */
	function editarQtdMateriaPrima() {

		/**
		 * Editar quantidade.
		 * 
		 * @param {button} btn
		 */
		function editar(btn) {
			
			$(btn)
				.parent()
				.addClass('editando')
			;
		
			$(btn)
				.parent()
				.children('span')
				.hide()
			;
			
			var input = '';
			if ( $(btn).parent('td').hasClass('qtd-alocada') ) 
				input = 'input.qtd-alocada';
			else
				input = 'input.qtd-alternativa-aloc';
			
			$(btn)
				.parent()
				.find(input)
				.show()
				.select()
			;
			
			$(btn)
				.hide()
			;
			
			$(btn)
				.nextAll('.qtd-gravar')
				.show()
			;
			
			$(btn)
				.nextAll('.qtd-cancelar')
				.show()
			;

		}

		/**
		 * Cancelar edição de quantidade.
		 * 
		 * @param {button} btn
		 */
		function cancelar(btn) {
			
			$(btn)
				.parent()
				.removeClass('editando')
			;
			
			$(btn)
				.parent()
				.children('span')
				.show()
			;
			
			var input = '';
			if ( $(btn).parent('td').hasClass('qtd-alocada') ) 
				input = 'input.qtd-alocada';
			else
				input = 'input.qtd-alternativa-aloc';
			
			$(btn)
				.parent()
				.find(input)
				.val( 
				
					$(btn)
						.parent()
						.children('span.valor')
						.val()
						
				)
				.hide()
			;
			
			$(btn)
				.show()
			;
			
			$(btn)
				.next('.qtd-gravar')
				.hide()
			;
			
			$(btn)
				.nextAll('.qtd-cancelar')
				.hide()
			;

		}

		/**
		 * Gravar quantidade.
		 * 
		 * @param {button} btn
		 */
		function gravar(btn) {

			var qtd			= '';
			var consumo_id	= $(btn).parent().nextAll('._consumo-id').val();
			var input		= '';
			var url			= '/_22020/alterarQtdAlocada';
			var retorno		= '';
			
			if ( $(btn).parent('td').hasClass('qtd-alocada') ) {
				input	= 'input.qtd-alocada';
				retorno = 'QUANTIDADE_ALOCADA';
			}
			else {
				input	= 'input.qtd-alternativa-aloc';
				retorno = 'QUANTIDADE_ALTERNATIVA_ALOCADA';
			}
			
			qtd	= $(btn).siblings(input).val();
			
			if ( isNaN(parseFloat(qtd)) || parseFloat(qtd) === 0 ) {
				showAlert('Valor deve ser maior do que 0 e menor do que '+ formataPadraoBr( $(btn).siblings(input).attr('max') ));
				return false;
			}

			execAjax1(
				'POST',
				url, 
				{ 
					retorno		: retorno,
					qtd			: qtd,
					consumo_id	: consumo_id
				},
				function(data) {

					$(btn)
						.siblings('span.valor')
						.empty()
						.text( formataPadraoBr(qtd) )
						.show()
					;
					
					$(btn)
						.siblings('span.um')
						.show()
					;
					
					var classe_qtd = (input === 'input.qtd-alocada') ? '._quantidade-alocada' : '._quantidade-alternativa-aloc';					
					$(btn)
						.parent()
						.siblings(classe_qtd)
						.val(qtd)
					;
					
					$(btn)
						.siblings(input)
						.attr('max', qtd)
						.hide()
					;
					
					$(btn)
						.hide()
					;
					
					$(btn)
						.siblings('button.qtd-cancelar')
						.hide()
					;
					
					$(btn)
						.siblings('button.qtd-editar')
						.show()
					;
					
					$(btn)
						.parent()
						.removeClass('editando')
					;
					
					new TalaoComposicao().consulta();

					showSuccess('Quantidade alterada com sucesso.');

				}
			);

		}

		/**
		 * Eventos dos botões e inputs.
		 */
		function evento() {
			
			function habilitarEdicaoQtd(tr, possui_qtd_altern) {
				
				$(tr)
					.children('td.qtd-alocada')
					.find('button.qtd-editar')
					.prop('disabled', !possui_qtd_altern)
				;
				
				$(tr)
					.children('td.qtd-alternativa-aloc')
					.find('button.qtd-editar')
					.prop('disabled', possui_qtd_altern)
				;
				
			}
			
//			$('table.materia-prima tbody tr')
//				.each(function() {
//					
//					var qtd_altern = $(this).find('._quantidade-alternativa-aloc');
//
//					if ( parseFloat( $(qtd_altern).val() ) === 0 ) {
//						habilitarEdicaoQtd( $(this), false );
//					}
//					else {
//						habilitarEdicaoQtd( $(this), true );
//					}
//			
//				})
//			;
			
			$('table.materia-prima tbody')
				.find('td.qtd-alocada, td.qtd-alternativa-aloc')
				.on('click', '.qtd-editar', function() {
					editar( $(this) );
                    //gravar_quantidade_produzida($(this));
				})
				.on('click', '.qtd-gravar', function() {
					gravar( $(this) );
				})
				.on('click', '.qtd-cancelar', function() {
					cancelar( $(this).siblings('.qtd-editar') );
				})
				.on('keydown', 'input', 'return', function() {
					$(this).siblings('.qtd-gravar').click();
				})
				.on('keydown', 'input', 'esc', function() {
					cancelar( $(this).siblings('.qtd-editar') );
				})
			;
			
		}
		
		evento();

	}

	/**
	 * Habilitar botões acima da tabela de Matéria-prima.
	 * @param {boolean} habilitar
	 */
	function habilitarBtnMateriaPrima(habilitar) {
				
		$('#registrar-componente')
			.prop('disabled', !habilitar)
		;
		
		//Botão de excluir itens alocados.
		$(document)
			.on('mouseenter', '.alocado-show', function() {
				$('.alocado-excluir').prop('disabled', !habilitar);
			})
		;
        
        //Botão de excluir itens DO APROVEITAMENTO.
		$(document)
			.on('mouseenter', '.alocado-show', function() {
				$('.aproveitado-excluir').prop('disabled', !habilitar);
			})
		;

		//Ao mudar o status do talão ou recarregar a tabela, o botão deve ficar desabilitado. 
		//Só habilita se clicar em um item.
		$('#registrar-materia-prima')
			.prop('disabled', true)
		;
		
		//Habilitar botão de Registrar Matéria-prima somente quando um item
		//da tabela de matéria-prima estiver selecionado.
		$('table.materia-prima tbody tr')
			.off()
			.click(function() {
					
				if ( habilitar === true ) {
					$('#registrar-materia-prima')
						.prop('disabled', false)
					;
				}
				
			})
		;
		
	}
	
	/**
	 * Habilitar botões acima da tabela de Detalhes.
	 * @param {boolean} habilitar
	 */
	function habilitarBtnDetalhe(habilitar) {
					
		//Ao mudar o status do talão ou recarregar a tabela, o botão deve ficar desabilitado. 
		//Só habilita se clicar em um item.
		$('#registrar-aproveitamento')
			.prop('disabled', true)
		;
		
		//Habilitar botão de Registrar Matéria-prima somente quando um item
		//da tabela de matéria-prima estiver selecionado.
		$('.table-detalhe tbody tr')
			.off()
			.click(function() {
					
				if ( habilitar === true ) {
					$('#registrar-aproveitamento')
						.prop('disabled', false)
					;
				}
				
			})
		;
		
	}
	
	/**
	 * Habilitar Botões de Editar Qtd nas tabelas de Detalhe e Matéria-prima.
	 * @param {boolean} habilitar
	 */
	function habilitarBtnEditarQtd(habilitar) {
		
		/**
		 * Habilitar botões de editar qtd na tabela de Detalhes.
		 * @param {element} tr
		 * @param {boolean} possui_qtd_altern
		 */
		function habilitarEdicaoQtdDetalhe(tr, possui_qtd_altern) {
				
			$(tr)
				.children('td.qtd-alternativa')
				.find('button.qtd-editar')
				.prop('disabled', !possui_qtd_altern)
			;

			$(tr)
				.children('td.qtd')
				.find('button.qtd-editar')
				.prop('disabled', possui_qtd_altern)
			;

		}
		
		if ( habilitar ) {

			//desabilitar botões de editar qtd na tabela de Detalhes
			$('.table-detalhe tbody tr')
				.each(function() {

					var qtd_altern = $(this).find('._quantidade-alternativa');
					
					//só habilita o botão de Editar Qtd quando a Qtd Alternativa for = 0
					if ( parseFloat( $(qtd_altern).val() ) === 0 ) {
						habilitarEdicaoQtdDetalhe( $(this), false );
					}
					//só habilita o botão de Editar Qtd Alternativa quando a Qtd Alternativa for > 0
					else {
						habilitarEdicaoQtdDetalhe( $(this), true );
					}

				})
				.find('.btn-balanca')
				.prop('disabled', false)
			;
			
			//habilitar os botões de editar qtd na tabela de Matéria-prima.
			$('table.materia-prima tbody tr')
				.find('.qtd-editar')
				.prop('disabled', false)
			;
			
			$('#qtd-gravar-todos')
				.prop('disabled', false)
			;
		}
		else {
			
			var tr_all = $('table tbody tr');
			
			//desabilitar todos os botões de editar qtd nas tabelas de Detalhes e Matéria-prima.
			$(tr_all)
				.find('.qtd-editar')
				.prop('disabled', true)
			;
			
			//desabilitar todos os botões de editar qtd (balança) na tabela de Detalhes.
			$(tr_all)
				.find('.btn-balanca')
				.prop('disabled', true)
			;
			
			$('#qtd-gravar-todos')
				.prop('disabled', true)
			;
		}
		
	}
		
	/**
	 * Objeto para cálculos de tempo de produção.
	 */
	function tempoProducao() {
		
		this.tempoRealizado				= tempoRealizado;
		this.tempoRealizadoEmAndamento	= tempoRealizadoEmAndamento;

		/**
		 * Calcular tempo realizado no Histórico de Produção.
		 * @param {div} div_table
		 * @param {boolean} em_acao Indica se está em alguma ação (pausar/finalizar)
		 */
		function tempoRealizado(div_table, em_acao) {

			var tempo_corrente	=	'';
			var tempo_proximo	=	'';
			var status_corrente	=	'';
			var diferenca		=	0;
			var tr				=	$(div_table)
										.find('.dataTables_scrollBody')
										.find('table')
										.find('tbody')
										.find('tr')
										.get()
										.reverse()
									;					
			var tr_length		=	$(tr).length;
			var tr_ultimo_i		=	tr_length - 1;
			var tr_penultimo_i	=	tr_length - 2;

			tempo_total			=	0;	//precisa ser reiniciado a cada chamada, pois a função 'tempoRealizadoEmAndamento' incrementa esse valor.

			$(tr)
				.each(function(i) {

					//último
					if( i === tr_ultimo_i ) {
						return false;
					}

					tempo_corrente	=	$(this)
											.find('td.data-historico')
											.data('datahora')
										;

					status_corrente	=	$(this)
											.find('td.status-historico')
											.data('status')
										;

					tempo_proximo	=	$(this)
											.prev()
											.find('td.data-historico')
											.data('datahora')
										;

					//se estiver em alguma ação (pausar/finalizar) e for o penúltimo item
					if( em_acao && (i === tr_penultimo_i) ) {

						//diferença entre tempos (ms)
						diferenca	=	moment()
											.diff(moment(tempo_proximo))
										;
					}
					else {

						//se o status for 'parado' ou 'finalizado'
						if( status_corrente == 1 || status_corrente == 2 ) {
							return;
						}

						//diferença entre tempos (ms)
						diferenca	=	moment(tempo_proximo)
											.diff(moment(tempo_corrente))
										;
					}

					tempo_total += diferenca;

				})
			;

			var tempo_total_obj = moment.duration(tempo_total);

			$('#_tempo-realizado')
				.val( tempo_total_obj.asMinutes().toFixed(2) )	//fração de minutos
			;

			$('#tempo-realizado, #tempo-realizado-resumo')
				.text( tempo_total_obj.format('mm[m] ss[s]') )
				//.text( tempo_total_obj.format('d[d] HH[h] mm[m] ss[s]') )
			;

		}

		/**
		 * Calcular tempo realizado do talão iniciado.
		 * @param {boolean} iniciar Iniciar(true) ou pausar(false) tempo.
		 */
		function tempoRealizadoEmAndamento(iniciar) {			
			
			if( iniciar ) {
				
				var ultima_data		=	$('#historico')
											.find('.dataTables_scrollBody')
											.find('tbody')
											.find('tr')
											.first()
											.find('td.data-historico')
											.data('datahora')
										;

				//diferença entre tempos (ms)
				var diferenca		 =	moment().diff(moment(ultima_data));
				tempo_total			+=	diferenca;
				var tempo_total_obj	 =	moment.duration(tempo_total);

				time_interval = setInterval(function() {

					tempo_total_obj = tempo_total_obj.add(1, 's');

					$('#_tempo-realizado')
						.val( tempo_total_obj.asMinutes().toFixed(2) )	//fração de minutos
					;

					$('#tempo-realizado, #tempo-realizado-resumo')
						.text( tempo_total_obj.format('mm[m] ss[s]') )
					;

				}, 1000);

			}
			else {

				clearInterval(time_interval);
				time_interval = null;

			}

		}
		
	}
	
	/**
	 * Objeto para manipulação de scroll.
	 */
	function Scroll() {
		
		this.getX = getX;
		this.setX = setX;
		
		function getX(tabela) {
			
			return	$(tabela)
						.find('.dataTables_scrollBody')
						.scrollLeft()
					;
		}
		
		function setX(tabela, scroll_posicao) {
			
			$(tabela)
				.find('.dataTables_scrollBody')
				.scrollLeft(scroll_posicao)
			;
		}
		
	}
	
	$(function() {
		
		ativarDatatable();
		fixDatatable();
		ativarSelecLinhaRadio();
		definirParam();
		verificarFiltro();
        new TalaoFiltrar().evento();
		ativarBtnAcao();
		abrirConsultaAutom();
        acoesProjecao();
		editarQtdDetalhe();
		editarQtdMateriaPrima();
		baixarBalanca();
		registrarAproveitamento();
		resumoDescansoTela();
		
	});
	
})(jQuery);
