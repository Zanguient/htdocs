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