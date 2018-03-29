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