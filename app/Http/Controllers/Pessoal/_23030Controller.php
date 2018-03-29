<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23030DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
 */
class _23030Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'pessoal/_23030';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index() {

    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'pessoal._23030.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Consultar níveis.
     *
     * @access public
     * @return json
     */
    public function consultarNivel() {

        $this->con = new _Conexao();

        try {
            
            $ret = _23030DAO::consultarNivel($this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Gravar nível.
     *
     * @access public
     * @param Request $request
     */
    public function gravar(Request $request) {

        $this->con = new _Conexao();

        try {
            
            $param = json_decode(json_encode($request->all()));
            $param->ID = isset($param->ID) ? $param->ID : 0;

            _23030DAO::gravar($param, $this->con);

            $this->con->commit();
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Excluir nível.
     *
     * @access public
     * @param Request $request
     * @return json
     */
    public function excluir(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            _23030DAO::excluir($param, $this->con);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }
    }
}