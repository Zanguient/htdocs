<?php

namespace App\Helpers;

/**
* funções para imprimir um valor no console
* @param obj $str
*/
class console{
    
    /**
     * imprime um valor no console
     * @param string $str
     */
    public static function log($str) {
        $str = str_replace("'", "", $str);
        print_r(" <script>console.log( '" .$str. "' );</script>");   
    }
    
    /**
     * imprime um valor no console
     * @param array $arr
     */
    public static function log_r($arr) {
        $valor = implode("','",$arr);
        print_r(" <script>console.log( '".$valor."' );</script>");
    }

}



