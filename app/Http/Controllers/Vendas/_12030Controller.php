<?php

namespace App\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Vendas\_12030;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

class _12030Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12030';
		 
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Fixando dados');   
        
        $dados = $request->all();
        
        $Ret = _12030::gravar($dados);
        
        return $Ret;
    }
    
    public function show(Request $request)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizando item'); 
        
        $dados = $request->all();
        
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/_12030/','',$url);
        
        $Ret = _12030::show($url);
        
        return view('vendas.show',['dados' => $Ret, 'permissaoMenu' => $permissaoMenu]);
    }
    
    public function delete(Request $request)
    {
        _11010::permissaoMenu($this->menu,'EXCLUIR','Excluindo item'); 
        
        $dados = $request->all();
        
        $Ret = _12030::delete($dados['ID']);
        
        $this->index($request);
    }
    
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Listando aprovação de preços');    
        
        $Ret = _12030::index($request->all());
        
    	return view('vendas.index',['dados' => $Ret, 'permissaoMenu' => $permissaoMenu]);
    }
    
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Create aprovação de preços');    
    	
    	return view('vendas.create',['permissaoMenu' => $permissaoMenu]);
    }
    
    /**
     * Filtrar lista.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtraObj(Request $request) {
        $protocol = protocol();

        $dados = _12030::index2($request->all());
        
        $res = '';
        
        foreach ($dados as $dado) {
            $res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_12030/' . $dado->ID . '">';
            $res .= '    <td>'.$dado->DATA.'</td>';
            $res .= '    <td>'.$dado->CLIENTE.'</td>';
            $res .= '    <td>'.$dado->MODELO.'</td>';
            $res .= '</tr>';
        }
        
        return Response::json($res);
    }
    
    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScroll(Request $request) {
        if ($request->ajax()) {

        }
    }
}
