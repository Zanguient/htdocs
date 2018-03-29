<?php

namespace App\Models\DTO\Produto;

use App\Models\DAO\Produto\_27020DAO;
use App\Models\Conexao\_Conexao;
/**
 * Objeto _27020 - Cadastro de Modelos
 */
class _27020
{
    

    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  

    public function selectModelo($param) {

        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP
                *
            FROM (
                SELECT
                    FN_LPAD(M.CODIGO,4,0) ID,
                    M.DESCRICAO,
                    FN_LPAD(M.FAMILIA_CODIGO,3,0) FAMILIA_ID,
                    (SELECT FIRST 1 F.DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = M.FAMILIA_CODIGO)FAMILIA_DESCRICAO,
                    M.GRADE_CODIGO GRADE_ID,
                    (SELECT FIRST 1 G.DESCRICAO FROM TBGRADE G WHERE G.CODIGO = M.GRADE_CODIGO)GRADE_DESCRICAO,
                    FN_LPAD(M.MATRIZ_CODIGO,4,0) MATRIZ_ID,
                    (SELECT FIRST 1 MT.DESCRICAO FROM TBMATRIZ MT WHERE MT.CODIGO = M.MATRIZ_CODIGO)MATRIZ_DESCRICAO,
                    TRIM(M.STATUS) STATUS,
                    TRIM(CASE M.STATUS
                    WHEN 1 THEN 'ATIVO'
                    WHEN 0 THEN 'INATIVO'
                    END) STATUS_DESCRICAO,
                    M.UNIDADEMEDIDA_SIGLA UM,
                    FN_LPAD(M.MODELO_PAI,4,0) MODELO_PAI,
                    (SELECT FIRST 1 MD.DESCRICAO FROM TBMODELO MD WHERE MD.CODIGO = M.MODELO_PAI)MODELO_PAI_DESCRICAO


                FROM
                    TBMODELO M
                ) X
            WHERE TRUE
            AND X.ID || ' - ' ||  X.DESCRICAO LIKE '%'||UPPER(REPLACE(CAST(:FILTRO AS VARCHAR(500)),' ','%') )||'%'
            /*@STATUS*/
        ";
        
        $status = array_key_exists('STATUS', $param) && $param->STATUS != '' ? "AND X.STATUS = $param->STATUS" : '';        
        
        $args = [
            'FIRST'  => setDefValue($param->FIRST , 50),
            'SKIP'   => setDefValue($param->SKIP  , 0),
            'FILTRO' => setDefValue($param->FILTRO, '%'),
            '@STATUS' => setDefValue($status, '', '')
        ];

        return $this->con->query($sql,$args);
    }  

    public function selectModeloTamanho($param) {

        $sql = "
            SELECT * FROM SPC_MODELO_GRADE_TAMANHO(:MODELO_ID)
        ";
        
        $args = [
            'MODELO_ID' => setDefValue($param->MODELO_ID, 0),
        ];

        return $this->con->query($sql,$args);
    }  

    public function selectArquivo($param) {

        $sql = "
            SELECT
                V.SEQUENCIA,
                V.OBSERVACAO,
                V.DATAHORA, 
                A.ID, 
                A.ARQUIVO,
                --A.CONTEUDO,
                A.TAMANHO,
                A.EXTENSAO
            FROM 
                TBARQUIVO A
                INNER JOIN TBVINCULO V ON V.ARQUIVO_ID = A.ID
            WHERE 
                V.TABELA = 'TBMODELO'
            AND V.TABELA_ID = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];        

        return $this->con->query($sql,$args);
    }  

    public function selectArquivoConteudo($param) {

        $sql = "
            SELECT A.ARQUIVO, A.CONTEUDO
              FROM TBARQUIVO A
             WHERE A.ID = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];        

        return $this->con->query($sql,$args);
    }  
    
	private $id;
	private $descricao;
	private $cliente_id;
	
	function getId() {
		return $this->id;
	}

	function getDescricao() {
		return $this->descricao;
	}

	function getClienteId() {
		return $this->cliente_id;
	}

	function setId($id) {
		$this->id = $id;
	}

	function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	function setClienteId($cliente_id) {
		$this->cliente_id = $cliente_id;
	}
	


	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _27020DAO::listar($dados);
	}

	/**
	 * Consultar modelo.
	 * @param _Conexao $con
	 * @return json
	 */
	public static function consultarModelo($con) {
		return _27020DAO::consultarModelo($con);
	}
	
	/**
	 * Consultar modelo por cliente.
	 * @param json $param
	 * @param _Conexao $con
	 * @return json
	 */
	public static function consultarModeloPorCliente($param, $con) {
		return _27020DAO::consultarModeloPorCliente($param, $con);
	}

	public static function verArquivo($param, $conFile) {
		return _27020DAO::verArquivo($param, $conFile);
	}

}