<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25011DAO;

/**
 * Objeto _25011 - Formulários
 */
class _25011
{
	
	public static function listarFormulario($con) {
		return _25011DAO::listarFormulario($con);
	}

	public static function listarPergunta($con) {
		return _25011DAO::listarPergunta($con);
	}

	public static function listarAlternativa($con) {
		return _25011DAO::listarAlternativa($con);
	}

	public static function listarResposta($con) {
		return _25011DAO::listarResposta($con);
	}

	/**
	 * Autenticar colaborador.
	 * @param string $cpf
	 * @param string $cracha
	 * @param integer $formulario_id
     * @param _Conexao $con
	 */
	public static function autenticarColaborador($cpf, $cracha, $formulario_id, $con) {
		return _25011DAO::autenticarColaborador($cpf, $cracha, $formulario_id, $con);
	}

	/**
	 * Gravar respostas.
	 * @param array $param
     * @param _Conexao $con
	 */
	public static function gravarResposta($dados, $con) {
		return _25011DAO::gravarResposta($dados, $con);
	}

}