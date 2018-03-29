<?php

namespace app\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Workflow\_29013;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _29013 - Painel com cronograma das tarefas
 */
class _29013Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'workflow/_29013';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;


    public function viewItem() {
        return view('workflow._29013.index.index', ['menu' => $this->menu]);
    }

    public function index() {
        
        $permissaoMenu = _11010::permissaoMenu($this->menu);

        return view(
            'workflow._29013.index', [
            'permissaoMenu'     => $permissaoMenu,
            'menu'              => $this->menu
        ]);  
    }

}