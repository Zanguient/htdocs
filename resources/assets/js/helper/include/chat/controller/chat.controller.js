
ChatController.$inject = ['ChatService', '$scope'];

function ChatController(ChatService, $scope) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.iniciarWebSocket			= iniciarWebSocket;
	ctrl.reconectarWebSocket 		= reconectarWebSocket;
	ctrl.analisarUsuario 			= analisarUsuario;
	ctrl.descerScrollConversa 		= descerScrollConversa;
	ctrl.atalhoTextarea 			= atalhoTextarea;
	ctrl.acaoScrollConversa			= acaoScrollConversa;
	ctrl.selecionarUsuario 			= selecionarUsuario;
	ctrl.enviarMsg 					= enviarMsg;
	ctrl.gravar  					= gravar;
	ctrl.consultarHistoricoConversa	= consultarHistoricoConversa;

	// VARIÁVEIS
	ctrl.ONLINE_CHAT 			= [];		// usuários online
	ctrl.MENSAGE 				= [];		// mensagem do usuário
	ctrl.usuarioIdConSelec 		= 0;		// id do usuário selecionado para conversar
	ctrl.usuarioIdConAtual		= 0;		// id do usuário atual
	ctrl.estadoJanelaChat 		= 0;
	ctrl.notifNovaMsgGeral 		= 0;
	ctrl.numFirstHistorico 		= 30;
	ctrl.ultimaConversa 		= false; 	// flag que define quando todas as conversas foram carregadas
	ctrl.usuarioEhCliente 		= (parseInt(document.querySelector('#usuario-cliente-id').value) > 0) ? true : false;
	ctrl.usuarioEhRepresentante = false;
	ctrl.represDoCliente 		= null;
	ctrl.representanteId 		= null;
	ctrl.houveErro				= false;
	ctrl.reconectando 			= false;
	ctrl.scrollPorConversa 		= [];
	ctrl.timeReconec;

	// Funções iniciadas ao carregar página
	ctrl.$onInit = function() {

		// Esperar tela ser carregada pelo angular.
		setTimeout(function() { 
			ctrl.iniciarWebSocket(); 
		}, 2000);

		ctrl.atalhoTextarea();
		ctrl.acaoScrollConversa();
	};


    // MÉTODOS

	function iniciarWebSocket() {

		ctrl.usuarioEhRepresentante	= (parseInt(document.querySelector('#usuario-representante-id').value) > 0) ? true : false;
		ctrl.represDoCliente 		= parseInt(document.querySelector('#representante-do-cliente').value);
		ctrl.represDoCliente 		= ctrl.represDoCliente > 0 ? ctrl.represDoCliente : 0;
		ctrl.representanteId 		= parseInt(document.querySelector('#usuario-representante-id').value) 
		ctrl.representanteId 		= ctrl.representanteId > 0 ? ctrl.representanteId : 0;

		// Funções para o web socket.
		var metodos = [
			{
				METHOD  :'ON_LOGIN_USER',
				FUNCTION:function(ret){
					
					// console.log('Entrou no chat:');
					//console.log(ret.MENSAGE.DADOS.NEW);
					ctrl.ONLINE_CHAT = ret.MENSAGE.DADOS.LISTA;

					$scope.$apply();
				}
			},
			{
				METHOD  :'ON_LOGOF_USER',
				FUNCTION:function(ret){
					$scope.$apply(function(){
						// console.log('Saiu do chat:');
						//console.log(ret.MENSAGE.DADOS.OLD);
						ctrl.ONLINE_CHAT = ret.MENSAGE.DADOS.LISTA;
	    			});	
				}
			},
			{
				METHOD  :'ON_MENSAGE',
				FUNCTION:function(ret){

					ctrl.MENSAGE.push(ret.MENSAGE.DADOS);
					ctrl.descerScrollConversa();
					
					var online = {};

					// Notificação.
					for (var i in ctrl.ONLINE_CHAT) {

						online = ctrl.ONLINE_CHAT[i];

						// Se o remetente na mensagem é diferente do usuário atual (remetente) e
						// pegar o usuário da lista (online) que é igual ao remetente na mensagem.
						// A primeira verificação serve para notificar ao destinatário apenas.
						// Como a mensagem é enviada (server) também para o próprio remetente,
						// é necessária essa verificação.
						if (ret.MENSAGE.DADOS.DE != ctrl.usuarioIdConAtual && online.USUARIO_ID == ret.MENSAGE.DADOS.DE) {

							// Notificar quando a conversa não estiver em foco
							if (!$('.digitar-msg textarea').is(':focus')) {

								ctrl.notifNovaMsgGeral = 1;	// notificação geral
								online.NOTIF_NOVA_MSG = 1;	// notificação no usuário

								Notification
									.requestPermission(function() {
		    				
					    				var notification = new Notification(
					    					"Delfa - Chat", 
					    					{
									            icon: '../../assets/images/logo2.png',
									            body: ret.MENSAGE.DADOS.MSG,
									            tag : ret.MENSAGE.DADOS.DE
								        	}
								        );

								        notification.onclick = function() {
								            window.focus();
								            notification.close();
								        };

								        setTimeout(notification.close.bind(notification), 5000);
					    			})
					    		;
							}							
						}

					}
	    			
	    			$scope.$apply();

	    			//console.log(ret.MENSAGE.DADOS);
				}
			}

		];

		// Iniciar web socket.
		SocketWeb.create(metodos);

		// Conectar usuário.
		setTimeout(function() {

			// ctrl.usuarioIdConAtual = SocketWeb.CONNECTION_ID;
			ctrl.usuarioIdConAtual = document.querySelector('#usuario-id').value;

			SocketWeb.sendMensage(
				SocketWeb.CONNECTION_ID,
				SocketWeb.CONNECTION_ID,
				{
					NOME 			 : document.querySelector('.user_nome').value,
			        ID 				 : SocketWeb.CONNECTION_ID,
			        TYPO 			 : 'CLIENTE',
			        CLIENTE_ID		 : document.querySelector('#usuario-cliente-id').value,			// identificar se é cliente
			        REPRESENTANTE_ID : ctrl.representanteId,	// identificar se é representante
			        REPRES_DO_CLIENTE: ctrl.represDoCliente,
			        STATUS  		 : 1
				},
				'LOGIN_CHAT',
				'LOGIN_CHAT',
				[]
			);

			ctrl.houveErro 	  = false;
			ctrl.reconectando = false;

		}, 1000);

		// Erro.
		SocketWeb.ERROR_EVENT = function(error) {
			
			ctrl.houveErro 	  = true;
			ctrl.reconectando = false;
			ctrl.ONLINE_CHAT  = [];
			ctrl.MENSAGE 	  = [];
			
			$scope.$apply();

			ctrl.reconectarWebSocket(false);
		};

		// Sucesso.
		SocketWeb.SUCCESS_EVENT = function() {

			// Se estiver reconectando.
			if (ctrl.reconectando)
				showSuccess('Chat conectado!');

			ctrl.reconectando = false;
		};
	}


	function reconectarWebSocket(imediatamente) {

		// Reconectar imediatamente (ativado ao clicar no botão 'reconectar')
		if (imediatamente) {

			clearTimeout(ctrl.timeReconec);
			ativar();
		}
		// Reconectar após 30s (ativado pelo evento de erro no WebSocket)
		else {
			ctrl.timeReconec = setTimeout(function() { ativar(); }, 10000);
		}

		function ativar() {

			ctrl.reconectando = true;
			ctrl.iniciarWebSocket();
		}
	}

	/**
	 * Analisar usuário para exibir ou não na lista de usuários.
	 */
	function analisarUsuario(online) {

		var ret = false;

		// Usuário diferente do atual.
		if (online.USUARIO_ID != ctrl.usuarioIdConAtual) {

			// Se o atual for cliente e o corrente for representante.
			// Conversa com comercial e SEU representante.
			if ( (ctrl.usuarioEhCliente == true) && (online.REPRESENTANTE_ID > 0) ) {
				// Se o corrente não for cliente também 
				// e o id de representante (do cliente) do atual for igual ao id de representante do corrente.
				if ( (online.CLIENTE_ID == '') && (ctrl.represDoCliente == online.REPRESENTANTE_ID) )
					ret = true;
			}
			// Se o atual for cliente.
			// Conversa com comercial.
			else if (ctrl.usuarioEhCliente == true) {
				// Se o corrente não for cliente também.
				if (online.CLIENTE_ID == '')
					ret = true;
			}
			// Se o atual for representante e o corrente for cliente (que deve ter um id de representante).
			// Conversa com comercial e SEU cliente.
			else if ( (ctrl.usuarioEhRepresentante == true) && (online.REPRES_DO_CLIENTE > 0) ) {
				// Se o corrente não for representante também
				// e o id de representante do atual for igual ao id de representante (do cliente) do corrente.
				if ( (online.REPRESENTANTE_ID == 0) && (ctrl.representanteId == online.REPRES_DO_CLIENTE) )
					ret = true;
			}
			// Se o atual for representante.
			// Conversa com comercial.
			else if (ctrl.usuarioEhRepresentante == true) {
				// Se o corrente não for representante também.
				if (online.REPRESENTANTE_ID == 0)
					ret = true;
			}
			// Se o atual for comercial.
			// Conversa com todos.
			else
				ret = true;
		}

		return ret;
	}

	function descerScrollConversa() {

		var container = $('.conversa-msg-container')[0];

		if ( container != undefined ) {
							
			setTimeout(function() {
				container.scrollTop = container.scrollHeight;
			}, 200);
		}
	}

	function acaoScrollConversa() {
	
		// Não continuar se o histórico de conversas já tiver sido completamente carregado.
		if (ctrl.ultimaConversa == true)
			return false;

		var scrollTimer = 0;	//verificar timeout

		document.addEventListener('scroll', function(event) {

		    if (event.target.className === 'conversa-msg-container') {

				clearTimeout(scrollTimer);

				scrollTimer = setTimeout(function() {

					if (event.target.scrollTop == 0) {

						ctrl.consultarHistoricoConversa();
						event.target.scrollTop = 10;
					}

				}, 500);
		    }
		}, true);
	}

	function atalhoTextarea() {

		$(document)
			.on('keydown', '.digitar-msg textarea', 'return', function(e) {
				
				e.preventDefault();
				// ctrl.enviarMsg();
				$('.digitar-msg button').click();
			})
		;
	}

	function selecionarUsuario(usuario) {

		var notifPendente 	= 0,
			online 			= {}
		;

		// Seleciona o usuário.
		if (usuario != null)
			ctrl.usuarioIdConSelec = usuario.USUARIO_ID;

		for (var i in ctrl.ONLINE_CHAT) {

			online = ctrl.ONLINE_CHAT[i];

			// Remove a notificação do usuário.
			if (online.USUARIO_ID == ctrl.usuarioIdConSelec)
				online.NOTIF_NOVA_MSG = 0;

			// Define se ainda existe notificação pendente.
			if (online.NOTIF_NOVA_MSG == 1)
				notifPendente = 1;
		}

		ctrl.notifNovaMsgGeral = notifPendente;

		setTimeout(function() {

			var textarea = $('.digitar-msg textarea');

			if ( !$(textarea).is(':focus') )
				$(textarea).focus();

			ctrl.descerScrollConversa();

		}, 200);

		// Indicar que um novo usuário foi selecionado 
		// e que o histórico de conversas pode ser consultado novamente (scroll).
		ctrl.ultimaConversa = false;
	}


	function enviarMsg() {

		if (!ctrl.mensagem)
			return false;

		var msg = {
				MSG 		: ctrl.mensagem,
		        DATA    	: moment().toDate(),
		        DE 	    	: ctrl.usuarioIdConAtual,
		        PARA 		: ctrl.usuarioIdConSelec,
		        REMETENTE 	: true,
		        CHAVE 		: parseInt($('.lbl-chave').text())
			}
		;

		// Enviar msg do remetente para ele mesmo (necessário quando há várias instâncias abertas).
		SocketWeb.sendMensage(
			ctrl.usuarioIdConAtual,
			ctrl.usuarioIdConAtual,
			msg,
			'SEND_CHAT',
			'SEND_CHAT',
			[]
		);

		ctrl.descerScrollConversa();

		// Limpar textarea.
		ctrl.mensagem = '';

		msg.REMETENTE = false;

		// Enviar msg para o destinatário.
		SocketWeb.sendMensage(
			ctrl.usuarioIdConAtual,
			ctrl.usuarioIdConSelec,
			msg,
			'SEND_CHAT',
			'SEND_CHAT',
			[]
		);

		// Gravar conversa na base.
		ctrl.gravar(msg);
	}

	function gravar(dadoMsg) {

		ChatService
			.gravar(dadoMsg)
		;
	}

	function consultarHistoricoConversa() {

		var scr 				= {},
			usuarioJaConversa 	= false,
			skip 				= 0;

		// Analisar os usuários que já estão com conversas (histórico) carregadas
		for (var i in ctrl.scrollPorConversa) {

			scr = ctrl.scrollPorConversa[i];

			if (scr.USUARIO_ID == ctrl.usuarioIdConSelec) {

				scr.POSICAO		 += ctrl.numFirstHistorico;
				skip 			  = scr.POSICAO;
				usuarioJaConversa = true;
				break;
			}
		}

		// Se o usuário ainda não tem conversa carregada, 
		// ou seja, primeiro carregamento de histórico do usuário online.
		if (!usuarioJaConversa) {

			ctrl.scrollPorConversa.push({
				USUARIO_ID 	: ctrl.usuarioIdConSelec,
				POSICAO 	: 0
			});
		}

		// Na ação do scroll, se ctrl.ultimaConversa for true não chega aqui;
		// mas quando o botão é clicado essa verificação é necessária.
		if (ctrl.ultimaConversa == true) 
			return false;

		var param = {
			FIRST 				: ctrl.numFirstHistorico,
			SKIP				: skip,
			USUARIO_ID_ATUAL	: ctrl.usuarioIdConAtual,
			USUARIO_ID_SELEC	: ctrl.usuarioIdConSelec
		};

		// Zerar conversa quando a consulta for feita pela primeira vez.
		if (skip == 0)
			ctrl.MENSAGE = [];

		ChatService
			.consultarHistoricoConversa(param)
			.then(function(response) {

				if (response.length == 0) {
					ctrl.ultimaConversa = true;
					return false;
				}

				var resp = {},
					dado = {}
				;

				for (var i in response) {

					resp = response[i];

					dado = {
						DE 			: resp.REMETENTE_ID,
						PARA 		: resp.DESTINATARIO_ID,
						MSG 		: resp.MENSAGEM,
						DATA 		: moment(resp.DATAHORA).format('DD/MM/YYYY HH:mm'),
						REMETENTE 	: (resp.REMETENTE_ID == ctrl.usuarioIdConAtual) ? true : false
					};

					ctrl.MENSAGE.splice(0, 0, dado);
				}
			})
		;
	}

}