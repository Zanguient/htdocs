<?php

namespace app\Http\Controllers\Ppcp\_22180;

use App\Helpers\UserControl;
use App\Models\DTO\Ppcp\_22180;

/**
 * Controller do objeto _22010 - Consulta de Ppcp
 */
class _22180Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22180';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'ppcp._22180.index', [
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
    
    
    public function processarEtiquetas($item, _22180 $dto) {
        
        $etiqueta = $dto->selectEtiqueta((object)['ETIQUETA_TIPO' => 'W22180']);
        
        if ( isset($etiqueta[0]) ) {
            $etiqueta = $etiqueta[0]->SCRIPT;
        }
        
        $etiqueta_itens = $dto->selectEtiquetaDados($item);
        
        $ret = '';
        foreach ( $etiqueta_itens as $item ) {
            
//            if ( ! ($item_ean->CODIGO_EAN >0)  ) {
//                log_erro('O Modelo ' . $item_ean->MODELO_DESCRICAO . ' Cor ' . $item_ean->COR_DESCRICAO . ' Tam. ' . $item_ean->TAMANHO_DESCRICAO . ' não possui Código EAN definido.');
//            }
            
            $str_talao = $etiqueta;
            
            $str_talao = str_replace('#IMAGEM'           , '', $str_talao);     
            $str_talao = str_replace('#PEDIDO#'          , '', $str_talao);                      
            $str_talao = str_replace('#CLIENTE_ETIQUETA#', '', $str_talao);                      
            $str_talao = str_replace('#CODIGO1'          , $item->CODIGO_BARRAS                                          , $str_talao);                      
            $str_talao = str_replace('#CODIGO2'          , $item->REMESSA_TALAO_DETALHE_ID                               , $str_talao);                      
            $str_talao = str_replace('#DATAHORA'         , date_format(date_create($item->DATAHORA_PRODUCAO),'d/m/y H:i'), $str_talao);                      
            $str_talao = str_replace('#OPERADOR_ID'      , $item->OPERADOR_ID                                            , $str_talao);                      
            $str_talao = str_replace('#OPERADOR_NOME'    , $item->OPERADOR_DESCRICAO                                     , $str_talao);                      
            $str_talao = str_replace('#VIA'              , $item->VIA_ETIQUETA                                           , $str_talao);                      
            $str_talao = str_replace('#CONTROLE'         , $item->REMESSA_TALAO_DETALHE_ID                               , $str_talao);                      
            $str_talao = str_replace('#SKU'              , ''                                                            , $str_talao);                      
            $str_talao = str_replace('#PRODUTO2'         , $item->MODELO_DESCRICAO . ' ' . $item->COR_DESCRICAO          , $str_talao);                      
            $str_talao = str_replace('#QUANTIDADE'       , $item->QUANTIDADE_PRODUCAO                                    , $str_talao);                      
            $str_talao = str_replace('#TAMANHO'          , $item->TAMANHO_DESCRICAO_BR                                   , $str_talao);                      
            $str_talao = str_replace('#TAM_USA'          , $item->TAMANHO_DESCRICAO_USA                                  , $str_talao);                      
            $str_talao = str_replace('#TAM_EUR'          , $item->TAMANHO_DESCRICAO_EUR                                  , $str_talao);                      
            $str_talao = str_replace('#NUMERO_TALAO'     , $item->REMESSA_TALAO_ID                                       , $str_talao);                      
            $str_talao = str_replace('#REMESSA'          , $item->REMESSA                                                , $str_talao);                      
            $str_talao = str_replace('#GP_DESCRICAO'     , ''                                                            , $str_talao);                      
            
            $ret = $ret . $str_talao;
        }
        
        return $ret;
    }
}