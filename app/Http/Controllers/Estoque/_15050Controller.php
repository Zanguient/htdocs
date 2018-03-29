<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DTO\Estoque\_15050;
use App\Models\DTO\Estoque\_15010;
use App\Models\DTO\Estoque\_15040;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

/**
 * Controller do objeto 15050 - Baixa de estoque.
 */
class _15050Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'estoque/_15050';
	
	/**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		
		_11010::permissaoMenu($this->menu);
        
        $dados = [];
        
		return view('estoque._15050.index', [
			'dados'			=> $dados,
			'menu'			=> $this->menu
		]);
    }
	
}
