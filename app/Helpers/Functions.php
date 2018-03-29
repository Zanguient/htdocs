<?php

/**
 * retorna o protocolo do site(http ou https)
 * @return string
 */
function protocol(){
    if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
        return 'https';
    }else{
        return 'http';
    }
}


/**
 * Verifica se há um próximo registro de um array
 * @param array $a
 * @return bool
 */
function has_next(array $array){
    if (is_array($array)) {
        if (next($array) === false) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

/**
 * formata um numero para float
 * @param string $num
 * @return boble
 */
function tofloat($num) {
       $dotPos = strrpos($num, '.');
       $commaPos = strrpos($num, ',');
       $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
           ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

       if (!$sep) {
           return floatval(preg_replace("/[^0-9]/", "", $num));
       } 

       return floatval(
           preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
           preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
       );
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
 * orderBy($dados, 'CCUSTO', SORT_STRING, 'CCONTABIL', SORT_STRING);
 * @return array
 */
function orderBy(&$array)
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
     * Verifica se houve algum valor alimentado
     */
    if ( !isset($data[0]) ) {
        return $data;
    }
    
    /**
     * Verifica se $data é um objeto e converte para array
     */    
    $object = (gettype($data[0]) == 'object') ? true : false;

    /**
     * Converte o objeto para array
     */
    $data = objectToArray($data);

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
    $array  = $object ? (array) arrayToObject($array_pop) : $array_pop;

    /**
     * Retorna a variável de retorno
     */
    return $array;
}   

/**
 * Converte um array para object array
 * @param array $d
 * @return (object)array
 */
function arrayToObject($d) {
    if (is_array($d)) {
        return (object) array_map('arrayToObject', $d);
    }
    else {
        return $d;
    }
}	

function objectToArray($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        return array_map('objectToArray', $d);

    }
    else {
        return $d;
    }
}

/**
 * Converte um array para uma lista separada por vírgula. <br/>Se o parametro $array não for um array o retorno será o próprio parametro.
 * @param array $array
 * @param string $val_def Valor default caso a lista esteja vazia
 * @param string $str Se o array for de string passar o caractere separador
 * @param string $field Se for um array com um campo para passar o nome do campo
 * @return string Lista separada por vírgula
 */
function arrayToList($array, $val_def = '',$str = false,$field = false) {
    $list = '';
    $i = -1;
    $array = objectToArray($array);

    if ( is_array($array) ) {
        foreach ($array as $key => $o) {

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
    } else {
        $list = $array;
    }
    
    return ( $list != '' ) ? $list : $val_def;
}

/** 
 * A recursive array_change_key_case function. 
 * @param array $input 
 * @param integer $case 
 */ 
function array_case($input, $case = CASE_UPPER){ 
    if(!is_array($input)){ 
        trigger_error("Invalid input array '{$input}'",E_USER_NOTICE); exit; 
    } 
    // CASE_UPPER|CASE_LOWER 
    if(null === $case){ 
        $case = CASE_LOWER; 
    } 
    if(!in_array($case, array(CASE_UPPER, CASE_LOWER))){ 
        trigger_error("Case parameter '{$case}' is invalid.", E_USER_NOTICE); exit; 
    } 
    $input = array_change_key_case($input, $case); 
    foreach($input as $key=>$array){ 
        if(is_array($array)){ 
            $input[$key] = array_change_key_case_recursive($array, $case); 
        } 
    } 
    return $input; 
} 

/**
 * Converte um array para objeto e muda o case da chave
 * @param array $array
 * @param integer $case Case: CASE_UPPER, CASE_LOWER. Default: CASE_UPPER
 */
function obj_case($array, $case = CASE_UPPER) {
    if (is_object($array)) {
        $array = get_object_vars($array);
    }
    return (object) array_change_key_case($array,$case);
}

/**
 * Registra log no stack trace
 * @param string $msg
 * @param string $menu Default: null
 */
function log_info($msg,$menu = null) {
    //Verifica se a string passada como menu possui módulo
    if ( $menu != null) {
        $str = explode('/_', $menu); 
        if ( is_numeric( $str[0] ) ) {
            $menu   = $str[0];
            $modulo = '';
        } else {
            $menu   = $str[1];
            $modulo = $str[0];
        }
        $menu = Illuminate\Support\Facades\Lang::get($modulo . '/_' . $menu . '.titulo') . ' | ';
    } else {
        $menu = '';
    }
    
    $user = str_pad( (Auth::check() ? Auth::user()->USUARIO : ''), 10) . ' | ';
    
    Illuminate\Support\Facades\Log::info($user . str_pad(\Request::getClientIp(),13) . ' | ' . $menu . print_r($msg, true));
}

/**
 * Função quer gera um erro tratado
 * @param type $msg Mensagem de erro
 * @param int $code Código do erro. Default: 99998
 * @throws Exception
 */
function log_erro($msg, $code = 99998) {
    throw new \Exception($msg, $code);
}

/**
 * Exibe nos logs uma mensagem ou array
 * @param type $msg
 * @throws Exception
 */
function print_l($msg) {
    throw new \Exception(print_r($msg, true), 99998);
}

/**
 * Remove acento
 * @param type $string
 * @param type $slug
 * @param type $strTo
 * @param type $acentos
 * @return type
 */
function str_remove_acento($string = false, $slug = false, $strTo = false, $acentos = false){

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
 * Completa com caractere à esquerda
 * @param string $string String que será preenchida a esquerda
 * @param int $tamanho
 * @param string $caractere Caractere a ser utilizado para o preenchimento - Default: ' ' (espaço em branco)
 */
function lpad($string, $tamanho,$caractere = ' ') {
    return str_pad($string, $tamanho, $caractere, STR_PAD_LEFT);
}

/**
 * Completa com caractere à direita
 * @param string $string String que será preenchida a direita
 * @param int $tamanho
 * @param string $caractere Caractere a ser utilizado para o preenchimento - Default: ' ' (espaço em branco)
 */
function rpad($string, $tamanho,$caractere = ' ') {
    return str_pad($string, $tamanho, $caractere, STR_PAD_RIGHT);
}


global $time;

/* Get current time */
function getTime(){
   return microtime(TRUE);
}

/* Calculate start time */
function startTime(){
   global $time;
   $time = getTime();
}

/*
 * Calculate end time of the script,
 * execution time and returns results
 */
function endTime(){
   global $time;      
   $finalTime = getTime();
   $execTime = $finalTime - $time;
   return number_format($execTime, 6) . ' s';
}

/**
 * Recupera um arquivo do banco de arquivos pelo id do arquivo<br/>
 * Obs: Os arquivos serão apagados logo após uma hora de sua criação
 * @param \App\Models\Conexao\_Conexao $con_file Instancia de conexão do banco de arquivos. Se nulo será criada uma instancia dentro do método e comidata logo após o armazenamento do arquivo em disco.
 * @param int $id Id do arquivo
 * @param string $name Nome do arquivo. Se nulo, retorna o nome salvo no banco
 * @param string $dir caminho dentro da pasta "public\assets\temp"
 * @return object URL: caminho para acesso via site<br/>PATH: caminho para acesso via sistema<br/>PATH_TEMP: caminho dentro da pasta temp onde o arquivo será salvo
 */
function getFile($con_file,$id,$name = null,$dir = null) {
    
    $con = $con_file;//new App\Models\Conexao\_Conexao('FILES');
    
    $sql = "
        SELECT FIRST 1 A.*
          FROM TBARQUIVO A
         WHERE A.ID = :ID        
    ";
    
    $args = [
        'ID' => $id
    ];
    
    $qry = $con->query($sql,$args);
    
    $ret = null;
    
    if ( isset($qry[0]) ) {
        
        $file_qry = $qry[0];
        $file_blob = $file_qry->CONTEUDO;

        $name = $name == null ? $file_qry->ARQUIVO : $name;
        
        if ( $dir == null ) {
            
            $dir_temp = env('APP_TEMP', '');
            
            $dir_def = 'any/';
            
            $hash_dir = $file_qry->ARQUIVO . '-' . $file_qry->ID . '-' . md5($file_qry->ID);
            
            $full_dir = $dir_def . $hash_dir;
            
            $dir = $dir_temp . $full_dir;
            
            deleleFilesTree($dir_def,'-60 minutes');
        }   
        
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);          
        }        
        
        // Gravar no diretório temporário.
        $full_name = $dir . '/' . $name;
        
        if ( !file_exists($full_name) ) {
            $file = fopen($full_name, "a+");
            fwrite($file, $file_blob);
            fclose($file);
        }
        
        $ret = (object) [
            'URL'       => '/assets/temp/' . (isset($full_dir) ? $full_dir : $dir) . '/' . $name,
            'PATH'      => $full_name,
            'PATH_TEMP' => (isset($full_dir) ? $full_dir : $dir) . '/' . $name
        ];
    }
    
//    $con->rollback();
    
    return $ret;    
}

function deleleFilesTree($dir,$time = null) { 
    
    if ( is_dir($dir) ) {
        
        $time = $time == null ? "-60 minutes" : $time;
        
        $files = array_diff(scandir($dir), array('.','..','index.php')); 

        foreach ($files as $file) {        
            
            $current_file = "$dir/$file";
            
            if (strtotime($time) > filemtime($current_file) || $time == 0) {
                if ( is_dir($current_file) ) {
                    deleleFilesTree($current_file,$time,true);
                    if ( dir_is_empty($current_file) ) {
                        rmdir($current_file);                     
                    }
                } else {
                    unlink($current_file);
                }
            }
        } 
    }
}

function deleleFilesTree2($dir) { 
    
    if ( is_dir($dir) ) {
        $files = array_diff(scandir($dir), array('.','..','index.php')); 

        foreach ($files as $file) {            
            if (strtotime("-3600 minutes") > filemtime("$dir/$file")) {
                (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
            }
        } 
        //return rmdir($dir); 
    }
}


function dir_is_empty($dir) {
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}

/**
 * Converter TColor para RGB.
 * @param string $tcolor
 * @return string
 */
function tcolorToRgb($tcolor) {
	
	if( empty($tcolor) ) {
		return '';
	}
	
	$tcolor = str_replace("$", "", $tcolor);

	$r = $tcolor & 0xFF;
	$g = ($tcolor >> 8) & 0xFF;
	$b = ($tcolor >> 16) & 0xFF;

	return 'rgb('. implode(",", array($r, $g, $b)) .')';
}

/**
 * Converter cor em Hexadecimal para RGB.
 * @param string $hex
 * @return string
 */
function hexTorgb($hex) {
		
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
	   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	   $r = hexdec(substr($hex,0,2));
	   $g = hexdec(substr($hex,2,2));
	   $b = hexdec(substr($hex,4,2));
	}
	
	return 'rgb('. implode(",", array($r, $g, $b)) .')';
}

/**
 * Função para validar campos no padrão laravel Validator()
 * @param array $fields Campos a serem validados<br/>Ex.: $request->all()
 * @param array $rules Condições de validação padrão do laravel Validator()<br/>Ex.: 'nome' => 'required|min:10'<br>
 * Obs:Se passado array para o campo, a posicao 0 do array será a descrição do campo e a posição 1 do array será a condição<br/>Ex.: 'estabelecimento_id' => ['Estabelecimento' ,'required|integer']
 * @param boolean $abort Default: <b>false</b>
 * <ul>
 *  <li><b>true</b>: Cancela a operação caso ocorra algum erro de validação.</li>
 *  <li><b>false</b>: Retorna uma lista com os erros de validação.</li>
 * </ul>
 * @return string
 * @throws Exception
 */
function validator($fields, $rules, $abort = false)
{
    $standards  = [];
    $nice_names = [];
    
    foreach ( $rules as $key => $value ) {
        $standards[$key]  = is_array($value) ? $value[1] : $value;
        $nice_names[$key] = is_array($value) ? "\n<b>\"".$value[0]."\"</b>" : $key;
    }
        
    $validator = \Validator::make((array) $fields, $standards);
 
    if ( !empty($nice_names) ) {
        $validator->setAttributeNames($nice_names);
    }
    
    $erros = '';
    foreach ($validator->errors()->all() as $error) {
        $erros =  $erros.'<li>' . $error . '</li>';
    }
    
    if ($abort) {
        if ($erros) {
            log_erro($erros,99990);
        }    
    } else {
        return strip_tags($erros);
    }
}

function randString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function userControl($cod_controle,$abort = null) {
    return App\Models\DTO\Admin\_11010::controle($cod_controle,$abort);
}

function systemControl($id) {
    return App\Models\DTO\Admin\_11000::controle($id);
}

function fnParametro($parametro,$id = null) {
    
    $con = new App\Models\Conexao\_Conexao;
    
    $sql = "SELECT FIRST 1 FN_PARAMETRO('" . $parametro . "'," . ($id == null ? 'NULL' : $id) . ") PARAMETRO FROM RDB\$DATABASE";
    
    $ret = $con->query($sql)[0]->PARAMETRO;
    
    $con->rollback();
    
    return $ret;
}

/**
 * • Verifica e retorna as permissões de acesso do usuário<br/>
 * • Registra o log de acesso 
 * @param int $menu_id Código do menu a ser acessado
 * @param string $permissao INCLUIR | ALTERAR | EXCLUIR | IMPRIMIR | Default NULL = Permissão para consulta | 0 = Não registra log 
 * @param string $msg Mensagem para o registro no log Ex.: Fixando Dados | Default NULL
 * @param bool $abort Abortar a operação. Default: true
 * @return _11010Controller@permissaoMenu
 */
function userMenu($menu_id, $permissao = null, $msg = null, $abort = true) {
    return App\Models\DTO\Admin\_11010::permissaoMenu($menu_id, $permissao, $msg, $abort);
}

/**
 * Seta um valor default para o primeiro parametro (por referência)
 * @param mixed $value Valor passado por referencia que será verificado se está setado/vazio/igual ao terceiro parametro
 * @param mixed $def_value Valor que será aplicado ao primeiro parametro se atendeer as condições
 * @param type $def_if_equals Valor que será comparado com o primeiro parametro se estiver setado. Se os dois forem iguais, o valor default será aplicado
 */
function setDefValue(&$value, $def_value = '', $def_if_equals = null) {

    if ( isset($value) ) {
        
        if ($value instanceof stdClass ) {
            if ( $value == $def_if_equals || empty($value)  ) {
                $value = $def_value;
            }
        } else        
        if ( $value != 0 && ($value == $def_if_equals || empty($value) ) ) {
            $value = $def_value;
        }
    } else {
        $value = $def_value;
    }
    
    return $value;
}

function getRefererMenu() {

    try {
        $referer = Request::header('referer');

        $referer_exp = explode("?", $referer);

        $base_url = $referer_exp[0];

        $base_url_exp = explode("/", $base_url);

        $referer_menu = (int) str_replace("_", "", $base_url_exp[3]);

    } catch (Exception $e) {

        $referer_menu = null;
    }   
    
    return $referer_menu;
}