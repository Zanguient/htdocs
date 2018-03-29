/**
 * Factory create do objeto _23032 - Cadastro de fatores para avaliação de desempenho.
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
        this.fator                  = {};
        this.fator.TIPO_ID          = 1;
        this.fator.ORDEM_PERC_NIVEL = '0';
        this.fator.DESCRITIVO       = [];
        this.descritivoPadrao       = {
            NIVEL_ID  : 1,
            DESCRICAO : ''
        };
        this.fatorBkp               = {};
        this.listaFatorTipo         = [];
        this.listaFatorNivel        = [];

        // Public methods.
        this.init               = init;
        this.consultarInicial   = consultarInicial;
        this.addDescritivo      = addDescritivo;
        this.excluirDescritivo  = excluirDescritivo;
        this.gravar             = gravar;
        this.excluir            = excluir;
        this.limparCampo        = limparCampo;
        this.exibirFator        = exibirFator;
        this.habilitarAlteracao = habilitarAlteracao;
        this.cancelarAlteracao  = cancelarAlteracao;
        this.exibirModal        = exibirModal;
        this.fecharModal        = fecharModal;

        // Init methods.
        this.init();
    }

    function init() {

        obj.consultarInicial();
        addDescritivo();
    }

    function consultarInicial() {

        $ajax
            .post('/_23032/consultarInicial')
            .then(function(response) {

                obj.listaFatorTipo  = response.FATOR_TIPO;
                obj.listaFatorNivel = response.FATOR_NIVEL;
            });
    }

    function addDescritivo() {

        var descritivoNovo = {};
        angular.copy(obj.descritivoPadrao, descritivoNovo);
        obj.fator.DESCRITIVO.push(descritivoNovo);
    }

    function excluirDescritivo(descritivo) {

        if (descritivo.ID > 0)
            descritivo.STATUSEXCLUSAO = '1';
        else
            obj.fator.DESCRITIVO.splice(obj.fator.DESCRITIVO.indexOf(descritivo), 1);

        // Adicionar descritivo quando não houver nenhum.
        if (obj.fator.DESCRITIVO.length == 0)
            obj.addDescritivo();
        else {

            var desc  = {},
                resta = false;

            // Verificar se tem algum descritivo que não tenha sido marcado para excluir.
            for (var i in obj.fator.DESCRITIVO) {
                
                desc = obj.fator.DESCRITIVO[i];

                if (desc.STATUSEXCLUSAO != '1') {

                    resta = true;
                    break;
                }
            }

            if (resta == false)
                obj.addDescritivo();
        }
    }

    function gravar() {

        $ajax
            .post('/_23032/gravar', obj.fator)
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
                .post('/_23032/excluir', obj.fator)
                .then(function(response){

                    showSuccess('Excluído com sucesso.');
                    gScope.Ctrl.Index.filtrar();
                    fecharModal();
                });
        }
    }

    function limparCampo() {

        obj.fator            = {};
        obj.fator.DESCRITIVO = [];
        
        obj.addDescritivo();
    }

    function exibirFator(fator) {

        $ajax
            .post('/_23032/consultarFatorNivelDescritivo', fator)
            .then(function(response) {

                response = formatarCampo(response);

                fator.DESCRITIVO = response;

                obj.fator    = fator;
                obj.fatorBkp = angular.copy(fator);

                obj.exibirModal();
            });


        function formatarCampo(response) {

            var desc = {};

            for (var i in response) {

                desc = response[i];

                desc.FAIXA_INICIAL  = parseFloat(desc.FAIXA_INICIAL);
                desc.FAIXA_FINAL    = parseFloat(desc.FAIXA_FINAL);
            }

            return response;
        }
    }

    function habilitarAlteracao() {

        gScope.Ctrl.tipoTela = 'alterar';
    }

    function cancelarAlteracao() {

        angular.extend(obj.fator, obj.fatorBkp);
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