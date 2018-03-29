<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23030DAO;
use App\Models\DAO\Pessoal\_23031DAO;
use App\Models\DAO\Pessoal\_23032DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23032 - Cadastro de fatores para avaliação de desempenho.
 */
class _23032Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'pessoal/_23032';

	/**
	 * Conexão.
	 * @var $con
	 */
	private $con = null;
	
	public function index() {

		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'pessoal._23032.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
	}

	/**
	 * Consultas iniciais.
	 *
	 * @access public
	 * @return json
	 */
	public function consultarInicial() {

		$this->con = new _Conexao();

		try {
			
			$ret = [
				'FATOR_NIVEL' => _23030DAO::consultarNivel($this->con),
				'FATOR_TIPO'  => _23031DAO::consultarTipo($this->con)
			];

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar fatores.
	 *
	 * @access public
	 * @return json
	 */
	public function consultarFator() {

		$this->con = new _Conexao();

		try {
			
			$ret = _23032DAO::consultarFator($this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar descritivos dos níveis de fatores.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarFatorNivelDescritivo(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$ret = _23032DAO::consultarFatorNivelDescritivo($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar fator.
	 *
	 * @access public
	 * @param Request $request
	 */
	public function gravar(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));
			
			$param->ID 			= isset($param->ID) 		? $param->ID 		: _23032DAO::gerarIdFator($this->con);
			$param->DESCRICAO 	= isset($param->DESCRICAO) 	? $param->DESCRICAO : null;

			_23032DAO::gravarFator($param, $this->con);

			foreach ($param->DESCRITIVO as $desc) {

				$desc->ID 				= isset($desc->ID) ? $desc->ID : 0;
				$desc->FATOR_ID 		= $param->ID;
				$desc->FAIXA_INICIAL 	= isset($desc->FAIXA_INICIAL) 	? $desc->FAIXA_INICIAL 	: null;
				$desc->FAIXA_FINAL 		= isset($desc->FAIXA_FINAL) 	? $desc->FAIXA_FINAL 	: null;
				$desc->STATUSEXCLUSAO 	= isset($desc->STATUSEXCLUSAO) 	? $desc->STATUSEXCLUSAO : '0';

				_23032DAO::gravarFatorNivelDescritivo($desc, $this->con);
			}

			$this->con->commit();
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Excluir fator.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function excluir(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_23032DAO::excluirFator($param, $this->con);
			_23032DAO::excluirFatorNivelDescritivo($param, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
			$this->con->rollback();
			throw $e;            
		}
	}

}