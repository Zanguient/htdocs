<?php

namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\EmailDAO;
use App\Models\Conexao\_Conexao;

class Email
{
	private $id;
	private $email;
	private $usuario_id;
	private $mensagem;
	private $url;
	private $assunto;
	private $corpo;
	private $status;
	private $datahora;
	private $datahora_envio;
	private $Codigo;
		
	public function getCodigo() {
		return $this->Codigo;
	}
	public function setCodigo($Codigo) {
		$this->Codigo = $Codigo;
	}

	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}

	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
	}
	public function getUsuarioId() {
		return $this->usuario_id;
	}
	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}
	public function getMensagem() {
		return $this->mensagem;
	}
	public function setMensagem($mensagem) {
		$this->mensagem = htmlspecialchars_decode(htmlentities($mensagem, ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES);
	}
	public function getUrl() {
		return $this->url;
	}
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getAssunto() {
		return $this->assunto;
	}
	public function setAssunto($assunto) {
		$this->assunto = $assunto;
	}
	public function getCorpo() {
		return $this->corpo;
	}
	public function setCorpo($corpo) {
		$this->corpo = $corpo;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
	}
	public function getDatahora() {
		return $this->datahora;
	}
	public function setDatahora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDatahoraEnvio() {
		return $this->datahora_envio;
	}
	public function setDatahoraEnvio($datahora_envio) {
		$this->datahora_envio = $datahora_envio;
	}
	
	/**
	 * Envia email via Ajax.
	 *
	 * @param Email $obj
     * @param _Conexao $con | Caso este parametro não seja passado será instânciado uma nova _Conexao
	 * @param int $anexo_id Opcional
	 * @param string $anexo_desc Opcional
     * @return array
	 */
	public static function gravar(Email $obj, _Conexao $con = null, $anexo_id = null, $anexo_desc = null) {
		return EmailDAO::gravar($obj, $con, $anexo_id, $anexo_desc);
	}
    
    public static function gravar2($obj) {
		return EmailDAO::gravar2($obj);
	}
	
	/**
	 * Gerar id do email.
	 * 
	 * @return int
	 */
	public static function gerarId() {
		return EmailDAO::gerarId();
	}
}

?>