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