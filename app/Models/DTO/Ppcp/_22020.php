<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22020DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _22020
{
    /**
     * Retorna os registros de produção da laminação
     * @param type $param
     * <ul>
     *      <li><b>RETORNO</b>: Consultas a serem retornadas na chave.<br/>
     *          Ex.: _22020::listar( RETORNO => [PRODUCAO] ), retornará a producao
     *      </li>
     * </ul>
     * @return type
     */
    public static function listar($param = []) {
        return _22020DAO::listar(obj_case($param));
    }
	
	/**
	 * Verifica se a Estação está ativa (em produção).
	 * 
	 * @param array $param
	 * @return array
	 */
    public static function verificarEstacaoAtiva($param = []) {
        return _22020DAO::verificarEstacaoAtiva(obj_case($param));
    }
	
	/**
	 * Registra a Ação do Talão
	 * 
	 * @param array $param
	 * @return array
	 */
    public static function registraAcao($param = []) {
        return _22020DAO::registraAcao(obj_case($param));
    }
	
	/**
     * OBS: FUNÇÃO TEMPORÁRIA
	 * Recupera o registro da pesagem
	 * @param array $param
	 * @return array
	 */
    public static function pesagem($param = []) {
        return _22020DAO::pesagem(obj_case($param));
    }
	
	/**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function materiaPrima($param = []) {
        return _22020DAO::materiaPrima(obj_case($param));
    }
    
    /**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function materiaPrimaSobra($param = []) {
        return _22020DAO::materiaPrimaSobra(obj_case($param));
    }
    
    /**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function itensmateriaPrima($param = []) {
        return _22020DAO::itensmateriaPrima(obj_case($param));
    }
    
    /**
	 * Alterar sobra Materia prima
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdSobraMaterial($param = []) {
        return _22020DAO::alterarQtdSobraMaterial(obj_case($param));
    }
    
    /**
	 * Consultar peças disponíveis.
	 * Ordenado pelas peças de maior quantidade mais próximas da quantidade
	 * requerida para o produto atual.
	 * @param array $param
	 * @return array
	 */
	public static function consultarPecaDisponivel($param = []) {
		return _22020DAO::consultarPecaDisponivel(obj_case($param));
	}

	/**
	 * Consulta obs de um talao
	 * @param array $param
	 * @return array
	 */
    public static function consultaOBTalao($param = []) {
        return _22020DAO::consultaOBTalao(obj_case($param));
    }    
    
	
	/**
	 * Registra o vinculo da projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function gravarVinculo($param = []) {
        return _22020DAO::gravarVinculo(obj_case($param));
    }
	
	/**
	 * Registra o vinculo da projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function projecaoVinculo($param = []) {
        return _22020DAO::projecaoVinculo(obj_case($param));
    }
	
	/**
	 * Baixar a quantidade produzida.
	 * @param array $param
	 * @return array
	 */
    public static function baixarQuantidadeProduzida($param = []) {
        return _22020DAO::baixarQuantidadeProduzida(obj_case($param));
    }
    
    /**
	 * Consulta TBRevisao
	 * @param array $param
	 * @return array
	 */
    public static function itemTbRevisao($param = []) {
        return _22020DAO::itemTbRevisao(obj_case($param));
    }
	
	/**
	 * Alterar quantidade alocada da matéria-prima.
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdAlocada($param = []) {
        return _22020DAO::alterarQtdAlocada(obj_case($param));
    }
	
	/**
	 * Alterar quantidade de produção do detalhe do talão.
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdTalaoDetalhe($param = []) {
        return _22020DAO::alterarQtdTalaoDetalhe(obj_case($param));
    }
	
	/**
	 * Alterar todas as quantidades de produção do detalhe do talão.
	 * @param array $param
	 * @return array
	 */
    public static function alterarTodasQtdTalaoDetalhe($param = []) {
        return _22020DAO::alterarTodasQtdTalaoDetalhe(obj_case($param));
    }
	
	/**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function recarregarStatus($param = []) {
        return _22020DAO::recarregarStatus(obj_case($param));
    }
    
	/**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function remessaOrigemConsumo($param = []) {
        return _22020DAO::remessaOrigemConsumo(obj_case($param));
    }
    
    /**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function remessaOrigem($param = []) {
        return _22020DAO::remessaOrigem(obj_case($param));
    }
    
    /**
	 * Vinculo consumo.
	 * @param array $param
	 * @return array
	 */
    public static function vinculoConsumo($param = []) {
        return _22020DAO::vinculoConsumo(obj_case($param));
    }
    
	/**
	 * Excluir Vinculo da Projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function projecaoVinculoExcluir($param = []) {
        return _22020DAO::projecaoVinculoExcluir(obj_case($param));
    }
	
	/**
	 * Verifica se o item é um aproveitamento.
	 * @param array $param
	 * @return array
	 */
    public static function verificarAproveitamento($param = []) {
        return _22020DAO::verificarAproveitamento(obj_case($param));
    }
	
	/**
	 * Registra aproveitamento.
	 * @param array $param
	 * @return array
	 */
    public static function registrarAproveitamento($param = []) {
        return _22020DAO::registrarAproveitamento(obj_case($param));
    }
	
	/**
	 * Autenticar UP.
	 * @param array $param
	 * @return array
	 */
	public static function autenticarUp($param = []) {
		return _22020DAO::autenticarUp(obj_case($param));
	}
	
	/**
	 * Totalizadores diários.
	 * @param array $param
	 * @return array
	 */
	public static function totalizadorDiario($param = []) {
		return _22020DAO::totalizadorDiario(obj_case($param));
	}
	
	public static function totalizadorProgramado($conexao, $param = []) {
		return _22020DAO::totalizadorProgramado(obj_case($param), $conexao);
	}
	public static function totalizadorProduzido($conexao, $param = []) {
		return _22020DAO::totalizadorProduzido(obj_case($param), $conexao);
	}
	public static function totalizadorParPorDataRemessa($conexao, $param = []) {
		return _22020DAO::totalizadorParPorDataRemessa(obj_case($param), $conexao);
	}
	public static function totalizadorParPorDataProducao($conexao, $param = []) {
		return _22020DAO::totalizadorParPorDataProducao(obj_case($param), $conexao);
	}
    
    public static function updateTalaoViaEtiqueta($id) {
        return _22020DAO::updateTalaoViaEtiqueta($id);
    }
}
