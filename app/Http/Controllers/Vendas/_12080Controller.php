<?php

namespace app\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Vendas\_12080;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _12080 - REGISTRO DE CASOS
 */
class _12080Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12080';
	
	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12080::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'vendas._12080.index', [
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

}