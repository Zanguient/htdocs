
	_29013IndexController.$inject = ['_29013IndexService', '$scope', '$timeout'];

	function _29013IndexController(_29013IndexService, $scope, $timeout) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarWorkflowItem 	= consultarWorkflowItem;
		ctrl.exibirItem				= exibirItem;
		ctrl.fluxograma				= fluxograma;
		ctrl.fecharModal			= fecharModal;

		// VARIÁVEIS
		ctrl.filtro 			= {};
		ctrl.filtro.dataIni 	= moment().subtract(1, "month").toDate();
		ctrl.filtro.dataFim 	= moment().toDate();
		ctrl.workflowItem		= [];
		ctrl.infoGeral 			= {};
		ctrl.tarefaPorUsuario 	= [];

		// MÉTODOS

		this.$onInit = function() {

			ctrl.filtro.workflowItemId 	= parseInt(getURLParameter('workflowItemId'));
		};

		// Consultar workflow (filtro inicial) quando o id do workflow for passado na URL.
		$scope.$watch('$ctrl.filtro.workflowItemId', function(newValue, oldValue, scope) {
            
            if ( newValue > 0 ) {

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

			_29013IndexService
				.consultarWorkflowItem(param)
				.then(function(response) {
					
					ctrl.workflowItem = response;

					var item = null;

					for (var i in ctrl.workflowItem) {

						item = ctrl.workflowItem[i];

						item.DATAHORA_INSERT_FORMATADO = moment(item.DATAHORA_INSERT).format('DD/MM/YYYY HH:mm:ss');
					}
				});

		}

		function exibirItem(workflow) {

			var tarefa 	  = [],
				usuarioId = parseInt( $('#usuario-id').val() );

			ctrl.tipoTela = 'exibir';
			consultarTarefa(workflow);

			function consultarTarefa(workflow) {

				var param = {
						WORKFLOW_ITEM_ID: workflow.ID
					};

				_29013IndexService
					.consultarWorkflowItemTarefa(param)
					.then(function(response) {

						tarefa = response;
						carregarInfoGeral();
						carregarTarefa();
						carregarTarefaPorUsuario();
						ctrl.fluxograma(tarefa.TAREFA);
						exibirModal();
					});
			}

			function carregarInfoGeral() {

				var infGrl = ctrl.infoGeral;

				angular.copy(workflow, infGrl);

				infGrl.WORKFLOW_MODELO 		  = {};
				infGrl.WORKFLOW_MODELO.ID 	  = infGrl.WORKFLOW_ID;
				infGrl.WORKFLOW_MODELO.TITULO = infGrl.WORKFLOW_TITULO;

				infGrl.DATAHORA_INI_PREVISTA  = moment(infGrl.DATAHORA_INI_PREVISTA).toDate();
				infGrl.DATAHORA_FIM_PREVISTA  = moment(infGrl.DATAHORA_FIM_PREVISTA).toDate();
			}

			function carregarTarefa() {

				var trf = {};

				for (var i in tarefa.TAREFA) {

					trf = tarefa.TAREFA[i];

					trf.TEMPO_PREVISTO_HUMANIZE  = moment.duration(parseFloat(trf.TEMPO_PREVISTO), "minutes").format('d[d] HH[h] mm[m] ss[s]');
					trf.TEMPO_CONCLUSAO_HUMANIZE = moment.duration(parseFloat(trf.TEMPO_CONCLUSAO), "minutes").format('d[d] HH[h] mm[m] ss[s]');

					trf.DATAHORA_INI_PREVISTA  		= moment(trf.DATAHORA_INI_PREVISTA).format('DD/MM/YYYY HH:mm');
					trf.DATAHORA_FIM_PREVISTA  		= moment(trf.DATAHORA_FIM_PREVISTA).format('DD/MM/YYYY HH:mm');
					trf.DATAHORA_INI_RECALCULADA	= trf.DATAHORA_INI_RECALCULADA 	? moment(trf.DATAHORA_INI_RECALCULADA).format('DD/MM/YYYY HH:mm') : '';
					trf.DATAHORA_FIM_RECALCULADA	= trf.DATAHORA_FIM_RECALCULADA 	? moment(trf.DATAHORA_FIM_RECALCULADA).format('DD/MM/YYYY HH:mm') : '';
					trf.DATAHORA_INI_CONCLUSAO 		= trf.DATAHORA_INI_CONCLUSAO 	? moment(trf.DATAHORA_INI_CONCLUSAO).format('DD/MM/YYYY HH:mm') 	 : '';
					trf.DATAHORA_FIM_CONCLUSAO 		= trf.DATAHORA_FIM_CONCLUSAO 	? moment(trf.DATAHORA_FIM_CONCLUSAO).format('DD/MM/YYYY HH:mm') 	 : '';

					calcularEficienciaTempo(trf);
					carregarTarefaDestinatario(trf);
					carregarTarefaNotificado(trf);
					carregarTarefaComentario(trf);
					carregarTarefaMovimentacao(trf);
				}

				ctrl.tarefa = tarefa.TAREFA;

				function calcularEficienciaTempo(trf) {

					var tempoPrevisto  = parseFloat(trf.TEMPO_PREVISTO),
						tempoConclusao = parseFloat(trf.TEMPO_CONCLUSAO);

					// Se o tempo de conclusão for menor do que o previsto, a eficiência será 100%.
					if (tempoConclusao <= tempoPrevisto)
						trf.PERCENTUAL_EFICIENCIA_TEMPO = 100;
					else {
						trf.PERCENTUAL_EFICIENCIA_TEMPO = 100 - (((tempoConclusao * 100) / tempoPrevisto) - 100);
						trf.PERCENTUAL_EFICIENCIA_TEMPO = ((trf.PERCENTUAL_EFICIENCIA_TEMPO < 0) || isNaN(trf.PERCENTUAL_EFICIENCIA_TEMPO))
															? 0 
															: trf.PERCENTUAL_EFICIENCIA_TEMPO;
					}

					trf.PERCENTUAL_EFICIENCIA_TEMPO = trf.PERCENTUAL_EFICIENCIA_TEMPO.toFixed(2);
				}

				function carregarTarefaDestinatario(trf) {

					var destin = {};

					trf.DESTINATARIO = [];

					for (var j in tarefa.DESTINATARIO) {

						destin = tarefa.DESTINATARIO[j];

						if (trf.ID == destin.WORKFLOW_ITEM_TAREFA_ID)
							trf.DESTINATARIO.push(destin);
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

				function carregarTarefaComentario(trf) {

					var coment = {};
					trf.COMENTARIO = [];

					for (var i in tarefa.COMENTARIO) {

						coment = tarefa.COMENTARIO[i];

						if (trf.ID == coment.WORKFLOW_ITEM_TAREFA_ID) {
							trf.COMENTARIO.push(coment);
						}
					}
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
			}

			function carregarTarefaPorUsuario() {

				var trf  = {},
					dest = {};

				for (var i in ctrl.tarefa) {

					trf = ctrl.tarefa[i];

					for (var j in trf.DESTINATARIO) {

						dest = trf.DESTINATARIO[j];

						if (dest.USUARIO_ID == parseInt(document.getElementById('usuario-id').value)) {

							ctrl.tarefaPorUsuario.push(trf);
						}
					}
				}
			}

			function exibirModal() {

				$('#modal-create')
					.modal('show')
					.removeData('workflowId')
					.data('workflowId', workflow.ID);	// Definindo id do workflow no modal para ser utilizado ao fechá-lo.
			}

		}

		/**
		 * Gerar fluxograma.
		 * Utiliza o plugin raphael.min.js e flowchart.min.js.
		 */
		function fluxograma(tarefaAll) {

			var trf 	 = {},
				conteudo = '',
				ordem 	 = 'ini',
				status 	 = '';

			$('#diagram').empty();

			// Ordenar tarefa por SEQUENCIA e ID.
			tarefaAll = tarefaAll.sort(function(a, b) { return a.SEQUENCIA - b.SEQUENCIA || a.ID - b.ID });

			for (var i in tarefaAll) {

				trf = tarefaAll[i];
				i   = parseInt(i);

				switch (trf.STATUS_CONCLUSAO) {
					case '0' || '4'	: status = '|past'; 	break;
					case '1'		: status = '|current'; 	break;
					case '2'		: status = '|request'; 	break;
					case '3'		: status = '|approved';
				}
				
				if (trf.PONTO_REPROVACAO > 0) {
					
					//'cond1=>condition: Yes?:>http://#cond1\n'+
					conteudo += 'cond'+ trf.ORDEM +'=>condition: '+ trf.TITULO + status +':>#heading-'+ i +'\n';

					//'st->op1->op2->cond\n'
					//'cond(yes)->op3\n'+
					//'cond(no)->op1'
					ordem += '->cond'+ trf.ORDEM +'\n';
					ordem += 'cond'+ trf.ORDEM +'(no)->op'+ trf.PONTO_REPROVACAO +'\n';
					ordem += 'cond'+ trf.ORDEM +'(yes)';
				}
				else {

					//'op1=>operation: My Operation:>http://#op1\n'+
					conteudo += 'op'+ trf.ORDEM +'=>operation: '+ trf.TITULO + status +':>#heading-'+ i +'\n';

					//'st->op1->op2->e\n'
					ordem += '->op'+ trf.ORDEM;
				}
			}

			ordem += '->fim\n';

			setTimeout(function() {

				var diagram = flowchart.parse(
								'ini=>start: Início\n'+
								'fim=>end: Fim\n'+
								conteudo +
								ordem);

				diagram.drawSVG('diagram', {
					'yes-text'	 : 'S',
                    'no-text'	 : 'N',
                    'line-width' : 2,
                  	'line-length': 20,
                    'fill'		 : 'white',
                    'font-size'  : '11px',
					// even flowstate support
                    'flowstate'  : {
                    	'past'	  : {	// parado
                    		'fill': 'rgb(217, 83, 79)'
                    	},
                    	'current' : {	// iniciado
                    		'fill': 'rgb(51, 122, 183)'
                    	},
                    	'request' : {	// pausado
                    		'fill': 'rgb(240, 173, 78)'
                    	},
                    	'approved': {	// concluido
                    		'fill': 'rgb(92, 184, 92)'
                    	}
                    }
				});


				// Abrir detalhamento da tarefa.
				$('#diagram a').click(function(e) {

					e.preventDefault();

					var idPanel = $(this).attr('href'),
						heading = $('.panel-heading'+idPanel);

					$(heading).children('a').click();

					// Scroll to.
					setTimeout(function() {

						$('.detalhe')
							.scrollTop(0)
							.scrollTop($(heading).position().top - 5);

					}, 500);

				});

			}, 500);
		}

		/**
		 * Fechar modal.
		 */
		function fecharModal() {
			
			$('#modal-create')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast');

			ctrl.tarefaPorUsuario = [];
		}

	}