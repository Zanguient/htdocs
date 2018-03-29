/**
 * Factory create do objeto _23031 - Cadastro de tipos de fatores para avaliação de desempenho.
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
        this.tipo    = {};
        this.tipoBkp = {};

        // Public methods.
        this.gravar             = gravar;
        this.excluir            = excluir;
        this.limparCampo        = limparCampo;
        this.exibirTipo         = exibirTipo;
        this.habilitarAlteracao = habilitarAlteracao;
        this.cancelarAlteracao  = cancelarAlteracao;
        this.exibirModal        = exibirModal;
        this.fecharModal        = fecharModal;

        // Init methods.
    }
    

    function gravar() {

        $ajax
            .post('/_23031/gravar', obj.tipo)
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
                .post('/_23031/excluir', obj.tipo)
                .then(function(response){

                    showSuccess('Excluído com sucesso.');
                    gScope.Ctrl.Index.filtrar();
                    fecharModal();
                });
        }
    }

    function limparCampo() {

        obj.tipo = {};
    }

    function exibirTipo(tipo) {

        obj.tipo    = tipo;
        obj.tipoBkp = angular.copy(tipo);

        obj.exibirModal();
    }

    function habilitarAlteracao() {

        gScope.Ctrl.tipoTela = 'alterar';
        
        setTimeout(function() { 
            $('.js-input-titulo').focus(); 
        }, 100);
    }

    function cancelarAlteracao() {

        angular.extend(obj.tipo, obj.tipoBkp);
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