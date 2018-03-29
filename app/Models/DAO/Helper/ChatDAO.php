<?php

namespace App\Models\DAO\Helper;

use App\Models\DTO\Helper\Chat;

/**
 * DAO do objeto Chat.
 */
class ChatDAO {

	public static function gravar($dado, $con) {

		$sql = "
			INSERT INTO TBCHAT (
				REMETENTE_ID, 
				DESTINATARIO_ID, 
				MENSAGEM
			)
			VALUES(
				:REMETENTE_ID, 
				:DESTINATARIO_ID, 
				:MENSAGEM
			)
		";

		$args = [
			':REMETENTE_ID' 	=> $dado->REMETENTE_ID,
			':DESTINATARIO_ID' 	=> $dado->DESTINATARIO_ID,
			':MENSAGEM'			=> $dado->MENSAGEM
		];

		$con->execute($sql, $args);
	}

	public static function consultarHistoricoConversa($param, $con) {

		$sql = "
			SELECT FIRST :FIRST SKIP :SKIP
				C.ID,
			    C.REMETENTE_ID,
			    C.DESTINATARIO_ID,
			    C.MENSAGEM,
			    C.DATAHORA
			    
			FROM
				TBCHAT C
			    
			WHERE
				(C.REMETENTE_ID = :USUARIO_ID_ATUAL AND C.DESTINATARIO_ID = :USUARIO_ID_SELEC)
			OR  (C.REMETENTE_ID = :USUARIO_ID_SELEC_1 AND C.DESTINATARIO_ID = :USUARIO_ID_ATUAL_1)
			AND C.STATUSEXCLUSAO = '0'

			ORDER BY
				C.ID DESC
		";

		$args = [
			':FIRST'				=> $param->FIRST,
			':SKIP'					=> $param->SKIP,
			':USUARIO_ID_ATUAL' 	=> $param->USUARIO_ID_ATUAL,
			':USUARIO_ID_SELEC' 	=> $param->USUARIO_ID_SELEC,
			':USUARIO_ID_SELEC_1' 	=> $param->USUARIO_ID_SELEC,
			':USUARIO_ID_ATUAL_1' 	=> $param->USUARIO_ID_ATUAL
		];

		return $con->query($sql, $args);
	}
}