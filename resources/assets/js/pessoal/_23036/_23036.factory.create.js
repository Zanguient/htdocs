/**
 * Factory create do objeto _23036 - Cadastro de avaliação de desempenho.
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

		// Public variables
		this.avaliacao 			= {};
		this.avaliacao.BASE		= {};
		this.avaliacao.BASE_BKP = {};

		// Public methods
		this.init 	            		 = init;
		this.formatarCampo 				 = formatarCampo;
		this.gravarBase         		 = gravarBase;
		this.excluirBase        		 = excluirBase;
		this.limparCampo        		 = limparCampo;
		this.habilitarAlteracaoBase 	 = habilitarAlteracaoBase;
		this.cancelarAlteracaoBase  	 = cancelarAlteracaoBase;
		this.exibirModalBase    		 = exibirModalBase;
		this.fecharModalBase    		 = fecharModalBase;

		// Init methods.
		this.init();
	}

	function init() {

		obj.avaliacao.BASE.STATUS = '1';
		obj.formatarCampo();
	}

	function formatarCampo() {

		obj.avaliacao.BASE.DATA_AVALIACAO_INPUT = moment().toDate();
	}

	function gravarBase() {

		obj.avaliacao.BASE.DATA_AVALIACAO = moment(obj.avaliacao.BASE.DATA_AVALIACAO_INPUT).format('YYYY-MM-DD');
		
		$ajax
			.post('/_23036/gravarBase', obj.avaliacao.BASE)
			.then(function(response) {

				showSuccess('Gravado com sucesso.');
				gScope.Ctrl.Index.filtrar();
				obj.fecharModalBase();
			});
	}

	function excluirBase() {

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
				.post('/_23036/excluirBase', obj.avaliacao.BASE)
				.then(function(response) {

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModalBase();
				});
		}
	}

	function limparCampo() {

		obj.avaliacao.BASE			= {};
		obj.avaliacao.BASE.STATUS 	= '1';
		obj.avaliacao.BASE.MODELO	= {};
		obj.avaliacao.BASE.CCUSTO	= [];

		obj.formatarCampo();
	}

	function habilitarAlteracaoBase() {

		angular.copy(obj.avaliacao.BASE, obj.avaliacao.BASE_BKP);
		gScope.Ctrl.tipoTela = 'alterar';
	}

	function cancelarAlteracaoBase() {

		angular.copy(obj.avaliacao.BASE_BKP, obj.avaliacao.BASE);
		
		// Setar o modelo novamente, pois no processo de cópia ele perde o $$hashKey.
		obj.avaliacao.BASE.MODELO = selectById(gScope.Ctrl.CreateModelo.listaModelo, obj.avaliacao.BASE.MODELO.ID);

		gScope.Ctrl.tipoTela = 'exibir';
	}

	function exibirModalBase() {

		$('#modal-create').modal('show');
	}

	function fecharModalBase() {

		$('#modal-create')
			.modal('hide')
			.find('.modal-body')
			.animate({ scrollTop: 0 }, 'fast');

		obj.limparCampo();		
	}

	/**
	 * Return the constructor function
	 */
	return Create;
};