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
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$q',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $q, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }

		gScope.Filtro = this; 
        
        this.FAMILIA_ID = 3;
        this.PRODUTO_ID = '> 0';
        this.STATUS     = '2';
        this.TURNO      = '';
//        this.DATA_1     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
//        this.DATA_2     = moment('2017.11.30').toDate();//new Date(Clock.DATETIME_SERVER);
        this.DATA_TODOS = false;
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;

        var dados = {};

        angular.copy(that, dados);

        if ( !that.DATA_TODOS ) {
            var data = "BETWEEN '" + moment(dados.DATA_1).format('DD.MM.YYYY') + "' AND '" + moment(dados.DATA_2).format('DD.MM.YYYY') + "'";
       
            switch (dados.STATUS) {
                case '2':
                    dados.DATA_PRODUCAO = data;
                    break;
                case '3':
                    dados.DATA_LIBERACAO = data;
                    break;

                default:
                    dados.DATA_REMESSA = data;

                    break;
            }
        }          
        delete dados.DATA_1;
        delete dados.DATA_2;
        
        if ( dados.STATUS.trim() != '' ) {
            dados.STATUS = '= ' + dados.STATUS;
        } else {
            delete dados.STATUS;
        }
        
        if ( dados.TURNO.trim() != '' ) {
            dados.TURNO = "= '" + dados.TURNO + "'";
        } else {
            delete dados.TURNO;
        }
        

        gScope.Talao.consultar(dados).then(function(response){

            gcCollection.merge(gScope.Talao.DADOS, response, 'TALAO_ID');
        });
    };
   
    

    /**
     * Return the constructor function
     */
    return Filtro;
};
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Imobilizado', Imobilizado);

	Imobilizado.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function Imobilizado($ajax, $q, $rootScope, $compile,$timeout, $consulta,gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Imobilizado() {
            
            obj = this; 

            // Public methods         
            this.merge = merge;
            this.consultarTodos = consultarTodos; 
            this.consultar      = consultar; 
            this.consultarParcelas = consultarParcelas;
            this.visualizar     = visualizar; 
            this.alterar        = alterar; 
            this.incluir        = incluir; 
            this.confirmar      = confirmar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
            this.depreciar      = depreciar; 
            this.selectedReset  = selectedReset;
            this.Modal          = Modal;
            this.copiar         = copiar;

            this.Flag  = 0;    
            this.RESPONSE = [];
            
            
            this.ARR_STATUS_ANO_MES_TIPO_NF = [];
            this.ARR_STATUS_ANO_MES_TIPO = [];
            this.ARR_STATUS_ANO_MES = [];
            this.ARR_STATUS_ANO = [];
            this.ARR_STATUS = [];
            
            
            this.DADOS = [];
            this.SELECTED = {};
            this.SELECTED_BACKUP = {};
            this.TIPOS = [];
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            
            obj.selectedReset();
	    }
        
        function merge(response) {
            
            
            sanitizeJson(response);
                        
            gcCollection.merge(obj.RESPONSE, response, ['NFE_ID','ID']);
            
            obj.VALOR_GERAL = 0;
            obj.SALDO_MES_GERAL = 0;
            obj.SALDO_GERAL = 0;

            // AGRUPAMENTO NF
            
            var arr_status_ano_mes_tipo_nf = gcCollection.groupBy(obj.RESPONSE,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO',
                'TIPO_ID',
                'TIPO_DESCRICAO',
                'TAXA',
                'NFE_ID',
                'NFE',
                'SERIE',
                'DOC_FISCAL',
                'EMPRESA_RAZAOSOCIAL',
                'NFE_DATA_ENTRADA',
                'NFE_DATA_ENTRADA_TEXT'
            ],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
                
                obj.VALOR_GERAL     += item.VALOR    ;
                obj.SALDO_MES_GERAL += item.SALDO_MES;
                obj.SALDO_GERAL     += item.SALDO    ;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES_TIPO_NF, arr_status_ano_mes_tipo_nf, [
                'STATUS',
                'ANO',
                'MES',
                'TIPO_ID',
                'NFE_ID'
            ]);
            
            //

            // AGRUPAMENTO TIPO
            
            var arr_status_ano_mes_tipo = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES_TIPO_NF,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO',
                'TIPO_ID',
                'TIPO_DESCRICAO',
                'TAXA'
            ],'NFS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES_TIPO, arr_status_ano_mes_tipo, [
                'STATUS',
                'ANO',
                'MES',
                'TIPO_ID'
            ]);
            
            //

            // AGRUPAMENTO MES
            
            var arr_status_ano_mes = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES_TIPO,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO',
                'MES',
                'MES_DESCRICAO'
            ],'TIPOS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO_MES, arr_status_ano_mes, [
                'STATUS',
                'ANO',
                'MES'
            ]);
            
            //

            // AGRUPAMENTO ANO
            
            var arr_status_ano = gcCollection.groupBy(obj.ARR_STATUS_ANO_MES,[
                'STATUS',
                'STATUS_DESCRICAO',
                'ANO'
            ],'MESES',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS_ANO, arr_status_ano, [
                'STATUS',
                'ANO'
            ]);
            
            //

            // AGRUPAMENTO STATUS
            
            var arr_status = gcCollection.groupBy(obj.ARR_STATUS_ANO,[
                'STATUS',
                'STATUS_DESCRICAO'
            ],'ANOS',function(group,item){
                if ( group.VALOR      == undefined ) { group.VALOR      = 0; }
                if ( group.SALDO_MES  == undefined ) { group.SALDO_MES  = 0; }
                if ( group.SALDO      == undefined ) { group.SALDO      = 0; }
                
                group.VALOR     += item.VALOR;
                group.SALDO_MES += item.SALDO_MES;
                group.SALDO     += item.SALDO;
            });

            gcCollection.merge(obj.ARR_STATUS, arr_status, [
                'STATUS'
            ]);
            
            //
            
            
            console.log(obj.ARR_STATUS);
            
            
        }

        function consultarTodos() {
            
            $ajax.post('/_16010/api/imobilizados',{progress : false}).then(function(response){

                obj.merge(response);
//                gcCollection.merge(obj.DADOS, response,'ID');

                var itens = $('.tabela-itens-imobilizado').find('.itens-imobilizado');

                if(itens.length > 0){
                    var iten = itens[0];
                    $(iten).trigger('click');   
                }
                
            });
        }        

        function consultar(id) {
            
            var id = id != undefined ? id : obj.SELECTED.ID;
                
            return $q(function(resolve,reject){
                
                $ajax.post('/_16010/api/imobilizado',{ID:id}).then(function(response){

                    sanitizeJson(response.ITENS);
                    sanitizeJson(response.FRETES);
                    sanitizeJson(response.IMOBILIZADO);
                    
                    response.DATA_DEPRECIACAO =  moment(response.DATA_DEPRECIACAO).toDate();

                    angular.extend(obj.SELECTED,response.IMOBILIZADO);
                    gcObject.bind(obj.SELECTED,response.ITENS,'ID|IMOBILIZADO_ID','ITENS');
                    gcObject.bind(obj.SELECTED,response.FRETES,'ID|IMOBILIZADO_ID','FRETES');

                    obj.SELECTED.DATA_DEPRECIACAO =  moment(obj.SELECTED.DATA_DEPRECIACAO).toDate();

                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }        
        
        function visualizar(id) {
            
            var prepare = function() {
                gScope.Ctrl.ConsultaImobilizadoTipo.Input.value = obj.SELECTED.TIPO_ID + ' - ' + obj.SELECTED.TIPO_DESCRICAO;
                gScope.Ctrl.ConsultaImobilizadoTipo.disable(true);   
                gScope.Ctrl.ConsultaImobilizadoTipo.item.selected = true;

                gScope.Ctrl.IConsultaCcusto.Input.value = obj.SELECTED.CCUSTO + ' - ' + obj.SELECTED.CCUSTO_DESCRICAO;
                gScope.Ctrl.IConsultaCcusto.disable(true);
                gScope.Ctrl.IConsultaCcusto.item.selected = true;

                gScope.Ctrl.ConsultaImobilizadoTipo.ID               = obj.SELECTED.TIPO_ID 
                gScope.Ctrl.ConsultaImobilizadoTipo.DESCRICAO        = obj.SELECTED.TIPO_DESCRICAO;
                gScope.Ctrl.ConsultaImobilizadoTipo.TAXA_DEPRECIACAO = obj.SELECTED.TAXA;
                gScope.Ctrl.ConsultaImobilizadoTipo.VIDA_UTIL        = obj.SELECTED.VIDA_UTIL + 0;
                gScope.Ctrl.IConsultaCcusto.ID                       = obj.SELECTED.CCUSTO;
                gScope.Ctrl.IConsultaCcusto.DESCRICAO                = obj.SELECTED.CCUSTO_DESCRICAO;

                obj.SELECTED.REPLICAR = 1;
                obj.REPLICAR_READONLY = true;
                obj.SELECTED.TOTAL_ITENS = 0;

                obj.consultar(id).then(function(){
                    obj.Modal.show();                
                });   
            };
            
            if ( id > 0 ) {
                obj.consultar(id).then(function(){
                    prepare();
                });
            } else {
                prepare();
            }
              
        
        }
        
        /**
         * Retorna a view da factory
         */
        function incluir() {
            obj.INCLUINDO = true;
            obj.ALTERANDO = true;
            obj.Modal.show();

            obj.selectedReset();
        }
        
        function confirmar() {
            
            if ( !(obj.SELECTED.ITENS.length > 0) ) {
                showErro('Não há componentes para esse imobilizado.');
                return false;
            }
            
            obj.SELECTED.TIPO_ID          = gScope.Ctrl.ConsultaImobilizadoTipo.ID;
            obj.SELECTED.TIPO_DESCRICAO   = gScope.Ctrl.ConsultaImobilizadoTipo.DESCRICAO;
            obj.SELECTED.TIPO_TAXA        = gScope.Ctrl.ConsultaImobilizadoTipo.TAXA_DEPRECIACAO;
            obj.SELECTED.TIPO_VIDA_UTIL   = gScope.Ctrl.ConsultaImobilizadoTipo.VIDA_UTIL;
            obj.SELECTED.CCUSTO           = gScope.Ctrl.IConsultaCcusto.ID;
            obj.SELECTED.CCUSTO_DESCRICAO = gScope.Ctrl.IConsultaCcusto.DESCRICAO;
            
            var dados = {
                DADOS : obj.SELECTED,
                FLAG  : gScope.Ctrl.Imobilizado.Flag
            };
          
            $ajax.post('/_16010/api/imobilizado/gravar',dados).then(function(response){

                obj.ALTERANDO = false;

                if ( obj.INCLUINDO ) {
                    obj.consultarTodos();
                    obj.INCLUINDO = false;
                    obj.selectedReset();
                    obj.Modal.hide();
                }

                if(gScope.Ctrl.Imobilizado.Flag == 2){
                    gScope.Ctrl.ImobilizadoItem.consultar();
                }
                
            });

        }

        function alterar() {
            
            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaImobilizadoTipo.setDataRequest({});
            gScope.Ctrl.IConsultaCcusto.setDataRequest({});
            
            this.ALTERANDO = true;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
        };        
        
        function cancelar() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){

                        obj.ALTERANDO = false;
                        angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);       
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            obj.selectedReset();
                            obj.Modal.hide();
                        }
                    });
                }}]     
            );
        }
        
        function excluir() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir este item?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                        $ajax.post('/_16010/api/imobilizado/excluir',{DADOS: obj.SELECTED}).then(function(response){

                            obj.consultarTodos();
                            obj.selectedReset();
                            obj.Modal.hide();

                        });
                    });
                }}]     
            );
        }
        
        function depreciar() {

            $ajax.post('/_16010/api/imobilizado/depreciar',{DADOS: obj.SELECTED}).then(function(response){
                gScope.Ctrl.ImobilizadoItem.consultar();
            });
        }
        
        function copiar(){
            var copia = angular.copy(this.SELECTED);

            copia.ID = undefined;
            copia.ITENS = [];

            this.SELECTED = copia;

            obj.INCLUINDO = true;
            obj.ALTERANDO = true;

            showSuccess('Copiado.');   
        }      
        

        function consultarParcelas(item) {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/parcelas',{IMOBILIZADO_ID:obj.SELECTED.ID}).then(function(response){

                    obj.PARCELAS = response;
                    
                    
                    $('#modal-imobilizado-parcela')
                        .modal('show')
                        .one('shown.bs.modal', function(){
                            $(this).find('.table-ec').scrollTop(0);
                        })
                    ;                      
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }        
        
        function selectedReset() {

            obj.SELECTED = {
                DATA_DEPRECIACAO : moment().toDate(),
                TIPO_ID: null,
                TIPO_TAXA: null,
                TIPO_VIDA_UTIL: null,
                CCUSTO: '',
                DESCRICAO : '',
                OBSERVACAO : '',
                ITENS : [],
                REPLICAR : 1,
                REPLICAR_READONLY: false
            };

            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            gScope.Ctrl.ConsultaImobilizadoTipo.setDataRequest({});
            gScope.Ctrl.ConsultaImobilizadoTipo.apagar();
            gScope.Ctrl.IConsultaCcusto.setDataRequest({});
            gScope.Ctrl.IConsultaCcusto.apagar();
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-imobilizado');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().focus();

                        if ( shown ) {
                            $rootScope.$apply(function(){
                                shown(); 
                            });
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return Imobilizado;
	};
   
})(window, window.angular);
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('ImobilizadoItem', ImobilizadoItem);

	ImobilizadoItem.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function ImobilizadoItem($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function ImobilizadoItem() {
            
            obj = this; 

            // Public methods         
            this.consultar                      = consultar;
            this.consultarParcelas              = consultarParcelas;
            this.importarDocumentoFiscal        = importarDocumentoFiscal; 
            this.importarDocumentoFiscalSaida   = importarDocumentoFiscalSaida; 
            this.incluir                        = incluir; 
            this.alterar                        = alterar; 
            this.confirmar                      = confirmar; 
            this.cancelar                       = cancelar; 
            this.remover                        = remover; 
            this.keypress                       = keypress; 
            this.pick                           = pick; 
            this.unpick                         = unpick; 
            this.pickToggle                     = pickToggle; 
            this.picked                         = picked; 
            this.pickReverse                    = pickReverse; 
            this.pickAll                        = pickAll; 
            this.unpickAll                      = unpickAll; 
            this.dblClick                       = dblClick;
            this.encerrar                       = encerrar; 
            this.encerrarPickToggle             = encerrarPickToggle; 
            this.encerrarPicked                 = encerrarPicked; 
            this.Modal                          = Modal; 
            
                      
            this.SELECTEDS = [];
            this.SELECTED_OF_LIST = {};
            this.SELECTED = {};
            this.SELECTED_COPY = {};
            this.PARCELAS = [];
            this.TIPOS = [];
            this.NFE = '';
            this.PRODUTO_READONLY = false;
                        
	    }

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/item',{IMOBILIZADO_ID:gScope.Ctrl.Imobilizado.SELECTED.ID}).then(function(response){

                    sanitizeJson(response);
                    gcObject.bind(gScope.Ctrl.Imobilizado.SELECTED,response,'ID|IMOBILIZADO_ID','ITENS');
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                

        function consultarParcelas(item) {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/imobilizado/item/parcelas',{IMOBILIZADO_ITEM_ID:item.ID}).then(function(response){

                    gcObject.bind(item,response,'ID|IMOBILIZADO_ITEM_ID','PARCELAMENTOS');
                    obj.PARCELAS = response;
                    
                    $('#modal-imobilizado-item-parcela').modal('show');
                    $('#modal-imobilizado-item-parcela .table-ec').scrollTop(0);
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        } 

                       
        
        /**
         * Retorna a view da factory
         */
        function importarDocumentoFiscal() {
            
            selected = gScope.Ctrl.Imobilizado.SELECTED;
            gScope.Ctrl.Empresas = [];
            
            $ajax.post('/_16010/api/nf/item',{NFE: obj.NFE}).then(function(response){
                
                sanitizeJson(response);


                var empresas = [];
                var old_string = '';
                angular.forEach(response, function(item, key) {
                    if(old_string != item.FLAG){
                        old_string = item.FLAG;

                        empresas.push({
                            FLAG: item.FLAG,
                            EMPRESA: item.EMPRESA,
                            NOTA: item.NFE,
                            SERIE: item.SERIE,
                            DESC_DATA_ENTRADA: item.DESC_DATA_ENTRADA,
                            DATA_ENTRADA:  item.DATA_ENTRADA});
                    }
                });

                angular.forEach(empresas, function(item, key) {
                    gcObject.bind(item,response,'FLAG','ITENS');
                });

                angular.forEach(empresas, function(item, key) {
                    gcObject.bind(item,response,'FLAG','ITENS');
                });

                obj.NFE = '';

                if(empresas.length == 1){
                    gcCollection.merge(selected.ITENS,empresas[0].ITENS, ['PRODUTO_ID','NFE'], true);
                    showSuccess('Documento fiscal importado com sucesso.');

                    addConfirme('<h4>Confirmação</h4>',
                        'Deseja utilizar a data de entrada da nota ('+empresas[0].DESC_DATA_ENTRADA+') como data de início da depreciação?',
                        [obtn_sim,obtn_nao],
                        [{ret:1,func:function(){
                            $rootScope.$apply(function(){
                               gScope.Ctrl.Imobilizado.SELECTED.DATA_DEPRECIACAO =  moment(empresas[0].DATA_ENTRADA).toDate();
                            });
                        }}]     
                    );

                }else{
                    if(empresas.length > 0){
                        gScope.Ctrl.Empresas = empresas;

                        gScope.Ctrl.AddItensEmpresa = function(itens){
                            gcCollection.merge(selected.ITENS, itens, ['PRODUTO_ID','NFE'], true);
                            gScope.Ctrl.Empresas = [];
                            showSuccess('Documento fiscal importado com sucesso.');

                            addConfirme('<h4>Confirmação</h4>',
                                'Deseja utilizar a data de entrada da nota ('+itens[0].DESC_DATA_ENTRADA+') como data de início da depreciação?',
                                [obtn_sim,obtn_nao],
                                [{ret:1,func:function(){
                                    $rootScope.$apply(function(){
                                       gScope.Ctrl.Imobilizado.SELECTED.DATA_DEPRECIACAO =  moment(itens[0].DATA_ENTRADA).toDate();
                                    });
                                }}]     
                            );
                        }

                    }else{
                        showErro('Documento fiscal não encontrado ou todos os itens já importados.');    
                    }
                }

            });
        }
        
        
        /**
         * Retorna a view da factory
         */
        function importarDocumentoFiscalSaida() {
                        
            $ajax.post('/_16010/api/nfs',{NFS: obj.NFS}).then(function(response){
                
                sanitizeJson(response);

                gScope.Ctrl.NFSS = response;
                
                if ( gScope.Ctrl.NFSS.length == 1 ) {
                    gScope.Ctrl.NFS_SELECTED = gScope.Ctrl.NFSS[0];
                    gScope.Ctrl.ImobilizadoItem.NFS = ''; 
                    gScope.Ctrl.NFSS = [];
                }

            });
        }
        

        
        /**
         * Retorna a view da factory
         */
        function incluir() {
            obj.PRODUTO_READONLY = false;
            gScope.Ctrl.IIConsultaProduto.option.required = true;
            obj.SELECTED = {
                DATA_ENTRADA : moment().toDate(),
                FRETE_UNITARIO : 0
            };
            
//            $ajax.get('/_16010/api/imobilizado/tipo').then(function(response){
//                obj.TIPOS = response;
                obj.Modal.show();
//
//                obj.SELECTED = {
//                    TIPO: null,
//                    DESCRICAO : '',
//                    OBSERVACAO : '',
//                    ITENS : []
//                };
//            });
        }
        
        /**
         * Retorna a view da factory
         */
        function alterar() {
            obj.PRODUTO_READONLY = true;
            
            gScope.Ctrl.IIConsultaProduto.option.required = false;
            
            obj.SELECTED = obj.SELECTEDS[0];

            angular.copy(obj.SELECTED,obj.SELECTED_COPY);
            obj.SELECTED.DATA_ENTRADA = moment(obj.SELECTED.DATA_ENTRADA).toDate();
            
            
//            $ajax.get('/_16010/api/imobilizado/tipo').then(function(response){
//                obj.TIPOS = response;
                obj.Modal.show();
//
//                obj.SELECTED = {
//                    TIPO: null,
//                    DESCRICAO : '',
//                    OBSERVACAO : '',
//                    ITENS : []
//                };
//            });
        }
        
        function confirmar() {

            if ( !obj.PRODUTO_READONLY ) {
                obj.SELECTED.PRODUTO_ID        = gScope.Ctrl.IIConsultaProduto.PRODUTO_ID;
                obj.SELECTED.PRODUTO_DESCRICAO = gScope.Ctrl.IIConsultaProduto.PRODUTO_DESCRICAO;
                
                gScope.Ctrl.Imobilizado.SELECTED.ITENS.push(obj.SELECTED);
                
                gScope.Ctrl.IIConsultaProduto.apagar();
            }
            obj.Modal.hide();
        }
        
        function cancelar() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        angular.extend(obj.SELECTED,obj.SELECTED_COPY);
                        gScope.Ctrl.IIConsultaProduto.apagar();
                        obj.Modal.hide();
                    });
                }}]     
            );
        }
        

        function remover(item,$event ){

            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente excluir os itens selecionados?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        

                        var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
                        var selecteds = obj.SELECTEDS;
                        
                        for ( var i  in selecteds ) {
                            var selected = selecteds[i];
                            
                            if ( selected.ID != undefined ) {
                                selected.EXCLUIDO = 1;
                            } else {

                                var idx = itens.indexOf(selected);

                                itens.splice(idx,1);
                            }
                        }
                        
                        obj.SELECTEDS = [];
                        
                        showSuccess('Itens removidos.');
                    });
                }}]     
            );
        };            
        


        function keypress(item,$event) {

            if ( $event.key == ' ' ) {

                $event.preventDefault();

                obj.pickToggle(item);
            }
//            else        
//            if ( $event.key == 'Enter' ) {
//
//            }
        };            
        
        
        /**
         * Retorna a view da factory
         */
        function pick(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function unpick(item) {
            
            if ( obj.picked(item) ) {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function pickToggle(item) {
            
            if ( !obj.picked(item) ) {
                obj.SELECTEDS.push(item);
            } else {
                obj.SELECTEDS.splice(obj.SELECTEDS.indexOf(item),1);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function pickReverse() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
            for ( var i in itens ) {
                obj.pickToggle(itens[i]);
            }
        }
        
        function picked(item) {
            
            var ret = true;
            
            if ( obj.SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }
        
        /**
         * Retorna a view da factory
         */
        function pickAll() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
            for ( var i in itens ) {
                obj.pick(itens[i]);
            }
        }
        
        /**
         * Retorna a view da factory
         */
        function unpickAll() {
            var itens = gScope.Ctrl.Imobilizado.SELECTED.ITENS;
            
            for ( var i in itens ) {
                obj.unpick(itens[i]);
            }
        }
        
        function picked(item) {
            
            var ret = true;
            
            if ( obj.SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }
        
        
        function dblClick(item) {
            obj.unpickAll();
            obj.pick(item);
            obj.alterar();
        }
        

        function encerrar() {
            
            var dados = {
                NFS_ID : gScope.Ctrl.NFS_SELECTED.NFS_ID,
                TIPO : obj.ENCERRAR_TIPO,
                IMOBILIZADO_ID : gScope.Ctrl.Imobilizado.SELECTED.ID,
                ITENS : obj.ENCERRAR_SELECTEDS
            };

            $ajax.post('/_16010/api/imobilizado/item/encerrar',dados).then(function(response){
                gScope.Ctrl.ImobilizadoItem.consultar();
                
                $('#modal-imobilizado-encerrar').modal('hide');
            });
        }        
        
        function encerrarPickToggle(item) {
            
            if ( obj.ENCERRAR_SELECTEDS == undefined ) {
                obj.ENCERRAR_SELECTEDS = [];
            }
            
            if ( !obj.encerrarPicked(item) ) {
                obj.ENCERRAR_SELECTEDS.push(item);
            } else {
                obj.ENCERRAR_SELECTEDS.splice(obj.ENCERRAR_SELECTEDS.indexOf(item),1);
            }
        }        
        

        function encerrarPicked(item) {
            
            var ret = true;
            
            if ( obj.ENCERRAR_SELECTEDS == undefined || obj.ENCERRAR_SELECTEDS.indexOf(item) == -1 ) {
                ret = false;
            }
            
            return ret;
        }        
        
                
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-imobilizado-item');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().focus();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return ImobilizadoItem;
	};
   
})(window, window.angular);
angular
    .module('app')
    .factory('ImobilizadoCcusto', ImobilizadoCcusto);
    

	ImobilizadoCcusto.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gcObject',
        'gScope'
    ];

function ImobilizadoCcusto($ajax, $q, $rootScope, $timeout, gcCollection, gcObject, gScope) {

    /**
     * Constructor, with class name
     */
    function ImobilizadoCcusto(data) {
        if (data) {
            this.setData(data);
        }

		gScope.ImobilizadoCcusto = this; 
        
        
        this.CONSULTA_FILTRO = '';
        this.DADOS = [];
        this.FILTERED = [];
        this.SELECTED = {};
        this.TALOES_LIBERAR = [];
        this.TALOES_LIBERAR_LISTA = [];
        this.BARRAS = '';
    }
    
    var data = {};
    
    
    ImobilizadoCcusto.prototype.consultar = function(data) {

        var that = this;
        
        return $q(function(resolve,reject){
        
            $ajax.post('/_22200/api/talao',data).then(function(response){

                resolve(response);
            },function(erro){
                reject(erro);
            });
        });
    };    
    
    ImobilizadoCcusto.prototype.consultarBarras = function() {
        
        var that = this;
                
        return $q(function(resolve,reject){
            that.consultar({
                REMESSA_ID       : that.BARRAS.substring(0,6),
                REMESSA_TALAO_ID : that.BARRAS.substring(6,10)
            }).then(function(response){
                
                if ( response.length == 1 ) {
                    
                    gcCollection.merge(that.TALOES_LIBERAR, response, 'TALAO_ID',true);                     
                    if ( response[0].STATUS == 2 ) {
                        gcCollection.merge(that.TALOES_LIBERAR_LISTA, response, 'TALAO_ID',true);                     
                    }
                    
                } else {
                    showErro('Talão inválido.');
                }
                
                that.BARRAS = '';
                resolve(response);
            },function(erro){
                that.BARRAS = '';
                reject(erro);
            });
        });
    };
    
    ImobilizadoCcusto.prototype.liberar = function() {
        
        var that = this;
        
        gScope.Operador.open(function(){
            gScope.Gp.open(function(){

                var data = {};

                data.OPERADOR_ID    = gScope.Operador.SELECTED.OPERADOR_ID;
                data.GP_ID          = gScope.Gp.SELECTED.GP_ID;
                data.TALOES_LIBERAR = that.TALOES_LIBERAR_LISTA;

                $ajax.post('/_22200/api/talao/liberar',data).then(function(){
                    that.TALOES_LIBERAR = [];
                    that.TALOES_LIBERAR_LISTA = [];
                });
                
                gScope.Operador.logoff();
                gScope.Gp.logoff();
            });  
        });
        
    };
    
    ImobilizadoCcusto.prototype.liberarLimpar = function() {
        this.TALOES_LIBERAR = [];
        this.TALOES_LIBERAR_LISTA = [];
    };
   
    
    ImobilizadoCcusto.prototype.mergeComposicao = function(response) {
         
  
        sanitizeJson(response.TALAO);
        
        var taloes = [];

        if ( response.CONSUMOS != undefined ) {
            sanitizeJson(response.DETALHES);
            sanitizeJson(response.CONSUMOS);
            sanitizeJson(response.HISTORICOS);   
            sanitizeJson(response.ALOCADOS);
            sanitizeJson(response.COMPONENTES);

            gcCollection.bind(response.CONSUMOS, response.ALOCADOS, 'CONSUMO_ID', 'ALOCACOES');   
            gcObject.bind(response.TALAO, response.DETALHES, ['REMESSA_ID','REMESSA_TALAO_ID'], 'DETALHES');
            gcObject.bind(response.TALAO, response.CONSUMOS, 'TALAO_ID', 'CONSUMOS');
            gcObject.bind(response.TALAO, response.COMPONENTES, 'TALAO_ID', 'COMPONENTES');
            gcObject.bind(response.TALAO, response.HISTORICOS, 'PROGRAMACAO_ID', 'HISTORICOS');     

            for ( var i in taloes ) {

                var talao = taloes[i];

                talao.CONSUMO_STATUS = '1';
                talao.ESTOQUE_STATUS = '1';


                for ( var y in talao.CONSUMOS ) {

                    var consumo = talao.CONSUMOS[y];


                    if ( talao.ESTOQUE_STATUS == '1' && consumo.ESTOQUE_STATUS == 0 ) {
                        talao.ESTOQUE_STATUS = '0';
                    }  

                    talao.ULTIMO_TALAO = true;
                    var i = 0;
                    for ( var y in talao.DETALHES ) {


                        var detalhe = talao.DETALHES[y];

                        if ( detalhe.TALAO_DETALHE_STATUS < 2 ) {
                            i++;    
                        }

                        if ( i > 1 ) {
                            talao.ULTIMO_TALAO = false;
                            break;
                        }
                    }                
                }
            }        
        }
        
        
        if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIDO' ) {
            taloes = gScope.ImobilizadoCcustoProduzido.DADOS;
        } else
        if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIR' ) {
            taloes = gScope.ImobilizadoCcustoProduzir.DADOS;
        }         
        
        gcCollection.merge(taloes, [response.TALAO], 'TALAO_ID',true);  
                
        
    };    


    ImobilizadoCcusto.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {

            if ( item.TALAO_ID != this.SELECTED.TALAO_ID ) {
                gScope.ImobilizadoCcustoDetalhe.SELECTED = {};
                gScope.ImobilizadoCcustoDetalhe.SELECTEDS = [];
                gScope.ImobilizadoCcustoDetalhe.SELECTEDS_PRODUZIR = [];
            }
            
            this.SELECTED = item;
            
            gScope.Filtro.TALAO_ID = item.TALAO_ID;
            gScope.Filtro.uriHistory();                        

            if ( gScope.Filtro.TAB_ACTIVE == 'PRODUZIDO' ) {
                that.consultarComposicao();
            }

            if ( action == 'modal-open' ) {

                
                that.show(null,function(){

                    $('[data-talao-id="' + gScope.Filtro.TALAO_ID + '"]:focusable').focus();

                    delete gScope.Filtro.TALAO_ID;
                    gScope.Filtro.uriHistory();      
                });               
                
            }
        }

    };    


 
    
    ImobilizadoCcusto.prototype.confirm = function () {
        var that = this;

        var dados = {
            FILTRO: gScope.ConsumoBaixarFiltro,
            DADOS: {
                ITENS : that.ITENS_BAIXAR,
                PESO : that.PESO
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

    ImobilizadoCcusto.prototype.irPara = function (direcao) {
        
        var that = this;
        var taloes = [];
        
        switch (gScope.Filtro.TAB_ACTIVE) {
            case 'PRODUZIR':
                taloes = gScope.ImobilizadoCcustoProduzir.FILTERED;
                break;
            case 'PRODUZIDO':
                taloes = gScope.ImobilizadoCcustoProduzido.FILTERED;
                break;
                
        }
        
        switch (direcao) {
            case '|<':
                that.pick(taloes[0]);
                break;
                
            case '<':
                
                var idx = taloes.indexOf(that.SELECTED);
                if ( taloes[idx-1] != undefined ) {
                    that.pick(taloes[idx-1]);
                }
                break;
                
            case '>':

                var idx = taloes.indexOf(that.SELECTED);
                if ( taloes[idx+1] != undefined ) {
                    that.pick(taloes[idx+1]);
                }
                break;
                
            case '>|':
                that.pick(taloes[taloes.length-1]);
                break;
        }
    };  

    var modal = $('#modal-talao');
    
    ImobilizadoCcusto.prototype.show = function(shown,hidden) {

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

    ImobilizadoCcusto.prototype.close = function(hidden) {

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
    
    ImobilizadoCcusto.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return ImobilizadoCcusto;
};
(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('DemonstrativoDepreciacaoAnual', DemonstrativoDepreciacaoAnual);

	DemonstrativoDepreciacaoAnual.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta',
        'gScope',
        'gcCollection',
        'gcObject'
    ];

	function DemonstrativoDepreciacaoAnual($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function DemonstrativoDepreciacaoAnual() {
            
            obj = this; 

            // Public methods         
            this.consultar     = consultar;
            this.merge         = merge;
          
            this.Modal         = Modal; 
            
                   
            this.DADOS = [];
            this.MESES = [];
            
            this.GERAL_MESES = [];
            this.GERAL = [];
            
            this.TIPOS_CCUSTOS = [];
            
            
            this.TIPOS_MESES = [];
            this.TIPOS = [];
            this.TIPOS_CCUSTOS = [];
            this.TIPOS_CCUSTOS_MESES = [];
            
            this.CCUSTOS_MESES = [];
            this.CCUSTOS_TIPOS_MESES = [];
            this.CCUSTOS_TIPOS = [];
            this.CCUSTOS = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            
	    }
        
        
        

        function consultar() {
            
            return $q(function(resolve, reject){
                $ajax.post('/_16010/api/demonstratitvo-depreciacao',{
                    MES_1: obj.MES_1,
                    MES_2: obj.MES_2,
                    ANO_1: obj.ANO_1,
                    ANO_2: obj.ANO_2
                }).then(function(response){
                    
                    obj.merge(response);
                    
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }                
        
        /**
         * Retorna a view da factory
         */
        function merge(response) {
            
            sanitizeJson(response);
            
            
            gcCollection.merge(obj.DADOS, response, ['CCUSTO','IMOBILIZADO_ID','TIPO_ID','MES','ANO']);
            
            obj.TOTAL_GERAL = 0;
            
            var meses = gcCollection.groupBy(obj.DADOS,['MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
                obj.TOTAL_GERAL += item.VALOR;
            });

            gcCollection.merge(obj.MESES, meses, ['MES','ANO']);
            
            
            
            
            // VISÃO POR CENTRO DE CUSTO/TIPO
            
            var ccustos_tipos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.CCUSTOS_TIPOS_MESES, ccustos_tipos_meses, ['CCUSTO','TIPO_ID','MES','ANO']);
            
            //
            
            var ccustos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','MES_DESCRICAO','MES','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.CCUSTOS_MESES, ccustos_meses, ['CCUSTO','MES','ANO']);
            
            //
            
            
            var ccustos_tipos = gcCollection.groupBy(obj.CCUSTOS_TIPOS_MESES,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.CCUSTOS_TIPOS, ccustos_tipos, ['CCUSTO','TIPO_ID']);
            
            //
            
            var ccustos = gcCollection.groupBy(obj.CCUSTOS_TIPOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO'],'TIPOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.CCUSTOS, ccustos, 'CCUSTO');
            
            gcCollection.bind(obj.CCUSTOS, obj.CCUSTOS_MESES, 'CCUSTO','MESES');
            
            
            
            
            
            
            
            // VISÃO POR TIPO/CCUSTO
            
            var tipos_ccustos_meses = gcCollection.groupBy(obj.DADOS,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.TIPOS_CCUSTOS_MESES, tipos_ccustos_meses, ['CCUSTO','TIPO_ID','MES','ANO']);
            
            //
            
            var tipos_meses = gcCollection.groupBy(obj.DADOS,['TIPO_ID','TIPO_DESCRICAO','MES','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.TIPOS_MESES, tipos_meses, ['TIPO_ID','MES','ANO']);
            
            //
            
            
            var tipos_ccustos = gcCollection.groupBy(obj.CCUSTOS_TIPOS_MESES,['CCUSTO','CCUSTO_MASK','CCUSTO_DESCRICAO', 'TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.TIPOS_CCUSTOS, tipos_ccustos, ['CCUSTO','TIPO_ID']);
            
            //
            
            var tipos = gcCollection.groupBy(obj.TIPOS_CCUSTOS,['TIPO_ID','TIPO_DESCRICAO'],'CCUSTOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.TIPOS, tipos, ['TIPO_ID']);
            
            gcCollection.bind(obj.TIPOS, obj.TIPOS_MESES, 'TIPO_ID','MESES');
            
            
            
            
            
            
            
            // VISAÕ GERAL
            
            
            var tipos_meses = gcCollection.groupBy(obj.DADOS,[ 'TIPO_ID','TIPO_DESCRICAO','MES','MES_DESCRICAO','ANO'],'IMOBILIZADOS',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
                
            });

            gcCollection.merge(obj.GERAL_MESES, tipos_meses, ['TIPO_ID','MES','ANO']);
            
            //
            
            
            var tipos = gcCollection.groupBy(obj.GERAL_MESES,['TIPO_ID','TIPO_DESCRICAO'],'MESES',function(group,item){
                
                if ( group.VALOR == undefined ) {
                    group.VALOR = 0;
                }
                
                group.VALOR += item.VALOR;
            });

            gcCollection.merge(obj.GERAL, tipos, ['TIPO_ID']);
            
            //
            
            
        }
        

                
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-demonstratitvo-depreciacao-anual');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().focus();

                        if ( shown ) {
                            shown(); 
                        }
                    })
                ;    

                    this._modal()
                        .one('hidden.bs.modal', function(){
                            
                            if ( hidden ) {
                                hidden();      
                            }
                        })
                    ;        
            },
            hide : function(hidden) {

                this._modal()
                    .modal('hide')
                ;

                if ( hidden ) {
                    this._modal()
                        .one('hidden.bs.modal', function(){
                            hidden ? hidden() : '';
                        })
                    ;                      
                }
            }
        };     

            
	    /**
	     * Return the constructor function
	     */
	    return DemonstrativoDepreciacaoAnual;
	};
   
})(window, window.angular);
angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        '$consulta',
        'Historico',
        'Filtro',
        'Imobilizado',
        'ImobilizadoItem',
        'ImobilizadoCcusto',
        'DemonstrativoDepreciacaoAnual'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        gScope, 
        $consulta,
        Historico,
        Filtro,
        Imobilizado,
        ImobilizadoItem,
        ImobilizadoCcusto,
        DemonstrativoDepreciacaoAnual
    ) {

		var vm     = this;
        
        gScope.Ctrl = this;
        
        
        vm.Consulta   = new $consulta();
        
        vm.ConsultaImobilizadoTipo                             = vm.Consulta.getNew(true);
        vm.ConsultaImobilizadoTipo.componente                  = '.consulta-imobilizado-tipo';
        vm.ConsultaImobilizadoTipo.model                       = 'vm.ConsultaImobilizadoTipo';
        vm.ConsultaImobilizadoTipo.option.label_descricao      = 'Tipo:';
        vm.ConsultaImobilizadoTipo.option.obj_consulta         = '/_16010/api/imobilizado/tipo';
        vm.ConsultaImobilizadoTipo.option.tamanho_input        = 'input-maior';
        vm.ConsultaImobilizadoTipo.option.tamanho_tabela       = 450;
        vm.ConsultaImobilizadoTipo.option.campos_tabela        = [['DESCRICAO', 'Descrição'],['TAXA_DEPRECIACAO_TEXTO','Taxa Deprecição'],['VIDA_UTIL_TEXTO','Vida Útil']];
        vm.ConsultaImobilizadoTipo.option.obj_ret              = ['DESCRICAO'];
        vm.ConsultaImobilizadoTipo.compile();

        vm.ConsultaImobilizadoTipo.onSelect = function(){
            vm.Imobilizado.SELECTED.TAXA  = Number(vm.ConsultaImobilizadoTipo.item.dados.TAXA_DEPRECIACAO);
            vm.Imobilizado.TIPO_TAXA      = Number(vm.ConsultaImobilizadoTipo.item.dados.TAXA_DEPRECIACAO);
            vm.Imobilizado.TIPO_VIDA_UTIL = Number(vm.ConsultaImobilizadoTipo.item.dados.VIDA_UTIL); 
        };
        

        vm.IConsultaCcusto                        = vm.Consulta.getNew(true);
        vm.IConsultaCcusto.componente             = '.i-consulta-ccusto';
        vm.IConsultaCcusto.model                  = 'vm.IConsultaCcusto';
        vm.IConsultaCcusto.option.label_descricao = 'Centro de Custo:';
        vm.IConsultaCcusto.option.obj_consulta    = '/_20030/api/ccusto';
        vm.IConsultaCcusto.option.tamanho_input   = 'input-maior';
        vm.IConsultaCcusto.option.campos_tabela   = [['MASK', 'C. Custo'],['DESCRICAO','Descrição']];
        vm.IConsultaCcusto.option.obj_ret         = ['MASK', 'DESCRICAO'];
        vm.IConsultaCcusto.compile();

        vm.IIConsultaProduto                             = vm.Consulta.getNew(true);
        vm.IIConsultaProduto.componente                  = '.ii-consulta-produto';
        vm.IIConsultaProduto.model                       = 'vm.IIConsultaProduto';
        vm.IIConsultaProduto.option.label_descricao      = 'Produto:';
        vm.IIConsultaProduto.option.obj_consulta         = '/_27050/api/produto';
        vm.IIConsultaProduto.option.tamanho_input        = 'input-maior';
        vm.IIConsultaProduto.option.tamanho_tabela       = 427;
        vm.IIConsultaProduto.option.campos_tabela        = [['PRODUTO_ID', 'ID'],['PRODUTO_DESCRICAO','PRODUTO']];
        vm.IIConsultaProduto.option.obj_ret              = ['PRODUTO_ID', 'PRODUTO_DESCRICAO'];
        vm.IIConsultaProduto.setDataRequest({STATUS: 1});
        vm.IIConsultaProduto.compile();
        
		vm.Filtro                        = new Filtro();
        vm.ImobilizadoItem               = new ImobilizadoItem();
		vm.Imobilizado                   = new Imobilizado();
		vm.ImobilizadoCcusto             = new ImobilizadoCcusto();        
		vm.DemonstrativoDepreciacaoAnual = new DemonstrativoDepreciacaoAnual();        
        vm.Historico                     = new Historico();

        vm.Imobilizado.consultarTodos();
        
        
            vm.IConsultaCcusto.disable(true);
            vm.ConsultaImobilizadoTipo.disable(true);
        
        $scope.$watch('vm.Imobilizado.ALTERANDO', function (newValue, oldValue, scope) {
            if ( newValue == false ) {
                vm.IConsultaCcusto.disable(true);
                vm.ConsultaImobilizadoTipo.disable(true);
            } else
            if ( newValue == true ) {
                vm.IConsultaCcusto.disable(false);
                vm.ConsultaImobilizadoTipo.disable(false);
            } 
        }, true);


        $scope.$watch('vm.Imobilizado.SELECTED.ITENS', function (newValue, oldValue, scope) {
            if(newValue != oldValue){
                
                sanitizeJson(newValue);
                    
                vm.ImobilizadoItem.TOTAL_PARCELA    = 0;
                vm.ImobilizadoItem.TOTAL_QUANTIDADE = 0;
                vm.ImobilizadoItem.TOTAL_VALOR      = 0;
                vm.ImobilizadoItem.TOTAL_VALOR_UNITARIO_SEM_DESC      = 0;
                vm.ImobilizadoItem.TOTAL_VALOR_DESCONTO      = 0;
                vm.ImobilizadoItem.TOTAL_VALOR_ACRESCIMO      = 0;
                vm.ImobilizadoItem.TOTAL_SUB      = 0;
                vm.ImobilizadoItem.TOTAL_FRETE      = 0;
                vm.ImobilizadoItem.TOTAL_ICMS       = 0;
                vm.ImobilizadoItem.TOTAL_GERAL      = 0;
                vm.ImobilizadoItem.TOTAL_SALDO      = 0;
                
                
                for ( var i in vm.Imobilizado.SELECTED.ITENS ) {
                    var item = vm.Imobilizado.SELECTED.ITENS[i];
                    
                    if ( item.EXCLUIDO == 1 ) continue;
                    
                    item.VALOR_TOTAL = item.QUANTIDADE * (item.VALOR_UNITARIO + item.FRETE_UNITARIO);

                    vm.ImobilizadoItem.TOTAL_PARCELA    += item.VALOR_PARCELA;
                    vm.ImobilizadoItem.TOTAL_QUANTIDADE += item.QUANTIDADE;
                    vm.ImobilizadoItem.TOTAL_VALOR      += item.VALOR_UNITARIO;
                    vm.ImobilizadoItem.TOTAL_VALOR_UNITARIO_SEM_DESC      += item.VALOR_UNITARIO_SEM_DESC;
                    vm.ImobilizadoItem.TOTAL_VALOR_DESCONTO      += item.VALOR_DESCONTO;
                    vm.ImobilizadoItem.TOTAL_VALOR_ACRESCIMO      += item.VALOR_ACRESCIMO;
                    vm.ImobilizadoItem.TOTAL_SUB        += (item.VALOR_UNITARIO_SEM_DESC * item.QUANTIDADE);
                    vm.ImobilizadoItem.TOTAL_FRETE      += item.FRETE_UNITARIO;
                    vm.ImobilizadoItem.TOTAL_ICMS       += item.ICMS_UNITARIO;
                    vm.ImobilizadoItem.TOTAL_GERAL      += item.VALOR_TOTAL;
                    vm.ImobilizadoItem.TOTAL_SALDO      += item.SALDO;
                }
            }
        }, true);

        
        vm.checkVisible = function(el_master,field_array_filtered) {
            
            var ret = false;
            var array = el_master[field_array_filtered];

            var ret = true;

            if ( array != undefined ) {
                ret = false;

                for ( var i in array ) {
                    var item = array[i];

                    if ( item.VISIBLE == true  ) {
                        ret = true;
                        break;
                    }
                }
            }

            el_master.VISIBLE = ret;

            return ret;
        };

        vm.exportTableToCsv = function(tabela,nome){
            exportTableToCsv(nome, tabela);
        };

        vm.exportTableToXls = function(tabela,nome){
            exportTableToXls(nome, tabela);
        };

        vm.exportTableToPrint = function(div,descricao){
            var user = $('#usuario-descricao').val();
            var filtro = 'Período : ' + vm.DemonstrativoDepreciacaoAnual.MES_1+'/'+vm.DemonstrativoDepreciacaoAnual.ANO_1 + ' a ' + vm.DemonstrativoDepreciacaoAnual.MES_2+'/'+vm.DemonstrativoDepreciacaoAnual.ANO_2  + ' - Visão: ' + vm.DemonstrativoDepreciacaoAnual.VISAO;
            printHtml(div, descricao, filtro, user, '1.0.0',1,'');
        };

	}   
  
//# sourceMappingURL=_16010.js.map
