/**
 * _12040 - Registro de Pedidos
 */
'use strict';

angular
	.module('app', [
		'vs-repeat', 
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-find'
	])
;
		
angular
	.module('app')
	.service('PedidoIndexService', PedidoIndexService)
	.controller('PedidoIndexController', PedidoIndexController)
	.component('pedidoIndex12040', {
		templateUrl: '/_12040/viewPedidoIndex',
		controller: 'PedidoIndexController'
	})
;

    PedidoIndexService.$inject = ['$ajax', '$filter'];

    function PedidoIndexService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
        this.verificarUsuarioEhRepresentante    = verificarUsuarioEhRepresentante;
        this.consultarRepresentanteDoCliente    = consultarRepresentanteDoCliente;
        this.consultarPedido                    = consultarPedido;
        this.consultarPedido2                   = consultarPedido2;
    	this.consultarPedidoItem                = consultarPedidoItem;


    	// MÉTODOS

    	/**
         * Verificar se usuário é representante.
         */
        function verificarUsuarioEhRepresentante() {

            var url = '/_12040/verificarUsuarioEhRepresentante';

            return $ajax.post(url, null, {contentType: 'application/json'});

        }

        /**
         * Consultar representante do cliente.
         */
        function consultarRepresentanteDoCliente() {

            var url = '/_12040/consultarRepresentanteDoCliente';

            return $ajax.post(url, null, {contentType: 'application/json'});

        }

        /**
         * Consultar pedido.
         */
        function consultarPedido(filtro) {

            var url = '/_12040/consultarPedido';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

        /**
         * Consultar pedido.
         */
        function consultarPedido2(filtro) {

            var url = '/_12040/consultarPedido2';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

        /**
         * Consultar item de pedido.
         */
        function consultarPedidoItem(filtro) {

            var url = '/_12040/consultarPedidoItem';

            return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

        }

	}


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
angular
	.module('app')
	.service('RepresentanteService', RepresentanteService)
	.controller('RepresentanteController', RepresentanteController)
	.component('representante12060', {
		templateUrl: '/_12060/modalConsultarRepresentante',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		controller: 'RepresentanteController'
	})
;
RepresentanteService.$inject = ['$ajax', '$filter'];

function RepresentanteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarRepresentante = consultarRepresentante;
	

	// MÉTODOS

	/**
	 * Consultar representante.
	 */
    function consultarRepresentante() {

		var url = '/_12060/consultarRepresentante';

		return $ajax.post(url, null, {contentType: 'application/json'});

	}

}

RepresentanteController.$inject = ['RepresentanteService', '$scope'];

function RepresentanteController(RepresentanteService, $scope) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarRepresentantePorUrl  	= consultarRepresentantePorUrl;
	ctrl.consultarRepresentante  		= consultarRepresentante;
	ctrl.selecionarRepresentante 		= selecionarRepresentante;
	ctrl.fecharModal			 		= fecharModal;

	// VARIÁVEIS
	ctrl.listaRepresentante	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.filtro = this;

		ctrl.representanteIdUrl = parseInt(getURLParameter('representanteId'));
		ctrl.consultarRepresentantePorUrl();
	};


	// MÉTODOS

	/**
	 * Consultar e selecionar representante através de parâmetro na URL.
	 */
	function consultarRepresentantePorUrl() {

		RepresentanteService
			.consultarRepresentante()
			.then(function(response) {

				ctrl.listaRepresentante = response;

				var rep = null;

				for (var i in ctrl.listaRepresentante) {

					rep = ctrl.listaRepresentante[i];

					if (rep.CODIGO == ctrl.representanteIdUrl) {

						ctrl.pedidoIndex12040.filtro.representante = rep;
						ctrl.pedidoIndex12040.filtroCliente.consultarClientePorRepresentantePorUrl();
						break;
					}
				}
			});
	}

	/**
	 * Consultar representante.
	 */
	function consultarRepresentante() {

		// Verificação para consultar apenas uma vez.
		if ( ctrl.listaRepresentante.length == 0 ) {

			RepresentanteService
				.consultarRepresentante()
				.then(function(response) { 
					ctrl.listaRepresentante = response; 
				})
			;

		}

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-representante')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input para filtrar.
			$('.input-filtrar-representante').select();

		}, 500);

	}

	/**
	 * Selecionar representante.
	 */
	function selecionarRepresentante(representante) {

		ctrl.pedidoIndex12040.filtro.representante = representante;
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-representante')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarRepresentante = '';
	}

}
angular
	.module('app')
	.service('ClientePorRepresentanteService', ClientePorRepresentanteService)
	.controller('ClientePorRepresentanteController', ClientePorRepresentanteController)
	.component('clientePorRepresentante12070', {
		templateUrl: '/_12070/modalConsultarClientePorRepresentante',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		controller: 'ClientePorRepresentanteController'
	})
;
ClientePorRepresentanteService.$inject = ['$ajax', '$filter'];

function ClientePorRepresentanteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarClientePorRepresentante = consultarClientePorRepresentante;
	

	// MÉTODOS

	/**
	 * Consultar cliente por representante.
	 */
    function consultarClientePorRepresentante(representanteId) {

		var url = '/_12070/consultarClientePorRepresentante',
			data = {
				representanteId: representanteId
			}
		;

		return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});

	}

}

ClientePorRepresentanteController.$inject = ['ClientePorRepresentanteService'];

function ClientePorRepresentanteController(ClientePorRepresentanteService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarClientePorRepresentantePorUrl	= consultarClientePorRepresentantePorUrl;
	ctrl.consultarClientePorRepresentante  		= consultarClientePorRepresentante;
	ctrl.selecionarClientePorRepresentante 		= selecionarClientePorRepresentante;
	ctrl.fecharModal			 				= fecharModal;

	// VARIÁVEIS
	ctrl.listaClientePorRepresentante	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.filtroCliente = this;

		ctrl.clienteIdUrl = parseInt(getURLParameter('clienteId'));
	};


	// MÉTODOS

	/**
	 * Consultar e selecionar cliente por representante através de parâmetro na URL.
	 */
	function consultarClientePorRepresentantePorUrl() {

		var representanteId = ctrl.pedidoIndex12040.filtro.representante.CODIGO;

		ClientePorRepresentanteService
			.consultarClientePorRepresentante(representanteId)
			.then(function(response) {

				ctrl.listaClientePorRepresentante = response;

				var cli = null;

				for (var i in ctrl.listaClientePorRepresentante) {

					cli = ctrl.listaClientePorRepresentante[i];

					if (cli.CODIGO == ctrl.clienteIdUrl) {

						ctrl.pedidoIndex12040.filtroCliente.cliente = cli;
						ctrl.pedidoIndex12040.consultarPedidoPorUrl();
						break;
					}
				}
			});
	}

	/**
	 * Consultar cliente por representante.
	 */
	function consultarClientePorRepresentante() {

		var representanteId = (ctrl.pedidoIndex12040.representanteId === null)
								? parseInt(ctrl.pedidoIndex12040.filtro.representante.CODIGO) 
								: parseInt(ctrl.pedidoIndex12040.representanteId)
		;

		ClientePorRepresentanteService
			.consultarClientePorRepresentante(representanteId)
			.then(function(response) { 
				ctrl.listaClientePorRepresentante = response; 
			})
		;

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-por-representante')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input para filtrar.
			$('.input-filtrar-cliente').select();

		}, 500);

	}

	/**
	 * Selecionar cliente por representante.
	 */
	function selecionarClientePorRepresentante(cliente) {

		ctrl.pedidoIndex12040.filtroCliente.cliente = cliente;
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-por-representante')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarCliente = '';
	}

}
		
angular
	.module('app')
	.service('PedidoCreateService', PedidoCreateService)
	.controller('PedidoCreateController', PedidoCreateController)
	.component('pedidoCreate12040', {
		templateUrl: '/_12040/viewPedidoCreate',
		require: {
			pedidoIndex12040: '^pedidoIndex12040'
		},
		bindings: {
			permissaoMenu: '=',
			tipoTela: '=',
			situacaoPedido: '=',
			consultarPedido: '&',
			fecharModal: '&'
		},
		controller: 'PedidoCreateController'
	})
;

    PedidoCreateService.$inject = ['$ajax', '$filter'];

    function PedidoCreateService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
        this.store   = store;
    	this.excluir = excluir;


    	// MÉTODOS

    	/**
    	 * Gravar.
    	 */
	    function store(dados) {

			var url = '/_12040/store';

			return $ajax.post(url, JSON.stringify(dados), {contentType: 'application/json'});

		}

        /**
         * Excluir.
         */
        function excluir(dados) {

            var url = '/_12040/excluir';

            return $ajax.post(url, JSON.stringify(dados), {contentType: 'application/json'});

        }

	}


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
		
angular
	.module('app')
	.service('InfoGeralService', InfoGeralService)
	.controller('InfoGeralController', InfoGeralController)
	.component('infoGeral12040', {
		templateUrl: '/_12040/viewInfoGeral',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoCreate12040: '^pedidoCreate12040'
		},
		bindings: {
			tipoTela: '='
		},
		controller: 'InfoGeralController'
	})
;

    InfoGeralService.$inject = ['$ajax', '$filter'];

    function InfoGeralService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarInfoGeral = consultarInfoGeral;
        this.gerarChave         = gerarChave;
        this.getPDF             = getPDF;


    	// MÉTODOS

    	/**
    	 * Consultar informações gerais.
    	 */
	    function consultarInfoGeral(clienteId) {

			var url = '/_12040/consultarInfoGeral',
                data = {
                    CLIENTE_ID: clienteId
                }
            ;

			return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});
		}

        /**
         * Gerar chave para liberação de nova quantidade mínima para cor.
         */
        function gerarChave() {

            var url = '/_12040/gerarChave';

            return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});
        }

        /**
         * PDF do pedido
         */
        function getPDF(dados) {

            var url = '/_12040/getPDF';

            return $ajax.post(url, dados, {contentType: 'application/json'});

        }

	}


	InfoGeralController.$inject = ['$scope','InfoGeralService', 'Historico'];

	function InfoGeralController($scope, InfoGeralService, Historico) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.consultarInfoGeral = consultarInfoGeral;
		ctrl.marcarProgramado 	= marcarProgramado;
		ctrl.ngWinPopUp         = ngWinPopUp;
		ctrl.gerarChave 		= gerarChave;
		ctrl.gerarPDF           = gerarPDF;
        ctrl.Historico          = new Historico('$ctrl.Historico',$scope);

		// VARIÁVEIS
		ctrl.infoGeral 			= {};

		this.$onInit = function() {

			ctrl.pedidoIndex12040.infoGeral  = this;
			ctrl.pedidoCreate12040.infoGeral = this;

			ctrl.FILE = {VISIVEL: false, DATA: null}
		};


		// MÉTODOS

		/**
		 * Consultar Informações Gerais.
		 */
		function consultarInfoGeral() {

			var clienteId = ctrl.pedidoIndex12040.filtroCliente.cliente
							? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) 
							: 0
			;
 
			InfoGeralService
				.consultarInfoGeral(clienteId)
				.then(function(response) { 
					ctrl.infoGeral = response;
					$('#modal-create').modal('show');
				})
			;

		}

		/**
		 * Consultar Informações Gerais.
		 */
		function gerarPDF() {

			var dados = {
				PEDIDO : ctrl.infoGeral,
				ITENS  : ctrl.pedidoIndex12040.pedidoItemEscolhido.pedidoItemEscolhido
			}

			console.log(InfoGeralService);

			InfoGeralService
				.getPDF(dados)
				.then(function(response) { 
					
					ctrl.FILE.DATA   = response;
					

					$('.visualizar-arquivo').css('display','block');
					$('.visualizar-arquivo').find('object').attr('data',response);
					$('.visualizar-arquivo').find('a').attr('href',response);
					
					if(ctrl.FILE.VISIVEL == false){
					  $(document).on('click','.esconder-arquivo', function(){
					  	$('.visualizar-arquivo').css('display','none');
					  });
					}

					ctrl.FILE.VISIVEL = true;
					

				})
			; 

			//console.log();
			//console.log();

		}

		/**
		 * Gerar chave para liberação de nova quantidade mínima para cor.
		 */
		function gerarChave() {
 
			InfoGeralService
				.gerarChave()
				.then(function(response) { 
					ctrl.infoGeral.CHAVE = response.CHAVE;
				})
			;
		}

		/**
		 * Ações ao marcar a opção 'Programado'.
		 */
		function marcarProgramado() {
			
			if (ctrl.infoGeral.PROGRAMADO == '1') {

				if (ctrl.infoGeral.DATA_CLIENTE === undefined) {

					ctrl.infoGeral.DATA_MIN_CLIENTE 	= moment().add(1, "month").format('YYYY-MM-DD');
					ctrl.infoGeral.DATA_CLIENTE 		= moment().add(1, "month").toDate();
				}

			}
			// else {

			// 	ctrl.infoGeral.DATA_MIN_CLIENTE 	= '';
			// 	ctrl.infoGeral.DATA_CLIENTE 		= '';

			// }

		}
        
        
        function ngWinPopUp(url,id,params) {
            var modal = winPopUp(url,id,params);
            
//            modal.onbeforeunload = function(){ 
//                $scope.$apply(function(){
//
//                    ctrl.pedidoIndex12040.consultarPedido().then(function(response){
//                        var objeto = selectById(response,id,'PEDIDO');
//
//                        ctrl.pedidoIndex12040.exibirPedido(objeto);
//                    });
//                    // processar após fechar modal
//                });
//            };
        };        

	}
		
	angular
		.module('app')
		.service('PedidoItemEscolhidoService', PedidoItemEscolhidoService)
		.controller('PedidoItemEscolhidoController', PedidoItemEscolhidoController)
		.component('pedidoItemEscolhido12040', {
			templateUrl: '/_12040/viewPedidoItemEscolhido',
			require: {
				pedidoIndex12040: '^pedidoIndex12040',
				pedidoCreate12040: '^pedidoCreate12040'
			},
			bindings: {
				tipoTela: '='
			},
			controller: 'PedidoItemEscolhidoController'
		})
	;

    PedidoItemEscolhidoService.$inject = ['$ajax'];

    function PedidoItemEscolhidoService($ajax) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.consultarQtdLiberada = consultarQtdLiberada;

    	// MÉTODOS
    	
    	/**
    	 * Consultar a quantidade mínima liberada para uma cor.
    	 */
	    function consultarQtdLiberada(param) {

			return $ajax
					.post(
						'/_12040/consultarQtdLiberada', 
						JSON.stringify(param), 
						{contentType: 'application/json'}
					);
		}

	}


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
		
	angular
		.module('app')
		.service('PedidoItemService', PedidoItemService)
		.service('ProdutoPorModeloECorService', ProdutoPorModeloECorService)		
		.controller('PedidoItemController', PedidoItemController)
		.component('pedidoItem12040', {
			templateUrl: '/_12040/viewPedidoItem',
			require: {
				pedidoIndex12040: '^pedidoIndex12040',
				pedidoCreate12040: '^pedidoCreate12040'
			},
			bindings: {
				pedidoItemEscolhido: '=',
				corEscolhida: '=',
				somaQuantidadeGeral: '&',
				definirDataCliente: '&'
			},
			controller: 'PedidoItemController'
		})
	;

    PedidoItemService.$inject = ['$ajax', '$filter'];

    function PedidoItemService($ajax, $filter) {

    	this.consultarTamanhoComPreco 		= consultarTamanhoComPreco;
    	this.consultarQtdEPrazoPorTamanho 	= consultarQtdEPrazoPorTamanho;

	    function consultarTamanhoComPreco(filtro) {

			var url = '/_12040/consultarTamanhoComPreco';

			return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

		}

		function consultarQtdEPrazoPorTamanho(filtro) {

			var url = '/_12040/consultarQtdEPrazoPorTamanho';

			return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

		}

	}

// Injetando Components ao Controller.
PedidoItemController.$inject = ['PedidoItemService', 'ProdutoPorModeloECorService', '$scope'];

/**
 * Controller.
 */
function PedidoItemController(PedidoItemService, ProdutoPorModeloECorService, $scope) {

	var ctrl = this;

	/**
	 * MÉTODOS (REFERÊNCIAS).
	 */
	ctrl.consultarProdutoPorModeloECor 	= consultarProdutoPorModeloECor;
	ctrl.consultarTamanhoComPreco		= consultarTamanhoComPreco;
	ctrl.somaQuantidade 				= somaQuantidade;
	ctrl.consultarQuantidadeMin			= consultarQuantidadeMin;
	ctrl.incluirPedidoItem				= incluirPedidoItem;
	ctrl.verificarQuantidadeModelo 		= verificarQuantidadeModelo;
	ctrl.arredondarQuantidadeModelo		= arredondarQuantidadeModelo;
	ctrl.calcularTabelaQtdMinima 		= calcularTabelaQtdMinima;
	ctrl.preencherTabelaQtdMinima 		= preencherTabelaQtdMinima;
	ctrl.verificarCampoPedidoItem		= verificarCampoPedidoItem;
	ctrl.alterarModelo					= alterarModelo;
	ctrl.alterarCor 					= alterarCor;
	ctrl.limparPedidoItem				= limparPedidoItem;
	ctrl.limparQuantidade				= limparQuantidade;
	ctrl.fecharModal					= fecharModal;

	/**
	 * VARIÁVEIS.
	 */
	ctrl.pedidoItem = {};
	// ctrl.pedidoItem.qtdValida = false;

	this.$onInit = function() {

		ctrl.pedidoIndex12040.pedidoItem  = this;

	};
	
	// Consultar produto ao escolher uma cor.
	$scope.$watch('$ctrl.pedidoItem.cor', function() {

		if (ctrl.pedidoItem.modelo != undefined) {
			
			ctrl.consultarProdutoPorModeloECor(
				ctrl.pedidoItem.modelo.MODELO_CODIGO, 
				ctrl.pedidoItem.cor.CODIGO
			);
		}
	});


	/**
	 * MÉTODOS.
	 */

	/**
	 * Consultar Produto por Modelo e Cor.
	 */
	function consultarProdutoPorModeloECor(modeloId, corId) {

		if (corId == undefined)
			return false;

		ProdutoPorModeloECorService
			.consultarProdutoPorModeloECor(modeloId, corId)
			.then(function(response) {

				ctrl.pedidoItem.produto = response;
				ctrl.consultarTamanhoComPreco();
			})
		;

	}

	/**
	 * Consultar Tamanho com Preço.
	 */
	function consultarTamanhoComPreco() {

		var filtro = {
			CLIENTE_ID 	: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) 
							? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) 
							: 0,
			PRODUTO_ID 	: ctrl.pedidoItem.produto.CODIGO,
			MODELO_ID 	: ctrl.pedidoItem.modelo.MODELO_CODIGO,
			GRADE_ID 	: ctrl.pedidoItem.produto.GRADE_CODIGO,
			COR_ID		: ctrl.pedidoItem.cor.CODIGO
		};

		PedidoItemService
			.consultarTamanhoComPreco(filtro)
			.then(function(response) {

				ctrl.tamanhoPreco = response; 

				// Verificar se todos os tamanhos estão bloqueados para pedido.
				var tam = '',
					blq = 0
				;

				for (var i in ctrl.tamanhoPreco) {

					tam = ctrl.tamanhoPreco[i];
					blq += (parseInt(tam.BLQ_PED));
				}

				if (ctrl.tamanhoPreco.length === blq)
					showAlert('SKU bloqueado para pedido.');
			})
		;
	}

	/**
	 * Somar quantidade e valor unitário dos tamanhos escolhidos pelo cliente.
	 */
	function somaQuantidade() {

		var qtd 		= 0,
			somaQtd 	= 0,
			somaVlrUnit = 0
		;

		// ctrl.pedidoItem.qtdValida = false;

		for(var i in ctrl.tamanhoPreco) {

			qtd = parseInt(ctrl.tamanhoPreco[i]['quantidade']);

			if (isNaN(qtd)) 
				continue;

			somaQtd 	+= qtd;
			somaVlrUnit += ( (qtd > 0) ? (parseFloat(ctrl.tamanhoPreco[i]['TAMANHO_PRECO']) * qtd) : 0 );

		}

		ctrl.quantidadeTotal = somaQtd;
		ctrl.valorTotal 	 = somaVlrUnit;

	}

	/**
	 * Verificar se a quantidade passada é adequada para o tamanho.
	 *
	 * @param json tamanho
	 * @param integer qtd
	 * @param json cor
	 * @param json pedidoItemEscolhido
	 */
	function consultarQuantidadeMin(tamanho, qtd, cor, pedidoItemEscolhido) {

		var filtro = {
				COR_ID		: ctrl.pedidoItem.cor.CODIGO,
				DATA 		: moment().format('YYYY-MM-DD'),
				PRODUTO_ID 	: ctrl.pedidoItem.produto.CODIGO,
				TAMANHO_ID 	: tamanho.TAMANHO,
				MODELO_ID	: ctrl.pedidoItem.modelo.MODELO_CODIGO,
				FAMILIA_ID	: ctrl.pedidoItem.modelo.FAMILIA_CODIGO,
				CHAVE 		: ctrl.pedidoCreate12040.infoGeral.infoGeral.CHAVE
			}
		;

		PedidoItemService
			.consultarQtdEPrazoPorTamanho(filtro)
			.then(function(response) {

				var	corSobEnc 		= parseFloat(response.COR_SOBENCOMENDA),
					pzoCliCorSobEnc	= parseInt(response.PZO_CLI_COR_SOBENC),
					pzoCliCorEstMin	= parseInt(response.PZO_CLI_COR_ESTMIN),
					pzoCliCorNormal = parseInt(response.PZO_CLI_COR_NORMAL),
					dataIdeal		= '',
					estMin 			= parseInt(response.EST_MIN),
					perfilId 		= response.ID,
					perfilDescricao	= response.DESCRICAO
				;				
				
				ctrl.calcularTabelaQtdMinima(response, qtd, cor);

				// Define a data ideal (previsão de faturamento) por item.
				if (corSobEnc === 1)
					dataIdeal = moment().add(pzoCliCorSobEnc, 'days');
				else if (estMin === 1)
					dataIdeal = moment().add(pzoCliCorEstMin, 'days');
				else
					dataIdeal = moment().add(pzoCliCorNormal, 'days');

				pedidoItemEscolhido.dataIdeal = moment(dataIdeal).format('DD/MM/YYYY');

				// Perfil de sku do item.
				pedidoItemEscolhido.perfilId 		= perfilId;
				pedidoItemEscolhido.perfilDescricao = perfilDescricao;

				// Define a data ideal (previsão de faturamento) do pedido,
				// ou seja, a maior data entre os itens.
				if (ctrl.tamanhoPreco.indexOf(tamanho) == (ctrl.tamanhoPreco.length - 1))	// último item da lista com os tamanhos e quantidades.
					ctrl.definirDataCliente();


				// Verificar se a quantidade digitada é maior que a quantidade mínima 
				// e múltipla da quantidade múltipla.
				/*if ( (qtd < qtdMin) || ((qtd % qtdMult) != 0) ) {

					showErro('Quantidade deve ser maior que '+qtdMin+' e múltipla de '+qtdMult+'.');

					tamanho.quantidade 			= null;
					ctrl.pedidoItem.qtdValida 	= false;

					ctrl.somaQuantidade();	// ng-change não é acionado quando o valor é alterado via script.
				}
				else {

					ctrl.pedidoItem.qtdValida = true;
				}*/

			})
		;

	}

	/**
	 * Incluir itens ao pedido.
	 */
	function incluirPedidoItem() {

		if (!verificarCampoPedidoItem())
			return false;

		var tamPreco  	= 0,
			tamId	  	= 0,
			tamDesc	  	= 0,
			qtd 	  	= 0,
			vlr 	  	= 0,
			sequencia 	= 0,
			cor 	  	= ''
		;

		if (ctrl.pedidoItemEscolhido == undefined)
			ctrl.pedidoItemEscolhido = [];

		else if (ctrl.pedidoItemEscolhido.length > 0)
			sequencia = ctrl.pedidoItemEscolhido.length;

		for (var i in ctrl.tamanhoPreco) {

			tamPreco 	= ctrl.tamanhoPreco[i];
			qtd 	 	= parseInt(tamPreco.quantidade);
			vlr 	 	= parseFloat(tamPreco.TAMANHO_PRECO);
			tamId	 	= parseInt(tamPreco.TAMANHO);
			tamDesc	 	= parseInt(tamPreco.TAMANHO_DESCRICAO);

			if ( qtd > 0 ) {

				sequencia += 1;
				
				ctrl.pedidoItemEscolhido.push({
					sequencia 		: sequencia,
					modelo 	 		: ctrl.pedidoItem.modelo,
					produto 		: ctrl.pedidoItem.produto,
					cor 			: ctrl.pedidoItem.cor,
					tamanhoId		: tamId,
					tamanhoDescricao: tamDesc,
					quantidade 		: qtd,
					valorUnitario	: vlr,
					valorTotal		: vlr * qtd,
					estMin 			: tamPreco.EST_MIN
				});

				ctrl.consultarQuantidadeMin(
					tamPreco, 
					qtd, 
					ctrl.pedidoItem.cor, 
					ctrl.pedidoItemEscolhido[ctrl.pedidoItemEscolhido.length - 1]
				);
			}			
		}

		ctrl.somaQuantidadeGeral();
		ctrl.limparPedidoItem();
		ctrl.fecharModal();

	}

	/**
	 * Definir informações para a tabela de quantidade mínima das cores.
	 *
	 * Quantidade e cor virão como parâmetro quando um item estiver sendo incluído;
	 * do contrário, esses campos vem da consulta de item de pedido (no parâmetro item) (ao exibir).
	 */
	function calcularTabelaQtdMinima(item, qtd, cor) {

		qtd = (qtd !== undefined) ? qtd : parseFloat(item.QUANTIDADE);
		
		if (cor === undefined) {
			cor 			= {};
			cor.CODIGO 		= item.COR_ID;
			cor.DESCRICAO 	= item.COR_DESCRICAO;
		}

		var qtdMinPerfil  	= parseFloat(item.QTD_MIN),
			qtdMinModelo  	= parseFloat(item.QTD_MIN_MODELO),
			qtdMinSobEnc  	= parseFloat(item.QTD_MIN_SOBENC),
			qtdMinLiberada 	= parseInt(item.QTD_MIN_LIBERADA),
			qtdMultPerfil 	= parseFloat(item.QTD_MULT),
			qtdMultModelo 	= parseFloat(item.QTD_MULT_MODELO),
			qtdMultSobEnc 	= parseFloat(item.QTD_MULT_SOBENC),
			corSobEnc 		= parseFloat(item.COR_SOBENCOMENDA),
			qtdMin 			= 0,
			qtdMult 		= 0
		;

		// Define a quantidade mínima:
		// se a cor tiver quantidade mínima redefinida (liberada através da chave), essa será considerada;
		// se a cor for sobencomenda e tiver quantidade mínima, essa será considerada;
		// se houver quantidade mínima no perfil, ela será considerada;
		// senão, será considerada a definida no modelo.
		if (qtdMinLiberada > 0)
			qtdMin = qtdMinLiberada;
		else if (corSobEnc === 1 && qtdMinSobEnc > 0)
			qtdMin = qtdMinSobEnc;
		else if (qtdMinPerfil > 0)
			qtdMin = qtdMinPerfil;
		else if (qtdMinModelo > 0)
			qtdMin = qtdMinModelo;

		// Define a quantidade múltipla:
		// se a cor for sobencomenda e tiver quantidade múltipla, essa será considerada;
		// se houver quantidade múltipla no perfil, ela será considerada;
		// senão, será considerada a definida no modelo.
		if (corSobEnc === 1 && qtdMultSobEnc > 0)
			qtdMult = qtdMultSobEnc;
		else if (qtdMultPerfil > 0)
			qtdMult = qtdMultPerfil;
		else if (qtdMultModelo > 0)
			qtdMult = qtdMultModelo;

		// Tabela de quantidade mínima por cor.
		ctrl.preencherTabelaQtdMinima(qtd, qtdMin, qtdMult, cor);
	}

	/**
	 * Preencher tabela de quantidade mínima das cores.
	 */
	function preencherTabelaQtdMinima(qtd, qtdMin, qtdMult, cor) {

		var item 	  = '',
			corExiste = false,
			corEsc 	  = ''
		;

		// Cores escolhidas vazia
		if (ctrl.corEscolhida.length === 0) {

			ctrl.corEscolhida.push({
				codigo 				: cor.CODIGO,
				descricao 			: cor.DESCRICAO,
				quantidade 			: qtd,
				quantidadeMinima	: qtdMin,
				quantidadeMultipla	: qtdMult
			});
		}
		else {

			for (var j in ctrl.corEscolhida) {

				corEsc = ctrl.corEscolhida[j];

				if (parseInt(corEsc.codigo) === parseInt(cor.CODIGO)) {

					corExiste = true;
					corEsc.quantidade += qtd;
				}
			}

			// se a cor não foi escolhida ainda
			if (corExiste === false) {

				ctrl.corEscolhida.push({
					codigo 				: cor.CODIGO,
					descricao 			: cor.DESCRICAO,
					quantidade 			: qtd,
					quantidadeMinima	: qtdMin,
					quantidadeMultipla	: qtdMult
				});
			}
		}

	}

	/**
	 * Verificar campos.
	 */
	function verificarCampoPedidoItem() {

		var ret = true,
			qtd	= 0
		;

		if (ctrl.pedidoItem == undefined) {
			showErro('Campos vazios.');
			ret = false;
		}
		else if (ctrl.pedidoItem.modelo == undefined) {
			showErro('Escolha um modelo.');
			ret = false;
		}
		else if (ctrl.pedidoItem.cor == undefined) {
			showErro('Escolha uma cor.');
			ret = false;	
		}
		// Quantidades.
		else {

			// Analisar quantidades da grade.
			for (var i in ctrl.tamanhoPreco)
				qtd += (ctrl.tamanhoPreco[i].quantidade === undefined) ? 0 : parseFloat(ctrl.tamanhoPreco[i].quantidade);

			if (qtd === 0) {
				showErro('Digite a quantidade.');
				ret = false;
			}

			// if (ctrl.pedidoItem.qtdValida == undefined || ctrl.pedidoItem.qtdValida == false) {
			// 	ret = false;
			// }

			else if ( !ctrl.verificarQuantidadeModelo() ) {
				showErro('Quantidade deve obedecer ao valor mínimo e múltiplo do Modelo.');
				ret = false;
			}

		}

		return ret;

	}

	/**
	 * Verificar se a quantidade passada é adequada para o tamanho de acordo com o modelo.
	 */
	function verificarQuantidadeModelo() {
		
		var tam 			= {},
			ret 			= true,
			qtdMinModelo 	= 0,
			qtdMultModelo 	= 0,
			qtd 			= 0
		;

		// Analisar quantidades da grade.
		for (var i in ctrl.tamanhoPreco) {

			tam = ctrl.tamanhoPreco[i];

			qtdMinModelo  	= parseInt(tam.QTD_MIN_MODELO);
			qtdMultModelo 	= parseInt(tam.QTD_MULT_MODELO);
			qtd 			= (tam.quantidade === undefined || tam.quantidade === null) ? 0 : parseFloat(tam.quantidade);

			if ( (qtd > 0) )
				if ( (qtd < qtdMinModelo) || ((qtd % qtdMultModelo) != 0) )
					ret = false;
		}

		return ret;
	}

	/**
	 * Arredondar quantidade de acordo com a quantidade mínima e múltipla do modelo.
	 */
	function arredondarQuantidadeModelo(tamanhoPreco) {

		var qtd 	= parseFloat(tamanhoPreco.quantidade),
			qtdMin  = parseInt(tamanhoPreco.QTD_MIN_MODELO),
			qtdMult = parseInt(tamanhoPreco.QTD_MULT_MODELO)
		;

		qtd = Math.round(qtd);

		if (qtd < qtdMin)
			tamanhoPreco.quantidade = qtdMin;

		else if ((qtd % qtdMult) != 0)
			tamanhoPreco.quantidade = qtd + qtdMult - qtd % qtdMult;

		ctrl.somaQuantidade();
	}

	/**
	 * Alterar modelo.
	 */
	function alterarModelo() {

		if (ctrl.pedidoItem.cor === undefined) {

			ctrl.modeloPorCliente.consultarModeloPorCliente();
			$('#modal-consultar-modelo-por-cliente').modal('show');
		}
		else {

			addConfirme(
				'<h4>Confirmação</h4>',
	        	'Ao alterar o Modelo, a Cor e as Quantidades serão redefinidas. Deseja continuar?',
	        	[obtn_sim, obtn_nao],
	        	[
	            	{
	            		ret: 1,
	            		func: function() {

	            			ctrl.limparPedidoItem();
	            			ctrl.modeloPorCliente.consultarModeloPorCliente();
	            			$('#modal-consultar-modelo-por-cliente').modal('show');
	            		}
	            	},
	            	{
	            		ret: 2,
	            		func: function() {}
	            	}
	        	]
	        );
		}

	}

	/**
	 * Alterar cor.
	 */
	function alterarCor() {

		// Modelo vazio.
		if (ctrl.pedidoItem.modelo === undefined) {

			showAlert('Escolha um modelo.');
		}
		// Cor vazia.
		else if (ctrl.pedidoItem.cor === undefined) {

			ctrl.corPorModelo.consultarCorPorModelo();
			$('#modal-consultar-cor-por-modelo').modal('show');
		}
		else {

			addConfirme(
				'<h4>Confirmação</h4>',
	        	'Ao alterar a Cor, as Quantidades serão redefinidas. Deseja continuar?',
	        	[obtn_sim, obtn_nao],
	        	[
	            	{
	            		ret: 1,
	            		func: function() {

	            			ctrl.limparQuantidade();
	            			ctrl.corPorModelo.consultarCorPorModelo();
	            			$('#modal-consultar-cor-por-modelo').modal('show');
	            		}
	            	},
	            	{
	            		ret: 2,
	            		func: function() {}
	            	}
	        	]
	        );
		}

	}

	/**
	 * Limpar itens de pedido.
	 */
	function limparPedidoItem() {

		ctrl.pedidoItem 			= {};
		ctrl.pedidoItem.qtdValida 	= false;

		ctrl.limparQuantidade();
	}

	/**
	 * Limpar quantidades.
	 */
	function limparQuantidade() {

		ctrl.tamanhoPreco			= [];
		ctrl.quantidade 			= [];
		ctrl.quantidadeTotal 		= 0;
		ctrl.valorUnitario 			= [];
		ctrl.valorTotal 			= 0;
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		if (ctrl.pedidoItem.modelo !== undefined) {

			addConfirme(
				'<h4>Confirmação</h4>',
	        	'Os dados serão perdidos. Deseja continuar?',
	        	[obtn_sim, obtn_nao],
	        	[
	            	{
	            		ret: 1,
	            		func: function() {

	            			fechar();
	            			ctrl.limparPedidoItem();
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
		else
			fechar();


		function fechar() {

			$('#modal-pedido-item')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast')
			;
		}
		
	}

}
angular
	.module('app')
	.service('ModeloPorClienteService', ModeloPorClienteService)
	.controller('ModeloPorClienteController', ModeloPorClienteController)
	.component('modeloPorCliente27020', {
		templateUrl: '/_27020/modalModeloPorCliente',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoItem12040	: '^pedidoItem12040'
		},
		controller: 'ModeloPorClienteController'
	})
;
ModeloPorClienteService.$inject = ['$ajax', '$filter'];

function ModeloPorClienteService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarModeloPorCliente = consultarModeloPorCliente;
	

	// MÉTODOS

	/**
	 * Consultar modelo por cliente.
	 */
    function consultarModeloPorCliente(filtro) {

		var url = '/_27020/consultarModeloPorCliente';

		return $ajax.post(url, JSON.stringify(filtro), {contentType: 'application/json'});

	}

}

ModeloPorClienteController.$inject = ['ModeloPorClienteService'];

function ModeloPorClienteController(ModeloPorClienteService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarModeloPorCliente 		= consultarModeloPorCliente;
	ctrl.selecionarModeloPorCliente 	= selecionarModeloPorCliente;
	ctrl.verArquivo 					= verArquivo;
	ctrl.excluirArquivo					= excluirArquivo;
	ctrl.excluirArquivoPorUsuario		= excluirArquivoPorUsuario;
	ctrl.fecharModal					= fecharModal;

	// VARIÁVEIS
	ctrl.listaModeloPorCliente	= [];

	this.$onInit = function() {

		ctrl.pedidoIndex12040.modeloPorCliente = this;
		ctrl.pedidoItem12040.modeloPorCliente = this;

	};


	// MÉTODOS

	/**
	 * Consultar modelo por cliente.
	 */
	function consultarModeloPorCliente() {

		var filtro = {
			CLIENTE_ID: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) 
							? ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO 
							: 0
		};

		ModeloPorClienteService
			.consultarModeloPorCliente(filtro)
			.then(function(response) { 
				ctrl.listaModeloPorCliente = response; 
			})
		;

		ctrl.filtrarModeloPorCliente = '';
		
		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-modelo-por-cliente')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-modelo').focus();

		}, 500);

	}

	/**
	 * Selecionar modelo.
	 */
	function selecionarModeloPorCliente($event, modelo) {

		// Não selecionar caso seja clicado para ver amostra.
		if ( $($event.target).hasClass('amostra') || $($event.target).hasClass('amostra-icone') )
			return false;

		ctrl.pedidoItem12040.pedidoItem.modelo = modelo;
		ctrl.fecharModal();

	}

	/**
	 * Ver amostra.
	 */
	function verArquivo(modeloId) {

		var nome = modeloId+'.JPG',
			tipo = 'JPG'
		;

		$('.visualizar-arquivo')
			.children('a')
			.attr('href', '/assets/temp/modelo/'+nome)
			.parent()
			.children('input.arquivo_nome_deletar')
			.val(nome)
			.parent()
			.children('object')
			.attr('data', '/assets/temp/modelo/'+nome)
			.removeClass()
			.addClass(tipo)
			.parent()				
			.fadeIn()
		;
		/*
	    //ajax
		var type	= 'POST',
			url		= '/_27020/verArquivo',
			data	= {
				modeloId: modeloId
			},
			success = function(data) {

	        	var nome = data,
					tipo = nome.split(".").pop()
				;
		
				$('.visualizar-arquivo')
					.children('a')
					.attr('href', '/assets/temp/modelo/'+nome)
					.parent()
					.children('input.arquivo_nome_deletar')
					.val(nome)
					.parent()
					.children('object')
					.attr('data', '/assets/temp/modelo/'+nome)
					.removeClass()
					.addClass(tipo)
					.parent()				
					.fadeIn()
				;

	        }
	    ;
		
		execAjax1(type, url, data, success, null, null, false);
		*/
	}

	/**
	 * Excluir amostra.
	 */
	function excluirArquivo() {

		$('.visualizar-arquivo').fadeOut();

	    //ajax
		var type	= 'POST',
			url		= '/_27020/excluirArquivo',
			data	= {
				arquivo: $('.arquivo_nome_deletar').val()
			}
	    ;
		
		execAjax1(type, url, data, null, null, null, false);
	}

	function excluirArquivoPorUsuario() {

	    //ajax
		var type	= 'POST',
			url		= '/_27020/excluirArquivoPorUsuario'
	    ;
		
		execAjax1(type, url, null, null, null, null, false);
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-modelo-por-cliente')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.excluirArquivoPorUsuario();
	}

}
angular
	.module('app')
	.service('CorPorModeloService', CorPorModeloService)
	.controller('CorPorModeloController', CorPorModeloController)
	.component('corPorModelo27030', {
		templateUrl: '/_27030/viewCorPorModelo',
		require: {
			pedidoIndex12040: '^pedidoIndex12040',
			pedidoItem12040: '^pedidoItem12040'
		},
		controller: 'CorPorModeloController'
	})
;
CorPorModeloService.$inject = ['$ajax', '$filter'];

function CorPorModeloService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarCorPorModelo = consultarCorPorModelo;
	

	// MÉTODOS

	/**
	 * Consultar cor por modelo.
	 */
    function consultarCorPorModelo(param) {

		var url = '/_27030/consultarCorPorModelo';

		return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json'});

	}

}

CorPorModeloController.$inject = ['CorPorModeloService'];

function CorPorModeloController(CorPorModeloService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarCorPorModelo 	= consultarCorPorModelo;
	ctrl.selecionarCorPorModelo = selecionarCorPorModelo;
	ctrl.fecharModal 			= fecharModal;

	// VARIÁVEIS
	ctrl.listaCorPorModelo	= [];

	this.$onInit = function() {

		ctrl.pedidoItem12040.corPorModelo = this;

	};


	// MÉTODOS

	/**
	 * Consultar cor por modelo.
	 */
	function consultarCorPorModelo() {

		var param = {
			CLIENTE_ID 	: (ctrl.pedidoIndex12040.filtroCliente.cliente !== undefined) ? parseInt(ctrl.pedidoIndex12040.filtroCliente.cliente.CODIGO) : 0,
			MODELO_ID 	: parseInt(ctrl.pedidoItem12040.pedidoItem.modelo.MODELO_CODIGO)
		};

		CorPorModeloService
			.consultarCorPorModelo(param)
			.then(function(response) { 
				ctrl.listaCorPorModelo = response; 
			})
		;

		ctrl.filtrarCorPorModelo = '';

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-cor-por-modelo')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-cor-por-modelo').focus();

		}, 500);

	}

	/**
	 * Selecionar cor.
	 */
	function selecionarCorPorModelo(cor) {

		ctrl.pedidoItem12040.pedidoItem.cor = cor;
		ctrl.fecharModal();

	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-cor-por-modelo')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;
		
	}

}
ProdutoPorModeloECorService.$inject = ['$ajax'];

function ProdutoPorModeloECorService($ajax) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarProdutoPorModeloECor = consultarProdutoPorModeloECor;
	

	// MÉTODOS

	/**
	 * Consultar produto por modelo e cor.
	 */
    function consultarProdutoPorModeloECor(modeloId, corId) {

		var url = '/_27050/consultarPorModeloECor',
			data = {
				modeloId: modeloId,
				corId 	: corId
			}
		;

		return $ajax.post(url, JSON.stringify(data), {contentType: 'application/json'});

	}

}

angular
	.module('app')
	.service('ConsultarCorService', ConsultarCorService)
	.controller('ConsultarCorController', ConsultarCorController)
	.component('consultarCor27030', {
		templateUrl: '/_27030/viewConsultarCor',
		require: {
			liberacao12040: '^liberacao12040'
		},
		controller: 'ConsultarCorController'
	})
;
ConsultarCorService.$inject = ['$ajax', '$filter'];

function ConsultarCorService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.consultarCor = consultarCor;
	

	// MÉTODOS

	/**
	 * Consultar cor.
	 */
    function consultarCor() {

		return $ajax.post('/_27030/consultarCor', null, {contentType: 'application/json'});

	}

}

ConsultarCorController.$inject = ['ConsultarCorService'];

function ConsultarCorController(ConsultarCorService) {

	var ctrl = this;

	// MÉTODOS (REFERÊNCIAS)
	ctrl.consultarCor  = consultarCor;
	ctrl.selecionarCor = selecionarCor;
	ctrl.fecharModal   = fecharModal;

	// VARIÁVEIS
	ctrl.listaCor = [];

	this.$onInit = function() {

		ctrl.liberacao12040.consultarCor = this;
	};


	// MÉTODOS

	/**
	 * Consultar cor.
	 */
	function consultarCor() {

		// Verificação para consultar apenas uma vez.
		if (ctrl.listaCor.length == 0) {

			ConsultarCorService
				.consultarCor()
				.then(function(response) { 
					ctrl.listaCor = response; 
				});
		}

		setTimeout(function() {

			// Fix para vs-repeat.
			$('.table-container-consultar-cor')
				.find('.scroll-table')
				.trigger('resize')
				.scrollTop(0);

			// Foco no input de filtrar.
			$('.input-filtrar-cor').select();

		}, 500);
	}

	/**
	 * Selecionar cor.
	 */
	function selecionarCor(cor) {

		var corLiberacao = ctrl.liberacao12040.liberacao.COR;
		corLiberacao.splice(corLiberacao.length-1, 1, cor);
		ctrl.fecharModal();
	}

	/**
	 * Fechar modal.
	 */
	function fecharModal() {

		$('#modal-consultar-cor')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast')
		;

		ctrl.filtrarCor = '';
	}

}
		
angular
	.module('app')
	.service('LiberacaoService', LiberacaoService)
	.controller('LiberacaoController', LiberacaoController)
	.component('liberacao12040', {
		templateUrl: '/_12040/viewLiberacao',
		controller: 'LiberacaoController'
	})
;

    LiberacaoService.$inject = ['$ajax', '$filter'];

    function LiberacaoService($ajax, $filter) {

    	// MÉTODOS (REFERÊNCIAS)
    	this.gravarLiberacao = gravarLiberacao;


    	// MÉTODOS

        /**
         * Gravar liberação de nova quantidade mínima para cor.
         */
        function gravarLiberacao(param) {

            return $ajax.post('/_12040/gravarLiberacao', JSON.stringify(param), {contentType: 'application/json'});
        }

	}


	LiberacaoController.$inject = ['LiberacaoService'];

	function LiberacaoController(LiberacaoService) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS).
		ctrl.addCor 				= addCor;
		ctrl.excluirCor 			= excluirCor;
		ctrl.consultarCorLiberacao 	= consultarCorLiberacao;
		ctrl.gravarLiberacao		= gravarLiberacao;
		ctrl.verificarCampos 		= verificarCampos;
		ctrl.limparModalLiberacao 	= limparModalLiberacao;
		ctrl.fecharModalLiberacao 	= fecharModalLiberacao;
		
		// VARIÁVEIS
		ctrl.liberacao  	= {};
		ctrl.liberacao.COR 	= [];
		ctrl.corPadrao 		= {
			CODIGO 		: null,
			DESCRICAO 	: null,
			AMOSTRA 	: null,
			QUANTIDADE 	: null
		};

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.addCor();
		};


		// MÉTODOS

		/**
		 * Adicionar cor.
		 */
		function addCor() {

			var corNova = {};
			angular.copy(ctrl.corPadrao, corNova);
			ctrl.liberacao.COR.push(corNova);
		}

		/**
		 * Excluir cor.
		 */
		function excluirCor(index) {

			if (ctrl.liberacao.COR.length > 1)
				ctrl.liberacao.COR.splice(index, 1);
		}

		/**
		 * Consultar cor para liberação.
		 */
		function consultarCorLiberacao() {

			ctrl.consultarCor.consultarCor();
			$('#modal-consultar-cor').modal('show');
		}

		/**
		 * Gravar liberação.
		 */
		function gravarLiberacao() {

			ctrl.verificarCampos();
 			
			LiberacaoService
				.gravarLiberacao(ctrl.liberacao)
				.then(function(response) {

					showSuccess('Gravado com sucesso.');
					ctrl.fecharModalLiberacao();
				});
		}

		/**
		 * Verificar campos do modal.
		 */
		function verificarCampos() {

			var ret = true;

			if (ctrl.liberacao.COR == undefined || ctrl.liberacao.COR.length == 0) {
				showAlert('Escolha uma cor.');
				ret = false;
			}

			if (ret == false)
				throw 'Existem campos inválidos.';
		}

		/**
		 * Limpar campos do modal.
		 */
		function limparModalLiberacao() {

			ctrl.liberacao 		= {};
			ctrl.liberacao.COR 	= [];
			ctrl.addCor();
		}

		/**
		 * Fechar modal.
		 */
		function fecharModalLiberacao() {

			$('#modal-liberacao')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast')
			;

			limparModalLiberacao();			
		}

	}
		
angular
	.module('app')
	.service('ChatService', ChatService)
	.controller('ChatController', ChatController)
	.component('chat', {
		templateUrl: '/chat/viewIndex',
		controller: 'ChatController'
	})
;

ChatService.$inject = ['$ajax', '$filter'];

function ChatService($ajax, $filter) {

	// MÉTODOS (REFERÊNCIAS)
	this.gravar 					= gravar;
	this.consultarHistoricoConversa = consultarHistoricoConversa;

	// MÉTODOS

	/**
	 * Gravar conversa.
	 */
	function gravar(dadoMsg) {

        var url = '/chat/gravar',
        	param = {
				REMETENTE_ID	: dadoMsg.DE,
				DESTINATARIO_ID	: dadoMsg.PARA,
				MENSAGEM		: dadoMsg.MSG
			}
		;

        return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json', progress: false});
    }

    /**
	 * Consultar histórico de conversas.
	 */
	function consultarHistoricoConversa(param) {

        var url = '/chat/consultarHistoricoConversa';

        return $ajax.post(url, JSON.stringify(param), {contentType: 'application/json'});
    }
}

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
//# sourceMappingURL=_12040.js.map
