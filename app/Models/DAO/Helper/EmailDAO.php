<?php

namespace App\Models\DAO\Helper;

use Illuminate\Support\Facades\DB;
use App\Models\DTO\Helper\Email;
use App\Models\Conexao\_Conexao;
use PDO;

class EmailDAO
{	
	
	/**
	 * Gerar id do email.
	 * Obs. Essa tabela insere o ID altomaticamente
	 * 
	 * @return integer
	 */
	public static function gerarId()
	{
	 	return DB::select('select gen_id(GTBEMAIL, 1) ID from RDB$DATABASE')[0]->ID;
	}
	
	
	public static function getTemplete($id)
	{
		$sql_req = 'SELECT E.DESCRICAO,E.CORPO FROM TBTEMPLATE_EMAIL E WHERE E.ID = :ID';
		
		$args_req = array(':ID'	=> $id);
		
		$corpo = DB::SELECT($sql_req, $args_req);
		
		//print_r($corpo[0]->CORPO);
		//exit();
		
		return	$corpo[0]->CORPO;
	}
	
	/**
     * Inserir dados do email na base de dados.
	 * 
     * @param Email $obj
     * @param _Conexao $conexao | Caso este parametro não seja passado será instânciado uma nova _Conexao
	 * @param int $anexo_id Opcional
	 * @param string $anexo_desc Opcional
     * @return array
     */
	public static function gravar(Email $obj, _Conexao $conexao = null, $anexo_id = null, $anexo_desc = null)
	{
        if ( !$conexao ) {
            $con = new _Conexao();
        } else {
            $con = $conexao;
        }
		
		try {
			
			$sql_req  = "
				insert into TBEMAIL
					(ID, EMAIL, USUARIO_ID, ASSUNTO, CORPO, STATUS, DATAHORA)
				values 
					(:ID, :EMAIL, :USUARIO_ID, :ASSUNTO, 
					(SELECT REPLACE(REPLACE(REPLACE(CORPO, '#URL-PREENCHER#', :CORPO), '#URL#', :URL), '#MSG#', :MSG) FROM TBTEMPLATE_EMAIL WHERE ID = :CODIGO),
					:STATUS, :DATAHORA)
			";

			$args_req = array(
				':ID'			=> !empty($obj->getId()) ? $obj->getId() : self::gerarId(),
				':EMAIL'		=> $obj->getEmail(),
				':USUARIO_ID'	=> $obj->getUsuarioId(),
				':ASSUNTO'		=> $obj->getAssunto(),
				':MSG'			=> $obj->getMensagem(),
				':URL'			=> $obj->getUrl(),
				':CORPO'		=> $obj->getCorpo(),
				':STATUS'		=> $obj->getStatus(),
				':DATAHORA'		=> $obj->getDatahora(),	
				':CODIGO'		=> $obj->getCodigo()
			);

			$con->executeBin($sql_req, $args_req);
			
			//caso haja anexo, grava.
			if ( !empty($anexo_id) ) {
				self::gravarAnexo($obj->getId(), $anexo_id, $anexo_desc, $con);
			}

            if ( !$conexao ) { 
                $con->commit();
            }
			$resposta = array('0' => 'sucesso', '1' => 'Enviado com sucesso.');
			
		} catch(ValidationException $e1) {
			
			$con->rollback();
			$resposta = array('0' => 'erro', '1' => $e1->getMessage());

		} catch(Exception $e2) {

			$con->rollback();
			$resposta = array('0' => 'erro', '1' => $e2->getMessage());

		}

		return $resposta;
	}	
    
    public static function gravar2($obj)
	{
        $con = new _Conexao();
		
		try {
           
            $sql = '
			insert into TBEMAIL
					(EMAIL,  USUARIO_ID,  ASSUNTO,  CORPO,  STATUS,  DATAHORA)
				values 
					(:EMAIL, :USUARIO_ID, :ASSUNTO, :CORPO, :STATUS, :DATAHORA)
            ';

            $query = $con->pdo->prepare($sql);
            
            $query->bindParam(':EMAIL',         $obj['Email']);
            $query->bindParam(':USUARIO_ID',    $obj['UsuarioId']);
            $query->bindParam(':ASSUNTO',       $obj['Assunto']);
            $query->bindParam(':CORPO',         $obj['Corpo'],PDO::PARAM_LOB);
            $query->bindParam(':STATUS',        $obj['Status']);
            $query->bindParam(':DATAHORA',      $obj['Datahora']);

            $query->execute();

			$resposta = array('0' => 'sucesso', '1' => 'Enviado com sucesso.');
            
            $con->commit();
		} catch(Exception $e) {
            $con->rollback();
			$resposta = array('0' => 'erro', '1' => $e->getMessage());
		}
        
		return $resposta;
	}	
	
	/**
	 * Indicar anexo do e-mail.
	 * 
	 * @param int $email_id
	 * @param int $anexo_id
	 * @param string $anexo_desc
	 * @param _Conexao $con
	 */
	public static function gravarAnexo($email_id, $anexo_id, $anexo_desc, _Conexao $con) {
		
		$sql_anx = "
			INSERT INTO TBEMAIL_ANEXO
				(EMAIL_ID, ARQUIVO_ID, DESCRICAO)
			VALUES
				(:EMAIL_ID, :ARQUIVO_ID, :DESCRICAO)
		";

		$args_anx = array(
			':EMAIL_ID'		=> $email_id,
			':ARQUIVO_ID'	=> $anexo_id,
			':DESCRICAO'	=> $anexo_desc
		);

		$con->execute($sql_anx, $args_anx);
		
	}
}

?>