
	_29012CreateController.$inject = ['_29012CreateService'];

	function _29012CreateController(_29012CreateService) {

		var ctrl = this;

		// MÉTODOS (REFERÊNCIAS)
		ctrl.limparCampo  			 = limparCampo;
		ctrl.exibirModalArquivoTodos = exibirModalArquivoTodos;
		ctrl.fecharModalArquivoTodos = fecharModalArquivoTodos;
		ctrl.fecharModal  			 = fecharModal;

		// VARIÁVEIS
		ctrl.arquivoTodos = [];

		// Métodos iniciados ao carregar página.
		ctrl.$onInit = function() {

			ctrl.index29012.create = this;
		};

		// MÉTODOS

		/**
		 * Limpar campos.
		 */
		function limparCampo() {

			ctrl.infoGeral.infoGeral = {};
			ctrl.infoGeral.STATUS 	 = '1';
			ctrl.tarefa.tarefa 		 = [];
			ctrl.arquivoTodos 		 = [];

			ctrl.tarefa.excluirArquivoTmpPorUsuario();
		}

		function exibirModalArquivoTodos() {

			$('#modal-create-arquivo-todos')
				.modal('show');
		}

		function fecharModalArquivoTodos() {
			
			$('#modal-create-arquivo-todos')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast');
		}

		/**
		 * Fechar modal.
		 */
		function fecharModal() {
			
			$('#modal-create')
				.modal('hide')
				.find('.modal-body')
				.animate({ scrollTop: 0 }, 'fast');

			// Foco na linha da tabela do workflow.
			var workflowId = $('#modal-create').data('workflowId');
			$('tr.workflow-'+workflowId).focus();
			
			ctrl.limparCampo();
		}

	}