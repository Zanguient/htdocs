
	// Injetando Components ao Controller.
	PedidoItemEscolhidoController.$inject = ['PedidoItemEscolhidoService', '$scope'];

	/**
	 * Controller.
	 */
	function PedidoItemEscolhidoController(PedidoItemEscolhidoService, $scope) {

		var ctrl = this;

		/**
		 * MÉTODOS (REFERÊNCIAS).
		 */
		ctrl.selecionarPedidoItemEscolhido 	= selecionarPedidoItemEscolhido;
		ctrl.excluirPedidoItemEscolhido 	= excluirPedidoItemEscolhido;
		ctrl.subtrairQtdResumo 				= subtrairQtdResumo;
		ctrl.somaQuantidadeGeral			= somaQuantidadeGeral;
		ctrl.definirDataCliente 			= definirDataCliente;
		ctrl.atualizarQtdLiberada 			= atualizarQtdLiberada;

		/**
		 * VARIÁVEIS.
		 */
		ctrl.pedidoItemEscolhido 		= [];
		ctrl.pedidoItemEscolhidoSelec 	= [];
		ctrl.pedidoItemEscolhidoExcluir	= [];
		ctrl.corEscolhida 				= [];

		this.$onInit = function() {

			ctrl.pedidoIndex12040.pedidoItemEscolhido 	= this;
			ctrl.pedidoCreate12040.pedidoItemEscolhido 	= this;

		};



		/**
		 * MÉTODOS.
		 */

		function selecionarPedidoItemEscolhido(item) {

			// Indica se deve selecionar a linha da tabela.
			if (item.selected == undefined || item.selected == false)
				item.selected = true;
			else
				item.selected = false;

			// Preencher model com a lista de itens selecionados.
			var index = ctrl.pedidoItemEscolhidoSelec.indexOf(item);

			if (index > -1) {
				ctrl.pedidoItemEscolhidoSelec.splice(index, 1);
			}
			else {
				ctrl.pedidoItemEscolhidoSelec.push(item);
			}

		}

		function excluirPedidoItemEscolhido() {

			addConfirme(
				'<h4>Confirmação</h4>',
            	'Confirma a exclusão dos itens selecionados?',
            	[obtn_sim, obtn_nao],
            	[
                	{
                		ret: 1,
                		func: function() { 

                			var index 	= -1,
								item 	= ''
							;

							//Excluir no JSON.
							for(var i in ctrl.pedidoItemEscolhidoSelec) {

								item  = ctrl.pedidoItemEscolhidoSelec[i];
								index = ctrl.pedidoItemEscolhido.indexOf(item);

								if (index > -1) {

									ctrl.subtrairQtdResumo(item);
									ctrl.pedidoItemEscolhido.splice(index, 1);
									
									if (item.gravado) {

										ctrl.pedidoItemEscolhidoExcluir.push({
											PEDIDO 		: ctrl.pedidoCreate12040.infoGeral.infoGeral.PEDIDO,
											SEQUENCIA 	: item.sequencia 
										});
									}
								}
							}

							ctrl.somaQuantidadeGeral();
							ctrl.definirDataCliente();
							ctrl.pedidoItemEscolhidoSelec = [];
							$scope.$apply();

               			}
                	},
                	{
                		ret: 2,
                		func: function() {}
                	}
            	]
            );

		}

		/**
		 * Subtrair quantidade da tabela de resumo de cores.
		 */
		function subtrairQtdResumo(item) {

			var corEsc = '',
				index  = 0
			;

			for (var j in ctrl.corEscolhida) {

				corEsc = ctrl.corEscolhida[j];

				if (corEsc.codigo === item.cor.CODIGO)
					corEsc.quantidade -= item.quantidade;
				
				if (corEsc.quantidade === 0) {

					index = ctrl.corEscolhida.indexOf(corEsc);
					ctrl.corEscolhida.splice(index, 1);
				}
			}
		}

		/**
		 * Somar quantidade e valor unitário dos tamanhos escolhidos pelo cliente.
		 */
		function somaQuantidadeGeral() {

			var item 	= {},
				qtdGrl 	= 0,
				vlrGrl 	= 0
			;

			for(var i in ctrl.pedidoItemEscolhido) {

				item = ctrl.pedidoItemEscolhido[i];

				qtdGrl += item.quantidade;
				vlrGrl += item.valorTotal;

			}

			ctrl.quantidadeGeralTotal 	= qtdGrl;
			ctrl.valorGeralTotal 		= vlrGrl;

		}

		/**
		 * Definir data do cliente de acordo com a maior 
		 * data de previsão de faturamento entre os itens do pedido escolhidos.
		 */
		function definirDataCliente() {

			if (ctrl.pedidoItemEscolhido.length > 0) {

				var item 		= '',
					dataMaior 	= '',
					dataIdeal 	= '',
					dataCliente = moment(ctrl.pedidoCreate12040.infoGeral.infoGeral.DATA_CLIENTE)
				;

				// Tratamento necessário quando a data está no formato 'dd/mm/yyyy'.
				dataMaior = ctrl.pedidoItemEscolhido[0].dataIdeal.split('/');
				dataMaior = new Date(dataMaior[2], dataMaior[1] - 1, dataMaior[0]);
				dataMaior = moment(dataMaior);

				for (var i in ctrl.pedidoItemEscolhido) {

					item = ctrl.pedidoItemEscolhido[i];

					// Tratamento necessário quando a data está no formato 'dd/mm/yyyy'.
					dataIdeal = item.dataIdeal.split('/');
					dataIdeal = new Date(dataIdeal[2], dataIdeal[1] - 1, dataIdeal[0]);
					dataIdeal = moment(dataIdeal);

					if (dataMaior < dataIdeal)
						dataMaior = dataIdeal;
				}

				// Alterar quando a data do cliente for menor que a maior 
				// data de previsão de faturamento entre os itens do pedido.
				if (dataMaior > dataCliente) {

					ctrl.pedidoCreate12040.infoGeral.infoGeral.DATA_CLIENTE 	= dataMaior.toDate();
					ctrl.pedidoCreate12040.infoGeral.infoGeral.DATA_MIN_CLIENTE = dataMaior.format('YYYY-MM-DD');

					showAlert('Sua data foi alterada para '+ moment(dataMaior).format('DD/MM/YYYY') +'.');
				}
			}
		}

		/**
    	 * Atualizar a quantidade mínima liberada para uma cor.
    	 * Caso exista uma liberação para a cor, a nova quantidade é exibida. Senão, continua a mesma.
    	 */
		function atualizarQtdLiberada(cor) {

			var param = {
				COR_ID: cor.codigo,
				CHAVE : ctrl.pedidoCreate12040.infoGeral.infoGeral.CHAVE
			};

			PedidoItemEscolhidoService
				.consultarQtdLiberada(param)
				.then(function(response) {

					if (response.length > 0)
						cor.quantidadeMinima = response[0].QUANTIDADE;
				});
		}

	}