<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25010DAO;

/**
 * Objeto _25010 - Cadastro de Formulários
 */
class _25010
{

	public static function listarFormulario($param, $pu217, $usuario_id, $con) {
		return _25010DAO::listarFormulario($param, $pu217, $usuario_id, $con);
	}
	public static function listarDestinatario($con) {
		return _25010DAO::listarDestinatario($con);
	}
	public static function listarPergunta($con) {
		return _25010DAO::listarPergunta($con);
	}
	public static function listarAlternativa($con) {
		return _25010DAO::listarAlternativa($con);
	}
	public static function listarPainel($formulario_id, $con) {
		return _25010DAO::listarPainel($formulario_id, $con);
	}
	public static function listarPainelCliente($param, $con) {
		return _25010DAO::listarPainelCliente($param, $con);
	}
	public static function consultarUF($con) {
		return _25010DAO::consultarUF($con);
	}
	public static function csv($paran, $con) {
		return _25010DAO::csv($paran, $con);
	}

	public static function painelResposta($formulario_id, $destinatario_id, $con) {
		return _25010DAO::painelResposta($formulario_id, $destinatario_id, $con);
	}

	/**
	 * Listar tipos de formulário.
	 */
	public static function listarTipoFormulario($con) {
		return _25010DAO::listarTipoFormulario($con);
	}

	/**
	 * Listar tipos de resposta.
	 */
	public static function listarTipoResposta($con) {
		return _25010DAO::listarTipoResposta($con);
	}

	/**
	 * Listar níveis de satisfação das alternativas.
	 */
	public static function listarNivelSatisfacao($con) {
		return _25010DAO::listarNivelSatisfacao($con);
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _25010 $obj
	 */
	public static function gravar(_25010 $obj) {
		return _25010DAO::gravar($obj);
	}

	/**
	 * Gerar id do objeto.
	 *
	 * @param _Conexao $con
	 */
	public static function gerarId($con) {
		return _25010DAO::gerarId($con);
	}

	/**
	 * Gerar id da pergunta.
	 *
	 * @param _Conexao $con
	 */
	public static function gerarIdPergunta($con) {
		return _25010DAO::gerarIdPergunta($con);
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param Array $dados
	 * @param _Conexao $con
	 */
	public static function gravarFormulario($dados, $formulario_id, $con) {
		return _25010DAO::gravarFormulario($dados, $formulario_id, $con);
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param Array $dados
	 * @param _Conexao $con
	 */
	public static function gravarDestinatario($dados, $formulario_id, $con) {
		return _25010DAO::gravarDestinatario($dados, $formulario_id, $con);
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param Array $dados
	 * @param _Conexao $con
	 */
	public static function gravarPergunta($dados, $formulario_id, $pergunta_id, $con) {
		return _25010DAO::gravarPergunta($dados, $formulario_id, $pergunta_id, $con);
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param Array $dados
	 * @param _Conexao $con
	 */
	public static function gravarAlternativa($dados, $formulario_id, $pergunta_id, $con) {
		return _25010DAO::gravarAlternativa($dados, $formulario_id, $pergunta_id, $con);
	}

    /**
     * Alterar dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function alterarFormulario($dados, $con) {
        return _25010DAO::alterarFormulario($dados, $con);
    }

    /**
     * Alterar dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function alterarDestinatario($dados, $con) {
        return _25010DAO::alterarDestinatario($dados, $con);
    }

    /**
     * Excluir dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function excluirDestinatario($dados, $con) {
        return _25010DAO::excluirDestinatario($dados, $con);
    }

    /**
     * Alterar dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function alterarPergunta($dados, $pergunta_id, $formulario_id, $con) {
        return _25010DAO::alterarPergunta($dados, $pergunta_id, $formulario_id, $con);
    }

    /**
     * Excluir dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function excluirPergunta($dados, $con) {
        return _25010DAO::excluirPergunta($dados, $con);
    }

    /**
     * Alterar dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function alterarAlternativa($dados, $formulario_id, $pergunta_id, $con) {
        return _25010DAO::alterarAlternativa($dados, $formulario_id, $pergunta_id, $con);
    }

    /**
     * Excluir dados do objeto na base de dados.
     *
     * @param Array $dados
     * @param _Conexao $con
     */
    public static function excluirAlternativa($dados, $con) {
        return _25010DAO::excluirAlternativa($dados, $con);
    }

    /**
     * Excluir formulários.
     * @param Integer $id
     * @param _Conexao $con
     */
    public static function excluirFormulario($id, $con) {
        return _25010DAO::excluirFormulario($id, $con);
    }

}