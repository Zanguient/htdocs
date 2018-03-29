<?php

namespace app\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Financeiro\_20110;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _20110 - Relatorio de Extrato de Caixa/Bancos
 */
class _20110Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'financeiro/_20110';

	public function ConsultarBanco(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _20110::ConsultarBanco($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarFluxo(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $Bancos       = _20110::ConsultarBancos($filtro,$con);
        $Negociados   = _20110::ConsultarNegociados($filtro,$con);
        $Provisoes    = _20110::ConsultaProvisoes($filtro,$con);
        $ContaPagar   = _20110::ConsultarContaPagar($filtro,$con); 
        $ContaReceber = _20110::ConsultarContaReceber($filtro,$con); 
        $OrdensCompra = _20110::ConsultarOrdensCompra($filtro,$con);

        $ret = [
           'Bancos'       => $Bancos,       
           'Negociados'   => $Negociados,   
           'Provisoes'    => $Provisoes,    
           'ContaPagar'   => $ContaPagar,   
           'ContaReceber' => $ContaReceber, 
           'OrdensCompra' => $OrdensCompra 
        ];

        return  Response::json($ret);      
    }



    public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _20110::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'financeiro._20110.index', [
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