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