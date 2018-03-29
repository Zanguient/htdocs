
	WorkflowIndexController.$inject = ['WorkflowIndexService', 'TarefaService'];

	function WorkflowIndexController(WorkflowIndexService, TarefaService) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarWorkflow 	= consultarWorkflow;
		ctrl.exibirWorkflow 	= exibirWorkflow;
		ctrl.ativarIncluir 		= ativarIncluir;
		ctrl.limparCampo 		= limparCampo;

		// VARIÁVEIS
		ctrl.tipoTela 		= 'incluir';
		ctrl.filtro 		= {};
		ctrl.workflow 		= [];

		// MÉTODOS

		/**
		 * Consultar workflow.
		 */
		function consultarWorkflow() {

			var param = {
				STATUS  : ctrl.filtro.status ? ctrl.filtro.status : null
			};

			WorkflowIndexService
				.consultarWorkflow(param)
				.then(function(response) {
					ctrl.workflow = response;
				})
			;

		}

		function exibirWorkflow(workflow, gravarFechar) {

			var tarefa = [];

			gravarFechar = (typeof gravarFechar == 'undefined') ? true : gravarFechar;

			if (gravarFechar)
				ctrl.tipoTela = 'exibir';

			consultarTarefa(workflow);

			function consultarTarefa(workflow) {

				var param = {
						WORKFLOW_ID: workflow.ID
					};

				TarefaService
					.consultarTarefa(param)
					.then(function(response) {

						tarefa = response;
						carregarInfoGeral();
						carregarTarefa();

						if (gravarFechar)
							exibirModal();
					});
			}

			function carregarInfoGeral() {

				angular.copy(workflow, ctrl.infoGeral.infoGeral);
			}

			function carregarTarefa() {

				var trf = {},
					tempoPrevistoDuration = null;

				for (var i in tarefa.TAREFA) {

					trf = tarefa.TAREFA[i];

					tempoPrevistoDuration 		= moment.duration(parseFloat(trf.TEMPO_PREVISTO), 'minutes');

					trf.TEMPO_PREVISTO_HORA 	= moment.duration(tempoPrevistoDuration.days(), 'days').asHours();	// dias em horas
					trf.TEMPO_PREVISTO_HORA 	= moment
													.duration(trf.TEMPO_PREVISTO_HORA, 'hours')
													.add(tempoPrevistoDuration.hours(), 'h')	// add horas do tempo previsto
													.asHours();

					trf.TEMPO_PREVISTO_MINUTO 	= tempoPrevistoDuration.minutes();

					verificarPontoRetorno(trf);
					carregarTarefaDestinatario(trf);
					carregarTarefaCampo(trf);

					// Caso não tenha nenhum arquivo, adiciona um vazio.
					if (trf.ARQUIVO.length == 0)
						ctrl.tarefa.addArquivo(trf, false);
				}

				ctrl.tarefa.tarefa = tarefa.TAREFA;

				/**
				 * Verificar se a tarefa é um ponto de retorno de alguma outra tarefa que possa ser reprovada.
				 */
				function verificarPontoRetorno(trf) {

					var t = {};

					for (var i in tarefa.TAREFA) {

						t = tarefa.TAREFA[i];

						trf.EH_PONTO_RETORNO = 0;

						if (parseInt(t.PONTO_REPROVACAO) == parseInt(t.ORDEM)) {

							trf.EH_PONTO_RETORNO = 1;
							break;
						}
					}
				}

				function carregarTarefaDestinatario(trf) {

					var destin = {};
					trf.DESTINATARIO = [];

					for (var j in tarefa.DESTINATARIO) {

						destin = tarefa.DESTINATARIO[j];

						if (trf.ID == destin.WORKFLOW_TAREFA_ID) {
							trf.DESTINATARIO.push(destin);
						}
					}
				}

				function carregarTarefaCampo(trf) {

					var campo = {};
					trf.CAMPO = [];

					for (var j in tarefa.CAMPO) {

						campo = tarefa.CAMPO[j];

						if (trf.ID == campo.WORKFLOW_TAREFA_ID) {
							trf.CAMPO.push(campo);
						}
					}

					// Caso não tenha nenhum campo, adiciona um vazio.
					if (trf.CAMPO.length == 0)
						ctrl.tarefa.addCampo(trf, false);
				}
			}

			function exibirModal() {

				$('#modal-create').modal('show');
			}
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
		}

	}