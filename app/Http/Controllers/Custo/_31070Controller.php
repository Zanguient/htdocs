<?php

namespace app\Http\Controllers\Custo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Custo\_31070;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _31070 - Cadastro de Incentivos
 */
class _31070Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'custo/_31070';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'custo._31070.index', [
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

    public function consultar(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            $param->ID = isset($param->ID) ? $param->ID : 0;
            
            $ret = _31070::consultar($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31070::excluir($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function incluir(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31070::incluir($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function alterar(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31070::alterar($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}