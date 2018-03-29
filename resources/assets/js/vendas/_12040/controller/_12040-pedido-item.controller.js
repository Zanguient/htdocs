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