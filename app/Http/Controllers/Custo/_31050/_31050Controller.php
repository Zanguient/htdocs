<?php

namespace app\Http\Controllers\Custo\_31050;

use App\Helpers\UserControl;
use App\Models\DTO\Custo\_31050;

/**
 * Controller do objeto _22010 - Consulta de Custo
 */
class _31050Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'custo/_31050';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'custo._31050.index', [
            'menu'          => $this->menu
		]);  
    }
    
    public function validaBarrasPeca($cod_barras) {
        
        $ret = (object) [
            'BARRAS' => $cod_barras
        ];
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12 ) {
            log_erro('Código de barras de peça inválido. Código: ' . $cod_barras);
        }
        /**
         * Verifica para onde será direcionado a consulta
         * 'RD' = [R=REMESSA; D=DETALHE]   Id do talão Detalhado 
         * 'P'  = [P=PESAGEM]              Id da Pesagem 
         */
        else 
        if ( strstr($cod_barras, 'RD') ) {
            
            $ret->TIPO      = 'D';
            $ret->TABELA    = 'VWREMESSA_TALAO_DETALHE';
            $ret->TABELA_ID = (float) str_replace('RD', '', $cod_barras);
            
        }
        else 
        if ( strstr($cod_barras, 'P') ){
            
            $ret->TIPO      = 'R';
            $ret->TABELA    = 'TBREVISAO';
            $ret->TABELA_ID = (float) str_replace('P', '', $cod_barras);
            
        }
        else {
            log_erro('Tipo de código de barras de peça inválido. Código informado: ' . $cod_barras);
        }
        
        return $ret;
    }
    
    public function distribuirQuantidade($itens,$saldo, $callback) {

        $saldo = (float) $saldo;

        if ( ! ($saldo > 0) ) {
            log_erro('Quantidade informada deve ser maior que zero.');
        }

        foreach ($itens as $item ) {
            
            validator($item, [
                'CONSUMO_ID'                        => ['Código do Consumo'                             ,'required'],
                'CONSUMO_ESTABELECIMENTO_ID'        => ['Estabelecimento do Consumo'                    ,'required'],
                'CONSUMO_PROCESSO_LOCALIZACAO_ID'   => ['Localização de Processo do Produtro do Consumo','required'],
                'CONSUMO_PRODUTO_ID'                => ['Produto do Consumo'                            ,'required'],
                'CONSUMO_TAMANHO'                   => ['Código do Tamanho do Consumo'                  ,'required'],
                'QUANTIDADE_CONSUMO'                => ['Quantidade Baixar'                             ,'required'],
                'TALAO_GP_CCUSTO'                   => ['C.Custo do Grupo de Produção do Talão'         ,'required'],
            ],true);                 

            $item->QUANTIDADE_SALDO   = (float) $item->QUANTIDADE_SALDO;
            $item->QUANTIDADE_CONSUMO = 0;
        }
        
        $last = end($itens);
        $i= 0;
        do {
            $item = $itens[$i];

            if ( $last == $item ) {
                $item->QUANTIDADE_CONSUMO = $saldo;

                $saldo = 0;
            } else {      

                if ( $item->QUANTIDADE_SALDO > 0 ) {
                    
                    $valor = ( $item->QUANTIDADE_SALDO > $saldo ) ? $saldo : $item->QUANTIDADE_SALDO;

                    $item->QUANTIDADE_CONSUMO = $valor;

                    $saldo -= $valor;
                }
            }

            $i++;
        }
        while ( $saldo > 0 );                    

        foreach ($itens as $key => $item) {
            if ( $item->QUANTIDADE_CONSUMO > 0 ) {
                if (is_callable($callback)) {
                    $callback($item);
                }  
            }
        }        
    }
    
    
    public function processarEtiquetas($item, _31050 $dto) {
        
        $etiqueta = $dto->selectEtiqueta((object)['ETIQUETA_TIPO' => 'W31050']);
        
        if ( isset($etiqueta[0]) ) {
            $etiqueta = $etiqueta[0]->SCRIPT;
        }
        
        $itens_ean = $dto->selectRemessaTalaoEAN($item);
        
        $ret = '';
        foreach ( $itens_ean as $item_ean ) {
            
            if ( ! ($item_ean->CODIGO_EAN >0)  ) {
                log_erro('O Modelo ' . $item_ean->MODELO_DESCRICAO . ' Cor ' . $item_ean->COR_DESCRICAO . ' Tam. ' . $item_ean->TAMANHO_DESCRICAO . ' não possui Código EAN definido.');
            }
            
            $str_talao = $etiqueta;
            
            $str_talao = str_replace('#CAMPO1#', $item_ean->MODELO_DESCRICAO . ' ' . $item_ean->COR_DESCRICAO, $str_talao);                      
            $str_talao = str_replace('#CAMPO2#', $item_ean->TAMANHO_DESCRICAO                                , $str_talao);                      
            $str_talao = str_replace('#CAMPO3#', $item_ean->CODIGO_EAN                                       , $str_talao);            
            
            $ret = $ret . $str_talao;
        }
        
        return $ret;
    }
}