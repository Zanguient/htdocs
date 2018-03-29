'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils'
	])
;
     
angular
    .module('app')
    .factory('ConsumoBaixadoTransacao', ConsumoBaixadoTransacao);
    

	ConsumoBaixadoTransacao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoTransacao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoTransacao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixadoTransacao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixadoTransacao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }
    };
    
    ConsumoBaixadoTransacao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Balanca.open();

                break;

        }
    };    
    
    ConsumoBaixadoTransacao.prototype.consultar = function() {
        
        var that = this;
        
        $ajax.post('/_22160/api/consumo-baixado/transacao',gScope.ConsumoBaixadoTalao.SELECTED,{progress: false}).then(function(response){
            
            gcCollection.merge(gScope.ConsumoBaixadoTransacao.DADOS, response, ['TIPO','CONSUMO_ID']);
            
        });
    };    
    
    ConsumoBaixadoTransacao.prototype.delete = function(transacao) {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta transação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){
                    
                    var dados = {
                        FILTRO: gScope.ConsumoBaixadoFiltro,
                        FILTRO_TRANSACAO: gScope.ConsumoBaixadoTalao.SELECTED,
                        DADOS: {
                            ITENS : [transacao]
                        }
                    };        

                    $ajax.post('/_22160/api/consumo-baixado/transacao/delete',dados,{progress: false}).then(function(response){

                        gScope.ConsumoBaixadoFiltro.merge(response.DATA_RETURN.DADOS);
                        gcCollection.merge(gScope.ConsumoBaixadoTransacao.DADOS, response.DATA_RETURN.TRANSACOES, ['TIPO','CONSUMO_ID']);

                    });
                    
                });
            }}]     
        );        


    };    

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoTransacao;
};
angular
    .module('app')
    .factory('ConsumoBaixadoTalao', ConsumoBaixadoTalao);
    

	ConsumoBaixadoTalao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoTalao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoTalao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixadoTalao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixadoTalao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
            
            gScope.ConsumoBaixadoTransacao.consultar();
        }

    };
    
    ConsumoBaixadoTalao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixadoTalao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Balanca.open();

                break;

        }
    };    
    
    ConsumoBaixadoTalao.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixadoFiltro,
            FILTRO_TRANSACAO: {ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID},
            DADOS: {
                ITENS : [that.SELECTED],
                QUANTIDADE : that.QUANTIDADE,
                PECA_BARRAS : that.PECA_BARRAS
            }
        };
        
        var input = null;
        
        if ( that.PECA_BARRAS != undefined && that.PECA_BARRAS.length > 0 ) {
            that.QUANTIDADE = null;   
            input = that.Modal.inputPeca();
        }
        if ( that.QUANTIDADE != undefined && parseFloat(that.QUANTIDADE) > 0 ) {
            that.PECA_BARRAS = '';
            input = that.Modal.inputQuantidade();
        }

        that.Modal.enableButton(false);
        
        $ajax.post('/_22160/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.ConsumoBaixadoFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixadoTalao.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    
    ConsumoBaixadoTalao.prototype.imprimirEtiqueta = function() {

        if ( this.SELECTED != undefined && this.SELECTED != {} ) {

            $ajax.post('/_22160/api/etiqueta',{ITENS:[this.SELECTED]}).then(function(response){
                postprint(response);
            });        
        }
    };   

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoTalao;
};
angular
    .module('app')
    .factory('ConsumoBaixadoProduto', ConsumoBaixadoProduto);
    

	ConsumoBaixadoProduto.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoProduto($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoProduto(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixadoProduto = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixadoProduto.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    ConsumoBaixadoProduto.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixadoProduto.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Reposicao.Modal.open();

                break;

        }
    };    
    
    ConsumoBaixadoProduto.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixadoFiltro,
            FILTRO_TRANSACAO: {ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID},
            DADOS: {
                ITENS : [that.SELECTED],
                QUANTIDADE : that.QUANTIDADE,
                PECA_BARRAS : that.PECA_BARRAS
            }
        };
        
        var input = null;
        
        if ( that.PECA_BARRAS != undefined && that.PECA_BARRAS.length > 0 ) {
            that.QUANTIDADE = null;   
            input = that.Modal.inputPeca();
        }
        if ( that.QUANTIDADE != undefined && parseFloat(that.QUANTIDADE) > 0 ) {
            that.PECA_BARRAS = '';
            input = that.Modal.inputQuantidade();
        }

        that.Modal.enableButton(false);
        
        $ajax.post('/_22160/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.ConsumoBaixadoFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixadoProduto.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoProduto;
};
angular
    .module('app')
    .factory('ConsumoBaixadoFiltro', ConsumoBaixadoFiltro);
    

	ConsumoBaixadoFiltro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixadoFiltro($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixadoFiltro(data) {
        if (data) {
            this.setData(data);
        }
        
        
        this.DATA_1 = new Date(Clock.DATETIME_SERVER);
        this.DATA_2 = new Date(Clock.DATETIME_SERVER);
        this.CONSUMO_STATUS = "= '1'";
		gScope.ConsumoBaixadoFiltro = this; 
        
    }
    
    ConsumoBaixadoFiltro.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        
        this.DATAHORA = {
            DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
            DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
        };
        
        $ajax.post('/_22160/api/consumo-baixado',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    
    ConsumoBaixadoFiltro.prototype.merge = function(response) {
        
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        for ( var i in response ) {
            var item = response[i];
            
            for (var k in item){
                if (item.hasOwnProperty(k)) {
                    
                    if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                        item[k] = parseFloat(item[k]);
                    }
                }
            }            
        }

        gcCollection.merge(gScope.ConsumoBaixadoTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixadoTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixadoProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return ConsumoBaixadoFiltro;
};
angular
    .module('app')
    .factory('ConsumoBaixarBalanca', ConsumoBaixarBalanca);
    

	ConsumoBaixarBalanca.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarBalanca($ajax, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarBalanca(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixarBalanca = this; 
        
        var that = this;
        
        this.DADOS           = [];
        this.SELECTED        = {};
        this.PESO            = null;
        this.PESO_AUTOMATICO = false;
        this.FILTRO          = '';
        this.ITENS_BAIXAR    = [];
        this.OPERADOR_BARRAS = '';
        this.OPERADOR_NOME   = '';
        this.OPERADOR_ID     = 0;
        
        var balanca_timeout = null;     
        
        $('.gc-print-recebe-peso').click(function(){
                        
            var str = $(this).val();
            var res = str.replace(",", ".");                        
            var value = parseFloat(res);
            
            var changed = false;

            if ( that.PESO != value ) {
                changed = true;
            }
                
            $rootScope.$apply(function(){
                                
                that.PESO_AUTOMATICO = true;
                that.PESO = parseFloat(value.toFixed(4));
                
                if ( changed ) {
                    that.setItens();
                }
            });
            
            clearTimeout( balanca_timeout );

            balanca_timeout = setTimeout(function(){
                $rootScope.$apply(function(){                
                    that.PESO_AUTOMATICO = false;
                    that.PESO = null;
                    
                    if ( changed ) {
                        that.setItens();
                    }                    
                });
            },3000);            
        });        
        
    }
    
    var modal = $('#modal-balanca');
    var modalOperador = $('#modal-autenticar-operador');
    
    ConsumoBaixarBalanca.prototype.open = function() {
        
        var that = this;
        if ( gScope.ConsumoBaixarTalao.SELECTED != undefined && gScope.ConsumoBaixarTalao.SELECTED != {} ) {
            
            this.SELECTED = gScope.ConsumoBaixarTalao.SELECTED;
            comunicacao.conectar();
            this.show(function(){
                $('#balanca-quantidade-baixar:focusable').first().focus();
            },function(){
                $('.table-container.table-taloes tr.selected').first().focus();                
                comunicacao.desconectar();
                that.PESO_AUTOMATICO = false;
                that.PESO = null;                
            });
        }
        
    };
  

    ConsumoBaixarBalanca.prototype.confirm = function () {

        var that = this;

        var dados = {
            FILTRO   : gScope.ConsumoBaixarFiltro,
            OPERADOR : {NOME: that.OPERADOR_NOME , ID: that.OPERADOR_ID},
            DADOS    : {
               ITENS : that.ITENS_BAIXAR,
               PESO  : that.PESO
            }
        };
        
        
        that.enableButton(false);
        
        $ajax.post('/_22160/api/consumo-baixar/post',dados,{complete: function(){
                
            that.enableButton(true);
            
        }}).then(function(response){
        
            postprint(response.ETIQUETAS);        
        
            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN);
            that.close();
            
        });        
    };

    ConsumoBaixarBalanca.prototype.operadorButton = function(bool) {
        modalOperador.find('button').prop('disabled',!bool);
    };

    ConsumoBaixarBalanca.prototype.altenticarOperador = function (flag) {
        var that = this;

        var dados = {
            COD_BARRAS : that.OPERADOR_BARRAS
        };
        
        that.operadorButton(false);

        $ajax.post('/_22160/api/operador',dados,{complete: function(){
                
            that.operadorButton(true);
            
        }}).then(function(response){
            
            if(Object.keys(response).length > 0){

                if(response.PERMICAO == 1){

                    that.OPERADOR_BARRAS = '';
                    that.OPERADOR_ID     = response.CODIGO;
                    that.OPERADOR_NOME   = response.NOME;

                    modalOperador.modal('hide');

                    if(flag = 1){
                        that.confirm();
                    }

                }else{
                    showErro('Operador não tem permissão.<br>' + response.DESCRICAO);
                    that.OPERADOR_BARRAS = '';
                    modalOperador.find('input').focus();   
                }

            }else{
                showErro('Operador não encontrado!');
                that.OPERADOR_BARRAS = '';
                modalOperador.find('input').focus();
            }           
            
        });    
    };

    ConsumoBaixarBalanca.prototype.modalOperador = function () {
        that.OPERADOR_ID     = 0;
        that.OPERADOR_BARRAS = '';
        that.OPERADOR_NOME   = '';
        
        modalOperador.modal();

        setTimeout(function(){
            modalOperador.find('input').focus();
        },300); 
    };

    ConsumoBaixarBalanca.prototype.setItens = function () {
        
        this.ITENS_BAIXAR = [];
        var array = this.ITENS_BAIXAR;
        
        if ( this.SELECTED.FILTERED == undefined ) {
            array.push(this.SELECTED);
        } else {
            
            var quantidade = 0;
            for ( var i in this.SELECTED.FILTERED ) {
                
                var item = this.SELECTED.FILTERED[i];
                
                quantidade += item.QUANTIDADE_SALDO;
                
                if ( quantidade <= (this.PESO + item.QUANTIDADE_SALDO) ) {
                    array.push(item);                    
                } else {
                    break;
                }
            }
        }
    };  

    ConsumoBaixarBalanca.prototype.show = function(shown,hidden) {

        modal
            .modal('show')
        ;                         
        
        if ( shown ) {
            modal
                .one('shown.bs.modal', function(){
                    shown();
                })
            ;     
        }
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden();
                })
            ;              
        }
    };

    ConsumoBaixarBalanca.prototype.close = function(hidden) {

        modal
            .modal('hide')
        ;
        
        if ( hidden ) {
            modal
                .one('hidden.bs.modal', function(){
                    hidden ? hidden() : '';
                })
            ;                      
        }
    };
    
    ConsumoBaixarBalanca.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    ConsumoBaixarBalanca.prototype.enableButton = function(bool) {
        modal.find('button').prop('disabled',!bool);
    };
 
       
    function isNumber(o) {
        return o == '' || !isNaN(o - 0);
    }    
    
    
    var time = null;
    var comunicacao = {
        conectar: function (){
            
            console.log('conectou');
            $( ".gc-print-open-com" )
                .trigger( "click" )
            ;

            $( ".gc-print-set-config" )
                .trigger( "click" )
            ;

            time = setInterval(function(){ 
                $( ".gc-print-set-config" )
                    .trigger( "click" )
                ;
            },1000);
        },
        desconectar: function (){
            
            console.log('desconectou');
            $( ".gc-print-close-com" )
                .trigger( "click" )
            ;
            clearInterval(time);
        }
    }    
    
    /**
     * Return the constructor function
     */
    return ConsumoBaixarBalanca;
};
angular
    .module('app')
    .factory('ConsumoBaixarTalao', ConsumoBaixarTalao);
    

	ConsumoBaixarTalao.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarTalao($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarTalao(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixarTalao = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixarTalao.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    ConsumoBaixarTalao.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixarTalao.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.ConsumoBaixarBalanca.open();

                break;

        }
    };    
    
    ConsumoBaixarTalao.prototype.confirm = function () {

        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
            FILTRO_TRANSACAO: {ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID},
            DADOS: {
                ITENS : [that.SELECTED],
                QUANTIDADE : that.QUANTIDADE,
                PECA_BARRAS : that.PECA_BARRAS
            }
        };
        
        var input = null;
        
        if ( that.PECA_BARRAS != undefined && that.PECA_BARRAS.length > 0 ) {
            that.QUANTIDADE = null;   
            input = that.Modal.inputPeca();
        }
        if ( that.QUANTIDADE != undefined && parseFloat(that.QUANTIDADE) > 0 ) {
            that.PECA_BARRAS = '';
            input = that.Modal.inputQuantidade();
        }

        that.Modal.enableButton(false);
        
        $ajax.post('/_22160/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixarTalao.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixarTalao.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    

    /**
     * Return the constructor function
     */
    return ConsumoBaixarTalao;
};
angular
    .module('app')
    .factory('ConsumoBaixarProduto', ConsumoBaixarProduto);
    

	ConsumoBaixarProduto.$inject = [
        '$ajax',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarProduto($ajax, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarProduto(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.ConsumoBaixarProduto = this; 
        
        this.DADOS    = [];
        this.SELECTED = {};
        this.FILTRO = '';
    }
    

    ConsumoBaixarProduto.prototype.pick = function(talao,setfocus) {
        
        var that = this;

        if ( talao != undefined ) {
        
            this.SELECTED = talao;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    ConsumoBaixarProduto.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-container.table-taloes .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    ConsumoBaixarProduto.prototype.keypress = function($event) {

        $event.preventDefault();
         
        
        switch ($event.key) {

            case 'Enter':

                gScope.Reposicao.Modal.open();

                break;

        }
    };    
    
    ConsumoBaixarProduto.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
            FILTRO_TRANSACAO: {ESTOQUE_MINIMO_ID: that.SELECTED.ESTOQUE_MINIMO_ID},
            DADOS: {
                ITENS : [that.SELECTED],
                QUANTIDADE : that.QUANTIDADE,
                PECA_BARRAS : that.PECA_BARRAS
            }
        };
        
        var input = null;
        
        if ( that.PECA_BARRAS != undefined && that.PECA_BARRAS.length > 0 ) {
            that.QUANTIDADE = null;   
            input = that.Modal.inputPeca();
        }
        if ( that.QUANTIDADE != undefined && parseFloat(that.QUANTIDADE) > 0 ) {
            that.PECA_BARRAS = '';
            input = that.Modal.inputQuantidade();
        }

        that.Modal.enableButton(false);
        
        $ajax.post('/_22160/api/transacao/post',dados,{complete: function(){

            that.Modal.enableButton(true);

        }}).then(function(response){

            gScope.ConsumoBaixarFiltro.merge(response.DATA_RETURN.DADOS);
            gcCollection.merge(gScope.ConsumoBaixarProduto.DADOS,response.DATA_RETURN.TRANSACOES,['TIPO','TABELA_ID','TABELA_NIVEL']);
            
            input.focus();
            input.val('');
            
            if ( that.SELECTED.ESTOQUE_NECESSIDADE <= 0 ) {
                that.Modal.close(function(){
                    gScope.ConsumoBaixarProduto.setFocus();                  
                });
            }
        },function(){
            input.select();
        });        
    };
    

    /**
     * Return the constructor function
     */
    return ConsumoBaixarProduto;
};
angular
    .module('app')
    .factory('ConsumoBaixarFiltro', ConsumoBaixarFiltro);
    

	ConsumoBaixarFiltro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function ConsumoBaixarFiltro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function ConsumoBaixarFiltro(data) {
        if (data) {
            this.setData(data);
        }
        
        this.CONSUMO_STATUS = "= '0'";
        
		gScope.ConsumoBaixarFiltro = this; 
        
    }
    
    ConsumoBaixarFiltro.prototype.consultar = function() {
        
        var that = this;
            
        
        return $q(function(resolve,reject){

            $ajax.post('/_22160/api/consumo-baixar',that,{progress: false}).then(function(response){

                that.merge(response);
                
                resolve(response);

            },function(e){
                reject(e);
            });

        });
    };
   
    
    ConsumoBaixarFiltro.prototype.merge = function(response) {
        
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        for ( var i in response ) {
            var item = response[i];
            
            for (var k in item){
                if (item.hasOwnProperty(k)) {
                    
                    if ( isNumber(item[k]) && (String(item[k]).substr(0, 1) !== '0' || String(item[k]).indexOf('.') !== -1) ) {               
                        item[k] = parseFloat(item[k]);
                    }
                }
            }            
        }

        gcCollection.merge(gScope.ConsumoBaixarTalao.DADOS, response, 'TALAO_ID');
      
        
        
        
        /**
         * Vinculo dos ConsumoBaixarProdutos - Incio
         */
        
        var produtos = gcCollection.groupBy(gScope.ConsumoBaixarTalao.DADOS, [
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'CONSUMO_PROCESSO_LOCALIZACAO_ID'
        ], 'TALOES',function(produto,talao){
            
            produto.TALAO_QUANTIDADE       == undefined ? produto.TALAO_QUANTIDADE       = 0 : '';
            produto.QUANTIDADE_PROJECAO    == undefined ? produto.QUANTIDADE_PROJECAO    = 0 : '';
            produto.QUANTIDADE_CONSUMO     == undefined ? produto.QUANTIDADE_CONSUMO     = 0 : '';
            produto.QUANTIDADE_SALDO       == undefined ? produto.QUANTIDADE_SALDO       = 0 : '';                
            
            produto.TALAO_UM   == undefined ? produto.TALAO_UM   = talao.TALAO_UM   : '';
            produto.CONSUMO_UM == undefined ? produto.CONSUMO_UM = talao.CONSUMO_UM : '';
            
            produto.TALAO_QUANTIDADE       += talao.TALAO_QUANTIDADE;
            produto.QUANTIDADE_PROJECAO    += talao.QUANTIDADE_PROJECAO;
            produto.QUANTIDADE_CONSUMO     += talao.QUANTIDADE_CONSUMO;
            produto.QUANTIDADE_SALDO       += talao.QUANTIDADE_SALDO;
            produto.CONSUMO_TOLERANCIA_MAX += talao.CONSUMO_TOLERANCIA_MAX;
        });
        
        
        gcCollection.merge(gScope.ConsumoBaixarProduto.DADOS, produtos, ['CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);
        
        /////
                
        
    };

    /**
     * Return the constructor function
     */
    return ConsumoBaixarFiltro;
};
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        'ConsumoBaixarFiltro',
        'ConsumoBaixarProduto',
        'ConsumoBaixarTalao',
        'ConsumoBaixarBalanca',
        'ConsumoBaixadoFiltro',
        'ConsumoBaixadoProduto',
        'ConsumoBaixadoTalao',
        'ConsumoBaixadoTransacao'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        ConsumoBaixarFiltro, 
        ConsumoBaixarProduto,
        ConsumoBaixarTalao, 
        ConsumoBaixarBalanca,
        ConsumoBaixadoFiltro, 
        ConsumoBaixadoProduto,
        ConsumoBaixadoTalao,
        ConsumoBaixadoTransacao
    ) {

		var vm = this;

		vm.ConsumoBaixarFiltro  = new ConsumoBaixarFiltro();
		vm.ConsumoBaixarTalao   = new ConsumoBaixarTalao();
		vm.ConsumoBaixarProduto = new ConsumoBaixarProduto();
		vm.ConsumoBaixarBalanca = new ConsumoBaixarBalanca();

		vm.ConsumoBaixadoFiltro    = new ConsumoBaixadoFiltro();
		vm.ConsumoBaixadoTalao     = new ConsumoBaixadoTalao();
		vm.ConsumoBaixadoProduto   = new ConsumoBaixadoProduto();
		vm.ConsumoBaixadoTransacao = new ConsumoBaixadoTransacao();


        loading('.main-ctrl');    
        vm.ConsumoBaixarFiltro.consultar().then(function(){
            loading('hide');
        });

	}   
  
//# sourceMappingURL=_22160.js.map
