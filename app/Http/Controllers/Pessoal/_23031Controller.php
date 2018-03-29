<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23031DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23031 - Cadastro de tipos de fatores para avaliação de desempenho.
 */
class _23031Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'pessoal/_23031';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index() {

    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'pessoal._23031.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Consultar tipos.
     *
     * @access public
     * @return json
     */
    public function consultarTipo() {

        $this->con = new _Conexao();

        try {
            
            $ret = _23031DAO::consultarTipo($this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Gravar tipo.
     *
     * @access public
     * @param Request $request
     */
    public function gravar(Request $request) {

        $this->con = new _Conexao();

        try {
            
            $param = json_decode(json_encode($request->all()));
            $param->ID = isset($param->ID) ? $param->ID : 0;

            _23031DAO::gravar($param, $this->con);

            $this->con->commit();
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Excluir tipo.
     *
     * @access public
     * @param Request $request
     * @return json
     */
    public function excluir(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            _23031DAO::excluir($param, $this->con);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }
    }

}