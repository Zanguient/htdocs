<?php

namespace App\Models\DTO\Financeiro;

use App\Models\DAO\Financeiro\_20020DAO;

class _20020
{
	private $id;
	private $descricao;
    private $nome_fantasia;
	private $fone;
	private $email;
	private $contato;
    private $cidade;
    private $uf;
    private $cnpj;
    private $filtro = null;
    private $status = null;
    
	/**
	 * Listar Formas de Pagamento
     * Esta função aceita paramentros para filtragem
	 *
	 * @param _20020 $obj
	 * @return array
	 */
	public static function listar(_20020 $obj = null) {
		return _20020DAO::listar($obj);
	}    
    
    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getNomeFantasia() {
        return $this->nome_fantasia;
    }

    public function getFone() {
        return $this->fone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getContato() {
        return $this->contato;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getUf() {
        return $this->uf;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function getFiltro() {
        return $this->filtro;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }

    public function setNomeFantasia($nome_fantasia) {
        $this->nome_fantasia = $nome_fantasia;
        return $this;
    }

    public function setFone($fone) {
        $this->fone = $fone;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setContato($contato) {
        $this->contato = $contato;
        return $this;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
        return $this;
    }

    public function setUf($uf) {
        $this->uf = $uf;
        return $this;
    }

    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function setFiltro($filtro) {
        $this->filtro = $filtro;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }


}