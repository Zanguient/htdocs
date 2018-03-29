/**
 * Factory index do objeto #TelaNO# - #Titulo#
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
        this.filtro = {
            DATA_INI_INPUT: moment().subtract(3, 'month').toDate(),
            DATA_FIM_INPUT: moment().toDate()
        };
        this.dado = {};

        // Public methods
        this.filtrar = filtrar;

        // Init methods.
        this.filtrar();
    }
    

    function filtrar() {

        obj.filtro.DATA_INI = moment(obj.filtro.DATA_INI_INPUT).format('DD.MM.YYYY') +' 00:00:00';
        obj.filtro.DATA_FIM = moment(obj.filtro.DATA_FIM_INPUT).format('DD.MM.YYYY') +' 23:59:59';

        $ajax
            .post('/#TelaNO#/consultar', obj.filtro)
            .then(function(response){

                obj.dado = response[0];
            });
    }


    /**
     * Return the constructor function
     */
    return Index;
};