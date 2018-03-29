<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22040;
use Illuminate\Support\Facades\Auth;
use App\Models\DTO\Helper\Historico;
use Exception;

class _22040DAO
{

	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param)
	{
        $res = [];
        /**
         * Retorna as informações da principais da remessa
         */
        if ( isset($param->RETORNO) && in_array('REMESSA', $param->RETORNO) )
        {
            $res = $res+['REMESSA' =>_22040DaoSelect::remessa($param)];
        }

        /**
         * Retorna as informações dos talões acumulados
         */
        if ( isset($param->RETORNO) && in_array('TALAO', $param->RETORNO) )
        {
            $res = $res+['TALAO' =>_22040DaoSelect::remessaTalao($param)];
        }

        /**
         * Retorna as informações dos talões detalhado
         */
        if ( isset($param->RETORNO) && in_array('TALAO_DETALHE', $param->RETORNO) )
        {
            $res = $res+['TALAO_DETALHE' =>_22040DaoSelect::remessaTalaoDetalhe($param)];
        }
		
        /**
         * Retorna as informações da família do consumo
         */
        if ( isset($param->RETORNO) && in_array('REMESSA_FAMILIA_CONSUMO', $param->RETORNO) )
        {
            $res = $res+['REMESSA_FAMILIA_CONSUMO' =>_22040DaoSelect::remessaConsumoFamiliaPorRemessaId($param)];
        }

		return (object) $res;
	}
    
    /**
     * reabrir talao 
     * @param array $param 
     */
    public static function reabrirTalao($param = [])
    {
        $con = new _Conexao();
		try
		{  
            $sql ="
                    EXECUTE PROCEDURE REABRIR_TALAO(:ID)
                  ";
            
            $args = [
                ':ID' => $param->ID
            ];
            
            Historico::setHistorico('TBREMESSA', $param->REMESSA_ID, 'Talão reaberto -> ' . $param->TALAO_ID);

            $con->query($sql,$args); 

            $con->commit();
            
            return $param;
            
		}catch (Exception $e){
            
			$con->rollback();
			throw $e;
            
		}      
    }

	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function count($param)
	{
        $res = [];

        /**
         * Retorna as informações da principais da remessa
         */
        if ( isset($param->RETORNO) && in_array('PROGRAMADO', $param->RETORNO) )
        {
            $res = $res+['PROGRAMADO' =>_22040DaoSelect::remessaCountProgramado($param)];
        }

        /**
         * Retorna as informações dos talões acumulados
         */
        if ( isset($param->RETORNO) && in_array('PRODUZIDO', $param->RETORNO) )
        {
            $res = $res+['PRODUZIDO' =>_22040DaoSelect::remessaTalao($param)];
        }

        /**
         * Retorna as informações dos talões detalhado
         */
        if ( isset($param->RETORNO) && in_array('ABERTO', $param->RETORNO) )
        {
            $res = $res+['ABERTO' =>_22040DaoSelect::remessaTalaoDetalhe($param)];
        }

        /**
         * Retorna as informações dos talões detalhado
         */
        if ( isset($param->RETORNO) && in_array('ATRASADO', $param->RETORNO) )
        {
            $res = $res+['ATRASADO' =>_22040DaoSelect::remessaTalaoDetalhe($param)];
        }

		return (object) $res;
	}
    
	/**
	 * Similar ao SHOW (EXIBIR) do LARAVEL
	 * Retorna dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id)
	{
		$con = new _Conexao();

		return array();
	}

	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _13050 $param
	 */
	public static function gravar($param)
	{
        set_time_limit(0);
        
		$con = new _Conexao();
		try
		{      
            /**
             * Grava remessa de bojo para utilização no gcweb
             */
            if ( array_key_exists('MANIPULACAO_WEB', $param) ) {
                _22040DaoUpdate::remessa($param->MANIPULACAO_WEB, $con);
            }
            
            /**
             * • Atualiza o status da requisicao para gerada
             */
            foreach ($param->REQUISICAO as $requisicao) {
          //      _22040DaoUpdate::requisicao($requisicao, $con);
            }
            
            /**
             * • Grava remessa
             * • Gera o consumo dos insumos da remessa
             */
            _22040DaoInsert::remessa($param->REMESSA, $con);

            /**
             * • Grava os talões acumulados
             * • Gera a programação dos talões
             */
            $controle_talao = 0;
            foreach ($param->REMESSA_TALAO as $talao) {
                $controle_talao++;
                _22040DaoInsert::remessaTalao      ($talao, $con);
                _22040DaoInsert::remessaProgramacao($talao, $con);
            }
            
            /**
             * Grava os talões detalhados
             */
            $controle_detalhe = 0;
            foreach ($param->REMESSA_TALAO_DETALHE as $item) {
                $controle_detalhe++;
                _22040DaoInsert::remessaTalaoDetalhe($item, $con);
            }

            _22040DaoInsert::remessaConsumoInsumo($param->REMESSA, $con);

            /**
             * Gera os vinculo dos consumos com os talões
             */
            foreach ($param->REMESSA_CONSUMO_VINCULO as $consumo_vinculo) {
                _22040DaoInsert::remessaConsumoVinculo($consumo_vinculo, $con);
            }
            
            $param->REMESSA->CONTROLE_TALAO   = $controle_talao;
            $param->REMESSA->CONTROLE_DETALHE = $controle_detalhe;
            
            _22040DaoSelect::verificarRemessaIntegridade($param->REMESSA,$con);
          
            $con->commit();
		}
        catch (Exception $e)
        {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Similar ao UPDATE (ATUALIZAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 * @param _13050 $obj
	 */
	public static function alterar(_13050 $obj)
	{
		$con = new _Conexao();
		try {

			//

			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Similar ao DESTROY (EXCLUIR) do CRUD
	 * Exclui dados do objeto na base de dados.
	 * @param int $id
	 */
	public static function excluir($id)
	{
		$con = new _Conexao();
		try {

			//

			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Gerar Id da Remessa
	 */
	public static function gerarId()
	{
		$con = new _Conexao();

		$sql = '
			SELECT GEN_ID(GTBREMESSA, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}

	/**
	 * Gerar Id do Talão
	 */
	public static function gerarTalaoId()
	{
		$con = new _Conexao();

		$sql = '
			SELECT GEN_ID(GTBREMESSA_ITEM_PROCESSADO, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}

	/**
	 * Gerar Id do Talão Detalhe
	 */
	public static function gerarTalaoDetalheId()
	{
		$con = new _Conexao();

		$sql = '
			SELECT GEN_ID(GERADOR1_ID, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}

	/**
	 * Gerar Id da Requisicao
	 */
	public static function gerarRequisicaoId()
	{
		$con = new _Conexao();

		$sql = '
			SELECT GEN_ID(GTBREMESSA_CONSUMO_REQUISICAO, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}

	/**
	 * Gerar Id da Requisicao
	 */
	public static function gerarReposicaoLaminacaoId()
	{
		$con = new _Conexao();

		$sql = '
			SELECT GEN_ID(GTBREMESSA_L, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}

    public static function remessaConsumo($param)
    {
		$con = new _Conexao();

		try {
			$res = [];
            
			if (isset($param->DETALHE) && in_array('CONSUMO'      , $param->DETALHE)) { $res = $res + ['CONSUMO'      => _22040DaoSelect::remessaConsumo           ($param, $con)]; }
			if (isset($param->DETALHE) && in_array('GP'           , $param->DETALHE)) { $res = $res + ['GP'           => _22040DaoSelect::remessaConsumoGp         ($param, $con)]; }
			if (isset($param->DETALHE) && in_array('PERFIL'       , $param->DETALHE)) { $res = $res + ['PERFIL'       => _22040DaoSelect::remessaConsumoPerfil     ($param, $con)]; }
            
            if ( isset($param->REMESSA) ) {
                if( $param->REMESSA == 'REP' ) {
                    if (isset($param->DETALHE) && in_array('FAMILIA'      , $param->DETALHE)) { $res = $res + ['FAMILIA'      => _22040DaoSelect::reposicaoConsumoFamilia    ($param, $con)]; }
                    if (isset($param->DETALHE) && in_array('NECESSIDADE'  , $param->DETALHE)) { $res = $res + ['NECESSIDADE'  => _22040DaoSelect::reposicaoConsumoNecessidade($param, $con)]; }
                } else 
                if( $param->REMESSA == 'REQ' ) {
                    if (isset($param->DETALHE) && in_array('FAMILIA'      , $param->DETALHE)) { $res = $res + ['FAMILIA'      => _22040DaoSelect::requisicaoConsumoFamilia    ($param, $con)]; }
                    if (isset($param->DETALHE) && in_array('NECESSIDADE'  , $param->DETALHE)) { $res = $res + ['NECESSIDADE'  => _22040DaoSelect::requisicaoConsumoNecessidade($param, $con)]; }
                } else 
                if ( strstr($param->REMESSA, 'PD') && is_numeric(substr($param->REMESSA , 2)) ) {
                    if (isset($param->DETALHE) && in_array('FAMILIA'      , $param->DETALHE)) { $res = $res + ['FAMILIA'      => _22040DaoSelect::pedidoConsumoFamilia    ($param, $con)]; }
                    if (isset($param->DETALHE) && in_array('NECESSIDADE'  , $param->DETALHE)) { $res = $res + ['NECESSIDADE'  => _22040DaoSelect::pedidoConsumoNecessidade($param, $con)]; }
                } else {
                    if (isset($param->DETALHE) && in_array('FAMILIA'      , $param->DETALHE)) { $res = $res + ['FAMILIA'      => _22040DaoSelect::remessaConsumoFamilia    ($param, $con)]; }
                    if (isset($param->DETALHE) && in_array('NECESSIDADE'  , $param->DETALHE)) { $res = $res + ['NECESSIDADE'  => _22040DaoSelect::remessaConsumoNecessidade($param, $con)]; }
                }
            }

			$con->commit();

			return (object)$res;

		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }

    public static function remessaProgramacao($param)
    {
		$con = new _Conexao();

		try {
			$res = [];
            
            if (isset($param->RETORNO) && in_array('PROGRAMACAO', $param->RETORNO)) { $res = $res + ['PROGRAMACAO' => _22040DaoSelect::remessaProgramacao         ($param, $con)]; }
			if (isset($param->RETORNO) && in_array('HISTORICO'  , $param->RETORNO)) { $res = $res + ['HISTORICO'   => _22040DaoSelect::remessaProgramacaoHistorico($param, $con)]; }
			
            if (isset($param->RETORNO) && in_array('TEMPO', $param->RETORNO))
            {
                //Insere os registro que se deseja saber o tempo em uma tabela temporária
                foreach ($param->PARAM_TEMPO as $param_tempo) {
                    _22040DaoInsert::remessaProgramacaoTempo($param_tempo, $con);
                }
                //Executa procedure que retorna o tempo com base nos valores inseridos
                $res = $res + ['TEMPO' => _22040DaoSelect::remessaProgramacaoTempo($param, $con)];
            }
            
			$con->commit();

			return (object)$res;

		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }

    public static function remessaDefeito($param)
    {
		$con = new _Conexao();

		try {
			$res = [];

			if (isset($param->RETORNO) && in_array('DEFEITO', $param->RETORNO)) { $res = $res + ['DEFEITO' => _22040DaoSelect::remessaDefeito($param, $con)]; }
			$con->commit();

			return (object)$res;

		} 
		catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
	
	/**
	 * Verifica se a remessa a ser gerada já existe.
	 * @param array $param
	 * @return array
	 */
	public static function verificarRemessaExiste($param) {
				
		return _22040DaoSelect::verificarRemessaExiste($param);
		
	}
    
	public static function atualizarCotaCliente($param) {
				
		$con = new _Conexao();
		try {
            _22040DaoUpdate::atualizarCotaCliente($param,$con);

			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
		
	}
}

class _22040DaoSelect
{


    /**
     * Lista remessas
     * @param (object)array $param
     * @param _Conexao $con
     * Campos de entrada:
     * <ul>
     *   <li>
     *     <b>ESTABELECIMENTO_ID</b> array de estabelecimentos
     *   </li>
     *   <li>
     *     <b>FAMILIA_ID</b> array de familias
     *   </li>
     *   <li>
     *     <b>REMESSA_ID</b> array de id's de remessas
     *   </li>
     *   <li>
     *     <b>REMESSA</b> array de códigos de remessas
     *   </li>
     *   <li>
     *     <b>DATA</b> array de faixa de período array[0]: data inicial ; array[1] data final
     *   </li>
     *   <li>
     *     <b>STATUS</b> array de de status. 1 = ativo ; 0 = inativo
     *   </li>
     *   <li>
     *     <b>FILTRO</b> string de filtragem
     *   </li>
     * </ul>
     * @return (object)array
     */
    public static function remessa($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $first           = isset($param->FIRST             ) ? "FIRST                       " . $param->FIRST : '';
        $skip            = isset($param->SKIP              ) ? "SKIP                        " . $param->SKIP : '';
        $estabelecimento = isset($param->ESTABELECIMENTO_ID) ? "AND ESTABELECIMENTO_ID IN  (" . Helpers::arrayToList($param->ESTABELECIMENTO_ID , 999999999) . ")" : '';
        $familia         = isset($param->FAMILIA_ID        ) ? "AND FAMILIA_ID         IN  (" . Helpers::arrayToList($param->FAMILIA_ID         , 999999999) . ")" : '';
        $remessa_id      = isset($param->REMESSA_ID        ) ? "AND REMESSA_ID         IN  (" . Helpers::arrayToList($param->REMESSA_ID         , 999999999) . ")" : '';
        $remessa         = isset($param->REMESSA           ) ? "AND REMESSA            IN  (" . Helpers::arrayToList($param->REMESSA            , 999999999) . ")" : '';
        $data            = isset($param->DATA              ) ? "AND DATA          BETWEEN  '" . $param->DATA[0]."' AND '" . (isset($param->DATA[1]) ? $param->DATA[1] : $param->DATA[0]) . "'" : '';
        $status          = isset($param->STATUS            ) ? "AND STATUS              =  '" . $param->STATUS . "'" : '';
        $perfil          = isset($param->PERFIL            ) ? "AND PERFIL              =  '" . $param->PERFIL . "'" : '';
        $not_perfil      = isset($param->NOT_PERFIL        ) ? "AND PERFIL         NOT IN ('" . $param->NOT_PERFIL . "')" : '';
        $filtro          = isset($param->FILTRO            ) ? "AND FILTRO           LIKE  '%". str_replace(' ','%', $param->FILTRO)            . "%'" : '';
        $ordem           = isset($param->ORDEM             ) ? "ORDER BY                    " . Helpers::arrayToList($param->ORDEM , 'REMESSA_ID DESC') : 'ORDER BY REMESSA_ID DESC';

        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                X.ESTABELECIMENTO_ID,
                X.ESTABELECIMENTO_NOMEFANTASIA,
                X.REMESSA,
                X.REMESSA_ID,
                X.PERFIL,
                X.FAMILIA_ID,
                X.FAMILIA_DESCRICAO,
                X.GP_ID,
                X.GP_DESCRICAO,
                X.DATA,
                X.STATUS,
                X.STATUS_DESCRICAO,
                X.TIPO,            
                X.TIPO_DESCRICAO

            FROM
                (SELECT
                    LPAD(R.ESTABELECIMENTO_ID,2,'0') ESTABELECIMENTO_ID,
                    E.NOMEFANTASIA ESTABELECIMENTO_NOMEFANTASIA,
                    R.REMESSA,
                    R.REMESSA_ID,
                    F.PERFIL,
                    LPAD(R.FAMILIA_ID,3,'0')FAMILIA_ID,
                    F.DESCRICAO FAMILIA_DESCRICAO,
                    R.GP_ID,
                    (SELECT FIRST 1 DESCRICAO FROM TBGP WHERE ID = R.GP_ID) GP_DESCRICAO,
                    R.DATA,
                    R.STATUS,
                   (CASE
                        R.STATUS
                    WHEN '1' THEN 'ATIVO'
                    WHEN '0' THEN 'INATIVO'
                    ELSE 'INDEFINIDO' END) STATUS_DESCRICAO,

                    /* 1 - NORMAL     */ CAST(IIF(R.TIPO = '1', '1',
                    /* 2 - VIP        */ IIF(R.TIPO = '2' AND R.REQUISICAO = '0','2',
                    /* 3 - REQUISICAO */ IIF(R.TIPO = '2' AND R.REQUISICAO = '1','3','0'))) AS VARCHAR(1))TIPO, 

                    /* 1 - NORMAL     */ CAST(IIF(R.TIPO = '1', 'NORMAL',
                    /* 2 - VIP        */ IIF(R.TIPO = '2' AND R.REQUISICAO = '0','VIP',
                    /* 3 - REQUISICAO */ IIF(R.TIPO = '2' AND R.REQUISICAO = '1','REQ','0'))) AS VARCHAR(15))TIPO_DESCRICAO,

                   (R.REMESSA   || ' ' ||
                    F.DESCRICAO || ' ' ||
                    /* 1 - NORMAL     */ CAST(IIF(R.TIPO = '1', 'NORMAL',
                    /* 2 - VIP        */ IIF(R.TIPO = '2' AND R.REQUISICAO = '0','VIP',
                    /* 3 - REQUISICAO */ IIF(R.TIPO = '2' AND R.REQUISICAO = '1','REQ','0'))) AS VARCHAR(15))
                    )FILTRO

                FROM
                    VWREMESSA R,
                    TBFAMILIA F,
                    TBESTABELECIMENTO E

                WHERE
                    F.CODIGO = R.FAMILIA_ID
                AND E.CODIGO = R.ESTABELECIMENTO_ID)X

            WHERE
                1=1
            /*@ESTABELECIMENTO*/
            /*@FAMILIA*/
            /*@REMESSA_ID*/
            /*@REMESSA*/
            /*@DATA*/
            /*@STATUS*/
            /*@PERFIL*/
            /*@NOT_PERFIL*/
            /*@FILTRO*/

            /*@ORDEM*/
        ";

        $args = [
            '@FIRST'           => $first,
            '@SKIP'            => $skip,
            '@ESTABELECIMENTO' => $estabelecimento,
            '@FAMILIA'         => $familia,
            '@REMESSA_ID'      => $remessa_id,
            '@REMESSA'         => $remessa,
            '@DATA'            => $data,
            '@STATUS'          => $status,
            '@PERFIL'          => $perfil,
            '@NOT_PERFIL'      => $not_perfil,
            '@FILTRO'          => $filtro,
            '@ORDEM'           => $ordem
        ];

        return $con->query($sql,$args);
    }

    /**
     * Listagem dos Talões Acumulados da Remessa
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaTalao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
		
        $first              = array_key_exists('FIRST_TALAO'       , $param) ? "FIRST                      " . $param->FIRST_TALAO                                      : '';
        $skip               = array_key_exists('SKIP_TALAO'        , $param) ? "SKIP                       " . $param->SKIP_TALAO                                       : '';
        $filtro             = array_key_exists('FILTRO'            , $param) ? "AND FILTRO           LIKE '%". str_replace(' ','%', $param->FILTRO) ."%'"               : '';
        $status             = array_key_exists('STATUS'			   , $param) ? "AND T.STATUS             IN (" . arrayToList($param->STATUS            , 999999999) . ")" : '';
        $programacao_status = array_key_exists('PROGRAMACAO_STATUS', $param) ? "AND PROGRAMACAO_STATUS IN (" . arrayToList($param->PROGRAMACAO_STATUS, "'#'","'") . ")" : '';
        $talao_id           = array_key_exists('TALAO_ID'		   , $param) ? "AND ID                 IN (" . arrayToList($param->TALAO_ID          , 999999999) . ")" : '';
        $estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
        $remessa            = array_key_exists('REMESSA'		   , $param) ? "AND REMESSA            IN (" . arrayToList($param->REMESSA           , "'#'","'") . ")" : '';
        $remessa_status     = array_key_exists('REMESSA_STATUS'	   , $param) ? "AND REMESSA_STATUS     IN (" . arrayToList($param->REMESSA_STATUS    , "'#'","'") . ")" : '';        
		$remessa_id         = (array_key_exists('REMESSA_ID', $param) && !empty($param->REMESSA_ID))
								? "AND REMESSA_ID IN (" . arrayToList($param->REMESSA_ID, 999999999) . ")" 
								: '';
		$periodo = '';
		$turno   = '';
		
		//status do talão
		if ( array_key_exists('STATUS', $param) ) {
			
			//se for talão à produzir
			if ( $param->STATUS[0] === 1 ) {
			
				//se 'periodo_todos' e 'período' forem passados
				if ( array_key_exists('PERIODO_TODOS', $param) && array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) ) {
					//se o período não for todos
					if ( ($param->PERIODO_TODOS === 'false') ) {
						$periodo = "AND REMESSA_DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
					}
				}
			}

			//se for talão produzido
			else if ( $param->STATUS[0] === 2 ) {
				
				if (array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param)) {
					$periodo = "AND DATA_PRODUCAO BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
				}
				
				if (array_key_exists('TURNO', $param) && !empty($param->TURNO)) {
					$turno = "AND X.TURNO IN('" . arrayToList(ltrim($param->TURNO, 0), 999999999) . "')";
				}
				
				//se o 'período' e 'turno' forem passados
//				if ( array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) && array_key_exists('TURNO', $param) && array_key_exists('TURNO_HORA_INI', $param) && array_key_exists('TURNO_HORA_FIM', $param) ) {
//					
//					if ( $param->TURNO == '01' ) {
//						$periodo = "AND HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '$param->DATA_FIM $param->TURNO_HORA_FIM'";
//					}
//					//se o turno for noite, será adicionado 1 dia na 'data fim' devido à 'data de produção'
//					else if ( $param->TURNO == '02' ) {
//						$data_criada = date_create($param->DATA_FIM);
//						date_add($data_criada, date_interval_create_from_date_string('1 days'));
//						$periodo = "AND HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '". date_format($data_criada, 'Y-m-d') ." $param->TURNO_HORA_FIM'";
//					}
//				}
//				else if (array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param)) {
//					$periodo = "AND DATA_PRODUCAO BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
//				}
			}
			
		}
		
        $gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
        if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $up_origem	        = array_key_exists('UP_ORIGEM'		   , $param) ? "AND UP_DESTINO      LIKE '%" . $param->UP_ORIGEM . "%'"									: '';
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND (T.ESTACAO = 0 OR T.ESTACAO IN (" . arrayToList($param->ESTACAO           , 999999999) . "))" : '';
        
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
        $ordem              = array_key_exists('ORDEM'             , $param) ? "ORDER BY                   " . arrayToList($param->ORDEM , 'DATAHORA_REALIZADO_INICIO, DATAHORA_INICIO') : '';        
        
        $programacao_datahora   = array_key_exists('PROGRAMACAO_DATAHORA', $param) ? "AND DATAHORA_INICIO >= '" . $param->PROGRAMACAO_DATAHORA[0] . "' AND DATAHORA_FIM <= '" . $param->PROGRAMACAO_DATAHORA[1] . "'" : '';

        $sql =
        "
            SELECT /*@FIRST_TALAO*/ /*@SKIP_TALAO*/
                X.ESTABELECIMENTO_ID,
                IIF(X.GP_ID = 19,
                (SELECT
                        '[' ||LIST( '{'                ||
                            '\"GP_ID\": '              || GP_ID            ||     ', '  ||
                            '\"GP_DESCRICAO\": \"'     || GP_DESCRICAO     ||   '\", '  ||
                            '\"UP_ID\": '              || UP_ID            ||     ', '  ||
                            '\"ESTACAO_DESCRICAO\": \"'     || ESTACAO_DESCRICAO     ||   '\", '  ||
                            '\"ESTACAO\": '            || ESTACAO          ||     ', '  ||
                            '\"ID\": '                 || ID               ||     ', '  ||
                            '\"REMESSA_ID\": '         || REMESSA_ID       ||     ', '  ||
                            '\"REMESSA_TALAO_ID\": \"' || REMESSA_TALAO_ID ||   '\", '  ||
                            '\"DATAHORA_INICIO\": \"' || DATAHORA_INICIO ||   '\", '  ||
                            '\"SEQUENCIA\": \"' || SEQUENCIA ||   '\" '  ||
                        '}') || ']'
                    FROM (
                        SELECT

                            P.GP_ID,
                            FN_DESCRICAO('TBGP',P.GP_ID) GP_DESCRICAO,
                            P.UP_ID,
                            P.ESTACAO,
                            (SELECT FIRST 1 DESCRICAO
                               FROM TBSUB_UP U
                              WHERE U.UP_ID = P.UP_ID
                                AND U.ID = P.ESTACAO) ESTACAO_DESCRICAO,
                            T2.ID,
                            T2.REMESSA_ID,
                            FN_LPAD(T2.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                            FN_TIMESTAMP_TO_STRING(P.DATAHORA_INICIO) DATAHORA_INICIO,
                            (SELECT  SEQUENCIA
                            FROM    (
                                SELECT FIRST 6
                                       ROW_NUMBER() OVER(ORDER BY P2.DATAHORA_INICIO) SEQUENCIA,
                                       T.ID,
                                       P2.DATAHORA_INICIO
                                  FROM VWREMESSA_TALAO T,
                                       TBPROGRAMACAO P2
                                 WHERE P2.TIPO = 'A'
                                   AND P2.TABELA_ID = T.ID
                                   AND P2.STATUS  < 3
                                   AND NOT (P2.STATUS = 1 AND P2.STATUS_REQUISICAO = 1)
                                   AND P2.GP_ID = P.GP_ID
                                   AND P2.UP_ID = P.UP_ID
                                   AND P2.ESTACAO = P.ESTACAO
                                   --AND P2.DATA <= CURRENT_DATE
                                 ORDER BY P2.DATAHORA_INICIO
                                ) B
                            WHERE B.ID = T2.ID ) SEQUENCIA

                        FROM
                            VWREMESSA_TALAO T1,
                            TBREMESSA_CONSUMO_VINCULO V,
                            VWREMESSA_CONSUMO C,
                            VWREMESSA_TALAO T2,
                            TBPROGRAMACAO P

                        WHERE TRUE
                        AND T1.ID = X.ID
                        AND V.REMESSA_ID = T1.REMESSA_ID
                        AND V.REMESSA_TALAO_ID = T1.REMESSA_TALAO_ID
                        AND C.ID = V.CONSUMO_ID
                        AND T2.REMESSA_ID = C.REMESSA_ID
                        AND T2.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                        AND P.TIPO = 'A'
                        AND P.TABELA_ID = T2.ID
                        ) X
                    WHERE SEQUENCIA > 0),NULL) SEQUENCIA_PRODUCAO,
                X.ID,
                coalesce((select
                                list(j.descricao||': '||coalesce(q.observacao,''),',<br>') as DESC

                            from
                                TBREGISTRO_PARADA Q,
                                tbjustificativa J,
                                (select
                                    distinct v.acumulado,
                                    d.remessa_id,
                                    d.remessa_talao_id
                                from SPU_RASTREAMENTO_VINCULO2(x.REMESSA_ID,x.remessa_talao_id,x.ID,0) v, vwremessa_talao_detalhe d
                                where v.acumulado > 0
                                and d.id = v.acumulado) p

                            where Q.tabela = 'PRODUCAO'
                            and Q.vinculo_id = P.remessa_id
                            and q.tabela_id  = P.remessa_talao_id
                            and j.id = Q.status),'') as JUSTIFICATIVA,
                X.GP_ID,
                X.UP_ID,
                X.UP_DESCRICAO,
				X.PERFIL_UP,
                X.ESTACAO,
                TRIM(CAST(SUBSTRING(X.ESTACAO_FIELDS FROM  1 FOR  20) AS VARCHAR( 20))) ESTACAO_DESCRICAO,
                TRIM(CAST(SUBSTRING(X.ESTACAO_FIELDS FROM 21 FOR 100) AS VARCHAR(100))) ESTACAO_PERFIL_SKU,
                X.TURNO,
                X.REMESSA,
                X.DATA_ORIGEM,
                X.REMESSA_DATA,
                X.REMESSA_ID,
                X.REMESSA_PRINCIPAL,
                X.REMESSA_TALAO_ID,     
                X.REMESSA_STATUS,
                X.MODELO_ID,
                X.MODELO_DESCRICAO,
                X.DENSIDADE,
                X.ESPESSURA,
                X.FAMAILIA_ID,
                CAST(SUBSTRING(X.QUANTIDADES FROM  1 FOR 11) AS NUMERIC(15,4)) QUANTIDADE,
                CAST(SUBSTRING(X.QUANTIDADES FROM 12 FOR 11) AS NUMERIC(15,4)) QUANTIDADE_PRODUCAO,
                CAST(SUBSTRING(X.QUANTIDADES FROM 23 FOR 11) AS NUMERIC(15,4)) QUANTIDADE_ALTERNATIVA,
                CAST(SUBSTRING(X.QUANTIDADES FROM 34 FOR 11) AS NUMERIC(15,4)) QUANTIDADE_ALTERNATIVA_PRODUCAO,
                CAST(SUBSTRING(X.QUANTIDADES FROM 45 FOR 11) AS NUMERIC(15,4)) QUANTIDADE_PRODUZIDA,
                X.TALOES_VINCULO,
                X.PARES,
                X.PARES_POR_GP,
                X.UM,
                X.UM_ALTERNATIVA,
                X.STATUS,
                X.STATUS_DESCRICAO,
                X.TEMPO,
                X.TEMPO_REALIZADO,
                X.DATAHORA_INICIO,
                X.DATAHORA_FIM,
                X.DATAHORA_REALIZADO_INICIO,
                X.DATAHORA_REALIZADO_FIM,
                X.DATA_PRODUCAO,
                X.HORA_PRODUCAO,
                CAST(SUBSTRING(X.OPERADOR FROM   1 FOR 10) AS INTEGER) OPERADOR_ID,
                CAST(SUBSTRING(X.OPERADOR FROM  11 FOR 20) AS VARCHAR(20)) OPERADOR_DESCRICAO,
                X.PROGRAMACAO_ID,
                X.PROGRAMACAO_DATA,
                X.PROGRAMACAO_STATUS,
                X.PROGRAMACAO_STATUS_DESCRICAO,
                SUBSTRING(X.DADOS_ORIGEM FROM 1 FOR (POSITION('@', X.DADOS_ORIGEM)-1)) TALOES_ORIGEM,
                SUBSTRING(X.DADOS_ORIGEM FROM (POSITION('@', X.DADOS_ORIGEM)+1)) DATA_REMESSA_ORIGEM,
                X.REMESSA_TIPO,
                X.REMESSA_TIPO_DESCRICAO,
                X.REMESSA_TIPO_VIP,
                X.UP_DESTINO,
                X.VIA_ETIQUETA,
                X.COMPONENTE,

                X.STATUS_COMPONENTE,
                X.VIA_ETIQUETA,
                TRIM((CASE X.STATUS_COMPONENTE
                    WHEN '0' THEN 'NÃO INICIADO'
                    WHEN '1' THEN 'EM ANDAMENTO'
                    WHEN '2' THEN 'PRODUZIDO'
                    WHEN '6' THEN 'ENCERRADO'
                    ELSE 'INDEFINIDO' END
                )) STATUS_COMPONENTE_DESCRICAO,
                
                --STATUS DA COMBINACAO DE MATERIA-PRIMA E COMPONENTE
                X.STATUS_MP_CP,
                
                --EFICIENCIA = ((QTD.PROD. / MIN.PROD.) / (QTD.PROJ. / MIN.PROJ.)) * 100
                IIF(X.STATUS = 2,
                (
                  (
                    CAST(
                        (NULLIF(CAST(SUBSTRING(X.QUANTIDADES FROM 45 FOR 11) AS NUMERIC(15,4)), 0))
                        /
                        NULLIF(X.TEMPO_REALIZADO, 0)
                    AS DOUBLE PRECISION)
                    /
                    CAST(
                        IIF(CAST(SUBSTRING(X.QUANTIDADES FROM 23 FOR 11) AS NUMERIC(15,4)) > 0,
                            NULLIF(CAST(SUBSTRING(X.QUANTIDADES FROM 23 FOR 11) AS NUMERIC(15,4)), 0),
                            NULLIF(CAST(SUBSTRING(X.QUANTIDADES FROM  1 FOR 11) AS NUMERIC(15,4)), 0))
                            /
                            NULLIF(X.TEMPO, 0)
                    AS DOUBLE PRECISION)                    
                  )
                  *
                  100
                )
                , 0) EFICIENCIA,
                coalesce((select first 1 lpad(l.codigo,3,0)||' - '||l.descricao as LOCALIZACAO from tblocalizacao l where l.codigo = x.localizacao_id),'') as LOCALIZACAO
            
            FROM
                (SELECT
                    R.ESTABELECIMENTO_ID,
                    T.ID,
                    R.DATA REMESSA_DATA,
                    T.REMESSA_ID,
                    R.REMESSA,
                    COALESCE(FN_REMESSA_PRINCIPAL(T.REMESSA_ID),R.REMESSA) REMESSA_PRINCIPAL,
                    (select first 1 w.data from vwremessa w where w.remessa = (iif(R.REMESSA like '1M%',substring(R.REMESSA from 3 for char_length(R.REMESSA) -2),'0'))) as DATA_ORIGEM,
                    LPAD(T.REMESSA_TALAO_ID,4,'0')REMESSA_TALAO_ID,
                    TRIM(R.STATUS) REMESSA_STATUS,
                    T.GP_ID,
                    T.UP_ID,
                    U.DESCRICAO UP_DESCRICAO,
					U.PERFIL PERFIL_UP,
                    LPAD(T.ESTACAO,2,'0') ESTACAO,

                   (SELECT CAST((RPAD(S.DESCRICAO, 20) || RPAD(LIST(S.PERFIL_SKU, ', '), 100)) AS VARCHAR(120)) TESTES
                      FROM TBSUB_UP S
                     WHERE S.UP_ID = T.UP_ID
                       AND S.ID = T.ESTACAO
                     GROUP BY DESCRICAO)ESTACAO_FIELDS,
                     
                    TRIM(T.TURNO) TURNO,

                    LPAD(T.MODELO_ID,5,'0') MODELO_ID,
                    M.DESCRICAO MODELO_DESCRICAO,
                    T.DENSIDADE,
                    T.ESPESSURA,
                    M.FAMILIA_CODIGO FAMAILIA_ID,
                    (SELECT
                       (LPAD(SUM(D.QUANTIDADE),11,' '        ) ||
                        LPAD(SUM(D.QUANTIDADE_PRODUCAO       ),11,' ') ||
                        LPAD(SUM(D.QUANTIDADE_ALTERN         ),11,' ') ||
                        LPAD(SUM(D.QUANTIDADE_ALTERN_PRODUCAO),11,' ') ||
                        --QUANTIDADE PRODUZIDA = QUANTIDADE_PRODUCAO - APROVEITAMENTO
                        LPAD(SUM(IIF(D.QUANTIDADE_ALTERN_PRODUCAO > 0, D.QUANTIDADE_ALTERN_PRODUCAO, D.QUANTIDADE_PRODUCAO))
                                - SUM(
                                     COALESCE(
                                        (SELECT FIRST 1 IIF(SUM(V.QUANTIDADE_ALTERNATIVA) > 0, SUM(V.QUANTIDADE_ALTERNATIVA), SUM(V.QUANTIDADE)) APROVEITAMENTO_ALOCADO
                                            FROM TBREMESSA_TALAO_VINCULO V
                                            WHERE
                                                V.STATUS <> '1' 
                                            AND V.TIPO = 'R'
                                            AND V.REMESSA_TALAO_DETALHE_ID = D.ID)
                                     ,0)
                                )
                        ,11,' '))
                    FROM  VWREMESSA_TALAO_DETALHE D
                    WHERE D.REMESSA_ID       = T.REMESSA_ID
                      AND D.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID) QUANTIDADES,
                      
                    (SELECT
                        SUM(IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
                            ((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/NULLIF(K.QUANTIDADE_CONSUMO, 0)))) QUANTIDADE

                        FROM
                        (
                            SELECT
                                C.QUANTIDADE_ORIGINAL QUANTIDADE_ORIGEM,
                                IIF(COALESCE(C.QUANTIDADE_NECES,0) > 0,C.QUANTIDADE_NECES, C.QUANTIDADE) QUANTIDADE_CONSUMO,
                                V.QUANTIDADE QUANTIDADE_VINCULO

                            FROM
                                VWREMESSA_CONSUMO C,
                                TBREMESSA_CONSUMO_VINCULO V
                            WHERE
                                V.REMESSA_ID        = T.REMESSA_ID
                            AND V.REMESSA_TALAO_ID  = T.REMESSA_TALAO_ID
                            AND V.CONSUMO_ID        = C.ID
                        )K
                    ) PARES,
                    
                    (SELECT LIST(
                            DISTINCT
                            TRIM(
                                IIF(T2.STATUS = 2,'<span style=\"color: green; font-weight: bold\">','') ||
                                    FN_LPAD(V2.REMESSA_TALAO_ID,4,0)         ||
                                IIF(T2.STATUS = 2,'</span>',''))
                            ,', ')
                      FROM VWREMESSA_CONSUMO C,
                           TBREMESSA_CONSUMO_VINCULO V,
                           VWREMESSA_CONSUMO C2,
                           TBREMESSA_CONSUMO_VINCULO V2,
                           VWREMESSA_TALAO T2
                     WHERE C.REMESSA_ID         = T.REMESSA_ID
                       AND C.REMESSA_TALAO_ID   = T.REMESSA_TALAO_ID
                       AND V.REMESSA_ID         = C.REMESSA_ID
                       AND V.REMESSA_TALAO_ID   = C.REMESSA_TALAO_ID
                       AND C2.ID                = V.CONSUMO_ID
                       AND V2.CONSUMO_ID        = C2.ID
                       AND V2.REMESSA_TALAO_ID <> C.REMESSA_TALAO_ID
                       AND T2.REMESSA_ID        = V2.REMESSA_ID
                       AND T2.REMESSA_TALAO_ID  = V2.REMESSA_TALAO_ID
                       ORDER BY 1) TALOES_VINCULO,

                    (SELECT LIST(GP,', ')
                        FROM
                        (
                            SELECT
                                (X.GP_DESCRICAO || ' / ' ||
                                TRUNC(SUM(IIF(X.QUANTIDADE_CONSUMO = X.QUANTIDADE_VINCULO,X.QUANTIDADE_ORIGEM,
                                    ((X.QUANTIDADE_ORIGEM * X.QUANTIDADE_VINCULO)/NULLIF(X.QUANTIDADE_CONSUMO, 0)))))) GP

                            FROM
                            (
                                SELECT
                                    C.QUANTIDADE_ORIGINAL QUANTIDADE_ORIGEM,
                                    IIF(COALESCE(C.QUANTIDADE_NECES,0) > 0,C.QUANTIDADE_NECES, C.QUANTIDADE) QUANTIDADE_CONSUMO,
                                    V.QUANTIDADE QUANTIDADE_VINCULO,
                                    G.DESCRICAO GP_DESCRICAO

                                FROM
                                    VWREMESSA_CONSUMO C,
                                    TBREMESSA_CONSUMO_VINCULO V,
                                    VWREMESSA_TALAO TT,
                                    TBGP G
                                WHERE
                                    V.REMESSA_ID = T.REMESSA_ID
                                AND V.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                                AND TT.REMESSA_ID = C.REMESSA_ID
                                AND TT.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                                AND G.ID = TT.GP_ID
                                AND V.CONSUMO_ID = C.ID
                            )X
                            GROUP BY GP_DESCRICAO
                        )Y
                    ) PARES_POR_GP,
                      
                    T.QUANTIDADE_ALTERNATIVA,
                    F.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    T.STATUS,
                   TRIM((CASE
                        T.STATUS
                    WHEN 1 THEN 'EM ABERTO'
                    WHEN 2 THEN 'PRODUZIDO'
                    WHEN 3 THEN 'LIBERADO'
                    ELSE 'INDEFINIDO' END)) STATUS_DESCRICAO,
                    P.TEMPO,
                    P.TEMPO_REALIZADO,
                    P.DATAHORA_INICIO,
                    P.DATAHORA_FIM,
                    P.ID PROGRAMACAO_ID,
                    (SELECT MIN(DATAHORA) FROM TBPROGRAMACAO_REGISTRO WHERE PROGRAMACAO_ID = P.ID AND STATUS = '0') DATAHORA_REALIZADO_INICIO,
                    (SELECT MAX(DATAHORA) FROM TBPROGRAMACAO_REGISTRO WHERE PROGRAMACAO_ID = P.ID AND STATUS = '2') DATAHORA_REALIZADO_FIM,
                    T.DATA_PRODUCAO,
                    T.HORA_PRODUCAO,
                    (SELECT FIRST 1 LPAD(PR.OPERADOR_ID,10,' ') || RPAD(O.NOME,20,' ') FROM TBPROGRAMACAO_REGISTRO PR, TBOPERADOR O WHERE PR.PROGRAMACAO_ID = P.ID AND PR.STATUS = '2' AND O.CODIGO = PR.OPERADOR_ID ORDER BY PR.ID DESC)OPERADOR,
                    P.DATA PROGRAMACAO_DATA,
                    TRIM(P.STATUS) PROGRAMACAO_STATUS,
                    TRIM(
                    IIF(P.STATUS = 0 AND T.STATUS = 2,'CORTADO',
                    IIF(P.STATUS = 0 AND T.STATUS = 3,'LIBERADO',
                    (CASE P.STATUS
                    WHEN '0' THEN 'NÃO INICIADO'
                    WHEN '1' THEN 'PARADO'
                    WHEN '2' THEN 'EM ANDAMENTO'
                    WHEN '3' THEN 'FINALIZADO'
                    WHEN '6' THEN 'ENCERRADO'
                    ELSE 'INDEFINIDO' END)))) PROGRAMACAO_STATUS_DESCRICAO,
                    
                    (SELECT
                        LIST(DISTINCT LPAD(C.REMESSA_TALAO_ID, 4, '0'), ', ')                    --TALOES ORIGEM
                        || '@' ||
                        LIST(DISTINCT RE.DATA, ', ')                                            --DATA REMESSA ORIGEM
                    FROM
                        VWREMESSA_CONSUMO C,
                        TBREMESSA_CONSUMO_VINCULO V,
                        VWREMESSA RE

                    WHERE
                        V.CONSUMO_ID       = C.ID
                    AND V.REMESSA_ID       = T.REMESSA_ID
                    AND V.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    AND RE.REMESSA_ID      = C.REMESSA_ID)DADOS_ORIGEM,
                    
                    COALESCE((SELECT
                        LIST(DISTINCT(SELECT FIRST 1 DESCRICAO FROM TBUP P WHERE P.ID = S.UP_ID), ', ')

                    FROM
                        VWREMESSA_CONSUMO C,
                        TBREMESSA_CONSUMO_VINCULO V,
                        TBPRODUTO P,
                        VWREMESSA_TALAO_DETALHE D,
                        vwremessa_talao S

                    WHERE
                        V.CONSUMO_ID       = C.ID
                    AND P.CODIGO           = D.PRODUTO_ID
                    AND D.REMESSA_ID       = C.REMESSA_ID
                    AND D.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID 
                    AND V.REMESSA_ID       = T.REMESSA_ID
                    AND V.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    AND S.remessa_id = C.remessa_id
                    AND S.remessa_talao_id = C.remessa_talao_id), '') UP_DESTINO,
                    

                    /* 1 - NORMAL     */ CAST(IIF(R.TIPO = '1', '1',
                    /* 2 - VIP        */      IIF(R.TIPO = '2' AND R.REQUISICAO = '0','2',
                    /* 3 - REQUISICAO */      IIF(R.TIPO = '2' AND R.REQUISICAO = '1','3','0'))) AS VARCHAR(1))REMESSA_TIPO,

                    /* 1 - NORMAL     */ TRIM(CAST(IIF(R.TIPO = '1', 'NORMAL',
                    /* 2 - VIP        */      IIF(R.TIPO = '2' AND R.REQUISICAO = '0','VIP',
                    /* 3 - REQUISICAO */      IIF(R.TIPO = '2' AND R.REQUISICAO = '1','REQ','0'))) AS VARCHAR(15)))REMESSA_TIPO_DESCRICAO,
                    
                    CAST(IIF(R.TIPO = '1',0,1) AS VARCHAR(1)) REMESSA_TIPO_VIP,
                                     
                    COALESCE((SELECT MAX(C.COMPONENTE)
                        FROM VWREMESSA_CONSUMO C
                        WHERE
                            C.REMESSA_ID = T.REMESSA_ID
                        AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    ), 0) COMPONENTE,

                    TRIM(COALESCE((
                        SELECT MIN(X.STATUS)
                          FROM VWREMESSA_CONSUMO C,
                               SPC_CONSUMO_ESTOQUE_DISPONIVEL(C.ID) X
                         WHERE C.REMESSA_ID = T.REMESSA_ID
                           AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    ),0))STATUS_MP_CP,
                    
                    
                    TRIM((SELECT CASE AVG(CAST(P.STATUS AS NUMERIC(15,4)))
                             WHEN 0 THEN '0'
                             WHEN 3 THEN '2'
                             ELSE IIF(POSITION('6', LIST(P.STATUS)) > 0, '6', '1') END
                        FROM VWREMESSA_CONSUMO C,
                             TBREMESSA_CONSUMO_VINCULO V,
                             VWREMESSA_TALAO TT,
                             TBPROGRAMACAO P
                        WHERE
                            C.REMESSA_ID = T.REMESSA_ID
                        AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                        AND V.CONSUMO_ID = C.ID
                        AND P.TABELA_ID = TT.ID
                        AND P.TIPO = 'A'
                        AND TT.REMESSA_ID = V.REMESSA_ID
                        AND TT.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
                    )) STATUS_COMPONENTE,
                    
                    COALESCE(T.VIA_ETIQUETA,0) VIA_ETIQUETA,

                    (select first 1 d.localizacao_id from vwremessa_talao_detalhe d where d.remessa_id = t.remessa_id and d.remessa_talao_id  = t.remessa_talao_id) as localizacao_id
                FROM
                    VWREMESSA_TALAO T,
                    TBUP U,
                    VWREMESSA R,
                    TBMODELO M,
                    TBPROGRAMACAO P,
                    TBFAMILIA F

                WHERE 1=1
                AND T.QUANTIDADE > 0
                AND U.ID         = T.UP_ID
                AND R.REMESSA_ID = T.REMESSA_ID
                AND M.CODIGO     = T.MODELO_ID
                AND P.TABELA_ID  = T.ID
                AND P.GP_ID      = T.GP_ID
                AND P.UP_ID      = T.UP_ID
                AND (P.ESTACAO    = T.ESTACAO OR T.ESTACAO = 0)
                AND F.CODIGO     = M.FAMILIA_CODIGO
                
                /*@GP_ID*/
                /*@UP_ID*/
                /*@STATUS*/
                /*@ESTACAO*/
                )X

            WHERE
                1=1
                /*@ESTABELECIMENTO_ID*/
                /*@REMESSA_ID*/
                /*@REMESSA*/
                /*@TALAO_ID*/
                /*@UP_ORIGEM*/
                /*@PROGRAMACAO_STATUS*/
                /*@PROGRAMACAO_DATAHORA*/
                /*@PERIODO*/
                /*@TURNO*/
                
            ORDER BY
                DATAHORA_REALIZADO_INICIO, DATAHORA_INICIO
        ";

        $args = [
            '@FIRST_TALAO'          => $first,
            '@SKIP_TALAO'           => $skip,
            '@ESTABELECIMENTO_ID'   => $estabelecimento_id,
            '@REMESSA_ID'           => $remessa_id,
            '@REMESSA'              => $remessa,
            '@TALAO_ID'             => $talao_id,
            '@GP_ID'                => $gp_id,
            '@UP_ID'                => $up_id,
            '@UP_ORIGEM'	        => $up_origem,
            '@ESTACAO'              => $estacao,
            '@STATUS'               => $status,
            '@PROGRAMACAO_STATUS'   => $programacao_status,
            '@PROGRAMACAO_DATAHORA' => $programacao_datahora,
            '@PERIODO'		        => $periodo,
            '@TURNO'		        => $turno
        ];

        return $con->query($sql,$args);
    }

    /**
     * Listagem dos Talões Detalhado da Remessa
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaTalaoDetalhe($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $first                          = array_key_exists('FIRST_TALAO_DETALHE'          , $param) ? "FIRST " . $param->FIRST_TALAO_DETALHE : '';
        $skip                           = array_key_exists('SKIP_TALAO_DETALHE'           , $param) ? "SKIP  " . $param->SKIP_TALAO          : '';
        $remessa_id                     = array_key_exists('REMESSA_ID'                   , $param) ? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID                    , 99999999999) . ")" : '';
        $remessa_talao_id               = array_key_exists('REMESSA_TALAO_ID'             , $param) ? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID              , 99999999999) . ")" : '';
        $remessa_talao_detalhe_id       = array_key_exists('REMESSA_TALAO_DETALHE_ID'     , $param) ? "AND ID               IN (" . arrayToList($param->REMESSA_TALAO_DETALHE_ID      , 99999999999) . ")" : '';
        $remessa_talao_detalhe_id_not   = array_key_exists('REMESSA_TALAO_DETALHE_ID_NOT' , $param) ? "AND ID NOT           IN (" . arrayToList($param->REMESSA_TALAO_DETALHE_ID_NOT  , 99999999999) . ")" : '';
		$peca_conjunto                  = array_key_exists('PECA_CONJUNTO'                , $param) ? "AND PECA_CONJUNTO    IN (" . arrayToList($param->PECA_CONJUNTO                 , 99999999999) . ")" : '';
        $talao_id                       = array_key_exists('TALAO_ID'                     , $param) ? "AND TALAO_ID         IN (" . arrayToList($param->TALAO_ID                      , 99999999999) . ")" : '';
		$produto_id                     = array_key_exists('PRODUTO_ID'                   , $param) ? "AND PRODUTO_ID       IN (" . arrayToList($param->PRODUTO_ID                    , 99999999999) . ")" : '';
        $status                         = array_key_exists('STATUS'                       , $param) ? "AND STATUS           IN (" . arrayToList($param->STATUS                        , '#',"'"    ) . ")" : '';
		$aproveitamento_status          = array_key_exists('APROVEITAMENTO_STATUS'        , $param) ? "AND V.STATUS         IN (" . arrayToList($param->APROVEITAMENTO_STATUS         , '#',"'"    ) . ")" : '';
        $quantidade_saldo               = array_key_exists('QUANTIDADE_SALDO'             , $param) ? "AND QUANTIDADE_SALDO	 " . $param->QUANTIDADE_SALDO										  : '';
        
        
        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                Y.ID,
                Y.REMESSA,
                Y.REMESSA_ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_COMPONENTE,
                Y.TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.PECA_CONJUNTO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.COR_AMOSTRA,
                IIF(Y.COR_AMOSTRA2 = 0,Y.COR_AMOSTRA,Y.COR_AMOSTRA2)COR_AMOSTRA2,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.PERFIL,
                Y.QUANTIDADE,
                Y.QUANTIDADE_PRODUCAO,
                Y.QUANTIDADE_PRODUCAO_TMP,
                Y.QUANTIDADE_SALDO,
                Y.SALDO,
                Y.QUANTIDADE_ALTERN,
                Y.QUANTIDADE_ALTERN_PRODUCAO,
                Y.QUANTIDADE_ALTERN_PRODUCAO_TMP,
                Y.QUANTIDADE_ALTERN_SALDO,
                Y.UM,
                Y.UM_ALTERNATIVA,
                Y.STATUS,
                Y.QUANTIDADE_SOBRA,
                Y.QUANTIDADE_SOBRA_TMP,
                Y.REVISAO_ID,
                Y.TOLERANCIAM,
                Y.TOLERANCIAN,
                Y.TOLERANCIA_TIPO,
                Y.SOBRA_TIPO,
                Y.SOBRA_TIPO_DESCRICAO,
                Y.APROVEITAMENTO_ALOCADO,
                Y.APROVEITAMENTO_ALOCADO_ALTERN,
                Y.STATUS_DESCRICAO,
                Y.DATAHORA_PRODUCAO,
                Y.CONSUMO_ID,
                Y.UP_ID,
                Y.UP_DESCRICAO,
                Y.UP_DESTINO,
                Y.TALOES_ORIGEM,
                Y.TALOES_ORIGEM_MODELO_DESCRICAO,
                Y.OBS,
                Y.OPERADOR_ID,
                Y.OPERADOR_DESCRICAO,
                Y.GP_PERFIL,
                Y.FAMILIA_ID,
                Y.FAMILIA_PERFIL,
                Y.APROVEITAMENTOITENS,
                Y.VIA_ETIQUETA,
                Y.ALOCADO,
                Y.RELAXAMENTO,
                Y.LOCALIZACAO

            FROM
               (
                SELECT
                    X.ID,
                    X.REMESSA,
                    X.REMESSA_ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_COMPONENTE,
                    X.TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.PECA_CONJUNTO,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    X.COR_ID,
                    CAST(SUBSTRING(X.COR_DADOS FROM  1 FOR 20) AS VARCHAR(20)) COR_DESCRICAO,
                    CAST(SUBSTRING(X.COR_DADOS FROM 21 FOR 30) AS INTEGER) COR_AMOSTRA,
                    CAST(IIF(SUBSTRING(X.COR_DADOS FROM 52 FOR 30) = '', 0, SUBSTRING(X.COR_DADOS FROM 52 FOR 30)) AS INTEGER) COR_AMOSTRA2,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.PERFIL,
                    X.QUANTIDADE,
                    X.QUANTIDADE_PRODUCAO,
                    X.QUANTIDADE_PRODUCAO_TMP,
                    X.QUANTIDADE_SALDO,
                    X.QUANTIDADE_SALDO as SALDO,
                    X.QUANTIDADE_ALTERN,
                    X.QUANTIDADE_ALTERN_PRODUCAO,
                    X.QUANTIDADE_ALTERN_PRODUCAO_TMP,
                    X.QUANTIDADE_ALTERN_SALDO,
                    X.UM,
                    X.UM_ALTERNATIVA,
                    X.STATUS,
                    X.QUANTIDADE_SOBRA,
                    X.QUANTIDADE_SOBRA_TMP,
                    coalesce(X.REVISAO_ID, X.REVISAO_ID, 0) as REVISAO_ID,
                    X.TOLERANCIA as TOLERANCIAM,
                    X.TOLERANCIA as TOLERANCIAN,
                    X.TOLERANCIA_TIPO,
                    X.SOBRA_TIPO,
                    IIF(X.SOBRA_TIPO = 'M', 'Matéria-Prima', 'Produção') SOBRA_TIPO_DESCRICAO,
                    X.APROVEITAMENTO_ALOCADO,
                    X.APROVEITAMENTO_ALOCADO_ALTERN,
                    X.STATUS_DESCRICAO,
                    X.DATAHORA_PRODUCAO,
                    X.CONSUMO_ID,
                    X.UP_ID,
                    X.UP_DESCRICAO,
                    X.UP_DESTINO,
                    X.TALOES_ORIGEM,
                    X.TALOES_ORIGEM_MODELO_DESCRICAO,
                    X.OBS,
                    CAST(SUBSTRING(X.OPERADOR FROM   1 FOR 10) AS INTEGER) OPERADOR_ID,
                    CAST(SUBSTRING(X.OPERADOR FROM  11 FOR 20) AS VARCHAR(20)) OPERADOR_DESCRICAO,
                    X.GP_PERFIL,
                    X.FAMILIA_ID,
                    X.FAMILIA_PERFIL,
                    X.APROVEITAMENTOITENS,
                    X.VIA_ETIQUETA,
                    X.ALOCADO,
                    X.RELAXAMENTO,
                    X.LOCALIZACAO
                FROM
                    (SELECT
                        D.ID,
                        R.REMESSA,
                        D.REMESSA_ID,
                        LPAD(D.REMESSA_TALAO_ID,4,'0')REMESSA_TALAO_ID,
                        R.COMPONENTE REMESSA_COMPONENTE,
                        T.ID TALAO_ID,
                        D.ID as REMESSA_TALAO_DETALHE_ID,
                        D.PECA_CONJUNTO,
                        LPAD(D.PRODUTO_ID,5,'0') PRODUTO_ID,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        D.MODELO_ID,
                        (SELECT FIRST 1 DESCRICAO FROM TBMODELO WHERE CODIGO = P.MODELO_CODIGO)MODELO_DESCRICAO,
                        LPAD(D.COR_ID,4,'0') COR_ID,
    
                        (SELECT
                            RPAD(C.DESCRICAO,20) || 
                            COALESCE(
                                (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                                   FROM TBCOR C1, TBCOR_COMPOSICAO CC
                                  WHERE CC.COR_ID = C.CODIGO
                                    AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                                 LPAD(C.AMOSTRA,30))CORES
                          FROM TBCOR C
                         WHERE C.CODIGO = P.COR_CODIGO)COR_DADOS,
    
                        (SELECT FIRST 1 DENSIDADE FROM TBMODELO WHERE CODIGO = P.MODELO_CODIGO)DENSIDADE,
                        (SELECT FIRST 1 ESPESSURA FROM TBMODELO WHERE CODIGO = P.MODELO_CODIGO)ESPESSURA,
                        P.GRADE_CODIGO GRADE_ID,
                        D.TAMANHO,
                        (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO))TAMANHO_DESCRICAO,
                        D.PERFIL,   
                        D.DATAHORA_PRODUCAO,
                        D.QUANTIDADE,
                        D.QUANTIDADE_PRODUCAO,
                        D.QUANTIDADE_PRODUCAO_TMP,
                        D.QUANTIDADE_SALDO,
                        D.QUANTIDADE_ALTERN,
                        D.QUANTIDADE_ALTERN_PRODUCAO,
                        D.QUANTIDADE_ALTERN_PRODUCAO_TMP,
                        D.QUANTIDADE_ALTERN_SALDO,
                        F.UNIDADEMEDIDA_SIGLA UM,
                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                        D.STATUS,
                        D.QUANTIDADE_SOBRA,
                        D.QUANTIDADE_SOBRA_TMP,

                        coalesce(((SELECT FIRST 1
                                        CAST(COALESCE(RTF.QUANTIDADE,0) AS NUMERIC(15,4)) QUANTIDADE
                                    FROM (
                                        SELECT j.REMESSA_TALAO_ID AS REMESSA_TALAO_ID,
                                               j.MODELO_ID        AS MODELO_ID,
                                               FFM.TIPO_CODIGO    AS TIPO_ID,
                                               FFM.TIPO_DESCRICAO AS TIPO_DESCRICAO,
                                               j.remessa_id

                                          FROM TBMODELO M,
                                               TBFAMILIA_FICHA_MODELO FFM,
                                               (SELECT
                                                        d1.modelo_id,
                                                        r.numero as remessa_id,
                                                        d1.id as remessa_talao_id,
                                                        d1.id
                                                    FROM
                                                        VWREMESSA_TALAO_DETALHE k,
                                                        VWREMESSA_CONSUMO C,
                                                        TBREMESSA_TALAO_VINCULO V,
                                                        VWREMESSA_TALAO_DETALHE D1,
                                                        tbremessa r
                                                    
                                                    
                                                    WHERE
                                                        k.ID = D.ID
                                                    AND k.REMESSA_ID = C.REMESSA_ID
                                                    AND k.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                                                    AND C.ID = V.CONSUMO_ID
                                                    AND D1.ID = V.TABELA_ID
                                                    and r.numero = d1.remessa_id
                                                ) j
                                         WHERE M.CODIGO           = j.MODELO_ID
                                           AND FFM.FAMILIA_CODIGO = M.FAMILIA_CODIGO
                                           and FFM.TIPO_CODIGO    = 14
                                        ) X
                                        LEFT JOIN TBMODELO_FICHA_TECNICA MFT
                                               ON MFT.TIPO_CODIGO   = X.TIPO_ID
                                              AND MFT.MODELO_CODIGO = X.MODELO_ID
                                        LEFT JOIN TBREMESSA_TALAO_FICHA  RTF
                                               ON RTF.TIPO_ID           = X.TIPO_ID
                                              AND RTF.REMESSA_TALAO_ID  = X.REMESSA_TALAO_ID
                                              and RTF.remessa_id        = X.REMESSA_ID)),0) as RELAXAMENTO,

                        (SELECT FIRST 1 ID FROM TBREVISAO WHERE TALAO_ID = D.ID) as REVISAO_ID,
                        
                        --Tolerancia
                        COALESCE((select first 1 TOLERANCIA from(SELECT FIRST 1
                            COALESCE(IIF(P.TOLERANCIA_TIPO = 'Q',P.TOLERANCIA_QTD,
                            IIF(P.TOLERANCIA_TIPO = 'P',P.TOLERANCIA_PERC,-1)),-1) as TOLERANCIA,
                            1 as TIPO
                            FROM TBPRODUTO_FICHA P WHERE P.PRODUTO_CODIGO = D.PRODUTO_ID
    
                            union
                            SELECT FIRST 1
    
                            COALESCE(IIF(G.TOLERANCIA_TIPO = 'Q',G.TOLERANCIA_QTD,
                            IIF(G.TOLERANCIA_TIPO = 'P',G.TOLERANCIA_PERC,0)),0) as TOLERANCIA,
                            0 as TIPO
                            FROM TBFAMILIA G,TBPRODUTO S WHERE G.CODIGO = S.FAMILIA_CODIGO AND S.CODIGO = D.PRODUTO_ID) where TOLERANCIA >= 0 order by TIPO DESC),0) AS TOLERANCIA,
                        
                        --Tipo de Tolerancia
                        TRIM(COALESCE((select first 1 TOLERANCIA_TIPO from(SELECT FIRST 1 P.TOLERANCIA_TIPO,
                            COALESCE(IIF(P.TOLERANCIA_TIPO = 'Q',0,
                            IIF(P.TOLERANCIA_TIPO = 'P',0,-1)),-1) as TOLERANCIA,
                            1 as TIPO
                            FROM TBPRODUTO_FICHA P WHERE P.PRODUTO_CODIGO = D.PRODUTO_ID
    
                            union
                            SELECT FIRST 1
                            G.TOLERANCIA_TIPO,
                            COALESCE(IIF(G.TOLERANCIA_TIPO = 'Q',0,
                            IIF(G.TOLERANCIA_TIPO = 'P',0,0)),0) as TOLERANCIA,
                            0 as TIPO
                            FROM TBFAMILIA G,TBPRODUTO S WHERE G.CODIGO = S.FAMILIA_CODIGO AND S.CODIGO = D.PRODUTO_ID) where TOLERANCIA >= 0 order by TIPO DESC),'N')) AS TOLERANCIA_TIPO,
                        
                        --Permitir sobra de material ou materia prima M->MATERIA PRIMA P->PRODUCAO
                        TRIM(COALESCE((select first 1 SOBRA_TIPO from(SELECT FIRST 1 P.SOBRA_TIPO,
                            COALESCE(IIF(P.SOBRA_TIPO = 'M',0,
                            IIF(P.SOBRA_TIPO = 'P',0,-1)),-1) as TOLERANCIA,
                            1 as TIPO
                            FROM TBPRODUTO_FICHA P WHERE P.PRODUTO_CODIGO = D.PRODUTO_ID
    
                            union
                            SELECT FIRST 1
                            G.SOBRA_TIPO,
                            COALESCE(IIF(G.SOBRA_TIPO = 'M',0,
                            IIF(G.SOBRA_TIPO = 'P',0,0)),0) as TOLERANCIA,
                            0 as TIPO
                            FROM TBFAMILIA G,TBPRODUTO S WHERE G.CODIGO = S.FAMILIA_CODIGO AND S.CODIGO = D.PRODUTO_ID) where TOLERANCIA >= 0 order by TIPO DESC),'M')) AS SOBRA_TIPO,
                        
    
                        COALESCE((SELECT FIRST 1 SUM(V.QUANTIDADE) 
                             FROM TBREMESSA_TALAO_VINCULO V 
                            WHERE 1=1
                              /*@APROVEITAMENTO_STATUS*/
                              AND V.TIPO = 'R'
                              AND V.REMESSA_TALAO_DETALHE_ID = D.ID),0) APROVEITAMENTO_ALOCADO,
                        COALESCE((SELECT FIRST 1 SUM(V.QUANTIDADE_ALTERNATIVA)
                             FROM TBREMESSA_TALAO_VINCULO V 
                            WHERE 1=1
                              /*@APROVEITAMENTO_STATUS*/
                              AND V.TIPO = 'R'
                              AND V.REMESSA_TALAO_DETALHE_ID = D.ID),0) APROVEITAMENTO_ALOCADO_ALTERN,
                        (CASE
                            D.STATUS
                        WHEN 1 THEN 'EM ABERTO'
                        WHEN 2 THEN 'EM PRODUÇÃO'
                        WHEN 3 THEN 'PRODUZIDO'
                        WHEN 6 THEN 'ENCERRADO'
                        ELSE 'INDEFINIDO' END) STATUS_DESCRICAO,
    
                        T.UP_ID,
                        (SELECT FIRST 1 DESCRICAO FROM TBUP P WHERE P.ID = T.UP_ID)UP_DESCRICAO,
                             
                        (SELECT FIRST 1 ID FROM VWREMESSA_CONSUMO C WHERE C.REMESSA_ID = R.REMESSA_ID AND C.REMESSA_TALAO_ID = T.ID AND C.REMESSA_TALAO_DETALHE_ID = D.ID)CONSUMO_ID,
    
                        (SELECT LIST(DISTINCT LPAD(C.REMESSA_TALAO_ID,4,'0'), ', ')
                         FROM TBREMESSA_CONSUMO_VINCULO V, VWREMESSA_CONSUMO C
                         WHERE C.ID = V.CONSUMO_ID
                           AND V.REMESSA_TALAO_DETALHE_ID = D.ID)TALOES_ORIGEM,
                           
                        COALESCE((SELECT LIST(DISTINCT(SELECT FIRST 1 DESCRICAO FROM TBUP P WHERE P.ID = T.UP_ID), ', ')
                         FROM TBREMESSA_CONSUMO_VINCULO V, VWREMESSA_CONSUMO C, vwremessa_talao T
                         WHERE C.ID = V.CONSUMO_ID
                           AND V.REMESSA_TALAO_DETALHE_ID = D.ID
                           AND T.remessa_id = C.remessa_id
                           AND T.remessa_talao_id = C.remessa_talao_id),'') AS UP_DESTINO,
                               
                        (SELECT
                            LIST(
                            DISTINCT
                            CAST(IIF(C.REMESSA_TALAO_DETALHE_ID = 0,
                                (SELECT LIST(M.DESCRICAO)
                                   FROM VWREMESSA_TALAO T, TBMODELO M
                                  WHERE T.MODELO_ID = M.CODIGO
                                    AND T.REMESSA_ID = C.REMESSA_ID
                                    AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID),
                            
                                 (SELECT LIST(P.DESCRICAO)
                                   FROM VWREMESSA_TALAO_DETALHE TD, TBPRODUTO P
                                  WHERE TD.PRODUTO_ID = P.CODIGO
                                    AND TD.ID = C.REMESSA_TALAO_DETALHE_ID)) AS VARCHAR(1000)), ', ')ORIGEM_MODELO
                            FROM    
                                TBREMESSA_CONSUMO_VINCULO V,
                                VWREMESSA_CONSUMO C
                            
                            WHERE
                                C.ID = V.CONSUMO_ID
                            AND V.REMESSA_TALAO_DETALHE_ID = D.ID)TALOES_ORIGEM_MODELO_DESCRICAO,
    
                        (SELECT trim(LIST(DISTINCT trim((SELECT FIRST 1 trim(R.OB)||'-'||trim(k.classificacao) FROM TBREVISAO R, tbrevisao_ob k WHERE k.ob = r.ob and  R.ID = v.TABELA_ID)), ','))
                         FROM TBREMESSA_TALAO_VINCULO V
                         WHERE V.TALAO_ID = T.ID      
                           AND V.CONSUMO_ID = (SELECT FIRST 1 ID FROM VWREMESSA_CONSUMO C WHERE C.REMESSA_ID = R.REMESSA_ID AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID AND C.REMESSA_TALAO_DETALHE_ID = D.ID)
                           AND V.TIPO = 'R')OBS,
    
                        (SELECT FIRST 1
                            LPAD(PR.OPERADOR_ID,10,' ') || RPAD(O.NOME,20,' ')
                        FROM
                            TBPROGRAMACAO_REGISTRO PR,
                            TBOPERADOR O
                        WHERE
                            PR.PROGRAMACAO_ID = (SELECT FIRST 1 ID FROM TBPROGRAMACAO WHERE TABELA_ID = T.ID)
                        AND PR.STATUS = '2'
                        AND O.CODIGO = PR.OPERADOR_ID
                        ORDER BY PR.ID DESC)OPERADOR,
    
                        (SELECT FIRST 1 G.PERFIL FROM TBGP G WHERE G.ID = T.GP_ID) GP_PERFIL,
                        F.CODIGO FAMILIA_ID,
                        F.PERFIL FAMILIA_PERFIL,
                    
                    COALESCE((SELECT LIST(
                    COALESCE((SELECT FIRST 1 P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = V.PRODUTO_ID),'')||'#@#'||
                    COALESCE(V.ID,0)||'#@#'||
                    COALESCE(V.QUANTIDADE,0)||'#@#'||coalesce(v.status,0),'#@@#') APROVEITAMENTOITENS
                    FROM TBREMESSA_TALAO_VINCULO V WHERE V.REMESSA_TALAO_DETALHE_ID = D.ID ),'') APROVEITAMENTOITENS,
                    COALESCE(T.VIA_ETIQUETA,0) VIA_ETIQUETA,
                    coalesce((
                        select first 1 'ULTIMA OPERACAO DE ESTOQUE:'||i.observacao||' ,REALIZADA POR '||u.usuario||' EM '||

                            (
                               lpad(EXTRACT(DAY FROM i.datahora),2,'0') || '/' ||
                               lpad(EXTRACT(MONTH FROM i.datahora),2,'0') || '/' ||
                               lpad(EXTRACT(YEAR FROM i.datahora),4,'0') || ' as ' ||
                               lpad(EXTRACT(hour FROM i.datahora),2,'0') || ':' ||
                               lpad(EXTRACT(minute FROM i.datahora),2,'0')
                            ) || '.'

                            from tbestoque_transacao_item i, tbusuario u
                            where i.remessa_item_controle = D.ID
                            and u.codigo = i.usuario_codigo
                            order by observacao
                    ),'') as ALOCADO,
                    coalesce((select first 1 lpad(l.codigo,3,0)||' - '||l.descricao as LOCALIZACAO from tblocalizacao l where l.codigo = d.localizacao_id),'') as LOCALIZACAO
                
                    FROM
                        VWREMESSA_TALAO_DETALHE D,
                        TBPRODUTO P,
                        VWREMESSA_TALAO T,
                        TBFAMILIA F,
                        VWREMESSA R
        
                    WHERE
                        P.CODIGO           = D.PRODUTO_ID
                    AND T.REMESSA_ID       = D.REMESSA_ID
                    AND T.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                    AND F.CODIGO           = P.FAMILIA_CODIGO
                    AND D.REMESSA_ID       = R.REMESSA_ID
                    )X
    
                WHERE
                    1=1
                    AND QUANTIDADE_SALDO is NOT NULL
                    /*@REMESSA_ID*/
                    /*@REMESSA_TALAO_ID*/
                    /*@REMESSA_TALAO_DETALHE_ID*/
                    /*@REMESSA_TALAO_DETALHE_ID_NOT*/
                    /*@PECA_CONJUNTO*/
                    /*@TALAO_ID*/
                    /*@QUANTIDADE_SALDO*/
                    /*@PRODUTO_ID*/
                )Y
        ";

        $args = [
            '@FIRST'                        => $first,
            '@SKIP'                         => $skip,
            '@REMESSA_ID'                   => $remessa_id,
            '@REMESSA_TALAO_ID'             => $remessa_talao_id,
            '@REMESSA_TALAO_DETALHE_ID'     => $remessa_talao_detalhe_id,
            '@REMESSA_TALAO_DETALHE_ID_NOT' => $remessa_talao_detalhe_id_not,
            '@PECA_CONJUNTO'                => $peca_conjunto,
            '@TALAO_ID'                     => $talao_id,
			'@QUANTIDADE_SALDO'             => $quantidade_saldo,
			'@APROVEITAMENTO_STATUS'        => $aproveitamento_status,
			'@PRODUTO_ID'                   => $produto_id
        ];
        
        $ret = $con->query($sql,$args);

        return $ret;
    }

    /**
     * Consulta projeção de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
   
        $remessa_id			= isset($param->REMESSA_ID      )		? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")"	: '';
        $remessa_talao_id	= isset($param->REMESSA_TALAO_ID)		? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")"	: '';
        $familia_id			= isset($param->FAMILIA_ID      )		? "AND FAMILIA_ID       IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")"	: '';
        $familia_id_consumo = isset($param->FAMILIA_ID_CONSUMO) && !empty($param->FAMILIA_ID_CONSUMO)	
								? "AND FAMILIA_CODIGO   =	" . $param->FAMILIA_ID_CONSUMO
								: '';
        $status				= isset($param->STATUS_CONSUMO  )		? "AND STATUS           IN (" . arrayToList($param->STATUS_CONSUMO  , "'#'","'") . ")"	: '';

        $sql =
        "
            SELECT
                iif(X.OB1 = '', iif(X.OB2 = '', '',X.OB2),iif(X.OB2 = '', X.OB1,(X.OB1||', '||X.OB2))) AS OB,
                X.ID,
                X.REMESSA_ID,
                X.REMESSA_TALAO_ID,
                X.REMESSA_TALAO_DETALHE_ID,
                X.CONTROLE,
                X.DENSIDADE,
                X.ESPESSURA,
                SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                X.PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.MODELO_ID,
                X.MODELO_DESCRICAO,
                X.COR_ID,
                X.COR_DESCRICAO,
                X.GRADE_ID,
                X.TAMANHO,
                X.TAMANHO_DESCRICAO,
                X.LOCALIZACAO_ID,
                SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,  
                X.QUANTIDADE_PROJECAO,
                X.QUANTIDADE_PROJECAO_ALTERNATIVA, 
                X.QUANTIDADE, 
                X.QUANTIDADE_ALTERNATIVA, 
                X.UM,     
                X.UM_ALTERNATIVA,
                X.STATUS,
                X.PECA_CONJUNTO,
                X.UP_ID,
                X.UP_DESCRICAO,
                X.TALAO_MODELO_ID,
                X.TALAO_MODELO_DESCRICAO,
                X.DATAHORA_INICIO,
                X.FAMILIA_CODIGO,
                X.TALAO_PRODUTO_ID,
                X.TALAO_TAMANHO,
                X.TALAO_COR_CLASSE
            FROM
                (
                SELECT
                    C.ID,
                    C.REMESSA_ID,
                    C.REMESSA_TALAO_ID,
                    IIF(F.CONTROLE_TALAO = 'A',0,C.REMESSA_TALAO_DETALHE_ID) REMESSA_TALAO_DETALHE_ID,
                    C.CONTROLE,
                    C.DENSIDADE,
                    C.ESPESSURA,
                    (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                    LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                    P.DESCRICAO           PRODUTO_DESCRICAO,
                    P.GRADE_CODIGO        GRADE_ID,
                    COALESCE(C.TAMANHO,0)TAMANHO,
                    (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                    C.QUANTIDADE_SALDO QUANTIDADE,
                    C.QUANTIDADE_ALTERNATIVA_SALDO QUANTIDADE_ALTERNATIVA,
                    C.QUANTIDADE QUANTIDADE_PROJECAO,
                    C.QUANTIDADE_ALTERNATIVA QUANTIDADE_PROJECAO_ALTERNATIVA,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    P.MODELO_CODIGO MODELO_ID,
                    (SELECT FIRST 1 M.DESCRICAO FROM TBMODELO M WHERE M.CODIGO = P.MODELO_CODIGO) MODELO_DESCRICAO,
                    P.COR_CODIGO COR_ID,
                    (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                    P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                    (SELECT FIRST 1 (S.PERFIL ||
                            (SELECT FIRST 1 DESCRICAO
                              FROM TBPERFIL P
                             WHERE P.TABELA = 'SKU'
                               AND P.ID     = S.PERFIL))
                       FROM VWSKU S
                      WHERE S.MODELO_ID = P.MODELO_CODIGO
                        AND S.COR_ID    = P.COR_CODIGO
                        AND S.TAMANHO   = C.TAMANHO) PERFIL_SKU,
                    C.STATUS,
                    D.PECA_CONJUNTO,
                    T.UP_ID,
                    (SELECT FIRST 1 DESCRICAO FROM TBUP WHERE ID = T.UP_ID) UP_DESCRICAO,
                    T.MODELO_ID TALAO_MODELO_ID,
                    M.DESCRICAO TALAO_MODELO_DESCRICAO,
                    T.TAMANHO TALAO_TAMANHO,
                    T.PRODUTO_ID TALAO_PRODUTO_ID,
                    (SELECT FIRST 1 C.CLASSE||'.'||C.SUBCLASSE
                       FROM TBCOR C, TBPRODUTO PP
                      WHERE PP.CODIGO = T.PRODUTO_ID
                        AND C.CODIGO = PP.COR_CODIGO) TALAO_COR_CLASSE,
                    PR.DATAHORA_INICIO,
                    PR.DATAHORA_FIM,
                    P.FAMILIA_CODIGO,
                    
                    coalesce((SELECT LIST( (select list(DISTINCT

                        (SELECT LIST(DISTINCT (SELECT FIRST 1 R.OB FROM TBREVISAO R WHERE R.ID = x.TABELA_ID and r.ob > 0
                        ), ', ')
                         FROM TBREMESSA_TALAO_VINCULO x
                         WHERE 1 = 1
                           AND x.TALAO_ID = g.id
                           AND x.CONSUMO_ID = k.id
                           AND x.TIPO = 'R'
                         )

                    ,', ') from VWREMESSA_CONSUMO k LEFT JOIN
                        VWREMESSA_TALAO_DETALHE s ON s.ID = k.REMESSA_TALAO_DETALHE_ID LEFT JOIN
                        VWREMESSA_TALAO g ON g.REMESSA_ID = k.REMESSA_ID AND g.REMESSA_TALAO_ID = k.REMESSA_TALAO_ID
                        where k.remessa_talao_detalhe_id = y.tabela_id)
                    , ', ')
                     FROM TBREMESSA_TALAO_VINCULO y
                     WHERE 1 = 1
                       AND y.TALAO_ID = t.id
                       AND y.CONSUMO_ID = c.id
                       AND y.TIPO = 'D'
                     ),'')OB1,

                     coalesce((SELECT LIST(DISTINCT (SELECT FIRST 1 R.OB FROM TBREVISAO R WHERE R.ID = y.TABELA_ID and r.ob > 0
                     ), ', ')
                     FROM TBREMESSA_TALAO_VINCULO y
                     WHERE 1 = 1
                       AND y.TALAO_ID = t.id
                       AND y.CONSUMO_ID = c.id
                       AND y.TIPO = 'R'
                     ),'') OB2
                 
                FROM
                    VWREMESSA_CONSUMO C LEFT JOIN
                    VWREMESSA_TALAO_DETALHE D ON D.ID = C.REMESSA_TALAO_DETALHE_ID LEFT JOIN
                    VWREMESSA_TALAO T ON T.REMESSA_ID = C.REMESSA_ID AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID LEFT JOIN
                    TBPROGRAMACAO PR ON PR.TABELA_ID = T.ID AND PR.TIPO = 'A',
                    TBMODELO M,
                    TBPRODUTO P,
                    TBFAMILIA F
                WHERE
                    C.PRODUTO_ID       = P.CODIGO
                AND F.CODIGO           = P.FAMILIA_CODIGO
                AND M.CODIGO           = T.MODELO_ID
                ) X
            WHERE
                1=1
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@FAMILIA_ID*/
            /*@FAMILIA_ID_CONSUMO*/
            /*@STATUS_CONSUMO*/
                        ORDER BY UP_DESCRICAO, REMESSA_TALAO_ID, FAMILIA_CODIGO, PECA_CONJUNTO, CONTROLE, CLASSE, SUBCLASSE, PRODUTO_DESCRICAO
        ";

        $args = [
            '@REMESSA_ID'			=> $remessa_id,
            '@REMESSA_TALAO_ID'		=> $remessa_talao_id,
            '@FAMILIA_ID'			=> $familia_id,
            '@FAMILIA_ID_CONSUMO'	=> $familia_id_consumo,
            '@STATUS_CONSUMO'		=> $status
        ];
        
        return $con->query($sql,$args);
    }

    /**
     * Consulta os grupos de produção da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumoGp($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            SELECT DISTINCT
                G.ID,
                G.DESCRICAO

            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBGP G

            WHERE
                C.PRODUTO_ID = P.CODIGO
            AND G.FAMILIA_ID = P.FAMILIA_CODIGO
            AND C.REMESSA 	 = :REMESSA
        ";

        $args = [
            ':REMESSA' => $param->REMESSA
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta perfis da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumoPerfil($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $remessa_id = isset($param->REMESSA_ID) ? "AND R.REMESSA_ID IN  (" . Helpers::arrayToList($param->REMESSA_ID, 999999999) . ")" : '';
        $remessa    = isset($param->REMESSA   ) ? "AND R.REMESSA    IN  ('" . Helpers::arrayToList($param->REMESSA  , 999999999) . "')" : '';

        $sql =
        "
            SELECT DISTINCT
                B.PERFIL

            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBMODELO_BLOQUEIO B,
                VWREMESSA R

            WHERE
                C.PRODUTO_ID = P.CODIGO
            AND B.MODELO_ID  = P.MODELO_CODIGO
            AND B.COR_ID     = P.COR_CODIGO
            AND B.TAMANHO    = C.TAMANHO
            AND C.REMESSA_ID = R.REMESSA_ID
            /*@REMESSA*/
            /*@REMESSA_ID*/
        ";

        $args = [
            '@REMESSA'    => $remessa,
            '@REMESSA_ID' => $remessa_id
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumoFamilia($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            SELECT DISTINCT
                R.REMESSA_ID,
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(R.ESTABELECIMENTO_ID, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = R.ESTABELECIMENTO_ID) ESTABELECIMENTO_DESCRICAO,
                R.REQUISICAO

            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBFAMILIA F,
                VWREMESSA R

            WHERE
                P.CODIGO     = C.PRODUTO_ID
            AND F.CODIGO     = P.FAMILIA_CODIGO
            AND R.REMESSA_ID = C.REMESSA_ID
            AND R.REMESSA    = :REMESSA
        ";

        $args = [
            ':REMESSA' => $param->REMESSA
        ];

        return $con->query($sql,$args);
    }
	
    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumoFamiliaPorRemessaId($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            SELECT DISTINCT
				F.CODIGO FAMILIA_ID,
				F.DESCRICAO FAMILIA_DESCRICAO

			FROM
				VWREMESSA_CONSUMO C,
				TBPRODUTO P,
				TBFAMILIA F
			WHERE
				C.REMESSA_ID = :REMESSA_ID
			AND P.CODIGO = C.PRODUTO_ID
			AND F.CODIGO = P.FAMILIA_CODIGO
        ";

        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID
        ];

        return $con->query($sql,$args);
    }
    
    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function requisicaoConsumoFamilia($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        /*
        $sql = "
            SELECT DISTINCT
                R.REMESSA,
                R.REMESSA_ID,
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(R.ESTABELECIMENTO_ID, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = R.ESTABELECIMENTO_ID) ESTABELECIMENTO_DESCRICAO

            FROM
                TBREQUISICAO C,
                TBPRODUTO P,
                TBFAMILIA F,
                VWREMESSA R

            WHERE
                P.CODIGO     = C.PRODUTO_ID
            AND F.CODIGO     = P.FAMILIA_CODIGO
            AND R.REMESSA_ID = C.REMESSA
            AND C.REMESSA_GERADA = 0
            AND C.CONSUMO = 1

        ";
       */
        
        $sql = "
            SELECT DISTINCT
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(C.ESTABELECIMENTO_ID, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = C.ESTABELECIMENTO_ID) ESTABELECIMENTO_DESCRICAO

            FROM
                TBREQUISICAO C,
                TBPRODUTO P,
                TBFAMILIA F

            WHERE
                P.CODIGO     = C.PRODUTO_ID
            AND F.CODIGO     = P.FAMILIA_CODIGO
            AND C.REMESSA_GERADA = 0
            AND C.CONSUMO = 1 ";
        
        return $con->query($sql);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function pedidoConsumoFamilia($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $param->REMESSA = str_replace('PD', '', $param->REMESSA);
                
        if ( $param->REMESSA == '' ) {
            log_erro('Número do pedido inválido');
        }
            
        $sql =
        "
            SELECT FIRST 1
                ('PD' || P.PEDIDO) REMESSA,
                P.PEDIDO REMESSA_ID,
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO FAMILIA_DESCRICAO,
                LPAD(P.ESTABELECIMENTO_CODIGO, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = P.ESTABELECIMENTO_CODIGO) ESTABELECIMENTO_DESCRICAO,
                '3' REQUISICAO

            FROM
                TBPEDIDO P,
                TBPEDIDO_ITEM I,
                TBPRODUTO PR,
                TBFAMILIA F

            WHERE
                 I.PEDIDO   = P.PEDIDO
            AND PR.CODIGO   = I.PRODUTO_CODIGO
            AND  F.CODIGO   = PR.FAMILIA_CODIGO
            AND  I.SITUACAO = '1'
            AND  P.SITUACAO = '1'
            AND  P.STATUS   = '1'
            AND  P.PEDIDO   = :REMESSA
        ";

        $args = [
            ':REMESSA' => $param->REMESSA
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function reposicaoConsumoFamilia($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
            
        $sql =
        "
            SELECT DISTINCT
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                1 ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = 1) ESTABELECIMENTO_DESCRICAO,
                '0' REQUISICAO

            FROM
                TBFAMILIA F

            WHERE F.HABILITA_REPOSICAO = '1'
        ";
        
        return $con->query($sql);
    }
    
    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaConsumoNecessidade($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $remessa_id       = isset($param->REMESSA_ID      ) ? "AND C.REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")" : '';
        $remessa_talao_id = isset($param->REMESSA_TALAO_ID) ? "AND C.REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")" : '';
        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        $requisicao       = (isset($param->REQUISICAO) && ($param->REQUISICAO == '0')) ? '0' : '1';
        
        $order_componente = strstr($param->REMESSA, '1D') ? 'ORDER BY MODELO_PRIORIDADE, ID' : 'ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID';

        $sql =
        "
            SELECT
                'NORMAL' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.ACRESCIMO,
                Y.UM,
                Y.LOCALIZACAO_ID,
                (SELECT FIRST 1
                    IIF(Y.TAMANHO =  1,M.T01, IIF(Y.TAMANHO =  2,M.T02, IIF(Y.TAMANHO =  3,M.T03, IIF(Y.TAMANHO =  4,M.T04, IIF(Y.TAMANHO =  5,M.T05,
                    IIF(Y.TAMANHO =  6,M.T06, IIF(Y.TAMANHO =  7,M.T07, IIF(Y.TAMANHO =  8,M.T08, IIF(Y.TAMANHO =  9,M.T09, IIF(Y.TAMANHO = 10,M.T10,
                    IIF(Y.TAMANHO = 11,M.T11, IIF(Y.TAMANHO = 12,M.T12, IIF(Y.TAMANHO = 13,M.T13, IIF(Y.TAMANHO = 14,M.T14, IIF(Y.TAMANHO = 15,M.T15,
                    IIF(Y.TAMANHO = 16,M.T16, IIF(Y.TAMANHO = 17,M.T17, IIF(Y.TAMANHO = 18,M.T18, IIF(Y.TAMANHO = 19,M.T19, IIF(Y.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                    FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = Y.MODELO_ID) FATOR_DIVISAO,
                    
                (SELECT
                    CASE Y.TAMANHO
                        WHEN 01 THEN PC.MI01
                        WHEN 02 THEN PC.MI02
                        WHEN 03 THEN PC.MI03
                        WHEN 04 THEN PC.MI04
                        WHEN 05 THEN PC.MI05
                        WHEN 06 THEN PC.MI06
                        WHEN 07 THEN PC.MI07
                        WHEN 08 THEN PC.MI08
                        WHEN 09 THEN PC.MI09
                        WHEN 10 THEN PC.MI10
                        WHEN 11 THEN PC.MI11
                        WHEN 12 THEN PC.MI12
                        WHEN 13 THEN PC.MI13
                        WHEN 14 THEN PC.MI14
                        WHEN 15 THEN PC.MI15
                        WHEN 16 THEN PC.MI16
                        WHEN 17 THEN PC.MI17
                        WHEN 18 THEN PC.MI18
                        WHEN 19 THEN PC.MI19
                        WHEN 20 THEN PC.MI20
                        ELSE 99999999999
                    END
                 FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = Y.MODELO_ID) FATOR_DIVISAO_DETALHE

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    AVG(X.ACRESCIMO) ACRESCIMO,
                    SUM(X.QUANTIDADE_ALTERNATIVA) QUANTIDADE_ALTERNATIVA,

                    IIF(:REQUISICAO = '1',
                        IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REQUISICAO INCLUI ACRESCIMO
                                FROM TBCONTROLE_N U
                               WHERE U.ID = 229) = '1',
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF((SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2)) < 1, 1,(SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                    (SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2))),                                                       --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF(SUM(X.QUANTIDADE) < 1, 1,SUM(X.QUANTIDADE)),                                           --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                    SUM(X.QUANTIDADE))),                                                                       --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.

                        IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REMESSA NORMAL INCLUI ACRESCIMO
                                FROM TBCONTROLE_N U
                               WHERE U.ID = 230) = '1',
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF((SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO)) < 1, 1,(SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                    (SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO))),                                                   --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF(SUM(X.QUANTIDADE) < 1, 1,SUM(X.QUANTIDADE)),                                           --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                    SUM(X.QUANTIDADE)))) QUANTIDADE                                                            --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.


                FROM
                    (
                    SELECT                                                                    
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.REMESSA_TALAO_ID) REMESSA_TALAO_ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.REMESSA_TALAO_DETALHE_ID) REMESSA_TALAO_DETALHE_ID,   
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.CONTROLE) CONTROLE,
                        C.DENSIDADE,
                        C.ESPESSURA,
                        (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                        LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                        P.DESCRICAO           PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO        GRADE_ID,
                        COALESCE(C.TAMANHO,0)TAMANHO,
                        (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                        C.QUANTIDADE_SALDO QUANTIDADE,
                        C.QUANTIDADE_ALTERNATIVA_SALDO QUANTIDADE_ALTERNATIVA,
                        COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                        P.UNIDADEMEDIDA_SIGLA UM,
                        P.MODELO_CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        M.PRIORIDADE MODELO_PRIORIDADE,
                        P.COR_CODIGO COR_ID,
                        (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                        P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                        (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                        F.CODIGO FAMILIA_ID
                    FROM
                        VWREMESSA_CONSUMO C,
                        TBPRODUTO P,
                        TBFAMILIA F,
                        TBMODELO M,
                        VWREMESSA R
                    WHERE
                        C.PRODUTO_ID     = P.CODIGO
                    AND F.CODIGO         = P.FAMILIA_CODIGO
                    AND M.CODIGO         = P.MODELO_CODIGO
                    AND R.REMESSA_ID     = C.REMESSA_ID
                    /*@REMESSA_ID*/
                    /*@REMESSA_TALAO_ID*/
                    /*@FAMILIA_ID*/
                    AND C.STATUS = '0'
                    ) X

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22
                )Y
            /*@ORDER_COMPONENTE*/
        ";

        $args = [
            ':REQUISICAO'		=> $requisicao,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@FAMILIA_ID'       => $familia_id,
            '@ORDER_COMPONENTE' => $order_componente
        ];
        
        return $con->query($sql,$args);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function requisicaoConsumoNecessidade($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $remessa_id       = isset($param->REMESSA_ID      ) ? "AND C.REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")" : '';
        $remessa_talao_id = isset($param->REMESSA_TALAO_ID) ? "AND C.REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")" : '';
        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        $quantidade       = (isset($param->REQUISICAO) && ($param->REQUISICAO == '0')) 
								? ", (SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO)) QUANTIDADE" 
								: ", SUM(X.QUANTIDADE) QUANTIDADE";

        $sql =
        "
            SELECT
                'REQUISICAO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                IIF(Y.QUANTIDADE < 1, 1,Y.QUANTIDADE)QUANTIDADE ,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                (SELECT FIRST 1
                    IIF(Y.TAMANHO =  1,M.T01, IIF(Y.TAMANHO =  2,M.T02, IIF(Y.TAMANHO =  3,M.T03, IIF(Y.TAMANHO =  4,M.T04, IIF(Y.TAMANHO =  5,M.T05,
                    IIF(Y.TAMANHO =  6,M.T06, IIF(Y.TAMANHO =  7,M.T07, IIF(Y.TAMANHO =  8,M.T08, IIF(Y.TAMANHO =  9,M.T09, IIF(Y.TAMANHO = 10,M.T10,
                    IIF(Y.TAMANHO = 11,M.T11, IIF(Y.TAMANHO = 12,M.T12, IIF(Y.TAMANHO = 13,M.T13, IIF(Y.TAMANHO = 14,M.T14, IIF(Y.TAMANHO = 15,M.T15,
                    IIF(Y.TAMANHO = 16,M.T16, IIF(Y.TAMANHO = 17,M.T17, IIF(Y.TAMANHO = 18,M.T18, IIF(Y.TAMANHO = 19,M.T19, IIF(Y.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                    FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = Y.MODELO_ID) FATOR_DIVISAO,
                    
                (SELECT
                    CASE Y.TAMANHO
                        WHEN 01 THEN PC.MI01
                        WHEN 02 THEN PC.MI02
                        WHEN 03 THEN PC.MI03
                        WHEN 04 THEN PC.MI04
                        WHEN 05 THEN PC.MI05
                        WHEN 06 THEN PC.MI06
                        WHEN 07 THEN PC.MI07
                        WHEN 08 THEN PC.MI08
                        WHEN 09 THEN PC.MI09
                        WHEN 10 THEN PC.MI10
                        WHEN 11 THEN PC.MI11
                        WHEN 12 THEN PC.MI12
                        WHEN 13 THEN PC.MI13
                        WHEN 14 THEN PC.MI14
                        WHEN 15 THEN PC.MI15
                        WHEN 16 THEN PC.MI16
                        WHEN 17 THEN PC.MI17
                        WHEN 18 THEN PC.MI18
                        WHEN 19 THEN PC.MI19
                        WHEN 20 THEN PC.MI20
                        ELSE 99999999999
                    END
                 FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = Y.MODELO_ID) FATOR_DIVISAO_DETALHE

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,

                    IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REQUISICAO INCLUI ACRESCIMO
                            FROM TBCONTROLE_N U
                           WHERE U.ID = 229) = '1',
                           IIF( (SELECT FIRST 1 POSITION (X.FAMILIA_ID IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                   FROM TBCONTROLE_N U
                                  WHERE U.ID = 231) > 0,
                                IIF((X.QUANTIDADE + X.ACRESCIMO) < 1, 1,(X.QUANTIDADE + (X.ACRESCIMO/2))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                (X.QUANTIDADE + (X.ACRESCIMO/2))),                                         --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                           IIF( (SELECT FIRST 1 POSITION (X.FAMILIA_ID IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                   FROM TBCONTROLE_N U
                                  WHERE U.ID = 231) > 0,
                                IIF(X.QUANTIDADE < 1, 1,X.QUANTIDADE),                                     --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                X.QUANTIDADE)) QUANTIDADE,                                                 --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.

                    X.QUANTIDADE_ALTERNATIVA,
                    X.ACRESCIMO
                            
                FROM
                    (
                        SELECT
                            'SQL_1' SQL_ID,
                            C.QUEBRA,
                            IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                            C.ID REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.ID CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,

                            CAST(IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',
                                    IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE),
                                    COALESCE(IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE)/COALESCE(C.FATOR_CONVERSAO,0),0)
                            ) AS NUMERIC(15,4))QUANTIDADE,

                            CAST(IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',0,
                                    COALESCE(IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE),0)
                            ) AS NUMERIC(15,4)) QUANTIDADE_ALTERNATIVA,
                            COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO,
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                            F.CODIGO FAMILIA_ID

                        FROM
                            TBREQUISICAO C,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.PRODUTO_ID     = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO 
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.CONSUMO        = '1'
                        AND (C.STATUS        = '1' OR C.AUTORIZACAO_STATUS = '1')
                        AND C.REMESSA_GERADA < 1
                        /*@FAMILIA_ID*/

                        UNION

                        SELECT               
                            'SQL_2' SQL_ID,
                            C.QUEBRA,
                            IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                            C.ID REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.ID CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,

                            IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',
                                    (C.QUANTIDADE/2),
                                    COALESCE((C.QUANTIDADE/2)/COALESCE(C.FATOR_CONVERSAO,0),0)
                            ) QUANTIDADE,

                            IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',0,
                                    COALESCE((C.QUANTIDADE/2),0)
                            ) QUANTIDADE_ALTERNATIVA,
                            COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO, 
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                            F.CODIGO FAMILIA_ID
                        FROM
                            TBREQUISICAO C,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.PRODUTO_ID     = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO     
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.CONSUMO        = '1'
                        AND (C.STATUS        = '1' OR C.AUTORIZACAO_STATUS = '1')
                        AND C.REMESSA_GERADA < 1
                        AND C.QUEBRA         = '1'
                        /*@FAMILIA_ID*/
                    ) X
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $args = ['@FAMILIA_ID' => $familia_id];

        return $con->query($sql,$args);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function pedidoConsumoNecessidade($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $remessa_id       = isset($param->REMESSA_ID      ) ? "AND C.PEDIDO IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")" : '';
        $remessa_talao_id = isset($param->REMESSA_TALAO_ID) ? "AND C.REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")" : '';
        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        $quantidade       = (isset($param->REQUISICAO) && ($param->REQUISICAO == '0')) 
								? ", IIF((SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO)) < 1, 1,(SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO))) QUANTIDADE" 
								: ", IIF(SUM(X.QUANTIDADE) < 1, 1, SUM(X.QUANTIDADE)) QUANTIDADE";
     
        
        $sql =
        "
            SELECT
                'PEDIDO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                Y.FATOR_DIVISAO,
                Y.FATOR_DIVISAO_DETALHE,
                Y.CLIENTE_ID

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    X.QUANTIDADE,
                    X.QUANTIDADE_ALTERNATIVA,
                    X.CLIENTE_ID,
                    COALESCE(
                        (SELECT FIRST 1
                                IIF(X.TAMANHO =  1,M.T01, IIF(X.TAMANHO =  2,M.T02, IIF(X.TAMANHO =  3,M.T03, IIF(X.TAMANHO =  4,M.T04, IIF(X.TAMANHO =  5,M.T05,
                                IIF(X.TAMANHO =  6,M.T06, IIF(X.TAMANHO =  7,M.T07, IIF(X.TAMANHO =  8,M.T08, IIF(X.TAMANHO =  9,M.T09, IIF(X.TAMANHO = 10,M.T10,
                                IIF(X.TAMANHO = 11,M.T11, IIF(X.TAMANHO = 12,M.T12, IIF(X.TAMANHO = 13,M.T13, IIF(X.TAMANHO = 14,M.T14, IIF(X.TAMANHO = 15,M.T15,
                                IIF(X.TAMANHO = 16,M.T16, IIF(X.TAMANHO = 17,M.T17, IIF(X.TAMANHO = 18,M.T18, IIF(X.TAMANHO = 19,M.T19, IIF(X.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                                FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = X.MODELO_ID),0) FATOR_DIVISAO,
                    COALESCE(
                        (SELECT FIRST 1 COTA
                           FROM TBCLIENTE_MODELO_PRECO CMP
                          WHERE CMP.CLIENTE_CODIGO = X.CLIENTE_ID
                            AND CMP.MODELO_CODIGO  = X.MODELO_ID),
                        (SELECT
                            CASE X.TAMANHO
                                WHEN 01 THEN PC.MI01
                                WHEN 02 THEN PC.MI02
                                WHEN 03 THEN PC.MI03
                                WHEN 04 THEN PC.MI04
                                WHEN 05 THEN PC.MI05
                                WHEN 06 THEN PC.MI06
                                WHEN 07 THEN PC.MI07
                                WHEN 08 THEN PC.MI08
                                WHEN 09 THEN PC.MI09
                                WHEN 10 THEN PC.MI10
                                WHEN 11 THEN PC.MI11
                                WHEN 12 THEN PC.MI12
                                WHEN 13 THEN PC.MI13
                                WHEN 14 THEN PC.MI14
                                WHEN 15 THEN PC.MI15
                                WHEN 16 THEN PC.MI16
                                WHEN 17 THEN PC.MI17
                                WHEN 18 THEN PC.MI18
                                WHEN 19 THEN PC.MI19
                                WHEN 20 THEN PC.MI20
                                ELSE 99999999999
                            END
                         FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = X.MODELO_ID)) FATOR_DIVISAO_DETALHE
                FROM
                    (
                        SELECT
                            C.PEDIDO ID,
                            C.PEDIDO REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.PEDIDO CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                            C.QUANTIDADE,
                            0 QUANTIDADE_ALTERNATIVA,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO,
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            C.CLIENTE_CODIGO CLIENTE_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO)
                               FROM VWSKU S, TBPERFIL PER
                              WHERE PER.TABELA  = 'SKU'
                                AND PER.ID      = S.PERFIL
                                AND S.MODELO_ID = P.MODELO_CODIGO
                                AND S.COR_ID    = P.COR_CODIGO
                                AND S.TAMANHO   = C.TAMANHO) PERFIL_SKU
                        FROM
                            TBPEDIDO_ITEM C,
                            TBPEDIDO_ITEM_SALDO S,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.CONTROLE       = S.PEDIDO_ITEM_CONTROLE
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.PRODUTO_CODIGO = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO     
                        AND C.SITUACAO       = '1'
                        AND M.GERAR_REMESSA  = '1'
                        /*@FAMILIA_ID*/
                        /*@PEDIDO_ID*/
                    ) X
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $args = [
            '@FAMILIA_ID' => $familia_id,
            '@PEDIDO_ID'  => $remessa_id
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta familias da remessa de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function reposicaoConsumoNecessidade($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql =
        "
            SELECT
                'REPOSICAO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                ( (SELECT VALOR2 FROM ARREDONDAR_PRA_CIMA(Y.QUANTIDADE / Y.FATOR_DIVISAO)) * Y.FATOR_DIVISAO ) QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                Y.FATOR_DIVISAO,
                Y.FATOR_DIVISAO_DETALHE,
                ( (SELECT VALOR2 FROM ARREDONDAR_PRA_CIMA(Y.QUANTIDADE / Y.FATOR_DIVISAO)) ) QUANTIDADE_TALOES

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,

                    (SELECT IIF(CT.QUEBRA_TAMANHO = 0, CT.QUEBRA_QUANTIDADE, CT.QUEBRA_TAMANHO) QUEBRA
                       FROM (SELECT FIRST 1
                                M.QUANTIDADE QUEBRA_QUANTIDADE,
                                COALESCE(IIF(X.TAMANHO =  1,M.T01, IIF(X.TAMANHO =  2,M.T02, IIF(X.TAMANHO =  3,M.T03, IIF(X.TAMANHO =  4,M.T04, IIF(X.TAMANHO =  5,M.T05,
                                         IIF(X.TAMANHO =  6,M.T06, IIF(X.TAMANHO =  7,M.T07, IIF(X.TAMANHO =  8,M.T08, IIF(X.TAMANHO =  9,M.T09, IIF(X.TAMANHO = 10,M.T10,
                                         IIF(X.TAMANHO = 11,M.T11, IIF(X.TAMANHO = 12,M.T12, IIF(X.TAMANHO = 13,M.T13, IIF(X.TAMANHO = 14,M.T14, IIF(X.TAMANHO = 15,M.T15,
                                         IIF(X.TAMANHO = 16,M.T16, IIF(X.TAMANHO = 17,M.T17, IIF(X.TAMANHO = 18,M.T18, IIF(X.TAMANHO = 19,M.T19, IIF(X.TAMANHO = 20,M.T20, 0)))))))))))))))))))),0) QUEBRA_TAMANHO
                               FROM TBMODELO_REMESSA_COTA M
                              WHERE M.MODELO_CODIGO = X.MODELO_ID)CT) FATOR_DIVISAO,

                    (SELECT
                        CASE X.TAMANHO
                            WHEN 01 THEN PC.T01
                            WHEN 02 THEN PC.T02
                            WHEN 03 THEN PC.T03
                            WHEN 04 THEN PC.T04
                            WHEN 05 THEN PC.T05
                            WHEN 06 THEN PC.T06
                            WHEN 07 THEN PC.T07
                            WHEN 08 THEN PC.T08
                            WHEN 09 THEN PC.T09
                            WHEN 10 THEN PC.T10
                            WHEN 11 THEN PC.T11
                            WHEN 12 THEN PC.T12
                            WHEN 13 THEN PC.T13
                            WHEN 14 THEN PC.T14
                            WHEN 15 THEN PC.T15
                            WHEN 16 THEN PC.T16
                            WHEN 17 THEN PC.T17
                            WHEN 18 THEN PC.T18
                            WHEN 19 THEN PC.T19
                            WHEN 20 THEN PC.T20
                            ELSE 99999999999
                        END
                     FROM TBMODELO_PRODUCAO_COTA PC WHERE PC.MODELO_CODIGO = X.MODELO_ID) FATOR_DIVISAO_DETALHE,

                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    SUM(X.QUANTIDADE) QUANTIDADE,
                    SUM(X.QUANTIDADE_ALTERNATIVA) QUANTIDADE_ALTERNATIVA
                FROM
                    (
                    SELECT                                                                    
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) REMESSA_TALAO_ID,
                        NULL REMESSA_TALAO_DETALHE_ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) CONTROLE,
                        C.DENSIDADE,
                        C.ESPESSURA,
                        (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                        LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                        P.DESCRICAO           PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO        GRADE_ID,
                        COALESCE(C.TAMANHO_ID,0)TAMANHO,
                        (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO_ID))TAMANHO_DESCRICAO,
                        C.NECESSIDADE QUANTIDADE,
                        0 QUANTIDADE_ALTERNATIVA,
                        COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                        P.UNIDADEMEDIDA_SIGLA UM,
                        P.MODELO_CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        M.PRIORIDADE MODELO_PRIORIDADE,
                        P.COR_CODIGO COR_ID,
                        (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                        P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                        (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO_ID) PERFIL_SKU
                    FROM
                        (SELECT * FROM SPC_PROJECAO_REMESSA1 (:ESTABELECIMENTO_ID,:FAMILIA_ID)) C ,
                        TBPRODUTO P,
                        TBFAMILIA F,
                        TBMODELO M
                    WHERE
                        C.PRODUTO_ID     = P.CODIGO
                    AND F.CODIGO         = P.FAMILIA_CODIGO
                    AND M.CODIGO         = P.MODELO_CODIGO
                    AND C.TAMANHO_ID > 0
                    /*@REMESSA_ID*/
                    /*@REMESSA_TALAO_ID*/
                    /*@FAMILIA_ID*/
                    ) X

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $args = [
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':FAMILIA_ID'         => $param->FAMILIA_ID
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta programação de talões
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaProgramacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $programacao_id = array_key_exists('PROGRAMACAO_ID', $param) ? "AND ID     IN (" . arrayToList($param->PROGRAMACAO_ID, 9999999999999) . ")" : '';
        $status         = array_key_exists('STATUS' 	   , $param) ? "AND STATUS IN (" . arrayToList($param->STATUS        , "'#'","'"    ) . ")" : '';
        
        $sql =
        "
            SELECT
                X.ID,
                X.ESTABELECIMENTO_ID,
                X.TIPO,
                X.TABELA_ID,
                X.GP_ID,
                X.UP_ID,
                X.ESTACAO,
                X.PRODUTO_ID,
                X.TAMANHO,
                X.QUANTIDADE,    
                X.TEMPO,
                X.DATAHORA_INICIO,     
                X.DATAHORA_FIM,
                X.DATA_INICIO,     
                X.DATA_FIM,
                X.STATUS,
                X.STATUS_DESCRICAO

            FROM
                (SELECT
                    P.ID,
                    P.ESTABELECIMENTO_ID,
                    P.TIPO,
                    P.TABELA_ID,
                    P.GP_ID,
                    P.UP_ID,
                    P.ESTACAO,
                    P.PRODUTO_ID,
                    P.TAMANHO,
                    P.QUANTIDADE,
                    P.DATAHORA_INICIO,
                    P.DATA_INICIO,
                    P.TEMPO,
                    P.DATAHORA_FIM,
                    P.DATA_FIM,
                    P.STATUS,
                   (CASE
                        P.STATUS
                    WHEN '0' THEN 'NÃO INICIADO'
                    WHEN '1' THEN 'INICIADO E PARADO'
                    WHEN '2' THEN 'EM ANDAMENTO'
                    WHEN '3' THEN 'FINALIZADO'
                    WHEN '6' THEN 'ENCERRADO'
                    ELSE 'INDEFINIDO' END) STATUS_DESCRICAO
                FROM
                    TBPROGRAMACAO P)X

            WHERE
                1=1
                /*@PROGRAMACAO_ID*/
                /*@STATUS*/
        ";

        $args = [
            '@PROGRAMACAO_ID' => $programacao_id,
            '@STATUS'         => $status
        ];

        return $con->query($sql,$args);
    }

    /**
     * Consulta histórico da programação de talões
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaProgramacaoHistorico($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $programacao_id = isset($param->PROGRAMACAO_ID) ? "AND PROGRAMACAO_ID IN  (" . arrayToList($param->PROGRAMACAO_ID, 9999999999999) . ")" : '';

        $sql =
        "
            SELECT
                X.ID,
                X.PROGRAMACAO_ID,
                X.OPERADOR_ID,
                X.OPERADOR_NOME,
                X.DATAHORA,
                X.STATUS,
                X.STATUS_DESCRICAO

            FROM
                (SELECT
                    R.ID,
                    R.PROGRAMACAO_ID,
                    R.OPERADOR_ID,
                    O.NOME OPERADOR_NOME,
                    R.DATAHORA,
                    R.STATUS,
                   (CASE
                        R.STATUS
                    WHEN '0' THEN 'INICIADO/REINICIADO'
                    WHEN '1' THEN 'PARADA TEMPORÁRIA'
                    WHEN '2' THEN 'FINALIZADO'
                    ELSE 'INDEFINIDO' END) STATUS_DESCRICAO
                FROM
                    TBPROGRAMACAO_REGISTRO R,
                    TBOPERADOR O

                WHERE
                    O.CODIGO = R.OPERADOR_ID)X

            WHERE
                1=1
                /*@PROGRAMACAO*/
        ";

        $args = [
            '@PROGRAMACAO' => $programacao_id
        ];

        return $con->query($sql,$args);
    }

    /**
     * Tempos dos fluxos
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaProgramacaoTempo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            SELECT
                T.TEMPOSETUP,
                T.TEMPOSETCOR,
                T.TEMPOREB,
                T.TEMPOPROCESSO,
                T.TOTALSETUP,
                T.TOTALPROCESSO,
                T.TOTAL
            FROM
                SPC_PROGRAMACAO_TEMPO_NOVO T
        ";
        
        return $con->query($sql);
    }

    /**
     * Consulta os defeitos de talões
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaDefeito($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $remessa_id       = isset($param->REMESSA_ID      ) ? "AND REMESSA_ID       IN  (" . arrayToList($param->REMESSA_ID      , 9999999999999) . ")" : '';
        $remessa_talao_id = isset($param->REMESSA_TALAO_ID) ? "AND REMESSA_TALAO_ID IN  (" . arrayToList($param->REMESSA_TALAO_ID, 9999999999999) . ")" : '';

        $sql =
        "
            SELECT
                X.REMESSA_ID,
                X.REMESSA_TALAO_ID,
                X.DEFEITO_ID,
                X.DEFEITO_DESCRICAO,
                X.DEFEITO_OBSERVACAO,
                X.PRODUTO_ID,
                X.TAMANHO,
                X.QUANTIDADE,
                X.DATA

            FROM
                (SELECT
                    I.ID,
                    I.REMESSA REMESSA_ID,
                    I.REMESSA_CONTROLE REMESSA_TALAO_ID,
                    I.DEFEITO_ID,
                    D.DESCRICAO DEFEITO_DESCRICAO,
                    I.OBSERVACAO DEFEITO_OBSERVACAO,
                    I.PRODUTO_ID,
                    I.TAMANHO,
                    I.DATA,
                    SUM(I.QUANTIDADE)QUANTIDADE

                FROM
                    TBDEFEITO_TRANSACAO_ITEM I,
                    TBSAC_DEFEITO D

                WHERE
                    D.CODIGO = I.DEFEITO_ID

                GROUP BY 1,2,3,4,5,6,7,8,9)X

            WHERE
                1=1
                /*@REMESSA_ID*/
                /*@REMESSA_TALAO_ID*/
        ";

        $args = [
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id
        ];

        return $con->query($sql,$args);
    }
	
	public static function verificarRemessaExiste($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
		
		$sql = "
			SELECT
				R.REMESSA
			FROM
				VWREMESSA R
			WHERE
				R.REMESSA LIKE :REMESSA
			AND R.GP_ID = :GP_ID
		";
		
		$args = [
			':REMESSA'	=> '%'.$param->REMESSA.'%',
			':GP_ID'	=> $param->GP_ID
		];
				
		return $con->query($sql, $args);
				
	}
    
	
	public static function verificarRemessaIntegridade($param = [], _Conexao $con = null) {
        
		$con = $con ? $con : new _Conexao;

		$sql = "
			SELECT * 
            FROM SPC_REMESSA_INTEGRIDADE_V2(
                :REMESSA,
                :CONTROLE_TALAO,
                :CONTROLE_DETALHE
            );
		";

		$args = [
			':REMESSA'          => $param->REMESSA,
            ':CONTROLE_TALAO'   => $param->CONTROLE_TALAO,
            ':CONTROLE_DETALHE' => $param->CONTROLE_DETALHE
		];

		return $con->query($sql, $args);	
	}
}

class _22040DaoInsert
{    
    public static function remessa($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $componente_field = array_key_exists('COMPONENTE', $param) ? ", COMPONENTE" : '';
        $componente_value = array_key_exists('COMPONENTE', $param) ? ", '" . $param->COMPONENTE . "'" : '';
        
        $tipo_field = array_key_exists('TIPO', $param) ? ", TIPO" : '';
        $tipo_value = array_key_exists('TIPO', $param) ? ", '" . $param->TIPO . "'" : '';
        
        $requisicao_field = array_key_exists('REQUISICAO', $param) ? ", REQUISICAO" : '';
        $requisicao_value = array_key_exists('REQUISICAO', $param) ? ", '" . $param->REQUISICAO . "'" : '';

        $sql =
        "
            INSERT INTO VWREMESSA (
                  ESTABELECIMENTO_ID
                , REMESSA_ID
                , REMESSA
                , FAMILIA_ID
                , GP_ID
                , DATA
                , STATUS
                , PERFIL
                , WEB
				/*@COMPOENTE_FIELD*/
				/*@TIPO_FIELD*/
				/*@REQUISICAO_FIELD*/
            ) VALUES (
                 :ESTABELECIMENTO_ID
               , :REMESSA_ID
               , :REMESSA
               , :FAMILIA_ID
               , :GP_ID
               , :DATA
               , :STATUS
               , :PERFIL
               , :WEB
               /*@COMPOENTE_VALUE*/
               /*@TIPO_VALUE*/
               /*@REQUISICAO_VALUE*/
            );
        ";
        $args = [
            ':ESTABELECIMENTO_ID'   => $param->ESTABELECIMENTO_ID,
            ':REMESSA_ID'           => $param->ID,
            ':REMESSA'              => $param->REMESSA,
            ':FAMILIA_ID'           => $param->FAMILIA_ID,
            ':GP_ID'                => $param->GP_ID,
            ':DATA'                 => $param->DATA_PRODUCAO,
            ':STATUS'               => 1,
            ':PERFIL'               => 1,
            ':WEB'                  => 1,
            '@COMPOENTE_FIELD'      => $componente_field,
            '@COMPOENTE_VALUE'      => $componente_value,
            '@TIPO_FIELD'           => $tipo_field,
            '@TIPO_VALUE'           => $tipo_value,
            '@REQUISICAO_FIELD'     => $requisicao_field,
            '@REQUISICAO_VALUE'     => $requisicao_value
        ];

        $con->execute($sql, $args);
    }

    public static function remessaTalao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            INSERT INTO VWREMESSA_TALAO (
                ID,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                PRODUTO_ID,
                MODELO_ID,
                DENSIDADE,
                ESPESSURA,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA,
                GP_ID,
                UP_ID,
                ESTACAO,
                STATUS
            ) VALUES (
               :ID,
               :REMESSA_ID,
               :REMESSA_TALAO_ID,
               :PRODUTO_ID,
               :MODELO_ID,
               :DENSIDADE,
               :ESPESSURA,
               :QUANTIDADE,
               :QUANTIDADE_ALTERNATIVA,
               :GP_ID,
               :UP_ID,
               :ESTACAO,
               :STATUS
            );
        ";

        $args = [
            ':REMESSA_ID'				=> $param->REMESSA_ID,
            ':ID'						=> $param->REMESSA_TALAO_ID,
            ':REMESSA_TALAO_ID'			=> $param->REMESSA_TALAO_CONTROLE,
            ':MODELO_ID'				=> $param->MODELO_ID,
            ':DENSIDADE'				=> $param->DENSIDADE,
            ':ESPESSURA'				=> $param->ESPESSURA,
            ':QUANTIDADE'				=> $param->QUANTIDADE_TALAO,
            ':QUANTIDADE_ALTERNATIVA'	=> $param->QUANTIDADE_TALAO_ALTERNATIVA,
            ':GP_ID'					=> $param->GP_ID,
            ':UP_ID'					=> $param->UP_ID,
            ':ESTACAO'					=> $param->ESTACAO,
            ':PRODUTO_ID'				=> 0,
            ':STATUS'					=> 1,
        ];

        $con->execute($sql, $args);
    }

    public static function remessaTalaoDetalhe($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql =
        "
            INSERT INTO VWREMESSA_TALAO_DETALHE (
                ESTABELECIMENTO_ID,
                ID,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                PECA_CONJUNTO,
                PRODUTO_ID,
                MODELO_ID,
                COR_ID,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_PRODUCAO,
				QUANTIDADE_SALDO,
				QUANTIDADE_ALTERN,
                QUANTIDADE_ALTERN_PRODUCAO,
                QUANTIDADE_ALTERN_SALDO,
                GP_ID,
                STATUS,
                LOCALIZACAO_ID
            ) VALUES (
               :ESTABELECIMENTO_ID,
               :ID,
               :REMESSA_ID,
               :REMESSA_TALAO_ID,
               :PECA_CONJUNTO,
               :PRODUTO_ID,
               :MODELO_ID,
               :COR_ID,
               :TAMANHO,
               :QUANTIDADE,
               :QUANTIDADE_PRODUCAO,
			   :QUANTIDADE_SALDO,
			   :QUANTIDADE_ALTERN,
               :QUANTIDADE_ALTERN_PRODUCAO,
               :QUANTIDADE_ALTERN_SALDO,
               :GP_ID,
               :STATUS,
               :LOCALIZACAO_ID
            );
        ";

        $args = [
            ':ESTABELECIMENTO_ID'			=> $param->ESTABELECIMENTO_ID,
            ':ID'							=> $param->REMESSA_TALAO_DETALHE_ID,
            ':REMESSA_ID'					=> $param->REMESSA_ID,
            ':REMESSA_TALAO_ID'				=> $param->REMESSA_TALAO_CONTROLE,
            ':PECA_CONJUNTO'				=> $param->CONTROLE_SEQ,
            ':PRODUTO_ID'					=> $param->PRODUTO_ID,
            ':MODELO_ID'					=> $param->MODELO_ID,
            ':COR_ID'						=> $param->COR_ID,
            ':TAMANHO'						=> $param->TAMANHO,
            ':QUANTIDADE'					=> $param->QUANTIDADE,
			':QUANTIDADE_PRODUCAO'			=> 0,
			':QUANTIDADE_SALDO'				=> 0,
			':QUANTIDADE_ALTERN'			=> $param->QUANTIDADE_ALTERNATIVA,
            ':QUANTIDADE_ALTERN_PRODUCAO'	=> 0,
            ':QUANTIDADE_ALTERN_SALDO'		=> 0,
            ':GP_ID'						=> $param->GP_ID,
            ':LOCALIZACAO_ID'				=> $param->LOCALIZACAO_ID,
            ':STATUS'						=> 1
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gera os consumos dos insumos da remessa
     * @param (object)array $param
     * @param _Conexao $con
     */
    public static function remessaConsumoInsumo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "
            EXECUTE PROCEDURE SPI_REM_CONSUMO_INSUMO3(
                :ESTABELECIMENTO_ID,
                :REMESSA_ID
            );
        ";

        $args = [
            ':ESTABELECIMENTO_ID'   => $param->ESTABELECIMENTO_ID,
            ':REMESSA_ID'           => $param->ID,
        ];

        return $con->query($sql,$args);        
    }

    /**
     * Gera os vinculo dos consumos com os talões
     * @param (object)array $param
     * @param _Conexao $con
     */
    public static function remessaConsumoVinculo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "
            INSERT INTO TBREMESSA_CONSUMO_VINCULO (
                QUANTIDADE,
                CONSUMO_ID,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                REMESSA_TALAO_DETALHE_ID
            ) VALUES (
               :QUANTIDADE,
               :CONSUMO_ID,
               :REMESSA_ID,
               :REMESSA_TALAO_ID,
               :REMESSA_TALAO_DETALHE_ID
            );
        ";

        $args = [
            'QUANTIDADE'               => $param->QUANTIDADE,
            'CONSUMO_ID'               => $param->CONSUMO_ID,
            'REMESSA_ID'               => $param->REMESSA_ID,
            'REMESSA_TALAO_ID'         => $param->REMESSA_TALAO_CONTROLE,
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID,
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gera a programação da remessa
     * @param (object)array $param
     * @param _Conexao $con
     */
    public static function remessaProgramacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "
            EXECUTE PROCEDURE SPI_PROGRAMACAO2(
                :ESTABELECIMENTO_ID,
                'A',
                :GP_ID,
                :UP_ID,
                :ESTACAO,
                :REMESSA_TALAO_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE,
                :TEMPO,
                :REMESSA_ID,
                :DATA_PRODUCAO
            );
        ";

        $args = [
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':GP_ID'              => $param->GP_ID,
            ':UP_ID'              => $param->UP_ID,
            ':ESTACAO'            => $param->ESTACAO,
            ':REMESSA_TALAO_ID'   => $param->REMESSA_TALAO_ID,
            ':PRODUTO_ID'         => $param->PRODUTO_ID,
            ':TAMANHO'            => $param->TAMANHO,
            ':QUANTIDADE'         => $param->QUANTIDADE_TALAO_PROGRAMACAO,
            ':TEMPO'              => $param->TEMPO,
            ':REMESSA_ID'         => $param->REMESSA_ID,
            ':DATA_PRODUCAO'      => $param->DATA_PRODUCAO
        ];

        $con->execute($sql, $args);
    }
    
    /**
     * Insere o talão detalhado de uma remessa para se calcular o tempo (Tempory Table)
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function remessaProgramacaoTempo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            INSERT INTO
            TMP22040 (
                UP_ID, 
                MODELO_ID, 
                COR_ID, 
                TAMANHO, 
                QUANTIDADE
            ) VALUES (
               :UP_ID, 
               :MODELO_ID, 
               :COR_ID, 
               :TAMANHO, 
               :QUANTIDADE
            );
        ";

        $args = [
            ':UP_ID'      => $param->UP_ID     ,
            ':MODELO_ID'  => $param->MODELO_ID ,
            ':COR_ID'     => $param->COR_ID    ,
            ':TAMANHO'    => $param->TAMANHO   ,
            ':QUANTIDADE' => $param->QUANTIDADE,
        ];

        return $con->query($sql,$args);
    }
}

class _22040DaoUpdate
{

    public static function remessa($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE VWREMESSA SET
                WEB = '1'
            WHERE REMESSA_ID = :REMESSA_ID
        ";
        
        $args = [
            ':REMESSA_ID' => $param->ID
        ];

        $con->execute($sql, $args);
    }
    
    public static function requisicao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE TBREQUISICAO SET
                REMESSA_GERADA = :REMESSA_ID,
                STATUS = '2'
            WHERE ID = :ID
        ";
        
        $args = [
            ':REMESSA_ID'   => $param->REMESSA_ID,
            ':ID'           => $param->ID
        ];

        $con->execute($sql, $args);
    }
    
    public static function atualizarCotaCliente($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE TBCLIENTE_MODELO_PRECO P
               SET P.COTA           = :QUANTIDADE_COTA
             WHERE P.CLIENTE_CODIGO = :CLIENTE_ID
               AND P.MODELO_CODIGO  = :MODELO_ID
        ";
        
        $args = [
            'CLIENTE_ID'        => $param->CLIENTE_ID,
            'MODELO_ID'         => $param->MODELO_ID,
            'QUANTIDADE_COTA'   => $param->QUANTIDADE_COTA,
        ];

        $con->execute($sql, $args);
    }
}

class _22040DaoDelete
{    
    public static function remessa($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM VWREMESSA                 R WHERE R.REMESSA_ID = :REMESSA_ID;
            DELETE FROM VWREMESSA_TALAO           T WHERE T.REMESSA_ID = :REMESSA_ID;
            DELETE FROM VWREMESSA_TALAO_DETALHE   D WHERE D.REMESSA_ID = :REMESSA_ID;
            DELETE FROM TBPROGRAMACAO             P WHERE P.REMESSA_ID = :REMESSA_ID;
            DELETE FROM TBREMESSA_CONSUMO_VINCULO V WHERE V.REMESSA_ID = :REMESSA_ID;
        ";
        
        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID
        ];

        $con->execute($sql, $args);
    }
}