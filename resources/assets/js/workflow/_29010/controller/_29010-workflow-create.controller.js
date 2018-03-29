
	WorkflowCreateController.$inject = ['WorkflowCreateService', '$scope'];

	function WorkflowCreateController(WorkflowCreateService, $scope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.store	 	  = store;
		ctrl.excluir 	  = excluir;
		ctrl.limparCampo  = limparCampo;
		ctrl.fecharModal  = fecharModal;

		// VARIÁVEIS
		ctrl.gravarFechar = false;

		// MÉTODOS

		/**
		 * Gravar.
		 */
		function store() {

			ctrl.tarefa.tarefa = ctrl.tarefa.tarefa.sort(function(a, b) { return a.SEQUENCIA - b.SEQUENCIA || a.ID - b.ID });

			verificarCampoVazio();
			formataCampo();

			var dados = {
					WORKFLOW: ctrl.infoGeral.infoGeral,
					TAREFA 	: ctrl.tarefa.tarefa
				};

	        WorkflowCreateService
	        	.store(dados)
		        .then(
		        	function(resp) {
		            
			            showSuccess('Gravado com sucesso.');

			            if (ctrl.gravarFechar == true) {

			            	ctrl.workflowIndex29010.consultarWorkflow();
			            	ctrl.fecharModal(false);
			            }
			            else {
			            	ctrl.workflowIndex29010.exibirWorkflow(resp.data, ctrl.gravarFechar);
			            }
			        }, 
			        function(resp) {

			            showErro('Erro ao gravar ('+ resp.status +')'); 
			        },
                    function(e) {

                        progressPagina(e);                  
                    }
                );
		

		    function verificarCampoVazio() {

		    	var msg  	 = '',
		    		erro 	 = false,
	    			trf 	 = {},
	    			trfIndex = '';

	    		for (var i in ctrl.tarefa.tarefa) {

	    			trf = ctrl.tarefa.tarefa[i];

	    			if (trf.STATUS_EXCLUSAO == '1')
		    			continue;

	    			trfIndex = (parseInt(i)+1).toString().padStart(3,'0');

	    			if (typeof trf.TITULO == 'undefined' || trf.TITULO === '') {

	    				msg  = 'Digite um título para a tarefa '+ trfIndex +'.';
	    				erro = true;
	    			}
	    			else if (typeof trf.DESCRICAO == 'undefined' || trf.DESCRICAO === '') {

	    				msg  = 'Digite uma descrição para a tarefa '+ trfIndex +'.';
	    				erro = true;
	    			}
	    			else if (typeof trf.SEQUENCIA == 'undefined' || trf.SEQUENCIA === '') {

	    				msg  = 'Digite uma sequência para a tarefa '+ trfIndex +'.';
	    				erro = true;
	    			}
	    			else if (
	    				typeof trf.TEMPO_PREVISTO_HORA 		== 'undefined' || trf.TEMPO_PREVISTO_HORA 		=== '' &&
	    				typeof trf.TEMPO_PREVISTO_MINUTO 	== 'undefined' || trf.TEMPO_PREVISTO_MINUTO 	=== ''
	    			) {

	    				msg  = 'Digite o tempo previsto para a tarefa '+ trfIndex +'.';
	    				erro = true;
	    			}


			    	if (erro) {

			    		$('#tab-tarefa li:nth-of-type('+ (parseInt(i)+1) +') a').tab('show');
			    		break;
			    	}
			    }

		    	if (erro) {

			    	showAlert(msg);
			    	throw msg;
			    }
		    }

		    function formataCampo() {

		    	var trf = {};

	    		for (var i in ctrl.tarefa.tarefa) {

	    			trf = ctrl.tarefa.tarefa[i];

	    			trf.TEMPO_PREVISTO_HORA 	= trf.TEMPO_PREVISTO_HORA 		? trf.TEMPO_PREVISTO_HORA 		: 0;
	    			trf.TEMPO_PREVISTO_MINUTO 	= trf.TEMPO_PREVISTO_MINUTO 	? trf.TEMPO_PREVISTO_MINUTO 	: 0;
	    			trf.TEMPO_PREVISTO_SEGUNDO 	= trf.TEMPO_PREVISTO_SEGUNDO 	? trf.TEMPO_PREVISTO_SEGUNDO 	: 0;

	    			// 'Montar' campo tempo previsto
	    			trf.TEMPO_PREVISTO = trf.TEMPO_PREVISTO_HORA +':'+ trf.TEMPO_PREVISTO_MINUTO +':'+ trf.TEMPO_PREVISTO_SEGUNDO;
	    			trf.TEMPO_PREVISTO = moment.duration(trf.TEMPO_PREVISTO).asMinutes().toFixed(4);
	    		}
	    	}
		}

		/**
		 * Excluir.
		 */
		function excluir() {

			confirmar();

			function confirmar() {

				addConfirme(
					'<h4>Confirmação</h4>',
		        	'Confirma a exclusão?',
		        	[obtn_sim, obtn_nao],
		        	[
		            	{
		            		ret: 1,
		            		func: function() {

		            			efetivar();
		            		}
		            	},
		            	{
		            		ret: 2,
		            		func: function() {}
		            	}
		        	]
		        );
			}

			function efetivar() {
			
				var param = {
						WORKFLOW_ID	: ctrl.infoGeral.infoGeral.ID,
						TAREFA 		: ctrl.tarefa.tarefa
					};

				WorkflowCreateService
					.excluir(param)
					.then(function(response) {
						
						showSuccess('Excluído com sucesso.');
						ctrl.workflowIndex29010.consultarWorkflow();
						ctrl.fecharModal();
					});
			}
		}

		/**
		 * Limpar campos.
		 */
		function limparCampo() {

			setTimeout(function() {		// para aplicar scope.apply

				ctrl.infoGeral.infoGeral 		= {};
				ctrl.infoGeral.infoGeral.STATUS = '1';

				ctrl.tarefa.tarefa = [];
				ctrl.tarefa.addTarefa();

				ctrl.tarefa.excluirArquivoTmpPorUsuario();

				$scope.$apply();
			});
		}

		/**
		 * Fechar modal.
		 */
		function fecharModal(confirmar) {

			if (typeof confirmar == 'undefined')					
				confirmar = (ctrl.tipoTela == 'exibir') ? false : true;

			if (confirmar && verificarCampoAlterado()) {

				addConfirme(
					'<h4>Confirmação</h4>',
		        	'Os dados serão perdidos. Deseja continuar?',
		        	[obtn_sim, obtn_nao],
		        	[
		            	{
		            		ret: 1,
		            		func: function() {

	            				fechar();
	            				ctrl.limparCampo();
		            		}
		            	},
		            	{
		            		ret: 2,
		            		func: function() {}
		            	}
		        	]
		        );
			}
			else {

				fechar();
				ctrl.limparCampo();
			}


			/**
			 * Verificar se algum campo foi alterado.
			 */
			function verificarCampoAlterado() {

				var ret 		= false,
					infoGeral 	= ctrl.infoGeral.infoGeral,
					tarefa 		= ctrl.tarefa.tarefa;

				if ( (infoGeral.TITULO !== undefined && infoGeral.TITULO !== '')
				 	|| (infoGeral.DESCRICAO !== undefined && infoGeral.DESCRICAO !== '') ) {
					ret = true;
				}
				else if (tarefa.length > 0) {

					var trf = {};

					for (var i in tarefa) {

						trf = tarefa[i];

						if ( (trf.TITULO !== undefined && trf.TITULO !== '') 
							|| (trf.DESCRICAO !== undefined && trf.DESCRICAO !== '') 
							|| (trf.DESTINATARIO.length > 0) )
							ret = true;
						
					}					
				}

				return ret;
			}

			function fechar() {
			
				$('#modal-create')
					.modal('hide')
					.find('.modal-body')
					.animate({ scrollTop: 0 }, 'fast')
				;
			}
		}

	}