<?php

namespace App\Models\DAO\Produto;

use App\Models\Conexao\_Conexao;
use Exception;

/**
 * DAO do objeto 27040 - Grade
 */
class _27040DAO {
	
	/**
     * Listar tamanhos do produto.
	 * 
	 * @param integer $id_prod
     * @return array
     */
    public static function listarTamanho($id_prod) {

        $con = new _Conexao();

        $sql = 'SELECT * from spc_grade_produto(:PRODUTO_ID)';

        $args = array(':PRODUTO_ID' => $id_prod);

        $retorno = $con->query($sql, $args);

        if (count($retorno) > 0) {

            $t_tamanhos = $retorno[0]->TOTAL_TAMANHOS;
            $descricao = $retorno[0]->DESCRICAO;

            $t01 = $retorno[0]->T01;
            $t06 = $retorno[0]->T06;
            $t02 = $retorno[0]->T02;
            $t07 = $retorno[0]->T07;
            $t03 = $retorno[0]->T03;
            $t08 = $retorno[0]->T08;
            $t04 = $retorno[0]->T04;
            $t09 = $retorno[0]->T09;
            $t05 = $retorno[0]->T05;
            $t10 = $retorno[0]->T10;

            $t11 = $retorno[0]->T11;
            $t16 = $retorno[0]->T16;
            $t12 = $retorno[0]->T12;
            $t17 = $retorno[0]->T17;
            $t13 = $retorno[0]->T13;
            $t18 = $retorno[0]->T18;
            $t14 = $retorno[0]->T14;
            $t19 = $retorno[0]->T19;
            $t15 = $retorno[0]->T15;
            $t20 = $retorno[0]->T20;

            $a01 = $retorno[0]->A01;
            $a06 = $retorno[0]->A06;
            $a02 = $retorno[0]->A02;
            $a07 = $retorno[0]->A07;
            $a03 = $retorno[0]->A03;
            $a08 = $retorno[0]->A08;
            $a04 = $retorno[0]->A04;
            $a09 = $retorno[0]->A09;
            $a05 = $retorno[0]->A05;
            $a10 = $retorno[0]->A10;

            $a11 = $retorno[0]->A11;
            $a16 = $retorno[0]->A16;
            $a12 = $retorno[0]->A12;
            $a17 = $retorno[0]->A17;
            $a13 = $retorno[0]->A13;
            $a18 = $retorno[0]->A18;
            $a14 = $retorno[0]->A14;
            $a19 = $retorno[0]->A19;
            $a15 = $retorno[0]->A15;
            $a20 = $retorno[0]->A20;

            $Tamanhos = array();
            $Temp = array($descricao, $t_tamanhos);
            array_push($Tamanhos, $Temp);
            $Temp = array($t01, $a01, 1);
            array_push($Tamanhos, $Temp);
            $Temp = array($t02, $a02, 2);
            array_push($Tamanhos, $Temp);
            $Temp = array($t03, $a03, 3);
            array_push($Tamanhos, $Temp);
            $Temp = array($t04, $a04, 4);
            array_push($Tamanhos, $Temp);
            $Temp = array($t05, $a05, 5);
            array_push($Tamanhos, $Temp);
            $Temp = array($t06, $a06, 6);
            array_push($Tamanhos, $Temp);
            $Temp = array($t07, $a07, 7);
            array_push($Tamanhos, $Temp);
            $Temp = array($t08, $a08, 8);
            array_push($Tamanhos, $Temp);
            $Temp = array($t09, $a09, 9);
            array_push($Tamanhos, $Temp);
            $Temp = array($t10, $a10, 10);
            array_push($Tamanhos, $Temp);
            $Temp = array($t11, $a11, 11);
            array_push($Tamanhos, $Temp);
            $Temp = array($t12, $a12, 12);
            array_push($Tamanhos, $Temp);
            $Temp = array($t13, $a13, 13);
            array_push($Tamanhos, $Temp);
            $Temp = array($t14, $a14, 14);
            array_push($Tamanhos, $Temp);
            $Temp = array($t15, $a15, 15);
            array_push($Tamanhos, $Temp);
            $Temp = array($t16, $a16, 16);
            array_push($Tamanhos, $Temp);
            $Temp = array($t17, $a17, 17);
            array_push($Tamanhos, $Temp);
            $Temp = array($t18, $a18, 18);
            array_push($Tamanhos, $Temp);
            $Temp = array($t19, $a19, 19);
            array_push($Tamanhos, $Temp);
            $Temp = array($t20, $a20, 20);
            array_push($Tamanhos, $Temp);

            return array($Tamanhos, $retorno[0]);
			
        } else {
            log_erro('Produto sem tamanhos definidos.');
        }
    }
}
