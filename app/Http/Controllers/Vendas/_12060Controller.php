<?php

namespace app\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Vendas\_12060;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _12060 - Representante
 */
class _12060Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12060';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'vendas._12060.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Retorna view de consultar representante.
     * @return view
     */
    public function viewConsultarRepresentante() {
        return view('vendas._12060.modal-consultar', ['menu' => $this->menu]);
    }

    /**
     * Consultar representante.
     * @return json
     */
    public function consultarRepresentante() {
        
        $this->con = new _Conexao();

        try {
            
            $dado = _12060::consultarRepresentante($this->con);
            
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