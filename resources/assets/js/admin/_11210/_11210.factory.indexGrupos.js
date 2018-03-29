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