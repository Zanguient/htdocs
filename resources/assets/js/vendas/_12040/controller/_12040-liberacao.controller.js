
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