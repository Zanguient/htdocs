<?php

namespace app\Http\Controllers\Estoque\_15060;

use App\Http\Controllers\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Estoque\_15060;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _15060 - Consulta de Estoque
 */
class Controller extends Ctrl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'estoque/_15060';
    public $con     = null;
    public $request = [];
	
	public function index(Request $request)
    {
        $menu_referer = getRefererMenu();
        $log = '';
        if ( $menu_referer ) {
            $log = 'Acessado a partir do menu ' . $menu_referer . '. ';
        }
        
        if ( isset($request->PRODUTO_ID) ) {
            $log .= 'Produto: ' . $request->PRODUTO_ID .' ';
        }
        if ( isset($request->LOCALIZACAO_ID) ) {
            $log .= 'Localização: ' . $request->LOCALIZACAO_ID . ' ';
        }
         
    	$permissaoMenu = _11010::permissaoMenu($this->menu,null,$log);
        
		return view(
            'estoque._15060.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

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
        
    public function getCon() {
        return $this->con;
    }
    
    public function getRequest() {
        return $this->request;
    }
        
    public function setCon() {
        $this->con = new _Conexao;
    }
    
    public function setRequest($request) {
        $this->request = obj_case($request);
    }
}