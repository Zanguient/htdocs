/**
 * Factory index do objeto _23036 - Cadastro de avaliação de desempenho.
 */

angular
	.module('app')
	.factory('Index', Index);    

Index.$inject = [
	'$ajax',
	'gScope'
];

function Index($ajax, gScope) {

	// Private variables.
	var obj = null;

	/**
	 * Constructor, with class name.
	 */
	function Index() {

		obj = this;

		// Public variables
		this.filtro 			= {};
		this.listaAvaliacaoBase = [];

		// Public methods
		this.init 				= init;
		this.filtrar 			= filtrar;
		this.habilitarIncluir   = habilitarIncluir;
		this.exibirBase         = exibirBase;

		// Init methods.
		this.init();
	}
	
	function init() {
		
		this.filtro.STATUS			= '1';
		this.filtro.DATA_INI_INPUT 	= moment().subtract(1, "month").toDate();
		this.filtro.DATA_FIM_INPUT 	= moment().toDate();
	}

	function filtrar() {

		obj.filtro.DATA_INI = moment(obj.filtro.DATA_INI_INPUT).format('YYYY-MM-DD');
		obj.filtro.DATA_FIM = moment(obj.filtro.DATA_FIM_INPUT).format('YYYY-MM-DD');

		$ajax
			.post('/_23036/consultarBaseAvaliacao', obj.filtro)
			.then(function(response) {

				obj.listaAvaliacaoBase = response;

				formatarCampo();
			});

		function formatarCampo() {

			var base = {};

			for (var i in obj.listaAvaliacaoBase) {

				base = obj.listaAvaliacaoBase[i];

				base.DATA_AVALIACAO_HUMANIZE = moment(base.DATA_AVALIACAO).format('MMM YYYY');
			}
		}
	}

	function habilitarIncluir() {

		gScope.Ctrl.tipoTela = 'incluir';
	}

	function exibirBase(base) {

		var create = gScope.Ctrl.Create;
		gScope.Ctrl.tipoTela = 'exibir';

		$ajax
			.post('/_23036/consultarBaseCCustoAvaliacao', base)
			.then(function(response) {

				create.avaliacao.BASE 		 = base;
				create.avaliacao.BASE.CCUSTO = response;
				
				formatarCampo();
				carregarModelo();

				create.exibirModalBase();				
			});

		function formatarCampo() {

			create.avaliacao.BASE.DATA_AVALIACAO_INPUT = moment(create.avaliacao.BASE.DATA_AVALIACAO).toDate();
		}

		function carregarModelo() {

			var modelo = {};

			for (var i in gScope.Ctrl.CreateModelo.listaModelo) {

				modelo = gScope.Ctrl.CreateModelo.listaModelo[i];

				if (modelo.ID == create.avaliacao.BASE.AVALIACAO_DES_MODELO_ID) {
					
					create.avaliacao.BASE.MODELO = modelo;
					break;
				}
			}
		}
	}


	/**
	 * Return the constructor function
	 */
	return Index;
};