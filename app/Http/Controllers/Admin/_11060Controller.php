<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11060;
use App\Models\DTO\Admin\_11010;
use Illuminate\Http\Request;

/**
 * Controller do objeto _11060
 */
class _11060Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11060';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras  = [];
        
		return view(
            'admin._11060.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]);  
    }
    
    public function listar(Request $request)
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras  = _11060::listar($request->all());
        
		return view(
            'admin._11060.include.lista', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]);  
    }
    
    public function create()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11060.create', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);
    }

    public function store(Request $request)
    {    	
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras  = _11060::store($request->all());
        $impressoras  = _11060::listar($request->all());
        
		return view(
            'admin._11060.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]); 
    }
    
    public function show($id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras  = _11060::show($id)[0];
        
    	return view(
            'admin._11060.show', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]); 
    }
    
    public function edit($id)
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras  = _11060::show($id)[0];
        
    	return view(
            'admin._11060.edit', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]); 
    }
    
    public function update(Request $request, $id)
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $impressoras   = _11060::update($request->all(), $id);
        
        $impressoras   = _11060::show($id)[0];
        
    	return view(
            'admin._11060.show', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'impressoras'   => $impressoras
		]); 
    }
    
    public function destroy($id)
    {
        $impressoras  = _11060::destroy($id);
    }
    
    public function excluir(Request $request)
    {          
        $impressoras  = _11060::destroy($request->all());
    }

}