<?php
  2 
  3     namespace C:\xampp\htdocs\GCWEB\resources\assets\js\compras;
  4     
  5     /** 
  6      * js de compras
  7      * 
  8      * Esta view aceita parametros para montar uma consulta
  9      * @version 1.0
 10      * @package includes
 11      * @author Anderson Sousa <anderson@delfa.com.br>
 12      */
 13     
 14     class consulta
 15         {
 16             
 17             /**
 18             * Include (view) da consulta generica
 19             * 
 20             * Os metodos dete objeto serverm apara gerar uma consulta apartir de parametros
 21             * cada parametro gera uma caracteristica da consulta
 22             * 
 23             * @version 1.0
 24             * @package Helper
 25             * @author Anderson Sousa <anderson@delfa.com.br>
 26             * @param string $label_descricao Descrição do label da consulta
 27             * @param array $obj_consulta Caminho do arquivo que que retornara os dados para tratamento
 28             * @param array $obj_ret retorno que sera inserido no campo de pesquisa Ex.: ['ID','DESCRICAO']
 29             * @param array(array) $campos_imputs Imputs hidden que serao criados e recebe um calor da consulta Ex.: [['_DESC','DESCRICAO']]
 30             * @param array(array) $recebe_valor Imputs que irão recebe um calor da consulta Ex.: [['consulta-campo1','ID']]
 31             * @param array $campos_sql campos da consulta
 32             * @param array $filtro_sql Parametros para modificar a coinsulta Ex.: ['so_ativos']
 33             * @param array $campos_tabela Campos da tabela que sera montado na tela Ex.:
 34             * @param array(array) $campos_titulo Descrição dos campos da tabela que sera montado na tela mais a largura da coluna Ex.: [['ID','80'],['DESCRICAO','200']]
 35             * @param string $class Classe que sera adicionada no botão
 36             * @param string $class1 Classe que sera adicionado no imput
 37             * @param string $class2 Classe que sera adicionado no input-group
 38             * 
 39             * @example <pre>include('helper.include.view.consulta',
 40             *     [
 41             *       'label_descricao'   => 'Consulta Generica',
 42             *       'obj_consulta'      => 'helper/include/ccusto',
 43             *       'obj_ret'           => ['ID','DESCRICAO'],
 44             *       'campos_imputs'     => [['_ID','ID'],['_DESC','DESCRICAO'],['_MASK','MASK']],
 45             *       'recebe_valor'      => [['consulta-campo1','ID'],['consulta-campo2','DESCRICAO'],['consulta-campo3','MASK']],
 46             *       'campos_sql'        => ['ID','DESCRICAO','MASK'],
 47             *       'filtro_sql'        => ['so_ativos'],
 48             *       'campos_tabela'     => [['ID','80'],['DESCRICAO','200'],['MASK','80']],
 49             *       'campos_titulo'     => ['ID','DESCRICAO','MASCARA'],
 50             *       'class1'            => 'input-medio',
 51             *     ]
 52             *   )</pre>
 53              * 
 54             */
 55             public static function consulta($label_descricao, $obj_consulta, $obj_ret, $campos_imputs,
 56                                             $recebe_valor, $campos_sql, $filtro_sql, $campos_tabela,
 57                                             $campos_titulo, $class , $class1, $class2 ){}
 58 
 59         }
 60  
 61 ?>