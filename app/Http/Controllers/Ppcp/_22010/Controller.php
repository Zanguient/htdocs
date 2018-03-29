<?php

namespace app\Http\Controllers\Ppcp\_22010;

use App\Http\Controllers\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22010;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Ppcp
 */
class Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22020';
    
	public function index()
    {
        $permissaoMenu = $this->Menu()->consultar();
        
		return view(
            'ppcp._22010.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }
}