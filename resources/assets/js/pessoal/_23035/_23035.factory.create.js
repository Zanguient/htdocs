/**
 * Factory create do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
 */

angular
	.module('app')
	.factory('Create', Create);    

Create.$inject = [
	'$ajax',
	'gScope'
];

function Create($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function Create() {

		obj = this;

		// Public variables.
		this.modelo         = {};
		this.modeloBkp      = {};

		// Public methods.
		this.init 				= init;
		this.gravar             = gravar;
		this.excluir            = excluir;
		this.limparCampo        = limparCampo;
		this.habilitarAlteracao = habilitarAlteracao;
		this.cancelarAlteracao  = cancelarAlteracao;
		this.exibirModal        = exibirModal;
		this.fecharModal        = fecharModal;

		// Init methods.
		this.init();
	}

	function init() {

		obj.modelo.META_MEDIA_GERAL = 80;
	}

	function gravar() {

		$ajax
			.post('/_23035/gravar', obj.modelo)
			.then(function(response) {

				showSuccess('Gravado com sucesso.');
				gScope.Ctrl.Index.filtrar();
				fecharModal();
			});
	}

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

			$ajax
				.post('/_23035/excluir', obj.modelo)
				.then(function(response){

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModal();
				});
		}
	}

	function limparCampo() {

		obj.modelo 			= {};
		obj.modelo.FATOR 	= [];
		obj.modelo.FORMACAO = [];
		obj.modelo.RESUMO 	= [];

		obj.modelo.META_MEDIA_GERAL = 80;
		gScope.Ctrl.CreateFator.addFator();
		gScope.Ctrl.CreateFormacao.addAllFormacao();
		gScope.Ctrl.CreateResumo.addResumo();
		gScope.Ctrl.CreateResumo.pesoFinal = 0;
	}

	function habilitarAlteracao() {

		gScope.Ctrl.tipoTela = 'alterar';
	}

	function cancelarAlteracao() {

		angular.copy(obj.modeloBkp, obj.modelo);
		gScope.Ctrl.CreateResumo.somarPeso();
		gScope.Ctrl.tipoTela = 'exibir';
	}

	function exibirModal() {

		$('#modal-create').modal('show');
	}

	function fecharModal() {

		$('#modal-create')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.limparCampo();		
	}


	/**
	 * Return the constructor function.
	 */
	return Create;
};