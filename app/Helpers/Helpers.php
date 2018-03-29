<?php

namespace App\Helpers;

use Validator;
use Exception;

class Helpers{
    
    /**
     * Retorna uma string codificada
     * @param string $str
     * @return string
     */
    public static function encrypt($str) {
        
        //Chaves
        $prfx = array(
            'AFVaIF', 'Vc2ddS', 'ZEcad1', 'aOhlVq', 'QhFmVJ', 'VTaU5U',
            'QRVuiZ', 'lZnhnU', 'Hi10X1', 'Gb9nUV', 'TnZGZz', 'ZGiZZG',
            'dodJe5', 'dcl0NT', 'Y0NeZy', 'dGnlNj', 'ac5lOD', 'BqbWdo',
            'bFp0Ma', 'QMFjNy', 'ZmFMdm', 'dkaIF1', 'hrMakD', 'aVFsbG',
            'bsm0Mz', 'opqRWv', 'QVlRWP', 'PWRdyQ', 'PQVRsa', 'RTWPAG',
            'pdtGSV', 'PLETFG', 'SQWEGA', 'PETUFJ', 'THRPAH', 'PLFCVM'
        );

        for($i=0; $i<3; $i++) {
            $str = $prfx[array_rand($prfx)].strrev(base64_encode($str));
        }

        //Troca de identificação
        $str = strtr($str,
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123567894=",
            "pQzqxPtfGdonMweL3Z5vHS46_OliRcuKJ1Uk2a8rgBbj79yDVIWshNFCm0TXEAY"
        );   
        
        //Divisão da string em grupos de 6
       $result = str_split($str, 6);
       
       //Sequencia de 4 posições
       $prfx = array('1230','3120','0132','1023','0321','0123','0321');
       
       //Seleciona uma das sequencias
       $chave = array_rand($prfx);
       
       if($chave == '0'){$chave = 1;}
            
       $pos1 = substr($prfx[$chave],0,1);
       $pos2 = substr($prfx[$chave],1,1);
       $pos3 = substr($prfx[$chave],2,1);
       $pos4 = substr($prfx[$chave],3,1);
            
       $str = $chave.$result[$pos1].$result[$pos2].$result[$pos3].$result[$pos4];
       
       for($i=4; $i<count($result); $i++) {
         $str = $str.$result[$i]; 
       }
       
       return $str;        
    }
    
    /**
     * Retorna uma string descodificada
     * @param string $str
     * @return string
     */
    public static function decrypt($str) {

       $result = str_split(substr($str,1,-1).substr($str,-1), 6);
       $temp = $str;

       $prfx = array('1230','3120','0132','1023','0321','0123','0321');
       $chave = substr($temp,0,1);
            
       $pos1 = substr($prfx[$chave],0,1);
       $pos2 = substr($prfx[$chave],1,1);
       $pos3 = substr($prfx[$chave],2,1);
       $pos4 = substr($prfx[$chave],3,1);
            
       $str = $result[$pos1].$result[$pos2].$result[$pos3].$result[$pos4];
       
        for($i=4; $i<count($result); $i++) {
            $str = $str.$result[$i]; 
        }
       
        $str = strtr($str,
            "pQzqxPtfGdonMweL3Z5vHS46_OliRcuKJ1Uk2a8rgBbj79yDVIWshNFCm0TXEAY",
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123567894="
        );

        for($i=0; $i<3; $i++) {
          $str = base64_decode(strrev(substr($str,6)));
        }

        return str_replace('&', '/',$str);       
    }

    public static function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
        }

        if (is_array($d)) {
        	return array_map(array('self', 'objectToArray'), $d);
            
        }
        else {
        	return $d;
        }
	}
    
    public static function arrayToObject($d) {
        if (is_array($d)) {
            return (object) array_map(array('self', 'arrayToObject'), $d);
        }
        else {
            return $d;
        }
    }	
    
    public static function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                if ($item != null) $item = strtoupper(utf8_encode($item));
            }else{
                if ($item != null) $item = strtoupper($item);
			}
        });
        return $array;
    }
	
	public static function ObjEncode($obj)
	{
		// return (array)$obj;
		return (array) Helpers::arrayToObject(Helpers::utf8_converter(Helpers::objectToArray($obj)));
	}

	/**
	 * Função para tratamento de string
	 * @param string $string | texto alfanumérico
	 * @param string $slug | remove o que não for letra ou número
	 * @param string $strTo | upper ou lower
	 * @param bool $acentos | false = sem acento | true = com acento
	 * @return string
	 */
    public static function removeAcento($string = false, $slug = false, $strTo = false, $acentos = false){

    	if ( !$acentos ) {
        	$string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $string);
    	}
    	
        if ( $strTo = 'upper'){
            $string = strtoupper($string);
        } elseif ( $strTo = 'lower' ) {
            $string = strtolower($string);
        }

        // Slug?
        if ($slug) {
            // Troca tudo que não for letra ou número por um caractere ($slug)
            $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
            // Tira os caracteres ($slug) repetidos
            $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
            $string = trim($string, $slug);
        }
        return $string;
    }

    /**
     * Formata número para o padrão do PHP.
     * Ex.: 1.000,00 -> 1000.00
     *
     * @param $num
     * @return mixed
     */
    public static function formataNumPadrao($num) {
        return str_replace(',', '.', str_replace('.', '', $num));
    }
    
    public static function objCount($obj) {
        return array_sum(array_map("count", (array)$obj));
    }
	
	/**
	 * Aplicar máscara. Serve para qualquer tipo, basta passar o formato desejado. <br />
	 * Ex.: echo mask($data,'##/##/####');
	 * 
	 * @param type $val
	 * @param type $mask
	 * @return type
	 */
	public static function mask($val, $mask)
	{
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++) {
			if($mask[$i] == '#') {
				if(isset($val[$k])) {
					$maskared .= $val[$k++];
				}
			}
			else {
				if(isset($mask[$i])) {
					$maskared .= $mask[$i];
				}
			}
		}
		return $maskared;
	}
	
	/**
	 * Máscara para fone.
	 * 
	 * @param string $val
	 * @return string
	 */
	public static function maskFone($val) {
		
		$masc = '';
		
		if ( strlen($val) === 10 ) {
			$masc = self::mask($val, '(##) ####-####');
		}
		else {
			$masc = self::mask($val, '(##) #####-####');
		}
		
		return $masc;
	}
	
	/**
	 * Máscara para CEP.
	 * 
	 * @param string $val
	 * @return string
	 */
	public static function maskCep($val) {
		return self::mask($val, '##.###-###');
	}
	
	/**
	 * Máscara para CNPJ.
	 * 
	 * @param string $val
	 * @return string
	 */
	public static function maskCnpj($val) {
		return self::mask($val, '##.###.###/####-##');
	}
    
    /**
     * Converte um array para uma lista separada por vírgula. <br/>Se o parametro $array não for um array o retorno será o próprio parametro.
     * @param array $array
     * @param string $val_def Valor default caso a lista esteja vazia
     * @param string $str Se o array for de string passar o caractere separador
     * @param string $field Se for um array com um campo para passar o nome do campo
     * @return string Lista separada por vírgula
     */
    public static function arrayToList($array, $val_def = '',$str = false,$field = false) {
        $list = '';
        $i = -1;
        $array = self::objectToArray($array);
        
        if ( is_array($array) ) {
            foreach ($array as $key => $o) {
                if ( $o ) {
                    $i++;
                    
                    //Verifica se existe um campo com nome 
                    if ($field) {
                        $o = $o[$field];
                    }
                    
                    //Verifica se é uma string e pega o caractere
                    if ( $str ) {
                        $item = $str . $o . $str;
                    } else {
                        $item = $o;
                    }
                    
                    $list = ( $i == 0 ) ? $item : $list  . ', ' . $item;
                }
            }   
        } else {
            $list = $array;
        }
        
        return $list ? $list : $val_def;
    }
    
    /**
     * imprime um valor no console
     * @param string $str
     */
    public static function log($str) {
        print_r(" <script>console.log( '" .$str. "' );</script>");   
    }
    
    /**
     * laso em um array recursivo
     * @param array $ar
     */
    function multiarray_keys($ar) { 

        foreach($ar as $k => $v) { 
            $keys[] = $k; 
            if (is_array($ar[$k])){ 
                $keys = array_merge($keys, multiarray_keys($ar[$k]));
                echo "console.log( '-->[".$keys."]' );";
            }else{
                
            }   
            
        } 
        return $keys; 
    } 
    
    /**
     * imprime um valor no console
     * @param array $arr
     */
    public static function log_r($arr) {
        
        $a = $arr;

        $c = 0;
        $t = count($a);
         echo " <script>";
        foreach ($a as $key => $array) {
            echo "console.log( '-->[".$key."]' );";
            foreach($array as $key => $value){
                $b = $key.': '.$value;
                echo "console.log( '     ".$b."' );";
                $c++;
            }
        }
        echo " </script>";

    }
    
    private static function log_s($arr,$tab) {
        $ret = '';
        $tab = $tab+1;
        $addtab = '';
        $addarr = '';
        
        for($i = 0; $i <= $tab; $i++ ){
            $addtab = $addtab.'    '; 
        }
        
        for($i = 0; $i < $tab; $i++ ){
            $addarr = $addarr.'    '; 
        }
        
        foreach ($arr as $key => $array) {
            $ret .= "console.log( '".$addarr."-->[".$key."]' );";
            foreach($array as $key => $value){

                if(typeOf($value) != 'array'){
                    $b = $key.': '.$value;
                    $ret .= "console.log( '".$addtab.$b."' );";
                }else{
                                        
                    $ret .=  self::log_s($value,$tab+1);

                }
            }
        }
        
        return $ret;
        
    }
    
    private static function log_t($arr,$tab,$sob,$corpo) {
        $ret = '';
        $tab = $tab+1;
        $addtab = '';
        $addarr = '';
        if($sob == 1){$x = '║';}else{$x = '';}
        
        $addtab = $corpo.$x.'  ';
        
        $sobs = 0;
        $cont = 0;
        $c = 1;
        $cont = count($arr);
        
        foreach ($arr as $key => $v) {
            if(is_array($v)){
                if($sob == 1){$x = '╠'; $sobs=1;}else{$x = '╚';}
                
                if($cont > $c){
                    $sobs = 1;
                }
                $ret .= "console.log( '".$addtab.$x.'➧'.$key."' );";
                $ret .=  self::log_t($v,$tab+1,$sobs,$addtab);
            }else{
                $b = $key.': '.$v;
                
                if($cont > $c){
                    $ret .= "console.log( '".$addtab.'╠➧'.$b."' );";
                }else{
                    $ret .= "console.log( '".$addtab.'╚➧'.$b."' );";
                }
            }
            
            $c++;
        }
            
        $cont++;
       
        
        return $ret;
        
    }
    
    /**
     * imprime um array no console
     * @param array $arr
     */
    public static function log_rr($arr) {
        
        echo " <script>";
        echo "console.log( '' );";
        echo "console.log( 'Valores do array' );";

        $a = $arr;
        echo self::log_t($a,0,0,'  ');
        
        echo " </script>";
    
    }
    
    /**
     * imprime um objeto (retorno query) no console
     * @param array $arr
     */
    public static function log_ro($arr) {
        
        echo " <script>";
        echo "console.log( '' );";
        echo "console.log( 'Valores do array' );";

        $a = $arr;
        echo self::log_s($a,0,0,'  ');
        
        echo " </script>";
    }
    
    /**
     * Função para validar campos no padrão laravel Validator()
     * @param array $array Campos a serem validados<br/>Ex.: $request->all()
     * @param array $conditions Condições de validação padrão do laravel Validator()<br/>Ex.: 'nome' => 'required|min:10'
     * @param boolean $abort
     * <ul>
     *  <li><b>true</b>: Cancela a operação caso ocorra algum erro de validação.</li>
     *  <li><b>false</b>: Retorna uma lista com os erros de validação.</li>
     * </ul>
     * @return string
     * @throws Exception
     */
    public static function validator($array = [], $conditions = [], $abort = true) {

        $validator = Validator::make($array, $conditions);
        
        $erros = '';
        foreach ($validator->errors()->all() as $error) {
            $erros =  $erros.'<li>' . $error . '</li>';
        }
        if ($abort) {
            if ($erros) {
                throw new Exception($erros, 99998);
            }    
        } else {
            return $erros;
        }
    }
    
    /**
     * Ordena um array
     * @param array $array array que deseja ordenar
     * @param array $campos Chaves pela qual deseja ordenar ex:( ['user|asc','cod|desc','setor'] )
     * @return array
     * @throws Exception
     */
    public static function orderBy2($array,$campos) {
        
        /**
         * Verifica se o array é um objeto
         */
        $object = (gettype($array[0]) == 'object') ? true : false;

        /**
         * Converte o objeto para array
         */
        $array = self::objectToArray($array);
        
        /**
         * Inverte os campos de ordenação
         */
        $inver = array_reverse($campos);
        
        /**
         * Loop nos campos de ordenação invertidos
         */
        foreach ($inver as $campo) {
            
            /**
             * Cria um array com as condições de ordenação
             */
            $cond = explode('|', $campo);
            
            
            foreach ($array as $key => $row) {
                $mid[$key]  = $row[$cond[0]];
            }
            
            if(count($cond) > 1){
                $d = $cond[1];
                
                if($d == 'desc'){
                    array_multisort($mid, SORT_DESC, $array);
                }else{
                    if($d == 'asc'){
                        array_multisort($mid, SORT_ASC, $array);
                    }
                }
            }else{
                array_multisort($mid, SORT_ASC, $array);
            }
 
        }
        $array = $object ? (array) self::arrayToObject($array) : $array;
        return $array;    
    }
    
    /**
     * Ordena um array
     * @param args Recebe como parametro todos os parametros da função array_multisort<br/><br/>
     * Opções de ordenação:
     * <ul>
     * <li>SORT_ASC</li>
     * <li>SORT_DESC</li>
     * <li>SORT_REGULAR</li>
     * <li>SORT_NUMERIC</li>
     * <li>SORT_STRING</li>
     * </ul><br/>
     * Exemplo de uso:<br/>
     * $dados = Helpers::orderBy($dados, 'CCUSTO', SORT_STRING, 'CCONTABIL', SORT_STRING);
     * @return array
     */
    public static function orderBy()
    {
        /**
         * Resgata todos os parametros da função
         */
        $args = func_get_args();

        /**
         * Remove o array dos argumentos da função e armazena na variável $data
         */
        $data = array_shift($args);

        /**
         * Verifica se $data é um objeto e converte para array
         */
        $object = (gettype($data[0]) == 'object') ? true : false;
        
        /**
         * Converte o objeto para array
         */
        $data = self::objectToArray($data);
        
        /**
         * Cria um array com os campos a serem ordenados e a condição de ordenação e armazena na variável $args
         */
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;                
            }
        }
        
        /**
         * Insere o array nos argumetos
         */
        $args[] = &$data;  
        
        /**
         * Chama a função array_multisort e aplica os argumentos da variável $args
         */
        call_user_func_array('array_multisort', $args);

        /**
         * Retira o array ordenado de $args vindo do array_multisort
         */
        $array_pop = array_pop($args);
        
        /**
         * Verifica se a variavel $object foi setada como true e converte o array para objeto
         */
        $return  = $object ? (array) self::arrayToObject($array_pop) : $array_pop;
        
        /**
         * Retorna a variável de retorno
         */
        return $return;
    }    
}



