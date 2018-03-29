/**
 * Factory index do objeto _23038 - Registro de indicadores por centro de custo.
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
        this.filtro                  = {};
        this.listaIndicadorPorCCusto = [];

        // Public methods
        this.init             = init;
        this.filtrar          = filtrar;
        this.habilitarIncluir = habilitarIncluir;
        this.exibir           = exibir;

        // Init methods.
        this.init();
    }
    
    function init() {

        obj.filtro.DATA_INI_INPUT = moment().subtract('1', 'years').toDate();
        obj.filtro.DATA_FIM_INPUT = moment().toDate();
    }

    function filtrar() {

        obj.filtro.DATA_INI = obj.filtro.DATA_INI_INPUT ? moment(obj.filtro.DATA_INI_INPUT).format('YYYY-MM-DD') : null;
        obj.filtro.DATA_FIM = obj.filtro.DATA_FIM_INPUT ? moment(obj.filtro.DATA_FIM_INPUT).format('YYYY-MM-DD') : null;

        $ajax
            .post('/_23038/consultarIndicadorPorCCusto', obj.filtro)
            .then(function(response){

                obj.listaIndicadorPorCCusto = response;
                formatarCampo();
            });

        function formatarCampo() {

            var ind = {};

            for (var i in obj.listaIndicadorPorCCusto) {

                ind = obj.listaIndicadorPorCCusto[i];

                ind.DATA_INI_HUMANIZE = moment(ind.DATA_INI).format('DD/MM/YYYY');
                ind.DATA_FIM_HUMANIZE = moment(ind.DATA_FIM).format('DD/MM/YYYY');
            }
        }
    }

    function habilitarIncluir() {

        gScope.Ctrl.tipoTela = 'incluir';

        setTimeout(function() { 
            $('.js-input-focus').focus(); 
        }, 500);
    }

    function exibir(indicadorPorCCusto) {

        gScope.Ctrl.tipoTela = 'exibir';
        gScope.Ctrl.Create.exibir(indicadorPorCCusto);
    }

    /**
     * Return the constructor function
     */
    return Index;
};