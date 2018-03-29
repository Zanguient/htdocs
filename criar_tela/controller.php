<?php

namespace app\Http\Controllers\#Grupos#;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\#Grupos#\#TelaNO#;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto #TelaNO# - #Titulo#
 */
class #TelaNO#Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = '#SGrupos#/#TelaNO#';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            '#SGrupos#.#TelaNO#.index', [
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

    public function consultar(Request $request){

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            $param->ID = isset($param->ID) ? $param->ID : 0;
            
            $ret = #TelaNO#::consultar($param, $this->con);

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}