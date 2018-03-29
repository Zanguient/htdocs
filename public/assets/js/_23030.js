/**
 * App do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
 */

'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form'
	])
;
/**
 * Controller do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Index',
	'Create'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	Create
) {

	// Public instance.
	gScope.Ctrl = this;

	// Local instance.
	var $ctrl = this;

	// Global variables.
	$ctrl.tipoTela      = 'listar';
	$ctrl.permissaoMenu = {};
	$ctrl.Historico     = new Historico('$ctrl.Historico', $scope);

	// Objects.
	$ctrl.Index  = new Index();
	$ctrl.Create = new Create();
}
/**
 * Factory index do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
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
        this.listaNivel = [];

        // Public methods
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibirNivel      = exibirNivel;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        $ajax
            .post('/_23030/consultarNivel')
            .then(function(response){

                obj.listaNivel = response;
            });
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-titulo').focus(); 
        }, 500);
    }

    function exibirNivel(nivel) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibirNivel(nivel);
    }


    /**
     * Return the constructor function
     */
    return Index;
};
/**
 * Factory create do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
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
        this.nivel    = {};
        this.nivelBkp = {};

        // Public methods.
        this.gravar             = gravar;
        this.excluir            = excluir;
        this.limparCampo        = limparCampo;
        this.exibirNivel        = exibirNivel;
        this.habilitarAlteracao = habilitarAlteracao;
        this.cancelarAlteracao  = cancelarAlteracao;
        this.exibirModal        = exibirModal;
        this.fecharModal        = fecharModal;

        // Init methods.
    }
    

    function gravar() {

        $ajax
            .post('/_23030/gravar', obj.nivel)
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
                .post('/_23030/excluir', obj.nivel)
                .then(function(response){

                    showSuccess('Excluído com sucesso.');
                    gScope.Ctrl.Index.filtrar();
                    fecharModal();
                });
        }
    }

    function limparCampo() {

        obj.nivel = {};
    }

    function exibirNivel(nivel) {

        obj.nivel    = nivel;
        obj.nivelBkp = angular.copy(nivel);

        obj.exibirModal();
    }

    function habilitarAlteracao() {

        gScope.Ctrl.tipoTela = 'alterar';
        
        setTimeout(function() { 
            $('.js-input-titulo').focus(); 
        }, 100);
    }

    function cancelarAlteracao() {

        angular.extend(obj.nivel, obj.nivelBkp);
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
//# sourceMappingURL=_23030.js.map
