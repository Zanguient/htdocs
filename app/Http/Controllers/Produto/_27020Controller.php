<?php

namespace app\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Produto\_27020;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _27020 - Cadastro de Modelos
 */
class _27020Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'produto/_27020';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;

	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'produto._27020.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }
	
	/**
	 * Retorna view de consultar modelo por cliente.
	 * @return view
	 */
	public function viewModeloPorCliente() {
		return view('produto._27020.modal-consultar-por-cliente', ['menu' => $this->menu]);
	}

	/**
	 * Consultar modelo.
	 * @return json
	 */
	public function consultarModelo() {
		
		$this->con = new _Conexao();

		try {			
			
			$dado = _27020::consultarModelo($this->con);
			
			$this->con->commit();

			return Response::json($dado);

		} catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

	}
	
	/**
	 * Consultar modelo por cliente.
	 * @param Request $request
	 * @return json
	 */
	public function consultarModeloPorCliente(Request $request) {
		
		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			// se o usuário (CLIENTE) possuir cliente vinculado, utiliza este cliente.
	        if ( !empty(\Auth::user()->CLIENTE_ID) )
	            $param->CLIENTE_ID = \Auth::user()->CLIENTE_ID;

	        // Se o usuário (CLIENTE) não possui cliente vinculado.
	        if ( $param->CLIENTE_ID === null )
	            log_erro('Você precisa ter o ID de Cliente vinculado a seu usuário.<br/>Entre em contato com o administrador do sistema.');

	        // Se o usuário (REPRESENTANTE OU SETOR COMERCIAL) não escolheu um cliente.
	        else if ( $param->CLIENTE_ID == 0 )
	            log_erro('Selecione um cliente.');

			
			$dado = _27020::consultarModeloPorCliente($param, $this->con);

			$this->verArquivo($dado);
			
			$this->con->commit();

			return Response::json($dado);

		} catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

	}

	/**
	 * Visualizar amostra do modelo.
	 * @param json $param
	 * @return string
	 */
	public function verArquivo($param) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'modelo/';

			foreach ($param as $p) {

				$arquivo = _27020::verArquivo($p, $conFile);

				if (!empty($arquivo)) {

					$novoNome = $arquivo[0]->ARQUIVO;
		            $conteudo = $arquivo[0]->CONTEUDO;
		            $tamanho  = $arquivo[0]->TAMANHO;
		            $extensao = $arquivo[0]->EXTENSAO;
		            
		            $novoNome = $caminho . \Auth::user()->CODIGO . '-' . $p->MODELO_CODIGO;
		            
		            $novoarquivo = fopen($novoNome, "a+");
		            fwrite($novoarquivo, $conteudo);
		            fclose($novoarquivo);
		        }
			}

			$conFile->commit();

		} catch (Exception $e) {
            $conFile->rollback();
            throw $e;
        }
	}

	/**
     * Excluir arquivo de amostra do diretório temporário.
     * @param Request $request
     * @return json
     */
    public function excluirArquivo(Request $request) {

        //_11010::permissaoMenu($this->menu, 'EXCLUIR', 'Excluir Arquivo Local');

        $arquivo = $request->arquivo;
        $ret = 0;
        $dir = env('APP_TEMP', '') . 'modelo/';

        if (file_exists($dir . $arquivo))
            $ret = unlink($dir . $arquivo); 
        else
            $ret = 3;

        $var = [
            "ret" => $ret
        ];

        return Response::json($var);
    }

    /**
     * Excluir arquivos de amostras por usuário do diretório temporário.
     * @param Request $request
     * @return json
     */
    public function excluirArquivoPorUsuario(Request $request) {

        try {

	        $dir = env('APP_TEMP', '') . 'modelo/';
	        $arq = \Auth::user()->CODIGO . '-*';

	        foreach (glob($dir.$arq) as $arquivo) {

	        	if (is_file($arquivo))
	        		unlink($arquivo);
	        }
	        
	    } catch (Exception $e) {
            throw $e;
        }
    }

}