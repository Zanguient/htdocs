<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13040DAO;
use App\Models\DTO\Compras\_13040;
use App\Http\Controllers\Compras\_13040Controller;
use App\Models\Conexao\_Conexao;

class _13040
{
	private $oc;
	private $estabelecimento_id;
	private $fornecedor_id;
	private $transportadora_id;
	private $frete;
	private $valor_frete;
	private $pagamento_forma;
	private $pagamento_condicao;
	private $usuario_id;
    private $item_fornecedor_id;
	private $sequencia; 
	private $produto_codigo;
	private $tamanho;
    private $orcamento_id;
	private $quantidade;
	private $ipi;
	private $valor;
	private $data_entrega;
	private $data_saida;
	private $controle;
	private $desconto;
	private $ccusto;
	private $operacao_codigo;
	private $conta_contabil;
    private $oc_nivel;
    private $referencia;
    private $referencia_id;

    public function getOc() {
		return $this->oc;
	}

	public function getEstabelecimentoId() {
		return $this->estabelecimento_id;
	}

	public function getFornecedorId() {
		return $this->fornecedor_id;
	}

	public function getTransportadoraId() {
		return $this->transportadora_id;
	}

	public function getFrete() {
		return $this->frete;
	}

	public function getValorFrete() {
		return $this->valor_frete;
	}

	public function getPagamentoForma() {
		return $this->pagamento_forma;
	}

	public function getPagamentoCondicao() {
		return $this->pagamento_condicao;
	}

	public function getUsuarioId() {
		return $this->usuario_id;
	}

	public function getItemFornecedorId() {
		return $this->item_fornecedor_id;
	}


	public function getSequencia() {
		return $this->sequencia;
	}

	public function getProdutoCodigo() {
		return $this->produto_codigo;
	}

	public function getTamanho() {
		return $this->tamanho;
	}


	public function getOrcamentoId() {
		return $this->orcamento_id;
	}

	public function getQuantidade() {
		return $this->quantidade;
	}

	public function getIpi() {
		return $this->ipi;
	}

	public function getValor() {
		return $this->valor;
	}

	public function getDataEntrega() {
		return $this->data_entrega;
	}
	
	public function getDataSaida() {
		return $this->data_saida;
	}

	public function getControle() {
		return $this->controle;
	}

	public function getDesconto() {
		return $this->desconto;
	}

	public function getCcusto() {
		return $this->ccusto;
	}

	public function getOperacaoCodigo() {
		return $this->operacao_codigo;
	}

	public function getContaContabil() {
		return $this->conta_contabil;
	}

	public function getOcNivel() {
		return $this->oc_nivel;
	}

	public function getReferencia() {
		return $this->referencia;
	}

	public function getReferenciaId() {
		return $this->referencia_id;
	}

	public function setOc($oc) {
		$this->oc[] = $oc;
	}

	public function setEstabelecimentoId($estabelecimento_id) {
		$this->estabelecimento_id[] = $estabelecimento_id;
	}

	public function setFornecedorId($fornecedor_id) {
		$this->fornecedor_id[] = $fornecedor_id;
	}

	public function setTransportadoraId($transportadora_id) {
		$this->transportadora_id[] = $transportadora_id;
	}

	public function setFrete($frete) {
		$this->frete[] = $frete;
	}

	public function setValorFrete($valor_frete) {
		$this->valor_frete[] = $valor_frete;
	}

	public function setPagamentoForma($pagamento_forma) {
		$this->pagamento_forma[] = $pagamento_forma;
	}

	public function setPagamentoCondicao($pagamento_condicao) {
		$this->pagamento_condicao[] = $pagamento_condicao;
	}

	public function setUsuarioId($usuario_id) {
		$this->usuario_id[] = $usuario_id;
	}

	public function setItemFornecedorId($item_fornecedor_id) {
		$this->item_fornecedor_id[] = $item_fornecedor_id;
	}

	public function setSequencia($sequencia) {
		$this->sequencia[] = $sequencia;
	}

	public function setProdutoCodigo($produto_codigo) {
		$this->produto_codigo[] = $produto_codigo;
	}

	public function setTamanho($tamanho) {
		$this->tamanho[] = $tamanho;
	}

	public function setOrcamentoId($orcamento_id) {
		$this->orcamento_id[] = $orcamento_id;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade[] = $quantidade;
	}

	public function setIpi($ipi) {
		$this->ipi[] = $ipi;
	}

	public function setValor($valor) {
		$this->valor[] = $valor;
	}

	public function setDataEntrega($data_entrega) {
		$this->data_entrega[] = $data_entrega;
	}
	
	public function setDataSaida($data_saida) {
		$this->data_saida[] = $data_saida;
	}

	public function setControle($controle) {
		$this->controle[] = $controle;
	}

	public function setDesconto($desconto) {
		$this->desconto[] = $desconto;
	}

	public function setCcusto($ccusto) {
		$this->ccusto[] = $ccusto;
	}

	public function setOperacaoCodigo($operacao_codigo) {
		$this->operacao_codigo[] = $operacao_codigo;
	}

	public function setContaContabil($conta_contabil) {
		$this->conta_contabil[] = $conta_contabil;
	}

	public function setOcNivel($oc_nivel) {
		$this->oc_nivel[] = $oc_nivel;
	}

	public function setReferencia($referencia) {
		$this->referencia[] = $referencia;
    }

	public function setReferenciaId($referencia_id) {
		$this->referencia_id[] = $referencia_id;
    }
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @param int $licitacao_id
	 * @return array _13040DAO::exibir() 
	 */
	public static function exibirProduto($licitacao_id) {
		return _13040DAO::exibirProduto($licitacao_id);
	}
	
	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 * @param _13040 $obj
	 */
	public static function gravar(_13040 $obj) {
		return _13040DAO::gravar($obj);
	}	
    
    /**
     * Exibe o nível da oc
     * @param int $oc_id
     */
    public static function exibirNivelOc($oc_id) {
        return _13040DAO::exibirNivelOc($oc_id);
    }
    
    /**
     * Exibe o código do usuário de acordo com o nível
     * @param int $nivel_usuario
     * @return int
     */
    public static function exibirNivelUsuario($nivel_usuario) {
        return _13040DAO::exibirNivelUsuario($nivel_usuario);
    }
    
    public static function exibirRequisicao($id) {
        return _13040DAO::exibirRequisicao($id);
    }

    /**
	 * Gerar id do objeto.
	 * @return integer
	 */
	public static function gerarId() {
		return _13040DAO::gerarId();
	}
 
    /**
     * 
     * @param _13040 $objeto
     * @param _Conexao $con
     * @return type
     */
    public static function emailAutorizacao(_13040 $objeto, _Conexao $con) {
        return _13040Controller::emailAutorizacao($objeto, $con);
    }    
}