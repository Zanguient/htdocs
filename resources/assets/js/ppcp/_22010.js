/* global table_default, requestPost, moment */

    var ngApp = angular.element('#AppCtrl').scope();
	
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
	 * Verificações.
	 */
	function verificarFiltro() {
		
		/**
		 * Ação ao excluir qualquer filtro.
		 */
		function acaoExcluir() {
			
			talao_em_andamento = false;
			
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
	function TalaoComposicaojQ() {
        

		this.consulta			= consulta;
//		this.talaoAngular	    = talaoAngular;
		this.talaoSelecionado	= talaoSelecionado;
		
		var produzir_selecionado	= true;	//verifica se a aba talão à produzir está selecionada
		
		
		/**
		 * Consulta os dados do talão.
		 */
		function consulta() {
			return new Promise(function(resolve) {
                var ngApp = angular.element('#AppCtrl').scope();
                
                ngApp.vm.TalaoComposicao.consultar();
                ngApp.$apply();
            
			});
		}
        

		/**
		 * Ações ao selecionar um talão.
		 */
		function talaoSelecionado() {
            
            
			$('#talao-produzido')
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


					consulta();
					resumoProducao();

				})
			;
		}

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
	 * Recarregar tabelas menores
	 */
	function recarregarTabelaMenor() {

        angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();

	}
	
	/**
	 * Definir informações do resumo da produção.
	 */
	function resumoProducao() {
		
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
            
            execAjax1('POST','/_22010/etiqueta',{
                id                 : id, 
                operador_id        : operador_id,
                operador_descricao : operador_descricao,
                retorno            : retorno,
                remessa_id         : remessa_id,
                remessa_talao_id   : remessa_talao_id,
                perfil_up 		   : perfil_up,
                reimpressao        : dados.reimpressao
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

		$('#etiqueta').on('click',function(e)
		{
			//Para a ação default do botão, se houver
			e.preventDefault();
			e.stopPropagation();

            if ( ! ($('#talao-produzido tr.selected').length > 0) ) {
                showErro('Selecione um talão');
                return false;
            }
            
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
                        retorno            : retorno,
                        reimpressao        : 1
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
								angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar()
									.then(function() {
                                        
                                        var consumos = angular.element('#AppCtrl').scope().vm.TalaoComposicao.DADOS.CONSUMO;
                                
                                        var componente_pendente = false;
                                        
                                        for ( var i in consumos ) {
                                            var consumo = consumos[i];
                                            
                                            if ( consumo.COMPONENTE == '1' ) {
                                                if ( consumo.QUANTIDADE_ALTERNATIVA > 0 && !(consumo.QUANTIDADE_ALTERNATIVA_ALOCADA >= consumo.QUANTIDADE_ALTERNATIVA) ) {
                                                    componente_pendente = true;
                                                    break;
                                                } else
                                                if ( !(consumo.QUANTIDADE_ALTERNATIVA > 0) && !(consumo.QUANTIDADE_ALOCADA >= consumo.QUANTIDADE) ) {
                                                    componente_pendente = true;
                                                    break;
                                                }
                                            }
                                            
                                        }
                                        
                                        if ( componente_pendente ) {
                                            showSuccess('Componente registrado com sucesso.<br/>Ainda há componentes para serem registrados.');
											param.input
												.focus()
											;
                                        } else {
											showSuccess('Componente registrado com sucesso.');
											
											param.modal
												.modal('hide')
											;
                                        }								

									})
								;
								
							}
							else {
								
								angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
								
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
                        rota_ajax : '/_22010/projecaoVinculoExcluir',
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
                rota_ajax        : '/_22010/registrarMateriaPrima',
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
                rota_ajax        : '/_22010/registrarComponente',
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
                                $('.popover').remove();
                                angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
//                                talao_atual = $('#talao-produzir').find('.selected').click();
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
                                angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
//                                talao_atual = $('#talao-produzir').find('.selected').click();
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
                rota_ajax        : '/_22010/registroPesagem',
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
            this.atualizarChange = atualizarChange;
            
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
                
                $('#balanca-peso-bruto').val('').prop('readonly',false);
            }
            
            function atualizar()
            {   
                $('#balanca-peso-bruto').val($('#_balanca-peso-bruto').val());
                var peso_b = $('#balanca-peso-bruto').val();
                
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
                
                if ( $('#_balanca-peso-bruto').val() == NaN ) {
                    $('#balanca-peso-bruto').val('').prop('readonly',false);
                } else {
                    $('#balanca-peso-bruto').prop('readonly',true);
                }
            }
            
            function atualizarChange()
            {   
                var peso_b = $('#balanca-peso-bruto').val();
                
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
                
                $('#balanca-peso-bruto').keydown(function(){
                    setTimeout(function(){
                        var balanca_result = new objBalancaResult();
                            balanca_result.atualizarChange();
                    });
                });

                var balanca_timeout = null;
                $('.gc-print-recebe-peso')
                    .on('click',function(){
						
                        clearTimeout( balanca_timeout );
                
                        balanca_timeout = setTimeout(function(){
                            $('#balanca-peso-bruto').val('').prop('readonly',false).trigger('keydown');
                        },3000);
                        
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
					'/_22010/baixarQuantidadeProduzida', 
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

                    var valor_bruto     = parseFloat( $('#balanca-peso-bruto').val().replace(",", ".") );
					var qtd_altern		= parseFloat( $('#balanca-metragem-baixar').val() ).toFixed(5);
					var qtd_altern_2	= parseFloat( $('#balanca-metragem-baixar-2').val() ).toFixed(5);
					
					if( isNaN(valor_bruto) ) {
						showAlert('Peso Bruto Atual deve ser do tipo númerico.');
						reject(false);
						return false;
					}
					
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
                        PECA_QUANTIDADE          : valor_bruto,
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
						REMESSA_TALAO_ID		 : $(this_detalhe).siblings('._remessa-talao-id').val(),
                        REGISTRO_AUTOMATICO      : $('#balanca-peso-bruto').prop('readonly') ? '1' : '0',
                        OPERADOR_NOME			 : $('#operador').find('.valor').attr('title'),
                        OPERADOR_ID              : $('#_operador-id').val() 	
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
						.then(function(response) {

							if(response.ETIQUETA != ''){
								postprint(response.ETIQUETA);
							}
							
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
                            angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar(); // Atualiza tabelas de composição do talão
							
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
                rota_ajax        : '/_22010/registrarAproveitamento',
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
                rota_ajax        : '/_22010/registroPesagem',
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
					
				}, 500);
				
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
					
				}, 500);
				
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
					
				}, 500);
				
			})
		;
		
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
 * Script com funções do obj _22010.
 */
(function($) {
    
	$(function() {
        
		definirParam();
		verificarFiltro();
		ativarBtnAcao();
		abrirConsultaAutom();
        acoesProjecao();
		baixarBalanca();
		registrarAproveitamento();
		resumoDescansoTela();
		
	});
	
})(jQuery);
