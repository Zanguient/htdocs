<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;

/**
 * Impressão direta
 * @package Opex
 * @category Controller
 */
class DirectPrintController extends Controller {
    public function getTagCache()
    {
        $str = \Auth::user()->USUARIO;
        
        $d = new \DateTime();
        
        $str .= $d->format('YmdTHisu').''.rand(111, 999);

        $tag = base64_encode($str);
        
        log_info('Etiqueta impressa: ' . $str . ' - ' . $tag);
        
        return $tag;
    }
    
    public function salveCache($dados)
    {  

        $dir = env('APP_TEMP', '').'/print/';
        $tag = self::getTagCache();

        //apaga arquivos  antigos
        deleleFilesTree($dir);
        
        $fp = fopen($dir.$tag.".txt", "a");
        $escreve = fwrite($fp, $dados['codigo']);
        fclose($fp);
           
        return $tag;
    }
    
    public function getCache($tag)
    {
        $dados = \Cache::get($tag);
           
        return $dados;
    }
    
    public function postprint(Request $request) {
        if ( $request->ajax() ) {
            
            $dados = $request->all();
            
            $tag = self::salveCache($dados);
            
            return $tag;
            
        }
    }
    
    public function getprint(Request $request) {
        if ( $request->ajax() ) {
            
            $dados = $request->all();
           
            $ret = self::getCache($dados['tag']);
            
            return $ret['codigo'];
           
        }
    }
    
    public function getprint2(Request $request) {
            
            $dados = $request->all();
            
            $ret = self::getCache($dados['tag']);
            
            \App\Helpers\Helpers::log('dados');
            
            return view('opex._25700.getprint',['CODIGO' => $dados['tag']]);
    }
    
}
