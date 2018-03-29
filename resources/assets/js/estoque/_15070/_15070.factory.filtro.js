angular
    .module('app')
    .factory('Filtro', Filtro);
    

	Filtro.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        '$timeout',
        'gcCollection',
        'gScope'
    ];

function Filtro($ajax, $httpParamSerializer, $rootScope, $timeout, gcCollection, gScope) {

    /**
     * Constructor, with class name
     */
    function Filtro(data) {
        if (data) {
            this.setData(data);
        }
        
		gScope.Filtro = this; 
        
//        this.REMESSA_FAMILIA_ID = 133;
        this.VISUALIZACAO = '1';
    }
    
    Filtro.prototype.consultar = function() {
        
        var that = this;
        
        loading('.main-ctrl');        
        
        $ajax.post('/_15070/api/consumo',that,{progress: false}).then(function(response){
            
            that.merge(response);
            
            loading('hide');
            
        });
    };
    
    
    Filtro.prototype.merge = function(response) {

        gcCollection.merge(gScope.Consumo.DADOS, response, 'CONSUMO_ID');


        /**
         * Configura a dependência de talões
         */

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
         * Agrupa talões para as remessas
         */
        var taloes = gcCollection.groupBy(gScope.Talao.DADOS,[
            'REMESSA',
            'REMESSA_ID',
            'REMESSA_ESTABELECIMENTO_ID',
            'REMESSA_DATA',
            'REMESSA_DATA_TEXT',
            'REMESSA_FAMILIA_ID',
            'REMESSA_FAMILIA_DESCRICAO'
        ],'TALOES');

        gcCollection.merge(gScope.Remessa.DADOS, taloes, 'REMESSA_ID');



        /**
         * Configura a dependência de modelos
         */

        /**
         * Agrupa consumos para os talões
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

        gcCollection.bind(gScope.Remessa.DADOS, gScope.Consumo.PRODUTOS, 'REMESSA_ID', 'PRODUTOS');

        for ( var i in gScope.Remessa.DADOS ) {
            var remessa = gScope.Remessa.DADOS[i];

            remessa.QUANTIDADE          = 0;
            remessa.QUANTIDADE_CONSUMO  = 0;
            remessa.QUANTIDADE_SALDO    = 0;

            for ( var j in remessa.PRODUTOS ) {
                var produto = remessa.PRODUTOS[j];

                remessa.QUANTIDADE          += parseFloat(produto.QUANTIDADE        );
                remessa.QUANTIDADE_CONSUMO  += parseFloat(produto.QUANTIDADE_CONSUMO);
                remessa.QUANTIDADE_SALDO    += parseFloat(produto.QUANTIDADE_SALDO  );

                if ( remessa.QUANTIDADE_CONSUMO > remessa.QUANTIDADE ) {
                    remessa.QUANTIDADE_CONSUMO = remessa.QUANTIDADE;
                    remessa.QUANTIDADE_SALDO   = 0;
                }
            }
        }
    };

    /**
     * Return the constructor function
     */
    return Filtro;
};