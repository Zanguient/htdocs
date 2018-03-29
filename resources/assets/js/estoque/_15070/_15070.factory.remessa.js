angular
    .module('app')
    .factory('Remessa', Remessa);
    

	Remessa.$inject = [
        '$ajax',
        '$rootScope',
        '$q',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Remessa($ajax, $rootScope, $q, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Remessa(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Remessa = this; 
        
        this.DADOS                  = [];
        this.SELECTED               = {};
        this.FILTRO                 = '';
        this.FAMILIAS               = [];
        this.FAMILIAS_CHECKEDS      = [];
        this.FAMILIAS_CHECKEDS_LIST = '';
        this.CONSUMOS               = [];
        this.CONSUMO_PERCENTUAL     = '< 1';
        this.DATA_1                 = '01.01.1989'; 
        this.DATA_2                 = '01.01.2500';        
    }

    this.GUIA_ATIVA = 'TALAO_PRODUZIR';
    
    /**
     * Private property
     */
    var url_base        = '_15070/api';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    
    
    Remessa.prototype.pick = function(remessa,setfocus) {
        
        var that = this;

        if ( remessa != undefined ) {

            if ( remessa != this.SELECTED ) {
                gScope.Talao.pick({});
            }        

            this.SELECTED       = remessa;

            if ( setfocus ) {
                that.setFocus();
            }
        }

    };
    
    Remessa.prototype.click = function(remessa) {
        
        this.SELECTED != remessa ? this.pick(remessa) : '';
        
    };
    
    Remessa.prototype.dblClick = function() {
        $timeout(function(){
            $('#tab-visualizacao-por-consumo').click();
        });
    };
    
    Remessa.prototype.etiqueta = function(remessa,setfocus) {
        
        var that = this;

        $ajax.post('/_15070/api/etiqueta',that.SELECTED).then(function(response){
            
            postprint(response);
                        
        });

    };
    
    Remessa.prototype.setFocus = function() {
        
        $timeout(function(){
            $('.table-remessa .table-lc-body tr.selected:focusable').focus();
        },50);                      
    };
    
    
    Remessa.prototype.consultarFamilia = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){     

            $ajax.post('/_15070/api/familia',{}).then(function(response){

                that.mergeFamilia(response);

                resolve(response);

            },function(erro){
                reject(erro);
            });
        });
    };

    Remessa.prototype.consultar = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){
            if ( that.FAMILIAS_CHECKEDS_LIST.trim() == '' ) {
                showErro('Selecione uma familia de produto.');
            } else {

                var data = {
                    REMESSA_FAMILIAS_ID : that.FAMILIAS_CHECKEDS_LIST,
                    CONSUMO_PERCENTUAL  : that.CONSUMO_PERCENTUAL,
                    PERIODO             : [moment(that.DATA_1).format('DD.MM.YYYY'),moment(that.DATA_2).format('DD.MM.YYYY')]
                };

                $ajax.post('/_15070/api/remessa',data).then(function(response){

                    that.merge(response);

                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
        
    };

    Remessa.prototype.consultarConsumos = function() {
        
        var that = this;
        
        return $q(function(resolve,reject){
            if ( that.FAMILIAS_CHECKEDS_LIST.trim() == '' ) {
                showErro('Selecione uma familia de produto.');
            } else {

                var data = {
                    REMESSA_ID : that.SELECTED.REMESSA_ID
                };

                $ajax.post('/_15070/api/consumo',data).then(function(response){

                    that.mergeConsumo(response);
                    
                    resolve(response);

                },function(erro){
                    reject(erro);
                });
            }
        });
        
    };
    
    
    Remessa.prototype.merge = function(response) {

        gcCollection.merge(this.DADOS, response, 'REMESSA_ID');

    };    
    
    
    Remessa.prototype.mergeFamilia = function(response) {

        gcCollection.merge(this.FAMILIAS, response, 'REMESSA_FAMILIA_ID');

    };    
    
    
    Remessa.prototype.mergeConsumo = function(response) {

        gcCollection.merge(gScope.Consumo.DADOS, response, 'CONSUMO_ID');

        /**
         * Agrupa consumos para os talões
         */
        var taloes_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'REMESSA_TALAO_ID',
            'MODELO_ID',
            'MODELO_DESCRICAO',
            'COR_ID',
            'COR_DESCRICAO',
            'GRADE_ID',
            'TAMANHO',
            'TAMANHO_DESCRICAO',
            'QUANTIDADE_TALAO',
            'UM_TALAO'
        ],'CONSUMOS');

        gcCollection.merge(gScope.Talao.DADOS, taloes_consumos, ['REMESSA_ID','REMESSA_TALAO_ID']);

        /**
         * Agrupa consumos para os produtos
         */
        var produtos_consumos = gcCollection.groupBy(gScope.Consumo.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO',
            'CONSUMO_FAMILIA_ID',
            'CONSUMO_FAMILIA_DESCRICAO',
            'CONSUMO_PRODUTO_ID',
            'CONSUMO_PRODUTO_DESCRICAO',
            'CONSUMO_GRADE_ID',
            'CONSUMO_TAMANHO',
            'CONSUMO_TAMANHO_DESCRICAO',
            'QUANTIDADE_ESTOQUE',
            'CONSUMO_UM',
            'CONSUMO_STATUS',
            'CONSUMO_STATUS_DESCRICAO',
            'CONSUMO_LOCALIZACAO_ID',
            'CONSUMO_LOCALIZACAO_ID_PROCESSO',
            'GP_CCUSTO'
        ],'TALOES');

        gcCollection.merge(gScope.Consumo.PRODUTOS, produtos_consumos, ['REMESSA_ID','CONSUMO_PRODUTO_ID','CONSUMO_TAMANHO']);

        for ( var i in gScope.Consumo.PRODUTOS ) {
            var produto = gScope.Consumo.PRODUTOS[i];

            produto.QUANTIDADE          = 0;
            produto.QUANTIDADE_CONSUMO  = 0;
            produto.QUANTIDADE_SALDO    = 0;

            for ( var j in produto.TALOES ) {
                var talao = produto.TALOES[j];

                produto.QUANTIDADE          += parseFloat(talao.QUANTIDADE        );
                produto.QUANTIDADE_CONSUMO  += parseFloat(talao.QUANTIDADE_CONSUMO);
                produto.QUANTIDADE_SALDO    += parseFloat(talao.QUANTIDADE_SALDO  );

            }
        }


        gcCollection.bind(gScope.Remessa.DADOS, gScope.Talao.DADOS     , 'REMESSA_ID', 'TALOES');
        gcCollection.bind(gScope.Remessa.DADOS, gScope.Consumo.PRODUTOS, 'REMESSA_ID', 'PRODUTOS');

    };    
    
    
    Remessa.prototype.toggleCheckFamilia = function(item,type) {

        item.CHECKED = item.CHECKED ? false : true;

        if ( type != undefined ) {
            item.CHECKED = type == true ? true : false;
        }

        var index = this.FAMILIAS_CHECKEDS.indexOf(item);

        if ( index == -1 ) {
            this.FAMILIAS_CHECKEDS.push(item);   
        }                 
        else 
        if ( index > -1 ) {
            this.FAMILIAS_CHECKEDS.splice(index, 1);                      
        }
    
        this.FAMILIAS_CHECKEDS_LIST = arrayToList(this.FAMILIAS_CHECKEDS, 'REMESSA_FAMILIA_ID' );
        
    };   
    
    function arrayToList( array, field, str, val_def ) {
        
        val_def = val_def   || '';
        str     = str       || false;
        field   = field     || false;
        
        var list    = '';
        var i       = -1;

        if ( Array.isArray(array) ) {
            for ( var key in array ) {
                
                var o    = array[key];
                var item = '';

                i++;

                //Verifica se existe um campo com nome 
                if (field) {
                    o = o[field];
                }

                //Verifica se é uma string e pega o caractere
                if ( str ) {
                    item = str . o . str;
                } else {
                    item = o;
                }

                list = ( i == 0 ) ? item : list  + ', ' + item;
            }   
        } else {
            list = array;
        }

        return ( list != '' ) ? list : val_def;
    }  

    /**
     * Return the constructor function
     */
    return Remessa;
};