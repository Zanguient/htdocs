<?php

namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\ChatDAO;

class Chat {

	public static function gravar($dado, $con) {
		return ChatDAO::gravar($dado, $con);
	}

	public static function consultarHistoricoConversa($param, $con) {
		return ChatDAO::consultarHistoricoConversa($param, $con);
	}
}