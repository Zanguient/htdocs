<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23034DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23034 - Cadastro de resumo para avaliação de desempenho.
 */
class _23034Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'pessoal/_23034';

	/**
	 * Conexão.
	 * @var $con
	 */
	private $con = null;
	
	public function index() {

		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'pessoal._23034.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
	}

	/**
	 * Consultar resumo.
	 *
	 * @access public
	 * @return json
	 */
	public function consultarResumo() {

		$this->con = new _Conexao();

		try {
			
			$ret = [
				'RESUMO' 	 		=> _23034DAO::consultarResumo($this->con),
				'RESUMO_FATOR_TIPO' => _23034DAO::consultarResumoFatorTipo($this->con),
				'FATOR_TIPO' 		=> _23034DAO::consultarFatorTipo($this->con)
			];

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar resumo.
	 *
	 * @access public
	 * @param Request $request
	 */
	public function gravar(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param 		= json_decode(json_encode($request->all()));
			$param->ID 	= empty($param->ID) ? _23034DAO::gerarIdResumo($this->con) : $param->ID;

			_23034DAO::gravar($param, $this->con);
			$this->gravarResumoTipo($param);

			$this->con->commit();
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar tipos relacionados ao resumo.
	 *
	 * @access public
	 * @param json $param
	 */
	public function gravarResumoTipo($param) {

		foreach ($param->FATOR_TIPO as $tipo) {

			$tipo->ID 							= empty($tipo->ID) ? 0 : $tipo->ID;
			$tipo->AVALIACAO_DES_RESUMO_ID 		= $param->ID;
			$tipo->AVALIACAO_DES_FATOR_TIPO_ID 	= $tipo->FATOR_TIPO_ID;
			$tipo->STATUSEXCLUSAO 				= isset($tipo->STATUSEXCLUSAO) ? $tipo->STATUSEXCLUSAO : '0';

			_23034DAO::gravarResumoTipo($tipo, $this->con);
		}
	}

	/**
	 * Excluir resumo.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function excluir(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_23034DAO::excluir($param, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
			$this->con->rollback();
			throw $e;            
		}
	}

}