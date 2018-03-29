<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23036DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23036 - Cadastro de avaliação de desempenho.
 */
class _23036Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'pessoal/_23036';

	/**
	 * Conexão.
	 * @var $con
	 */
	private $con = null;
	
	public function index() {

		$permissaoMenu 	= _11010::permissaoMenu($this->menu);
		
		return view(
			'pessoal._23036.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
	}

	/**
	 * Consultar base para avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarBaseAvaliacao(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$param->STATUS = $param->STATUS == '' ? null : $param->STATUS;

			$ret = _23036DAO::consultarBaseAvaliacao($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar centros de custo da base para avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarBaseCCustoAvaliacao(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$ret = _23036DAO::consultarBaseCCustoAvaliacao($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar modelo.
	 *
	 * @access public
	 * @return json
	 */
	public function consultarModelo() {

		$this->con = new _Conexao();

		try {
			
			$ret = _23036DAO::consultarModelo($this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar base para avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function gravarBase(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$this->gravarBaseAvaliacao($param);
			$this->gravarBaseCCustoAvaliacao($param);

			$this->con->commit();
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function gravarBaseAvaliacao(&$param) {

		$param->ID = (empty($param->ID) || array_key_exists('DO_MODELO', $param))
						? _23036DAO::gerarIdBase($this->con) 
						: $param->ID;

		_23036DAO::gravarBaseAvaliacao($param, $this->con);
	}

	public function gravarBaseCCustoAvaliacao($param) {

		foreach ($param->CCUSTO as $ccusto) {

			$ccusto->ID 						= empty($ccusto->ID) ? 0 : $ccusto->ID;
			$ccusto->AVALIACAO_DES_RESP_BASE_ID = $param->ID;
			$ccusto->STATUSEXCLUSAO 			= empty($ccusto->STATUSEXCLUSAO) ? '0' : $ccusto->STATUSEXCLUSAO;

			_23036DAO::gravarBaseCCustoAvaliacao($ccusto, $this->con);
		}
	}

	/**
	 * Excluir avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function excluirBase(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_23036DAO::excluirBaseAvaliacao($param, $this->con);
			_23036DAO::excluirBaseCCustoAvaliacao($param, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
			$this->con->rollback();
			throw $e;            
		}
	}

}