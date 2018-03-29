<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23038DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23038 - Registro de indicadores por centro de custo.
 */
class _23038Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'pessoal/_23038';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index() {

        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'pessoal._23038.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
        ]);  
    }

    /**
     * Consultar indicadores por centro de custo.
     *
     * @access public
     * @param Request $request
     * @return json
     */
    public function consultarIndicadorPorCCusto(Request $request) {

        $this->con = new _Conexao();

        try {
            
            $param = json_decode(json_encode($request->all()));

            $ret = _23038DAO::consultarIndicadorPorCCusto($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar indicadores.
     *
     * @access public
     * @return json
     */
    public function consultarIndicador() {

        $this->con = new _Conexao();

        try {
            
            $ret = _23038DAO::consultarIndicador($this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Gravar indicador.
     *
     * @access public
     * @param Request $request
     */
    public function gravar(Request $request) {

        $this->con = new _Conexao();

        try {
            
            $param = json_decode(json_encode($request->all()));
            $param->ID = isset($param->ID) ? $param->ID : 0;

            _23038DAO::gravar($param, $this->con);

            $this->con->commit();
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Excluir indicador.
     *
     * @access public
     * @param Request $request
     * @return json
     */
    public function excluir(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            _23038DAO::excluir($param, $this->con);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }
    }

}