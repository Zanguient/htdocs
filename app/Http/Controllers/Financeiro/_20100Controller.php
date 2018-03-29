<?php

namespace app\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Financeiro\_20100;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _20100 - Relatorio de Extrato de Caixa/Bancos
 */
class _20100Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'financeiro/_20100';

	public function ConsultarBanco(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _20100::ConsultarBanco($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarExtrato(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $extrato = _20100::ConsultarExtrato($filtro,$con);
        $detalhe = _20100::ConsultarDetalhe($filtro,$con);

        $ret = [
           'EXTRATO' => $extrato,
           'DETALHE' => $detalhe
        ];

        return  Response::json($ret);      
    }



    public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _20100::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'financeiro._20100.index', [
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

}