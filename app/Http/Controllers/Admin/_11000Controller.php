<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11000;
use App\Models\DTO\Admin\_11010;

/**
 * 11000 - Gerenciar sistema.
 */
class _11000Controller extends Controller {

	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11000';
	
    public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $file_name        = '.env';
        $log_path_storage = base_path() . '/';
        $lines            = file($log_path_storage.$file_name);
        $file             = "";
        
        // Percorre o array, mostrando o fonte HTML com numeração de linhas.
        foreach ($lines as $line_num => $line) {
            $file .= $line;
        }
        
        return view(
            'admin._11000.index', [
            'permissaoMenu' => $permissaoMenu,
            'arquivo'       => $file
        ]);
    }
    
    public function create()
    {
        _11010::permissaoMenu($this->menu,'INCLUIR');
    }

    public function store(Request $request)
    {    	
        _11010::permissaoMenu($this->menu,'INCLUIR','Fixando Dados');
    }

    public function show(Request $request,$id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizando Usuário ' . $id);
        
        return View::make(
            'admin._11010.show' . $body, [
                'permissaoMenu' => $permissaoMenu,
                'id'            => $id,
                'usuario'       => $usuario,
                'class_acoes'   => $class_acoes
            ])->render()
        ;
    }
    
    public function edit($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR');
    }
    
    public function update(Request $request)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Fixando Dados');
    }
    
    public function destroy($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Excluindo Usuário ' . $id);
    }
    
    public function gravarEnv(Request $request) {
        
        $texto = $request->texto;
        $arquivo = base_path() . '/.env';
        
        if (is_writable($arquivo)) {
            
           $manipular = fopen("$arquivo", 'w+');
           
            if (!$manipular) {
                log_erro("Erro<br /><br />Não foi possível abrir o arquivo.");
            } else {
                if (!fwrite($manipular, $texto)) {
                  log_erro("Erro<br /><br />Não foi possível gravar as informações no arquivo.");
                }

                fclose($manipular);
            } // if !$manipular
            
        } else {
          log_erro("O $arquivo não tem permissões de leitura e/ou escrita.");
        } // if is_writable
    }
    
	/**
	 * Retorna as permissões do sistema de acordo com o parâmetro passado.
	 * @param integer $id Id da permissão
	 * @return string
	 */
	public static function controle($id) {
		
		$ret = _11000::permissao($id);
		
		return !empty($ret) ? $ret[0]->VALOR_EXT : '';
	}
   
	
}