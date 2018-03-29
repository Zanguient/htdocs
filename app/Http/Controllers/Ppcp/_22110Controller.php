<?php

namespace app\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22110;
use App\Models\DTO\Admin\_11010;

/**
 * Controller do objeto _22110 - Registro de Agrupamento de Pedidos e Reposicoes
 */
class _22110Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22110';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22110.index', [
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