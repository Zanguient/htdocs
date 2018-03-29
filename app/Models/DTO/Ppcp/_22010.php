<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22010DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _22010 extends _22010DAO
{
    public function __construct($con) {
        $this->con = $con;
    }        
    
    public function getTalaoComposicao($param) {
        
        $ret = (object) [];
        
        $ret->DETALHE          = $this->getTalaoDetalhe($param);
        $ret->CONSUMO          = $this->getTalaoConsumo($param);
        $ret->CONSUMO_ALOCACAO = $this->getTalaoConsumoAlocacao($param);
        $ret->HISTORICO        = $this->getTalaoHistorico($param);        
        $ret->DEFEITO          = $this->getTalaoDefeito($param);
        $ret->FICHA            = $this->getTalaoFicha($param);
        
        if ( isset($param->GP_PECAS_DISPONIVEIS) && $param->GP_PECAS_DISPONIVEIS == '1' ) {
            $ret->CONSUMO_PECAS_DISPONIVEIS = $this->getTalaoConsumoPecaDisponivel($param);
        }
        
        return $ret;
    }

    public function getTalaoDetalhe($param) {
        return $this->selectTalaoDetalhe((object)[
            'REMESSA_ID'			=> $param->REMESSA_ID,
            'REMESSA_TALAO_ID'		=> $param->REMESSA_TALAO_ID,
            'APROVEITAMENTO_STATUS' => $param->STATUS                
        ],$this->con);
    }
        
    public function getTalaoConsumo($param) {
        return $this->selectTalaoConsumo((object)[
            'REMESSA_ID'		=> $param->REMESSA_ID,
            'REMESSA_TALAO_ID'	=> $param->REMESSA_TALAO_ID,
            'STATUS'			=> $param->STATUS
        ],$this->con);
    }
    
    public function getTalaoConsumoAlocacao($param) {
        return $this->selectTalaoConsumoAlocacao((object)[
            'TALAO_ID' => $param->ID
        ],$this->con);
    }
    
    public function getTalaoConsumoPecaDisponivel($param) {
        return $this->selectTalaoConsumoPecaDisponivel((object)[
            'TALAO_ID' => $param->ID
        ],$this->con);
    }

    public function getTalaoHistorico($dados) {
        return $this->selectTalaoHistorico((object)[
            'PROGRAMACAO_ID' => $dados->PROGRAMACAO_ID
        ],$this->con);
    }

    public function getTalaoDefeito($dados) {
        return $this->selectTalaoDefeito((object)[
            'REMESSA_ID'        => $dados->REMESSA_ID,
            'REMESSA_TALAO_ID'  => $dados->REMESSA_TALAO_ID
        ],$this->con);
    }

    public function getTalaoFicha($dados) {
        return $this->selectTalaoFicha((object)[
            'TALAO_ID'        => $dados->ID
        ],$this->con);
    }

    public function postTalaoFicha($dados) {
        return $this->insertTalaoFicha((object)[
            'REMESSA_ID'        => $dados->REMESSA_ID,
            'REMESSA_TALAO_ID'  => $dados->REMESSA_TALAO_ID,
            'TIPO_ID'           => $dados->TIPO_ID,
            'QUANTIDADE'        => $dados->QUANTIDADE,
            'OBSERVACAO'        => $dados->OBSERVACAO
        ],$this->con);
    }

    public function getDefeitos($dados) {
        return $this->selectDefeitos((object)[
            'FAMILIA_ID' => $dados->FAMILIA_ID
        ],$this->con);
    }

    public function postDefeitos($dados) {
        return $this->insertDefeito((object)[
            'ESTABELECIMENTO_ID'        => $dados->ESTABELECIMENTO_ID      ,
            'REMESSA_ID'                => $dados->REMESSA_ID              ,
            'REMESSA_TALAO_DETALHE_ID'  => $dados->REMESSA_TALAO_DETALHE_ID,
            'PRODUTO_ID'                => $dados->PRODUTO_ID              ,
            'TAMANHO'                   => $dados->TAMANHO                 ,
            'QUANTIDADE'                => $dados->QUANTIDADE              ,
            'DEFEITO_ID'                => $dados->DEFEITO_ID              ,
            'GP_ID'                     => $dados->GP_ID                   ,
            'OPERADOR_ID'               => $dados->OPERADOR_ID             ,
            'OBSERVACAO'		        => $dados->OBSERVACAO              ,
        ],$this->con);
    }

    public function excludeDefeito($dados) {
        return $this->deleteDefeito((object)[
            'DEFEITO_TRANSACAO_ID' => $dados->DEFEITO_TRANSACAO_ID
        ],$this->con);
    }
      
    public function getJustificativa($dados) {
        return $this->selectJustificativa((object)[
        ],$this->con);
    }    

    public function getTalaoVinculoModelos($dados) {
        return $this->selectTalaoVinculoModelos((object)[
            'TALAO_ID' => $dados->TALAO_ID
        ],$this->con);
    }
    
    /**
     * Retorna os registros de produção da laminação
     * @param type $param
     * <ul>
     *      <li><b>RETORNO</b>: Consultas a serem retornadas na chave.<br/>
     *          Ex.: _22010::listar( RETORNO => [PRODUCAO] ), retornará a producao
     *      </li>
     * </ul>
     * @return type
     */
    public static function listar($param = []) {
        return _22010DAO::listar(obj_case($param));
    }
	
	/**
	 * Verifica se a Estação está ativa (em produção).
	 * 
	 * @param array $param
	 * @return array
	 */
    public static function verificarEstacaoAtiva($param = []) {
        return _22010DAO::verificarEstacaoAtiva(obj_case($param));
    }
	
	/**
	 * Registra a Ação do Talão
	 * 
	 * @param array $param
	 * @return array
	 */
    public static function registraAcao($param = []) {
        return _22010DAO::registraAcao(obj_case($param));
    }
	
	/**
     * OBS: FUNÇÃO TEMPORÁRIA
	 * Recupera o registro da pesagem
	 * @param array $param
	 * @return array
	 */
    public static function pesagem($param = []) {
        return _22010DAO::pesagem(obj_case($param));
    }
	
	/**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function materiaPrima($param = []) {
        return _22010DAO::materiaPrima(obj_case($param));
    }
    
    /**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function materiaPrimaSobra($param = []) {
        return _22010DAO::materiaPrimaSobra(obj_case($param));
    }
    
    /**
	 * Materia prima da remessa/talão
	 * @param array $param
	 * @return array
	 */
    public static function itensmateriaPrima($param = []) {
        return _22010DAO::itensmateriaPrima(obj_case($param));
    }
    
    /**
	 * Alterar sobra Materia prima
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdSobraMaterial($param = []) {
        return _22010DAO::alterarQtdSobraMaterial(obj_case($param));
    }
    
    /**
	 * Consultar peças disponíveis.
	 * Ordenado pelas peças de maior quantidade mais próximas da quantidade
	 * requerida para o produto atual.
	 * @param array $param
	 * @return array
	 */
	public static function consultarPecaDisponivel($param = []) {
		return _22010DAO::consultarPecaDisponivel(obj_case($param));
	}

	/**
	 * Consulta obs de um talao
	 * @param array $param
	 * @return array
	 */
    public static function consultaOBTalao($param = []) {
        return _22010DAO::consultaOBTalao(obj_case($param));
    }    
    
	
	/**
	 * Registra o vinculo da projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function gravarVinculo($param = []) {
        return _22010DAO::gravarVinculo(obj_case($param));
    }
	
	/**
	 * Registra o vinculo da projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function projecaoVinculo($param = []) {
        return _22010DAO::projecaoVinculo(obj_case($param));
    }
	
	/**
     * Baixar a quantidade produzida.
     * @param array $param
     * @return array
     */
    public static function baixarQuantidadeProduzida($param = [], $conexao){
        return _22010DAO::baixarQuantidadeProduzida(obj_case($param), $conexao);
    }

    /**
     * Tolerancia para o tecido.
     * @param array $param
     * @return array
     */
    public static function toleranciaTecido($param, $conexao){
        return _22010DAO::toleranciaTecido(obj_case($param), $conexao);
    }

    /**
     * Tolerancia para o tecido.
     * @param array $param
     * @return array
     */
    public static function validarImpressaoTecido($param, $conexao){
        return _22010DAO::validarImpressaoTecido(obj_case($param), $conexao);
    }
    
    /**
     * Consulta TBRevisao
     * @param array $param
     * @return array
     */
    public static function itemTbRevisao($param = []) {
        return _22010DAO::itemTbRevisao(obj_case($param));
    }

    /**
     * Consulta 2 TBRevisao
     * @param array $param
     * @return array
     */
    public static function itemTbRevisao2($param = []) {
        return _22010DAO::itemTbRevisao2(obj_case($param));
    }
	
	/**
	 * Alterar quantidade alocada da matéria-prima.
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdAlocada($param = []) {
        return _22010DAO::alterarQtdAlocada(obj_case($param));
    }
	
	/**
	 * Alterar quantidade de produção do detalhe do talão.
	 * @param array $param
	 * @return array
	 */
    public static function alterarQtdTalaoDetalhe($param = []) {
        return _22010DAO::alterarQtdTalaoDetalhe(obj_case($param));
    }
	
	/**
	 * Alterar todas as quantidades de produção do detalhe do talão.
	 * @param array $param
	 * @return array
	 */
    public static function alterarTodasQtdTalaoDetalhe($param = []) {
        return _22010DAO::alterarTodasQtdTalaoDetalhe(obj_case($param));
    }
	
	/**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function recarregarStatus($param = []) {
        return _22010DAO::recarregarStatus(obj_case($param));
    }
    
	/**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function remessaOrigemConsumo($param = []) {
        return _22010DAO::remessaOrigemConsumo(obj_case($param));
    }
    
    /**
	 * Recarregar o status do talão.
	 * @param array $param
	 * @return array
	 */
    public static function remessaOrigem($param = []) {
        return _22010DAO::remessaOrigem(obj_case($param));
    }
    
    /**
	 * Vinculo consumo.
	 * @param array $param
	 * @return array
	 */
    public static function vinculoConsumo($param = []) {
        return _22010DAO::vinculoConsumo(obj_case($param));
    }
    
	/**
	 * Excluir Vinculo da Projeção de consumo
	 * @param array $param
	 * @return array
	 */
    public static function projecaoVinculoExcluir($param = []) {
        return _22010DAO::projecaoVinculoExcluir(obj_case($param));
    }
	
	/**
	 * Verifica se o item é um aproveitamento.
	 * @param array $param
	 * @return array
	 */
    public static function verificarAproveitamento($param = []) {
        return _22010DAO::verificarAproveitamento(obj_case($param));
    }
	
	/**
	 * Registra aproveitamento.
	 * @param array $param
	 * @return array
	 */
    public static function registrarAproveitamento($param = []) {
        return _22010DAO::registrarAproveitamento(obj_case($param));
    }
	
	/**
	 * Autenticar UP.
	 * @param array $param
	 * @return array
	 */
	public static function autenticarUp($param = []) {
		return _22010DAO::autenticarUp(obj_case($param));
	}
	
	/**
	 * Totalizadores diários.
	 * @param array $param
	 * @return array
	 */
	public static function totalizadorDiario($param = []) {
		return _22010DAO::totalizadorDiario(obj_case($param));
	}
	
	public static function totalizadorProgramado($conexao, $param = []) {
		return _22010DAO::totalizadorProgramado(obj_case($param), $conexao);
	}
	public static function totalizadorProduzido($conexao, $param = []) {
		return _22010DAO::totalizadorProduzido(obj_case($param), $conexao);
	}
	public static function totalizadorParPorDataRemessa($conexao, $param = []) {
		return _22010DAO::totalizadorParPorDataRemessa(obj_case($param), $conexao);
	}
	public static function totalizadorParPorDataProducao($conexao, $param = []) {
		return _22010DAO::totalizadorParPorDataProducao(obj_case($param), $conexao);
	}
    
    public static function updateTalaoViaEtiqueta($id) {
        return _22010DAO::updateTalaoViaEtiqueta($id);
    }
}
    