/**
 * Factory create do objeto _23033 - Cadastro de formação do avaliado para avaliação de desempenho.
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
        this.formacao    = {};
        this.formacaoBkp = {};

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

        $ajax
            .post('/_23033/gravar', obj.formacao)
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
                .post('/_23033/excluir', obj.formacao)
                .then(function(response){

                    showSuccess('Excluído com sucesso.');
                    gScope.Ctrl.Index.filtrar();
                    fecharModal();
                });
        }
    }

    function limparCampo() {

        obj.formacao = {};
    }

    function exibir(formacao) {

        obj.formacao    = formacao;
        obj.formacaoBkp = angular.copy(formacao);

        obj.exibirModal();
    }

    function habilitarAlteracao() {

        gScope.Ctrl.tipoTela = 'alterar';
        
        setTimeout(function() { 
            $('.js-input-descricao').focus(); 
        }, 100);
    }

    function cancelarAlteracao() {

        angular.extend(obj.formacao, obj.formacaoBkp);
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