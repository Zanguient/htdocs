<?php
namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\ArquivoDAO;
use App\Models\Conexao\_Conexao;

class Arquivo
{
	
	private $id;
	private $vinculo;
	private $sequencia;
	private $tabela;
    private $tipo;
	private $tamanho;
	private $tmp_name;
	private $nome;
	private $conteudo;
	private $usuario_id;
	private $data;

    public function getId() {
		return $this->id;
	}

	public function getVinculo() {
		return $this->vinculo;
	}
	
	public function getSequencia() {
		return $this->sequencia;
	}

	public function getTabela() {
		return $this->tabela;
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function getTamanho() {
		return $this->tamanho;
	}

	public function getTmpName() {
		return $this->tmp_name;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getConteudo() {
		return $this->conteudo;
	}
	
	public function getUsuarioId() {
		return $this->usuario_id;
	}
	
	public function getData() {
		return $this->data;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setVinculo($vinculo) {
		$this->vinculo = $vinculo;
	}
	
	public function setSequencia($sequencia) {
		$this->sequencia = $sequencia;
	}

	public function setTabela($tabela) {
		$this->tabela = $tabela;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	public function setTamanho($tamanho) {
		$this->tamanho = $tamanho;
	}

	public function setTmpName($tmp_name) {
		$this->tmp_name = $tmp_name;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
	}
	
	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}

	public function setData($data) {
		$this->data = $data;
	}
	
	/**
	 * Gerar id do arquivo.
	 * 
	 * @return array
	 */
	public static function gerarIdArquivo() {
		return ArquivoDAO::gerarIdArquivo();
	}
	
	/**
	 * Gerar id de vínculo do arquivo.
	 * 
	 * @param string $tabela
	 * @return array
	 */
	public static function gerarVinculo($tabela) {
		return ArquivoDAO::gerarVinculo($tabela);
	}
	
	/**
	* Grava arquivo no banco de dados.
	*
	* @param Arquivo $obj
	*/
	public static function gravarArquivo(Arquivo $obj) {
		return ArquivoDAO::gravarArquivo($obj);
	}
	
	/**
	 * Exibe arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con_files
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function exibirArquivoObj(_Conexao $con_files, $vinc, $tabela) {
		return ArquivoDAO::exibirArquivoObj($con_files, $vinc, $tabela);
	}
    
    /**
	 * retorna o conteudo de um arquivo
	 * 
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function getFile($vinc, $tabela)  {
		return ArquivoDAO::getFile($vinc, $tabela);
	}
    
    /**
	 * gerar um conteudo de um arquivo
	 * 
	 * @param integer $vinc
	 * @param string $tabela
	 * @return array
	 */
	public static function gerarFile($id,$caminho)  {
        
        $Ret = ArquivoDAO::gerarFile($id);

        $novoNome = $Ret['nome'];
        $conteudo = $Ret['conteudo'];
        $tamanho  = $Ret['tamanho'];
        $extensao = $Ret['extensao'];

        //$temp = substr(md5(uniqid(time())), 0, 10);
        //$novoNome = $temp . $novoNome;

        $novoNome = $id.$extensao;

        $novoarquivo = fopen($caminho.$novoNome, "a+");
        fwrite($novoarquivo, $conteudo);
        fclose($novoarquivo);

        return $novoNome;
        
	}
    
	/**
	 * Atualiza vínculo de arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con
	 * @param object $obj
	 * @return array
	 */
	public static function alterarVinculoObj(_Conexao $con, $obj) {
		ArquivoDAO::alterarVinculoObj($con, $obj);
	}
	
	/**
	 * Exclui arquivo relacionado a algum objeto. <br>
	 * Função chamada diretamente por outros objetos.
	 * 
	 * @param _Conexao $con
	 * @param object $obj
	 * @return array
	 */
	public static function excluirArquivo(_Conexao $con, $obj) {
		ArquivoDAO::excluirArquivo($con, $obj);
	}
	
	/**
	 * Excluir arquivo.
	 * OBS.: Utilizado por meio do Angular.
	 * 
	 * @param integer $arquivoId
	 */
	public static function excluir($arquivoId) {
		ArquivoDAO::excluir($arquivoId);
	}
    
    
    public static function svnHead($dados, $con) {
		ArquivoDAO::svnHead($dados, $con);        
    }
    
    public static function svnBody($dados, $con) {
		ArquivoDAO::svnBody($dados, $con);        
    }
}