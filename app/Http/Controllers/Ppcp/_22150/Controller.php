<?php

namespace app\Http\Controllers\Ppcp\_22150;

use App\Http\Controllers\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22150;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _22150 - Consulta de Ppcp
 */
class Controller extends Ctrl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22150';
    public $con     = null;
    public $request = [];
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22150.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public function correios()
    {
        log_info('Consultando Objeto nos Correios');

		return view(
            'ppcp._22150.correios'
		);  
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