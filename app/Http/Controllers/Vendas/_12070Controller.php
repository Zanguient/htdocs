<?php

namespace app\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Vendas\_12070;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _12070 - Clientes
 */
class _12070Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12070';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'vendas._12070.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Retorna view de consultar cliente.
     * @return view
     */
    public function viewConsultarClientePorRepresentante() {
        return view('vendas._12070.modal-por-representante', ['menu' => $this->menu]);
    }

    /**
     * Consultar cliente.
     * @return json
     */
    public function consultarClientePorRepresentante(Request $request) {

        $this->con = new _Conexao();

        try {
            
            $param = json_decode(json_encode($request->all()));
            
            $dado = _12070::consultarClientePorRepresentante($param, $this->con);
            
            $this->con->commit();

            return Response::json($dado);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

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

}