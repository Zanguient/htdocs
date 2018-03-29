(function(window, angular) {
    'use strict';

    angular
        .module('app')
        .factory('Comparar', Comparar);

	Comparar.$inject = [
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

	function Comparar($ajax, $q, $rootScope, $compile,$timeout, $consulta, gScope, gcCollection, gcObject) {

        // Private variables.
        var obj = null;
        var selected = null;
        
        
	    /**
	     * Constructor, with class name
	     */
	    function Comparar() {
            
            obj = this; 

            // Public methods         
  
            this.getTransportadoraCidade = getTransportadoraCidade;
            
            this.autoComparar = autoComparar;
            
            this.calcular       = calcular;
            this.inserirItem    = inserirItem;
            this.alterar        = alterar; 
            this.cancelar       = cancelar; 
            this.excluir        = excluir; 
          
            this.changeRebobinamento = changeRebobinamento;
            this.changeConformacao   = changeConformacao;
            
            this.Modal         = Modal; 
            
            this.ORDER_BY = '-DATA_ENTRADA_JS';
            
            this.FILTRO    = {
                DATA_1 : moment().subtract(1, "month").toDate(),
                DATA_2 : moment().toDate()                
            };
        
            
            this.INCLUINDO = false;
            this.ALTERANDO = false;
            this.DADOS = [];
            this.DADOS_RENDER = [];

            this.ITENS          = [];
            this.DADOS          = [];
            this.DADOS_DETALHES = [];
            this.TRANSPORTADORAS     = [];
            
            this.TOTAL_GERAL = 0;
            this.SELECTEDS = [];
            this.SELECTED = [];
            this.SELECTED_BACKUP = [];
            this.DOM_ID = 0;
            
	    }
        
        function getTransportadoraCidade(cidade_id) {
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/transportadora/cidade',{CIDADE_ID : cidade_id}).then(function(response){
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }
        
        function autoComparar(cidade_id) {
            
            getTransportadoraCidade(cidade_id).then(function(response){
                
                var transportadoras = response;
                
                for ( var i in transportadoras ) {
                    var transportadora = transportadoras[i];
                    
                    inserirItem({
                        TRANSPORTADORA_ID : transportadora.TRANSPORTADORA_ID,
                        TRANSPORTADORA_RAZAOSOCIAL : transportadora.RAZAOSOCIAL
                    },true);
                }
                
                inserirItem();
            });
        }
        
        function calcular(transportadora_id) {
            
            var filtro = {
                ORIGEM : obj.DADOS.ORIGEM,
                ORIGEM_ID : obj.DADOS.ORIGEM_ID,
                TRANSPORTADORA_ID : transportadora_id,
                ITENS : obj.DADOS.ITENS
            };
            
            return $q(function(resolve, reject){
                $ajax.post('/_14020/api/frete/calcular',filtro).then(function(response){
                    resolve(response);
                },function(e){
                    reject(e);
                });
            });
        }

        function inserirItem(transportadora,no_auto_insert) {
   
            obj.DOM_ID += 1;
            
            var item = {DOM_ID:obj.DOM_ID}; 
            
            var id = item.DOM_ID;
    
            item.REQUIRED = obj.TRANSPORTADORAS.length == 0 ? true : false;
            obj.TRANSPORTADORAS.push(item);
    

            
            item.ConsultaTransportadora                        = gScope.Ctrl.Consulta.getNew(true);
            item.ConsultaTransportadora.componente             = '.consulta-transportadora-'+id;
            item.ConsultaTransportadora.model                  = 'item.ConsultaTransportadora';
            item.ConsultaTransportadora.option.label_descricao = 'Transportadora:';
            item.ConsultaTransportadora.option.obj_consulta    = '/_14020/api/transportadora';
            item.ConsultaTransportadora.option.tamanho_input   = 'input-maior';
            item.ConsultaTransportadora.option.tamanho_tabela  = 650;
            item.ConsultaTransportadora.option.campos_tabela   = [['TRANSPORTADORA_ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['CLASSIFICACAO','Classificação']];
            item.ConsultaTransportadora.option.obj_ret         = ['TRANSPORTADORA_ID','RAZAOSOCIAL'];
            item.ConsultaTransportadora.option.required        = true;
            item.ConsultaTransportadora.autoload               = false;

            item.ConsultaTransportadora.onSelect = function(){
                
                
                obj.calcular(item.ConsultaTransportadora.TRANSPORTADORA_ID).then(function(response){
                    
                    angular.extend(item,response);
                    
                    item.COMPOSICOES = [
                        {
                            DESCRICAO : 'Composição dos Valores',
                            DADOS : response.DADOS_COMPOSICAO
                        },
                        {
                            DESCRICAO : 'Dados da Carga',
                            DADOS : response.DADOS_CARGA
                        }                         
                    ];                

                    if ( !(no_auto_insert == true) )  {
                        obj.inserirItem();
                    }
                });
                
            };
            
            item.ConsultaTransportadora.onClear = function() {
                
//                item.MODELO_ID = undefined;
                
                var idx = obj.TRANSPORTADORAS.indexOf(item);
                if ( obj.TRANSPORTADORAS.length-1 != idx ) {
                    obj.TRANSPORTADORAS.splice(idx,1);
                    
                    if ( obj.TRANSPORTADORAS.length == 1 ) {
                        obj.TRANSPORTADORAS[0].REQUIRED = true;
                        item.ConsultaTransportadora.option.required  = item.REQUIRED;
                    }
                }
                
            };



            $timeout(function(){
                item.ConsultaTransportadora.compile();

                if ( transportadora != undefined ) {
                    item.ConsultaTransportadora.TRANSPORTADORA_ID          = transportadora.TRANSPORTADORA_ID;
                    item.ConsultaTransportadora.TRANSPORTADORA_RAZAOSOCIAL = obj.DADOS.TRANSPORTADORA_RAZAOSOCIAL;
                    item.ConsultaTransportadora.Input.value = transportadora.TRANSPORTADORA_ID + ' - ' + transportadora.TRANSPORTADORA_RAZAOSOCIAL;
                    item.ConsultaTransportadora.Input.readonly             = true;
                    item.ConsultaTransportadora.btn_apagar_filtro.visivel  = true;
                    item.ConsultaTransportadora.btn_apagar_filtro.disabled = false;
                    item.ConsultaTransportadora.btn_filtro.visivel         = false;            
                    item.ConsultaTransportadora.item.selected              = true;  
                    item.ConsultaTransportadora.onSelect();
                }

            });
            
            return item;
        }

        function alterar() {
            
//            gScope.Ctrl.ImobilizadoItem.SELECTEDS = [];
            
            
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.value = obj.SELECTED.FAMILIA_PRODUCAO + ' - ' + obj.SELECTED.FAMILIA_PRODUCAO_DESCRICAO;  
            gScope.Ctrl.ConsultaFamiliaAgrup.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamiliaAgrup.btn_filtro.visivel         = false;              
            gScope.Ctrl.ConsultaFamiliaAgrup.item.selected = true;            
            
            
            gScope.Ctrl.ConsultaFamilia.Input.value = obj.SELECTED.FAMILIA_ID + ' - ' + obj.SELECTED.FAMILIA_DESCRICAO;
            gScope.Ctrl.ConsultaFamilia.Input.readonly             = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaFamilia.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaFamilia.btn_filtro.visivel         = false;            
            gScope.Ctrl.ConsultaFamilia.item.selected = true;            
            
            
            if ( obj.SELECTED.GP_ID > 0 ) {
                gScope.Ctrl.ConsultaGp.Input.value = obj.SELECTED.GP_ID + ' - ' + obj.SELECTED.GP_DESCRICAO;
                gScope.Ctrl.ConsultaGp.Input.readonly             = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaGp.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaGp.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaGp.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaGp.apagar();
                gScope.Ctrl.ConsultaGp.disable(true);
            }
            
            
            if ( obj.SELECTED.PERFIL_UP != null && obj.SELECTED.PERFIL_UP != '' ) {
                gScope.Ctrl.ConsultaPerfil.Input.value = obj.SELECTED.PERFIL_UP + ' - ' + obj.SELECTED.PERFIL_UP_DESCRICAO;
                gScope.Ctrl.ConsultaPerfil.Input.readonly             = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaPerfil.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaPerfil.btn_filtro.visivel         = false;            
                gScope.Ctrl.ConsultaPerfil.item.selected = true;            
            } else {
                gScope.Ctrl.ConsultaPerfil.apagar();
                gScope.Ctrl.ConsultaPerfil.disable(true);
            }
            
            
            gScope.Ctrl.ConsultaUp1.Input.value = obj.SELECTED.UP_PADRAO1 + ' - ' + obj.SELECTED.UP_PADRAO1_DESCRICAO;
            gScope.Ctrl.ConsultaUp1.Input.readonly             = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaUp1.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaUp1.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaUp1.item.selected = true;
            
            
            if ( obj.SELECTED.UP_PADRAO2 > 0 ) {
                gScope.Ctrl.ConsultaUp2.Input.value = obj.SELECTED.UP_PADRAO2 + ' - ' + obj.SELECTED.UP_PADRAO2_DESCRICAO;
                gScope.Ctrl.ConsultaUp2.Input.readonly             = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.visivel  = true;
                gScope.Ctrl.ConsultaUp2.btn_apagar_filtro.disabled = false;
                gScope.Ctrl.ConsultaUp2.btn_filtro.visivel         = false;
                gScope.Ctrl.ConsultaUp2.item.selected              = true; 
            } else {
                gScope.Ctrl.ConsultaUp2.apagar();
            }
            
            
            gScope.Ctrl.ConsultaCLogistica.Input.value = obj.SELECTED.CLOGISTICA_MASK + ' - ' + obj.SELECTED.CLOGISTICA_DESCRICAO;
            gScope.Ctrl.ConsultaCLogistica.Input.readonly             = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.visivel  = true;
            gScope.Ctrl.ConsultaCLogistica.btn_apagar_filtro.disabled = false;
            gScope.Ctrl.ConsultaCLogistica.btn_filtro.visivel         = false;
            gScope.Ctrl.ConsultaCLogistica.item.selected = true;            
            
            
            this.ALTERANDO = true;
            this.INCLUINDO = false;
            angular.copy(this.SELECTED, this.SELECTED_BACKUP);
            
            obj.Modal.show();
        };        
        
        function cancelar() {
            
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        
                             
                        
                        if ( obj.ALTERANDO && !obj.INCLUINDO ) {
                            angular.extend(obj.SELECTED, obj.SELECTED_BACKUP);  
                            
                            obj.Modal.hide();
                        }
                        
                        obj.ALTERANDO = false;
                        
                        if ( obj.INCLUINDO ) {
                            obj.INCLUINDO = false;
                            
                            var idx = obj.DADOS.indexOf(obj.SELECTED);
                            
                            obj.DADOS.splice(idx,1);                            
                                                    
                            
//                            obj.selectedReset();
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
                        
                        obj.SELECTED.EXCLUIDO = true;
                    });
                }}]     
            );
        }
        
        
        /**
         * Retorna a view da factory
         */
        function renderChart(response) {


            var obj = $('#chart_div');
            var pai = $(obj).closest('#chart-google');

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(function() {
                
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var dados = response;
                var item = null;
                
                var chart_container = [];
                var chart_item = [];
                
                for ( var i in dados ) {
                    item = dados[i];
                    
                    chart_item = [
                        {
                            v:''+item.ID,
                            f:item.CLOGISTICA_DESCRICAO,
                            id: ''+item.ORDEM
                        },
                        ''+( (item.ORDEM - 1) > 0 ? item.ORDEM - 1 : ''),
                        ''
                    ];
                    
                    chart_container.push(chart_item);
                }
                
                // For each orgchart box, provide the name, manager, and tooltip to show.
                data.addRows(chart_container);

                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                chart.draw(data, {allowHtml:true});

                setTimeout(function(){
//
//                    $('.img-loading').css('display','none');
                    $(obj).replaceWith( $compile($(obj).html())($rootScope) );
                    $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                    $rootScope.$apply(function () {
                        $rootScope.message = "Timeout called!";
                    });

                },400);
            });
        }
        
        function changeConformacao() {
            if ( obj.SELECTED.CALCULO_CONFORMACAO_MODEL ) {
                obj.SELECTED.CALCULO_CONFORMACAO           = 1;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_CONFORMACAO           = 0;
                obj.SELECTED.CALCULO_CONFORMACAO_DESCRICAO = 'NÃO';
            }
        }  
        
        function changeRebobinamento(key) {
            if ( obj.SELECTED.CALCULO_REBOBINAMENTO_MODEL ) {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 1;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'SIM';
            } else {
                obj.SELECTED.CALCULO_REBOBINAMENTO           = 0;
                obj.SELECTED.CALCULO_REBOBINAMENTO_DESCRICAO = 'NÃO';
            }
        }  
        
        var Modal = {
            
            _modal : function(){
                return $('#modal-regra');
            },
            show : function(shown,hidden) {

                this._modal()
                    .modal('show')
                ;                         


                this._modal()
                    .one('shown.bs.modal', function(){

                        $(this).find('input:focusable').first().select();

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
	    return Comparar;
	};
   
})(window, window.angular);