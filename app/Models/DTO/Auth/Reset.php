<?php

namespace App\Models\DTO\Auth;

use App\Models\DAO\Auth\ResetDAO;

class Reset {
	
	private $usuario;
	private $email;
	private $cnpj;
	private $senha;
	private $senha_antiga;
	
	public function getUsuario() {
		return $this->usuario;
	}

	public function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getCnpj() {
		return $this->cnpj;
	}

	public function setCnpj($cnpj) {
		$this->cnpj = $cnpj;
	}
	
	public function getSenha() {
		return $this->senha;
	}

	public function setSenha($senha) {
		$this->senha = $senha;
	}
	
	public function getSenhaAntiga() {
		return $this->senha_antiga;
	}

	public function setSenhaAntiga($senha_antiga) {
		$this->senha_antiga = $senha_antiga;
	}

	public static function verificarUsuario(Reset $obj) {
		return ResetDAO::verificarUsuario($obj);
	}
	
	public static function alterarSenha(Reset $obj) {
		return ResetDAO::alterarSenha($obj);
	}
}
