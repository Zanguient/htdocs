	
	_29011TarefaController.$inject = ['_29011TarefaService', '$scope'];

	function _29011TarefaController(_29011TarefaService, $scope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS)

		ctrl.addTarefa 							= addTarefa;
		ctrl.replicarTarefa 					= replicarTarefa;
		ctrl.excluirTarefa 						= excluirTarefa;
		ctrl.verificarPontoRetorno				= verificarPontoRetorno;
		ctrl.exibirModalOrdenar					= exibirModalOrdenar;
		ctrl.focoInputOrdenar					= focoInputOrdenar;
		ctrl.fecharModalOrdenar					= fecharModalOrdenar;
		ctrl.eventoInputOrdenar 				= eventoInputOrdenar;
		ctrl.reordenarSequencia 				= reordenarSequencia;
		ctrl.confirmarOrdenacao 				= confirmarOrdenacao;
		ctrl.addArquivo 						= addArquivo;
		ctrl.processarArquivo 					= processarArquivo;
		ctrl.excluirArquivo 		 			= excluirArquivo;
		ctrl.excluirArquivoTmpPorUsuario		= excluirArquivoTmpPorUsuario;
		ctrl.listarUsuario 						= listarUsuario;
		ctrl.incluirDestinatario 				= incluirDestinatario;
		ctrl.selecionarUsuario 					= selecionarUsuario;
		ctrl.verificarDestinatarioExiste 		= verificarDestinatarioExiste;
		ctrl.selecionarDestinatarioEscolhido	= selecionarDestinatarioEscolhido;
		ctrl.excluirDestinatarioEscolhido 		= excluirDestinatarioEscolhido;
		ctrl.incluirNotificado  				= incluirNotificado;
		ctrl.selecionarUsuarioNotificado		= selecionarUsuarioNotificado;
		ctrl.verificarNotificadoExiste  		= verificarNotificadoExiste;
		ctrl.selecionarNotificadoEscolhido		= selecionarNotificadoEscolhido;
		ctrl.excluirNotificadoEscolhido 		= excluirNotificadoEscolhido;
		ctrl.addCampo 							= addCampo;
		ctrl.excluirCampo 						= excluirCampo;
		ctrl.fecharModalPesqUsuario 			= fecharModalPesqUsuario;
		ctrl.fecharModalPesqUsuarioNotificado	= fecharModalPesqUsuarioNotificado;
		ctrl.alterarEmailUsuario 				= alterarEmailUsuario;
		ctrl.gravarEmailUsuario 				= gravarEmailUsuario;
		ctrl.cancelarAlterarEmailUsuario 		= cancelarAlterarEmailUsuario;
		ctrl.fecharModalAlterarEmailUsuario 	= fecharModalAlterarEmailUsuario;

		
		// VARIÁVEIS

		ctrl.tarefa 		= [];
		ctrl.tarefaAtual 	= {};

		ctrl.tarefaPadrao 	= {

			SEQUENCIA	: 1,
			STATUS 		: 1,
			DESCRICAO 	: '',

			TEMPO_PREVISTO_HORA 	: 0,
			TEMPO_PREVISTO_MINUTO 	: 0,

			DOMINGO : '0',
			SEGUNDA : '1',
			TERCA 	: '1',
			QUARTA 	: '1',
			QUINTA 	: '1',
			SEXTA 	: '1',
			SABADO 	: '0',

			HORARIO_PERMITIDO	: '07:00-11:50;13:02-17:00',

			PONTO_REPROVACAO	: '',

			DESTINATARIO 		: [],
			DESTINATARIO_SELEC 	: [], 	// para manipulação na view

			NOTIFICADO 			: [],
			NOTIFICADO_SELEC	: [], 	// para manipulação na view

			CAMPO: [{
				ROTULO	: null,
				TIPO	: '1'
			}],

			ARQUIVO: [{
				NOME 	: null,
				TABELA 	: null,
				TIPO 	: null,
				TAMANHO	: null,
				BINARIO	: null,
				CONTEUDO: null
			}],

			ARQUIVO_EXCLUIR: []
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

		ctrl.listaUsuario = [];

		ctrl.emailUsuario = {
			NOME : null,
			EMAIL: null
		};

		ctrl.emailUsuarioBKP = {};

		var ordenar = true;


		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.index29011.tarefa  = this;
			ctrl.create29011.tarefa = this;
		};

		
		// MÉTODOS

		function addTarefa() {

			var tarefaNova = {},
				tarefaOrd  = [];
				
			angular.copy(ctrl.tarefaPadrao, tarefaNova);

			tarefaOrd = ctrl.tarefa.sort(function(a, b) { return a.SEQUENCIA - b.SEQUENCIA || a.ID - b.ID });
			
			tarefaNova.SEQUENCIA = (ctrl.tarefa.length == 0) ? 1 : tarefaOrd[tarefaOrd.length-1].SEQUENCIA+1;
			tarefaNova.ORDEM 	 = ctrl.tarefa.length+1;

			ctrl.tarefa.push(tarefaNova);

			// Foco na última tarefa.
			setTimeout(function() {
				$('#tab-tarefa').scrollLeft( $('#tab-tarefa')[0].scrollWidth );
				$('#tab-tarefa a:last').tab('show');
			}, 100);
		}

		function replicarTarefa(tarefa) {

			var tarefaNova  	= {},
				destin 			= {},
				notif 			= {},
				campo 			= {},
				arquivoNovo 	= {},
				existiaArquivo 	= false;
			
			angular.copy(tarefa, tarefaNova);

			// Tratamento na tarefa replicada.
			delete tarefaNova.ID;
			delete tarefaNova.WORKFLOW_ID;
			delete tarefaNova.WORKFLOW_ITEM_ID;
			delete tarefaNova.STATUSEXCLUSAO;			
			tarefaNova.SEQUENCIA = ctrl.tarefa[ctrl.tarefa.length-1].SEQUENCIA+1;
			tarefaNova.ORDEM 	 = ctrl.tarefa.length+1;

			// Tratamento nos destinatários.
			for (var i in tarefaNova.DESTINATARIO) {

				destin = tarefaNova.DESTINATARIO[i];
				delete destin.ID;
				delete destin.WORKFLOW_ID;
				delete destin.WORKFLOW_ITEM_ID;
				delete destin.WORKFLOW_ITEM_TAREFA_ID;
				delete destin.STATUSEXCLUSAO;
			}

			// Tratamento nos notificados.
			for (var i in tarefaNova.NOTIFICADO) {

				notif = tarefaNova.NOTIFICADO[i];
				delete campo.ID;
				delete campo.WORKFLOW_ID;
				delete campo.WORKFLOW_ITEM_ID;
				delete campo.WORKFLOW_ITEM_TAREFA_ID;
				delete campo.STATUSEXCLUSAO;
			}

			// Tratamento nos campos.
			for (var i in tarefaNova.CAMPO) {

				campo = tarefaNova.CAMPO[i];
				delete campo.ID;
				delete campo.WORKFLOW_ID;
				delete campo.WORKFLOW_ITEM_ID;
				delete campo.WORKFLOW_ITEM_TAREFA_ID;
				delete campo.STATUSEXCLUSAO;
			}

			// Tratamento nos arquivos.
			existiaArquivo = (tarefaNova.ARQUIVO[0].BINARIO == null) ? false : true;

			angular.copy(ctrl.arquivoPadrao, arquivoNovo);

			tarefaNova.ARQUIVO = [];
			tarefaNova.ARQUIVO.push(arquivoNovo);

			// Insere tarefa replicada.
			ctrl.tarefa.push(tarefaNova);

			// Foco na última tarefa.
			setTimeout(function() {
				$('#tab-tarefa').scrollLeft( $('#tab-tarefa')[0].scrollWidth );
				$('#tab-tarefa a:last').tab('show');
			}, 100);

			if (existiaArquivo)
				showAlert('Por favor, insira novamente os arquivos, pois eles não podem ser replicados para a nova tarefa.');
		}

		function excluirTarefa(tarefa) {

			if (ctrl.tarefa.length > 1) {

				// Excluir destinatários da tarefa.
				for (var i in tarefa.DESTINATARIO) {

					// Só adiciona para excluir do banco de dados se o destinatário tiver ID, ou seja, já está gravado no banco.
					if (tarefa.DESTINATARIO[i].ID > 0)
						tarefa.DESTINATARIO[i].STATUSEXCLUSAO = '1';
				}

				// Excluir arquivos da tarefa.
				for (var i in tarefa.ARQUIVO) {

					// Só adiciona para excluir do banco de dados se o arquivo tiver ID, ou seja, já está gravado no banco.
					if (tarefa.ARQUIVO[i].ID > 0) {

						tarefa.ARQUIVO_EXCLUIR = (typeof tarefa.ARQUIVO_EXCLUIR != 'undefined') ? tarefa.ARQUIVO_EXCLUIR : [];
						tarefa.ARQUIVO_EXCLUIR.push(tarefa.ARQUIVO[i]);
					}
				}
			
				// Excluir tarefa.
				if (tarefa.ID > 0)
					tarefa.STATUSEXCLUSAO = '1';
				else
					ctrl.tarefa.splice(ctrl.tarefa.indexOf(tarefa), 1);

				ctrl.verificarPontoRetorno();

				// Foco na última tarefa.
				setTimeout(function() {
					$('#tab-tarefa').scrollLeft( $('#tab-tarefa')[0].scrollWidth );
					$('#tab-tarefa a:last').tab('show');
				}, 100);
			}
		}

		/**
		 * Verificar se a tarefa é um ponto de retorno de alguma outra tarefa que possa ser reprovada.
		 */
		function verificarPontoRetorno() {

			var trf = {},
				t 	= {};

			for (var i in ctrl.tarefa) {

				trf = ctrl.tarefa[i];

				if (trf.STATUSEXCLUSAO != '1') {

					for (var j in ctrl.tarefa) {

						t = ctrl.tarefa[j];
						trf.EH_PONTO_RETORNO = 0;

						if ( (t.STATUSEXCLUSAO != '1') && (parseInt(t.PONTO_REPROVACAO) == parseInt(trf.ORDEM)) ) {

							trf.EH_PONTO_RETORNO = 1;
							break;
						}
					}
				}
			}
		}

		function exibirModalOrdenar(tarefa) {

			var trf = null;

			for (var i in ctrl.tarefa) {

				trf = ctrl.tarefa[i];

				trf.SEQUENCIA_TMP = trf.SEQUENCIA;
			}

			$('#modal-ordenar').modal('show');

			setTimeout(function() {
				ctrl.focoInputOrdenar(tarefa);
			}, 500);
		}

		function focoInputOrdenar(tarefa) {

			$('#modal-ordenar .list-group .list-group-item.list-tarefa-'+tarefa.ID)
				.find('.input-reordenar-sequencia')
				.focus();
		}

		function fecharModalOrdenar() {

			ctrl.confirmarOrdenacao();

			$('#modal-ordenar')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast');
		}

		function eventoInputOrdenar(event, tarefa) {

			// enter, up, down
			if (event.keyCode !== 13 && event.keyCode !== 38 && event.keyCode !== 40)			
				ordenar = false;
			else {
				ordenar = true;

				// enter, tab
				if (event.keyCode === 13) {

					ctrl.confirmarOrdenacao();

					setTimeout(function() {
						ctrl.focoInputOrdenar(tarefa);
					});
				}
			}
		}

		/**
		 * Reordenar sequência das tarefas após alterar uma delas.
		 */
		function reordenarSequencia(tarefa, oldSequencia) {

			if (ordenar == false)
				return false;

			var trf = {};

			tarefa.SEQUENCIA_TMP = parseInt(tarefa.SEQUENCIA_TMP);
			oldSequencia 	 	 = parseInt(oldSequencia);

			for (var i in ctrl.tarefa) {

				trf = ctrl.tarefa[i];

				if ((trf.STATUSEXCLUSAO != '1') && (trf.ID != tarefa.ID)) {

					if (tarefa.SEQUENCIA_TMP < oldSequencia) {
						
						// Ajuste para as tarefas seguintes à posição inicial da tarefa alterada
						// para que não pule uma sequência, deixando vazia.
						if (trf.SEQUENCIA_TMP >= (oldSequencia+1))
							trf.SEQUENCIA_TMP = (trf.SEQUENCIA_TMP === 1) ? trf.SEQUENCIA_TMP : trf.SEQUENCIA_TMP-1;

						if (trf.SEQUENCIA_TMP >= tarefa.SEQUENCIA_TMP)
							trf.SEQUENCIA_TMP++;
					}
					else {

						// Ajuste para as tarefas anteriores à posição inicial da tarefa alterada
						// para que não pule uma sequência, deixando vazia.
						if (trf.SEQUENCIA_TMP <= (oldSequencia-1))
							trf.SEQUENCIA_TMP++;

						if (trf.SEQUENCIA_TMP <= tarefa.SEQUENCIA_TMP)
							trf.SEQUENCIA_TMP = (trf.SEQUENCIA_TMP === 1) ? trf.SEQUENCIA_TMP : trf.SEQUENCIA_TMP-1;
					}

					ctrl.confirmarOrdenacao();
				}
			}

			// foco no input de sequência
			setTimeout(function() {
				ctrl.focoInputOrdenar(tarefa);
			});
		}

		function confirmarOrdenacao() {

			setTimeout(function() {

				var trf = null;

				for (var i in ctrl.tarefa) {

					trf = ctrl.tarefa[i];

					// passa nova ordenação para a tarefa (abas).
					trf.SEQUENCIA = trf.SEQUENCIA_TMP;
				}

				$scope.$apply();

			});
		}

		function addArquivo(tarefa, focar) {

			focar = (typeof focar == 'undefined') ? true : focar;

			var arquivoNovo = {};
			angular.copy(ctrl.arquivoPadrao, arquivoNovo);
			tarefa.ARQUIVO.push(arquivoNovo);

			if (focar) {

				setTimeout(function() {
					$('.arquivo-container .scroll .form-group:last-of-type input').focus();
				}, 100);
			}
		}

		function processarArquivo(event, arquivo) {

			arquivo.NOME 	 = event.target.files[0].name;
			arquivo.TABELA 	 = 'TBWORKFLOW_ITEM_TAREFA';
			arquivo.TIPO 	 = event.target.files[0].type;
			arquivo.TAMANHO	 = event.target.files[0].size;
		}

		function excluirArquivo(tarefa, arquivo) {

			var indexArq = tarefa.ARQUIVO.indexOf(arquivo);

			// Só adiciona para excluir do banco de dados se o arquivo tiver ID, ou seja, já está gravado no banco.
			if (arquivo.ID > 0) {

				tarefa.ARQUIVO_EXCLUIR = (typeof tarefa.ARQUIVO_EXCLUIR != 'undefined') ? tarefa.ARQUIVO_EXCLUIR : [];
				tarefa.ARQUIVO_EXCLUIR.push(arquivo);
			}

			tarefa.ARQUIVO.splice(indexArq, 1);

			// Adiciona um arquivo vazio se não tiver mais nenhum outro.
			if (tarefa.ARQUIVO.length == 0)
				ctrl.addArquivo(tarefa);
		}

		function excluirArquivoTmpPorUsuario() {

			var param = {
				DIRETORIO: 'workflowTarefa'
			};

			_29011TarefaService.excluirArquivoTmpPorUsuario(param);
		}

		function listarUsuario() {

			_29011TarefaService
				.consultarUsuario()
				.then(function(response) {

					ctrl.listaUsuario = response;
				})
			;

		}

		/**
		 * Incluir destinatários.
		 */
		function incluirDestinatario(tarefa) {

			if (ctrl.listaUsuario.length == 0)
				ctrl.listarUsuario();

			ctrl.tarefaAtual = tarefa;

			setTimeout(function() {

				// Fix para vs-repeat.
				$('#modal-pesq-usuario .table-container-usuario')
					.trigger('resize')
					.scrollTop(0);

				// Foco no input de filtrar.
				$('#input-filtrar-usuario').select();

			}, 500);
		}

		/**
		 * Selecionar usuário (modal).
		 */
		function selecionarUsuario(usuario, $event) {

			var usuarioJaSelec = (ctrl.verificarDestinatarioExiste(usuario) > -1) ? true : false;

			// Não desmarcar caso o checkbox seja clicado e o usuário já esteja selecionado.
			if ( $($event.target).hasClass('chk-selec-usuario') && usuarioJaSelec ) {

				$event.preventDefault();
				return false;
			}
			// Não desmarcar caso o usuário já esteja selecionado.
			else if (usuarioJaSelec) {
				return false;
			}

			var indexDestin  = ctrl.tarefaAtual.DESTINATARIO.indexOf(usuario),
				destinatario = {};

			if (indexDestin > -1) {
				ctrl.tarefaAtual.DESTINATARIO.splice(indexDestin, 1);
			}
			else {
				destinatario.USUARIO_ID = usuario.ID;
				destinatario.USUARIO 	= usuario.USUARIO;
				destinatario.NOME 		= usuario.NOME;
				destinatario.SETOR 		= usuario.SETOR;
				destinatario.EMAIL 		= usuario.EMAIL;

				ctrl.tarefaAtual.DESTINATARIO.push(destinatario);
			}

		}

		/**
	     * Verificar se o destinatário já foi selecionado.
	     */
	    function verificarDestinatarioExiste(usuario) { 

	    	var ret = ctrl.tarefaAtual.DESTINATARIO
			    		.map(function(item) { return (item.STATUSEXCLUSAO == '1') ? null : parseInt(item.USUARIO_ID); })
			    		.indexOf(parseInt(usuario.ID));

	    	return ret;
		}

		/**
		 * Selecionar destinatários escolhidos.
		 */
		function selecionarDestinatarioEscolhido(tarefa, destinatario) {

			if (typeof tarefa.DESTINATARIO_SELEC == 'undefined')
				tarefa.DESTINATARIO_SELEC = [];

			var indexDestin = tarefa.DESTINATARIO_SELEC.indexOf(destinatario);

			if (indexDestin > -1) {
				tarefa.DESTINATARIO_SELEC.splice(indexDestin, 1);
			}
			else {
				tarefa.DESTINATARIO_SELEC.push(destinatario);
			}

		}

		/**
		 * Excluir destinatários escolhidos.
		 */
		function excluirDestinatarioEscolhido(tarefa) {

			var indexDestin	 = -1,
				destin 		 = '',
				destinLength = tarefa.DESTINATARIO_SELEC.length;

			for (var i = destinLength-1; i >= 0; i--) {

				destin 		 = tarefa.DESTINATARIO_SELEC[i];
				indexDestin  = tarefa.DESTINATARIO.indexOf(destin);

				if (indexDestin > -1)
					tarefa.DESTINATARIO[indexDestin].STATUSEXCLUSAO = '1';
			}

			tarefa.DESTINATARIO_SELEC = [];
		}



		/**
		 * Incluir notificados.
		 */
		function incluirNotificado(tarefa) {

			if (ctrl.listaUsuario.length == 0)
				ctrl.listarUsuario();

			ctrl.tarefaAtual = tarefa;

			setTimeout(function() {

				// Fix para vs-repeat.
				$('#modal-pesq-usuario-notificado .table-container-usuario')
					.trigger('resize')
					.scrollTop(0);

				// Foco no input de filtrar.
				$('#input-filtrar-usuario-notificado').select();

			}, 500);
		}

		/**
		 * Selecionar usuário que será notificado (modal).
		 */
		function selecionarUsuarioNotificado(usuario, $event) {

			var usuarioJaSelec = (ctrl.verificarNotificadoExiste(usuario) > -1) ? true : false;

			// Não desmarcar caso o checkbox seja clicado e o usuário já esteja selecionado.
			if ( $($event.target).hasClass('chk-selec-usuario-notificado') && usuarioJaSelec ) {

				$event.preventDefault();
				return false;
			}
			// Não desmarcar caso o usuário já esteja selecionado.
			else if (usuarioJaSelec) {
				return false;
			}

			var indexNotif = ctrl.tarefaAtual.NOTIFICADO.indexOf(usuario),
				notificado = {};

			if (indexNotif > -1) {
				ctrl.tarefaAtual.NOTIFICADO.splice(indexNotif, 1);
			}
			else {
				notificado.USUARIO_ID 	= usuario.ID;
				notificado.USUARIO 		= usuario.USUARIO;
				notificado.NOME 		= usuario.NOME;
				notificado.SETOR 		= usuario.SETOR;
				notificado.EMAIL 		= usuario.EMAIL;

				ctrl.tarefaAtual.NOTIFICADO.push(notificado);
			}

		}

		/**
	     * Verificar se o notificado já foi selecionado.
	     */
	    function verificarNotificadoExiste(usuario) { 

	    	var ret = -1;

	    	if (ctrl.tarefaAtual.NOTIFICADO) {

	    		ret = ctrl.tarefaAtual.NOTIFICADO
			    		.map(function(item) { return (item.STATUSEXCLUSAO == '1') ? null : parseInt(item.USUARIO_ID); })
			    		.indexOf(parseInt(usuario.ID));
	    	}

	    	return ret;
		}

		/**
		 * Selecionar notificados escolhidos.
		 */
		function selecionarNotificadoEscolhido(tarefa, notificado) {

			if (typeof tarefa.NOTIFICADO_SELEC == 'undefined')
				tarefa.NOTIFICADO_SELEC = [];

			var indexNotif = tarefa.NOTIFICADO_SELEC.indexOf(notificado);

			if (indexNotif > -1) {
				tarefa.NOTIFICADO_SELEC.splice(indexNotif, 1);
			}
			else {
				tarefa.NOTIFICADO_SELEC.push(notificado);
			}

		}

		/**
		 * Excluir notificados escolhidos.
		 */
		function excluirNotificadoEscolhido(tarefa) {

			var indexNotif	 = -1,
				notif 		 = '',
				notifLength  = tarefa.NOTIFICADO_SELEC.length;

			for (var i = notifLength-1; i >= 0; i--) {

				notif 		 = tarefa.NOTIFICADO_SELEC[i];
				indexNotif   = tarefa.NOTIFICADO.indexOf(notif);

				if (indexNotif > -1)
					tarefa.NOTIFICADO[indexNotif].STATUSEXCLUSAO = '1';
			}

			tarefa.NOTIFICADO_SELEC = [];
		}


		function addCampo(tarefa, focar) {

			focar = (typeof focar == 'undefined') ? true : focar;

			var campoNovo = {};
			angular.copy(ctrl.campoPadrao, campoNovo);
			tarefa.CAMPO.push(campoNovo);

			if (focar) {
			
				setTimeout(function() {
					$('.campo-container .scroll > div:last-of-type .form-group:first-child input').focus();
				}, 100);
			}
		}

		function excluirCampo(tarefa, campo) {

			// Só exclui do banco de dados se o campo tiver ID, ou seja, já está gravado no banco.
			if (campo.ID > 0) {

				campo.STATUSEXCLUSAO = '1';
				tarefa.existeCampoParaExcluir = true;
			}
			else
				tarefa.CAMPO.splice(tarefa.CAMPO.indexOf(campo), 1);

			// Adiciona um campo vazio se não tiver mais nenhum outro.
			if (tarefa.CAMPO.length == 0) {
				ctrl.addCampo(tarefa);
			}
			else {

				var possuiCampo = false;

				for(var i in tarefa.CAMPO)
					if (tarefa.CAMPO[i].STATUSEXCLUSAO == 0)
						possuiCampo = true;

				if (possuiCampo == false)
					ctrl.addCampo(tarefa);
			}
		}



		function fecharModalPesqUsuario() {

			$('#modal-pesq-usuario')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast')
			;
		}

		function fecharModalPesqUsuarioNotificado() {

			$('#modal-pesq-usuario-notificado')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast')
			;
		}


		function alterarEmailUsuario(usuario) {

			angular.copy(usuario, ctrl.emailUsuarioBKP);
			ctrl.emailUsuario = usuario;

			$('#modal-alterar-email-usuario').modal('show');
		}

		function gravarEmailUsuario() {

			_29011TarefaService
				.gravarEmailUsuario(ctrl.emailUsuario)
				.then(function() {

					showSuccess('E-mail alterado com sucesso.');

					for (var i in ctrl.listaUsuario) {

						if (ctrl.listaUsuario[i].ID == ctrl.emailUsuario.USUARIO_ID) {
							
							ctrl.listaUsuario[i].EMAIL = ctrl.emailUsuario.EMAIL;
							break;
						}
					}

					ctrl.fecharModalAlterarEmailUsuario();
				});
		}

		function cancelarAlterarEmailUsuario() {

			angular.copy(ctrl.emailUsuarioBKP, ctrl.emailUsuario);
			ctrl.fecharModalAlterarEmailUsuario();
		}

		function fecharModalAlterarEmailUsuario() {

			$('#modal-alterar-email-usuario').modal('hide');
		}

	}	