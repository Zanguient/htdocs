'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'infinite-scroll'
	])
;
     
angular
    .module('app')
    .factory('Empresa', Empresa);
    

	Empresa.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Empresa($ajax, $q, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Empresa(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Empresa = this; 
        
        this.DADOS = [];
        this.SELECTED = {};
        this.ITENS = [];
        this.ALTERANDO = false;
        this.COTA_BACKUP = {};
        
        this.events();
    }
    
    Empresa.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve, reject){
            $ajax.post('/_12090/api/empresa',that.SELECTED).then(function(response){

                angular.extend(that.SELECTED, response);
                
                resolve(that.SELECTED);
            },function(erro){
                reject(erro);
            });
        });
    };
        
    Empresa.prototype.open = function(empresa) {
          
        var that = this;
        
        this.consultar().then(function(){
            
            window.history.replaceState('', '', encodeURI(urlhost + '/_12090?EMPRESA_ID='+that.SELECTED.EMPRESA_ID));
            
            that.modalOpen(null,function(){
                
                window.history.replaceState('', '', encodeURI(urlhost + '/_12090'));
                
            });
        },function(erro){
            showErro('Ocorreu uma falha ao consultar as informações. ' + erro);
        });
    };    
    
    
    Empresa.prototype.consultarModelosPreco = function() {
        var that = this;
    
        $ajax.post('/_12090/api/modelos/preco',that.SELECTED).then(function(response){

            if ( that.SELECTED.MODELOS_PRECO == undefined ) {
                that.SELECTED.MODELOS_PRECO = [];
            }
            gcCollection.merge(that.SELECTED.MODELOS_PRECO,response,['TIPO','ID','TAMANHO']);
            
        });        
    };    
    
    Empresa.prototype.empresaRepresentate = function() {
        
        var that = this;
        
        var ret = true;
        
        if ( gScope.REPRESENTANTE_ID > 0 ) {
            if ( parseInt(that.SELECTED.REPRESENTANTE_ID) != parseInt(gScope.REPRESENTANTE_ID) ) {
                ret = false;
            }
        }
        
        return ret;
    };    
    
    Empresa.prototype.cancelar = function() {
        
        var that = this;
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente cancelar esta operação?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){        
        
                    that.ALTERANDO = false;
                    angular.extend(that.SELECTED, that.COTA_BACKUP);

                });
            }}]     
        );      
    };

    Empresa.prototype.alterar = function() {
        this.ALTERANDO = true;
        angular.copy(this.SELECTED, this.COTA_BACKUP);
    };
    
    Empresa.prototype.excluir = function() {
        
        var that = this;
        
        addConfirme('<h4>Confirmação</h4>',
            'Deseja realmente excluir esta empresa?',
            [obtn_sim,obtn_nao],
            [{ret:1,func:function(){
                $rootScope.$apply(function(){

                    var dados = {
                        DADOS : {
                            ITENS : [that.SELECTED]
                        },
                        FILTRO : gScope.Filtro,
                        FILTRO_COTA : that.SELECTED
                    };

                    $ajax.post('/_13030/api/empresa/delete',dados).then(function(response){

                        gScope.Filtro.merge(response.DATA_RETURN.DADOS);
                        angular.extend(that.SELECTED, response.DATA_RETURN.COTA);

                        that.ModalClose();
                    });

                });
            }}]     
        );        
        
    };
    
    Empresa.prototype.dblPick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;
            
            gScope.Filtro.COTA_ID = item.ID;
            gScope.Filtro.COTA_OPEN = 1;
            gScope.Filtro.uriHistory();
            
            that.consultar();
            that.ModalShow(null,function(){   
                that.ALTERANDO = false;
                delete gScope.Filtro.COTA_OPEN;
                gScope.Filtro.uriHistory();
            });

        }

    };    

    
    Empresa.prototype.pick = function(empresa,setfocus) {
        
        var that = this;

        if ( empresa != undefined ) {
        
            this.SELECTED = empresa;

            gScope.Filtro.COTA_ID = empresa.ID;
            gScope.Filtro.uriHistory();
            
            if ( setfocus ) {
                that.setFocus();
            }
        }

    };  
    
    var modal = $('#modal-empresa');
    
    Empresa.prototype.modalOpen = function(shown,hidden) {

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

    Empresa.prototype.modalClose = function(hidden) {

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
    
    Empresa.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Empresa.prototype.events = function($event) {
        var that = this;
        var cancel_bf_unload = false;
        //
        $(document).on('click','[type="submit"]',function(e) {
            var form = $(this).closest('form');
            var action = $(form).attr('action') == undefined ? '' : $(form).attr('action');

            if ( action != '' ) {
                cancel_bf_unload = true;
            }
        });

        var bf_load_timeout;

        function warning() {
            if ( that.ALTERANDO && cancel_bf_unload == false ) {
                return 'oi';
            }
        }

        function noTimeout() {
            clearTimeout(bf_load_timeout);
        }

        window.onbeforeunload = warning;
        window.unload = noTimeout;         
         
    };
        
    
    /**
     * Return the constructor function
     */
    return Empresa;
};
angular
    .module('app')
    .factory('Empresas', Empresas);
    

	Empresas.$inject = [
        '$ajax',
        '$filter',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Empresas($ajax, $filter, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Empresas(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Empresas = this; 
          
        this.AJAX_LOCKED = true;
        this.DADOS = [];
        this.SELECTED = {};
        
        this.CONF_PAGE = {
            FIRST : 50,
            SKIP: 0
        };
        
        this.FILTRO = {
            HABILITA_CLIENTE : '1',
            STATUS : '1'
        };
        
        this.CONSULTAS = [];
        
        this.events();
    }
    
    Empresas.prototype.consultar = function(def_page) {
        
        var that = this;
        
        var options = {};
        
        if ( def_page ) {
            angular.extend(that.FILTRO,that.CONF_PAGE);
        } else {
            options.progress = false;            
        }
        
        that.AJAX_LOCKED = true;
        var consulta = $ajax;
                
        that.CONSULTAS.push(consulta);
        
        return $q(function(resolve,reject){
            consulta.post('/_12090/api/empresas',that.FILTRO,options).then(function(response){

                that.merge(response,def_page);

                if ( def_page ) {
                    $('.table-ec').scrollTop(0);                
                }

                if ( response.length >= that.CONF_PAGE.FIRST ) {
                    that.AJAX_LOCKED = false;
                }
                resolve(response);
            },function(e){
                reject(e);
            });            
        }); 
        
        
    };
        
    Empresas.prototype.getMoreData = function(empresa,setfocus) {
        
        this.FILTRO.SKIP   = this.FILTRO.SKIP || 0;
        this.FILTRO.SKIP  += this.CONF_PAGE.FIRST;
        this.FILTRO.FIRST  = this.CONF_PAGE.FIRST;
        
        this.consultar();
    };   
    
    Empresas.prototype.merge = function(response,def_page) {
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

        response = $filter('orderBy')(response,'EMPRESA_RAZAO_SOCIAL');
        
        var preserve_main = def_page == true ? false : true;
        gcCollection.merge(this.DADOS, response, 'EMPRESA_ID',preserve_main);     
        
        
    };
    
    
    
    Empresas.prototype.emptyData = function(newvalue,oldvalue) {
        
        this.AJAX_LOCKED = true;

        for ( var i in this.CONSULTAS ) {
            var consulta = this.CONSULTAS[i];

            consulta.abort();
        }

        this.CONSULTAS = [];

        this.DADOS = [];
    };     
    
    
    
    Empresas.prototype.virifyChange = function(newvalue,oldvalue) {
        
        if ( newvalue.toUpperCase() != oldvalue.toUpperCase() ) {
            
            this.emptyData();
        }
    };     

    
    Empresas.prototype.pick = function(empresa,setfocus) {
        
        
        if ( this.SELECTED != empresa && empresa != undefined ) {
            
            this.SELECTED = empresa;
            gScope.Empresa.SELECTED = this.SELECTED;

            if ( setfocus ) {
                this.setFocus();
            }
        }
    };  

         
    
    Empresas.prototype.dblPick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;
            
            gScope.Filtro.COTA_ID = item.ID;
            gScope.Filtro.COTA_OPEN = 1;
            gScope.Filtro.uriHistory();
            
            that.consultar();
            that.ModalShow(null,function(){   
                that.ALTERANDO = false;
                delete gScope.Filtro.COTA_OPEN;
                gScope.Filtro.uriHistory();
            });

        }

    };    

    
    var modal = $('#modal-empresa');
    
    Empresas.prototype.ModalShow = function(shown,hidden) {

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

    Empresas.prototype.ModalClose = function(hidden) {

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
    
    Empresas.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    
    Empresas.prototype.events = function($event) {
//        var that = this;
//        var cancel_bf_unload = false;
//        //
//        $(document).on('click','[type="submit"]',function(e) {
//            var form = $(this).closest('form');
//            var action = $(form).attr('action') == undefined ? '' : $(form).attr('action');
//
//            if ( action != '' ) {
//                cancel_bf_unload = true;
//            }
//        });
//
//        var bf_load_timeout;
//
//        function warning() {
//            if ( that.ALTERANDO && cancel_bf_unload == false ) {
//                return 'oi';
//            }
//        }
//
//        function noTimeout() {
//            clearTimeout(bf_load_timeout);
//        }
//
//        window.onbeforeunload = warning;
//        window.unload = noTimeout;         
         
    };
        
    
    /**
     * Return the constructor function
     */
    return Empresas;
};
angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        '$q',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, $q, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
        
//        this.DATA_1 = new Date(Clock.DATETIME_SERVER);
//        this.DATA_2 = new Date(Clock.DATETIME_SERVER);

		gScope.Filtro = this; 
        
    }
    
    Filtro.prototype.consultar = function(progress) {
        
        var that = this;
        
        return $q(function(resolve){
    //        loading('.main-ctrl');     

//            this.DATAHORA = {
//                DATAHORA_1 : moment(this.DATA_1).format('YYYY.MM.DD 00:00:00'),
//                DATAHORA_2 : moment(this.DATA_2).format('YYYY.MM.DD 23:59:59')
//            };

            $ajax.post('/_13030/api/cotas',that,{progress: progress == undefined ? false : progress}).then(function(response){

                that.merge(response);
                resolve(response);
    //            loading('hide');

            });
        });
    };
   
    
    Filtro.prototype.merge = function(response) {
        
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

        gcCollection.merge(gScope.Cota.DADOS, response, 'ID');      
        
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var periodos = gcCollection.groupBy(gScope.Cota.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO',
            'MES',
            'ANO',
            'PERIODO_DESCRICAO'
        ], 'CCONTABEIS',function(periodo,ccontabil){

            periodo.VALOR     == undefined ? periodo.VALOR     = 0 : '';
            periodo.EXTRA     == undefined ? periodo.EXTRA     = 0 : '';
            periodo.TOTAL     == undefined ? periodo.TOTAL     = 0 : '';
            periodo.OUTROS    == undefined ? periodo.OUTROS    = 0 : '';
            periodo.UTIL      == undefined ? periodo.UTIL      = 0 : '';
//            periodo.PERC_UTIL == undefined ? periodo.PERC_UTIL = 0 : '';
            periodo.SALDO     == undefined ? periodo.SALDO     = 0 : '';

            periodo.VALOR     += ccontabil.VALOR    ;
            periodo.EXTRA     += ccontabil.EXTRA    ;
            periodo.TOTAL     += ccontabil.TOTAL    ;
            periodo.OUTROS    += ccontabil.OUTROS   ;
            periodo.UTIL      += ccontabil.UTIL     ;
//            periodo.PERC_UTIL += ccontabil.PERC_UTIL;
            periodo.SALDO     += ccontabil.SALDO    ;        


            if ( periodo.TOTAL > 0 ) {
                periodo.PERC_UTIL = ((1-(periodo.SALDO/periodo.TOTAL))*100);
            } else {
                if ( periodo.TOTAL == 0 && periodo.SALDO < 0 ) {
                    periodo.PERC_UTIL = 100;
                } else {
                    periodo.PERC_UTIL = 0;  
                }
            }

//            if ( (periodo.VALOR + periodo.EXTRA) = 0 && periodo.SALDO )
//            IIF(A.VALOR+A.EXTRA = 0 AND A.SALDO < 0, 100, 0))

        });
        
        gcCollection.merge(gScope.CotaPeriodo.DADOS, periodos, ['CCUSTO','MES','ANO']);
        
        /////
        
        
        /**
         * Vinculo dos ConsumoBaixadoProdutos - Incio
         */
        var ccustos = gcCollection.groupBy(gScope.CotaPeriodo.DADOS, [
            'CCUSTO',
            'CCUSTO_MASK',
            'CCUSTO_DESCRICAO'
        ], 'PERIODOS',function(ccusto,periodo){

            ccusto.VALOR     == undefined ? ccusto.VALOR     = 0 : '';
            ccusto.EXTRA     == undefined ? ccusto.EXTRA     = 0 : '';
            ccusto.TOTAL     == undefined ? ccusto.TOTAL     = 0 : '';
            ccusto.OUTROS    == undefined ? ccusto.OUTROS    = 0 : '';
            ccusto.UTIL      == undefined ? ccusto.UTIL      = 0 : '';
//            ccusto.PERC_UTIL == undefined ? ccusto.PERC_UTIL = 0 : '';
            ccusto.SALDO     == undefined ? ccusto.SALDO     = 0 : '';     

            ccusto.VALOR     += periodo.VALOR    ;
            ccusto.EXTRA     += periodo.EXTRA    ;
            ccusto.TOTAL     += periodo.TOTAL    ;
            ccusto.OUTROS    += periodo.OUTROS   ;
            ccusto.UTIL      += periodo.UTIL     ;
//            ccusto.PERC_UTIL += periodo.PERC_UTIL;
            ccusto.SALDO     += periodo.SALDO    ;        

            if ( ccusto.TOTAL > 0 ) {
                ccusto.PERC_UTIL = ((1-(ccusto.SALDO/ccusto.TOTAL))*100);
            } else {
                if ( ccusto.TOTAL == 0 && ccusto.SALDO < 0 ) {
                    ccusto.PERC_UTIL = 100;
                } else {
                    ccusto.PERC_UTIL = 0;  
                }
            }
        });
        
        gcCollection.merge(gScope.CotaCcusto.DADOS, ccustos, ['CCUSTO','MES','ANO','CCONTABIL']);
        
        /////
                
        
//        console.log(gScope.CotaCcusto.DADOS);
    };
    
    Filtro.prototype.uriHistory = function() { 
        window.history.replaceState('', '', encodeURI(urlhost + '/_13030/ng?'+$httpParamSerializer(this)));        
    };    

    /**
     * Return the constructor function
     */
    return Filtro;
};
angular
    .module('app')
    .value('gScope', {
        REPRESENTANTE_ID : $('#usuario-representante-id').first().val()
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        '$consulta',
        'gScope',
        'Filtro',
        'Empresa',
        'Empresas',
        'Historico'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        $consulta,
        gScope, 
        Filtro, 
        Empresa,
        Empresas,
        Historico
    ) {

		var vm = this;

		vm.Filtro     = new Filtro();
		vm.Empresa    = new Empresa();
		vm.Empresas   = new Empresas();
		vm.Historico  = new Historico();
        vm.Consulta   = new $consulta();
        
        
        loading('.main-ctrl');    
        
        var usuario_representante_id = gScope.REPRESENTANTE_ID;
        
        var representante_args = {
            HABILITA_REPRESENTANTE: '1'
        };
        

        if ( usuario_representante_id > 0 ) {
            representante_args.EMPRESA_ID = usuario_representante_id;
        }
        
        vm.ConsultaRepresentante                        = vm.Consulta.getNew(true);
        vm.ConsultaRepresentante.componente             = '.consulta-representante';
        vm.ConsultaRepresentante.model                  = 'vm.ConsultaRepresentante';
        vm.ConsultaRepresentante.option.label_descricao = 'Representante:';
        vm.ConsultaRepresentante.option.obj_consulta    = '/_12090/api/empresas';
        vm.ConsultaRepresentante.option.tamanho_input   = 'input-maior';
        vm.ConsultaRepresentante.option.campos_tabela   = [['EMPRESA_ID','ID'],['EMPRESA_CNPJ_MASK', 'CNPJ/CPF'],['EMPRESA_NOMEFANTASIA','Nome Fantasia'],['EMPRESA_RAZAO_SOCIAL','Razão Social'],['EMPRESA_UF','UF'],['EMPRESA_CIDADE','Cidade']];
        vm.ConsultaRepresentante.option.obj_ret         = ['EMPRESA_CNPJ_MASK', 'EMPRESA_NOMEFANTASIA'];
        vm.ConsultaRepresentante.option.required        = false;        
        vm.ConsultaRepresentante.compile();
        vm.ConsultaRepresentante.setDataRequest(representante_args);
        gScope.ConsultaRepresentante = vm.ConsultaRepresentante;
        
        
        if ( usuario_representante_id > 0 ) {
            vm.ConsultaRepresentante.Input.disabled             = true;
            vm.ConsultaRepresentante.btn_apagar_filtro.disabled = true;
            vm.ConsultaRepresentante.filtrar();

        } else {
            vm.Empresas.consultar(true).then(function(){
                loading('hide');
            });        
        }
        

        vm.ConsultaRepresentante.onSelect = function(){

            vm.Empresas.emptyData();                
            
            vm.Empresas.FILTRO.REPRESENTANTE_ID = vm.ConsultaRepresentante.EMPRESA_ID;

            if ( usuario_representante_id > 0 ) {
                vm.ConsultaRepresentante.btn_apagar_filtro.disabled = true;
                vm.Empresas.consultar(true).then(function(){
                    loading('hide');
                });                
            } 
        };

        vm.ConsultaRepresentante.onClear = function (){
            
            delete vm.Empresas.FILTRO.REPRESENTANTE_ID;
            
            vm.Empresas.emptyData();    
        };

        vm.export1 = function(tabela,nome){
            exportTableToCsv(nome, tabela);
        };

        vm.export2 = function(tabela,nome){
            exportTableToXls(nome, tabela);
        };

        vm.export3 = function(div,descricao){
            var user = $('#usuario-descricao').val();
            var filtro = '' + vm.Empresa.MODELO_PRECO_FILTRO;
            printPDF('preco_modelos',div, 'Preço por Modelo - ' + descricao, filtro, user, '1.0.0',1,'');
        };

        vm.Imprimir = function(div,descricao){
            var user = $('#usuario-descricao').val();
            var filtro = '' + vm.Empresa.MODELO_PRECO_FILTRO;
            printHtml(div, 'Preço por Modelo - ' + descricao, filtro, user, '1.0.0',1,'');
        }           
        
        
        $timeout(function(){
            if ( vm.Empresa.SELECTED.EMPRESA_ID > 0 ) {
                vm.Empresa.open();
            }
        });

//        $timeout(function(){
//            vm.Filtro.consultar().then(function(){
//
//                loading('hide');
//                $timeout(function(){
//                    if ( vm.Filtro.COTA_ID > 0 ) {
//                        var cota = $('[data-cota-id="' + vm.Filtro.COTA_ID + '"]:focusable');
//
//                        cota.focus();
//
//                        $timeout(function(){
//                            if ( vm.Filtro.COTA_OPEN == 1 && gScope.Cota.SELECTED.ID != undefined ) {
//                                vm.Cota.dblPick(vm.Cota.SELECTED);
//                            }
//                        },100);
//                    }
//                },50);
//
//            });
//
//        },50);
//        
	}   
  
//# sourceMappingURL=_12090.js.map
