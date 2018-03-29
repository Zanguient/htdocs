<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11110;
use App\Models\DTO\Admin\_11010;

/**
 * Controller do objeto _11110 - Gerenciar Qlik Sense
 */
class _11110Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11110';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11110.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public function listUser()
    {
        return _11110::listUser();
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