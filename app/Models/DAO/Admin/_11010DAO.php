<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;


class _11010DAO {
    
    /**
     * Resetar senha do usuário 
     * @param array $dados
     * @return array
     */
    public static function ResetarPass($dados)
    {
        $con = new _Conexao;

        $sql = 'execute procedure spu_resetarsenha(:USERID);';
        
        $args = [
            ':USERID' => $dados['user_id']
        ];

        $ret = $con->execute($sql,$args);
        
        $con->commit();
        
    }

    /**
     * Resetar senha do usuário 
     * @param array $dados
     * @return array
     */
    public static function CriarUsuarioDB($dados)
    {   
        try {

            $con = new _Conexao('SUPER');

            $sql = "CREATE USER ".$dados['USERNAME']." PASSWORD 'Gc@180376'";

            $ret = $con->execute($sql);
            
            $con->commit();

        } catch (Exception $e) {

            $con->rollback();
            $con->close();

        } 
        
    }

    
    /**
     * Consulta Menus de um usuario 
     * @param array $userid
     * @return array
     */
    public static function MenusUser($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT
            
                    MD.*
                    
                FROM
                    TBMENU M,
                    VWPERFIL_USER_DETALHE MD
                WHERE
                    M.CONTROLE = MD.CONTROLE
                AND MD.USUARIO_ID = :USERID
                --AND MD.TIPO = 1

                ORDER BY M.DESCRICAO';
        
        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }

    /**
     * Consulta relatorios de um usuario 
     * @param array $userid
     * @return array
     */
    public static function RelatorioUser($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT
            
                    D.*
                    
                FROM
                    TBrelatorio_web_usuario M,
                    TBRELATORIO_WEB D
                WHERE
                    D.id = m.relatorio_id
                AND M.USUARIO_ID = :USERID

                ORDER BY D.descricao';
        
        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }

    /**
     * Consulta relatorios
     * @param array $userid
     * @return array
     */
    public static function Relatorio($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT * from (SELECT

                    ID,
                    NOME,
                    DESCRICAO,
                    TIPO,
                    TEMPLATE_ID,
                    STATUS,
                    MENU_GRUPO,
                    max(FLAG) as FLAG,
                    max(CHEC) as CHEC


                from
                (
                    SELECT
                            
                        D.*,
                        0 as FLAG,
                        0 as CHEC
                        
                    FROM
                        TBRELATORIO_WEB D
                    
                    union
                    
                    SELECT
                    
                        D.*,
                        1 as FLAG,
                        1 as CHEC
                        
                    FROM
                        TBrelatorio_web_usuario M,
                        TBRELATORIO_WEB D
                    WHERE
                        D.id = m.relatorio_id
                    AND M.USUARIO_ID = :USERID

                )
                group by
                    ID,
                    NOME,
                    DESCRICAO,
                    TIPO,
                    TEMPLATE_ID,
                    STATUS,
                    MENU_GRUPO

                ) ORDER BY NOME';

        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }

    /**
     * Consulta Menus 
     * @param array $userid
     * @return array
     */
    public static function Menus($userid)
    {
        $con = new _Conexao;

        $sql = "SELECT * FROM

                (SELECT
                            
                    MD.ID,
                    MD.USUARIO_ID,
                    MD.DESCRICAO,
                    MD.CONTROLE,
                    MD.GRUPO,
                    MD.SUBGRUPO,
                    MD.TIPO,
                    MD.INCLUIR,
                    MD.ORIGEM_I,
                    MD.ALTERAR,
                    MD.ORIGEM_A,
                    MD.EXCLUIR,
                    MD.ORIGEM_E,
                    MD.IMPRIMIR,
                    MD.ORIGEM_M,
                    MD.NEGAR,
                    iif(MD.ORIGEM_I||MD.ORIGEM_A||MD.ORIGEM_E||MD.ORIGEM_M = 'MDMDMDMD',2,1) as FLAG,
                    iif(MD.ORIGEM_I = 'MD',0,1) as TAG_I,
                    iif(MD.ORIGEM_A = 'MD',0,1) as TAG_A,
                    iif(MD.ORIGEM_E = 'MD',0,1) as TAG_E,
                    iif(MD.ORIGEM_M = 'MD',0,1) as TAG_M,
                    MD.ORIGEM_I as ORIGEM
                    
                FROM
                    TBMENU M,
                    VWPERFIL_USER_DETALHE MD
                WHERE
                    M.CONTROLE = MD.CONTROLE
                    AND MD.USUARIO_ID = :USERID1
                --AND MD.TIPO = 1

                union all

                SELECT
                            
                    ID,
                    '' USUARIO_ID,
                    DESCRICAO,
                    CONTROLE,
                    GRUPO,
                    SUBGRUPO,
                    TIPO,
                    0 INCLUIR,
                    'MD' ORIGEM_I,
                    0 ALTERAR,
                    'MD' ORIGEM_A,
                    0 EXCLUIR,
                    'MD' ORIGEM_E,
                    0 IMPRIMIR,
                    'MD' ORIGEM_M,
                    0 as NEGAR,
                    0 as FLAG,
                    0 as TAG_I,
                    0 as TAG_A,
                    0 as TAG_E,
                    0 as TAG_M,
                    'MD' as ORIGEM

                    
                FROM
                    TBMENU M
                    WHERE M.TIPO = 1
                    AND M.controle not in (
                            SELECT
                                    
                            MD.controle
                            
                        FROM
                            TBMENU M,
                            VWPERFIL_USER_DETALHE MD
                        WHERE
                            M.CONTROLE = MD.CONTROLE
                            AND MD.USUARIO_ID = :USERID2
                        --AND MD.TIPO = 1
                        
                        ORDER BY M.DESCRICAO
                    )

                ) ORDER BY DESCRICAO";

        $args = [
            ':USERID1' => $userid,
            ':USERID2' => $userid
        ];

        $ret = $con->query($sql,$args);

        return $ret;
        
    }
    
    /**
     * Consulta Menus de um usuario 
     * @param array $userid
     * @return array
     */
    public static function CcustoUser($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT
                    s.descricao,
                    c.ccusto
                    
                FROM
                    tbusuario_ccusto c,
                    tbcentro_de_custo s
                WHERE c.USUARIO_ID = :USERID and s.codigo = c.ccusto
                order by s.descricao';
        
        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }
    
    /**
     * Consulta Permições de um usuario 
     * @param array $userid
     * @return array
     */
    public static function PermicoesUser($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT
            
                    U.ID,
                    U.PARAMETRO,
                    C.VALOR_EXT,
                    U.COMENTARIO
                    
                FROM
                    TBCONTROLE_U U,
                    TBCONTROLE_USUARIO C

                WHERE C.USUARIO_ID = :USERID
                AND U.ID = C.ID
                order by u.parametro';
        
        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }
    
    /**
     * Consulta Perfil de um usuario 
     * @param array $userid
     * @return array
     */
    public static function PerfilUser($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT * FROM

                (SELECT
                    P.ID,
                    P.DESCRICAO
                    
                FROM
                    TBUSUARIO_PERFIL_DETALHE D,
                    TBUSUARIO_PERFIL P

                WHERE D.USUARIO_ID = :USERID
                AND P.ID = D.PERFIL_ID

                UNION

                SELECT
                    P.ID,
                    P.DESCRICAO
                    
                FROM
                    TBUSUARIO_PERFIL P

                WHERE P.ID = 0
                ) A
                ORDER BY A.DESCRICAO';
        
        $args = [
            ':USERID' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }

    /**
     * Alterar Perfil de um usuario 
     * @param array $dados
     * @return array
     */
    public static function setPerfilUser($dados)
    {
        $con = new _Conexao;
        $deletar = '0';

        foreach ($dados['perfil'] as $key => $value) {
            //$con->query($sql,$args);
            if( ($value[1] == 1) &&  ($value[2] == 0) ){
                $deletar = $deletar . ','. $value[0];
            }

        }

        $sql = 'DELETE
                FROM
                TBUSUARIO_PERFIL_DETALHE D

                WHERE D.USUARIO_ID = :USERID
                AND D.perfil_id in ('.$deletar.')';
        
        $args = [
            ':USERID' => $dados['user_id']
        ];

        $ret = $con->query($sql,$args);

        foreach ($dados['perfil'] as $key => $value) {
            //$con->query($sql,$args);
            if( ($value[1] == 0) &&  ($value[2] == 1) ){

                $sql = 'INSERT INTO
                        TBUSUARIO_PERFIL_DETALHE (PERFIL_ID,USUARIO_ID)
                        VALUES(:PERFIL,:USERID)';

                $args = [
                    ':USERID' => $dados['user_id'],
                    ':PERFIL' => $value[0]
                ];


                $ret = $con->query($sql,$args);
            }

        }

        $con->commit();

        return $ret;
        
    }

    /**
     * Alterar Perfil de um usuario 
     * @param array $dados
     * @return array
     */
    public static function setRelatorioUser($dados)
    {
        $con = new _Conexao;
        $deletar = '0';

        foreach ($dados['relatorio'] as $key => $value) {
            
            if( ($value[1] == 1) &&  ($value[2] == 0) ){
                $deletar = $deletar . ','. $value[0];
            }

        }

        $sql = 'DELETE
                FROM
                TBrelatorio_web_usuario D

                WHERE D.USUARIO_ID = :USERID
                AND D.relatorio_id in ('.$deletar.')';
        
        $args = [
            ':USERID' => $dados['user_id']
        ];

        $ret = $con->query($sql,$args);

        foreach ($dados['relatorio'] as $key => $value) {
            //$con->query($sql,$args);
            if( ($value[1] == 0) &&  ($value[2] == 1) ){

                $sql = 'INSERT INTO
                        TBrelatorio_web_usuario (RELATORIO_ID,USUARIO_ID)
                        VALUES(:RELATORIO,:USERID)';

                $args = [
                    ':USERID'    => $dados['user_id'],
                    ':RELATORIO' => $value[0]
                ];


                $ret = $con->query($sql,$args);
            }

        }

        $con->commit();

        return $ret;
        
    }

    /**
     * Alterar Perfil de um usuario 
     * @param array $dados
     * @return array
     */
    public static function SetMenusUser($dados)
    {
        $con = new _Conexao;
        $deletar = '0';
        $gravar  = [];

        foreach ($dados['menus'] as $key => $value) {

            if( ($value[1] == 0) ){
                $deletar = $deletar . ','. $value[0];
            }else{
                array_push($gravar,$value);
            }

        }

        $sql = 'DELETE
                FROM
                TBMENU_DETALHE D

                WHERE D.USUARIO_ID = :USERID
                AND D.menu_id in ('.$deletar.')';
        
        $args = [
            ':USERID' => $dados['user_id']
        ];

        $ret = $con->query($sql,$args);

        foreach ($gravar as $key => $value) {

            $sql = 'UPDATE OR INSERT INTO
                TBMENU_DETALHE
                (
                    MENU_ID,
                    USUARIO_ID,
                    NEGAR,
                    INCLUIR,
                    ALTERAR,
                    EXCLUIR,
                    IMPRIMIR  
                )

                VALUES

                (
                    :MENU_ID,
                    :USUARIO_ID,
                    :NEGAR,
                    :INCLUIR,
                    :ALTERAR,
                    :EXCLUIR,
                    :IMPRIMIR
                )

                MATCHING (MENU_ID,USUARIO_ID)';

            $args = [
                ':USUARIO_ID'  => $dados['user_id'],
                ':MENU_ID'     => $value[0],
                ':NEGAR'       => $value[2],
                ':INCLUIR'     => $value[3],
                ':ALTERAR'     => $value[4],
                ':EXCLUIR'     => $value[5],
                ':IMPRIMIR'    => $value[6]
            ];

            $ret = $con->query($sql,$args);

        }

        $con->commit();

        return $ret;
        
    }
    
    /**
     * Consulta todos Perfils X um usuario 
     * @param array $userid
     * @return array
     */
    public static function Perfil($userid)
    {
        $con = new _Conexao;

        $sql = 'SELECT
                    P.ID,
                    P.DESCRICAO,
                    coalesce((select 1 from TBUSUARIO_PERFIL_DETALHE D where d.perfil_id = p.id and d.usuario_id = :USERID1),0) as FLAG,
                    coalesce((select 2 from TBUSUARIO_PERFIL_DETALHE D where d.perfil_id = p.id and d.usuario_id = :USERID2),0) as CHEC
                FROM
                     TBUSUARIO_PERFIL P

                ORDER BY P.DESCRICAO';
        
        $args = [
            ':USERID1' => $userid,
            ':USERID2' => $userid
        ];

        $ret = $con->query($sql,$args);
        
        return $ret;
        
    }
    
    public static function find($param = [])
    {
        $res = [];
                
        /**
         * Retorna as informações da principais do usuario
         */
        if ( isset($param->RETORNO) && in_array('USUARIO', $param->RETORNO) )
        {
            $res = $res+['USUARIO' =>_11010DaoSelect::usuario($param)];
        }
        
		return (object) $res;
    }

    public static function listar($param) {
        if ( !isset($param->ID) ) {
            $usuario = _11010DaoSelect::listar($param)[0];
        } else {
            $usuario = _11010DaoSelect::listar($param);
        }
          
        return $usuario;
    }

    public static function autorizarMenu($menu_id)
    {
		return self::exibirPermissao(new _Conexao(),$menu_id);
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
	public static function  controleUsuario($param)
    {   
        $res = self::exibirControle(new _Conexao(),$param);
        
        if ( !isset($param->USUARIO_ID) ) {
            if ( !empty($res) ) {
                $res = $res[0]->VALOR_EXT; 
            }
        }
		return $res;
	}
	
	/**
	 * Função que retorna uma lista com os estabelecimentos permitidos por usuário.
	 * @return string
	 */
	public static function estabPerm() {
		
		$con = new _Conexao();
		
        $sql = "
            SELECT 
				LIST(ESTABELECIMENTO_CODIGO)
			FROM 
				TBUSUARIO_ESTABELECIMENTO U
			WHERE 
				U.USUARIO_CODIGO = :USU_ID
		";
		
		$args = array(
			':USU_ID'	=> Auth::user()->CODIGO
		);
			
		$estab_perm = $con->query($sql, $args)[0]->LIST;
		
		//se não a consulta acima não retornar nenhum valor, 
		//o usuário poderá ver todos os estabelecimentos.
		if( empty($estab_perm) ) {
			$estab_perm = self::estabTodos();
		}
		
		return $estab_perm;
	}
	
	/**
	 * Função que retorna uma lista com todos os estabelecimentos.
	 * @return string
	 */
	public static function estabTodos() {
		
		$con = new _Conexao();
		
        $sql = "
            SELECT 
				LIST(CODIGO)
			FROM 
				TBESTABELECIMENTO
		";
		
		return $con->query($sql)[0]->LIST;
	}
    
	/**
	 * Função que retorna os produtos permitidos por usuário.
	 * @return string
	 */
	public static function produtoPerm() {
		
		$con = new _Conexao();
		
        $sql = "
            SELECT 
                LIST(U.FAMILIA_ID)
            FROM 
                TBUSUARIO_FAMILIA U
            WHERE 
                U.USUARIO_ID = :USU_ID
		";
		
		$args = array(
			':USU_ID'	=> Auth::user()->CODIGO
		);
			
		return $con->query($sql, $args)[0]->LIST;
	}
	
	public static function exibirPermissao(_Conexao $con, $menu_id) {

		$sql = "
            SELECT FIRST 1
                TRIM(m.INCLUIR) INCLUIR,
                TRIM(m.ALTERAR) ALTERAR,
                TRIM(m.EXCLUIR) EXCLUIR,
                1 IMPRIMIR
            FROM
                tbusuario_menu M
            WHERE
                M.menu_id = :MENU_ID
            AND M.USUARIO_ID = :USUARIO_ID
		";
		
		$args = array(
            ':MENU_ID'		=> $menu_id,
            ':USUARIO_ID'	=> Auth::user()->CODIGO
		);
			
		return $con->query($sql, $args);			
	}
    
	/**
	 * Consulta os grupos de menu do usuário conectado ao sistema
	 * @return string
	 */
	public static function selectGrupo() {
		
		$con = new _Conexao();
		
        $sql = "
            SELECT DISTINCT
                    m.GRUPO
                FROM
                    TBUSUARIO_MENU n, TBMENU m
                    where USUARIO_ID = :USUARIO_ID
                    and m.CODIGO = n.MENU_ID
                ORDER BY 1
		";
		
		$args = [
			':USUARIO_ID' => Auth::user()->CODIGO
		];
			
		$ret = $con->query($sql, $args);
		
		return $ret;
	}
    
	public static function exibirControle(_Conexao $con, $param = []) {

        if ( isset($param->USUARIO_ID) ) {
            if ($param->USUARIO_ID == '') {
                $usuario_id = '';
            } else {
                $usuario_id = 'and c.usuario_id IN (' . $param->USUARIO_ID . ')';
            }
        } else {
            $usuario_id = 'and c.usuario_id = ' . Auth::user()->CODIGO;
        }
                
		$sql = "
        Select
        ID,
        GRUPO,
        PARAMETRO,
        --VALOR_EXT,
        COMENTARIO,
        MENU,
        MENUS,
    
        Coalesce((
        Select c.valor_ext
        from tbcontrole_u u, tbcontrole_usuario c
        where u.id = c.id
        and u.id = g.id
        /*@USUARIO_ID*/
        ),
    
        Coalesce((
        Select p.valor
        from tbcontrole_u j, tbusuario_controle_perfil p, tbusuario_perfil_detalhe c
        where j.id = p.controle_id
        and p.perfil_id = c.perfil_id
        and j.id = g.id
        /*@USUARIO_ID*/
        ),
    
        (
        select j.valor_admin
        from tbcontrole_u j, tbusuario_controle_grupo p, tbusuario_perfil_detalhe c
        where j.grupo = p.grupo
        and p.perfil_id = c.perfil_id
        and j.id = g.id
        /*@USUARIO_ID*/
        ))) as VALOR_EXT
        
    
        from tbcontrole_u G where g.id = :CONTROLE_ID
        ";
		
		$args = array(
				':CONTROLE_ID' => $param->ID,
				'@USUARIO_ID'  => $usuario_id
		);
			
		return $con->query($sql, $args);			
	}

    /**
     * Listar todos os usuários ativos.
     */
    public static function listarTodos() 
    {
        $con = new _Conexao();
        
        $sql = "
            SELECT         
                LPAD(U.CODIGO, 4, '0') ID,
                U.USUARIO,
                U.NOME,
                U.EMAIL,  
                U.NIVEL_OC, 
                U.GESTOR,
                U.SETOR,
                U.CCUSTO_ID SETOR_ID,
                (SELECT FIRST 1 S.CCUSTO_ID FROM TBCCUSTO_SETOR S WHERE S.ID = U.CCUSTO_ID) CCUSTO

            FROM
                TBUSUARIO U
            WHERE
                U.STATUS = '1'
        ";

        return $con->query($sql);            
    }
	
}

class _11010DaoSelect {
    
public static function usuario($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $first    = array_key_exists('FIRST'     , $param) ? "FIRST               " . $param->FIRST : '';
        $skip     = array_key_exists('SKIP'      , $param) ? "SKIP                " . $param->SKIP : '';
        $filtro   = array_key_exists('FILTRO'    , $param) ? "AND FILTRO   LIKE '%". str_replace(' ','%', $param->FILTRO) ."%'"       : '';
        $status   = array_key_exists('STATUS'    , $param) ? "AND STATUS     IN (" . arrayToList($param->STATUS    , "'#'","'") . ")" : '';
        $nivel_oc = array_key_exists('NIVEL_OC'  , $param) ? "AND NIVEL_OC   IN (" . arrayToList($param->NIVEL_OC  , "'#'","'") . ")" : '';
        $usuario  = array_key_exists('USUARIO'   , $param) ? "AND USUARIO    IN (" . arrayToList($param->USUARIO   , "'#'","'") . ")" : '';
        $nome     = array_key_exists('NOME'	     , $param) ? "AND NOME       IN (" . arrayToList($param->NOME      , "'#'","'") . ")" : '';
        $email    = array_key_exists('EMAIL'     , $param) ? "AND EMAIL      IN (" . arrayToList($param->EMAIL     , "'#'","'") . ")" : '';
        $id       = array_key_exists('ID'	     , $param) ? "AND ID         IN (" . arrayToList($param->ID        , 999999999) . ")" : '';
        $familia  = array_key_exists('FAMILIA_ID', $param) ? "AND FAMILIA_ID IN (" . arrayToList($param->FAMILIA_ID, 999999999) . ")" : '';

        $sql =
        "   
            SELECT /*@FIRST*/ /*@SKIP*/
                Y.*
            FROM
               (SELECT
                    X.*,
                   (X.ID      || ' ' ||
                    X.USUARIO || ' ' ||
                    X.NOME    || ' ' ||
                    COALESCE(UPPER(X.EMAIL),''))FILTRO
                FROM
                   (SELECT
                        U.CODIGO ID,
                        U.USUARIO,
                        U.NOME,
                        U.EMAIL,
                        U.SKYPE,
                        U.RAMAL,
                        U.STATUS,  
                        U.NIVEL_OC, 
                        U.GESTOR,            
                        U.CARGO,
                        U.CCUSTO_ID SETOR_ID,
                        (SELECT FIRST 1 S.DESCRICAO FROM TBCCUSTO_SETOR S WHERE S.ID = U.CCUSTO_ID)SETOR_DESCRICAO,
                        (SELECT FIRST 1 S.CCUSTO_ID FROM TBCCUSTO_SETOR S WHERE S.ID = U.CCUSTO_ID)CCUSTO,
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCCUSTO_SETOR S, TBCENTRO_DE_CUSTO C WHERE S.ID = U.CCUSTO_ID AND C.CODIGO = S.CCUSTO_ID)CCUSTO_DESCRICAO,
                        U.FAMILIA_CODIGO FAMILIA_ID,
                        (SELECT FIRST 1 DESCRICAO FROM TBFAMILIA WHERE CODIGO = U.FAMILIA_CODIGO)FAMILIA_DESCRICAO,
                        U.EMAIL_USERNAME,
                        U.EMAIL_PASS,
                        U.EMAIL_SMTP,
                        U.PORTA_SMTP EMAIL_PORTA,
                        U.AUTENTICACAO EMAIL_AUTENTICACAO,
                        U.SSL_TLS EMAIL_SSL_TLS,
                        U.PASSWORD,
                        U.SENHA

                    FROM
                        TBUSUARIO U

                    ORDER BY
                        USUARIO,NOME)X)Y
            WHERE
                1=1
            /*@STATUS*/
            /*@ID*/
            /*@NIVEL_OC*/
            /*@USUARIO*/
            /*@NOME*/
            /*@EMAIL*/
            /*@FILTRO*/
        ";

        $args = [
            '@FIRST'           => $first,
            '@SKIP'            => $skip,
            '@FILTRO'          => $filtro,
            '@STATUS'          => $status,
            '@ID'              => $id,
            '@USUARIO'         => $usuario,
            '@NOME'            => $nome,
            '@EMAIL'           => $email,
            '@FAMILIA'         => $familia,
            '@NIVEL_OC'        => $nivel_oc,
        ];

        return $con->query($sql,$args);
    }
    
    /**
     * Lista atributos de usuário
     * @param _Conexao $con
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
     *     <li>Caso o campo ID naõ seja alimentado, retornará o id do usuário conectado ao sistema</li>
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
     *     <li>Caso o campo STATUS não seja alimentado, retornará status 1 (ativo)</li>
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
    public static function listar($param = []) 
    {
        $con        = isset($param->CON)        ? $param->CON : new _Conexao;
        $usuario_id = isset($param->ID) ? (($param->ID == '') ? '' : 'AND U.CODIGO IN (' . $param->ID . ')') : 'AND U.CODIGO = ' . Auth::user()->CODIGO;
        $status     = isset($param->STATUS)     ? "AND U.STATUS   IN (" . $param->STATUS   . ")" : '';
        $nivel_oc   = isset($param->NIVEL_OC)   ? "AND U.NIVEL_OC <= " . $param->NIVEL_OC . " AND U.NIVEL_OC > 0" : '';
        $sql = "
            SELECT         
                U.CODIGO ID,
                U.USUARIO,
                U.NOME,
                U.EMAIL,
                U.STATUS,  
                U.NIVEL_OC, 
                U.GESTOR,
                U.CCUSTO_ID SETOR_ID,
                (SELECT FIRST 1 S.CCUSTO_ID FROM TBCCUSTO_SETOR S WHERE S.ID = U.CCUSTO_ID)CCUSTO

            FROM
                TBUSUARIO U

            WHERE
                1=1
            /*@ID*/
            /*@STATUS*/
            /*@NIVEL_OC*/

            ORDER BY
                2
        ";

        $args = array(
            '@ID'	    => $usuario_id,
            '@STATUS'   => $status,
            '@NIVEL_OC' => $nivel_oc,
        );

        return $con->query($sql, $args);			
    }

}


class _11010DaoInsert {
//    public static function 
}

class _11010DaoUpdate {
//    public static function 
}

class _11010DaoDelete {
//    public static function 
}

