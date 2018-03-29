
	PedidoIndexController.$inject = ['PedidoIndexService', '$scope', '$timeout', '$q'];

	function PedidoIndexController(PedidoIndexService, $scope, $timeout, $q) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.verificarUsuarioEhRepresentante 	= verificarUsuarioEhRepresentante;
		ctrl.consultarRepresentanteDoCliente 	= consultarRepresentanteDoCliente;
		ctrl.alterarRepresentante 				= alterarRepresentante;
		ctrl.alterarCliente		 				= alterarCliente;
		ctrl.consultarPedido 					= consultarPedido;
		ctrl.consultarPedidoPorUrl				= consultarPedidoPorUrl;
		ctrl.exibirPedido 	 					= exibirPedido;
		ctrl.ativarIncluir 						= ativarIncluir;
		ctrl.ativarLiberacao					= ativarLiberacao;
		ctrl.limparTelaEdit	 					= limparTelaEdit;
		ctrl.fecharModal  	 					= fecharModal;
		ctrl.export = exportDados;

		// VARIÁVEIS
		ctrl.dataIni 			= moment().subtract(1, "month").toDate();
		ctrl.dataFim 			= moment().toDate();
		ctrl.tipoTela 			= 'incluir';
		ctrl.situacaoPedido 	= '0';		// Situação do pedido (confirmado ou não)
		ctrl.representanteId 	= null;
		ctrl.represDoCliente 	= null;
		ctrl.pedidoIdUrl        = '';
		ctrl.pedidoCliente      = '';
		ctrl.faturado           = true;
		ctrl.tipo_data          = 1;

		this.$onInit = function() {

			ctrl.verificarUsuarioEhRepresentante();
			ctrl.consultarRepresentanteDoCliente();

			// representanteId=3495&clienteId=2309&pedidoId=166223
			// ids para filtro na url são utilizados em 12060 e 12070
			ctrl.pedidoIdUrl = parseInt(getURLParameter('pedidoId'));
		};



		// MÉTODOS

		/**
		 * Verificar se o usuário é um representante.
		 */
		function verificarUsuarioEhRepresentante() {

			PedidoIndexService
				.verificarUsuarioEhRepresentante()
				.then(function(response) {
					ctrl.representanteId = response.length === 0 ? null : response[0].REPRESENTANTE_CODIGO;
				})
			;
		}

		/**
		 * Consultar representante do cliente.
		 */
		function consultarRepresentanteDoCliente() {

			PedidoIndexService
				.consultarRepresentanteDoCliente()
				.then(function(response) {
					ctrl.represDoCliente = response.length === 0 ? null : response[0].REPRESENTANTE_CODIGO;
				})
			;
		}

		/**
		 * Alterar representante.
		 */
		function alterarRepresentante() {

			ctrl.pedido = null;
			ctrl.filtroCliente.cliente = null;
			ctrl.filtro.consultarRepresentante();
			$('#modal-consultar-representante').modal('show');
		}

		/**
		 * Alterar cliente.
		 */
		function alterarCliente() {

			ctrl.pedido = null;

			// Se o usuário não é um representante e não foi escolhido um representante para a filtragem.
			if (ctrl.representanteId === null && ctrl.filtro.representante === undefined)
				showErro('Selecione um representante.');
			else {
				ctrl.filtroCliente.consultarClientePorRepresentante();
				$('#modal-por-representante').modal('show');
			}
		}

		/**
		 * Consultar pedidos.
		 */
		function consultarPedido() {
           var filtro = {
                    filtro : {
                        REPRESENTANTE   : (ctrl.filtro.representante  !== undefined && ctrl.filtro.representante  !== null) ? ctrl.filtro.representante.CODIGO  : 0,
                        CLIENTE_ID		: (ctrl.filtroCliente.cliente !== undefined && ctrl.filtroCliente.cliente !== null) ? ctrl.filtroCliente.cliente.CODIGO : 0,
                        PEDIDO			: ctrl.pedidoIdUrl,
                        PEDIDO_CLIENTE	: ctrl.pedidoCliente,
                        FATURADO    	: ctrl.faturado,
                        TIPO_DATA       : ctrl.tipo_data,
                        DATA_INI 		: moment(ctrl.dataIni).format('DD.MM.YYYY'),
                        DATA_FIM 		: moment(ctrl.dataFim).format('DD.MM.YYYY')
                    }
                };
                
            return $q(function(resolve,reject){
     

                PedidoIndexService
                    .consultarPedido(filtro)
                    .then(function(response) {
                        ctrl.pedido = response;
                
                        resolve(response);
                    
                },function(err){
                    reject(err);
                });
            });
		}

		/**
		 * Consultar pedido por parâmetros da url.
		 */
		function consultarPedidoPorUrl() {

			var filtro = {
				filtro : {
					CLIENTE_ID	: ctrl.filtroCliente.cliente.CODIGO,
					PEDIDO		: ctrl.pedidoIdUrl,
					DATA_INI 	: null,
					DATA_FIM 	: null
				}
			};

			PedidoIndexService
				.consultarPedido(filtro)
				.then(function(response) {
					ctrl.pedido = response;
					ctrl.exibirPedido(ctrl.pedido[0]);
				});
		}

		function exportDados(tipo) {

			var dados = [];

			var filtro = {
				filtro : {
					REPRESENTANTE   : (ctrl.filtro.representante  !== undefined && ctrl.filtro.representante  !== null) ? ctrl.filtro.representante.CODIGO  : 0,
					CLIENTE_ID		: (ctrl.filtroCliente.cliente !== undefined && ctrl.filtroCliente.cliente !== null) ? ctrl.filtroCliente.cliente.CODIGO : 0,
					PEDIDO			: ctrl.pedidoIdUrl,
					PEDIDO_CLIENTE	: ctrl.pedidoCliente,
					FATURADO    	: ctrl.faturado,
					TIPO_DATA       : ctrl.tipo_data,
					DATA_INI 		: moment(ctrl.dataIni).format('DD.MM.YYYY'),
					DATA_FIM 		: moment(ctrl.dataFim).format('DD.MM.YYYY')
				}
			};

			PedidoIndexService
				.consultarPedido2(filtro)
				.then(function(response) {
					dados = response;

					var coll = [
			            {COLUNA: 'PEDIDO', 				DESCRICAO:'Pedido', 		TIPO: 'INTEIRO' },
						{COLUNA: 'NFS', 				DESCRICAO:'N. Fiscal', 		TIPO: 'STRING'  },
						{COLUNA: 'PEDIDO_CLIENTE', 		DESCRICAO:'Ped. Cli.', 		TIPO: 'STRING'  },
						{COLUNA: 'CLIENTE_RAZAOSOCIAL', DESCRICAO:'Cliente', 		TIPO: 'STRING'  },
						{COLUNA: 'TIPO', 				DESCRICAO:'Tipo', 			TIPO: 'STRING'  },
						{COLUNA: 'DATA', 				DESCRICAO:'Dt. Emissão', 	TIPO: 'DATA'    },
						{COLUNA: 'DATA_CLIENTE', 		DESCRICAO:'Dt. Cliente', 	TIPO: 'DATA'    },
						{COLUNA: 'PROGRAMADO', 			DESCRICAO:'Prog.?', 		TIPO: 'INTEIRO' },
						{COLUNA: 'FORMA_ANALISE', 		DESCRICAO:'Form. Analise', 	TIPO: 'INTEIRO' },
						{COLUNA: 'SITUACAO', 			DESCRICAO:'Situação', 		TIPO: 'INTEIRO' },
						{COLUNA: 'QUANTIDADE_TOTAL', 	DESCRICAO:'Qtd.  Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'FATURADO', 			DESCRICAO:'Faturado Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'SALDO_FATURAR', 		DESCRICAO:'Saldo Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'PRODUZIR', 			DESCRICAO:'A Prod. Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'EMPRODUCAO', 			DESCRICAO:'Em Prod. Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'ALOCADO', 			DESCRICAO:'Alocado Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'ENCERRADO', 			DESCRICAO:'Encerrado Tot.', TIPO: 'NUMERICO'},
						{COLUNA: 'VALOR_ST', 			DESCRICAO:'Valor ST  Tot.', TIPO: 'NUMERICO'},
						{COLUNA: 'VALOR_TOTAL', 		DESCRICAO:'Valor  Tot.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'MODELO', 				DESCRICAO:'Modelo', 		TIPO: 'STRING'  },
						{COLUNA: 'OBSERVACAO', 			DESCRICAO:'Observação', 	TIPO: 'STRING'  },

						{COLUNA: 'SEQUENCIA', 			DESCRICAO:'Seq.', 			TIPO: 'INTEIRO' },
						{COLUNA: 'PRODUTO_CODIGO', 	 	DESCRICAO:'Pord. ID', 		TIPO: 'INTEIRO' },
						{COLUNA: 'PRODUTO_DESCRICAO',	DESCRICAO:'Produto', 		TIPO: 'STRING'  },
						{COLUNA: 'PERFIL_DESCRICAO', 	DESCRICAO:'Perfil', 		TIPO: 'STRING'  },
						{COLUNA: 'TAMANHO_DESCRICAO',	DESCRICAO:'Tam.', 			TIPO: 'STRING'  },
						{COLUNA: 'QUANTIDADE', 			DESCRICAO:'Qtd.', 			TIPO: 'NUMERICO'},
						{COLUNA: 'FATURADO2', 			DESCRICAO:'Faturado', 		TIPO: 'NUMERICO'},
						{COLUNA: 'SALDO_FATURAR2', 		DESCRICAO:'Saldo', 			TIPO: 'NUMERICO'},
						{COLUNA: 'PRODUZIR2', 			DESCRICAO:'A Produzir', 	TIPO: 'NUMERICO'},
						{COLUNA: 'EMPRODUCAO2', 		DESCRICAO:'Em Produção',	TIPO: 'NUMERICO'},
						{COLUNA: 'ALOCADO2', 			DESCRICAO:'Alocado', 		TIPO: 'NUMERICO'},
						{COLUNA: 'ENCERRADO2', 			DESCRICAO:'Encerrado', 		TIPO: 'NUMERICO'},
						{COLUNA: 'VALOR_SBT', 			DESCRICAO:'Valor ST', 		TIPO: 'NUMERICO'},
						{COLUNA: 'PRODUTO_UM', 			DESCRICAO:'UM', 			TIPO: 'STRING'  },
						{COLUNA: 'VALOR', 				DESCRICAO:'Vlr. Unit.', 	TIPO: 'NUMERICO'},
						{COLUNA: 'TOTAL_ITEM', 			DESCRICAO:'Valor Total', 	TIPO: 'NUMERICO'},
						{COLUNA: 'DATA_CLIENTE2', 		DESCRICAO:'Prev. Fat.', 	TIPO: 'STRING'  },
			        ];

			        if(tipo == 1){
						exportToXls('Pedidos.xls', dados, coll);
					}else{
						exportToCsv('Pedidos.csv', dados, coll);	
					}
				})
			;

			
		};

		/**
		 * Exibir pedido.
		 */
		function exibirPedido(pedido) {

			var pedidoItem = {};

//			$('#detalhe-cliente-toggle').click();	// exibir 'mais informações'.
			ctrl.tipoTela = 'exibir';
			ctrl.situacaoPedido = pedido.SITUACAO;
			ctrl.limparTelaEdit();

			function consultarPedidoItem(pedido) {

                return $q(function(resolve,reject){
                    var filtro = {
                        filtro: {
                            PEDIDO 	: pedido.PEDIDO,
                            CHAVE 	: pedido.CHAVE_LIBERACAO,
                            OBJ     : pedido
                        }
                    };

                    PedidoIndexService
                        .consultarPedidoItem(filtro)
                        .then(function(response) {

                            pedidoItem = response;
                            carregarInfoGeral();
                            carregarPedidoItem();
                            exibirModal();
                            
                            resolve(response);
                            
                        },function(err){
                            reject(err);
                        })
                    ;
                });
			}

			function carregarInfoGeral() {

				var infoGeral = ctrl.infoGeral.infoGeral;

				infoGeral.PEDIDO 					= pedido.PEDIDO;
				infoGeral.RAZAOSOCIAL				= pedido.CLIENTE_RAZAOSOCIAL;
				infoGeral.REPRESENTANTE_CODIGO		= pedido.REPRESENTANTE_CODIGO;
				infoGeral.CLIENTE_CODIGO			= pedido.CLIENTE_CODIGO;
				infoGeral.REPRESENTANTE_DESCRICAO	= pedido.REPRESENTANTE_DESCRICAO;
				infoGeral.PROGRAMADO				= pedido.PROGRAMADO;
				infoGeral.NFS				        = pedido.NFS;
				
				// if (pedido.PROGRAMADO == '1') {

					infoGeral.DATA_CLIENTE		= moment(pedido.DATA_CLIENTE).toDate();
					
					infoGeral.DATA_MIN_CLIENTE	= (pedido.DATA_CLIENTE != undefined) 
													? moment(pedido.DATA_CLIENTE).format('YYYY-MM-DD')
													: moment().add(1, "month").format('YYYY-MM-DD');
				// }
				// else {

				// 	infoGeral.DATA_CLIENTE 		= '';
				// 	infoGeral.DATA_MIN_CLIENTE 	= '';
				// }

				infoGeral.PEDIDO_CLIENTE 				= pedido.PEDIDO_CLIENTE;
				infoGeral.OBSERVACAO 					= pedido.OBSERVACAO;
				infoGeral.EMAIL_XML 					= pedido.EMAIL_XML;
				infoGeral.TRANSPORTADORA_CODIGO  		= pedido.TRANSPORTADORA_CODIGO;
				infoGeral.TRANSPORTADORA_DESCRICAO 		= pedido.TRANSPORTADORA_DESCRICAO;
				infoGeral.FRETE 			 			= pedido.FRETE;
				infoGeral.FRETE_DESCRICAO 				= pedido.FRETE_DESCRICAO;
				infoGeral.PAGAMENTO_FORMA 				= pedido.PAGAMENTO_FORMA;
				infoGeral.PAGAMENTO_FORMA_DESCRICAO 	= pedido.PAGAMENTO_FORMA_DESCRICAO;
				infoGeral.PAGAMENTO_CONDICAO 			= pedido.PAGAMENTO_CONDICAO;
				infoGeral.PAGAMENTO_CONDICAO_DESCRICAO	= pedido.PAGAMENTO_CONDICAO_DESCRICAO;
				infoGeral.FORMA_ANALISE					= pedido.FORMA_ANALISE;
				infoGeral.CHAVE 						= pedido.CHAVE_LIBERACAO;

				infoGeral.CNPJ 							= pedido.CNPJ;
				infoGeral.IE 							= pedido.IE;
				infoGeral.CIDADE 						= pedido.CIDADE;
				infoGeral.BAIRRO						= pedido.BAIRRO;
				infoGeral.EMAIL							= pedido.EMAIL;
				infoGeral.FONE 							= pedido.FONE;
				infoGeral.FAX 							= pedido.FAX;
				infoGeral.NUMERO 						= pedido.NUMERO;
				infoGeral.ENDERECO						= pedido.ENDERECO;
				infoGeral.CEP						    = pedido.CEP;
				infoGeral.VALOR_FRETE                   = pedido.VALOR_FRETE;
				infoGeral.MENSAGEM						= pedido.MENSAGEM;
				infoGeral.PREVFAT 						= pedido.PREVFAT;


			}

			function carregarPedidoItem() {

				var item 	= {},
					modelo 	= {},
					produto	= {},
					cor 	= {},
					valor 	= 0,
					qtd 	= 0
				;

				for (var i in pedidoItem) {

					item 	= pedidoItem[i];
					modelo 	= {};
					produto	= {};
					cor 	= {};

					modelo.MODELO_CODIGO	= item.MODELO_CODIGO;
					modelo.MODELO_DESCRICAO	= item.MODELO_DESCRICAO;
					modelo.FAMILIA_CODIGO 	= item.FAMILIA_CODIGO;
					produto.CODIGO 			= item.PRODUTO_CODIGO;
					produto.DESCRICAO 		= item.PRODUTO_DESCRICAO;
					produto.UM 				= item.PRODUTO_UM;
					cor.CODIGO 				= item.COR_ID;
					cor.DESCRICAO 			= item.COR_DESCRICAO;

					qtd 	= parseFloat(item.QUANTIDADE);
					valor 	= parseFloat(item.VALOR);

					ctrl.pedidoItemEscolhido.pedidoItemEscolhido.push({
						sequencia 		: item.SEQUENCIA,
						modelo 	 		: modelo,
						produto 		: produto,
						cor 			: cor,
						tamanhoId		: item.TAMANHO,
						tamanhoDescricao: item.TAMANHO_DESCRICAO,
						valor_st        : item.VALOR_SBT,
						quantidade 		: qtd,
						valorUnitario	: valor,
						valorTotal		: valor * qtd,
						perfilId 		: item.PERFIL,
						perfilDescricao	: item.PERFIL_DESCRICAO,
						estMin 			: item.EST_MIN,
						dataIdeal 		: moment(item.DATA_CLIENTE).format('DD/MM/YYYY'),
						gravado 		: true,		// indicar que o item já está gravado na base de dados
						FATURADO        : item.FATURADO,
						SALDO_FATURAR	: item.SALDO_FATURAR,
						PRODUZIR		: item.PRODUZIR,
						EMPRODUCAO		: item.EMPRODUCAO,
						AGRUPAMENTO		: item.AGRUPAMENTO,
						ALOCADO		    : item.ALOCADO,
						ENCERRADO		: item.ENCERRADO,
						VALOR_SBT		: item.VALOR_SBT
					});

					ctrl.pedidoItem.calcularTabelaQtdMinima(item);

				}

				ctrl.pedidoItemEscolhido.somaQuantidadeGeral();

			}

			function exibirModal() {

				$('#modal-create').modal('show');
			}

			consultarPedidoItem(pedido);

		}

		/**
		 * Ativar comportamento padrão da tela de Incluir.
		 */
		function ativarIncluir() {

			ctrl.tipoTela 		= 'incluir';
			ctrl.situacaoPedido = '0';
			ctrl.limparTelaEdit();

			ctrl.infoGeral.consultarInfoGeral();
			// angular.copy(ctrl.infoGeral.infoGeralPadrao, ctrl.infoGeral.infoGeral);
		}

		/**
		 * Ativar comportamento padrão da tela de liberação de quantidade mínima para cor.
		 */
		function ativarLiberacao() {

			$('#modal-liberacao').modal('show');
		}

		/**
		 * Limpar campos do modal.
		 */
		function limparTelaEdit() {

			ctrl.infoGeral.infoGeral 						= {};
			ctrl.pedidoItemEscolhido.pedidoItemEscolhido 	= [];
			ctrl.pedidoItemEscolhido.corEscolhida 			= [];
			ctrl.pedidoItemEscolhido.quantidadeGeralTotal 	= 0;
			ctrl.pedidoItemEscolhido.valorGeralTotal 		= 0;
		}

		/**
		 * Fechar modal.
		 */
		function fecharModal() {

			var confirmar = (ctrl.tipoTela == 'exibir') ? false : true;

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
		            			ctrl.limparTelaEdit();
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
				ctrl.limparTelaEdit();
			}


			/**
			 * Verificar se algum campo foi alterado.
			 */
			function verificarCampoAlterado() {

				var ret = false;

				if (
				 	(ctrl.infoGeral.infoGeral.PEDIDO_CLIENTE !== undefined && ctrl.infoGeral.infoGeral.PEDIDO_CLIENTE !== '')
				 ||	(ctrl.infoGeral.infoGeral.OBSERVACAO 	 !== undefined && ctrl.infoGeral.infoGeral.OBSERVACAO 	  !== '')
				 ||	ctrl.pedidoItemEscolhido.pedidoItemEscolhido.length > 0
				)
					ret = true;

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