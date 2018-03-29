<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11180;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Helpers\Helpers;

/**
 * Controller do objeto _11180 - Blok
 */
class _11180Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11180';
	
	public function Consultar(Request $request){

        $con = new _Conexao('BLOK');
        $filtro = $request->all();

        $ret = _11180::Consultar($filtro,$con);

        return  Response::json($ret);      
    }

    public function url(Request $request){

        $con = new _Conexao('BLOK');
        $filtro = $request->all();

        $ret = _11180::url($filtro,$con);

        $res = ['DADOS' => Helpers::ObjEncode($ret)];

        return  Response::json($res);      
    }

    public function excluir(Request $request){

        $con = new _Conexao('BLOK');
        $filtro = $request->all();

        _11180::excluir($filtro,$con);

        $con->commit();

        return [];     
    }

    public function gravar(Request $request){

        $con    = new _Conexao('BLOK');
        $filtro = $request->all();

        $ret = _11180::gravar($filtro,$con);

        $con->commit();

        return $ret;     
    }
    

    public function janela(Request $request){

        $con = new _Conexao('BLOK');
        $filtro = $request->all();

        $ret = _11180::janela($filtro,$con);

        $res = ['DADOS' => $ret];

        return  Response::json($res);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11180.index', [
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