<?php

namespace App\Models\DAO\Helper;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Helper\Arquivo;
use Exception;
use PDO;

class ArquivoDAO
{

	/**
	 * Gerar id do arquivo.
	 * 
	 * @return array
	 */
	public static function gerarIdArquivo() {
		
		$con = new _Conexao();
		
		try {
			
			$sql = 'select gen_id(GTBARQUIVOS, 1) ID from RDB$DATABASE';
			
			$id  = $con->query($sql);

			$resposta = array('0' => 'sucesso');
			$retorno  = array('id' => $id, 'resposta' => $resposta);

		} catch(ValidationException $e1) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));

		} catch(Exception $e2) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
		}
		
		return $retorno;
		
	}
	
	/**
	 * Gerar id de vínculo do arquivo.
	 * 
	 * @param string $tabela
	 * @return array
	 */
	public static function gerarVinculo($tabela) {
		
		$con = new _Conexao();
		
		try {
			
			$sql = 'select gen_id(GTBVINCULO_'.$tabela.', 1) ID from RDB$DATABASE';
			
			$vinculo  = $con->query($sql);

			$resposta = array('0' => 'sucesso');
			$retorno  = array('vinculo' => $vinculo, 'resposta' => $resposta);

		} catch(ValidationException $e1) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));

		} catch(Exception $e2) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
		}
		
		return $retorno;
		
	}
	
	/**
	* Grava arquivo no banco de dados.
	*
	* @param Arquivo $obj
	*/
	public static function gravarArquivo(Arquivo $obj)
	{

		$con_files = new _Conexao('FILES');

		try {

			self::gravarArqVinc($obj, $con_files);
			self::gravarArq($obj, $con_files);

			$con_files->commit();
			$resposta = array('0' => 'sucesso', '1' => $obj->getId());

		} catch(ValidationException $e1) {

			$con_files->rollback();
			$resposta = array('0' => 'erro', '1' => $e1->getMessage());

		} catch(Exception $e2) {

			$con_files->rollback();
			$resposta = array('0' => 'erro', '1' => $e2->getMessage());

		}

		return $resposta;
		
	}
	
	/**
	 * Gravar vínculo do arquivo.
	 * Função complementar à 'gravarArquivo'.
	 * 
	 * @param Arquivo $obj
	 * @param _Conexao $con_files
	 */
	public static function gravarArqVinc(Arquivo $obj, _Conexao $con_files) {
		
		$sql_arq = '
			insert into tbvinculo (tabela,tabela_id,arquivo_id,sequencia,observacao,datahora,usuario_id)
    		values (:tabela,:tabela_id,:arquivo_id,:sequencia,:observacao,:datahora,:usuario_id)
		';
		
		$args_arq = array(
			':tabela' 		=> $obj->getTabela(),
			':tabela_id' 	=> $obj->getVinculo(),
			':arquivo_id' 	=> $obj->getId(),
			':sequencia' 	=> $obj->getSequencia(),
			':observacao'	=> $obj->getNome(),
			':datahora' 	=> $obj->getData(),
			':usuario_id' 	=> $obj->getUsuarioId()
		);

		$con_files->execute($sql_arq, $args_arq);
		
	}
	
	/**
	 * Gravar arquivo.
	 * Função complementar à 'gravarArquivo'.
	 * 
	 * @param Arquivo $obj
	 * @param _Conexao $con_files
	 */
	public static function gravarArq(Arquivo $obj, _Conexao $con_files) {

		$sql = '
			insert into TBARQUIVO
			(ID, DATAHORA, USUARIO_ID, ARQUIVO, CONTEUDO, EXTENSAO, TAMANHO)
			values(:id, :data, :usuario_id, :arquivo, :conteudo, :extensao, :tamanho)
		';
        
        $id			= $obj->getId();
		$data		= $obj->getData();
		$usuario_id = $obj->getUsuarioId();
		$arquivo	= $obj->getNome();
		$conteudo	= $obj->getConteudo();
		$extensao	= $obj->getTipo();
		$tamanho	= $obj->getTamanho();
        
        $query = $con_files->pdo->prepare($sql);

		$query->bindParam(':id', $id);
		$query->bindParam(':data', $data);
		$query->bindParam(':usuario_id', $usuario_id);
		$query->bindParam(':arquivo', $arquivo);
		$query->bindParam(':conteudo', $conteudo,PDO::PARAM_LOB);
		$query->bindParam(':extensao', $extensao);
		$query->bindParam(':tamanho', $tamanho);

		return $query->execute();
        
	}
	
	/**
	 * Exibe arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con_files
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function exibirArquivoObj(_Conexao $con_files, $vinc, $tabela) {
		
		$sql = '
			SELECT V.ID, V.ARQUIVO_ID,V.OBSERVACAO,V.USUARIO_ID FROM TBVINCULO V
			WHERE V.TABELA = :TABELA AND V.TABELA_ID = Coalesce(:ID, 0) and STATUSVINCULO = 1
		';

		$args = array(
			':ID'		=> $vinc,
			':TABELA'	=> $tabela
		);

		return $con_files->query($sql,$args);
		
	}
    
    /**
	 * Exibe arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function getFile($vinc, $tabela) {
		
        $con_files = new _Conexao('FILES');
        
		$sql = '
			SELECT V.ID, V.ARQUIVO_ID,V.OBSERVACAO,V.USUARIO_ID FROM TBVINCULO V
			WHERE V.TABELA = \''.$tabela.'\' AND V.TABELA_ID IN ('.$vinc.')
		';

		return $con_files->query($sql);
		
	}
    
    /**
	 * Exibe arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function gerarFile($id){
        $con_files = new _Conexao('FILES');

        $sql = 'SELECT a.arquivo,a.conteudo,a.tamanho,a.extensao FROM tbarquivo a where a.id = :item';
        $args = array(':item' => $id);

        $dado = $con_files->query($sql, $args);

        $nome = $dado[0]->ARQUIVO;
        $conteudo = $dado[0]->CONTEUDO;
        $tamanho = $dado[0]->TAMANHO;
        $extensao = $dado[0]->EXTENSAO;
        
        return array('nome' => $nome, 'conteudo' => $conteudo, 'tamanho' => $tamanho,'extensao' => $extensao);
    }
	
	/**
	 * Altera vínculo de arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con_files
	 * @param object $obj
	 * @return array
	 */
	public static function alterarVinculoObj(_Conexao $con_files, $obj) {
		
		//Alterar vínculo
		$sql = '
			UPDATE TBVINCULO V SET V.STATUSVINCULO = 1 
			WHERE V.TABELA = :TABELA AND V.TABELA_ID = :ID
		';

		$args = array(
			':TABELA' 	 => $obj->getTabela(),
			':ID' 	 	 => $obj->getVinculo()
		);

		$con_files->execute($sql, $args);
				
	}
	
	/**
	 * Exclui arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con_files
	 * @param object $obj
	 * @return array
	 */
	public static function excluirArquivo(_Conexao $con_files, $obj) {

		//Excluir arquivos
		if ( count($obj->getArquivoExcluir()) > 0 ) {

			foreach ($obj->getArquivoExcluir() as $arq_exc) {

			   $sql = 'DELETE FROM TBVINCULO WHERE ID = :item';
			   $args = array(':item' => $arq_exc);

			   $con_files->execute($sql, $args);

			}

		}
		
	}


	/**
	 * Excluir arquivo.
	 * OBS.: Utilizado por meio do Angular.
	 * 
	 * @param integer $arquivoId
	 */
	public static function excluir($arquivoId) {

		$conFile = new _Conexao('FILES');

		try {

			self::excluirVinculo($arquivoId, $conFile);
			self::excluirArq($arquivoId, $conFile);

			$conFile->commit();

		} catch(Exception $e) {
            $conFile->rollback();
            throw $e;
        }
	}

	public static function excluirVinculo($arquivoId, $conFile) {

		$sql = '
			DELETE FROM TBVINCULO 
			WHERE ARQUIVO_ID = :ARQUIVO_ID
		';

		$args = [
			':ARQUIVO_ID' => $arquivoId
		];

		$conFile->execute($sql, $args);
	}

	public static function excluirArq($arquivoId, $conFile) {

		$sql = '
			DELETE FROM TBARQUIVO 
			WHERE ID = :ARQUIVO_ID
		';

		$args = [
			':ARQUIVO_ID' => $arquivoId
		];

		$conFile->execute($sql, $args);
	}
    
    public static function svnHead($param = [], _Conexao $con = null)
    {
        $sql =
        '
            UPDATE OR INSERT
            INTO TBSVN (
                REVISION,
                AUTHOR,
                "DATE",
                MSG
            ) VALUES (
                :REVISION,
                :AUTHOR,
                :DATE,
                :MSG
            ) MATCHING (
                REVISION
            );
        ';
        
        return $con->query($sql,$param);
    }       
    
    public static function svnBody($param = [], _Conexao $con = null)
    {
        $sql =
        '
            UPDATE OR INSERT
            INTO TBSVN_FILE (
                REVISION,
                "ACTION",
                TEXT_MODS,
                PROP_MODS,
                "TYPE",
                "FILE"
            ) VALUES (
                :REVISION,
                :ACTION,
                :TEXT_MODS,
                :PROP_MODS,
                :TYPE,
                :FILE
            ) MATCHING (
                REVISION,
                "FILE"
            );
        ';
        
        return $con->query($sql,$param);
    }       
	
}
