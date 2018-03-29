<?php

namespace App\Models\DTO\Auth;

use App\Models\DAO\Auth\CustomPasswordDAO;

class CustomPassword {

	public static function verificarEmailExiste($param, $con) {

		return CustomPasswordDAO::verificarEmailExiste($param, $con);
	}

	public static function gravarEmailRecuperacao($param, $con) {

		return CustomPasswordDAO::gravarEmailRecuperacao($param, $con);
	}
	
	public static function verificarToken($param, $con) {

		return CustomPasswordDAO::verificarToken($param, $con);
	}

	public static function gravarNovaSenha($param, $con) {

		return CustomPasswordDAO::gravarNovaSenha($param, $con);
	}
}
