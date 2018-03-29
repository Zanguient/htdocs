
	_29011IndexController.$inject = ['_29011IndexService', '_29011TarefaService', '$scope', '$timeout'];

	function _29011IndexController(_29011IndexService, _29011TarefaService, $scope, $timeout) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarItem 	= consultarItem;
		ctrl.exibirItem		= exibirItem;
		ctrl.ativarIncluir 	= ativarIncluir;
		ctrl.limparCampo 	= limparCampo;

		// VARIÁVEIS
		ctrl.tipoTela 		= 'incluir';
		ctrl.filtro 		= {};
		ctrl.filtro.dataIni = moment().subtract(1, "month").toDate();
		ctrl.filtro.dataFim = moment().toDate();
		ctrl.workflowItem	= [];

		// MÉTODOS

		this.$onInit = function() {

			ctrl.filtro.workflowItemId 	= parseInt(getURLParameter('workflowItemId'));
			ctrl.filtro.tarefaId 		= parseInt(getURLParameter('tarefaId'));
		};

		// Consultar workflow (filtro inicial) quando o id do workflow for passado na URL.
		$scope.$watch('$ctrl.filtro.workflowItemId', function(newValue, oldValue, scope) {
            
            if (newValue > 0) {

                $timeout(function() {
					ctrl.consultarItem();
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
		function consultarItem() {

			var param = {
					DATA_INI		: moment(ctrl.filtro.dataIni).format('DD.MM.YYYY')+' 00:00:00',
					DATA_FIM		: moment(ctrl.filtro.dataFim).format('DD.MM.YYYY')+' 23:59:59',
					STATUS_CONCLUSAO: ctrl.filtro.status ? ctrl.filtro.status : null,
					WORKFLOW_ITEM_ID: ctrl.filtro.workflowItemId ? ctrl.filtro.workflowItemId : null
				};

			_29011IndexService
				.consultarItem(param)
				.then(function(response) {
					ctrl.workflowItem = response;
				});
		}

		function exibirItem(workflow, gravarFechar) {

			var tarefa = [];

			gravarFechar = (typeof gravarFechar == 'undefined') ? true : gravarFechar;

			if (gravarFechar)
				ctrl.tipoTela = 'exibir';

			consultarTarefa(workflow);

			function consultarTarefa(workflow) {

				var param = {
						WORKFLOW_ITEM_ID: workflow.ID
					};

				_29011TarefaService
					.consultarItemTarefa(param)
					.then(function(response) {

						tarefa = response;
						carregarInfoGeral();
						carregarTarefa();

						if (gravarFechar)
							exibirModal();
					});
			}

			function carregarInfoGeral() {

				var infGrl = ctrl.create.infoGeral.infoGeral;

				angular.copy(workflow, infGrl);

				infGrl.WORKFLOW_MODELO 		  = {};
				infGrl.WORKFLOW_MODELO.ID 	  = infGrl.WORKFLOW_ID;
				infGrl.WORKFLOW_MODELO.TITULO = infGrl.WORKFLOW_TITULO;

				infGrl.DATAHORA_INI_PREVISTA  	 = moment(infGrl.DATAHORA_INI_PREVISTA).toDate();
				infGrl.DATAHORA_FIM_PREVISTA  	 = moment(infGrl.DATAHORA_FIM_PREVISTA).toDate();
				infGrl.DATAHORA_INI_PREVISTA_TMP = infGrl.DATAHORA_INI_PREVISTA;
				infGrl.DATAHORA_FIM_PREVISTA_TMP = infGrl.DATAHORA_FIM_PREVISTA;
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
					carregarTarefaNotificado(trf);
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

						if (parseInt(t.PONTO_REPROVACAO) == parseInt(trf.ORDEM)) {

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

						if (trf.ID == destin.WORKFLOW_ITEM_TAREFA_ID) {
							trf.DESTINATARIO.push(destin);
						}
					}
				}

				function carregarTarefaNotificado(trf) {

					var notif 		= {};
					trf.NOTIFICADO 	= [];

					for (var j in tarefa.NOTIFICADO) {

						notif = tarefa.NOTIFICADO[j];

						if (trf.ID == notif.WORKFLOW_ITEM_TAREFA_ID) {
							trf.NOTIFICADO.push(notif);
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