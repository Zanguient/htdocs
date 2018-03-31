<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11002;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _11002 - Usuarios
 */
class _11002Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'Admin/_11002';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11002.index', [
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
            
            $ret = _11002::consultar($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function atualizarMenusUser(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11002::atualizarMenusUser($param, $this->con);

            $this->con->commit();
            
            return Response::json(['OK' => 'OK']);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function resetarSenhaSuper(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11002::resetarSenhaSuper($param, $this->con);

            $this->con->commit();
            
            return Response::json(['OK' => 'OK']);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11002::excluir($param, $this->con);

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
            
            $ret = _11002::incluir($param, $this->con);

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
            
            $ret = _11002::alterar($param, $this->con);

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
            
            $ret = _11002::consultar_itens($param, $this->con);

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
            
            $ret = _11002::excluir_itens($param, $this->con);

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
            
            $ret = _11002::incluir_itens($param, $this->con);

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
            
            $ret = _11002::alterar_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}