/**
 * Factory index do objeto _23032 - Cadastro de fatores para avalia?o de desempenho.
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
        this.listaFator = [];

        // Public methods
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibirFator      = exibirFator;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        $ajax
            .post('/_23032/consultarFator')
            .then(function(response){

                obj.listaFator = response;
            });
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-titulo').focus(); 
        }, 500);
    }

    function exibirFator(fator) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibirFator(fator);
    }


    /**
     * Return the constructor function
     */
    return Index;
};