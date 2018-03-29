<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11210;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _11210 - Cadastro de Perfil de Usuario
 */
class _11210Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'Admin/_11210';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11210.index', [
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

    public function ConsultaUsuario(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::ConsultaUsuario($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consulta_menu(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::consulta_menu($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consulta_grupo(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::consulta_grupo($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultar_perfil_menu(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::consultar_perfil_menu($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultar_perfil_grupo(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::consultar_perfil_grupo($param, $this->con);

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
            
            $ret = _11210::consultar($param, $this->con);

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
            
            $ret = _11210::excluir($param, $this->con);

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
            
            $ret = _11210::incluir($param, $this->con);

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
            
            $ret = _11210::alterar($param, $this->con);

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
            
            $ret = _11210::consultar_itens($param, $this->con);

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
            
            $ret = _11210::excluir_itens($param, $this->con);

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
            
            $ret = _11210::incluir_itens($param, $this->con);

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
            
            $ret = _11210::alterar_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function incluir_grupo(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::incluir_grupo($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function incluir_menu(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::incluir_menu($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir_grupo(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::excluir_grupo($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function excluir_menu(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            
            $ret = _11210::excluir_menu($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}