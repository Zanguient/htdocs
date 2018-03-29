/**
 * App do objeto _11210 - Cadastro de Perfil de Usuario
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
 * Controller do objeto _11210 - Cadastro de Perfil de Usuario
 */

angular
	.module('app')
	.value('gScope', {})
	.controller('Ctrl', Ctrl);

Ctrl.$inject = [
	'$scope',
	'gScope',
	'Historico',
	'Index',
	'IndexItens',
	'IndexMenus',
	'IndexGrupo',
	'$consulta'
];

function Ctrl( 
	$scope,
	gScope,
	Historico,
	Index,
	IndexItens,
	IndexMenus,
	IndexGrupo,
	$consulta
) {

	// Public instance.
	gScope.vm = this;

	// Local instance.
	var vm = this;

	// Global variables.
	vm.tipoTela      = 'listar';
	vm.permissaoMenu = {};
	vm.Historico     = new Historico('$ctrl.Historico', $scope);

	// Objects.
	vm.Index = new Index();
	vm.IndexItens = new IndexItens();

	vm.Consulta         = new $consulta();
	vm.Consulta_Usuario = vm.Consulta.getNew();
	vm.Consulta_Menu    = vm.Consulta.getNew();
	vm.Consulta_Grupo   = vm.Consulta.getNew();

    vm.Consulta_Usuario.componente             = '.Consulta_Usuario',
    vm.Consulta_Usuario.model                  = 'vm.Consulta_Usuario',
    vm.Consulta_Usuario.option.label_descricao = 'Usuário:',
    vm.Consulta_Usuario.option.obj_consulta    = '/_11210/ConsultaUsuario',
    vm.Consulta_Usuario.option.tamanho_input   = 'input-medio';
    vm.Consulta_Usuario.option.class           = 'ConsultaUsuario';
    vm.Consulta_Usuario.option.tamanho_tabela  = 400;
    vm.Consulta_Usuario.option.obj_ret         = ['ID','USUARIO'];
    vm.Consulta_Usuario.option.campos_tabela   = [['ID','ID'],['USUARIO','Usuário'],['NOME','Descrição']];
	vm.Consulta_Usuario.compile();

	vm.Consulta_Menu.componente             = '.Consulta_Menu',
    vm.Consulta_Menu.model                  = 'vm.Consulta_Menu',
    vm.Consulta_Menu.option.label_descricao = 'Usuário:',
    vm.Consulta_Menu.option.obj_consulta    = '/_11210/ConsultaMenu',
    vm.Consulta_Menu.option.tamanho_input   = 'input-medio';
    vm.Consulta_Menu.option.class           = 'ConsultaMenu';
    vm.Consulta_Menu.option.obj_ret         = ['ID','USUARIO'];
    vm.Consulta_Menu.option.campos_tabela   = [['ID','ID'],['USUARIO','Usuário'],['NOME','Descrição']];
    vm.Consulta_Menu.option.tamanho_tabela  = 400;
	vm.Consulta_Menu.compile();

	vm.Consulta_Grupo.componente             = '.Consulta_Grupo',
    vm.Consulta_Grupo.model                  = 'vm.Consulta_Grupo',
    vm.Consulta_Grupo.option.label_descricao = 'Usuário:',
    vm.Consulta_Grupo.option.obj_consulta    = '/_11210/ConsultaGrupo',
    vm.Consulta_Grupo.option.tamanho_input   = 'input-medio';
    vm.Consulta_Grupo.option.class           = 'ConsultaGrupo';
    vm.Consulta_Grupo.option.tamanho_tabela  = 400;
	vm.Consulta_Grupo.compile();

	vm.Index.consultar();
}
/**
 * Factory index do objeto _11210 - Cadastro de Perfil de Usuario
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

        obj.ALTERANDO = false;
        obj.SELECTED  = null;
        obj.DADOS     = []
        obj.DADOS.push({ID:''});
        obj.ORDER_BY  = 'ID*1';

        obj.consultar = function(){
            var ds = {
                    FLAG : 0
                };
				
			obj.DADOS = [];

            $ajax.post('/_11210/consultar',ds,{contentType: 'application/json'})
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
                ID        : 0,
                DESCRICAO : '',
                STATUS    : '1'
            };

            $('#modal-incluir').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = obj.SELECTED;
            obj.NOVO.STATUS = '' + obj.NOVO.STATUS + '';
			
			gScope.vm.IndexItens.consultar();

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

                    $ajax.post('/_11210/incluir',ds,{contentType: 'application/json'})
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

                    $ajax.post('/_11210/alterar',ds,{contentType: 'application/json'})
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
                'Deseja realmente excluir o Index ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_11210/excluir',ds,{contentType: 'application/json'})
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
    return Index;
};
/**
 * Factory IndexItens do objeto _11210 - Cadastro de Perfil de Usuario
 */

angular
    .module('app')
    .factory('IndexItens', IndexItens);    

IndexItens.$inject = [
    '$ajax',
    'gScope'
];

function IndexItens($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function IndexItens() {

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
        obj.DADOS.push({ID:''});
        obj.ORDER_BY  = 'ID';

        obj.consultar = function(){
            var ds = {
                    FLAG : 0,
					TBUSUARIO_PERFIL_ID : gScope.vm.Index.NOVO.ID
                };
				
			obj.DADOS = [];

            $ajax.post('/_11210/consultar_itens',ds,{contentType: 'application/json'})
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
                ID             : 0
            };

            gScope.vm.Consulta_Usuario.apagar();

            $('#modal-incluir-itens').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = obj.SELECTED.ID;

            $('#modal-incluir-itens').modal();  
        };

        obj.incluir = function(){
            if(gScope.vm.Consulta_Usuario.item.selected == true){
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente gravar?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){

                        obj.NOVO = gScope.vm.Consulta_Usuario.item.dados;

                        var ds = {
                            ITEM : obj.NOVO,
    						PERFIL_ID : gScope.vm.Index.NOVO.ID
                        };

                        $ajax.post('/_11210/incluir_itens',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                obj.consultar();
                                $('#modal-incluir-itens').modal('hide'); 
                                showSuccess('Gravado com sucesso!'); 
                                obj.ALTERANDO = false;   
                            }
                        );
                    }}]     
                );
            }else{
                showErro('Selecione um usuário');    
            }
            
        };

        obj.alterar = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente gravar?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                        ITEM : obj.NOVO
                    };

                    $ajax.post('/_11210/alterar_itens',ds,{contentType: 'application/json'})
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
                'Deseja realmente excluir ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_11210/excluir_itens',ds,{contentType: 'application/json'})
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
    return IndexItens;
};
/**
 * Factory IndexMenus do objeto _11210 - Cadastro de Perfil de Usuario
 */

angular
    .module('app')
    .factory('IndexMenus', IndexMenus);    

IndexMenus.$inject = [
    '$ajax',
    'gScope'
];

function IndexMenus($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function IndexMenus() {

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
        obj.DADOS.push({ID:''});
        obj.ORDER_BY  = 'ID';

        obj.consultar = function(){
            var ds = {
                    FLAG : 0,
					TBUSUARIO_PERFIL_ID : gScope.vm.Index.NOVO.ID
                };
				
			obj.DADOS = [];

            $ajax.post('/_11210/consultar_menu',ds,{contentType: 'application/json'})
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
                ID             : 0
            };

            gScope.vm.Consulta_Menu.apagar();

            $('#modal-incluir-itens').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = obj.SELECTED.ID;

            $('#modal-incluir-itens').modal();  
        };

        obj.incluir = function(){
            if(gScope.vm.Consulta_Usuario.item.selected == true){
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente gravar?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){

                        obj.NOVO = gScope.vm.Consulta_Usuario.item.dados;

                        var ds = {
                            ITEM : obj.NOVO,
    						PERFIL_ID : gScope.vm.Index.NOVO.ID
                        };

                        $ajax.post('/_11210/incluir_menu',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                obj.consultar();
                                $('#modal-incluir-itens').modal('hide'); 
                                showSuccess('Gravado com sucesso!'); 
                                obj.ALTERANDO = false;   
                            }
                        );
                    }}]     
                );
            }else{
                showErro('Selecione um usuário');    
            }
            
        };

        obj.excluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_11210/excluir_menu',ds,{contentType: 'application/json'})
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
    return IndexMenus;
};
/**
 * Factory IndexGrupos do objeto _11210 - Cadastro de Perfil de Usuario
 */

angular
    .module('app')
    .factory('IndexGrupos', IndexGrupos);    

IndexGrupos.$inject = [
    '$ajax',
    'gScope'
];

function IndexGrupos($ajax, gScope) {

    // Private variables.
    var obj = null;

    /**
     * Constructor, with class name.
     */
    function IndexGrupos() {

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
        obj.DADOS.push({ID:''});
        obj.ORDER_BY  = 'ID';

        obj.consultar = function(){
            var ds = {
                    FLAG : 0,
					TBUSUARIO_PERFIL_ID : gScope.vm.Index.NOVO.ID
                };
				
			obj.DADOS = [];

            $ajax.post('/_11210/consultar_grupo',ds,{contentType: 'application/json'})
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
                ID             : 0
            };

            gScope.vm.Consulta_Usuario.apagar();

            $('#modal-incluir-itens').modal();  
        };

        obj.modalAlterar = function(){
            obj.ALTERANDO = true;

            obj.NOVO = obj.SELECTED.ID;

            $('#modal-incluir-itens').modal();  
        };

        obj.incluir = function(){
            if(gScope.vm.Consulta_Usuario.item.selected == true){
                addConfirme('<h4>Confirmação</h4>',
                    'Deseja realmente gravar?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){

                        obj.NOVO = gScope.vm.Consulta_Usuario.item.dados;

                        var ds = {
                            ITEM : obj.NOVO,
    						PERFIL_ID : gScope.vm.Index.NOVO.ID
                        };

                        $ajax.post('/_11210/incluir_grupo',ds,{contentType: 'application/json'})
                            .then(function(response) {
                                obj.consultar();
                                $('#modal-incluir-itens').modal('hide'); 
                                showSuccess('Gravado com sucesso!'); 
                                obj.ALTERANDO = false;   
                            }
                        );
                    }}]     
                );
            }else{
                showErro('Selecione um usuário');    
            }
            
        };

        obj.excluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_11210/excluir_grupo',ds,{contentType: 'application/json'})
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
    return IndexGrupos;
};
//# sourceMappingURL=_11210.js.map
