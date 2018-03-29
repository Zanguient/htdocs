
	_29011InfoGeralController.$inject = ['_29011InfoGeralService','$scope','Historico'];

	function _29011InfoGeralController(_29011InfoGeralService, $scope, Historico) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.alterarWorkflowModelo 	= alterarWorkflowModelo;
		ctrl.consultarTarefa 		= consultarTarefa;

		// VARIÁVEIS
		ctrl.infoGeral = {};
		ctrl.Historico = new Historico('$ctrl.Historico', $scope);
		var alterandoModelo = false;

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.create29011.infoGeral = this;
		};

		// Consultar tarefas ao selecionar um workflow.
		$scope.$watch('$ctrl.infoGeral.WORKFLOW_MODELO', function() {
			
			if ( (alterandoModelo == true) 
				&& (ctrl.tipoTela == 'incluir' || ctrl.tipoTela == 'alterar') 
				&& (typeof ctrl.infoGeral.WORKFLOW_MODELO != 'undefined') 
			) {

				ctrl.infoGeral.TITULO 	 = ctrl.infoGeral.WORKFLOW_MODELO.TITULO;
				ctrl.infoGeral.DESCRICAO = ctrl.infoGeral.WORKFLOW_MODELO.DESCRICAO;
				ctrl.consultarTarefa();
				alterandoModelo = false;
			}
		});


		// MÉTODOS

		function alterarWorkflowModelo() {

			ctrl.create29011.consulta29010.consultar();
			$('#modal-consulta-workflow').modal('show');
			alterandoModelo = true;
		}

		// Troca de modelo
		function consultarTarefa() {

			var tarefa = [];

			removerTarefa();
			consultar();

			/**
			 * Remover tarefas ao trocar de modelo.
			 */
			function removerTarefa() {

				var tarefaClonada  = angular.copy(ctrl.create29011.tarefa.tarefa),
					tarefaAlterada = [];

				for (var i in tarefaClonada) {

					if (tarefaClonada[i].ID > 0) {

						tarefaClonada[i].STATUSEXCLUSAO = '1';
						tarefaAlterada.push(tarefaClonada[i]);
					}
				}

				ctrl.create29011.tarefa.tarefa = tarefaAlterada;
			}

			function consultar() {

				var param = {
						WORKFLOW_ID: ctrl.infoGeral.WORKFLOW_MODELO.ID
					};

				_29011InfoGeralService
					.consultarWorkflowTarefa(param)
					.then(function(response) {

						tarefa = response;
						carregar();

						ctrl.create29011.tarefa.verificarPontoRetorno();
					});
			}

			function carregar() {

				var trf = {},
					tempoPrevistoDuration = null;

				for (var i in tarefa.TAREFA) {

					trf = tarefa.TAREFA[i];

					tempoPrevistoDuration 		= moment.duration(parseFloat(trf.TEMPO_PREVISTO), 'minutes');

					trf.TEMPO_PREVISTO_HORA 	= tempoPrevistoDuration.hours();
					trf.TEMPO_PREVISTO_MINUTO 	= tempoPrevistoDuration.minutes();

					trf.DOMINGO 			= '0';
					trf.SEGUNDA 			= '1';
					trf.TERCA 				= '1';
					trf.QUARTA 				= '1';
					trf.QUINTA 				= '1';
					trf.SEXTA 				= '1';
					trf.SABADO 				= '0';
					trf.HORARIO_PERMITIDO	= '07:00-11:50;13:02-17:00';

					carregarTarefaDestinatario(trf);
					carregarTarefaCampo(trf);
					
					// Caso não tenha nenhum arquivo, adiciona um vazio.
					if (trf.ARQUIVO.length == 0)
						ctrl.create29011.tarefa.addArquivo(trf, false);

					delete trf.ID;

					ctrl.create29011.tarefa.tarefa.push(trf);
				}

				function carregarTarefaDestinatario(trf) {

					var destin = {};
					trf.DESTINATARIO = [];
					trf.NOTIFICADO 	 = [];

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
						ctrl.create29011.tarefa.addCampo(trf, false);
				}
			}
		}
	}