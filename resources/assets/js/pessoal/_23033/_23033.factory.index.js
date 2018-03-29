/**
 * Factory index do objeto _23033 - Cadastro de formação do avaliado para avaliação de desempenho.
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
        this.listaFormacao = [];

        // Public methods
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibir           = exibir;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        $ajax
            .post('/_23033/consultarFormacao')
            .then(function(response){

                obj.listaFormacao = response;
            });
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-descricao').focus(); 
        }, 500);
    }

    function exibir(formacao) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibir(formacao);
    }


    /**
     * Return the constructor function
     */
    return Index;
};