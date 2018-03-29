/**
 * Factory create do objeto _23038 - Registro de indicadores por centro de custo.
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
		this.indicadorPorCCusto    = {};
		this.indicadorPorCCustoBkp = {};

		// Public methods.
		this.gravar             = gravar;
		this.excluir            = excluir;
		this.limparCampo        = limparCampo;
		this.exibir             = exibir;
		this.habilitarAlteracao = habilitarAlteracao;
		this.cancelarAlteracao  = cancelarAlteracao;
		this.exibirModal        = exibirModal;
		this.fecharModal        = fecharModal;

		// Init methods.
	}
	

	function gravar() {

		obj.indicadorPorCCusto.DATA_INI = moment(obj.indicadorPorCCusto.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.indicadorPorCCusto.DATA_FIM = moment(obj.indicadorPorCCusto.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23038/gravar', obj.indicadorPorCCusto)
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
				.post('/_23038/excluir', obj.indicadorPorCCusto)
				.then(function(response){

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModal();
				});
		}
	}

	function limparCampo() {

		obj.indicadorPorCCusto = {};
	}

	function exibir(indicadorPorCCusto) {

		indicadorPorCCusto.DATA_INI_INPUT 	= moment(indicadorPorCCusto.DATA_INI).toDate();
		indicadorPorCCusto.DATA_FIM_INPUT 	= moment(indicadorPorCCusto.DATA_FIM).toDate();
		indicadorPorCCusto.CCUSTO 			= {};
		indicadorPorCCusto.CCUSTO.CODIGO    = indicadorPorCCusto.CCUSTO_CODIGO;
		indicadorPorCCusto.CCUSTO.MASK      = indicadorPorCCusto.CCUSTO_MASK;
		indicadorPorCCusto.CCUSTO.DESCRICAO = indicadorPorCCusto.CCUSTO_DESCRICAO;
		indicadorPorCCusto.INDICADOR 		= {};
		indicadorPorCCusto.INDICADOR.ID		= indicadorPorCCusto.INDICADOR_ID;
		indicadorPorCCusto.INDICADOR.TITULO	= indicadorPorCCusto.INDICADOR_TITULO;

		obj.indicadorPorCCusto    = indicadorPorCCusto;
		obj.indicadorPorCCustoBkp = angular.copy(indicadorPorCCusto);

		obj.exibirModal();
	}

	function habilitarAlteracao() {

		gScope.Ctrl.tipoTela = 'alterar';
		
		setTimeout(function() { 
			$('.js-input-focus').focus(); 
		}, 100);
	}

	function cancelarAlteracao() {

		angular.extend(obj.indicadorPorCCusto, obj.indicadorPorCCustoBkp);
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