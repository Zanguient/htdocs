<?php

namespace app\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Estoque\_15100;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _15100 - Abastecer estoque
 */
class _15100Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'estoque/_15100';

    /**
     * Consultar UP
     * 
     * @return array
     */
    public static function ConsultarUP(Request $request) {
        $paran = $request->all();
        $ret   = _15100::ConsultarUP($paran);
        return Response::json($ret);       
    }

    /**
     * Consultar Operador
     * 
     * @return array
     */
    public static function ConsultarOperador(Request $request) {
        $paran = $request->all();
        $ret   = _15100::ConsultarOperador($paran);
        return Response::json($ret);       
    }

    /**
     * Consultar Peça
     * 
     * @return array
     */
    public static function ConsultarPeca(Request $request) {
        $paran = $request->all();
        $ret   = _15100::ConsultarPeca($paran);
        return Response::json($ret);       
    }

    /**
     * Consultar Peça
     * 
     * @return array
     */
    public static function Abastercer(Request $request) {
        $paran = $request->all();
        $ret   = _15100::Abastercer($paran);
        return Response::json($ret);       
    }
	
	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _15100::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'estoque._15100.index', [
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