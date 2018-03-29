<?php

namespace app\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Produto\_27050;
use App\Models\Conexao\_Conexao;
use App\Models\DAO\Produto\_27050DAO;

/**
 * Controller do objeto _27050 - Produto.
 */
class _27050Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'produto/_27050';

    /**
     * Conexão.
     * @var _Conexao
     */
	private $con = null;


	/**
	 * Consultar produto por modelo e cor.
	 * @param Request $request
	 * @return array
	 */
	public function consultarPorModeloECor(Request $request) {

		$this->con = new _Conexao();

		try {

			if ($request->modeloId 	== '') 
				log_erro('Escolha o modelo para o produto.');
			if ($request->corId 	== '') 
				log_erro('Escolha a cor para o produto.');

			$filtro = [
				'MODELO_ID' => $request->modeloId,
				'COR_ID' 	=> $request->corId
			];

			$dados = _27050::consultarPorModeloECor($filtro, $this->con);

			$this->con->commit();

			if ( empty($dados) ) 
				log_erro('Produto não existe.');

            return Response::json($dados[0]);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

	}
    
    public function consultaJson(Request $request ) {
        $ret = _27050DAO::filtrar2($request->all());
        
        return response()->json($ret);
    }

}
