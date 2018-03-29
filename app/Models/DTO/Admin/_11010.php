<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11010DAO;
use App\Http\Controllers\Admin\_11010Controller;

/**
 * 11010 - Gerenciar Usuário
 */
class _11010
{
	public static function find($param = []) {
		return _11010DAO::find(obj_case($param));
	}
    
	/**
	 * • Verifica e retorna as permissões de acesso do usuário<br/>
     * • Registra o log de acesso 
	 * @param int $menu_id Código do menu a ser acessado
	 * @param string $permissao INCLUIR | ALTERAR | EXCLUIR | IMPRIMIR | Default NULL = Permissão para consulta | 0 = Não registra log 
     * @param string $msg Mensagem para o registro no log Ex.: Fixando Dados | Default NULL
	 * @return _11010Controller@permissaoMenu
	 */
	public static function permissaoMenu($menu_id, $permissao = null, $msg = null, $abort = true) {
		return _11010Controller::permissaoMenu($menu_id, $permissao, $msg, $abort);
	}
    
	/**
     * Exibe os grupos de menu do usuário conectado ao sistema
     */ 
	public static function permissaoGrupo() {
		return _11010DAO::selectGrupo();
	}
    
    /**
     * Retorna os controles permitidos ao usuário
     * @param int $id Código da operação de controle
     * @param bool $abort Default = false | true = Cancela a operação | false = Retorna a consulta
     * @return _11010Controller@permissaoMenu
     */
    public static function controle($id, $abort = false) {
        return _11010Controller::controleUsuario($id,$abort);
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
     * @return stdClass VALOR_EXT ou ID, VALOR_EXT, USUARIO_ID, USUARIO, NOME, EMAIL
     */
	public static function controleUsuario($param) {
        return _11010DAO::controleUsuario($param);
	}
	
	/**
	 * Função que retorna uma lista com estabelecimentos permitidos por usuário.
	 * @return _11010DAO@estabPerm
	 */
	public static function estabPerm() {
        return _11010DAO::estabPerm();
	}
	
	/**
	 * Função que retorna uma lista com todos os estabelecimentos.
	 * @return _11010DAO@estabPerm
	 */
	public static function estabTodos() {
        return _11010DAO::estabTodos();
	}
	
	/**
	 * Função que retorna os produtos permitidos por usuário.
	 * @return _11010DAO@estabProduto
	 */
	public static function produtoPerm() {
        return _11010DAO::produtoPerm();
	}
	
	/**
	 * Função para acessar permissões de usuário
	 * 
	 * @param int $menu_id
	 * @return _11010DAO@autorizarMenu
	 */
	public static function autorizarMenu($menu_id) {
        return _11010DAO::autorizarMenu($menu_id);
	}	
    
    /**
     * Lista atributos de usuário
     * @param (object)array $param Aceita como parametro os seguintes campos:
     * <ul>
     * <li>CON - Obj de Conexão com banco de dados  (_Conexao)
     *   <ul>
     *     <li>Caso o campo CON naõ seja alimentado, será criado uma nova conexão com o banco de dados</li>
     *   </ul>
     * </li> 
     * <li>ID - Código do usuário.
     *   <ul>
     *     <li>Aceita passar lista de usuários separado por vírgula. Ex: ID => '1,2,3,4,5,6,7,...'</li>
     *     <li>Caso o campo ID seja vazio, retornará todos os usuários</li>
     *     <li>Caso o campo ID naõ seja alimentado, retornará o id do usuário conectado ao sistema <b>O retorno não será um array</b></li>
     *   </ul>
     * </li> 
     * <li>STATUS
     *   <ul>
     *     <li>| 1 = Ativo | 2 = Inativo |</li>
     *     <li>Aceita passar lista de status separado por vírgula. Ex: ID => '1,2'</li>
     *     <li>Caso o campo STATUS não seja alimentado, retornará status 1 (ativo)</li>
     *   </ul>
     * </li>
     * <li>NIVEL_OC
     *   <ul>
     *     <li>Limite de do nível de autorização de Ordem de Compra. Ex: NIVEL_OC => 3 os usuários de nível 1,2 e 3 serão listados</li>
     *   </ul>
     * </li>
     * </ul>
     * @return (object)array Campos:<br/>
     * <ul>
     * <li>ID - Código do usuário</li>
     * <li>USUARIO - Nome de usuário</li>
     * <li>NOME - Nome/Apelido do usuário</li>
     * <li>EMAIL - Email do usuário</li>
     * <li>STATUS - Status do usuário | 1 = Ativo | 2 = Inativo |</li>
     * <li>NIVEL_OC - Nivel do usuário para autorização de Ordens de Compra</li>
     * <li>GESTOR - Status de gestor do usuário | 1 = É gestor | 2 = Não é gestor</li>
     * </ul>
     */
    public static function listar($param = []) {
        return _11010DAO::listar($param);
    }
    
    /**
     * Resetar senha do usuário 
     * @param array $dados
     * @return array
     */
    public static function ResetarPass($dados) {
        $ret = _11010DAO::ResetarPass($dados);
        return $ret;
    }

    /**
     * Resetar senha do usuário 
     * @param array $dados
     * @return array
     */
    public static function CriarUsuarioDB($dados) {
        $ret = _11010DAO::CriarUsuarioDB($dados);
        return $ret;
    }
    
    

    /**
     * Consulta Menus de um usuario 
     * @param array $userid
     * @return array
     */
    public static function MenusUser($userid) {
        $ret = _11010DAO::MenusUser($userid);
        return $ret;
    }

    /**
     * Consulta Relatorios de um usuario 
     * @param array $userid
     * @return array
     */
    public static function RelatorioUser($userid) {
        $ret = _11010DAO::RelatorioUser($userid);
        return $ret;
    }

    /**
     * Consulta Relatorios
     * @param array $userid
     * @return array
     */
    public static function Relatorio($userid) {
        $ret = _11010DAO::Relatorio($userid);
        return $ret;
    }

    /**
     * Consulta Menus de um usuario 
     * @param array $userid
     * @return array
     */
    public static function SetMenusUser($userid) {
        $ret = _11010DAO::SetMenusUser($userid);
        return $ret;
    }

    /**
     * Consulta Menus
     * @param array $userid
     * @return array
     */
    public static function Menus($userid) {
        $ret = _11010DAO::Menus($userid);
        return $ret;
    }

    
    
    /**
     * Consulta Ccusto de um usuario 
     * @param array $userid
     * @return array
     */
    public static function CcustoUser($userid) {
        $ret = _11010DAO::CcustoUser($userid);
        return $ret;
    }
    
    /**
     * Consulta Permições de um usuario 
     * @param array $userid
     * @return array
     */
    public static function PermicoesUser($userid) {
        $ret = _11010DAO::PermicoesUser($userid);
        return $ret;
    }
    
    /**
     * Consulta Perfil de um usuario 
     * @param array $userid
     * @return array
     */
    public static function PerfilUser($userid) {
        $ret = _11010DAO::PerfilUser($userid);
        return $ret;
    }

    /**
     * Alterar Perfil de um usuario 
     * @param array $dados
     * @return array
     */
    public static function setPerfilUser($dados) {
        $ret = _11010DAO::setPerfilUser($dados);
        return $ret;
    }

    /**
     * Alterar Relatorio de um usuario 
     * @param array $dados
     * @return array
     */
    public static function setRelatorioUser($dados) {
        $ret = _11010DAO::setRelatorioUser($dados);
        return $ret;
    }
    
    /**
     * Consulta Perfil x um usuario 
     * @param array $userid
     * @return array
     */
    public static function Perfil($userid) {
        $ret = _11010DAO::Perfil($userid);
        return $ret;
    }

    /**
     * Listar todos os usuários.
     */
    public static function listarTodos() {
        return _11010DAO::listarTodos();
    }
    
    
}