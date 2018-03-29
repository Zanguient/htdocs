(function(window, angular) {
    'use strict';

    angular
        .module('gc-pessoal', ['gc-find'])
        .factory('ColaboradorCentroDeTrabalho', ColaboradorCentroDeTrabalho);

//    if (typeof module !== 'undefined' && module.exports) {
//        module.exports = Pessoal.name;
//    }

	ColaboradorCentroDeTrabalho.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout',
        '$consulta'
    ];

	function ColaboradorCentroDeTrabalho($ajax, $q, $rootScope, $compile,$timeout, $consulta) {

        // Private variables.
        var obj = null;
        
        var _model               = 'vm.ColaboradorCentroDeTrabalho';
        var _button              = null;
        var _args                = {};
        var _data_call_component = '[gc-pessoal-colaborador-centro-de-trabalho]';
        var _scope               = null;
        var _ctrl                = null;
        
	    /**
	     * Constructor, with class name
	     */
	    function ColaboradorCentroDeTrabalho(args) {
            
            obj = this; 

            // Public methods         
            this.getView        = getView; 
            this.getCtrl        = getCtrl; 
            this.callComponent  = callComponent; 
            this.autenticar     = autenticar; 
            this.confirmar      = confirmar; 
            this.cancelar       = cancelar; 
            this.Colaborador    = Colaborador; 
            this.Modal          = Modal; 
                        
            if ( args != undefined ) {
                if ( args.model != undefined ) {
                    _model = args.model;
                }
            }
                        
            _ctrl  = obj.getCtrl();
            _scope =_ctrl.scope();
                    
            _scope.$watch(_model+'.Colaborador.AUTENTICADO',function(newValue){
                if ( newValue ) {
                    obj.CCTConsultaPerfil.disable(false);
                obj.CCTConsultaUp.disable(false);
                    $timeout(function(){
                        obj.CCTConsultaPerfil.filtrar();
                        obj.CCTConsultaPerfil.setFocusInput();     
                    });
                } else
                if ( newValue == false ) {
                    obj.Colaborador.SELECTED = {};
                    obj.CCTConsultaPerfil.apagar();
                    obj.CCTConsultaPerfil.disable(true);
                    obj.CCTConsultaUp.disable(true);
                }
            });
                    
            _scope.$watch(_model+'.CCTConsultaUp.UP_CCUSTO',function(newValue){
                if ( newValue != undefined && newValue.trim() != '' ) {
                    obj.CONFIRMAR = true;
                } else {
                    obj.CONFIRMAR = false;                    
                }
            });
            
            obj.getView().then(function(){


                obj.Consulta   = new $consulta();
                
                obj.CCTConsultaPerfil                             = obj.Consulta.getNew(true);
                obj.CCTConsultaPerfil.componente                  = '.cct-consulta-perfil';
                obj.CCTConsultaPerfil.model                       = _model+'.CCTConsultaPerfil';
                obj.CCTConsultaPerfil.option.label_descricao      = 'Perfil do Grupo de Produção:';
                obj.CCTConsultaPerfil.option.obj_consulta         = '/_11200/api/perfil';
                obj.CCTConsultaPerfil.option.tamanho_input        = 'input-maior';
                obj.CCTConsultaPerfil.option.tamanho_tabela       = 427;
                obj.CCTConsultaPerfil.option.campos_tabela        = [['PERFIL_DESCRICAO', 'Descrição']];
                obj.CCTConsultaPerfil.option.obj_ret              = ['PERFIL_DESCRICAO'];
                obj.CCTConsultaPerfil.setDataRequest({TABELA: 'GP'});
                obj.CCTConsultaPerfil.compile();    
                obj.CCTConsultaPerfil.disable(true);
                            
                
                obj.CCTConsultaUp                             = obj.Consulta.getNew(true);
                obj.CCTConsultaUp.componente                  = '.cct-consulta-up';
                obj.CCTConsultaUp.model                       = _model+'.CCTConsultaUp';
                obj.CCTConsultaUp.option.label_descricao      = 'Up do Grupo de Produção:';
                obj.CCTConsultaUp.option.obj_consulta         = '/_22030/api/up';
                obj.CCTConsultaUp.option.tamanho_input        = 'input-maior';
                obj.CCTConsultaUp.option.tamanho_tabela       = 427;
                obj.CCTConsultaUp.option.campos_tabela        = [['UP_ID', 'Id'],['UP_DESCRICAO','Descrição']];
                obj.CCTConsultaUp.option.obj_ret              = ['UP_ID','UP_DESCRICAO'];
                obj.CCTConsultaUp.require                     = obj.CCTConsultaPerfil;
                obj.CCTConsultaUp.vincular();
                obj.CCTConsultaUp.setRequireRequest({GP_PERFIL: [obj.CCTConsultaPerfil, 'PERFIL_TABELA_ID']});
                obj.CCTConsultaUp.compile();    
                obj.CCTConsultaUp.disable(true);
                            
                
                obj.callComponent(function(){
                    obj.Modal.show();
                });
            });            
	    }
        
        /**
         * Chama a view
         */
	    function callComponent(callback){
            $(document).on('click',_data_call_component,function(){
                var button = $(this);
                $rootScope.$apply(function(){
                    
                    _button = button;
                    
                    if ( button.attr('data-args') != undefined ) {
                        _args = JSON.parse(button.attr('data-args'));
                    } else {
                        _args = {};
                    }
                    
                    callback != undefined && callback();
                });
            });         
            
        };    
        
        
        /**
         * Retorna a view da factory
         */
        function autenticar() {
            if ( !obj.Colaborador.AUTENDICADO ) {
                obj.Colaborador.consultarBarras();
            }
        }
        
        function confirmar() {
            
            var dados = {
                COLABORADOR_ID  : obj.Colaborador.SELECTED.COLABORADOR_ID,
                CCUSTO_PRODUCAO : obj.CCTConsultaUp.UP_CCUSTO
            };
    
            $ajax.post('/_23020/api/colaborador/centro-de-trabalho/update',dados).then(function(response){

                obj.Colaborador.AUTENTICADO = false;
                $timeout(function(){
                    $('#centro-de-trabalho-colaborador-barras:focusable').focus();
                });
//                obj.Modal.hide();
                
            });
        }
        
        function cancelar() {
            addConfirme('<h4>Confirmação</h4>',
                'Deseja realmente cancelar esta operação?',
                [obtn_sim,obtn_nao],
                [{ret:1,func:function(){
                    $rootScope.$apply(function(){
                        obj.Colaborador.AUTENTICADO = false;
                        obj.Modal.hide();
                    });
                }}]     
            );
        }
        
        
        
	    function getView(){
            
            return $q(function(resolve,reject){
                $ajax.get('/pessoal/colaborador-centro-de-trabalho').then(function(html){
                    
                    var template = angular.element(html.replace(/ctrl/g, _model));
                    
                    _ctrl.append($compile(template)(_scope));               
                    
                    resolve(true);
                },function(erro){
                    reject(erro);
                });            
            });
        };    

        
        function getCtrl(){
                return $('#main').find('[ng-controller]').first();
        };
        
        
        var Colaborador = {
            SELECTED : {},
            BARRAS : '',
            AUTENDICADO : false,
            consultarBarras : function() {
                var that = this;
                $ajax.post('/_23020/api/colaboradores',{COLABORADOR_CRACHA: that.BARRAS}).then(function(response){
                    if ( response.length == 1 ) {
                        that.SELECTED = response[0];
                        that.AUTENTICADO = true;                        
                    } else {
                        showErro('Código de barras do colaborador inválido.');
                    }
                    that.BARRAS = '';
                },function(){
                    that.BARRAS = '';
                });
            }
        };
        

        var Modal = {
            
            _modal : function(){
                return $('#modal-pessoal-colaborador-centro-de-trabalho');
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
	    return ColaboradorCentroDeTrabalho;
	};
   
})(window, window.angular);
//# sourceMappingURL=factory.colaborador-centro-de-trabalho.js.map
