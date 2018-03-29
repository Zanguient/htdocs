angular
    .module('app')
    .factory('Create', Create);
    

Create.$inject = [
    '$ajax',
    '$timeout',
    'gScope',
    '$q',
    '$filter'
];

function Create($ajax, $timeout, gScope, $q, $filter) {

    // Private variables.
    var obj           = null,
        clienteFiltro = false;   // Define se a pesquisa de cliente é para o filtro inicial ou para a tela de cadastro.;

    /**
     * Constructor, with class name.
     */
    function Create() {

        obj = this;

        // Public variables.
        this.pesquisa                = {};
        this.listaModeloPesquisa     = [];
        this.listaCliente            = [];

        // Public methods.
        this.alterarModeloPesquisa              = alterarModeloPesquisa;
        this.consultarModeloPesquisa            = consultarModeloPesquisa;
        this.selecionarModeloPesquisa           = selecionarModeloPesquisa;
        this.consultarModeloPesquisaPergunta    = consultarModeloPesquisaPergunta;
        this.fixVsRepeatPesqPesquisa            = fixVsRepeatPesqPesquisa;
        this.fecharModalPesqPesquisa            = fecharModalPesqPesquisa;
        this.alterarCliente                     = alterarCliente;
        this.consultarCliente                   = consultarCliente;
        this.selecionarCliente                  = selecionarCliente;
        this.limparClienteSelecionado           = limparClienteSelecionado;
        this.fixVsRepeatPesqCliente             = fixVsRepeatPesqCliente;
        this.fecharModalPesqCliente             = fecharModalPesqCliente;
        this.consultarResposta                  = consultarResposta;
        this.alterarResposta                    = alterarResposta;
        this.destacarRespostaNegativa           = destacarRespostaNegativa;
        this.calcularSatisfacao                 = calcularSatisfacao;
        this.gravar                             = gravar;
        this.excluir                            = excluir;
        this.limparCampo                        = limparCampo;
        this.exibirModal                        = exibirModal;
        this.fecharModal                        = fecharModal;
    }
    

    function alterarModeloPesquisa() {

        consultarModeloPesquisa();
        $('#modal-pesq-pesquisa').modal('show');
    }

    function consultarModeloPesquisa() {

        $ajax
            .post('/_26021/consultarModeloPesquisa')
            .then(function(response){

                obj.listaModeloPesquisa = response;

                formataCampoModeloPesquisa();
                obj.fixVsRepeatPesqPesquisa();

                // Foco no input de filtrar.
                $('.input-filtrar-modelo-pesquisa').select();
            });


        function formataCampoModeloPesquisa() {

            var pesq = {};

            for (var i in obj.listaModeloPesquisa) {

                pesq = obj.listaModeloPesquisa[i];

                pesq.DATAHORA_INSERT_HUMANIZE = moment(pesq.DATAHORA_INSERT).format('DD/MM/YYYY');
            }
        }
    }

    function selecionarModeloPesquisa(modelo) {

        obj.pesquisa.MODELO = modelo;

        consultarModeloPesquisaPergunta(modelo);
        fecharModalPesqPesquisa();
    }

    function consultarModeloPesquisaPergunta(modelo) {

        return $q(function(resolve, reject) {

            $ajax
                .post('/_26021/consultarModeloPesquisaPergunta',
                    JSON.stringify(modelo), 
                    {contentType: 'application/json'})
                .then(function(response){

                    obj.pesquisa.PERGUNTA    = response.PERGUNTA;
                    obj.pesquisa.ALTERNATIVA = response.ALTERNATIVA;

                    resolve(response);

                }, function(e) {

                    reject(e);
                });
        });
    }

    /**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqPesquisa() {

        $timeout(function(){
            $('#modal-pesq-pesquisa .table-modelo-pesquisa').scrollTop(0);
        }, 200);

    }

    function fecharModalPesqPesquisa() {
        
        $('#modal-pesq-pesquisa')
            .modal('hide')
            .find('.modal-body')
            .animate({ scrollTop: 0 }, 'fast');

        gScope.Ctrl.filtrarModeloPesquisa = '';
    }

    function alterarCliente(filtro) {

        filtro        = (typeof filtro != 'undefined') ? filtro : false;
        clienteFiltro = filtro;

        consultarCliente();
        $('#modal-pesq-cliente').modal('show');
    }

    function consultarCliente() {

        $ajax
            .post('/_26021/consultarCliente')
            .then(function(response){

                obj.listaCliente = response;

                // Fix para vs-repeat.
                $('.table-cliente')
                    .trigger('resize')
                    .scrollTop(0);

                // Foco no input de filtrar.
                $('.input-filtrar-cliente').select();

            });
    }

    function selecionarCliente(cliente) {

        if (clienteFiltro == false) {
            obj.pesquisa.CLIENTE = cliente;
        }
        else {
            gScope.Ctrl.Index.filtro.CLIENTE = cliente;
            $('.js-input-filtro-cliente').focus();
        }

        fecharModalPesqCliente();
    }

    function limparClienteSelecionado() {

        gScope.Ctrl.Index.filtro.CLIENTE = {ID: null};
    }

    /**
     * Fix para vs-repeat: exibir a tabela completa.
     */
    function fixVsRepeatPesqCliente() {

        $timeout(function(){
            $('#modal-pesq-cliente .table-cliente').scrollTop(0);
        }, 200);

    }

    function fecharModalPesqCliente() {
        
        $('#modal-pesq-cliente')
            .modal('hide')
            .find('.modal-body')
            .animate({ scrollTop: 0 }, 'fast');

        gScope.Ctrl.filtrarCliente = '';
    }

    function alterarResposta(perg, altern) {

        perg.JUSTIFICATIVA_OBRIGATORIA = altern.JUSTIFICATIVA_OBRIGATORIA;

        $timeout(function() {
            calcularSatisfacao();
        }, 100);
    }

    function consultarResposta(pesquisa) {

        return $q(function(resolve, reject) {

            $ajax
                .post('/_26021/consultarResposta', pesquisa)
                .then(function(response){

                    setarRespostaDaPergunta(response);
                    destacarRespostaNegativa();

                    resolve(response);

                }, function(e) {
                    reject(e);
                });
        });


        function setarRespostaDaPergunta(resposta) {

            var perg = {},
                resp = {};

            for (var i in obj.pesquisa.PERGUNTA) {
                
                perg = obj.pesquisa.PERGUNTA[i];

                // Definindo respostas.
                for (var j in resposta) {
                    
                    resp = resposta[j];

                    if (resp.FORMULARIO_PERGUNTA_ID == perg.ID) {

                        perg.RESPOSTA = resp;
                        break;
                    }
                }
            }
        }
    }

    function destacarRespostaNegativa() {
        
        var prg = {},
            alt = {};

        for (var i in obj.pesquisa.PERGUNTA) {

            prg = obj.pesquisa.PERGUNTA[i];

            for (var j in obj.pesquisa.ALTERNATIVA) {

                alt = obj.pesquisa.ALTERNATIVA[j];

                if ((prg.RESPOSTA.ALTERNATIVA_ESCOLHIDA_ID == alt.ID) && (parseFloat(alt.NOTA) == 0))
                    prg.RESPOSTA_NEGATIVA = 1;
            }
        }
    }

    function calcularSatisfacao() {

        var prg         = {},
            alt         = {},
            somaNota    = 0;

        // Pergunta (contém a resposta).
        for (var i in obj.pesquisa.PERGUNTA) {
            
            prg = obj.pesquisa.PERGUNTA[i];

            // Alternativa.
            for (var j in obj.pesquisa.ALTERNATIVA) {

                alt = obj.pesquisa.ALTERNATIVA[j];

                if ( (typeof prg.RESPOSTA != 'undefined')
                    && (alt.ID == prg.RESPOSTA.ALTERNATIVA_ESCOLHIDA_ID) ) {

                    somaNota += parseFloat(alt.NOTA);
                    break;
                }
            }
        }

        obj.pesquisa.SATISFACAO = somaNota;
    }

    function gravar() {

        if (!verificarCampo()) return false;
        formatarCampo();

        $ajax
            .post('/_26021/gravar',
                JSON.stringify(obj.pesquisa), 
                {contentType: 'application/json'})
            .then(function(response) {

                showSuccess('Gravado com sucesso.');
                gScope.Ctrl.Index.filtrar();
                fecharModal(false);
            });


        function verificarCampo() {

            var ret = true;

            if (typeof obj.pesquisa.MODELO == 'undefined' || obj.pesquisa.MODELO.length > 0) {
                showErro('Escolha um formulário.');
                ret = false;
            }
            else if (typeof obj.pesquisa.CLIENTE == 'undefined' || obj.pesquisa.CLIENTE.length > 0) {
                showErro('Escolha um cliente.');
                ret = false;
            }

            return ret;
        }

        function formatarCampo() {

            obj.pesquisa.SATISFACAO = parseFloat($filter('number')(obj.pesquisa.SATISFACAO).replace(',', '.'));
        }
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
                .post('/_26021/excluir',
                    JSON.stringify(obj.pesquisa), 
                    {contentType: 'application/json'})
                .then(function(response){

                    showSuccess('Excluído com sucesso.');
                    gScope.Ctrl.Index.filtrar();
                    fecharModal(false);
                });
        }
    }

    function limparCampo() {

        obj.pesquisa = {};
    }

    function exibirModal() {

        $('#modal-create').modal('show');
    }

    function fecharModal(confirmar) {

        if (typeof confirmar == 'undefined')                    
            confirmar = (gScope.Ctrl.tipoTela == 'exibir') ? false : true;

        if (confirmar) {

            addConfirme(
                '<h4>Confirmação</h4>',
                'Os dados serão perdidos. Deseja continuar?',
                [obtn_sim, obtn_nao],
                [
                    {
                        ret: 1,
                        func: function() {

                            fechar();
                        }
                    },
                    {
                        ret: 2,
                        func: function() {}
                    }
                ]
            );
        }
        else {

            fechar();
        }

        function fechar() {
        
            $('#modal-create')
                .modal('hide')
                .find('.modal-body')
                .animate({ scrollTop: 0 }, 'fast');

            obj.limparCampo();
        }
    }


    /**
     * Return the constructor function
     */
    return Create;
};