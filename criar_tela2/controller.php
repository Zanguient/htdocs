<?php

namespace app\Http\Controllers\#Grupos#;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\#Grupos#\#TelaNO#;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto #TelaNO# - #Titulo#
 */
class #TelaNO#Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = '#Grupos#/#TelaNO#';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            '#SGrupos#.#TelaNO#.index', [
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
            
            $ret = #TelaNO#::consultar($param, $this->con);

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
            
            $ret = #TelaNO#::excluir($param, $this->con);

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
            
            $ret = #TelaNO#::incluir($param, $this->con);

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
            
            $ret = #TelaNO#::alterar($param, $this->con);

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
            
            $ret = #TelaNO#::consultar_itens($param, $this->con);

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
            
            $ret = #TelaNO#::excluir_itens($param, $this->con);

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
            
            $ret = #TelaNO#::incluir_itens($param, $this->con);

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
            
            $ret = #TelaNO#::alterar_itens($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}