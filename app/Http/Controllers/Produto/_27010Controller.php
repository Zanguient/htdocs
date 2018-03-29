<?php

namespace app\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Produto\_27010;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _27010 - Cadastro de Familias de Produto
 */
class _27010Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'produto/_27010';
    public $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'produto._27010.index', [
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
    
    public function familiaModeloAlocacao(Request $request) {
        $this->con = new _Conexao;
        
        try {
            $res = _27010::selectFamiliaModeloAlocacao($request->all(),$this->con);
            $this->con->commit();
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }

}