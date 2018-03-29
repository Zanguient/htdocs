<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23035DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
 */
class _23035Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'pessoal/_23035';

	/**
	 * Conexão.
	 * @var $con
	 */
	private $con = null;
	
	public function index() {

		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'pessoal._23035.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
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
			
			$ret = _23035DAO::consultarModelo($this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar itens do modelo.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarModeloItem(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));

			$ret = [
				'FATOR' 	=> _23035DAO::consultarModeloFator($param, $this->con),
				'FORMACAO' 	=> _23035DAO::consultarModeloFormacao($param, $this->con),
				'RESUMO' 	=> _23035DAO::consultarModeloResumo($param, $this->con)
			];

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar modelo.
	 *
	 * @access public
	 * @param Request $request
	 */
	public function gravar(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));

			$this->gravarModelo($param);
			$this->gravarModeloFator($param);
			$this->gravarModeloFormacao($param);
			$this->gravarModeloResumo($param);

			$this->con->commit();
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar modelo.
	 *
	 * @access public
	 * @param json $param
	 */
	public function gravarModelo(&$param) {

		$param->ID = empty($param->ID) 
						? _23035DAO::gerarIdModelo($this->con) 
						: $param->ID;

		_23035DAO::gravarModelo($param, $this->con);
	}

	/**
	 * Gravar fatores do modelo.
	 *
	 * @access public
	 * @param json $param
	 */
	public function gravarModeloFator($param) {

		foreach ($param->FATOR as $fator) {
			
			$fator->ID                      = empty($fator->ID) ? 0 : $fator->ID;
			$fator->AVALIACAO_DES_MODELO_ID = $param->ID;
			$fator->STATUSEXCLUSAO 			= empty($fator->STATUSEXCLUSAO) ? '0' : $fator->STATUSEXCLUSAO;

			_23035DAO::gravarModeloFator($fator, $this->con);
		}
	}

	/**
	 * Gravar formação do modelo.
	 *
	 * @access public
	 * @param json $param
	 */
	public function gravarModeloFormacao($param) {

		foreach ($param->FORMACAO as $formacao) {
			
			$formacao->ID                      	= empty($formacao->ID) ? 0 : $formacao->ID;
			$formacao->AVALIACAO_DES_MODELO_ID 	= $param->ID;
			$formacao->STATUSEXCLUSAO 			= empty($formacao->STATUSEXCLUSAO) ? '0' : $formacao->STATUSEXCLUSAO;

			_23035DAO::gravarModeloFormacao($formacao, $this->con);
		}
	}

	/**
	 * Gravar resumo do modelo.
	 *
	 * @access public
	 * @param json $param
	 */
	public function gravarModeloResumo($param) {

		foreach ($param->RESUMO as $resumo) {
			
			$resumo->ID                      = empty($resumo->ID) ? 0 : $resumo->ID;
			$resumo->AVALIACAO_DES_MODELO_ID = $param->ID;
			$resumo->STATUSEXCLUSAO 		 = empty($resumo->STATUSEXCLUSAO) ? '0' : $resumo->STATUSEXCLUSAO;

			_23035DAO::gravarModeloResumo($resumo, $this->con);
		}
	}

	/**
	 * Excluir modelo.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function excluir(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_23035DAO::excluirModelo($param, $this->con);
			_23035DAO::excluirModeloFator($param, $this->con);
			_23035DAO::excluirModeloFormacao($param, $this->con);
			_23035DAO::excluirModeloResumo($param, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
			$this->con->rollback();
			throw $e;            
		}
	}

}