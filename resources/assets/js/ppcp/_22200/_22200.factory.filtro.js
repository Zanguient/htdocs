angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$q',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $q, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Filtro = this; 
        
        this.FAMILIA_ID = 3;
        this.PRODUTO_ID = '> 0';
        this.STATUS     = '2';
        this.TURNO      = '';
        this.DATA_1     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
        this.DATA_2     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = false;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;

        var dados = {};

        angular.copy(that, dados);

        if ( !that.DATA_TODOS ) {
            var data = "BETWEEN '" + moment(dados.DATA_1).format('DD.MM.YYYY') + "' AND '" + moment(dados.DATA_2).format('DD.MM.YYYY') + "'";
       
            switch (dados.STATUS) {
                case '2':
                    dados.DATA_PRODUCAO = data;
                    break;
                case '3':
                    dados.DATA_LIBERACAO = data;
                    break;

                default:
                    dados.DATA_REMESSA = data;

                    break;
            }
        }          
        delete dados.DATA_1;
        delete dados.DATA_2;
        
        if ( dados.STATUS.trim() != '' ) {
            dados.STATUS = '= ' + dados.STATUS;
        } else {
            delete dados.STATUS;
        }
        
        if ( dados.TURNO.trim() != '' ) {
            dados.TURNO = "= '" + dados.TURNO + "'";
        } else {
            delete dados.TURNO;
        }
        

        gScope.Talao.consultar(dados).then(function(response){

            gcCollection.merge(gScope.Talao.DADOS, response, 'TALAO_ID');
        });
    };
   
    

    /**
     * Return the constructor function
     */
    return Filtro;
};