<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13050DAO;

/**
 * 13050 - Autorização de Ordem de Compra
 */
class _13050
{
    private $filtro;
    private $first;
    private $skip;
    private $id;
    private $status;
    private $autorizacao; 
    private $enviada; 
    private $itens = false;
    private $historico = false;
	private $fornecedor_id;
    private $data_inicial;
    private $data_final;
    private $pendencia;
    private $item_pendente;


    /**
     * Filtra itens do objeto
     * @return string
     */
    public function getFiltro() {
        return $this->filtro;
    }
    
    /**
     * Quantos registros retorna a consulta <br />
     * Default 30 
     * @return int
     */
    public function getFirst() {
        return $this->first;
    }

    /**
     * Quantos registro a consulta deve pular
     * @return int
     */
    public function getSkip() {
        return $this->skip;
    }
 
    /**
     * Id da OC
     * @return string
     */
    public function getId() {
        return $this->id;
    }
 
    /**
     * Status da OC
     * '1' = Ativo | '2' = Inativo
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Autorização da OC <br />
     * '1' = Pendente de Autorização | '2' = Autorizaco | '3' = Recuzado
     * @return string
     */
    public function getAutorizacao() {
        return $this->autorizacao;
    }
    
    /**
     * OC Enviada <br />
     * '1' = Enviada | '0' = Não Enviada
     * @return string
     */
    public function getEnviada() {
        return $this->enviada;
    }
    
    /**
     * Retorna o itens da OC<br/>
     * Default = false | Retornar itens = true 
     * @return bool
     */
    public function getItens() {
        return $this->itens;
    }
    
    /**
     * Retorna o historico de autorização da OC<br/>
     * Default = false | Retornar itens = true 
     * @return bool
     */
    public function getHistorico() {
        return $this->historico;
    }
	
	/**
     * Retorna o id do fornecedor.
     * @return int
     */
    public function getFornecedorId() {
        return $this->fornecedor_id;
    }
	
	/**
     * Retorna a data inicial.
     * @return string Data no formato aaaa.mm.dd
     */
    public function getDataInicial() {
        return $this->data_inicial;
    }
	
	/**
     * Retorna a data final.
     * @return string Data no formato aaaa.mm.dd
     */
    public function getDataFinal() {
        return $this->data_final;
    }
	
	/**
     * Retorna se existe somente pendencias de autorização do usuário.
     * @return boolean
     */
    public function getPendencia() {
        return $this->pendencia;
    }
    
	/**
     * Difine se será exibido somente oc com itens pendentes de autorizacao.
     * @return boolean
     */
    public function getItemPendente() {
        return $this->item_pendente;
    }
    
    /**
     * Filtra itens do objeto
     * @param string $filtro
     */
    public function setFiltro($filtro) {
        $this->filtro = $filtro;
    }
    
    /**
     * Quantos registros retorna a consulta<br />
     * Default 30 
     * @param int $first
     */
    public function setFirst($first) {
        $this->first = $first;
    }

    /**
     * Quantos registro a consulta deve pular
     * @param type $skip
     */
    public function setSkip($skip) {
        $this->skip = $skip;
    }

    /**
     * Id da OC
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Status da OC <br />
     * '1' = Ativo | '2' = Inativo
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Autorização da OC <br />
     * '1' = Pendente de Autorização | '2' = Autorizaco | '3' = Recuzado
     * @param string $autorizacao
     */    
    public function setAutorizacao($autorizacao) {
        $this->autorizacao = $autorizacao;
    }

    /**
     * OC Enviada<br />
     * '1' = Enviada | '0' = Não Enviada
     * @param string $enviada
     */    
    public function setEnviada($enviada) {
        $this->enviada = $enviada;
    }

    /**
     * Retorna o itens da OC<br/>
     * Default = false | Retornar itens = true 
     * @param bool $itens
     */    
    public function setItens($itens) {
        $this->itens = $itens;
    }

    /**
     * Retorna o historico de autorização da OC<br/>
     * Default = false | Retornar itens = true 
     * @param bool $historico
     */    
    public function setHistorico($historico) {
        $this->historico = $historico;
    }
	
	/**
     * Define o id do fornecedor.
	 * @param int $fornecedor_id
     */
    public function setFornecedorId($fornecedor_id) {
        $this->fornecedor_id = $fornecedor_id;
    }
	
	/**
     * Define a data inicial.
	 * @param string $data_inicial Formato aaaa.mm.dd
     */
    public function setDataInicial($data_inicial) {
        $this->data_inicial = $data_inicial;
    }
	
	/**
     * Define a data final.
	 * @param string $data_final Formato aaaa.mm.dd
     */
    public function setDataFinal($data_final) {
        $this->data_final = $data_final;
    }
	
	/**
     * Define se existe somente pendencias de autorização do usuário.
	 * @param boolean
     */
    public function setPendencia($pendencia) {
        $this->pendencia = $pendencia;
    }
	
	/**
     * Difine se será exibido somente oc com itens pendentes de autorizacao.
	 * @param boolean
     */
    public function setItemPedente($item_pendente) {
        $this->item_pendente = $item_pendente;
    }

	/**
	 * Listar Ordens de Compra pendentes de autorização
	 * @param _13050 $obj
	 * @return array
	 */
	public static function listar(_13050 $obj = null) {
		return _13050DAO::listar($obj);
	}    
    
	/**
	 * Gerar PDF de OC autorizada.
	 */
	public static function infoPdfOc(_13050 $obj) {
		return _13050DAO::infoPdfOc($obj);
	}
    
	/**
     *
     * Autoriza/Nega OC
     * @param int $id
     */
    public static function autorizar($param = []) {
        return _13050DAO::autorizar($param);
    }

    /**
     *
     * Autoriza/Nega OC
     * @param int $id
     */
    public static function ocPendencia2($con, $id) {
        return _13050DAO::ocPendencia2($con, $id);
    }
    
	/**
	 *
	 * Settar OC como enviada
	 * @param int $id
	 */
	public static function enviarOc($id) {
		return _13050DAO::enviarOc($id);
	}

}