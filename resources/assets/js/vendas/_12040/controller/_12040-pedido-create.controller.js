
	PedidoCreateController.$inject = ['PedidoCreateService', '$scope'];

	function PedidoCreateController(PedidoCreateService, $scope) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.ativarAlterar 			 = ativarAlterar;
		ctrl.verificarSituacaoPedido = verificarSituacaoPedido;
		ctrl.store		 			 = store;
		ctrl.verificarExisteItem 	 = verificarExisteItem;
		ctrl.excluir	 			 = excluir;

		// VARIÁVEIS
		


		// MÉTODOS

		/**
		 * Ativar comportamento padrão da tela de Alterar.
		 */
		function ativarAlterar() {

			ctrl.verificarSituacaoPedido();
			ctrl.tipoTela = 'alterar';
			// ctrl.infoGeral.gerarChave();
		}

		/**
		 * Verificar situação do pedido (confirmado ou não) e a forma de análise (imediata ou não).
		 * Se confirmado ou análise imediata, o pedido não pode ser alterado.
		 */
		function verificarSituacaoPedido() {

			var res = true;

			if (ctrl.infoGeral.infoGeral.FORMA_ANALISE == '0')
				res = false;
			else if (ctrl.situacaoPedido == '1')
				res = false;

			if (!res)
				throw 'Pedido não pode ser alterado.';
		}

		/**
		 * Gravar.
		 */
		function store() {

			if (!ctrl.verificarExisteItem())
				return false;

			var pedidoItem 					= [],
				pedidoItemEscolhido 		= ctrl.pedidoItemEscolhido.pedidoItemEscolhido,
				pedidoItemEscolhidoExcluir 	= ctrl.pedidoItemEscolhido.pedidoItemEscolhidoExcluir,
				pedItemEsc 					= {},
				infoGeral  					= ctrl.infoGeral.infoGeral,
				dataIdeal 					= '',
				btnImediatam 				= { desc: 'Imediatamente', class: 'btn-primary', ret: '1', hotkey: 'alt+m', glyphicon: '' },
				btnAguardar 				= { desc: 'Aguardar 24h', class: 'btn-warning', ret: '3', hotkey: 'alt+g', glyphicon: '' }
			;

			confirmarFormaAnalise();

			/**
			 * Confirmar escolha da forma de análise.
			 */
			function confirmarFormaAnalise() {

				addConfirme(
					'<h4>Confirmação</h4>',
		        	'Como deseja que esse pedido seja analisado e confirmado?',
		        	[obtn_cancelar, btnImediatam, btnAguardar],
		        	[
		            	{
		            		ret: 1,
		            		func: function() {

		            			reconfirmarFormaImediatam();
		            		}
		            	},
		            	{
		            		ret: 2,		// padrão para botão cancelar (popup.js)
		            		func: function() {	            			
		            		}
		            	},
		            	{
		            		ret: 3,
		            		func: function() {

		            			processarGravacao(1);
		            		}
		            	}
		        	]
		        );
			}

			/**
			 * Reconfirmar caso a forma de análise escolhida seja imediatamente.
			 */
	        function reconfirmarFormaImediatam() {

	        	addConfirme(
					'<h4>Confirmação</h4>',
		        	'Após a gravação, esse pedido será imediatamente processado, não podendo mais ser alterado ou cancelado. Confirma?',
		        	[obtn_nao, obtn_sim],
		        	[
		        		{
		            		ret: 1,
		            		func: function() {

		            			processarGravacao(0);
		            		}
		            	},
		            	{
		            		ret: 2,
		            		func: function() {
		            			confirmarFormaAnalise();      			
		            		}
		            	}
		        	]
		        );
	        }

			/**
			 * Processar dados e gravar.
			 * @param {int} formaAnalise Forma de análise do pedido.
			 */
			function processarGravacao(formaAnalise) {

				// Itens do pedido.
				for (var i in pedidoItemEscolhido) {

					pedItemEsc = pedidoItemEscolhido[i];

					// Tratamento necessário quando a data está no formato 'dd/mm/yyyy'.
					dataIdeal = pedItemEsc.dataIdeal.split('/');
					dataIdeal = new Date(dataIdeal[2], dataIdeal[1] - 1, dataIdeal[0]);
					dataIdeal = moment(dataIdeal).format('DD.MM.YYYY');

					pedidoItem[i] = {
						SEQUENCIA 		: pedItemEsc.sequencia,
						PRODUTO_ID 		: pedItemEsc.produto.CODIGO,
						MODELO_ID 		: pedItemEsc.modelo.MODELO_CODIGO,
						TAMANHO 		: pedItemEsc.tamanhoId,
						COR_ID 	 		: pedItemEsc.cor.CODIGO,
						COR_CONDICAO	: pedItemEsc.cor.CONDICAO.charAt(0),
						PERFIL_COR 		: pedItemEsc.cor.PERFIL,
						QUANTIDADE 		: pedItemEsc.quantidade,
						VALOR_UNITARIO	: pedItemEsc.valorUnitario,
						VALOR_TOTAL		: pedItemEsc.valorTotal,
						PERFIL 			: pedItemEsc.perfilId,
						DATA_CLIENTE 	: dataIdeal,
						EST_MIN 		: pedItemEsc.estMin
					};
				}

				var dados = {

					pedido: {
						CLIENTE_ID 				: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) ? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) : 0,
					    PEDIDO 					: (infoGeral.PEDIDO != undefined) ? infoGeral.PEDIDO : null,
					    PEDIDO_CLIENTE			: infoGeral.PEDIDO_CLIENTE,
					    REPRESENTANTE_CODIGO	: infoGeral.REPRESENTANTE_CODIGO,
					    TRANSPORTADORA_CODIGO	: infoGeral.TRANSPORTADORA_CODIGO,
					    FRETE					: infoGeral.FRETE,
					    PAGAMENTO_FORMA			: infoGeral.PAGAMENTO_FORMA,
					    PAGAMENTO_CONDICAO		: infoGeral.PAGAMENTO_CONDICAO,
					    FAMILIA_CODIGO			: pedidoItemEscolhido[0].modelo.FAMILIA_CODIGO,
					    OBSERVACAO				: infoGeral.OBSERVACAO,
					    DATA_CLIENTE			: (infoGeral.DATA_CLIENTE != undefined) ? moment(infoGeral.DATA_CLIENTE).format('DD.MM.YYYY') : null,
					    PRIORIDADE				: infoGeral.PRIORIDADE,
					    PROGRAMADO				: infoGeral.PROGRAMADO,
					    EMAIL_XML				: infoGeral.EMAIL_XML,
					    CHAVE_LIBERACAO 		: infoGeral.CHAVE,
					    FORMA_ANALISE 			: formaAnalise,
					    TIPO_TELA 				: ctrl.tipoTela,
					    PEDIDO_ITEM 			: pedidoItem,
					    PEDIDO_ITEM_EXCLUIR 	: pedidoItemEscolhidoExcluir,
					    VALOR_FRETE             : infoGeral.VALOR_FRETE
					}
				};

				PedidoCreateService
					.store(dados)
					.then(function(response) {

						showSuccess('Gravado com sucesso.');
						ctrl.tipoTela = 'exibir';
						ctrl.consultarPedido();

						// espera para o tipoTela ser aplicado.
						setTimeout(function() {
							ctrl.fecharModal();
						}, 500);
					})
				;
			}

		}

		/**
		 * Verificar se itens de pedido foram escolhidos.
		 */
		function verificarExisteItem() {

			var ret 	= true,
				corEsc 	= ''
			;

			// Se existe item.
			if (ctrl.pedidoItemEscolhido.pedidoItemEscolhido.length === 0) {

				showAlert('Escolha itens para o pedido.');
				ret = false;
			}
			// Se a quantidade das cores obedece a quantidade mínima e múltipla.
			else {
				
				for (var i in ctrl.pedidoItemEscolhido.corEscolhida) {

					corEsc = ctrl.pedidoItemEscolhido.corEscolhida[i];

					if ( (corEsc.quantidade < corEsc.quantidadeMinima) || ((corEsc.quantidade % corEsc.quantidadeMultipla) != 0) ) {

						showErro('<span>A cor <b>'+corEsc.codigo+'</b> deve ter quantidade maior que '+corEsc.quantidadeMinima+' e múltipla de '+corEsc.quantidadeMultipla+'.</span>');
						ret = false;
					}
				}
			}

			return ret;
		}

		/**
		 * Excluir.
		 */
		function excluir() {

			ctrl.verificarSituacaoPedido();
			
			addConfirme(
				'<h4>Confirmação</h4>',
	        	'Confirma a exclusão deste pedido?',
	        	[obtn_nao, obtn_sim],
	        	[
	            	{
	            		ret: 1,
	            		func: function() {

	            			efetivarExcluir();
	            		}
	            	},
	            	{
	            		ret: 2,		// padrão para botão cancelar (popup.js)
	            		func: function() {	            			
	            		}
	            	}
	        	]
	        );

	        function efetivarExcluir() {
			
				var dados = {
					pedido: ctrl.infoGeral.infoGeral.PEDIDO
				};

				PedidoCreateService
					.excluir(dados)
					.then(function(response) {
						
						showSuccess('Excluído com sucesso.');
						ctrl.consultarPedido();
						ctrl.fecharModal();

					})
				;
			}
		}

	}