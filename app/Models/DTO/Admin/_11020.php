<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11020DAO;

/**
 * Objeto 11020 - Estabelecimento
 */
class _11020 {
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  
    
    public function selectEstabelecimento($param1) {
        
        $sql = "
            SELECT *
            FROM (
                SELECT
                    ESTABELECIMENTO_ID,
                    ESTABELECIMENTO_RAZAOSOCIAL,
                    ESTABELECIMENTO_NOMEFANTASIA,
                    USUARIO_PERMISSAO,
                    CNPJ,
                    (ESTABELECIMENTO_ID || ' ' ||
                     ESTABELECIMENTO_RAZAOSOCIAL || ' ' ||
                     ESTABELECIMENTO_NOMEFANTASIA || ' ' ||
                     CNPJ ) FILTRO
                FROM
                    (SELECT
                        FN_LPAD(CODIGO,3,0) ESTABELECIMENTO_ID,
                        RAZAOSOCIAL ESTABELECIMENTO_RAZAOSOCIAL,
                        NOMEFANTASIA ESTABELECIMENTO_NOMEFANTASIA,
                        CNPJ,
    
                        (SELECT OSPLIT
                           FROM SPLIT(
                           (SELECT LIST(FN_LPAD(ESTABELECIMENTO_CODIGO,3,0))
                              FROM TBUSUARIO_ESTABELECIMENTO U
                             WHERE U.USUARIO_CODIGO = FN_CURRENT_USER_ID(5)), ',')) USUARIO_PERMISSAO
                    FROM
                        TBESTABELECIMENTO E) X
                WHERE
                    IIF( USUARIO_PERMISSAO IS NULL, TRUE, X.ESTABELECIMENTO_ID IN (USUARIO_PERMISSAO))
                ) X
            WHERE TRUE
                /*@FILTRO*/
                /*@ESTABELECIMENTO_ID*/
        ";
        
        $param = (object)[];

        if ( isset($param1->FILTRO) && trim($param1->FILTRO) != '' ) {
            $param->FILTRO = " LIKE UPPER('%$param1->FILTRO%')";
        }

        if ( isset($param1->ESTABELECIMENTO_ID) && trim($param1->ESTABELECIMENTO_ID) != '' ) {
            $param->ESTABELECIMENTO_ID = " = $param1->ESTABELECIMENTO_ID";
        }

        $filtro             = array_key_exists('FILTRO'            , $param) ? "AND UPPER(FILTRO)      $param->FILTRO            " : '';
        $estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND ESTABELECIMENTO_ID $param->ESTABELECIMENTO_ID" : '';
        
        $args = [
            '@FILTRO'             => $filtro,
            '@ESTABELECIMENTO_ID' => $estabelecimento_id
        ];        
        
        return $this->con->query($sql,$args);
    }      
    
	private $id;
	private $nome_fantasia;
	
	public function getId() {
		return $this->id;
	}

	public function getNomeFantasia() {
		return $this->nome_fantasia;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setNomeFantasia($nome_fantasia) {
		$this->nome_fantasia = $nome_fantasia;
	}
	
	/**
	 * Listar estabelecimentos permitidos ao usuário.
	 * 
	 * @return array
	 */
	public static function listarSelect() {
		return _11020DAO::listarSelect();
	}
	
	/**
	 * Listar estabelecimentos.
	 * 
	 * @return array
	 */
	public static function listarTodos() {
		return _11020DAO::listarTodos();
	}
	
}

?>