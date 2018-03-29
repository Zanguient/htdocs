
	_29011CreateController.$inject = ['_29011CreateService', '$scope'];

	function _29011CreateController(_29011CreateService, $scope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS)
		ctrl.gravar	 	  = gravar;
		ctrl.excluir 	  = excluir;
		ctrl.encerrar 	  = encerrar;
		ctrl.limparCampo  = limparCampo;
		ctrl.fecharModal  = fecharModal;

		// VARIÁVEIS
		ctrl.gravarFechar = false;

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.index29011.create = this;
		};

		// MÉTODOS

		/**
		 * Gravar.
		 */
		function gravar() {

			ctrl.tarefa.tarefa = ctrl.tarefa.tarefa.sort(function(a, b) { return a.SEQUENCIA - b.SEQUENCIA || a.ID - b.ID });

			verificarCampoVazio();
			formataCampo();

			var dados = {
					WORKFLOW: ctrl.infoGeral.infoGeral,
					TAREFA 	: ctrl.tarefa.tarefa
				};

	        _29011CreateService
	        	.gravar(dados)
		        .then(
		        	function(resp) {
		            
			            showSuccess('Gravado com sucesso.');
			            
			            if (ctrl.gravarFechar == true) {

			            	ctrl.index29011.consultarItem();
			            	ctrl.fecharModal(false);
			            }
			            else {
			            	
			            	var wkf 					= resp.data;
			            	wkf.DATAHORA_INI_PREVISTA 	= moment(wkf.DATAHORA_INI_PREVISTA_TMP);
			            	wkf.DATAHORA_FIM_PREVISTA 	= moment(wkf.DATAHORA_FIM_PREVISTA_TMP);
			            	wkf.WORKFLOW_TITULO 		= wkf.WORKFLOW_MODELO.TITULO;

			            	ctrl.index29011.exibirItem(wkf, ctrl.gravarFechar);
			            }
			        }, 
			        function(resp) {

			            showErro('Erro ao gravar ('+ resp.status +')'); 
			        },
                    function (e) {

                        progressPagina(e);
                    }
			    );


		    function verificarCampoVazio() {

		    	var msg  = '',
		    		erro = false,
		    		modelo = ctrl.infoGeral.infoGeral.WORKFLOW_MODELO;

		    	if (typeof modelo == 'undefined' || angular.equals(modelo, {}) || modelo == null) {

		    		msg  = 'Escolha um modelo de workflow.';
		    		erro = true;
		    	}
		    	else {

		    		var trf 	 = {},
		    			trfIndex = '';

		    		for (var i in ctrl.tarefa.tarefa) {

		    			trf = ctrl.tarefa.tarefa[i];

		    			if (trf.STATUSEXCLUSAO == '1')
		    				continue;

		    			trfIndex = (parseInt(i)+1).toString().padStart(3,'0');

		    			if (typeof trf.TITULO == 'undefined' || trf.TITULO === '' || trf.TITULO == null) {

		    				msg  = 'Digite um título para a tarefa '+ trfIndex +'.';
		    				erro = true;
		    			}
		    			else if (typeof trf.DESCRICAO == 'undefined' || trf.DESCRICAO === '' || trf.DESCRICAO == null) {

		    				msg  = 'Digite uma descrição para a tarefa '+ trfIndex +'.';
		    				erro = true;
		    			}
		    			else if (typeof trf.SEQUENCIA == 'undefined' || trf.SEQUENCIA === '' || trf.SEQUENCIA == null) {

		    				msg  = 'Digite uma sequência para a tarefa '+ trfIndex +'.';
		    				erro = true;
		    			}
			    		else if (typeof trf.DESTINATARIO == 'undefined' || trf.DESTINATARIO.length == 0 || trf.DESTINATARIO == null) {

				    		msg  = 'Escolha um destinatário para a tarefa '+ trfIndex +'.';
				    		erro = true;
				    	}
				    	else if (
		    				(typeof trf.TEMPO_PREVISTO_HORA 	== 'undefined' || trf.TEMPO_PREVISTO_HORA 		=== '' || parseInt(trf.TEMPO_PREVISTO_HORA) 	== 0) &&
		    				(typeof trf.TEMPO_PREVISTO_MINUTO 	== 'undefined' || trf.TEMPO_PREVISTO_MINUTO 	=== '' || parseInt(trf.TEMPO_PREVISTO_MINUTO) 	== 0)
		    			) {

		    				msg  = 'Digite o tempo previsto para a tarefa '+ trfIndex +'.';
		    				erro = true;
		    			}
		    			else if (typeof trf.HORARIO_PERMITIDO == 'undefined' || trf.HORARIO_PERMITIDO === '' || trf.HORARIO_PERMITIDO == null) {

		    				msg  = 'Digite os horários permitidos para a tarefa '+ trfIndex +'.';
		    				erro = true;
		    			}

				    	if (erro) {

				    		$('#tab-tarefa li:nth-of-type('+ (parseInt(i)+1) +') a').tab('show');
				    		break;
				    	}
				    }
		    	}

		    	if (erro) {

			    	showAlert(msg);
			    	throw msg;
			    }
		    }

		    function formataCampo() {

		    	var infoGeral = ctrl.infoGeral.infoGeral,
		    		trf 	  = {};

		    	// Formata datetime de 'Data Prevista Inicial'.
		    	infoGeral.DATAHORA_INI_PREVISTA = infoGeral.DATAHORA_INI_PREVISTA_TMP
		    										? moment(infoGeral.DATAHORA_INI_PREVISTA_TMP).format('DD.MM.YYYY HH:mm:ss')
		    										: null;

		    	// Formata datetime de 'Data Prevista Final'.
		    	infoGeral.DATAHORA_FIM_PREVISTA = infoGeral.DATAHORA_FIM_PREVISTA_TMP
		    										? moment(infoGeral.DATAHORA_FIM_PREVISTA_TMP).format('DD.MM.YYYY HH:mm:ss')
		    										: null;

	    		for (var i in ctrl.tarefa.tarefa) {

	    			trf = ctrl.tarefa.tarefa[i];

	    			trf.TEMPO_PREVISTO_HORA 	= trf.TEMPO_PREVISTO_HORA 		? trf.TEMPO_PREVISTO_HORA 		: 0;
	    			trf.TEMPO_PREVISTO_MINUTO 	= trf.TEMPO_PREVISTO_MINUTO 	? trf.TEMPO_PREVISTO_MINUTO 	: 0;
	    			trf.TEMPO_PREVISTO_SEGUNDO 	= trf.TEMPO_PREVISTO_SEGUNDO 	? trf.TEMPO_PREVISTO_SEGUNDO 	: 0;

	    			// 'Montar' campo tempo previsto
	    			trf.TEMPO_PREVISTO = trf.TEMPO_PREVISTO_HORA +':'+ trf.TEMPO_PREVISTO_MINUTO +':'+ trf.TEMPO_PREVISTO_SEGUNDO;
	    			trf.TEMPO_PREVISTO = moment.duration(trf.TEMPO_PREVISTO).asMinutes().toFixed(4);

	    			trf.DOMINGO = (trf.DOMINGO 	== null) ? '0' : trf.DOMINGO;
	    			trf.SEGUNDA = (trf.SEGUNDA 	== null) ? '0' : trf.SEGUNDA;
	    			trf.TERCA 	= (trf.TERCA 	== null) ? '0' : trf.TERCA;
	    			trf.QUARTA 	= (trf.QUARTA 	== null) ? '0' : trf.QUARTA;
	    			trf.QUINTA	= (trf.QUINTA 	== null) ? '0' : trf.QUINTA;
	    			trf.SEXTA 	= (trf.SEXTA 	== null) ? '0' : trf.SEXTA;
	    			trf.SABADO 	= (trf.SABADO 	== null) ? '0' : trf.SABADO;
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
						WORKFLOW_ITEM_ID: ctrl.infoGeral.infoGeral.ID,
						TAREFA 			: ctrl.tarefa.tarefa
					};

				_29011CreateService
					.excluir(param)
					.then(function(response) {
						
						showSuccess('Excluído com sucesso.');
						ctrl.index29011.consultarItem();
						ctrl.fecharModal();
					});
			}
		}

		/**
		 * Encerrar.
		 */
		function encerrar() {

			var param = {
					WORKFLOW_ITEM_ID: ctrl.infoGeral.infoGeral.ID,
					STATUS_CONCLUSAO: (ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO != '3') ? '3' : 0
				};

			_29011CreateService
				.encerrar(param)
				.then(function(response) {
					
					ctrl.index29011.consultarItem();
					
					if (ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO != '3') {
						ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO = '3';
						showSuccess('Encerrado com sucesso.');
					}
					else if (ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO == '3') {
						ctrl.infoGeral.infoGeral.STATUS_CONCLUSAO = '0';
						showSuccess('Cancelamento de encerramento realizado com sucesso.');
					}
				});
		}

		/**
		 * Limpar campos.
		 */
		function limparCampo() {

			setTimeout(function() {		// para aplicar scope.apply

				ctrl.infoGeral.infoGeral = {};
				ctrl.infoGeral.STATUS 	 = '1';
				ctrl.tarefa.tarefa 		 = [];

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