<?php

namespace app\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Produto\_27030;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _27030 - Cadastro de Cores
 */
class _27030Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'produto/_27030';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;

	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'produto._27030.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {    	
        //
    }
    
    public function show($id)
    {
    	//
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request, $id)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }

    /**
     * View para consultar cor.
     * @return view
     */
    public function viewConsultarCor() {

        return view('produto._27030.modal-consultar-cor', ['menu' => $this->menu]);
    }

    /**
     * View para consultar cor por modelo.
     * @return view
     */
    public function viewCorPorModelo() {

        $pu218 = _11010::controle(218);

    	return view('produto._27030.modal-consultar-por-modelo', ['menu' => $this->menu, 'pu218' => $pu218]);
    }

    /**
     * Consultar cores.
     * @param Request $request
     * @return view
     */
    public function consultarCor() {

        $this->con = new _Conexao();

        try {
            
            $dado = _27030::consultarCor($this->con);
        
            $this->con->commit();

            return Response::json($dado);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }   
    }
	
	/**
	 * Consultar cores por modelo ou todas.
	 * @param Request $request
	 * @return view
	 */
	public function consultarCorPorModelo(Request $request) {

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


            $param->MODELO_ID        = empty($param->MODELO_ID)       ? 0 : $param->MODELO_ID;
            $param->RETORNA_TODOS    = empty($param->RETORNA_TODOS)   ? 0 : $param->RETORNA_TODOS;

			// $obj = new _27030();
			
			// $obj->setModeloId(empty($request->modeloId)	? 0	: $request->modeloId);
			// $obj->setRetornaTodos(empty($request->retornaTodos) ? 0 : $request->retornaTodos);
            // $obj->setClienteId(\Auth::user()->CLIENTE_ID);
			
			$dado = _27030::consultarCorPorModelo($param, $this->con);
		
			$this->con->commit();

			return Response::json($dado);

		} catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
		
	}

}