angular
    .module('app')
    .factory('Index', Index);
    

Index.$inject = [
    '$ajax',
    '$timeout',
    'gScope'
];

function Index($ajax, $timeout, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function Index() {

        obj = this;

        // Public variables
        this.filtro = {
            STATUS          : '',
            CLIENTE         : {
                ID: null
            },
            DATA_INI_INPUT  : moment().subtract(3, 'month').toDate(),
            DATA_FIM_INPUT  : moment().toDate()
        };
        this.listaPesquisa = [];

        // Public methods
        this.filtrar            	= filtrar;
        this.eventoFiltrarCliente 	= eventoFiltrarCliente;
        this.formataCampo       	= formataCampo;
        this.exibirPesquisa     	= exibirPesquisa;
    }
    

    function filtrar() {

        obj.filtro.DATA_INI = moment(obj.filtro.DATA_INI_INPUT).format('DD.MM.YYYY') +' 00:00:00';
        obj.filtro.DATA_FIM = moment(obj.filtro.DATA_FIM_INPUT).format('DD.MM.YYYY') +' 23:59:59';

        $ajax
            .post('/_26021/consultarPesquisa',
                JSON.stringify(obj.filtro), 
                {contentType: 'application/json'})
            .then(function(response){

                obj.listaPesquisa = response;
                formataCampo();
            });
    }

    function formataCampo() {

        var pesq = {};

        for (var i in obj.listaPesquisa) {

            pesq = obj.listaPesquisa[i];

            pesq.DATAHORA_INSERT_INPUT      = moment(pesq.DATAHORA_INSERT).toDate();
            pesq.DATAHORA_INSERT_HUMANIZE   = moment(pesq.DATAHORA_INSERT).format('DD/MM/YYYY');
        }
    }

    function eventoFiltrarCliente($event) {

    	// enter
    	if ($event.keyCode == 13)
    		gScope.Ctrl.Create.alterarCliente(true);

    	// backspace ou delete
    	else if ($event.keyCode == 8 || $event.keyCode == 46)
    		gScope.Ctrl.Create.limparClienteSelecionado();
    }

    function exibirPesquisa(pesquisa) {

        var create = gScope.Ctrl.Create;

        gScope.Ctrl.tipoTela = 'exibir';

        create.pesquisa = {
            ID                   : pesquisa.ID,
            SATISFACAO           : pesquisa.SATISFACAO,
            STATUS               : pesquisa.STATUS,
            NOTA_DELFA           : pesquisa.NOTA_DELFA,
            OBSERVACAO_DELFA     : pesquisa.OBSERVACAO_DELFA,
            DATAHORA_INSERT      : pesquisa.DATAHORA_INSERT,
            DATAHORA_INSERT_INPUT: moment(pesquisa.DATAHORA_INSERT).toDate(),
            MODELO               : {
                ID       : pesquisa.FORMULARIO_ID,
                TITULO   : pesquisa.TITULO,
                DESCRICAO: pesquisa.DESCRICAO
            },
            CLIENTE              : {
                ID         : pesquisa.CLIENTE_ID,
                RAZAOSOCIAL: pesquisa.RAZAOSOCIAL
            }
        };
        
        create
        	.consultarModeloPesquisaPergunta(create.pesquisa.MODELO)
        	.then(function() {
        		
        		create
        			.consultarResposta(pesquisa)
        			.then(function() {
        				
        				create.exibirModal();
        			});
        	});
    }

    /**
     * Return the constructor function
     */
    return Index;
};