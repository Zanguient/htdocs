<?php

namespace app\Http\Controllers\Estoque\_15080;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Estoque
 */
class _15080Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'estoque/_15080';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'estoque._15080.index', [
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
                'REMESSA_ESTABELECIMENTO_ID'        => ['Código do Estabelecimento'                     ,'required'],
                'CONSUMO_LOCALIZACAO_ID'            => ['Código da Localização Padrão do Produto '      ,'required'],
                'CONSUMO_LOCALIZACAO_ID_PROCESSO'   => ['Código da Localização de Processo do Produto'  ,'required'],
                'CONSUMO_PRODUTO_ID'                => ['Código do Produto do Consumo'                  ,'required'],
                'CONSUMO_TAMANHO'                   => ['Código do Tamanho do Produto do Consumo'       ,'required'],
                'QUANTIDADE_SALDO'                  => ['Quantidade do Saldo Projetado'                 ,'required'],
                'GP_CCUSTO'                         => ['Centro de Custo do Grupo de Produção'          ,'required'],
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
}