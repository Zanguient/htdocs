<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11001;
use App\Models\DTO\Admin\_11010;

/**
 * Controller do objeto _11001 - Agendador
 */
class _11001Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11001';

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {    	
        //
    }
    
    public function show($id)
    {
    	//
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request, $id)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }
    
    public function index(Request $request)
    {               
		_11010::permissaoMenu($this->menu);
            
        $schedule         = app_path(). '/Console/';
        $diretorio        = dir($schedule);
        
        $comands          = app_path(). '/Console/Commands/';
        $diretorioc       = dir($comands);
        
        $logs             = app_path(). '/Console/Commands/Saidas/';
        $diretoriol       = dir($logs);
        
        $dia             = app_path(). '/Console/Commands/Logs/';
        $diretoriod       = dir($dia);
        
        $arquivos         = [];
        
        array_push($arquivos, (object)[
            'FILE_DIR'  => 'Agendador',
            'FILE_NAME' => 'Agendador',
            'TIPO'      => 0
        ]);
        
        while($file_name = $diretorio->read()){
            
            if (  pathinfo($file_name, PATHINFO_EXTENSION) != 'php' ) {
                continue;
            }
   
            array_push($arquivos, (object)[
                'FILE_DIR'  => $schedule,
                'FILE_NAME' => $file_name,
                'TIPO'      => 1
            ]);
        }
        
        array_push($arquivos, (object)[
            'FILE_DIR'  => 'Comandos',
            'FILE_NAME' => 'Comandos',
            'TIPO'      => 0
        ]);
        
        while($file_name = $diretorioc->read()){
            
            if (  pathinfo($file_name, PATHINFO_EXTENSION) != 'php' ) {
                continue;
            }
   
            array_push($arquivos, (object)[
                'FILE_DIR'  => $comands,
                'FILE_NAME' => $file_name,
                'TIPO'      => 2
            ]);
        }
        
        array_push($arquivos, (object)[
            'FILE_DIR'  => 'Logs',
            'FILE_NAME' => 'Logs',
            'TIPO'      => 0
        ]);
        
        while($file_name = $diretoriol->read()){
            
            if (  pathinfo($file_name, PATHINFO_EXTENSION) != 'txt' ) {
                continue;
            }
   
            array_push($arquivos, (object)[
                'FILE_DIR'  => $logs,
                'FILE_NAME' => $file_name,
                'TIPO'      => 3
            ]);
        }
        
        array_push($arquivos, (object)[
            'FILE_DIR'  => 'Log Dia',
            'FILE_NAME' => 'Log Dia',
            'TIPO'      => 0
        ]);
        
        while($file_name = $diretoriod->read()){
            
            $data = date("d-m-Y");
            $d0 = date('Ymd', strtotime($data. ' - 0 day'));
            $d1 = date('Ymd', strtotime($data. ' - 1 day'));
            $d2 = date('Ymd', strtotime($data. ' - 2 day'));
            $d3 = date('Ymd', strtotime($data. ' - 3 day'));
            $d4 = date('Ymd', strtotime($data. ' - 4 day'));
            
            if ( strpos($file_name,$d0) > 0) {
                array_push($arquivos, (object)[
                    'FILE_DIR'  => $dia,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 4
                ]);
            }
            
            if ( strpos($file_name,$d1) > 0) {
                array_push($arquivos, (object)[
                    'FILE_DIR'  => $dia,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 4
                ]);
            }
            
            if ( strpos($file_name,$d2) > 0) {
                array_push($arquivos, (object)[
                    'FILE_DIR'  => $dia,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 4
                ]);
            }
            
            if ( strpos($file_name,$d3) > 0) {
                array_push($arquivos, (object)[
                    'FILE_DIR'  => $dia,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 4
                ]);
            }
            
            if ( strpos($file_name,$d4) > 0) {
                array_push($arquivos, (object)[
                    'FILE_DIR'  => $dia,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 4
                ]);
            }
        }
        
        $diretorio->close();
                
        return view('admin._11001.index', [
            'arquivos' => $arquivos
        ]);
	}
    
    public function requestFile(Request $request) {

        $file_name        = $request->file_name;
        $file_dir         = $request->file_dir;
        $schedule         = $file_dir;
        $lines            = file($schedule.$file_name);
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