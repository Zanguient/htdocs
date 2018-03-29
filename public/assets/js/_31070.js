/**
 * App do objeto _31070 - Cadastro de Incentivos
 */

'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form'
	])
;
/**
 * Controller do objeto _31070 - Cadastro de Incentivos
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Incentivo'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Incentivo
) {

	// Public instance.
	gScope.vm = this;

	// Local instance.
	var vm = this;

	// Global variables.
	vm.tipoTela      = 'listar';
	vm.permissaoMenu = {};
	vm.Historico     = new Historico('vm.Historico', $scope);

	// Objects.
	vm.Incentivo = new Incentivo();
}
/**
 * Factory index do objeto _31070 - Cadastro de Incentivos
 */


angular
    .module('app')
    .factory('Incentivo', Incentivo);    

Incentivo.$inject = [
    '$ajax',
    'gScope'
];

function Incentivo($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function Incentivo() {

        obj = this;

        // Public variables
        this.filtro = {
            DATA_INI_INPUT: moment().subtract(3, 'month').toDate(),
            DATA_FIM_INPUT: moment().toDate()
        };
        this.dado = {};

        obj.ALTERANDO = false;
        obj.SELECTED  = null;
        obj.DADOS     = []
        obj.DADOS.push({ID:'', DESCRICAO:'', PERCENTUAL: '',PERCENTUAL_IR:''});
        obj.ORDER_BY  = 'ID';

        obj.consultar = function(){
            var ds = {
                    FLAG : 0
                };

            $ajax.post('/_31070/consultar',ds,{contentType: 'application/json'})
                .then(function(response) {
                    obj.DADOS = response;     
                }
            );
        };

        obj.cancelar = function(){
            obj.ALTERANDO = false;
        }

        obj.modalIncluir = function(){
            obj.ALTERANDO = false;

            obj.NOVO = {
                ID : 0,
                DESCRICAO : '',
                PERCENTUAL : 0,
                PERCENTUAL_IR : 0
            };

            $('#modal-incluir').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = {
                ID            : obj.SELECTED.ID,
                DESCRICAO     : obj.SELECTED.DESCRICAO,
                PERCENTUAL    : Number(obj.SELECTED.PERCENTUAL),
                PERCENTUAL_IR : Number(obj.SELECTED.PERCENTUAL_IR)
            };

            $('#modal-incluir').modal();  
        };

        obj.incluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente gravar?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                        ITEM : obj.NOVO
                    };

                    $ajax.post('/_31070/incluir',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            $('#modal-incluir').modal('hide'); 
                            showSuccess('Gravado com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    );
                }}]     
            );

            
        };

        obj.alterar = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente gravar?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                        ITEM : obj.NOVO
                    };

                    $ajax.post('/_31070/alterar',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            $('#modal-incluir').modal('hide'); 
                            showSuccess('Alterado com sucesso!');
                            obj.ALTERANDO = false;  
                        }
                    );
                }}]     
            );
        };

        obj.excluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir o incentivo ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_31070/excluir',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Excluido com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    ); 
                }}]     
            );
        };

        obj.consultar();
    }


    /**
     * Return the constructor function
     */
    return Incentivo;
};
//# sourceMappingURL=_31070.js.map
