<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DAO\Pessoal\_23037DAO;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _23037 - Avaliação de desempenho
 */
class _23037Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'pessoal/_23037';

	/**
	 * Conexão.
	 * @var $con
	 */
	private $con = null;
	
	public function index() {

		$permissaoMenu = _11010::permissaoMenu($this->menu);
		$pu225         = _11010::controle(225);
		
		return view(
			'pessoal._23037.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu,
			'pu225'         => $pu225
		]);  
	}

	/**
	 * Consultar base da avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarBase(Request $request) {

		$this->con = new _Conexao();

		try {

			if (empty(\Auth::user()->COLABORADOR_ID))
				log_erro('Usuário precisa estar vinculado a um colaborador.');

			$param = json_decode(json_encode($request->all()));

			$param->STATUS = $param->STATUS == '' ? null : $param->STATUS;
			$param->CCUSTO = _23037DAO::consultarCCustoGestor(\Auth::user()->COLABORADOR_ID, $this->con);

			$ret = _23037DAO::consultarBase($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarAvaliacao(Request $request) {

		$this->con = new _Conexao();

		try {

			if (empty(\Auth::user()->COLABORADOR_ID))
				log_erro('Usuário precisa estar vinculado a um colaborador.');

			$param = json_decode(json_encode($request->all()));
			$param->CCUSTO = _23037DAO::consultarCCustoGestor(\Auth::user()->COLABORADOR_ID, $this->con);
			
			$ret = _23037DAO::consultarAvaliacao($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar itens da avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarAvaliacaoItem(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));

			$ret = [
				'FATOR'         => _23037DAO::consultarAvaliacaoFator($param, $this->con),
				'FATOR_TIPO'    => _23037DAO::consultarAvaliacaoFatorTipo($param, $this->con),
				'FATOR_NIVEL'   => _23037DAO::consultarAvaliacaoFatorNivel($param, $this->con),
				'FORMACAO'      => _23037DAO::consultarAvaliacaoFormacao($param, $this->con),
				'RESUMO'        => _23037DAO::consultarAvaliacaoResumo($param, $this->con)
			];

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar itens do modelo escolhido.
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
				'FATOR'         => _23037DAO::consultarModeloFator($param, $this->con),
				'FATOR_TIPO'    => _23037DAO::consultarModeloFatorTipo($param, $this->con),
				'FATOR_NIVEL'   => _23037DAO::consultarModeloFatorNivel($param, $this->con),
				'FORMACAO'      => _23037DAO::consultarModeloFormacao($param, $this->con),
				'RESUMO'        => _23037DAO::consultarModeloResumo($param, $this->con)
			];

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar colaboradores.
	 *
	 * @access public
	 * @return json
	 */
	public function consultarColaborador() {

		$this->con = new _Conexao();

		try {
			
			if (empty(\Auth::user()->COLABORADOR_ID))
				log_erro('Usuário precisa estar vinculado a um colaborador.');

			$param         = new \stdClass();
			$param->CCUSTO = _23037DAO::consultarCCustoGestor(\Auth::user()->COLABORADOR_ID, $this->con);

			$ret = _23037DAO::consultarColaborador($param, $this->con);

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar indicador do colaborador.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarColaboradorIndicador(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));

			$ret = [
				'ABSENTEISMO' 	=> _23037DAO::consultarColaboradorAbsenteismo($param, $this->con),
				'INDICADOR' 	=> _23037DAO::consultarColaboradorIndicador($param, $this->con)
			];	

			$this->con->commit();
			
			return Response::json($ret);
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function gravarAvaliacao(Request $request) {

		$this->con = new _Conexao();

		try {
			
			$param = json_decode(json_encode($request->all()));

			$param->ID = (empty($param->ID) || array_key_exists('DO_MODELO', $param))
							? _23037DAO::gerarIdAvaliacao($this->con) 
							: $param->ID;

			$this->gravarFatorTipo($param);
			$this->gravarFator($param);
			$this->gravarFatorNivel($param);
			$this->gravarFormacao($param);
			$this->gravarResumo($param);
			$this->gravarAvaliacaoInfo($param);	// último devido à formação

			$this->con->commit();
			
		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function gravarFatorTipo(&$param) {

		foreach ($param->FATOR_TIPO as &$tipo) {
			
			$tipo->AVALIACAO_DES_RESPOSTA_ID    = $param->ID;
			$tipo->ID_OLD                       = $tipo->ID;    // para verificação ao gravar fator e resumo
			$tipo->ID                           = (empty($tipo->ID) || array_key_exists('DO_MODELO', $tipo))
													? _23037DAO::gerarIdFatorTipo($this->con) 
													: $tipo->ID;
			$tipo->STATUSEXCLUSAO               = empty($tipo->STATUSEXCLUSAO) ? '0' : $tipo->STATUSEXCLUSAO;

			_23037DAO::gravarFatorTipo($tipo, $this->con);
		}
	}

	public function gravarFator(&$param) {

		foreach ($param->FATOR as &$fator) {

			$fator->AVALIACAO_DES_RESPOSTA_ID   = $param->ID;
			$fator->ID                          = (empty($fator->ID) || array_key_exists('DO_MODELO', $fator))
													? _23037DAO::gerarIdFator($this->con) 
													: $fator->ID;
			$fator->PONTO                       = !isset($fator->PONTO)         ? null : $fator->PONTO;
			$fator->STATUSEXCLUSAO              = empty($fator->STATUSEXCLUSAO) ? '0'  : $fator->STATUSEXCLUSAO;

			foreach ($param->FATOR_TIPO as $tipo) {

				// Define o tipo do fator. Esse tipo é o gravado na avaliação, que é a cópia do modelo.
				if ($tipo->ID_OLD == $fator->TIPO_ID)
					$fator->AVALIACAO_DES_RESP_FAT_TIPO_ID = $tipo->ID;
			}

			_23037DAO::gravarFator($fator, $this->con);
		}
	}

	public function gravarFatorNivel(&$param) {

		foreach ($param->FATOR_NIVEL as &$nivel) {
			
			foreach ($param->FATOR as $fator) {
			
				if ($fator->FATOR_ID == $nivel->FATOR_ID) {

					$nivel->AVALIACAO_DES_RESP_FATOR_ID = $fator->ID;
					break;
				}
			}

			$nivel->ID                          = (empty($nivel->ID) || array_key_exists('DO_MODELO', $nivel)) 
													? 0 
													: $nivel->ID;

			$nivel->AVALIACAO_DES_RESPOSTA_ID   = $param->ID;
			$nivel->STATUSEXCLUSAO              = empty($nivel->STATUSEXCLUSAO) ? '0' : $nivel->STATUSEXCLUSAO;

			_23037DAO::gravarFatorNivel($nivel, $this->con);
		}
	}

	public function gravarFormacao(&$param) {

		foreach ($param->FORMACAO as &$formacao) {
			
			$formacao->ID_OLD 					 = $formacao->ID;
			$formacao->ID                        = (empty($formacao->ID) || array_key_exists('DO_MODELO', $formacao))
														? _23037DAO::gerarIdFormacao($this->con)  
														: $formacao->ID;

			$formacao->AVALIACAO_DES_RESPOSTA_ID = $param->ID;
			$formacao->STATUSEXCLUSAO            = empty($formacao->STATUSEXCLUSAO) ? '0' : $formacao->STATUSEXCLUSAO;

			_23037DAO::gravarFormacao($formacao, $this->con);
		}
	}

	public function gravarResumo(&$param) {

		foreach ($param->RESUMO as &$resumo) {

			$resumo->ID                         = (empty($resumo->ID) || array_key_exists('DO_MODELO', $resumo))
													? 0 
													: $resumo->ID;
			
			$resumo->AVALIACAO_DES_RESPOSTA_ID  = $param->ID;
			$resumo->PONTUACAO_GERAL            = empty($resumo->PONTUACAO_GERAL)   ? 0 	: $resumo->PONTUACAO_GERAL;
			$resumo->RESULTADO                  = empty($resumo->RESULTADO)         ? 0 	: $resumo->RESULTADO;
			$resumo->STATUSEXCLUSAO             = empty($resumo->STATUSEXCLUSAO)    ? '0' 	: $resumo->STATUSEXCLUSAO;

			$novoFatorTipoId = '';

			foreach ($param->FATOR_TIPO as $tipo) {
				
				// Definindo novos ids dos tipos de fatores relacionados ao resumo.
				if (preg_match("/$tipo->ID_OLD/i", $resumo->FATOR_TIPO_ID))
					$novoFatorTipoId .= $tipo->ID .',';
			}

			$resumo->FATOR_TIPO_ID = $novoFatorTipoId;

			_23037DAO::gravarResumo($resumo, $this->con);
		}
	}

	public function gravarAvaliacaoInfo($param) {

		$param->PONTUACAO_TOTAL_FATOR       = empty($param->PONTUACAO_TOTAL_FATOR)      ? null : $param->PONTUACAO_TOTAL_FATOR;
		$param->PONTUACAO_MEDIA_FATOR       = empty($param->PONTUACAO_MEDIA_FATOR)      ? null : $param->PONTUACAO_MEDIA_FATOR;
		$param->RESULTADO_FINAL_RESUMO      = empty($param->RESULTADO_FINAL_RESUMO)     ? null : $param->RESULTADO_FINAL_RESUMO;
		$param->META_MEDIA_GERAL            = empty($param->META_MEDIA_GERAL)           ? null : $param->META_MEDIA_GERAL;
		$param->ALCANCOU_META_MEDIA_GERAL   = empty($param->ALCANCOU_META_MEDIA_GERAL)  ? 0	   : $param->ALCANCOU_META_MEDIA_GERAL;
		$param->PONTO_POSITIVO              = empty($param->PONTO_POSITIVO)             ? null : $param->PONTO_POSITIVO;
		$param->PONTO_MELHORAR              = empty($param->PONTO_MELHORAR)             ? null : $param->PONTO_MELHORAR;
		$param->OPINIAO_AVALIADO            = empty($param->OPINIAO_AVALIADO)           ? null : $param->OPINIAO_AVALIADO;
		$param->STATUSEXCLUSAO              = empty($param->STATUSEXCLUSAO)             ? '0'  : $param->STATUSEXCLUSAO;

		foreach ($param->FORMACAO as $formacao) {
		
			if ($param->FORMACAO_ESCOLHIDA_ID == $formacao->ID_OLD) {
				$param->FORMACAO_ESCOLHIDA_ID = $formacao->ID;
				break;
			}
		}

		$param->FORMACAO_ESCOLHIDA_ID = empty($param->FORMACAO_ESCOLHIDA_ID) ? null : $param->FORMACAO_ESCOLHIDA_ID;

		_23037DAO::gravarAvaliacao($param, $this->con);
	}

	/**
	 * Excluir avaliação.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function excluirAvaliacao(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_23037DAO::excluirAvaliacao($param, $this->con);
			_23037DAO::excluirAvaliacaoFatorTipo($param, $this->con);
			_23037DAO::excluirAvaliacaoFator($param, $this->con);
			_23037DAO::excluirAvaliacaoFatorNivel($param, $this->con);
			_23037DAO::excluirAvaliacaoFormacao($param, $this->con);
			_23037DAO::excluirAvaliacaoResumo($param, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
			$this->con->rollback();
			throw $e;            
		}
	}

}