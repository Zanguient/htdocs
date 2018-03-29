<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\Conexao\_Conexao;

/**
 * Controller do menu Gestão de Usuários
 */
class _11010Controller extends Controller
{  
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11010';
    
    public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'admin._11010.index', [
                'permissaoMenu' => $permissaoMenu
            ]);
    }

    public function create()
    {
        _11010::permissaoMenu($this->menu,'INCLUIR');
    }

    public function store(Request $request)
    {    	
        _11010::permissaoMenu($this->menu,'INCLUIR','Fixando Dados');
    }

    public function loginUser(Request $request){

        if(\Auth::user()->OLDUSER_CODIGO == 0){

            $param  = $request->all();
            $con    = new  _Conexao();

            $sql = 'SELECT
                        *
                    from TBUSUARIO a  
                    
                    where a.CODIGO = :CODIGO
            ';

            $args = array(
                ':CODIGO' => $param['CODIGO'],
            );

            $ret = $con->query($sql,$args);

            if(count($ret) > 0){

                $user = $ret[0];

                $user->OLD_CODIGO = $param['CODIGO'];
                $user->NEW_CODIGO = $user->CODIGO;

                \Cache::forever(\Auth::user()->CODIGO . '_OLTLOGIN', $user);
            }

        }else{
            log_erro('Não permitido!');
        }
    }

    public function voltarUser(Request $request){
        \Cache::flush(\Auth::user()->CODIGO . '_OLTLOGIN');
    }


    public function show(Request $request,$id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizando Usuário ' . $id);
        
        $dados = (object)$request->all();
        $dados->RETORNO = ['USUARIO'];
        $dados->FIRST   = 1;
        $dados->ID      = $id;
        
        $usuario = _11010::find($dados)->USUARIO;  
                
        if ( isset($usuario[0]) ) {
            $usuario = $usuario[0];
        } else {
            log_erro('Usuário inexistente!');
        }
    
        if ( $request->ajax() ) {
            $body        = '.body';
            $class_acoes = 'popup-acoes';
            
            $menus = $this->MenusUser($id);
            
        } else {
            $body        = '';
            $class_acoes = 'acoes';
            $menus       = [];
        }
        
        return View::make(
            'admin._11010.show' . $body, [
                'permissaoMenu' => $permissaoMenu,
                'id'            => $id,
                'usuario'       => $usuario,
                'class_acoes'   => $class_acoes,
                'menus'         => $menus
            ])->render()
        ;
    }
    
    public function edit($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR');
    }
    
    public function update(Request $request)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Fixando Dados');
    }
    
    public function destroy($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Excluindo Usuário ' . $id);
    }
        
    public function indexBody(Request $request)
    {
        $usuarios = _11010::find($request->all())->USUARIO;  
        
        return view(
            'admin._11010.index.body', [
                'usuarios' => $usuarios
            ])
        ;
    }
        
    public function showBody(Request $request,$id)
    {
        
    }
	
	/**
	 * • Verifica e retorna as permissões de acesso do usuário
     * • Registra o log de acesso 
	 * @param string $menu Módulo e Código do menu a ser acessado. Ex.: Admin/_11010
     * @param string $permissao INCLUIR | ALTERAR | EXCLUIR | IMPRIMIR | Default NULL = Permissão para consulta | 0 = Não registra log 
	 * @return array 
	 */
	public static function permissaoMenu($menu, $permissao = null, $msg = null, $abort = true)
	{
        $str = explode('/_', $menu); 
        
        //Verifica se a string passada como menu possui módulo
        if ( is_numeric( $str[0] ) ) {
            $menu   = $str[0];
            $modulo = '';
        } else {
            $menu   = $str[1];
            $modulo = $str[0];
        }
        
        $log  = ($permissao == '0') ? $log = false : true;
		$ret  = _11010::autorizarMenu($menu);
		$op   = null;

		if(!$ret) {
            
			$op 	   = 'acessar o';
			$permissao = 'CONSULTAR';
		}
		else if( $permissao === 'INCLUIR' ) {
		
			if (!$ret[0]->INCLUIR){
				$op = 'incluir no';
			}
		}
		else if( $permissao === 'ALTERAR' ) {
		
			if (!$ret[0]->ALTERAR){
				$op = 'alterar no';
			}
		}
		else if( $permissao === 'EXCLUIR' ) {
		
			if (!$ret[0]->EXCLUIR){
				$op = 'excluir no';
			}
		}
		else if( $permissao === 'IMPRIMIR' ) {
		
			if (!$ret[0]->IMPRIMIR){
				$op = 'imprimir no';
			}
		}
		
		if ($op && $abort) {
            Log::info(str_pad(\Auth::user()->USUARIO, 10) . ' | ' . str_pad(\Request::getClientIp(),13) . ' | ' . Lang::get($modulo . '/_' . $menu . '.titulo') . ' - Acesso negado!');
			log_erro('Usuário não possui permissão para ' .$op . ' menu ' . $menu . '.');
		}
        
        //Não registra log se permissão for igual 0
        if( $log ) {
            Log::info(str_pad(\Auth::user()->USUARIO, 10) . ' | ' . str_pad(\Request::getClientIp(),13) . ' | ' . Lang::get($modulo . '/_' . $menu . '.titulo') . ($msg ? ' - ' . $msg : ''));
        }
		
		return isset($ret[0]) ? $ret[0] : [];
	}
    
    /**
     * Exibe os grupos de menu do usuário conectado ao sistema
     */
    public static function permissaoGrupo()
    {
        return Response::json(_11010::permissaoGrupo());
    }
    
    
    
    /**
     * Lista os Controles de Usuário<br/>
     * Exibir controle do usuário conectado, passar como parametro o id do controle<br/>
     * Ex.: controleUsuario(194)<br/>
     * Consulta condicional com os parametros:<br/>
     * • ID = Código do controle<br/>
     * • USUARIO_ID = Código do usuário - Possível list (1,2,3,4,...)<br/>
     * Ex.: controleUsuario((object) array('ID' => 194, 'USUARIO_ID' => 193)
     * @param (object)array $param
     * @param bool $abort Default = false | true = Cancela a operação | false = Retorna a consulta
     * @return stdClass VALOR_EXT ou ID, VALOR_EXT, USUARIO_ID, USUARIO, NOME, EMAIL
     */
    public static function controleUsuario($param, $abort = false) 
    {
        if (gettype($param) != 'object') {
            $param = (object) array ('ID' => $param);
        } 
        
        $ret = _11010::controleUsuario($param);
        
        if ( $abort && !($ret == '1') ) {
            log_erro('[P' .$param->ID . '] Você não tem permissão para executar esta operação!'); 
        }
        
        return isset($ret) ? $ret : '';
    }	
	
	/**
     * Função que retorna os estabelecimentos permitidos por usuário.
     *
     * @return string
     */
    public static function estabPerm() 
    {
		$estab_perm = _11010::estabPerm();

        return $estab_perm;
    }
    
    /**
     * Resetar senha do usuário 
     * @param Request $request
     * @return array
     */
    public function ResetarPass(Request $request) 
    {
        if( $request->ajax() ) {
            
            $dados = $request->all();
            
            $ret = _11010::ResetarPass($dados);
            return $ret;
            
        }
    }

    /**
     * CriarUsuarioDB
     * @param Request $request
     * @return array
     */
    public function CriarUsuarioDB(Request $request) 
    {
        if( $request->ajax() ) {
            
            $dados = $request->all();
            
            $ret = _11010::CriarUsuarioDB($dados);
            return $ret;
            
        }
    }

    
    
    function getMenusUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Menus do Usuário ' . $dados['user_id']);
            $ret = _11010::MenusUser($dados['user_id']);
            
            return view(
            'admin._11010.show.menu-usuario', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'menus' => $ret
            ])
        ;
        }
    }


    function getRelatorioUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $ret = _11010::RelatorioUser($dados['user_id']);
            
            return view(
            'admin._11010.show.relatorios', [
                'id'         => $dados['user_id'],
                'relatorios' => $ret
            ])
        ;
        }
    }

    function getRelatorios(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $ret = _11010::Relatorio($dados['user_id']);

            return view(
            'admin._11010.show.alter_relatorio', [
                'id'            => $dados['user_id'],
                'relatorios'    => $ret
            ]);
        }
    }

    function setMenusUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,'ALTERAR','Menus do Usuário ' . $dados['user_id']);

            if(isset($dados['menus'])){
                $ret = _11010::SetMenusUser($dados);
            }
            $ret = _11010::MenusUser($dados['user_id']);
            
            return view(
            'admin._11010.show.menu-usuario', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'menus' => $ret
            ])
        ;
        }
    }

    function getMenus(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,'ALTERAR','Menus do Usuário ' . $dados['user_id']);
            $ret = _11010::Menus($dados['user_id']);

            return view(
            'admin._11010.show.alter_menus', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'menus' => $ret
            ]);
        }
    }
    
    function getCcustoUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,null,'C. Custo do Usuário ' . $dados['user_id']);
            $ret = _11010::CcustoUser($dados['user_id']);
            
            return view(
            'admin._11010.show.ccusto', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'custo' => $ret
            ])
        ;
        }
    }
    
    function getPermicoesUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Parametros e Permissões do Usuário ' . $dados['user_id']);
            $ret = _11010::PermicoesUser($dados['user_id']);
            
            return view(
            'admin._11010.show.parametro', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'permicoes' => $ret
            ])
        ;
        }
    }
    
    function getPerfilUser(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Parametros e Permissões do Usuário ' . $dados['user_id']);
            $ret = _11010::PerfilUser($dados['user_id']);
            
            return view(
            'admin._11010.show.perfil', [
                'id'      => $dados['user_id'],
                'permissaoMenu' => $permissaoMenu,
                'perfils' => $ret
            ])
        ;
        }
    }

    function setPerfilUser(Request $request){
        if( $request->ajax() ) {

            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,'ALTERAR','Parametros e Permissões do Usuário ' . $dados['user_id']);
            $ret = _11010::setPerfilUser($dados);
            
            //return view(
            //'admin._11010.show.perfil', [
            //    'id'      => $dados['user_id'],
            //    'permissaoMenu' => $permissaoMenu,
            //    'perfils' => $ret
            //]);
        }
    }

    function setRelatorioUser(Request $request){
        if( $request->ajax() ) {

            $dados = $request->all();
            $permissaoMenu = _11010::permissaoMenu($this->menu,'ALTERAR','Relatorios do Usuário ' . $dados['user_id']);
            $ret = _11010::setRelatorioUser($dados);

        }
    }
    
    function getPerfil(Request $request){
        if( $request->ajax() ) {
            $dados = $request->all();
            $ret = _11010::Perfil($dados['user_id']);
            
            return view(
            'admin._11010.show.alter_perfil', [
                'id'      => $dados['user_id'],
                'perfils' => $ret
            ])
        ;
        }
    }


    /**
     * Consultar menus do usuário 
     * @param integer $userid
     * @return array
     */
    public function MenusUser($userid) 
    {
        $ret = _11010::MenusUser($userid);
        return $ret;
    }
    
    /**
     * Consultar Ccusto do usuário 
     * @param integer $userid
     * @return array
     */
    public function CcustoUser($userid) 
    {
        $ret = _11010::CcustoUser($userid);
        return $ret;
    }
    
    /**
     * Consultar Permições do usuário 
     * @param integer $userid
     * @return array
     */
    public function PermicoesUser($userid) 
    {
        $ret = _11010::PermicoesUser($userid);
        return $ret;
    }
    
    /**
     * Consultar Perfil do usuário 
     * @param integer $userid
     * @return array
     */
    public function PerfilUser($userid) 
    {
        $ret = _11010::PerfilUser($userid);
        return $ret;
    }
    
    /**
     * Consultar Perfil do usuário 
     * @param integer $userid
     * @return array
     */
    public function Perfil($userid) 
    {
        $ret = _11010::Perfil($userid);
        return $ret;
    }
    
    /**
     * Listar todos os usuários.
     */
    public function listarTodos() {

        return Response::json( _11010::listarTodos() );

    }

}
