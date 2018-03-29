<?php

namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\ConsultaAllDAO;

/**
 * DTO de de consulta generica
 * 
 * Os metodos dete objeto serverm apara gerar uma consulta apartir de parametros
 * cada parametro gera uma caracteristica da consulta
 * 
 * @version 1.0
 * @package Helper
 * @author Anderson Sousa <anderson@delfa.com.br>
 * @example Classe generica
 */
class ConsultaAll
{   
    
    /**
     * Variavel que contem o caminho do diretorio principal
     * @access private
      * @name $diretorio 
     */
      var $diretorio = "./" ;
      
      /**
      * Variavel que contem o caminho da pasta publica
      * @access private
      * @name $dir_public 
      */
      var $dir_public = "./public/temp" ;
      
      /** 
       * @var string|null variavel que recebera a descricao dos arquivos 
       * @access private
       */
      protected $description = null;
    
    /**
    * DTO da consulta generica
    * 
    * Este metodo chama e recebe o retorno do metodo ConsultaAllDAO::ConsultaAll
    * 
    * @version 1.0
    * @package Helper
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param String $filtro string que foi digitada pelo usuário no campo de filtro
    * @param string $obj local do arquivo que retornara os dados para tratamento
    * @param array $campos Um array com os campos da tabela que foram passados como parametro
    * @param array $condicao Um array com os parametros que foram passados
    * @uses ConsultaAll::ConsultaAll($filtro,$obj,$campos,$condicao) sendo nescessario importar App\Models\DTO\Helper\ConsultaAll.
    * @return array
    * @api
    */
	public static function ConsultaAll($filtro,$obj,$campos,$condicao,$condicao_campo,$imputs) {
		return ConsultaAllDAO::ConsultaAll($filtro,$obj,$campos,$condicao,$condicao_campo,$imputs);
	}
	
}
