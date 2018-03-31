/**
 * Factory IndexItens do objeto _11002 - Usuarios
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
					TBUSUARIO_ID : gScope.vm.Index.NOVO.ID
                };
				
			obj.DADOS = [];

            $ajax.post('/_11002/consultar_itens',ds,{contentType: 'application/json'})
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

            $('#modal-incluir-itens').modal();  
        };

        obj.modalAlterar = function(item){
            obj.ALTERANDO = true;

            obj.NOVO = item;

            $('#modal-incluir-itens').modal();  
        };

        obj.incluir = function(){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente gravar?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                        ITEM : obj.NOVO,
						TBUSUARIO_ID : gScope.vm.Index.NOVO.ID
                    };

                    $ajax.post('/_11002/incluir_itens',ds,{contentType: 'application/json'})
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

                    $ajax.post('/_11002/alterar_itens',ds,{contentType: 'application/json'})
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
                'Deseja realmente excluir o IndexItens ('+obj.SELECTED.DESCRICAO+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : obj.SELECTED
                        };

                    $ajax.post('/_11002/excluir_itens',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Excluido com sucesso!'); 
                            obj.ALTERANDO = false;   
                        }
                    ); 
                }}]     
            );
        };

        obj.atualizarMenusUser = function(item){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja atualizar menus ('+item.NOME+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : item
                        };

                    $ajax.post('/_11002/atualizarMenusUser',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Atualizado!');
                        }
                    ); 
                }}]     
            );
        };

        obj.resetarSenha = function(item){
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente resetar senha ('+item.NOME+')?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    var ds = {
                            ITEM : item
                        };

                    $ajax.post('/_11002/resetarSenhaSuper',ds,{contentType: 'application/json'})
                        .then(function(response) {
                            obj.consultar();
                            showSuccess('Senha Resetada!');  
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