<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11080;
use App\Models\DTO\Admin\_11010;

/**
 * Controller do objeto _11080 - Criar Relatorio
 */
class _11080Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11080';
	
	public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);

        return view(
            'admin._11080.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
        ]);  
    }

    public function Consultar()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $ret = _11080::listarRelatorios('');

        return $ret; 
    }

    public function getRelatorios(Request $request)
    {

        $filtro = $request->all();

        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $ret = _11080::listarRelatorios($filtro['filtro']);

        return view('admin._11080.include.lista', ['dados' => $ret]);  
    }


    public function dados()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $ret = _11080::listarRelatorios('');

        return json_encode($ret);
    }

    public function create()
    {   
        $permissaoMenu = _11010::permissaoMenu($this->menu);

    	return view(
            'admin._11080.create', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
        ]);
        
    }

    public function store(Request $request)
    {    	
        //
    }
    
    public function show(Request $request, $id)
    {

    	$permissaoMenu = _11010::permissaoMenu($this->menu);

        $ret = _11080::show($id);

        return view(
            'admin._11080.show', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'dados'         => $ret,
            'id'            => $id
        ]);     
    }
    
    public function edit($id)
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);

        $ret = _11080::show($id);

        return view(
            'admin._11080.edit', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'dados'         => $ret,
            'id'            => $id
        ]);
    }
    
    public function update(Request $request, $id)
    {
    	//
    }
    
    public function destroy($id)
    {
        //
    }

    public function getRetornoSql(Request $request)
    {

        $param = $request->all();
        $dados = _11080::getRetornoSql($param);

        return $dados;
    }

    public function getRetorno(Request $request)
    {

        $param = $request->all();
        $dados = _11080::getRetorno($param);

        return $dados;
    }

    public function Gravar(Request $request)
    {

        $param = $request->all();

        $dados = _11080::Gravar($param);

        return $dados;
    }

    public function Excluir(Request $request)
    {   
        $param = $request->all();
        $dados = _11080::Excluir($param);
        return $param;
    }
 
}