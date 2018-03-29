<?php

namespace app\Http\Controllers\Custo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Custo\_31010;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _31010 - Custos Gerenciais
 */
class _31010Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'custo/_31010';
    
    public function gravarSimulacao(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::gravarSimulacao($filtro,$con);

        return  Response::json($ret);      
    }

    public function excluirSimulacao(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::excluirSimulacao($filtro,$con);

        return  Response::json($ret);      
    }

    

    public function Simulacao(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::Simulacao($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarSimulacao(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarSimulacao($filtro,$con);

        return  Response::json($ret);      
    }    

    public function ConsultarDetalheDespesa(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarDetalheDespesa($filtro,$con);

        return  Response::json($ret);      
    }

    public function FaturamentoFamilia(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::FaturamentoFamilia($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarPrecoVenda(Request $request){

        $con    = new _Conexao();
        $filtro = $request->all();

        $ret    = _31010::ConsultarPrecoVenda($filtro,$con);

        return  Response::json($ret);      
    }

    public function consultarProduto(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::consultarProduto($filtro,$con);

        return  Response::json($ret);      
    }

    public function consultarDensidade(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::consultarDensidade($filtro,$con);

        return  Response::json($ret);      
    }
    

    public function consultarIncentivo(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::consultarIncentivo($filtro,$con);

        return  Response::json($ret);      
    }
    
    public function custoPadrao(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::custoPadrao($filtro,$con);

        return  Response::json($ret);      
    }

    public function custoPadraoItem(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::custoPadraoItem($filtro,$con);

        return  Response::json($ret);      
    }

    public function DetalharDespesa(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::DetalharDespesa($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarCor(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarCor($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarDespesas(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarDespesas($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarEstacoes(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarEstacoes($filtro,$con);

        return  Response::json($ret);      
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarAbsorcao(Request $request){
        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarAbsorcao($filtro,$con);

        return  Response::json($ret);  
    }

    /**
     * Consultar Detalhamento Absorcao Proprio
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarProprio(Request $request){
        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarProprio($filtro,$con);

        return  Response::json($ret);  
    }

    /**
     * Consultar Configuracoes de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarConfiguracao(Request $request){
        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarConfiguracao($filtro,$con);

        return  Response::json($ret);  
    }

    public function ConsultarTempo(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarTempo($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarMaoDeObra(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarMaoDeObra($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarFicha(Request $request){

        set_time_limit(300);

        $con = new _Conexao();
        $filtro = $request->all();

        $troca = $filtro['FLAG_TROCA'];

        if($troca == 0){
            $ret = _31010::ConsultarFicha($filtro,$con);
        }else{
            $ret = _31010::ConsultarFicha2($filtro,$con);
        }

        return  Response::json($ret);      
    }

    public function ConsultarFichaTempo(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarFichaTempo($filtro,$con);

        return  Response::json($ret);      
    }    

    public function ConsultarPerfil(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarPerfil($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarTamanho(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarTamanho($filtro,$con);

        return  Response::json($ret);      
    }

    public function ConsultarTamanho2(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::ConsultarTamanho2($filtro,$con);

        return  Response::json($ret);      
    }

	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _31010::Consultar($filtro,$con);

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);

		return view(
            'custo._31010.index', [
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