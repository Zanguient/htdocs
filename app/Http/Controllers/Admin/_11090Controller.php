<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11090;
use App\Models\DTO\Admin\_11010;

/**
 * Controller do objeto _11090 - Teste
 */
class _11090Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11090';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);

        $dados = [];

		return view(
            'admin._11090.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'dados'         => $dados
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