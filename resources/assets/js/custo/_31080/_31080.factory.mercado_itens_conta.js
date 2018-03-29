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