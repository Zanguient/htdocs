<?php

namespace App\Http\Controllers\Ppcp;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22032;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class _22032Controller extends Controller
{
    /**
     * Código do menu Gestão de Estações de Trabalho
     * @var int 
     */
    private $menu = 'ppcp/_22032';
    
    public function index(Request $request)
    {        
		$permissaoMenu = _11010::permissaoMenu($this->menu);
       
		return view(
            'ppcp._22032.index', [
            'menu' => $this->menu
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

    public function show(Request $request,$id)
    {
		$permissaoMenu  = _11010::permissaoMenu($this->menu);
        
        if ( strripos($request->url(), 'show') ) {       
            $view = 'ppcp._22032.show.body';
        } else {
            $view = 'ppcp._22032.show';
        }
        
		return view(
            $view, [
		]);
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }
    
    public function search(Request $request)
    {
        //
    }
}
