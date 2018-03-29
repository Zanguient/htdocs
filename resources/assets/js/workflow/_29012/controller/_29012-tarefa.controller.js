	
	_29012TarefaController.$inject = ['_29012TarefaService', '$rootScope'];

	function _29012TarefaController(_29012TarefaService, $rootScope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS)

		ctrl.alterarSituacao 			 			 = alterarSituacao;
		ctrl.addComentario 				 			 = addComentario;
		ctrl.gravarWorkflowItemTarefaComentario 	 = gravarWorkflowItemTarefaComentario;
		ctrl.excluirComentario 						 = excluirComentario;
		ctrl.addCampo 								 = addCampo;
		ctrl.gravarWorkflowItemTarefaCampo			 = gravarWorkflowItemTarefaCampo;
		ctrl.addArquivo 				 			 = addArquivo;
		ctrl.gravarWorkflowItemArquivoDoDestinatario = gravarWorkflowItemArquivoDoDestinatario;
		ctrl.processarArquivo 			 			 = processarArquivo;
		ctrl.excluirArquivo 		 	 			 = excluirArquivo;
		ctrl.excluirArquivoTmpPorUsuario 			 = excluirArquivoTmpPorUsuario;
		ctrl.calcularTempo							 = calcularTempo;

		
		// VARIÁVEIS

		ctrl.usuarioId  	  = 0;
		ctrl.usuarioDescricao = '';

		ctrl.comentarioPadrao = {
			COMENTARIO: null
		};

		ctrl.campoPadrao = {
			ROTULO	: null,
			TIPO	: '1'
		};

		ctrl.arquivoPadrao = {
			NOME 	: null,
			TABELA 	: null,
			TIPO 	: null,
			TAMANHO	: null,
			BINARIO	: null,
			CONTEUDO: null
		};

		var tempoTotal 	 = 0;
		var timeInterval = null;


		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.index29012.tarefa  = this;
			ctrl.create29012.tarefa = this;

			ctrl.usuarioId 		  = parseInt( $('#usuario-id').val() );
			ctrl.usuarioDescricao = $('#usuario-descricao').val();
		};

		
		// MÉTODOS

		function alterarSituacao(situacao, tarefa) {

			verificarTarefa();

			// Limpar contagem de intervalo das movimentações.
			clearInterval(timeInterval);
			timeInterval = null;

			var param = {
					INFO_GERAL 		: ctrl.create29012.infoGeral.infoGeral,
					SITUACAO 		: situacao,
					TAREFA_ID 		: tarefa.ID,
					TAREFA_ATUAL	: tarefa,
					TAREFA  		: ctrl.tarefa,
					WORKFLOW_ITEM_ID: ctrl.create29012.infoGeral.infoGeral.ID
				};

			_29012TarefaService
				.alterarSituacao(param)
				.then(function(response) {

					if (situacao == 4) {

						ctrl.index29012.carregarTarefa(response);
					}
					else {

						tarefa.STATUS_CONCLUSAO = situacao;

						recarregarMovimentacao(response.MOVIMENTACAO);
						reabilitarBotaoAcao();
						ctrl.calcularTempo(tarefa);
					}

					recarregarProgresso(response.PROGRESSO[0].PROGRESSO);
				});


			function verificarTarefa() {

				// Se não for uma Reativação da Tarefa.
				if (situacao != 0) {

					var msg  = '',
						erro = false;

					if (tarefa.DO_USUARIO == 0) {
					
						msg  = 'Você não está listado para executar essa tarefa.';
						erro = true;
					}

					// if (tarefa.USUARIO_CONCLUSAO_ID != null && tarefa.USUARIO_CONCLUSAO_ID != ctrl.usuarioId) {

					// 	msg  = 'Outro usuário já iniciou essa tarefa.';
					// 	erro = true;
					// }


					if (erro) {

						showErro(msg);
						throw msg;
					}
				}
			}

			function recarregarProgresso(progresso) {

				ctrl.create29012.infoGeral.infoGeral.PROGRESSO = progresso;
			}

			function recarregarMovimentacao(movimentacao) {

				tarefa.MOVIMENTACAO = [];

				for (var i in movimentacao) {

					movimentacao[i].DATAHORA_FORMATADO = moment(movimentacao[i].DATAHORA).format('DD/MM/YYYY HH:mm:ss');
					tarefa.MOVIMENTACAO.push(movimentacao[i]);
				}
			}

			function reabilitarBotaoAcao() {

				tarefa.habilitarIniciar  = (situacao == 0 || situacao == 2) ? true : false;
				tarefa.habilitarPausar   = (situacao == 1) ? true : false;
				tarefa.habilitarConcluir = (situacao == 1) ? true : false;

				// Se a tarefa estiver sendo concluída.
				if (situacao == 3) {
				
					var trf = {};

					for (var i in ctrl.tarefa) {

						trf = ctrl.tarefa[i];

						// Pular tarefa atual e tarefas concluídas.
	                    if ( (trf.ID != tarefa.ID) && (trf.STATUS_CONCLUSAO != '3') ) {

	                        // Se a tarefa tiver a sequência posterior à atual.
	                        if ( parseInt(tarefa.SEQUENCIA)+1 == parseInt(trf.SEQUENCIA) ) {

	                        	for (var j in trf.DESTINATARIO) {

	                        		// Se for destinado para a próxima tarefa com sequência posterior à atual.
		                        	if (trf.DESTINATARIO[j].USUARIO_ID == ctrl.usuarioId) {

			                            trf.habilitarIniciar = true;
			                            break;
			                        }
			                    }

			                    break;
	                        }
	                    }
					}
				}
			}
		}

		function addComentario(tarefa) {

			var comentarioNovo = {};
			angular.copy(ctrl.comentarioPadrao, comentarioNovo);
			tarefa.COMENTARIO.push(comentarioNovo);

			setTimeout(function() {
				$('.comentario-container .scroll .form-group:last-of-type textarea').focus();
			}, 100);
		}

		/**
		 * Gravar comentário.
		 */
		function gravarWorkflowItemTarefaComentario(tarefa) {

			var param = {
					COMENTARIO 		  		: tarefa.COMENTARIO,
					WORKFLOW_ID 			: tarefa.WORKFLOW_ID,
					WORKFLOW_ITEM_ID 		: tarefa.WORKFLOW_ITEM_ID,
					WORKFLOW_ITEM_TAREFA_ID : tarefa.ID,
					USUARIO_ID 				: ctrl.usuarioId
				};

	        _29012TarefaService
	        	.gravarWorkflowItemTarefaComentario(param)
		        .then(function(resp) {
		            
		            showSuccess('Gravado com sucesso.');
		            tarefa.COMENTARIO = resp;
		        });
		}

		function excluirComentario(tarefa, comentario) {

			// Só exclui do banco de dados se o comentário tiver ID, ou seja, já está gravado no banco.
			if (comentario.ID > 0) {

				comentario.STATUSEXCLUSAO = '1';
				tarefa.existeComentarioParaExcluir = true;
			}
			else
				tarefa.COMENTARIO.splice(tarefa.COMENTARIO.indexOf(comentario), 1);

			// Adiciona um comentário vazio se não tiver mais nenhum outro.
			if (tarefa.COMENTARIO.length == 0) {
				ctrl.addComentario(tarefa);
			}
			else {

				var possuiComent = false;

				for(var i in tarefa.COMENTARIO)
					if (tarefa.COMENTARIO[i].STATUSEXCLUSAO == 0)
						possuiComent = true;

				if (possuiComent == false)
					ctrl.addComentario(tarefa);
			}
		}

		function addCampo(tarefa) {

			var campoNovo = {};
			angular.copy(ctrl.campoPadrao, campoNovo);
			tarefa.CAMPO.push(campoNovo);
		}

		/**
		 * Gravar campos dinâmicos.
		 */
		function gravarWorkflowItemTarefaCampo(tarefa) {

			var param = {
					CAMPO: tarefa.CAMPO
				};

	        _29012TarefaService
	        	.gravarWorkflowItemTarefaCampo(param)
		        .then(function(resp) {
		            
		            showSuccess('Gravado com sucesso.');
		        });
		}

		function addArquivo(tarefa) {

			var arquivoNovo = {};
			angular.copy(ctrl.arquivoPadrao, arquivoNovo);
			tarefa.ARQUIVO_DESTINATARIO.push(arquivoNovo);

			setTimeout(function() {
				$('.arquivo-container .scroll .form-group:last-of-type input.arquivo-binario').focus();
			}, 100);
		}

		/**
		 * Gravar arquivo do destinatário.
		 */
		function gravarWorkflowItemArquivoDoDestinatario(tarefa) {

			var param = {
					TAREFA_ID 					: tarefa.ID,
					ARQUIVO_DESTINATARIO 		: tarefa.ARQUIVO_DESTINATARIO,
					ARQUIVO_DESTINATARIO_EXCLUIR: tarefa.ARQUIVO_DESTINATARIO_EXCLUIR
				};

	        _29012TarefaService
	        	.gravarWorkflowItemArquivoDoDestinatario(param)
	        	.then(
                    function(resp) {
                        
                        showSuccess('Gravado com sucesso.');
                        tarefa.ARQUIVO_DESTINATARIO = resp.data.ARQUIVO;
                        delete tarefa.ARQUIVO_DESTINATARIO_EXCLUIR;
                    }, 
                    function(resp) {

                        showErro('Erro ao gravar ('+ resp.status +')'); 
                    },
                    function(e) {

                        progressPagina(e);
                    }
                );
		}

		function processarArquivo(event, arquivo) {

			arquivo.NOME 	 = event.target.files[0].name;
			arquivo.TABELA 	 = 'TBWORKFLOW_ITEM_TAREFA_DESTINAT';
			arquivo.TIPO 	 = event.target.files[0].type;
			arquivo.TAMANHO	 = event.target.files[0].size;
		}

		function excluirArquivo(tarefa, arquivo) {

			// Só adiciona para excluir do banco de dados se o arquivo tiver ID, ou seja, já está gravado no banco.
			if (arquivo.ID > 0) {

				tarefa.ARQUIVO_DESTINATARIO_EXCLUIR = (typeof tarefa.ARQUIVO_DESTINATARIO_EXCLUIR != 'undefined') 
														? tarefa.ARQUIVO_DESTINATARIO_EXCLUIR 
														: [];
				tarefa.ARQUIVO_DESTINATARIO_EXCLUIR.push(arquivo);
			}

			tarefa.ARQUIVO_DESTINATARIO.splice(tarefa.ARQUIVO_DESTINATARIO.indexOf(arquivo), 1);

			// Adiciona um arquivo vazio se não tiver mais nenhum outro.
			if (tarefa.ARQUIVO_DESTINATARIO.length == 0)
				ctrl.addArquivo(tarefa);
		}

		function excluirArquivoTmpPorUsuario() {

			var param = {
				DIRETORIO: 'workflowTarefa'
			};

			_29012TarefaService.excluirArquivoTmpPorUsuario(param);
		}


		function calcularTempo(tarefa) {

			if (tarefa.STATUS_CONCLUSAO == 1) {
				calcular(tarefa);
				calcularTempoAndamento(tarefa);
			}
			// reativando
			else if (tarefa.STATUS_CONCLUSAO == 0) {

				tarefa.TEMPO_CONCLUSAO 			= 0;
				tarefa.TEMPO_CONCLUSAO_HUMANIZE = 0;
			}
			else
				calcular(tarefa);


			function calcular(tarefa) {

				var mov 			= {},
					tempoCorrente 	= '',
					statusCorrente	= '',
					tempoProximo 	= '',
					diferenca 		= 0,
					tempoTotalObj 	= null,
					movOrdenado		= tarefa
										.MOVIMENTACAO
										.sort(function(a, b) { return a.ID - b.ID; });

				tempoTotal = 0;

				for (var i in movOrdenado) {

					i = parseInt(i);

					// último
					if ( i === movOrdenado.length-1 )
						break;

					mov = movOrdenado[i];

					tempoCorrente 	= mov.DATAHORA;
					statusCorrente 	= mov.STATUS_CONCLUSAO;
					tempoProximo	= movOrdenado[i+1].DATAHORA;

					// se estiver em 'reativado'
					if (statusCorrente == '0') {

						tempoTotal = 0;
						continue;
					}

					// se estiver em pausar ou finalizar e for o penúltimo item
					if ( (statusCorrente == '2' || statusCorrente == '3') && (i === (movOrdenado.length-2)) ) {

						// diferença entre tempos (ms)
						diferenca =	moment().diff(moment(tempoProximo));
					}
					else {
					
						// se o status não for 'iniciado'
						if ( statusCorrente != '1' )
							continue;

						// diferença entre tempos (ms)
						diferenca = moment(tempoProximo).diff(moment(tempoCorrente));
					}

					tempoTotal += diferenca;
				}

				tempoTotalObj = moment.duration(tempoTotal);

				tarefa.TEMPO_CONCLUSAO 			= tempoTotalObj.asMinutes().toFixed(4);
				tarefa.TEMPO_CONCLUSAO_HUMANIZE = tempoTotalObj.format('d[d] HH[h] mm[m] ss[s]');
			}

			function calcularTempoAndamento(tarefa) {

		        var movOrdenado	= tarefa
									.MOVIMENTACAO
									.sort(function(a, b) { return a.ID - b.ID; });

				var ultimaMov	= movOrdenado[movOrdenado.length-1];

				var ultimaData  = (ultimaMov.DATAHORA && (ultimaMov.STATUS_CONCLUSAO != 0))		// se existir um registro e o status nesse registro não for parado (ou reativado)
									? movOrdenado[movOrdenado.length-1].DATAHORA 
									: new Date();

				var diferenca 		 = moment(Clock.DATETIME_SERVER).diff(moment(ultimaData));
				tempoTotal 			+= diferenca;
				var tempoTotalObj 	 = moment.duration(tempoTotal);
		        
		        timeInterval = setInterval(function() {

		            $rootScope.$apply(function() {

		                tempoTotalObj = tempoTotalObj.add(1, 's');
		            
		                tarefa.TEMPO_CONCLUSAO 			= tempoTotalObj.asMinutes().toFixed(4);
		                tarefa.TEMPO_CONCLUSAO_HUMANIZE = tempoTotalObj.format('d[d] HH[h] mm[m] ss[s]');
		            });
		        }, 1000);
		    }
		}

	}