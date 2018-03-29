<?php

namespace app\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Vendas\_12100;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Helpers\Helpers;
use App\Models\DTO\Vendas\_12040;
use Illuminate\Support\Facades\Auth;
/**
 * Controller do objeto _12100 - NOTAS FISCAIS
 */
class _12100Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12100';
	
	public function Consultar(Request $request){

           
    }

    public function modeloEtiqueta(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::modeloEtiqueta($filtro,$con);

        return  Response::json($ret);        
    }

    public function DadosEtiqueta(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::DadosEtiqueta($filtro,$con);

        return  Response::json($ret);        
    }

    public function consultarItens(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::consultarItens($filtro,$con);

        $xml = [ 'ID' => 0];
        if(count($ret) > 0){
            $xml = _12100::getArquivo($filtro);
        }


        return  Response::json(['RETORNO' => $ret, 'XML' => $xml]);        
    }

    public function pdf(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        return view(
            'vendas._12100.modal.pdf', [
            'FILE' => $filtro['FILE'],
            'NOME' => $filtro['NOME'],
            'DIR'  => $filtro['DIR'],
            'CAMINHO'  => $filtro['CAMINHO']
        ]);          
    }

    public function consultarNotas(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::consultarNotas($filtro,$con);

        return  Response::json(['RETORNO' => $ret]);      
    }

    public function consultarRepresentante(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::consultarRepresentante($filtro,$con);

        return  Response::json($ret);      
    }

    public function consultarClientePorRepresentante(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _12100::consultarClientePorRepresentante($filtro,$con);

        return  Response::json($ret);      
    }

    
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);

        $con = new _Conexao();
        
        $param = (object)[];
        $param->USUARIO_CODIGO = Auth::user()->CODIGO;
        $rep = _12040::verificarUsuarioEhRepresentante($param, $con);

        $representante = 0;

        if (count($rep)){
            $representante   = $rep[0]->REPRESENTANTE_CODIGO;
        }

		return view(
            'vendas._12100.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'representante2'=> $representante
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