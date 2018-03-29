
	_29012IndexController.$inject = ['_29012IndexService', '_29012TarefaService', '$scope', '$timeout'];

	function _29012IndexController(_29012IndexService, _29012TarefaService, $scope, $timeout) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarWorkflowItem 	= consultarWorkflowItem;
		ctrl.exibirItem				= exibirItem;
		ctrl.ativarIncluir 			= ativarIncluir;
		ctrl.limparCampo 			= limparCampo;
		ctrl.consultarTarefa 		= consultarTarefa;
		ctrl.carregarInfoGeral		= carregarInfoGeral;
		ctrl.carregarTarefa			= carregarTarefa;
		ctrl.exibirModal			= exibirModal;

		// VARIÁVEIS
		ctrl.tipoTela 		= 'incluir';
		ctrl.filtro 		= {};
		ctrl.filtro.dataIni = moment().subtract(1, "month").toDate();
		ctrl.filtro.dataFim = moment().toDate();
		ctrl.workflowItem	= [];
		ctrl.usuarioId 		= parseInt( $('#usuario-id').val() );

		// MÉTODOS

		this.$onInit = function() {

			ctrl.filtro.workflowItemId 	= parseInt(getURLParameter('workflowItemId'));
			ctrl.filtro.tarefaId 		= parseInt(getURLParameter('tarefaId'));
		};

		// Consultar workflow (filtro inicial) quando o id do workflow for passado na URL.
		$scope.$watch('$ctrl.filtro.workflowItemId', function(newValue, oldValue, scope) {
            
            if (newValue > 0) {

                $timeout(function() {
					ctrl.consultarWorkflowItem();
                }, 100);
            } 
        });

		// Depois que a lista de workflows for carregada,
		// exibir um workflow de acordo com o id do workflow passado na URL.
        $scope.$watch('$ctrl.workflowItem', function(newValue, oldValue, scope) {

        	if (newValue.length > 0) {

        		for (var i in newValue) {

        			if (newValue[i].ID == ctrl.filtro.workflowItemId) {

        				ctrl.exibirItem(newValue[i]);
        				
        				if (ctrl.filtro.tarefaId) {
	        			
	        				setTimeout(function() {
								$('#tab-tarefa a.tarefa-'+ctrl.filtro.tarefaId).tab('show');
							}, 2000);
	        			}

        				ctrl.filtro.workflowItemId = 0;		// para não repetir o watch dele.

        				break;
        			}
        		}
        	}
        });

		/**
		 * Consultar item de workflow.
		 */
		function consultarWorkflowItem() {

			var param = {
					DATA_INI		: moment(ctrl.filtro.dataIni).format('DD.MM.YYYY')+' 00:00:00',
					DATA_FIM		: moment(ctrl.filtro.dataFim).format('DD.MM.YYYY')+' 23:59:59',
					STATUS_CONCLUSAO: ctrl.filtro.status ? ctrl.filtro.status : null,
					WORKFLOW_ITEM_ID: ctrl.filtro.workflowItemId ? ctrl.filtro.workflowItemId : null
				};

			_29012IndexService
				.consultarWorkflowItem(param)
				.then(function(response) {
					
					ctrl.workflowItem = response;

					var item = null;

					for (var i in ctrl.workflowItem) {

						item = ctrl.workflowItem[i];

						item.DATAHORA_INSERT_FORMATADO = moment(item.DATAHORA_INSERT).format('DD/MM/YYYY HH:mm:ss');
					}
				});

			//uriHistory(param);
		}

		/**
		 * Definir URL com parâmetro do filtro e guardar em localStorage.
		 */
		// function uriHistory(dado) {

		// 	window.history.replaceState('', '', encodeURI(urlhost + '/_29012?'+ $.param(dado)));

		// 	localStorage.setItem('29012FiltroUrl', location.href);
		// }

		function exibirItem(workflow) {

			ctrl.tipoTela = 'exibir';
			ctrl.consultarTarefa(workflow);
		}

		function consultarTarefa(workflow) {

			var param = {
					WORKFLOW_ITEM_ID: workflow.ID
				};

			_29012TarefaService
				.consultarWorkflowItemTarefa(param)
				.then(function(response) {

					// tarefa = response;
					ctrl.carregarInfoGeral(workflow);
					ctrl.carregarTarefa(response);
					ctrl.exibirModal(workflow);
				});
		}

		function carregarInfoGeral(workflow) {

			var infGrl = ctrl.create.infoGeral.infoGeral;

			if (infGrl.ID == null) {

				angular.copy(workflow, infGrl);

				infGrl.WORKFLOW_MODELO 		  = {};
				infGrl.WORKFLOW_MODELO.ID 	  = infGrl.WORKFLOW_ID;
				infGrl.WORKFLOW_MODELO.TITULO = infGrl.WORKFLOW_TITULO;

				infGrl.DATAHORA_INI_PREVISTA  = moment(infGrl.DATAHORA_INI_PREVISTA).toDate();
				infGrl.DATAHORA_FIM_PREVISTA  = moment(infGrl.DATAHORA_FIM_PREVISTA).toDate();
			}
		}

		function carregarTarefa(tarefa) {

			var trf = {},
				tempoPrevistoDuration = null;

			// Limpa array que contém todos os arquivos.
			ctrl.create.arquivoTodos = [];

			// Ordenar por sequencia e id.
			tarefa.TAREFA = tarefa
								.TAREFA
								.sort(function(a, b) { return a.SEQUENCIA - b.SEQUENCIA || a.ID - b.ID });

			for (var i in tarefa.TAREFA) {

				trf = tarefa.TAREFA[i];

				trf.TEMPO_CONCLUSAO_HUMANIZE = moment.duration(parseFloat(trf.TEMPO_CONCLUSAO), "minutes").format('d[d] HH[h] mm[m] ss[s]');

				tempoPrevistoDuration 		= moment.duration(parseFloat(trf.TEMPO_PREVISTO), 'minutes');

				trf.TEMPO_PREVISTO_HORA 	= moment.duration(tempoPrevistoDuration.days(), 'days').asHours();	// dias em horas
				trf.TEMPO_PREVISTO_HORA 	= moment
												.duration(trf.TEMPO_PREVISTO_HORA, 'hours')
												.add(tempoPrevistoDuration.hours(), 'h')	// add horas do tempo previsto
												.asHours();
												
				trf.TEMPO_PREVISTO_MINUTO 	= tempoPrevistoDuration.minutes();

				carregarTarefaDestinatario(trf);
				habilitarAcaoTarefa(trf, parseInt(i));	// Precisa chamar depois de destinatário devido ao campo 'DO_USUARIO'.
				carregarTarefaNotificado(trf);
				carregarTarefaComentario(trf);
				carregarTarefaMovimentacao(trf);
				carregarTarefaCampo(trf);
				carregarArquivoTodos(trf);
				
				// Caso não tenha nenhum arquivo, adiciona um vazio.
				if (trf.ARQUIVO_DESTINATARIO.length == 0)
					ctrl.tarefa.addArquivo(trf);
			}

			ctrl.tarefa.tarefa = tarefa.TAREFA;


			function carregarTarefaDestinatario(trf) {

				var destin = {};

				trf.DESTINATARIO = [];

				for (var j in tarefa.DESTINATARIO) {

					destin = tarefa.DESTINATARIO[j];

					if (trf.ID == destin.WORKFLOW_ITEM_TAREFA_ID)
						trf.DESTINATARIO.push(destin);

					// Se não tiver permissão para reiniciar uma tarefa.
					if (ctrl.tarefa.pu224 == null || ctrl.tarefa.pu224 == '0')
						analisarTarefaDestinatario(trf);
					else
						trf.DO_USUARIO = 1;
				}
			}

			function carregarTarefaNotificado(trf) {

				var notif = {};

				trf.NOTIFICADO = [];

				for (var j in tarefa.NOTIFICADO) {

					notif = tarefa.NOTIFICADO[j];

					if (trf.ID == notif.WORKFLOW_ITEM_TAREFA_ID)
						trf.NOTIFICADO.push(notif);
				}
			}


			/**
			 * Define se a tarefa pertence ao usuário.
			 */
			function analisarTarefaDestinatario(trf) {

				for (var j in tarefa.DESTINATARIO) {

					destin = tarefa.DESTINATARIO[j];

					if (destin.WORKFLOW_ITEM_TAREFA_ID == trf.ID && destin.USUARIO_ID == ctrl.usuarioId) {

						trf.DO_USUARIO = 1;
						break;
					}
					else
						trf.DO_USUARIO = 0;
				}
			}

			/**
			 * Habilitar ações da tarefa.
			 */
			function habilitarAcaoTarefa(trf, i) {

				trf.habilitarIniciar  = true;
				trf.habilitarPausar   = true;
				trf.habilitarConcluir = true;

				// Evitar index negativo.
				i = (i > 0) ? i-1 : 0;

				// Desabilitar botão 'Iniciar', 'Pausar' e 'Concluir' se:
				// - a tarefa não for destinada ao usuário atual;
				// - (removido) outro destinatário já tiver iniciado a tarefa; 		-->> || (trf.USUARIO_CONCLUSAO_ID != null && trf.USUARIO_CONCLUSAO_ID != ctrl.usuarioId)
				// - a sequência da tarefa atual for maior que a sequência da tarefa anterior.
				if ((trf.DO_USUARIO == 0) 
					|| (trf.SEQUENCIA > tarefa.TAREFA[i].SEQUENCIA && tarefa.TAREFA[i].STATUS_CONCLUSAO != '3')) {

					trf.habilitarIniciar  = false;
					trf.habilitarPausar   = false;
					trf.habilitarConcluir = false;
				}
				else {

					// Desabilitar botão 'Iniciar' se:
					// - o status da tarefa for 'iniciado' ou 'concluído'.
					if (trf.STATUS_CONCLUSAO == '1' || trf.STATUS_CONCLUSAO == '3')
						trf.habilitarIniciar = false;

					// Desabilitar botão 'Pausar' e 'Concluir' se:
					// - o status da tarefa não for 'iniciado';
					if (trf.STATUS_CONCLUSAO != '1') {

						trf.habilitarPausar   = false;
						trf.habilitarConcluir = false;
					}
				}
			}

			function carregarTarefaComentario(trf) {

				var coment = {};
				trf.COMENTARIO = [];

				for (var i in tarefa.COMENTARIO) {

					coment = tarefa.COMENTARIO[i];

					if (trf.ID == coment.WORKFLOW_ITEM_TAREFA_ID) {
						trf.COMENTARIO.push(coment);
					}
				}

				// Caso não tenha nenhum comentário, adiciona um vazio.
				if (trf.COMENTARIO.length == 0)
					ctrl.tarefa.addComentario(trf);
			}

			function carregarTarefaMovimentacao(trf) {

				var mov = {};

				trf.MOVIMENTACAO = [];

				for (var j in tarefa.MOVIMENTACAO) {

					mov = tarefa.MOVIMENTACAO[j];

					if (trf.ID == mov.WORKFLOW_ITEM_TAREFA_ID) {

						mov.DATAHORA_FORMATADO = moment(mov.DATAHORA).format('DD/MM/YYYY HH:mm:ss');
						trf.MOVIMENTACAO.push(mov);
					}
				}
			}

			function carregarTarefaCampo(trf) {

				var campo = {};
				trf.CAMPO = [];

				for (var j in tarefa.CAMPO) {

					campo = tarefa.CAMPO[j];

					if (trf.ID == campo.WORKFLOW_ITEM_TAREFA_ID) {
						trf.CAMPO.push(campo);
					}
				}
			}

			function carregarArquivoTodos(trf) {

				for (var i in trf.ARQUIVO)
					ctrl.create.arquivoTodos.push(trf.ARQUIVO[i]);

				for (var i in trf.ARQUIVO_DESTINATARIO)
					ctrl.create.arquivoTodos.push(trf.ARQUIVO_DESTINATARIO[i]);
			}
		}

		function exibirModal(workflow) {

			$('#modal-create')
				.modal('show')
				.removeData('workflowId')
				.data('workflowId', workflow.ID);	// Definindo id do workflow no modal para ser utilizado ao fechá-lo.
		}

		/**
		 * Ativar comportamento padrão da tela de Incluir.
		 */
		function ativarIncluir() {

			ctrl.tipoTela = 'incluir';
			$('#modal-create').modal('show');
		}

		/**
		 * Limpar campos do modal.
		 */
		function limparCampo() {

			ctrl.infoGeral.infoGeral = {};
			ctrl.infoGeral.STATUS 	 = '1';

			ctrl.tarefa.tarefa = [];
			ctrl.tarefa.addTarefa();

			ctrl.arquivoTodos = [];
		}

	}