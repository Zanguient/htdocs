<?php

namespace app\Http\Controllers\Custo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Custo\_31080;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _31080 - Cadastro de Mercados
 */
class _31080Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'custo/_31080';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'custo._31080.index', [
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

    public function consultarFamilia(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::consultarFamilia($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultarConta(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::consultarConta($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultar(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::consultar($param, $this->con);

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
            
            $ret = _31080::excluir($param, $this->con);

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
            
            $ret = _31080::incluir($param, $this->con);

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
            
            $ret = _31080::alterar($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultar_itens(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::consultar_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir_itens(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::excluir_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function incluir_itens(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::incluir_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function alterar_itens(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::alterar_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
//////



    public function consultar_itens_conta(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::consultar_itens_conta($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir_itens_conta(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::excluir_itens_conta($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function incluir_itens_conta(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::incluir_itens_conta($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function alterar_itens_conta(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _31080::alterar_itens_conta($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}