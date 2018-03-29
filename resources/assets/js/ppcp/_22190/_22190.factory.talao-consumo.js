angular
    .module('app')
    .factory('TalaoConsumo', TalaoConsumo);
    

	TalaoConsumo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function TalaoConsumo($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function TalaoConsumo(data) {
        if (data) {
            this.setData(data);
        }

		gScope.TalaoConsumo = this; 
        
        this.DADOS = [];
        this.COMPONENTE_DADOS = [];
        this.ALOCADOS = [];
        this.SELECTED = {};
    }
    
    TalaoConsumo.prototype.consultar = function() {
        
        var that = this;
        
//        loading('.main-ctrl');     
        

        
        var data = {};

        angular.copy(that, data);
        
        if ( this.DATA_TODOS ) {
            delete data.DATA_1;
            delete data.DATA_2;
        }
        
        data.PROGRAMACAO_STATUS = "< 3";
        data.GP_ID              = gScope.ConsultaGp.GP_ID;
        data.UP_ID              = gScope.ConsultaUp.UP_ID;
        data.ESTACAO            = gScope.ConsultaEstacao.ESTACAO;
        
        $ajax.post('/_22190/api/talao',data,{progress: false}).then(function(response){
            
            that.merge(response);
            
//            loading('hide');
            
        });
    };
   
    


    TalaoConsumo.prototype.pick = function(item,action) {
        
        var that = this;

        if ( item != undefined ) {
        
            this.SELECTED = item;

            if ( action == 'modal-open' ) {
                that.open();
            }
        }

    };    

    TalaoConsumo.prototype.componenteAlocadoDelete = function(componente) {
        var data = {
            DADOS : componente,
            FILTRO : {
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID,
                TALAO_ID : gScope.Talao.SELECTED.TALAO_ID
            }
        };        
        
        $ajax.post('/_22190/api/consumo/componente/alocado/delete',data).then(function(response){
            gScope.Talao.mergeComposicao(response.DATA_RETURN.DADOS);
        });       

    };    



    $(document).off('click', '.alocado-excluir').on('click', '.alocado-excluir', function() {
        var _this   = this;
        
        $ajax.post('/_22010/projecaoVinculoExcluir',{id: $(_this).data('talao-vinculo-id')}).then(function(response){
            $('.popover').remove();
            gScope.Talao.consultarComposicao();
            
        });        
        
    });

    TalaoConsumo.prototype.componenteRegistrar = function(item,action) {
        
        var that = this;

        var data = {
            DADOS : {
                COMPONENTE_BARRAS   : that.COMPONENTE_BARRAS,
                TALAO_ID            : gScope.Talao.SELECTED.TALAO_ID,
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID
            },
            FILTRO : {
                REMESSA_ID          : gScope.Talao.SELECTED.REMESSA_ID,
                REMESSA_TALAO_ID    : gScope.Talao.SELECTED.REMESSA_TALAO_ID,
                TALAO_ID : gScope.Talao.SELECTED.TALAO_ID
            }
        };
        
        $ajax.post('/_22190/api/consumo/componente/alocar',data).then(function(response){
            
            gScope.Talao.mergeComposicao(response.DATA_RETURN.DADOS);
            $('#modal-registrar-componente').modal('hide');
            
        },function(){
            that.COMPONENTE_BARRAS = '';
        });
    };    

    TalaoConsumo.prototype.componenteModalOpen = function(item,action) {
        
        var that = this;

        that.COMPONENTE_BARRAS = '';
        
        var modal = $('#modal-registrar-componente');
        
        modal.modal('show');
        
        modal.one('shown.bs.modal', function() {
            $(this).find('input:focusable').first().focus();
        });
    };    


    var modal = $('#modal-talao');
    
    TalaoConsumo.prototype.open = function() {
        
        var that = this;
        if ( this.SELECTED != undefined ) {
            
            this.show();
        }
        
    };
  

    TalaoConsumo.prototype.confirm = function () {
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

    TalaoConsumo.prototype.setItens = function () {
        
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

    TalaoConsumo.prototype.show = function(shown,hidden) {

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

    TalaoConsumo.prototype.close = function(hidden) {

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
    
    TalaoConsumo.prototype.inputKeydown = function($event) {
         
        if ( this.PESO_AUTOMATICO ) {
            if ( isNumber($event.key) || $event.key == 'Backspace' || $event.key == 'Delete' ) {
                $event.preventDefault();
            }
        }
    };
    


    /**
     * Return the constructor function
     */
    return TalaoConsumo;
};