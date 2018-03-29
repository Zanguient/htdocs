/**
 * _25010 - Cadastro de Formulários
 */
;(function(angular) {

	/**
	 * Controller.
	 */
	var ctrl = function($ajax, $filter, $timeout) {

		var vm = this;

		vm.tipoTela						= "incluir";
		vm.permissao 					= {};
		vm.listaFormulario				= [];	// Lista de formulários cadastrados.
		vm.listaFormularioSelec			= [];	// Formulário selecionado.
		vm.listaFormularioShow			= [];	// Formulário a ser exibido.
		vm.tipoFormulario 				= [];	// Tipos de formulários.
		vm.tipoResposta					= [];	// Tipos de respostas.
		vm.nivelSatisfacao				= [];	// Níveis de satisfação das alternativas.
		vm.listaUsuario 				= [];	// Lista de usuários cadastrados.
		vm.listaUsuarioSelec 			= [];	// Usuários selecionados.
		vm.listaUsuarioSelecEscolhido 	= [];	// Usuários selecionados marcados.
		vm.listaCCusto	 				= [];	// Lista de centro de custos cadastrados.
		vm.listaCCustoSelec 			= [];	// Centro de custos selecionados.
		vm.listaCCustoSelecEscolhido 	= [];	// Centro de custos selecionados marcados.
		vm.todosCCustoSelecionado		= false;
		vm.listaDestinatarioExcluir		= [];	// Destinatários que serão excluídos ao alterar.

		vm.perguntaPadrao				= {
											ID 							: 0,
											ORDEM 						: 1,
											DESCRICAO 					: '',
											TIPO_RESPOSTA 				: 1,
											INDICADOR 					: '',
											TAG 						: '',
											ALTERNATIVA 				: [
												{ ID: 0, DESCRICAO: 'Sim', NOTA: '1', NIVEL_SATISFACAO: '1', JUSTIFICATIVA_OBRIGATORIA : 0 }, 
												{ ID: 0, DESCRICAO: 'Não', NOTA: '0', NIVEL_SATISFACAO: '2', JUSTIFICATIVA_OBRIGATORIA : 0 }
											],
											ALTERNATIVA_EXCLUIR			: [{}]
										  };

		vm.pergunta						= [];
		vm.perguntaExcluir				= [];

		vm.alternativaPadrao			= { ID: 0, DESCRICAO: 'Alternativa', NOTA: '1', NIVEL_SATISFACAO: '1', JUSTIFICATIVA_OBRIGATORIA : 0 };

		vm.formularioPadrao				= {
											ID 					: '',
											TIPO 				: 1,
											TITULO 				: '',
											DESCRICAO 			: '',
											PERIODO_INI			: moment().toDate(),
											PERIODO_FIM 		: moment().add(1, "month").toDate(),
											DESTINATARIO_TIPO	: 'usuario'
										  };

		vm.formulario					= {};

		vm.painel 						= [];
		vm.painelAlternativa 			= [];
		vm.painelTop 					= {};
		vm.painelFiltro 				= {
			DATA_INI_INPUT	: moment().subtract(3, 'months').toDate(),
			DATA_FIM_INPUT	: moment().toDate(),
			REPRESENTANTE 	: {CODIGO: null},
			UF				: {UF: null}
		};
		vm.listaRepresentante 			= [];

		vm.peso_soma 					= 0;
		vm.satisf_soma					= 0;
		vm.satisf_geral					= 0;
		vm.insatisf_geral				= 0;
		vm.satisf_usuario				= 0;
		vm.insatisf_usuario				= 0;
		vm.satisf_pergunta				= 0;
		vm.insatisf_pergunta			= 0;

		vm.destinatarioResposta			= [];
		vm.filtroStatusDestinatario		= false;
		vm.urlTipoForm 					= 0;
		vm.filtro 						= {};

		vm.totalNota 					= 0;


		this.$onInit = function() {

			vm.urlTipoForm 		= parseInt(getURLParameter('tipoForm'));

			vm.filtro.STATUS	= '';
			vm.filtro.TIPO 		= vm.urlTipoForm ? vm.urlTipoForm : 1;
			vm.filtro.DATA_INI 	= moment().subtract(3, 'month').toDate();
			vm.filtro.DATA_FIM 	= moment().toDate();

			vm.formulario.TIPO 	= vm.urlTipoForm ? vm.urlTipoForm : 1;

			// Carregar dados padrões do gráfico.
			google.charts.load('current', {packages: ['corechart'], 'language': 'pt-br'}); // basta chamar 1 vez
		};


        /**
		 * Fix para vs-repeat: exibir a tabela completa.
		 */
		vm.fixVsRepeat = function() {

			$timeout(function(){
                $('.modal .scroll-table').scrollTop(0);
            }, 200);

		};




		/**
		 * INDEX
		 */

		/**
		 * Listar formulários.
		 */
		vm.listarFormulario = function() {

			var param = {
				STATUS  : vm.filtro.STATUS,
				TIPO 	: vm.filtro.TIPO,
				DATA_INI: vm.filtro.DATA_INI ? moment(vm.filtro.DATA_INI).format('DD.MM.YYYY') : null,
				DATA_FIM: vm.filtro.DATA_FIM ? moment(vm.filtro.DATA_FIM).format('DD.MM.YYYY') : null
			};
            
			$ajax
				.post('/_25010/listar',
					JSON.stringify(param), 
					{contentType: 'application/json'})
				.then(function(response) {

					vm.listaFormulario  = response;
					vm.permissao 		= response.PERMISSAO;
					formataCampoFormulario();
				})
			;

			function formataCampoFormulario() {

	            var frm = {};

	            for (var i in vm.listaFormulario.FORMULARIO) {

	                frm = vm.listaFormulario.FORMULARIO[i];

	                frm.DATAHORA_INSERT_HUMANIZE = moment(frm.DATAHORA_INSERT).format('DD/MM/YYYY');
	            }
	        }

		};
    
		vm.csv = function(tipo) {
            
			$ajax
				.post('/_25010/csv', {
					formulario_id 	: vm.painelTop.FORMULARIO_ID,
					data_ini 		: (vm.formulario.TIPO == 3) 	? moment(vm.painelFiltro.DATA_INI_INPUT).format('YYYY-MM-DD')+' 00:00:00' : null,
					data_fim 		: (vm.formulario.TIPO == 3) 	? moment(vm.painelFiltro.DATA_FIM_INPUT).format('YYYY-MM-DD')+' 23:59:59' : null,
					representante   : vm.painelFiltro.REPRESENTANTE ? vm.painelFiltro.REPRESENTANTE : null,
					uf 				: vm.painelFiltro.UF		  	? vm.painelFiltro.UF 		    : null
				})
				.then(function(response) {

					if(tipo == 1){
						exportToXls('export.xls', response);
					}else{
						exportToCsv('export.csv', response);
					}			

				})
			;

		};



		/**
		 * Atalhos para as linhas da lista de formulários.
		 */
		vm.atalhoFormulario = function($event, formulario) {

			// Exibir formulário ao pressionar 'enter'.
			if ( $event.keyCode == 13 )
				vm.exibirFormulario($event, formulario);

			// Selecionar formulário ao pressionar 'space'.
			else if ( $event.keyCode == 32 )
				vm.selecionarFormulario(formulario);

		};

		/**
		 * Selecionar formulários.
		 */
		vm.selecionarFormulario = function(formulario) {

			// Indica se deve selecionar a linha da tabela.
			if (formulario.selected == undefined || formulario.selected == false)
				formulario.selected = true;
			else
				formulario.selected = false;

			// Preencher model com a lista de formulários selecionados.
			var indexFormulario = vm.listaFormularioSelec.indexOf(formulario);

			if (indexFormulario > -1) {
				vm.listaFormularioSelec.splice(indexFormulario, 1);
			}
			else {
				vm.listaFormularioSelec.push(formulario);
			}

		};

		/**
		 * Excluir formulários selecionados.
		 */
		vm.excluirFormularioSelec = function(formulario) {

			addConfirme(
				'<h4>Confirmação</h4>',
            	'Confirma a exclusão dos formulários selecionados?',
            	[obtn_sim, obtn_nao],
            	[
                	{
                		ret: 1,
                		func: function() { 

                			var indexFormulario = -1,
								form 			= '',
								id 				= []	//ID's a serem enviados para o SQL.
							;

							//Excluir no JSON.
							for(var i in formulario) {

								form 		 	= formulario[i];
								indexFormulario = vm.listaFormulario.FORMULARIO.indexOf(form);

								if (indexFormulario > -1) {
									vm.listaFormulario.FORMULARIO.splice(indexFormulario, 1);
								}

								id.push(form.ID);

							}

							//Excluir na base de dados.
							var dados = {
								id: id
							};

							$ajax
								.post('/_25010/excluirFormulario', dados)
								.then(function(response) {

									showSuccess('Excluído com sucesso.');
									vm.listaFormularioSelec = [];

								})
							;

                		}
                	},
                	{
                		ret: 2,
                		func: function() {}
                	}
            	]
            );

		};


		/**
		 * Exibir formulário.
		 */
		vm.exibirFormulario = function($event, formulario) {

			// Não exibir caso o checkbox seja clicado.
			if ( $event.target.className == 'chk-selec-form' || $event.target.className == 'chk' ) {
				return false;
			}
			
			var destinatario 	= [],
				pergunta 		= []
			;

			vm.tipoTela 						= 'exibir';
			vm.formulario.ID 					= formulario.ID;
			vm.formulario.TIPO 					= parseInt(formulario.TIPO);
			vm.formulario.TITULO 				= formulario.TITULO;
			vm.formulario.DESCRICAO 			= formulario.DESCRICAO;
			vm.formulario.DATAHORA_INSERT		= moment(formulario.DATAHORA_INSERT).toDate();
			vm.formulario.PERIODO_INI			= moment(formulario.PERIODO_INI).toDate();
			vm.formulario.PERIODO_FIM			= moment(formulario.PERIODO_FIM).toDate();
			vm.formulario.DESTINATARIO_TIPO		= formulario.DESTINATARIO_TIPO.trim();

			// Destinatários.
			for (var i in vm.listaFormulario.DESTINATARIO) {

				destinatario = vm.listaFormulario.DESTINATARIO[i];

				if ( parseInt(destinatario.FORMULARIO_ID) == parseInt(formulario.ID) ) {

					if(vm.formulario.DESTINATARIO_TIPO == 'usuario') 
						vm.listaUsuarioSelec.push(destinatario);
					else
						vm.listaCCustoSelec.push(destinatario);
				}

			}

			vm.pergunta = [];

			// Perguntas.
			for (var j in vm.listaFormulario.PERGUNTA) {

				pergunta = vm.listaFormulario.PERGUNTA[j];
				pergunta.ALTERNATIVA = [];
				pergunta.ALTERNATIVA_EXCLUIR = [];

				if ( parseInt(pergunta.FORMULARIO_ID) == parseInt(formulario.ID) ) {

					// Alternativas.
					for (var k in vm.listaFormulario.ALTERNATIVA) {

						alternativa = vm.listaFormulario.ALTERNATIVA[k];

						if ( ( parseInt(alternativa.FORMULARIO_ID) == parseInt(formulario.ID) ) 
						  && ( parseInt(alternativa.FORMULARIO_PERGUNTA_ID) == parseInt(pergunta.ID) ) 
						) {
							pergunta.ALTERNATIVA.push(alternativa);
						}

					}

					vm.pergunta.push(pergunta);

				}

			}

			vm.somarNota();

			$('#modal-create .modal-body').scrollTop(0);
			$('#modal-create').modal('show');

		};

		/**
		 * Redefinir formulário.
		 */
		vm.redefinirFormulario = function() {

			angular.copy(vm.formularioPadrao, vm.formulario);
			vm.formulario.TIPO = vm.urlTipoForm ? vm.urlTipoForm : 1;

		};

		/**
		 * Limpar tela.
		 */
		vm.limparTela = function() {

			vm.formulario 		 		= {};
			vm.listaUsuarioSelec 		= [];
			vm.listaCCustoSelec  		= [];
			vm.pergunta 		 		= [];
			vm.todosCCustoSelecionado 	= false;
			vm.redefinirFormulario();
			vm.addPergunta();

		};

		/**
		 * Limpar painel.
		 */
		vm.limparPainel = function() {

			vm.painel = {};
			vm.destinatarioResposta = {};

			// Limpando gráficos.
			$('#grafico-qtd-respondida, #grafico-satisf-geral, #grafico-satisf-delfa, #grafico-satisf-usuario, #grafico-satisf-pergunta-*')
				.empty();

		};

		/**
		 * Ver painel.
		 */
		vm.verPainel = function(formulario) {

			vm.painel.DESTINATARIO 			= [];
			vm.painel.RESPOSTA				= [];
			vm.painel.PERGUNTA  			= [];
			vm.painel.PERGUNTA_DETALHADA	= [];
			vm.painel.ALTERNATIVA  			= [];

			vm.painelTop.FORMULARIO_ID 		= formulario[0].ID;
			vm.painelTop.FORMULARIO_TITULO	= formulario[0].TITULO;

			if (vm.formulario.TIPO != 3)
				vm.painelClimaESatisfacao();
		};

		vm.painelClimaESatisfacao = function() {

			//Consulta dados para o painel na base de dados.
			var dados = {
				formulario_id: vm.painelTop.FORMULARIO_ID
			};

			$ajax
				.post('/_25010/listarPainel', dados)
				.then(function(response) {

					var qtd 				= response.QTD,
						destinatario 		= response.DESTINATARIO,
						satisfacao_geral	= response.SATISFACAO_GERAL,
						satisfacao_pergunta	= response.SATISFACAO_PERGUNTA,
						pergunta 			= response.PERGUNTA,
						alternativa 		= response.ALTERNATIVA
					;

					// Quantidades.
					vm.painel.QTD_RESPOSTA_ESPERADA	= (qtd[0].QTD_RESPOSTA_ESPERADA_USUARIO > 0) ? qtd[0].QTD_RESPOSTA_ESPERADA_USUARIO : qtd[0].QTD_RESPOSTA_ESPERADA_COLAB;
					vm.painel.QTD_RESPONDIDA 		= (qtd[0].QTD_RESPOSTA_ESPERADA_USUARIO > 0) ? qtd[0].QTD_RESPONDIDA_USUARIO 		: qtd[0].QTD_RESPONDIDA_COLAB;


				 	// Perguntas.
				 	for (var i in pergunta) {

				 		vm.painel.PERGUNTA.push({ 

					 		FORMULARIO_PERGUNTA_ID	: pergunta[i].FORMULARIO_PERGUNTA_ID,
					 		PERGUNTA_ORDEM			: pergunta[i].PERGUNTA_ORDEM,
					 		PERGUNTA_DESCRICAO		: pergunta[i].PERGUNTA_DESCRICAO

					 	});

					}

					// Alternativas.
				 	for (var i in alternativa) {

					 	vm.painel.ALTERNATIVA.push({ 

					 		FORMULARIO_PERGUNTA_ID		: alternativa[i].FORMULARIO_PERGUNTA_ID,
					 		ALTERNATIVA_ID 				: alternativa[i].ALTERNATIVA_ID,
					 		ALTERNATIVA_DESCRICAO		: alternativa[i].ALTERNATIVA_DESCRICAO,
					 		ALTERNATIVA_QTD_ESCOLHIDA	: alternativa[i].ALTERNATIVA_QTD_ESCOLHIDA

					 	});

					}

					// Usuários que responderam.
					for (var i in destinatario) {
						
						vm.painel.DESTINATARIO.push({ 

					 		FORMULARIO_ID	: destinatario[i].FORMULARIO_ID,
					 		ID 				: destinatario[i].DESTINATARIO_ID,
					 		DESCRICAO 		: destinatario[i].DESTINATARIO_DESCRICAO,
					 		PESO 			: destinatario[i].DESTINATARIO_PESO, 
					 		STATUS_RESPOSTA : destinatario[i].DESTINATARIO_STATUS_RESPOSTA, 
					 		CCUSTO 			: destinatario[i].DESTINATARIO_CCUSTO

					 	});

					}

					vm.graficoQtdRespondida().carregar();
					vm.calcularSatisfacaoGeral(satisfacao_geral);

					$timeout(function() {
						vm.calcularSatisfacaoPorPergunta(satisfacao_pergunta);
					}, 2000);

				})
			;
		};

		vm.painelCliente = function() {

			vm.limparPainel();

			vm.painel.DESTINATARIO 			= [];
			vm.painel.RESPOSTA				= [];
			vm.painel.PERGUNTA  			= [];
			vm.painel.PERGUNTA_DETALHADA	= [];
			vm.painel.ALTERNATIVA  			= [];

			//Consulta dados para o painel na base de dados.
			var dados = {
				formulario_id 	: vm.painelTop.FORMULARIO_ID,
				data_ini 		: moment(vm.painelFiltro.DATA_INI_INPUT).format('YYYY-MM-DD')+' 00:00:00',
				data_fim 		: moment(vm.painelFiltro.DATA_FIM_INPUT).format('YYYY-MM-DD')+' 23:59:59',
				representante   : vm.painelFiltro.REPRESENTANTE ? vm.painelFiltro.REPRESENTANTE : null,
				uf 				: vm.painelFiltro.UF 			? vm.painelFiltro.UF 			: null
			};

			$ajax
				.post('/_25010/listarPainelCliente', dados)
				.then(function(response) {

					carregarResumo(response.RESUMO[0]);
					carregarDestinatario(response.DESTINATARIO);
					carregarPergunta(response.PERGUNTA);
					carregarAlternativa(response.ALTERNATIVA);

					$timeout(function() {
						vm.calcularSatisfacaoPorPergunta(response.SATISFACAO_PERGUNTA);
					}, 2000);
				});


			function carregarResumo(resumo) {

				var metaSatisf  = 10,
					percSatisf 	= 0;

				vm.painel.QTD_PESQUISA 		= parseFloat(resumo.QTD_PESQUISA);
				vm.painel.MEDIA_SATISFACAO  = parseFloat(resumo.MEDIA_SATISFACAO);
				vm.painel.MEDIA_DELFA 		= parseFloat(resumo.MEDIA_DELFA);

				// Calcular satisfação com a Delfa.
				percSatisf 			= (vm.painel.MEDIA_DELFA * 100) / metaSatisf;
				vm.satisf_delfa	 	= percSatisf;
				vm.insatisf_delfa 	= 100 - percSatisf;

				vm.graficoSatisfacaoDelfa().carregar();

				// Calcular satisfação geral.
				percSatisf 			= 0;
				percSatisf 			= (vm.painel.MEDIA_SATISFACAO * 100) / metaSatisf;
				vm.satisf_geral	 	= percSatisf;
				vm.insatisf_geral 	= 100 - percSatisf;

				vm.graficoSatisfacaoGeral().carregar();
			}

			function carregarDestinatario(destinatario) {

				// Usuários que responderam.
				for (var i in destinatario) {
					
					vm.painel.DESTINATARIO.push({ 

				 		FORMULARIO_ID	: destinatario[i].FORMULARIO_ID,
				 		ID 				: destinatario[i].CLIENTE_ID,
				 		DESCRICAO 		: destinatario[i].CLIENTE_RAZAOSOCIAL,
				 		UF 				: destinatario[i].CLIENTE_UF,
				 		SATISFACAO  	: destinatario[i].SATISFACAO,
				 		NOTA_DELFA  	: destinatario[i].NOTA_DELFA,
				 		OBSERVACAO_DELFA: destinatario[i].OBSERVACAO_DELFA

				 	});

				}
			}

			function carregarPergunta(pergunta) {

			 	for (var i in pergunta) {

			 		vm.painel.PERGUNTA.push({ 

				 		FORMULARIO_PERGUNTA_ID	: pergunta[i].FORMULARIO_PERGUNTA_ID,
				 		PERGUNTA_ORDEM			: pergunta[i].PERGUNTA_ORDEM,
				 		PERGUNTA_DESCRICAO		: pergunta[i].PERGUNTA_DESCRICAO

				 	});
				}
			}

			function carregarAlternativa(alternativa) {

				for (var i in alternativa) {

				 	vm.painel.ALTERNATIVA.push({ 

				 		FORMULARIO_PERGUNTA_ID		: alternativa[i].FORMULARIO_PERGUNTA_ID,
				 		ALTERNATIVA_ID 				: alternativa[i].ALTERNATIVA_ID,
				 		ALTERNATIVA_DESCRICAO		: alternativa[i].ALTERNATIVA_DESCRICAO,
				 		ALTERNATIVA_QTD_ESCOLHIDA	: alternativa[i].ALTERNATIVA_QTD_ESCOLHIDA

				 	});
				}
			}
		};

		//// Início filtro representante.

		vm.eventoAlterarRepresentante = function($event) {

	    	// enter
	    	if ($event.keyCode == 13)
	    		vm.alterarRepresentante();

	    	// backspace ou delete
	    	else if ($event.keyCode == 8 || $event.keyCode == 46)
	    		vm.limparRepresentanteSelecionado();
	    };

		vm.alterarRepresentante = function() {

	        vm.consultarRepresentante();
	        $('#modal-consultar-representante').modal('show');
	    };

	    vm.consultarRepresentante = function() {

	        $ajax
	            .post('/_12060/consultarRepresentante')
	            .then(function(response){

	                vm.listaRepresentante = response;

	                // Fix para vs-repeat.
	                $('.table-representante')
	                    .trigger('resize')
	                    .scrollTop(0);

	                // Foco no input de filtrar.
	                $('.input-filtrar-representante').select();

	            });
	    };

	    vm.selecionarRepresentante = function(representante) {

            vm.painelFiltro.REPRESENTANTE = representante;
            $('.js-input-filtrar-representante').focus();

	        vm.fecharModalConsultarRepresentante();
	    };

	    vm.limparRepresentanteSelecionado = function() {

	        vm.painelFiltro.REPRESENTANTE = {CODIGO: null};
	    };

	    /**
	     * Fix para vs-repeat: exibir a tabela completa.
	     */
	    vm.fixVsRepeatConsultarRepresentante = function() {

	        $timeout(function(){
	            $('#modal-consultar-representante .table-representante').scrollTop(0);
	        }, 200);
	    };

	    vm.fecharModalConsultarRepresentante = function() {
	        
	        $('#modal-consultar-representante')
	            .modal('hide')
	            .find('.modal-body')
	            .animate({ scrollTop: 0 }, 'fast');

	        vm.filtrarRepresentante = '';
	    };

	    //// Fim filtro representante

	    //// Início filtro UF

	    vm.eventoAlterarUF = function($event) {

	    	// enter
	    	if ($event.keyCode == 13)
	    		vm.alterarUF();

	    	// backspace ou delete
	    	else if ($event.keyCode == 8 || $event.keyCode == 46)
	    		vm.limparUFSelecionado();
	    };

		vm.alterarUF = function() {

	        vm.consultarUF();
	        $('#modal-consultar-uf').modal('show');
	    };

	    vm.consultarUF = function() {

	        $ajax
	            .post('/_25010/consultarUF')
	            .then(function(response){

	                vm.listaUF = response;

	                // Fix para vs-repeat.
	                $('.table-uf')
	                    .trigger('resize')
	                    .scrollTop(0);

	                // Foco no input de filtrar.
	                $('.input-filtrar-uf').select();

	            });
	    };

	    vm.selecionarUF = function(uf) {

            vm.painelFiltro.UF = uf;
            $('.js-input-filtrar-uf').focus();

	        vm.fecharModalConsultarUF();
	    };

	    vm.limparUFSelecionado = function() {

	        vm.painelFiltro.UF = {UF: null};
	    };

	    /**
	     * Fix para vs-repeat: exibir a tabela completa.
	     */
	    vm.fixVsRepeatConsultarUF = function() {

	        $timeout(function(){
	            $('#modal-consultar-uf .table-uf').scrollTop(0);
	        }, 200);
	    };

	    vm.fecharModalConsultarUF = function() {
	        
	        $('#modal-consultar-uf')
	            .modal('hide')
	            .find('.modal-body')
	            .animate({ scrollTop: 0 }, 'fast');

	        vm.filtrarUF = '';
	    };

	    //// Fim filtro UF

		/**
		 * Gráfico de quantidades respondidas no painel.
		 */
		vm.graficoQtdRespondida = function() {
			
			function dados() {
				
				var data = new google.visualization.arrayToDataTable([
					['Item', 'Qtd'],
					['Respondido', parseInt(vm.painel.QTD_RESPONDIDA)],
					['Não respondido', parseInt(vm.painel.QTD_RESPOSTA_ESPERADA) - parseInt(vm.painel.QTD_RESPONDIDA)]
				]);

				var options = {
		        	title: 'Respondido',
		        	height: 300
		        };

		        var chart = new google.visualization.PieChart(document.getElementById('grafico-qtd-respondida'));

        		chart.draw(data, options);
				
			}
			
			function carregar() {

				//google.charts.load('current', {packages: ['corechart', 'bar'], 'language': 'pt-br'});	// basta chamar 1 vez
				google.charts.setOnLoadCallback(dados);

			}
			
			return {
				carregar: carregar
			};
			
		};

		/**
		 * Gráfico de satisfação geral no painel.
		 */
		vm.graficoSatisfacaoGeral = function() {
			
			function dados() {
				
				var data = new google.visualization.arrayToDataTable([
					['Item', 'Qtd'],
					['Satisfação', parseFloat(vm.satisf_geral)],
					['Insatisfação', parseFloat(vm.insatisf_geral)]
				]);

				var options = {
		        	title: 'Satisfação',
		        	height: 300
		        };

		        var chart = new google.visualization.PieChart(document.getElementById('grafico-satisf-geral'));

        		chart.draw(data, options);
				
			}
			
			function carregar() {

				//google.charts.load('current', {packages: ['corechart'], 'language': 'pt-br'}); // basta chamar 1 vez
				google.charts.setOnLoadCallback(dados);

			}
			
			return {
				carregar: carregar
			};
			
		};

		/**
		 * Gráfico de satisfação com a Delfa no painel.
		 */
		vm.graficoSatisfacaoDelfa = function() {
			
			function dados() {
				
				var data = new google.visualization.arrayToDataTable([
					['Item', 'Qtd'],
					['Satisfação', parseFloat(vm.satisf_delfa)],
					['Insatisfação', parseFloat(vm.insatisf_delfa)]
				]);

				var options = {
		        	title: 'Satisfação com a Delfa',
		        	height: 300
		        };

		        var chart = new google.visualization.PieChart(document.getElementById('grafico-satisf-delfa'));

        		chart.draw(data, options);
				
			}
			
			function carregar() {

				//google.charts.load('current', {packages: ['corechart'], 'language': 'pt-br'}); // basta chamar 1 vez
				google.charts.setOnLoadCallback(dados);

			}
			
			return {
				carregar: carregar
			};
			
		};

		/**
		 * Gráfico de satisfação por usuário no painel.
		 */
		vm.graficoSatisfacaoPorUsuario = function() {
			
			function dados(elemento) {
				
				var data = new google.visualization.arrayToDataTable([
					['Item', 'Qtd'],
					['Satisfação', parseFloat(vm.satisf_usuario)],
					['Insatisfação', parseFloat(vm.insatisf_usuario)]
				]);

				var options = {
		        	legend: { position: 'bottom' },
		        	chartArea: { top: 10, width: '100%', height: '75%' }
		        };

		        var chart = new google.visualization.PieChart(elemento);

        		chart.draw(data, options);
				
			}
			
			function carregar(elemento) {

				//google.charts.load('current', {packages: ['corechart'], 'language': 'pt-br'}); // basta chamar 1 vez
				google.charts.setOnLoadCallback(function() { dados(elemento); });

			}
			
			return {
				carregar: carregar
			};
			
		};

		/**
		 * Gráfico de satisfação por pergunta no painel.
		 */
		vm.graficoSatisfacaoPorPergunta = function() {
			
			function dados(elemento) {
				
				var data = new google.visualization.arrayToDataTable([
					['Item', 'Qtd'],
					['Satisfação', parseFloat(vm.satisf_pergunta)],
					['Insatisfação', parseFloat(vm.insatisf_pergunta)]
				]);

				var options = {
		        	title: 'Satisfação por pergunta',
		        	height: 220
		        };

		        var chart = new google.visualization.PieChart(elemento);

        		chart.draw(data, options);
				
			}
			
			function carregar(elemento) {

				//google.charts.load('current', {packages: ['corechart'], 'language': 'pt-br'}); // basta chamar 1 vez
				google.charts.setOnLoadCallback(function() { dados(elemento); });

			}
			
			return {
				carregar: carregar
			};
			
		};

		/**
		 * Gráfico de satisfação por alternativa no painel.
		 */
		vm.graficoSatisfacaoPorAlternativa = function() {
			
			function dados(elemento) {
				
				var tam 	= vm.painelAlternativa.length,
					dados 	= ['Alternativa', 'Qtd']
				;

				var dataTable = new google.visualization.DataTable();

				dataTable.addColumn('string', 'Alternativa');
				dataTable.addColumn('number', 'Qtd.');

				for (var i in vm.painelAlternativa) {
					dataTable.addRow([vm.painelAlternativa[i].DESCRICAO, parseFloat(vm.painelAlternativa[i].QTD_ESCOLHIDA)]);
				}				

				var options = {
		        	title: 'Qtd. respostas por alternativa',
		        	chartArea: {left: 200}
		        };

		        var chart = new google.visualization.BarChart(elemento);

        		chart.draw(dataTable, options);
				
			}
			
			function carregar(elemento) {

				//google.charts.load('current', {packages: ['corechart', 'bar'], 'language': 'pt-br'});	// basta chamar 1 vez
				google.charts.setOnLoadCallback(function() { dados(elemento); });

			}
			
			return {
				carregar: carregar
			};

		};

		/**
		 * Calcular satisfação por alternativa.
		 */
		vm.calcularSatisfacaoPorAlternativa = function(pergunta) {

			var alternativa = {};
			vm.painelAlternativa = [];

			for (var i in vm.painel.ALTERNATIVA) {

				alternativa = vm.painel.ALTERNATIVA[i];

				if (alternativa.FORMULARIO_PERGUNTA_ID == pergunta.FORMULARIO_PERGUNTA_ID) {

					vm.painelAlternativa.push({

						ID 				: alternativa.ALTERNATIVA_ID,
						DESCRICAO		: alternativa.ALTERNATIVA_DESCRICAO,
						QTD_ESCOLHIDA	: alternativa.ALTERNATIVA_QTD_ESCOLHIDA

					});

				}

			}

			vm.graficoSatisfacaoPorAlternativa().carregar(document.getElementById('grafico-satisf-alternativa-'+pergunta.FORMULARIO_PERGUNTA_ID));

		};

		/**
		 * Calcular satisfação por usuário.
		 */
		vm.calcularSatisfacaoPorUsuario = function(resposta) {
			
			var rspt  				= {},
				soma_nota			= 0,
				// possui_nivel_satisf = false,
				nao_se_aplica		= false,
				conta_nao_se_aplica = 0,
				conta_satisf		= 0,
				conta_insatisf		= 0,
				satisf_usuario		= 0,
				total_resp 			= 0,
				qtd_perg			= 0,
				meta_satisf			= 0,
				nota_maior			= 0
			;

			vm.satisf_usuario	= 0;
			vm.insatisf_usuario	= 0;

			// Respostas.
			for (var i in resposta) {

				rspt = resposta[i];

				// Quantidade respostas.
				total_resp++;

				// Se a alternativa possui nível de satisfação.
				if (rspt.NIVEL_SATISFACAO > 0) {

					//possui_nivel_satisf = true;
					nao_se_aplica = false;

					if (rspt.NIVEL_SATISFACAO == '1')
						conta_satisf++;
					else if (rspt.NIVEL_SATISFACAO == '2')
						conta_insatisf++;
					else if (rspt.NIVEL_SATISFACAO == '3') {
						conta_nao_se_aplica++;
						//possui_nivel_satisf = false;
						nao_se_aplica = true;
					}

				}

				// Se a alternativa possui nota.
				if (rspt.ALTERNATIVA_NOTA != null && !nao_se_aplica) {

					qtd_perg++;		// Quantidade de perguntas com nota.
					nota_maior = rspt.ALTERNATIVA_NOTA_MAIOR;

					// Soma satisfação.
					// Obs.: a verificação da nota serve para as perguntas subjetivas.
					soma_nota += parseFloat(rspt.ALTERNATIVA_NOTA);

				}

			}

			if (total_resp > 0) {

				// Cálculo por nível de satisfação.
				//if ( possui_nivel_satisf ) {	// Se a pergunta (alternativas) possui nível de satisfação.

					// Percentual satisfação.
					meta_satisf			= qtd_perg * 1;														// Meta de satisfação. Definida pela qtd de pergunta multiplicado pelo valor satisfatório.
					satisf_usuario		= (meta_satisf > 0) ? ((conta_satisf * 100) / meta_satisf) : 0;		// % de satisfação do usuário.

				//}
				// Cálculo por nota.
				/*else {

					// Percentual satisfação.
					meta_satisf			= qtd_perg * nota_maior;										// Meta de satisfação. Ex.: 5 perguntas com respostas de 1 à 5 a meta é 25.
					satisf_usuario		= (meta_satisf > 0) ? ((soma_nota * 100) / meta_satisf) : 0;	// % de satisfação do usuário.

				}*/

				// Satisfação do usuário.
				vm.satisf_usuario	= satisf_usuario;
				vm.insatisf_usuario	= 100 - satisf_usuario;

			}

			// Se todas as respostas forem 'Não se aplica', o gráfico some.
			if (total_resp === conta_nao_se_aplica)
				document.getElementById('grafico-satisf-usuario').innerHTML = '';
			else
				vm.graficoSatisfacaoPorUsuario().carregar(document.getElementById('grafico-satisf-usuario'));

		};

		vm.calcularSatisfacaoPorCliente = function(cliente) {

			var metaSatisf  = 10,
				percSatisf 	= 0;

			vm.painel.CLIENTE_SELECIONADO = cliente;

			// Calcular percentual de satisfação do cliente.
			percSatisf 			= (cliente.SATISFACAO * 100) / metaSatisf;
			vm.satisf_usuario	= percSatisf;
			vm.insatisf_usuario	= 100 - percSatisf;
			
			vm.graficoSatisfacaoPorUsuario().carregar(document.getElementById('grafico-satisf-usuario'));
		};

		/**
		 * Selecionar destinatário.
		 */
		vm.selecionarDestinatarioPainel = function(destinatario) {

			for(var i in vm.painel.DESTINATARIO)
				vm.painel.DESTINATARIO[i].selected = false;

			// Indica se deve selecionar a linha da tabela.
			if (destinatario.selected == undefined || destinatario.selected == false)
				destinatario.selected = true;
			else
				destinatario.selected = false;

		};

		/**
		 * Ver as respostas do destinatário.
		 */
		vm.verDestinatarioResposta = function(destinatario) {

			vm.destinatarioResposta = [];
			vm.selecionarDestinatarioPainel(destinatario);

			var dados 	= {
		            formulario_id 	: destinatario.FORMULARIO_ID,
		            destinatario_id	: destinatario.ID
		        },
	        	url 	= '/_25010/painelResposta'
	        ;
	    
	        $ajax
	        	.post(url, JSON.stringify(dados), {contentType: 'application/json'})
	            .then(function(response) {  

	            	var resposta = {};

	                for (var i in response) {

	                	resposta = response[i];

	                	vm.destinatarioResposta.push({

							FORMULARIO_PERGUNTA_ID		: resposta.FORMULARIO_PERGUNTA_ID,	
							FORMULARIO_PERGUNTA_ORDEM	: resposta.FORMULARIO_PERGUNTA_ORDEM,	
							PERGUNTA_DESCRICAO			: resposta.PERGUNTA_DESCRICAO,	
							ALTERNATIVA_DESCRICAO		: resposta.ALTERNATIVA_DESCRICAO,
							ALTERNATIVA_NOTA			: resposta.ALTERNATIVA_NOTA,
							ALTERNATIVA_NOTA_MAIOR		: resposta.ALTERNATIVA_NOTA_MAIOR,
							NIVEL_SATISFACAO			: resposta.NIVEL_SATISFACAO,
							NIVEL_SATISFACAO_DESCRICAO	: resposta.NIVEL_SATISFACAO_DESCRICAO,
							JUSTIFICATIVA				: resposta.JUSTIFICATIVA

						});
	                }

	                if (vm.formulario.TIPO != 3)
	                	vm.calcularSatisfacaoPorUsuario(vm.destinatarioResposta);
	                else
	                	vm.calcularSatisfacaoPorCliente(destinatario);
	            })
	        ;

		};

		/**
		 * Calcular satisfação geral.
		 */
		vm.calcularSatisfacaoGeral = function(satisfacao) {
			
			// Satisfação geral.
			vm.satisf_geral	 	= satisfacao[0].PERC_SATISF;
			vm.insatisf_geral 	= 100 - satisfacao[0].PERC_SATISF;

			vm.graficoSatisfacaoGeral().carregar();

		};

		/**
		 * Calcular satisfação por pergunta.
		 */
		vm.calcularSatisfacaoPorPergunta = function(satisf_pergunta) {

			var pergunta = {};

			for (var i in satisf_pergunta) {

				pergunta = satisf_pergunta[i];

				// Satisfação da pergunta.
				vm.satisf_pergunta	 = parseFloat(pergunta.PERC_SATISF);
				vm.insatisf_pergunta = 100 - parseFloat(pergunta.PERC_SATISF);

				vm.graficoSatisfacaoPorPergunta().carregar(document.getElementById('grafico-satisf-pergunta-'+pergunta.FORMULARIO_PERGUNTA_ID));
				vm.calcularSatisfacaoPorAlternativa(pergunta);
			}
		};


		// vm.filtrarStatusDestinatario = function() {

		// 	if (vm.filtroStatusDestinatario) {
		// 		vm.painel.DESTINATARIO = $filter('filter')(vm.painel.DESTINATARIO, STATUS_RESPOSTA = '1');
		// 		vm.filtroStatusDestinatario = false;
		// 	}
		// 	else {
		// 		vm.painel.DESTINATARIO = $filter('filter')(vm.painel.DESTINATARIO, STATUS_RESPOSTA = '0');
		// 		vm.filtroStatusDestinatario = true;
		// 	}

		// };


		/**
		 * Ações ao fechar modal.
		 */
		$('#modal-create')
			.on('hidden.bs.modal', function() {
				
				// Foco no input de pesquisa.
				$('.filtro-obj').focus();

			})
		;



		/**
		 * CREATE
		 */

        /**
         * Listar tipos de formulário.
         */
		vm.listarTipoFormulario = function() {

			$ajax
				.post('/_25010/listarTipoFormulario')
				.then(function(response) {

					vm.tipoFormulario = response;

				})
			;

		};

		/**
		 * Verificação ao trocar o tipo de destinatário.
		 */
		vm.alterarDestinatarioTipo = function() {
			
			if ( (vm.formulario.DESTINATARIO_TIPO == "usuario") && (vm.listaCCustoSelec.length > 0) ) {

				addConfirme(
					'<h4>Confirmação</h4>',
                	'Você só pode escolher um tipo de destinatário para esta pesquisa.<br />Deseja excluir os <b>Centro de Custos</b> escolhidos?',
                	[obtn_sim, obtn_nao],
                	[
	                	{
	                		ret: 1,
	                		func: function() { vm.excluirCCustoEscolhido(vm.listaCCustoSelec); }
	                	},
	                	{
	                		ret: 2,
	                		func: function() { $timeout(function() { vm.formulario.DESTINATARIO_TIPO = "ccusto"; }); }                	
	                	}
                	]
                );

			}
			else if ( (vm.formulario.DESTINATARIO_TIPO == "ccusto") && (vm.listaUsuarioSelec.length > 0) ) {

				addConfirme(
					'<h4>Confirmação</h4>',
                	'Você só pode escolher um tipo de destinatário para esta pesquisa.<br />Deseja excluir os <b>Usuários</b> escolhidos?',
                	[obtn_sim, obtn_nao],
                	[
	                	{
	                		ret: 1,
	                		func: function() { vm.excluirUsuarioEscolhido(vm.listaUsuarioSelec); }
	                	},
	                	{
	                		ret: 2,
	                		func: function() { $timeout(function() { vm.formulario.DESTINATARIO_TIPO = "usuario"; }); }
	                	}
                	]
                );

			}

		};

		/**
		 * Listar usuários.
		 */
		vm.listarUsuario = function() {
            
			$ajax
				.post('/_11010/listarTodos')
				.then(function(response) {

					vm.listaUsuario = response;
				})
			;

		};

		/**
		 * Selecionar usuários (modal).
		 */
		vm.selecionarUsuario = function(usuario, $event) {

			// Não exibir caso o checkbox seja clicado.
			// if ( $($event.target).hasClass('chk-selec-usuario') )
			// 	return false;

			if (vm.verificarUsuarioEhDestinatario(usuario) > -1)
				return false;

			var indexUsuario = vm.listaUsuarioSelec.indexOf(usuario);

			if (indexUsuario > -1) {
				vm.listaUsuarioSelec.splice(indexUsuario, 1);
			}
			else {
				usuario.PESO = 1;
				vm.listaUsuarioSelec.push(usuario);
			}

		};

		/**
	     * Verificar se o usuário da lista de usuários (modal) já é um destinatário.
	     */
	    vm.verificarUsuarioEhDestinatario = function(usuario) { 

	    	var ret = 	vm.listaUsuarioSelec
		    				.map(function(item) { return item.ID; })
		    				.indexOf(usuario.ID)
			    		;

	    	return ret;
		};

		/**
		 * Selecionar usuários escolhidos.
		 */
		vm.selecionarUsuarioEscolhido = function($event, usuario) {

			// Não exibir caso o checkbox seja clicado.
			if ( $($event.target).hasClass('chk-reabilitar-usuario') || $($event.target).hasClass('chk-visualiza-cadastro') )
				return false;

			var indexUsuario = vm.listaUsuarioSelecEscolhido.indexOf(usuario);

			if (indexUsuario > -1) {
				vm.listaUsuarioSelecEscolhido.splice(indexUsuario, 1);
			}
			else {
				vm.listaUsuarioSelecEscolhido.push(usuario);
			}

		};

		/**
		 * Excluir usuários escolhidos.
		 */
		vm.excluirUsuarioEscolhido = function(usuario) {

			var indexUsuario = -1,
				usu = '',
				usuario_length = usuario.length
			;

			for (var i = usuario_length-1; i >= 0; i--) {

				usu 		 = usuario[i];
				indexUsuario = vm.listaUsuarioSelec.indexOf(usu);

				if (indexUsuario > -1) {
					vm.listaDestinatarioExcluir.push(usu);
					vm.listaUsuarioSelec.splice(indexUsuario, 1);
				}

			}

			vm.listaUsuarioSelecEscolhido = [];

		};

		/**
		 * Reabilitar usuários (modal).
		 */
		vm.reabilitarUsuario = function(usuario) {

			if (usuario.STATUS_RESPOSTA == '0' || usuario.STATUS_RESPOSTA == 'undefined')
				return false;

			var usu = [];

			for (var i in vm.listaUsuarioSelec) {

				usu = vm.listaUsuarioSelec[i];

				if (usu.ID == usuario.ID)
					usu.STATUS_RESPOSTA = '0';
			}

		};


		$('#modal-destinatario-usuario')
			.on('shown.bs.modal', function() {

				$('#input-filtrar-usuario').select();

			})
		;


		/**
		 * Listar Centro de Custos.
		 */
		vm.listarCCusto = function() {
            
			$ajax
				.post('/_20030/pesquisaCCustoTodos')
				.then(function(response) {

					vm.listaCCusto = response;					

				})
			;

		};

		/**
		 * Selecionar Centro de Custos (modal).
		 */
		vm.selecionarCCusto = function(ccusto) {

			var indexCCusto = vm.listaCCustoSelec.indexOf(ccusto);

			if (indexCCusto > -1) {
				vm.listaCCustoSelec.splice(indexCCusto, 1);
			}
			else {
				ccusto.PESO = 1;
				vm.listaCCustoSelec.push(ccusto);
			}

			vm.todosCCustoSelecionado = false;

		};

		/**
		 * Selecionar Centro de Custos escolhidos.
		 */
		vm.selecionarCCustoEscolhido = function(ccusto) {

			var indexCCusto = vm.listaCCustoSelecEscolhido.indexOf(ccusto);

			if (indexCCusto > -1) {
				vm.listaCCustoSelecEscolhido.splice(indexCCusto, 1);
			}
			else {
				vm.listaCCustoSelecEscolhido.push(ccusto);
			}

		};

		/**
		 * Excluir Centro de Custos escolhidos.
		 */
		vm.excluirCCustoEscolhido = function(ccusto) {

			var indexCCusto 	= -1,
				cc 				= '',
				ccusto_length 	= ccusto.length
			;

			for (var i = ccusto_length-1; i >= 0; i--) {

				cc 		 	= ccusto[i];
				indexCCusto = vm.listaCCustoSelec.indexOf(cc);

				if (indexCCusto > -1) {
					vm.listaDestinatarioExcluir.push(cc);
					vm.listaCCustoSelec.splice(indexCCusto, 1);
				}

			}

			vm.listaCCustoSelecEscolhido = [];

		};

		/**
		 * Selecionar todos os Centro de Custos.
		 */
		vm.selecionarTodosCCusto = function() {

			// Limpar todos.
			if (vm.listaCCustoSelec.length > 0 && vm.todosCCustoSelecionado) {

				vm.listaCCustoSelec = [];
				vm.todosCCustoSelecionado = false;

			}
			// Selecionados todos.
			else {

				var ccusto = [];
				vm.listaCCustoSelec = [];

				for (var i in vm.listaCCusto) {

					ccusto = vm.listaCCusto[i];

					ccusto.PESO = 1;
					vm.listaCCustoSelec.push(ccusto);

				}

				vm.todosCCustoSelecionado = true;

			}

		};

		$('#modal-destinatario-ccusto')
			.on('shown.bs.modal', function() {

				$('#input-filtrar-ccusto').select();

			})
		;

		/**
		 * Adicionar pergunta.
		 */
		vm.addPergunta = function() {

			var perguntaNova = {};
			angular.copy(vm.perguntaPadrao, perguntaNova);
			perguntaNova.ORDEM = vm.pergunta.length+1;
			vm.pergunta.push(perguntaNova);

			vm.somarNota();

		};

		/**
		 * Excluir pergunta.
		 */
		vm.excluirPergunta = function(index) {

			vm.perguntaExcluir.push(vm.pergunta[index]);
			vm.pergunta.splice(index, 1);

			vm.somarNota();

		};

		/**
         * Listar tipos de resposta.
         */
		vm.listarTipoResposta = function() {

			$ajax
				.post('/_25010/listarTipoResposta')
				.then(function(response) {

					vm.tipoResposta = response;					

				})
			;

		};

		/**
         * Listar níveis de satisfação das alternativas.
         */
		vm.listarNivelSatisfacao = function() {

			$ajax
				.post('/_25010/listarNivelSatisfacao')
				.then(function(response) {

					vm.nivelSatisfacao = response;			

				})
			;

		};

		/**
		 * Ações ao selecionar um nível de satisfação.
		 */
		vm.selecionarNivelSatisfacao = function(alternativa) {

			if (alternativa.NIVEL_SATISFACAO == 3)
				alternativa.NOTA = '0';

		};

		/**
		 * Adicionar alternativas.
		 */
		vm.addAlternativa = function(perg) {

			var alternativaNova = {};
			angular.copy(vm.alternativaPadrao, alternativaNova);
			perg.ALTERNATIVA.push(alternativaNova);

			vm.somarNota();

		};

		/**
		 * Excluir alternativa.
		 */
		vm.excluirAlternativa = function(perg, index) {

			perg.ALTERNATIVA_EXCLUIR.push(perg.ALTERNATIVA[index]);
			perg.ALTERNATIVA.splice(index, 1);

			vm.somarNota();

		};

		/**
		 * Ações ao selecionar um tipo de resposta.
		 */
		vm.selecionarTipoResposta = function(perg) {

			if (perg.TIPO_RESPOSTA == '3')
				for (var i in perg.ALTERNATIVA)
					perg.ALTERNATIVA[i].NOTA = 0;

			vm.somarNota();
		};


		/**
		 * Verificar dados antes de gravar.
		 */
		vm.verificarDados = function() {

			var ret = true;

			// Verificar se algum destinatário foi escolhido.
			if ( (vm.formulario.TIPO != 3) && (vm.listaUsuarioSelec.length == 0 && vm.listaCCustoSelec.length == 0) ) {

				showErro('Escolha destinatários.');
				ret = false;

			}
			// Verificar se existem perguntas.
			else if ( vm.pergunta.length == 0 ) {

				showErro('Adicione perguntas.');
				ret = false;

			}
			// Verificar se o total das notas é igual a 10.
			else if ( vm.formulario.TIPO == 3 && ($filter('number')(vm.totalNota) != '10,000' && $filter('number')(vm.totalNota) != '10') ) {
	    		
	    		showErro('A soma das notas deve ser igual à 10.');
	    		ret = false;
			}
			// Verificações para as alternativas.
			else if ( vm.formulario.TIPO != 3 ) {

				var perg 				= [],
					altern 				= [],
					conta_satisf 		= 0,
					conta_insatisf		= 0,
					conta_nao_se_aplica = 0
				;

				for (var i in vm.pergunta) {

					perg = vm.pergunta[i];

					conta_satisf 		= 0;
					conta_insatisf		= 0;
					conta_nao_se_aplica = 0;

					for (var j in perg.ALTERNATIVA) {

						altern = perg.ALTERNATIVA[j];

						// Contar níveis escolhidos.
						if (altern.NIVEL_SATISFACAO == '1')
							conta_satisf++;
						else if (altern.NIVEL_SATISFACAO == '2')
							conta_insatisf++;
						else if (altern.NIVEL_SATISFACAO == '3')
							conta_nao_se_aplica++;

					}

					// Se o tipo de resposta não for subjetiva.
					if (perg.TIPO_RESPOSTA != '3') {

						// Se o nível selecionado for 'SATISFATÓRIO',
						// pelo menos uma das alternativas deve ser 'INSATISFATÓRIA' e vice-versa.
						if (conta_nao_se_aplica == 0 && (conta_satisf == 0 || conta_insatisf == 0)) {
							
							showErro('Ao selecionar o nível "Satisfatório" ou "Insatisfatório", alguma alternativa precisa ter nível oposto. Verifique a pergunta '+ $filter('lpad')([(parseInt(i)+1)], [2,"0"]) +'.');
							ret = false;
							return false;

						}

						// Se um nível selecionado for 'NÃO SE APLICA', 
						// todas as outras alternativas também deverão ser desse nível.
						// if (conta_nao_se_aplica > 0 && (conta_satisf > 0 || conta_insatisf > 0)) {

						// 	showErro('Ao selecionar o nível "Não se aplica" em uma alternativa, todas as outras também deverão ser desse nível. Verifique a pergunta '+ $filter('lpad')([(parseInt(i)+1)], [2,"0"]) +'.');
						// 	ret = false;
						// 	return false;

						// }

					}

				}

			}

			return ret;

		};

		/**
		 * Ações no sucesso ao gravar formulário.
		 */
		vm.sucessoGravarFormulario = function() {

			if (vm.tipoTela == 'incluir') 
            	showSuccess('Formulário gravado com sucesso.');
            else if (vm.tipoTela == 'alterar') 
            	showSuccess('Formulário alterado com sucesso.');

            vm.tipoTela 				= 'exibir';
            vm.listaDestinatarioExcluir = [];
            vm.perguntaExcluir 			= [];

            vm.listarFormulario();

		};

		/**
		 * Incluir/alterar formulário.
		 */
		vm.gravarFormulario = function() {

			if ( !vm.verificarDados() ) return false;

			var dados 	= {
		            formulario 				: vm.formulario,
		            destinatario			: (vm.listaUsuarioSelec.length > 0) ? vm.listaUsuarioSelec : vm.listaCCustoSelec,
		            destinatario_excluir	: vm.listaDestinatarioExcluir,
		            pergunta 				: vm.pergunta,
		            pergunta_excluir		: vm.perguntaExcluir
		        },
	        	url 	= (vm.tipoTela == 'incluir') ? '/_25010/gravar' : '/_25010/alterar'
	        ;
	    
	        $ajax
	        	.post(url, JSON.stringify(dados), {contentType: 'application/json'})
	            .then(function(response) {  

	                vm.sucessoGravarFormulario();

	            })
	        ;

	    };

	    /**
	     * Somar notas.
	     * Na pesquisa de cliente (tipo 3), precisa ser 10.
	     */
	    vm.somarNota = function() {

	    	if (vm.formulario.TIPO != 3)
	    		return false;

	    	var alt = {};
	    	vm.totalNota = 0;

	    	for (var i in vm.pergunta) {

	    		for (var j in vm.pergunta[i].ALTERNATIVA) {

	    			alt = vm.pergunta[i].ALTERNATIVA[j];

	    			vm.totalNota += (alt.NOTA) ? parseFloat(alt.NOTA) : 0;
	    		}
	    	}
	    };

	    vm.redefinirFormulario();
		vm.listarTipoFormulario();
		vm.listarTipoResposta();
		vm.listarNivelSatisfacao();
		vm.listarUsuario();
		vm.listarCCusto();
		vm.addPergunta();

	};

	//Injetando Components ao Controller.
	ctrl.$inject = ['$ajax', '$filter', '$timeout'];

    angular
    	.module('app', ['vs-repeat', 'gc-ajax', 'gc-find', 'gc-transform', 'gc-form'])
    	.controller('ctrl', ctrl)
	; 
    
    //angular.bootstrap(document.getElementById('main'), ['app']);

})(angular);
//# sourceMappingURL=_25010.js.map
