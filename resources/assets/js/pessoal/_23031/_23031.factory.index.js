/**
 * Factory index do objeto _23031 - Cadastro de tipos de fatores para avaliação de desempenho.
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
        this.listaTipo = [];

        // Public methods
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibirTipo       = exibirTipo;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        $ajax
            .post('/_23031/consultarTipo')
            .then(function(response){

                obj.listaTipo = response;
            });
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-titulo').focus(); 
        }, 500);
    }

    function exibirTipo(tipo) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibirTipo(tipo);
    }


    /**
     * Return the constructor function
     */
    return Index;
};