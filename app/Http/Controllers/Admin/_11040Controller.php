<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11040;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Log;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto 11040 - Logs do sistema.
 */
class _11040Controller extends Controller {

    /**
     * Código do menu Logs do sistema
     * @var int 
     */
    private $menu = 'admin/_11040';
    
    public function index(Request $request)
    {               
		_11010::permissaoMenu($this->menu);
            
        $log_path_storage = storage_path(). '/logs/';
        $diretorio        = dir($log_path_storage);
        $arquivos         = [];
        

        while($file_name = $diretorio->read()){
            if (  pathinfo($file_name, PATHINFO_EXTENSION) != 'log' ) {
                continue;
            }
   
            array_push($arquivos, (object)[
                'FILE_DIR'  => $log_path_storage,
                'FILE_NAME' => $file_name
            ]);
        }
        
        $arquivos = orderBy($arquivos,'FILE_NAME',SORT_DESC);
        
        $diretorio->close();
                
        return view('admin._11040.index', [
            'arquivos' => $arquivos
        ]);
	}
    
    public function requestFile(Request $request) {

        $file_name        = $request->file_name;
        $log_path_storage = storage_path(). '/logs/';
        $lines            = file($log_path_storage.$file_name);
        $file             = "";
        
        // Percorre o array, mostrando o fonte HTML com numeração de linhas.
        foreach ($lines as $line_num => $line) {
            $file .= $line;
        }
        
        return $file;
    }

    private static function delTree($dir) { 
        if ( is_dir($dir) ) {
            
            $files = array_diff(scandir($dir), array('.','..','index.php')); 
            
            foreach ($files as $file) {            
                if (strtotime("-30 minutes") > filemtime("$dir/$file")) {
                    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
                }
            } 
            return rmdir($dir); 
        }
    }
}
