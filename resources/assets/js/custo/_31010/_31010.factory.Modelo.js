angular
    .module('app')
    .factory('Modelo', Modelo);
    

	Modelo.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope',
        '$consulta'
    ];

function Modelo($ajax, $httpParamSerializer, $rootScope, $q, $timeout, gcCollection, gScope, $consulta) {

    /**
     * Constructor, with class name
     */
    function Modelo(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Modelo = this; 
        
        this.ITENS = [];
        this.FILTRO = '';
        this.ORDEM = 'MODELO_DESCRICAO';
        this.SELECTED = [];
        this.TIME = [];
        this.Perfil = [];

        this.Consulta   = new $consulta();
        
        this.ConsultaModelo = this.Consulta.getNew();
            
        this.ConsultaModelo.componente                  = '.consulta-modelo';
        this.ConsultaModelo.option.class                = 'modeloctrl';
        this.ConsultaModelo.model                       = 'vm.Modelo.ConsultaModelo';
        this.ConsultaModelo.option.label_descricao      = 'Modelo:';
        this.ConsultaModelo.option.obj_consulta         = '/_31010/Consultar';
        this.ConsultaModelo.option.tamanho_input        = 'input-maior';
        this.ConsultaModelo.option.tamanho_tabela       = 427;
        this.ConsultaModelo.compile();

    }
    
    Modelo.prototype.open = function(item) {
        var that = this;

        $('#modal-sku').modal();
    }

    Modelo.prototype.close = function() {
        var that = this;

        $('#modal-sku').modal('hide');
    }

    Modelo.prototype.ConsultarPerfil = function() {
        var that = this;

        var ds = {
                MODELO  : gScope.Modelo.ConsultaModelo.selected,
                COR     : gScope.Cor.SELECTED,
                TAMANHO : gScope.Tamanho.SELECTED
            };

        $ajax.post('/_31010/ConsultarPerfil',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.Perfil = response; 
     
            }
        );
    }

    Modelo.prototype.consultar = function() {
        var that = this;

        var ds = {
                ID : 0
            };

        $ajax.post('/_31010/Consultar',ds,{contentType: 'application/json'})
            .then(function(response) {
                that.ITENS = response;

                var grupos = gcCollection.groupBy(response, [
                    'MODELO_CODIGO',
                    'MODELO_DESCRICAO'
                ], 'COR'); 
                
                gcCollection.merge(that.ITENS, grupos, ['COR_CODIGO', 'COR_DESCRICAO']);             
            }
        );
    }

    Modelo.prototype.Selectionar = function (modelo) {
        var that = this;

        if(that.SELECTED.MODELO_CODIGO != modelo.MODELO_CODIGO){
            that.SELECTED = modelo; 
        }
        
    }

    /**
     * Return the constructor function
     */
    return Modelo;
};