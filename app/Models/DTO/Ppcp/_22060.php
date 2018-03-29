<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22060DAO;

/**
 * Gerenciamento de produção
 */
class _22060
{
	private $estabelecimento_id;
	private $gp_id;
	private $up_id;
	private $estacao_id;
	private $talao_id;
	private $remessa_id;
	private $data_inicial;
	private $data_final;
	
	public function getEstabelecimentoId() {
		return $this->estabelecimento_id;
	}

	public function getGpId() {
		return $this->gp_id;
	}

	public function getUpId() {
		return $this->up_id;
	}
	
	public function getEstacaoId() {
		return $this->estacao_id;
	}
	
	public function getTalaoId() {
		return $this->talao_id;
	}
	
	public function getRemessaId() {
		return $this->remessa_id;
	}
	
	public function getDataInicial() {
		return $this->data_inicial;
	}

	public function getDataFinal() {
		return $this->data_final;
	}

	public function setEstabelecimentoId($estabelecimento_id) {
		$this->estabelecimento_id = $estabelecimento_id;
	}

	public function setGpId($gp_id) {
		$this->gp_id = $gp_id;
	}

	public function setUpId($up_id) {
		$this->up_id = $up_id;
	}
	
	public function setEstacaoId($estacao_id) {
		$this->estacao_id = $estacao_id;
	}
	
	public function setTalaoId($talao_id) {
		$this->talao_id = $talao_id;
	}
	
	public function setRemessaId($remessa_id) {
		$this->remessa_id = $remessa_id;
	}
	
	public function setDataInicial($data_inicial) {
		$this->data_inicial = $data_inicial;
	}

	public function setDataFinal($data_final) {
		$this->data_final = $data_final;
	}
	

    public static function listar() {
        return _22060DAO::listar();
    }
		
	public static function filtrarEstabelecimento(_22060 $obj) {
        return _22060DAO::filtrarEstabelecimento($obj);
    }
	
	public static function filtrarGp(_22060 $obj) {
        return _22060DAO::filtrarGp($obj);
    }
	
	public static function filtrarUp(_22060 $obj) {
        return _22060DAO::filtrarUp($obj);
    }
	
	public static function filtrarEstacao(_22060 $obj) {
        return _22060DAO::filtrarEstacao($obj);
    }
	
	public static function filtrarTalao(_22060 $obj) {
        return _22060DAO::filtrarTalao($obj);
    }
	
	public static function filtrarTalaoDetalhe(_22060 $obj) {
        return _22060DAO::filtrarTalaoDetalhe($obj);
    }
	
    
}