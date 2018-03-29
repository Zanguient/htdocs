/**
 * App do objeto _31080 - Cadastro de Mercados
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
	'Mercado',
	'MercadoItens',
	'MercadoItensConta',
	'$compile',
	'$consulta'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Mercado,
	MercadoItens,
	MercadoItensConta,
	$compile,
	$consulta
) {

	// Local instance.
	var vm = this;

	vm.FAMILIA_ID = 3;
	vm.Consulta   = new $consulta();

	// Global variables.
	vm.tipoTela      = 'listar';
	vm.permissaoMenu = {};
	vm.Historico     = new Historico('vm.Historico', $scope);

	vm.Mercado = new Mercado();
	vm.MercadoItens = new MercadoItens();
	vm.MercadoItensConta = new MercadoItensConta();

	vm.ConsultaFamilia = vm.Consulta.getNew();
    vm.ConsultaFamilia.componente              = '.consulta-familia';
    vm.ConsultaFamilia.model                   = 'vm.ConsultaFamilia';
    vm.ConsultaFamilia.option.label_descricao  = 'Família:';
    vm.ConsultaFamilia.option.obj_consulta     = '/_31080/consultarFamilia';
    vm.ConsultaFamilia.option.tamanho_input    = 'input-medio';
    vm.ConsultaFamilia.option.tamanho_tabela   = 260;
    vm.ConsultaFamilia.autoload                = false;

    vm.ConsultaFamilia.compile();

    vm.ConsultaConta = vm.Consulta.getNew();
    vm.ConsultaConta.componente              = '.consulta-conta';
    vm.ConsultaConta.model                   = 'vm.ConsultaConta';
    vm.ConsultaConta.option.label_descricao  = 'Conta:';
    vm.ConsultaConta.option.obj_consulta     = '/_31080/consultarConta';
    vm.ConsultaConta.option.tamanho_input    = 'input-medio';
    vm.ConsultaConta.option.campos_tabela    = [['ID','ID'],['CONTA','CONTA'],['DESCRICAO','DESCRIÇÃO']],
    vm.ConsultaConta.option.tamanho_tabela   = 500;
    vm.ConsultaConta.autoload                = false;

    vm.ConsultaConta.compile();


    vm.ConsultaFamilia.onSelect = function(){
    	if(vm.ConsultaFamilia.item.selected == true){
            vm.FAMILIA_ID = vm.ConsultaFamilia.item.dados.ID;

            vm.Mercado.consultar();
        }
    };

    vm.ConsultaFamilia.onClear = function(){
        vm.Mercado.DADOS     = []
        vm.Mercado.DADOS.push({ID:'', DESCRICAO:'', PERCENTUAL: '',PERCENTUAL_IR:''});
    };

	vm.ConsultaFamilia.filtrar();


	// Public instance.
	gScope.vm = vm;
	
}
/**
 * Factory index do objeto _31080 - Cadastro de Mercados
 */


angular
    .module('app')
    .factory('Mercado', Mercado);    

Mercado.$inject = [
    '$ajax',
    'gScope'
];

function Mercado($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function Mercado() {

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
                    FLAG : 0,
                    FAMILIA_ID : gScope.vm.FAMILIA_ID
                };

            $ajax.post('/_31080/consultar',ds,{contentType: 'application/json'})
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
                ID             : 0,
                DESCRICAO      : '',
                PERC_INCENTIVO : 0,
                INCENTIVO      : '0',
                FAMILIA_ID     : gScope.vm.FAMILIA_ID
            };

            $('#modal-incluir').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            gScope.vm.MercadoItens.consultar();

            obj.NOVO = {
                ID             : obj.SELECTED.ID,
                DESCRICAO      : obj.SELECTED.DESCRICAO,
                PERC_INCENTIVO : Number(obj.SELECTED.PERC_INCENTIVO),
                INCENTIVO      : '' + Number(obj.SELECTED.INCENTIVO) + '',
                FAMILIA_ID     : gScope.vm.FAMILIA_ID
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

                    $ajax.post('/_31080/incluir',ds,{contentType: 'application/json'})
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

                    $ajax.post('/_31080/alterar',ds,{contentType: 'application/json'})
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
                'Deseja realmente excluir o Mercado ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_31080/excluir',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Excluido com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    ); 
                }}]     
            );
        };
    }


    /**
     * Return the constructor function
     */
    return Mercado;
};
/**
 * Factory index do objeto _31080 - Cadastro de Mercados
 */


angular
    .module('app')
    .factory('MercadoItens', MercadoItens);    

MercadoItens.$inject = [
    '$ajax',
    'gScope'
];

function MercadoItens($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function MercadoItens() {

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
                    PADRAO_ID : gScope.vm.Mercado.SELECTED.ID
                };

            $ajax.post('/_31080/consultar_itens',ds,{contentType: 'application/json'})
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
                ID         : 0,
                DESCRICAO  : '',
                PERCENTUAL : 0,
                FATOR      : 0,
                AVOS       : 0,
                USAR_FATOR : '0',
                EDITAVEL   : '0',
                PADRAO_ID  : gScope.vm.Mercado.NOVO.ID,
                INCENTIVO  : '0',
                FRETE      : '0',
                MARGEM     : '0'
            };

            $('#modal-incluir-itens').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            gScope.vm.MercadoItensConta.consultar();

            obj.NOVO = {
                ID         : obj.SELECTED.ID,
                DESCRICAO  : obj.SELECTED.DESCRICAO,
                PERCENTUAL : Number(obj.SELECTED.PERCENTUAL),
                FATOR      : Number(obj.SELECTED.FATOR),
                AVOS       : Number(obj.SELECTED.AVOS),
                USAR_FATOR : obj.SELECTED.USAR_FATOR,
                EDITAVEL   : obj.SELECTED.EDITAVEL,
                PADRAO_ID  : gScope.vm.Mercado.NOVO.ID,
                INCENTIVO  : obj.SELECTED.INCENTIVO,
                FRETE      : obj.SELECTED.FRETE,
                MARGEM     : obj.SELECTED.MARGEM
            };

            $('#modal-incluir-itens').modal();  
        };

        obj.incluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente gravar?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                        ITEM : obj.NOVO
                    };

                    $ajax.post('/_31080/incluir_itens',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            $('#modal-incluir-itens').modal('hide'); 
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

                    $ajax.post('/_31080/alterar_itens',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            $('#modal-incluir-itens').modal('hide'); 
                            showSuccess('Alterado com sucesso!');
                            obj.ALTERANDO = false;  
                        }
                    );
                }}]     
            );
        };

        obj.excluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir o Item ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_31080/excluir_itens',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Excluido com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    ); 
                }}]     
            );
        };
    }


    /**
     * Return the constructor function
     */
    return MercadoItens;
};
/**
 * Factory index do objeto _31080 - Cadastro de MercadoItensContas
 */


angular
    .module('app')
    .factory('MercadoItensConta', MercadoItensConta);    

MercadoItensConta.$inject = [
    '$ajax',
    'gScope'
];

function MercadoItensConta($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function MercadoItensConta() {

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
                    FLAG    : 0,
                    ITEM_ID : gScope.vm.MercadoItens.SELECTED.ID
                };

            $ajax.post('/_31080/consultar_itens_conta',ds,{contentType: 'application/json'})
                .then(function(response) {
                    obj.DADOS = response;     
                }
            );
        };

        obj.cancelar = function(){
            obj.ALTERANDO = false;
            gScope.vm.ConsultaConta.apagar();
        }

        obj.modalIncluir = function(){
            obj.ALTERANDO = false;

            obj.NOVO = {
                ID        : 0,
                DESCRICAO : '',
                CONTA     : 0,
                ITEM_ID   : gScope.vm.MercadoItens.SELECTED.ID
            };

            gScope.vm.ConsultaConta.filtrar();

            $('#modal-incluir-conta').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = {
                ID        : obj.SELECTED.ID,
                DESCRICAO : obj.SELECTED.DESCRICAO,
                CONTA     : obj.SELECTED.CONTA,
                ITEM_ID   : gScope.vm.MercadoItens.SELECTED.ID
            };

            gScope.vm.ConsultaConta.filtrar();

            $('#modal-incluir-conta').modal();  
        };

        obj.incluir = function(){
            if(gScope.vm.ConsultaConta.item.selected == true){
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente gravar?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){

                        obj.NOVO.CONTA = gScope.vm.ConsultaConta.item.dados.CONTA
                        gScope.vm.ConsultaConta.apagar();

                        var ds = {
                            ITEM : obj.NOVO
                        };

                        $ajax.post('/_31080/incluir_itens_conta',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                obj.consultar();
                                $('#modal-incluir-conta').modal('hide'); 
                                showSuccess('Gravado com sucesso!'); 
                                obj.ALTERANDO = false;   
                            }
                        );
                    }}]     
                );
            }else{
                showErro('Selecione uma Conta');
            }            
        };

        obj.alterar = function(){
            if(gScope.vm.ConsultaConta.item.selected == true){
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente gravar?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){

                        obj.NOVO.CONTA = gScope.vm.ConsultaConta.item.dados.CONTA
                        gScope.vm.ConsultaConta.apagar();

                        var ds = {
                            ITEM : obj.NOVO
                        };

                        $ajax.post('/_31080/alterar_itens_conta',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                obj.consultar();
                                $('#modal-incluir-conta').modal('hide'); 
                                showSuccess('Alterado com sucesso!');
                                obj.ALTERANDO = false;  
                            }
                        );
                    }}]     
                );
            }else{
                showErro('Selecione uma Conta');
            } 
        };

        obj.excluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir Conta ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_31080/excluir_itens_conta',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Excluido com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    ); 
                }}]     
            );
        };
    }


    /**
     * Return the constructor function
     */
    return MercadoItensConta;
};
//# sourceMappingURL=_31080.js.map
