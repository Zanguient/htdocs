<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Admin\_11050;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class _11050Controller extends Controller
{
    /**
     * Código do menu Gestão de Grupos de Produção
     * @var int 
     */
    private $menu = 'admin/_11050';
    
    public function index(Request $request)
    {        
		$permissaoMenu = _11010::permissaoMenu($this->menu);
       
		return view(
            'ppcp._11050.index', [
            'menu' => $this->menu
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

    public function show(Request $request,$id)
    {
		$permissaoMenu  = _11010::permissaoMenu($this->menu);
        
        if ( strripos($request->url(), 'show') ) {       
            $view = 'ppcp._11050.show.body';
        } else {
            $view = 'ppcp._11050.show';
        }
        
		return view(
            $view, [
		]);
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }
    
    public function search(Request $request)
    {
        //
    }
    
    
    public static function etiqueta($id)
    {
        $etiquetas = _11050::listar([
            'RETORNO' => ['ETIQUETA'],
            'ID'      => $id
        ])->ETIQUETA;
        
        if ( !isset( $etiquetas[0] ) ) {
            log_erro('Etiqueta ' . $id . ' não localizada');
        }

        return utf8_encode($etiquetas[0]->SCRIPT);
    }
}
