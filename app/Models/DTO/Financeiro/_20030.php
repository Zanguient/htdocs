<?php

namespace App\Models\DTO\Financeiro;

use App\Models\DAO\Financeiro\_20030DAO;

/**
 * Centro de custo
 */
class _20030
{
	private $id;
	private $descricao;
	
    
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function selectCcusto($param = null) {
        
        $sql = "
            SELECT FIRST :FIRST DISTINCT
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO
            FROM
                (SELECT
                    C.CODIGO ID,
                    FN_CCUSTO_MASK     (C.CODIGO) MASK,
                    FN_CCUSTO_DESCRICAO(C.CODIGO) DESCRICAO,

                    (F_REMOVE_ACENTOS(
                        C.CODIGO                      || ' - ' ||
                        FN_CCUSTO_MASK     (C.CODIGO) || ' - ' ||
                        FN_CCUSTO_DESCRICAO(C.CODIGO)
                    ))FILTRO

                FROM VWCENTRO_DE_CUSTO C
           LEFT JOIN TBUSUARIO_CCUSTO U ON C.CODIGO    = U.CCUSTO
                                        OR C.CODIGO LIKE U.CCUSTO||'%'

                WHERE 
                    C.ID > 1000
                AND U.USUARIO_ID = FN_CURRENT_USER_ID(5)
                
                ORDER BY
                    1,2)X
            WHERE TRUE
                /*@FILTRO*/
                /*@CCUSTO*/
        ";

        $filtro = array_key_exists('FILTRO', $param) ? "AND X.FILTRO LIKE UPPER('%'||REPLACE(CAST('$param->FILTRO' AS VARCHAR(500)),' ','%')||'%')" : '';        
        $ccusto = array_key_exists('CCUSTO', $param) ? "AND X.ID = $param->CCUSTO" : '';        
        
        $args = [
            'FIRST'    => setDefValue($param->FIRST, '100'),
            '@FILTRO'  => $filtro,
            '@CCUSTO'  => $ccusto
        ];
        
        return $this->con->query($sql,$args);
    } 
    
	/**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listar() {
		return _20030DAO::listar();
	}
	
	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _20030DAO::gerarId();
	}
	
	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _20030 $obj
	 */
	public static function gravar(_20030 $obj) {
		_20030DAO::gravar($obj);
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _20030DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _20030 $obj
	 */
	public static function alterar(_20030 $obj) {
		_20030DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		_20030DAO::excluir($id);
	}
	
	/**
	 * Pesquisa CCusto de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCusto($filtro) {
		return _20030DAO::pesquisaCCusto($filtro);
	}

	/**
	 * Pesquisa todos os Centro de Custos.
	 * Função chamada via Ajax.
	 *
	 * @return array
	 */
	public static function pesquisaCCustoTodos() {
		return _20030DAO::pesquisaCCustoTodos();
	}
    
    /**
	 * Pesquisa CCusto de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCusto2($filtro) {
		return _20030DAO::pesquisaCCusto2($filtro);
	}
    
    /**
	 * Pesquisa CCusto de acordo com o que for digitado pelo usuário apenas para Indicadores.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCustoIndicador($filtro) {
		return _20030DAO::pesquisaCCustoIndicador($filtro);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina) {
		return _20030DAO::paginacaoScroll($qtd_por_pagina, $pagina);
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro) {
		return _20030DAO::filtraObj($filtro);
	}
    
    /**
    * • Retorna um sentro de custo (desc e id)
    */
    public static function getCCusto($id)
    {
        return _20030DAO::getCCusto($id);
    }
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}	
}

?>