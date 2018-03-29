<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11140;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _11140 - Painel de Casos
 */
class _11140Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11140';
	
	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _11140::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11140.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public function create()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'admin._11140.create', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
        ]);  
    }

    public function getClientes(Request $request)
    {   
        $filtro = $request->all();
        $ret = _11140::getClientes($filtro);
        
        return $ret;
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

}