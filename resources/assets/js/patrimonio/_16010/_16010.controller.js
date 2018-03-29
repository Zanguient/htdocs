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
  