/**
 * Factory create do objeto _23034 - Cadastro de resumo para avaliação de desempenho.
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
		this.resumo             = {};
		this.resumo.FATOR_TIPO  = [];
		this.resumoBkp          = {};
		this.fatorTipoPadrao    = { FATOR_TIPO_ID: 0 };

		// Public methods.
		this.addFatorTipo       = addFatorTipo;
		this.excluirFatorTipo   = excluirFatorTipo;
		this.gravar             = gravar;
		this.excluir            = excluir;
		this.limparCampo        = limparCampo;
		this.exibir             = exibir;
		this.habilitarAlteracao = habilitarAlteracao;
		this.cancelarAlteracao  = cancelarAlteracao;
		this.exibirModal        = exibirModal;
		this.fecharModal        = fecharModal;

		// Init methods.
		this.addFatorTipo();
	}

	function addFatorTipo() {

		var tipoNovo = angular.copy(obj.fatorTipoPadrao);
		obj.resumo.FATOR_TIPO.push(tipoNovo);
	}

	function excluirFatorTipo(tipo) {

		if (tipo.ID > 0)
            tipo.STATUSEXCLUSAO = '1';
        else
            obj.resumo.FATOR_TIPO.splice(obj.resumo.FATOR_TIPO.indexOf(tipo), 1);

        // Adicionar tipo quando não houver nenhum.
        if (obj.resumo.FATOR_TIPO.length == 0)
            addFatorTipo();
        else {

            var tp    = {},
                resta = false;

            // Verificar se tem algum tipo que não tenha sido marcado para excluir.
            for (var i in obj.resumo.FATOR_TIPO) {
                
                tp = obj.resumo.FATOR_TIPO[i];

                if (tp.STATUSEXCLUSAO != '1') {

                    resta = true;
                    break;
                }
            }

            if (resta == false)
                addFatorTipo();
        }
	}

	function gravar() {

		$ajax
			.post('/_23034/gravar', obj.resumo)
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
				.post('/_23034/excluir', obj.resumo)
				.then(function(response){

					showSuccess('Excluído com sucesso.');
					gScope.Ctrl.Index.filtrar();
					fecharModal();
				});
		}
	}

	function limparCampo() {

		obj.resumo              = {};
		obj.resumo.FATOR_TIPO   = [];

		addFatorTipo();
	}

	function exibir(resumo) {

		obj.resumo    = resumo;
		obj.resumoBkp = angular.copy(resumo);

		if (obj.resumo.FATOR_TIPO.length == 0)
			obj.addFatorTipo();

		obj.exibirModal();
	}

	function habilitarAlteracao() {

		gScope.Ctrl.tipoTela = 'alterar';
		
		setTimeout(function() { 
			$('.js-input-descricao').focus(); 
		}, 100);
	}

	function cancelarAlteracao() {

		angular.extend(obj.resumo, obj.resumoBkp);
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