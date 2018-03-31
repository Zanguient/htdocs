<?php

namespace App\Models\DAO\Menu;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;

class MenuDAO
{	
	
	/**
	 * Filtra menu de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 * 
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraMenu($filtro) 
	{
		$con = new _Conexao();
		
		try {
			
			$sql = "
				SELECT * FROM

				(SELECT
					M.CONTROLE, M.URL, M.GRUPO, M.DESCRICAO, M.TIPO, M.CONTROLE as ID , 0 as REL, M.CONTROLE as CONTROLE_REL

				FROM
					TBMENU M,
					VWPERFIL_USER MD

				WHERE
					M.CONTROLE = MD.CONTROLE
				AND MD.USUARIO_ID = :USUARIO_ID1
				AND (M.CONTROLE || M.DESCRICAO || M.GRUPO) LIKE :FILTRO1
				AND MD.TIPO = 1

				union all

				SELECT
                    (28000 + d.ID) as CONTROLE,  d.menu_grupo as GRUPO, d.nome descricao, d.TIPO, d.ID, 1 as REL,(28000) as CONTROLE_REL

                FROM
                    tbrelatorio_web_usuario b,
                    tbrelatorio_web d

                WHERE
                b.usuario_id  = :USUARIO_ID2
                and d.id = b.relatorio_id
                and ((28000 + d.ID) || d.nome )LIKE :FILTRO2)

				ORDER BY 1,3
			";
			
			$args = array(
				':FILTRO1'		=>	'%'.$filtro.'%',
				':FILTRO2'		=>	'%'.$filtro.'%',
				':USUARIO_ID1'	=>	Auth::user()->CODIGO,
				':USUARIO_ID2'	=>	Auth::user()->CODIGO
			);
							
			$menu = $con->query($sql, $args);
		
			$retorno = array(
				'menu'			=> $menu,
				'resposta'		=> array('0' => 'sucesso')
			);
		}
		catch (ValidationException  $e1) {$retorno = array('resposta' => array('0' => 'erro', '1' => $e1->getMessage()));}
		catch (Exception			$e2) {$retorno = array('resposta' => array('0' => 'erro', '1' => $e2->getMessage()));}
		
		return $retorno;
	}
	
	/**
	 * Filtra menu por grupo.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraMenuGrupo($filtro)
	{
		$con = new _Conexao();
		try {
			$sql =
			'
				Select M.CONTROLE, M.GRUPO, M.DESCRICAO, M.TIPO, M.CONTROLE as ID,M.CONTROLE as CONTROLE_REL
						 From TBMENU M , VWPERFIL_USER MD
						 Where M.CONTROLE = MD.CONTROLE
						   And MD.USUARIO_ID = '. \Auth::user()->CODIGO . '
						   And M.GRUPO = \''.$filtro.'\'
						   AND MD.TIPO = 1
						 Order by 1,3
			';
							
			$menu = $con->query($sql);
		
			$retorno = array(
					'menu'			=> $menu,
					'resposta'		=> array('0' => 'sucesso')
			);
			
		}
		catch (ValidationException  $e1) {$retorno = array('resposta' => array('0' => 'erro', '1' => $e1->getMessage()));}
		catch (Exception			$e2) {$retorno = array('resposta' => array('0' => 'erro', '1' => $e2->getMessage()));}
		
		return $retorno;
	}
	
	/**
     * Consulta um material na tbrevisao.
     * @param array $param
     */
    public static function selectMenu(){
        $con = new _Conexao();

		try
		{
			 $sql = "
                SELECT DISTINCT
				    n.MENU_ID AS CONTROLE,
				    m.CODIGO,
				    m.GRUPO,
				    m.DESCRICAO,
				    '' AS URL,
				    0 AS REL
				FROM
				    TBUSUARIO_MENU n, TBMENU m
				    where USUARIO_ID = :USUARIO_ID
				    and m.CODIGO = n.MENU_ID
				ORDER BY 3,4
            ";
					
			$args = array(
				':USUARIO_ID'	=>	Auth::user()->CODIGO,
			);
            
            $menus = $con->query($sql, $args);

            $sql = "
                SELECT DISTINCT
				    m.GRUPO
				FROM
				    TBUSUARIO_MENU n, TBMENU m
				    where USUARIO_ID = :USUARIO_ID
				    and m.CODIGO = n.MENU_ID
				ORDER BY 1
            ";
					
			$args = array(
				':USUARIO_ID'	=>	Auth::user()->CODIGO,
			);
            
            $grupo = $con->query($sql, $args);
            
			$con->commit();

			$ret = [
                'menus'  => $menus,
                'grupos' => $grupo
            ];
            
            return $ret;
			
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
}