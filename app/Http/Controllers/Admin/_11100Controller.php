<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11100;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _11100 - Qlik Sense
 */
class _11100Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11100';

    /**
     * Código do menu
     * @var int 
     */
    private $url = 'https://gc.delfa.com.br:83';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $con = new _Conexao();

        $filter = [
            'USUARIO_ID' => \Auth::user()->CODIGO
        ];
        
        $rest = _11100::getProjetos($filter,$con);
        
        return view(
            'admin._11100.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'ret'           => $rest,
            'usuario'       => \Auth::user()->USUARIO,
            'url'           => $this->url
        ]);

        $con->query($sql,$args);  
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