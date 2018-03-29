<?php

namespace App\Http\Controllers\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Menu\Menu;
use Illuminate\Support\Facades\Response;
use App\Models\Conexao\_Conexao;

class MenuController extends Controller
{
	public function home() {

		$con    = new  _Conexao();

		// Redirecionar para a tela de pedidos quando o usuário for cliente.
		if (!empty(\Auth::user()->CLIENTE_ID)) {
			return redirect('/_12040');
        }
		else {

            $con = new _Conexao;

            try {
                
                $rev = null;
                                
                $con->commit();
                return view('welcome',['rev'=>$rev]);
            }
            catch (Exception $e)
            {
                $con->rollback();
                throw $e;
            }
            
        
            
            
        }
    }
    
	/**
	 * Filtra menu de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param Request $request
	 */
	public function filtraMenu(Request $request)
	{
		if( $request->ajax() ) {
			
			$ret = Menu::filtraMenu($request->get('filtro'));
			if ($ret['resposta'][0] === 'erro') return view('errors.query', ['erro' => $ret['resposta'][1]]);
			
			$menus = $ret['menu'];
			
			$res = '<ul class="nav">';
			
			if( !empty($menus) ) {
				foreach ($menus as $menu) {
					$res .= '<li> <a href="'.$request->get('urlBase').'/_'.$menu->CONTROLE.'" class="tipo-'.$menu->TIPO.'">'.$menu->CONTROLE.' - '.$menu->DESCRICAO.' </a></li>';
				}
			}
			else {
				$res .= '<li class="nao-encontrado">N&atildeo encontrado.</li>';
			}
			$res .= '</ul>';
			
			echo $res;
		}
	}
	
	/**
	 * Filtra menu por grupo.
	 * Função chamada via Ajax.
	 *
	 * @return String $res
	 */
	public function filtraMenuGrupo(Request $request)
	{
		if( $request->ajax() ) {
				
			$ret = Menu::filtraMenuGrupo($request->get('filtro'));
			if ($ret['resposta'][0] === 'erro') return view('errors.query', ['erro' => $ret['resposta'][1]]);
				
			$menus = $ret['menu'];
				
			$res = '<ul class="nav">';
				
			if( !empty($menus) ) {
				foreach ($menus as $menu) {
					$res .= '<li> <a href="'.$request->get('urlBase').'/_'.$menu->CONTROLE.'" class="tipo-'.$menu->TIPO.'">'.$menu->CONTROLE.' - '.$menu->DESCRICAO.' </a></li>';
				}
			}
			else {
				$res .= '<li class="nao-encontrado">N&atildeo encontrado.</li>';
			}
			$res .= '</ul>';
				
			echo $res;
		}
	}
    
    /**
	 * Tab inativa
	 *
	 * @return String $res
	 */
	public function tabInativa(Request $request,$url){
            $tratado = str_replace("@", "/", $url);
            return view('helper.include.view.tabinativa', ['url' => $tratado]);
	}
    
    public function listarMenu() {
        $res = Menu::selectMenu();
        return Response::json($res);
    }
	
}
