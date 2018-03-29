<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Helper\ArquivoController;
use PDO;
use Exception;
use PDOException;

/**
 * DAO do objeto _11150 - Registro de Casos
 */
class _11150DAO {

    /**
     * Função generica
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getChecList($dados) {
        return $dados;
    }

	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listar($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = '';

            $args = array(
                ':id' => $dados->getId(),
            );

            $ret = $con->query($sql, $args);

            $con->commit();
			
			return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

	/**
     * Consultar casos de um painel
     * @access public
     * @param int painel_id
     * @param Conection $con
     * @return array
     */
    public static function getCasos($painel_id, $status, $filtro,$con) {

        $filter1 = '';
        $filter2 = '';
        if(array_key_exists('FILTRO', $filtro)){
            $filter1 = $filtro['FILTRO'];
            $filter1 = str_replace(' ','%', $filter1);
            $filter2 = 'TBCASO_REGISTRO_DETALHE k,';
            $filter1 = ' and k.registro_id = r.id and k.painel_id   = r.painel_id and (upper(k.valor) like upper(\'%'.$filter1.'%\') or upper(k.json) like upper(\'%'.$filter1.'%\')) ';
        }

        $conf_view = _11150DAO::getVisializacao($painel_id,$con);

        $temp_sql = '';
        forEach($conf_view['CAMPO'] as $key => $iten){
            $temp_sql .= " (select first 1 valor from CASO_DETALHE(p.painel_id, p.id) where campo_id = ".$iten->CAMPO_ID.") as C".$key.",";
        }

        $sql_order  = ' order by DATAHORA_REGISTRO desc ';
        $sql_status = ' and i.FECHADO = 0';
        $sql_first = '';

        if($status == 2){
            $sql_status = ' and i.FECHADO = 1';
            $sql_first  = ' first 50'; 
            $sql_order  = ' order by data_fechamento desc ';
        }

        $sql = "
            SELECT
                p.*,

                coalesce((SELECT
                                count(ID)
                            from tbnotificacao n
                            where n.usuario_id = :USUARIO_ID2
                            and n.tabela       = 'TBCASO'
                            and n.tabela_id    = P.ID),0) as NOTIFICACOES,

                $temp_sql
                
                0 as flag2
            FROM(
                SELECT $sql_first distinct
                    
                        R.ID,
                        lpad(R.ID,6,0) as CODIGO,
                        formatdatetime(R.DATAHORA_REGISTRO) as DATAHORA_REGISTRO,
                        R.USUARIO_ID,
                        m.descricao   as MOTIVO,
                        T.descricao   as TIPO,
                        o.descricao   as ORIGEM,
                        u.usuario     as RESPONSAVEL,
                        r.status_id   as STATUS_ID,
                        i.descricao   as STATUS,
                        i.cor         as COR,
                        r.painel_id   as PAINEL_ID,

                        0 as flag
                    
                    FROM
                        tbcaso_registro r, tbcaso_tipo t,tbcaso_motivo m, $filter2
                        tbcaso_origem o, tbusuario u, tbcaso_status i, tbcaso_usuarios j left join TBCASO_ENVOLVIDOS e on (e.usuario_id = j.usuario_id)
                    where r.painel_id = :PAINEL_ID1
                    and t.id = r.tipo_id
                    and m.id = r.motivo_id
                    and o.id = r.origem_id
                    and i.id = r.status_id
                    and u.codigo = r.responsavel_id
                    $sql_status
                    and j.usuario_id = :USUARIO_ID1
                    and j.painel_id  = r.painel_id
                    and (j.responsavel = 1 or j.usuario_id = r.usuario_id or e.caso_id = r.id)

                    $filter1

                    $sql_order
                    
                ) p
                    ";

        $args = array(
            ':PAINEL_ID1'  => $painel_id,
            ':USUARIO_ID1' => Auth::user()->CODIGO,
            ':USUARIO_ID2' => Auth::user()->CODIGO
        );

        $ret = $con->query($sql, $args);
        
        return [
                'CONF'  => $conf_view,
                'ITENS' => $ret
            ];
    }

    /**
     * Consultar visialização de um painel
     * @access public
     * @param int painel_id
     * @param Conection $con
     * @return array
     */
    public static function getVisializacao($painel_id,$con) {

        $sql = "SELECT
                    *
                from tbcaso_visualizacao_conf c
                where c.painel_id = :PAINEL_ID";

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret1 = $con->query($sql, $args);

        $sql = "SELECT
                    *
                from tbcaso_visualizacao_campo c, tbcaso_painel_conf f
                where c.painel_id = :PAINEL_ID
                and c.visivel = 1
                and f.id = c.campo_id
                order by c.ordem
                ";

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret2 = $con->query($sql, $args);
        
        return [
                'CONF'   => $ret1,
                'CAMPO'  => $ret2
            ];
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
        
        try {

            $sql = 'SELECT \'TELA 100% FUNCIONAL\' as FRASE from RDB$DATABASE WHERE 0 = :ID';

            $args = array(
                ':ID' => $filtro['ID'],
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Status casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Status($param,$con) {

        $filter = strtoupper($param['FILTRO']);
        $id     = $param['PARAN']['ID'];
        $reg    = $id > 0 ? 'and e.id = '.$id : '';

        $desc = !empty($filter) ? 'and a.id||\' \'||upper(a.descricao) like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';

        $aberto = '';
        if(array_key_exists('ABERTO',$param['OPTIONS'])){
            $aberto = 'and e.aberto = '.$param['OPTIONS']['ABERTO'];
        }

        $options = $param['OPTIONS'];

        $sql = 'SELECT 
                    e.*,
                    coalesce((SELECT
                                count(r.status_id)
                            FROM
                                tbcaso_registro r
                            where r.painel_id = e.PAINEL_ID
                            and r.fechado = 0
                            and r.status_id = e.id
                            group by r.status_id),0) as ABERTOS

                from tbcaso_status e
                where e.painel_id = :PAINEL_ID

                '.$desc.'
                '.$aberto.'
                '.$reg.'
            ';

        $args = array(
            ':PAINEL_ID' => $options['PAINEL_CASO']['ID']
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Sql
     * @access public
     * @param Integer ID
     * @param Conection $con
     * @return array
     */
    public static function getSql($id,$con) {

        $sql = 'select * from tbcaso_sql s where s.id = :ID';

        $args = array(
            ':ID' => $id
        );

        $ret = $con->query($sql, $args);
        
        if(count($ret) > 0){
            $ret = $ret[0]->SQL;
        }else{
            $ret = '';
        }

        return $ret;
    }

    /**
     * Consultar Historico de um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function historico($painel_id, $caso_id, $con) {

        $sql = 'SELECT
                    ID,
                    formatdatetime(DATAHORA) as DATAHORA_REGISTRO,
                    HISTORICO,
                    u.usuario,
                    CASO_ID,
                    PAINEL_ID
                from tbcaso_historico h, tbusuario u
                where h.painel_id = :PAINEL_ID
                and h.caso_id = :CASO_ID
                and u.codigo = h.usuario_id

                order by h.datahora desc
            ';

        $args = array(
            ':PAINEL_ID' => $painel_id,
            ':CASO_ID'   => $caso_id
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Deleta um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function excluirCaso($painel_id, $caso_id, $con) {

        $sql = 'DELETE FROM TBCASO_REGISTRO R WHERE R.ID = :CASO_ID AND R.PAINEL_ID = :PAINEL_ID';

        $args = array(
            ':PAINEL_ID' => $painel_id,
            ':CASO_ID'   => $caso_id
        );

        $con->execute($sql, $args);

        return true;
    }

    /**
     * Consultar arquivos.
     * @param array $tarefa
     */
    public static function getArquivo($feeds) {

        $conFile = new _Conexao('FILES');

        try {

            if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                $caminho = env('APP_TEMP', '').'/files/';
            } else {
                $caminho = '/var/www/html/GCWEB/public/assets/temp/files/';
            }

            deleleFilesTree2($caminho);

            foreach ($feeds as $feed) {

                $arquivoRet = [];

                if($feed->FILES == 1){

                    $sql = "
                            SELECT
                                A.ID, 
                                lower(A.ARQUIVO) as ARQUIVO,
                                A.CONTEUDO,
                                A.TAMANHO,
                                lower(replace(A.EXTENSAO,'.','')) as EXTENSAO
                            FROM 
                                TBARQUIVO A
                                INNER JOIN TBVINCULO V ON V.ARQUIVO_ID = A.ID
                            WHERE 
                                V.TABELA = 'TBCASO_REGISTRO'
                            AND V.TABELA_ID = :TABELA_ID
                        ";

                        $args = [
                            ':TABELA_ID' => $feed->ID
                        ];

                    $arquivos = $conFile->query($sql, $args);

                    if (!empty($arquivos)) {

                        try {
                            
                            foreach ($arquivos as $key => $a) {
                                
                                $novoNome = \Auth::user()->CODIGO .'-'. $feed->ID .'-'. $a->ID .'-'. $a->ARQUIVO;

                                // Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
                                $arquivoRet[$key]['FEED_ID']    = $feed->ID;
                                $arquivoRet[$key]['ID']         = $a->ID;
                                $arquivoRet[$key]['NOME']       = $a->ARQUIVO;
                                $arquivoRet[$key]['TIPO']       = $a->EXTENSAO;
                                $arquivoRet[$key]['BINARIO']    = '/assets/temp/files/'.$novoNome;
                                
                                $novoNome = $caminho . $novoNome;

                                $novoArquivo = fopen($novoNome, "a");
                                fwrite($novoArquivo, $a->CONTEUDO);
                                fclose($novoArquivo);                            
                                
                            }

                            $feed->FILE = $arquivoRet;

                        } catch (Exception $e) {
                            log_info('2 - Erro ao gerar arquivos do feed ID:'.$feed->ID.' '.$e->getmessage());    
                        }
                    }
                }
            }

            $conFile->commit();

            return $feeds;

        } catch (Exception $e) {
            $conFile->rollback();
            throw $e;
        }
    }

        /**
     * Consultar Historico de um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function consultarSubFeed($painel_id, $caso_id, $feed, $con) {

        $sql = 'SELECT

                    f.*,
                    formatdatetime(f.datahora) as DATA_REGISTRO,

                    iif(f.tipo = 0, \'Caso\',
                    iif(f.tipo = 1, \'Email\',
                    iif(f.tipo = 2, \'Anexo\',
                    iif(f.tipo = 3, \'Visita\',
                    iif(f.tipo = 4, \'Resposta de Email\',\'\'))))) DESC_TIPO,
                    u.nome as USER_NAME,
                    coalesce((select sum(g.valor) from tbcaso_feed_gostei g where g.feed_id = f.ID),0) QTD_GOSTOU,
                    coalesce((select first 1 g.valor  from tbcaso_feed_gostei g where g.usuario_id = :USUARIO_ID and g.feed_id = f.ID),0) USUARIO_GOSTOU

                from tbcaso_feed f, tbusuario u
                where f.painel_id = :PAINEL_ID
                and f.caso_id = :CASO_ID
                and u.codigo  = f.usuario_id
                and SUBFEED   = :FEED

                order by f.datahora
            ';

        $args = array(
            ':PAINEL_ID'  => $painel_id,
            ':CASO_ID'    => $caso_id,
            ':FEED'       => $feed,
            ':USUARIO_ID' => Auth::user()->CODIGO
        );

        $ret = $con->query($sql, $args);

        $feeds = _11150DAO::getArquivo($ret);

        return $feeds;
    }

    /**
     * Consultar Historico de um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function consultarFeed($painel_id, $caso_id, $comentario, $con) {

        $sql = 'SELECT

                f.*,
                formatdatetime(f.datahora) as DATA_REGISTRO,
                comentario as COMENT,
                iif(f.tipo = 0, \'Caso\',
                iif(f.tipo = 1, \'Email\',
                iif(f.tipo = 2, \'Anexo\',
                iif(f.tipo = 3, \'Visita\',
                iif(f.tipo = 4, \'Resposta de Email\',\'\'))))) DESC_TIPO,
                u.nome as USER_NAME,
                coalesce((select sum(g.valor) from tbcaso_feed_gostei g where g.feed_id = f.ID),0) QTD_GOSTOU,
                coalesce((select first 1 g.valor  from tbcaso_feed_gostei g where g.usuario_id = :USUARIO_ID and g.feed_id = f.ID),0) USUARIO_GOSTOU,
                c.valor as CONTATO_NAME

            from tbcaso_feed f
            left join tbusuario u on (u.codigo = f.usuario_id)
            left join tbcaso_contato c on (c.reg_id = f.contato and c.campo = (select first 1 p.campo_nome_contato from tbcaso_painel p where p.id = f.painel_id))
            where f.painel_id = :PAINEL_ID
            and f.caso_id = :CASO_ID
            and SUBFEED = 0
            and comentario = :COMENTARIO

            order by f.datahora desc
            ';

        $args = array(
            ':PAINEL_ID'  => $painel_id,
            ':CASO_ID'    => $caso_id,
            ':USUARIO_ID' => Auth::user()->CODIGO,
            ':COMENTARIO' => $comentario
        );

        $ret = $con->query($sql, $args);

        $cont = 0;

        foreach ($ret as $feed) {
            $str = $feed->MENSAGEM;

            if(!mb_detect_encoding($str, 'utf-8', true)){
                if ($str != null) $str = strtoupper(utf8_encode($str));
            }else{
                if ($str != null) $str = strtoupper($str);
            }

            $encoding = 'UTF-8';
            $str = mb_convert_case($str, MB_CASE_UPPER, $encoding);

            $feed->MENSAGEM = $str;

            $sub_feed = _11150DAO::consultarSubFeed($painel_id, $caso_id, $feed->ID, $con);
            $feed->COMENTARIO = $sub_feed;
        }

        $feeds = _11150DAO::getArquivo($ret);

        return $feeds;
    }

    /**
     * Gravar feed de um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function gravarFeed($dados, $con) {

        if($dados['FEED_ID'] > 0){
            $id = $dados['FEED_ID'];
        }else{
            $id = $con->gen_id('GTBCASO_FEED');
        }

        $arquivos = '';

        if(array_key_exists('ARQUIVOS', $dados)){
            foreach($dados['ARQUIVOS'] as $arquivo) {

                if ( $arquivo['BINARIO'] != 'null' && $arquivo['ID'] == 0) {
                     $arquivo['VINCULO']  = $id;
                     
                    $file_res = ArquivoController::gravarArquivo($arquivo,1);

                    if($arquivos == ''){
                        $arquivos = $file_res['1'];
                    }else{
                        $arquivos = $arquivos.','.$file_res['1'];
                    }

                    $sql2 = 'execute procedure SPI_CASO_HISTORICO(:CASO_ID, :PAINEL_ID,\'Arquivo Adicionado: '.$arquivo['NOME'].'  \');';
                    $args2 = array(
                        ':PAINEL_ID'        => $dados['PAINEL_ID'], 
                        ':CASO_ID'          => $dados['CASO_ID']
                    );
                    $con->execute($sql2, $args2);
                }

            }
        }

        if(array_key_exists('EXCLUIR', $dados)){
            foreach($dados['EXCLUIR'] as $arquivo) {

                if ( $arquivo['BINARIO'] != 'null' && $arquivo['ID'] > 0) {
                     $arquivo['VINCULO']  = $id;
                    ArquivoController::excluir($arquivo['ID']);

                    $sql2 = 'execute procedure SPI_CASO_HISTORICO(:CASO_ID, :PAINEL_ID ,\'Arquivo Excluido: '.$arquivo['NOME'].'  \');';
                    $args2 = array(
                        ':PAINEL_ID'        => $dados['PAINEL_ID'], 
                        ':CASO_ID'          => $dados['CASO_ID']
                    );
                    $con->execute($sql2, $args2);
                }

            }
        }

        $sql = 'UPDATE OR INSERT INTO TBCASO_FEED (ID, DE, PARA, EM_COPIA, EM_COPIA_OCULTA, MENSAGEM, ASSUNTO, PAINEL_ID, CASO_ID, FILES, SUBFEED, TIPO, USUARIO_ID, COMENTARIO,LISTA_FILE)
                 VALUES (:ID, :DE, :PARA, :EM_COPIA, :EM_COPIA_OCULTA, :MENSAGEM, :ASSUNTO, :PAINEL_ID, :CASO_ID, 1, :SUBFEED, :TIPO, :USUARIO_ID, :COMENTARIO, :LISTA_FILE)
                 
                 matching(ID,PAINEL_ID,CASO_ID);
                ';

        $query = $con->pdo->prepare($sql);

        $query->bindParam(':ID'               , $id); 
        $query->bindParam(':DE'               , $dados['DE']);  
        $query->bindParam(':PARA'             , $dados['PARA']); 
        $query->bindParam(':EM_COPIA'         , $dados['EM_COPIA']); 
        $query->bindParam(':EM_COPIA_OCULTA'  , $dados['EM_COPIA_OCULTA']);  
        $query->bindParam(':MENSAGEM'         , $dados['MENSAGEM'], PDO::PARAM_LOB);  
        $query->bindParam(':ASSUNTO'          , $dados['ASSUNTO']);  
        $query->bindParam(':PAINEL_ID'        , $dados['PAINEL_ID']);  
        $query->bindParam(':CASO_ID'          , $dados['CASO_ID']);  
        $query->bindParam(':SUBFEED'          , $dados['SUBFEED']); 
        $query->bindParam(':TIPO'             , $dados['TIPO']); 
        $query->bindParam(':USUARIO_ID'       , $dados['USUARIO_ID']); 
        $query->bindParam(':COMENTARIO'       , $dados['COMENTARIO']); 
        $query->bindParam(':LISTA_FILE'       , $arquivos); 
        

        $query->execute();

        return true;
    }

    /**
     * Excluir feed de um caso
     * @access public
     * @param Integer ID
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function excluirFeed($dados, $con) {

        $id = $dados['FEED_ID'];

        $sql = 'DELETE FROM TBCASO_FEED F WHERE F.ID = :FEED_ID';

        $args = array(
            ':FEED_ID' => $id
        );

        if(array_key_exists('ARQUIVOS', $dados)){
            foreach($dados['ARQUIVOS'] as $arquivo) {

                if ( $arquivo['BINARIO'] != 'null' && $arquivo['ID'] > 0) {
                     $arquivo['VINCULO']  = $id;
                    ArquivoController::excluir($arquivo['ID']);
                }

            }
        }

        if(array_key_exists('EXCLUIR', $dados)){
            foreach($dados['EXCLUIR'] as $arquivo) {

                if ( $arquivo['BINARIO'] != 'null' && $arquivo['ID'] > 0) {
                     $arquivo['VINCULO']  = $id;
                    ArquivoController::excluir($arquivo['ID']);
                }

            }
        }

        $con->execute($sql, $args);

        return true;
    }


    /**
     * Gostei do feed de um caso
     * @access public
     * @param array dados
     * @param Conection $con
     * @return array
     */
    public static function gostei($dados, $con) {

        $id = $dados['FEED_ID'];

        $sql = 'select * from spu_feed_gostei(:USUARIO_ID, :FEED_ID)';

        $args = array(
            ':FEED_ID'    => $id,
            ':USUARIO_ID' => Auth::user()->CODIGO,
        );

        $ret = $con->query($sql, $args);

        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = [];
        }

        return $ret;
    }

    /**
     * Envolvidos em caso
     * @access public
     * @param array dados
     * @param Conection $con
     * @return array
     */
    public static function getEnvolvidos($dados, $con) {

        $caso   = $dados['CASO_ID'];
        $painel = $dados['PAINEL_ID'];

        $sql = 'SELECT
                    e.*,
                    u.usuario,
                    u.nome
                from tbcaso_envolvidos e, tbusuario u
                where e.caso_id = :CASO_ID
                and e.painel_id = :PAINEL_ID
                and e.usuario_id = u.codigo';

        $args = array(
            ':CASO_ID'   => $caso,
            ':PAINEL_ID' => $painel
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Envolvidos em caso
     * @access public
     * @param array dados
     * @param Conection $con
     * @return array
     */
    public static function rmvEnvolvidos($dados, $con) {

        $sql = 'DELETE from tbcaso_envolvidos e where e.id = :ID';

        $args = array(
            ':ID'   => $dados['ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Envolvidos em caso
     * @access public
     * @param array dados
     * @param Conection $con
     * @return array
     */
    public static function grvEnvolvidos($dados, $con) {

        $caso    = $dados['CASO_ID'];
        $painel  = $dados['PAINEL_ID'];
        $usuario = $dados['USUARIO_ID'];

        $sql = 'INSERT INTO TBCASO_ENVOLVIDOS (PAINEL_ID, CASO_ID, USUARIO_ID)
                       VALUES (:PAINEL_ID, :CASO_ID, :USUARIO_ID);';

        $args = array(
            ':CASO_ID'    => $caso,
            ':PAINEL_ID'  => $painel,
            ':USUARIO_ID' => $usuario
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Envolvidos em caso
     * @access public
     * @param array dados
     * @param Conection $con
     * @return array
     */
    public static function listEnvolvidos($dados, $con) {

        $painel = $dados['PARAN']['PAINEL_ID'];
        $filtro = $dados['FILTRO'];

        $sql = 'SELECT

                    u.codigo as ID,
                    u.usuario,
                    u.nome as descricao

                from tbcaso_usuarios c, tbusuario u
                where u.codigo = c.usuario_id
                and c.usuario_id > 0
                and c.painel_id = :PAINEL_ID
                and u.codigo||\' \'||u.usuario like \'%'.strtoupper($filtro).'%\'

                order by u.nome';

        $args = array(
            ':PAINEL_ID' => $painel
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function getPainelCaso($painel_id,$con) {

        $sql = 'SELECT * from tbcaso_painel p where p.id = :PAINEL_ID';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoRegistro($painel_id,$caso_id,$con) {

        if(!is_numeric($caso_id)){
            $caso_id = 0;
        }

        $sql = 'SELECT * from tbcaso_registro r
                where r.painel_id = :PAINEL_ID
                and r.id = :CASO_ID';

        $args = array(
            ':PAINEL_ID' => $painel_id,
            ':CASO_ID'   => $caso_id
        );

        $ret = $con->query($sql, $args);
        
        if(count($ret)>0){
            $ret = $ret[0];
        }else{
            $ret = [];
        }

        return $ret;
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoDetalhe($painel_id,$caso_id,$con) {

        if(!is_numeric($caso_id)){
            $caso_id = 0;
        }

        $sql = 'SELECT * from tbcaso_registro_detalhe r
                where r.painel_id = :PAINEL_ID
                and r.registro_id = :CASO_ID';

        $args = array(
            ':PAINEL_ID' => $painel_id,
            ':CASO_ID'   => $caso_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoItens($painel_id,$caso_id,$con) {

        if(!is_numeric($caso_id)){
            $caso_id = 0;
        }
        
        $sql = 'SELECT * from tbcaso_registro_itens r
                where r.registro_id = :CASO_ID';

        $args = array(
            ':CASO_ID'   => $caso_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Configuração Painel
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function confPainel($painel_id,$con) {

        $sql = 'SELECT
                    p.ID,
                    p.PAINEL_ID,
                    p.ORDEM,
                    p.DESCRICAO,
                    p.TIPO,
                    p.STATUS,
                    p."DEFAULT",
                    p."MIN",
                    p."MAX",
                    p.TAMANHO,
                    p.REQUERED,
                    p.VINCULO,
                    p.STEP,
                    p.GRUPO_ID,
                    p.VAR_NOME,
                    p.CAMPO_GRAVAR,
                    p.AUTOLOAD,
                    c.URL_CONSULTA,
                    c.TAMANHO_TABELA,
                    c.SQL_ID,
                    c.CAMPO_ID,
                    c.CAMPO_TABELA,
                    c.CAMPOS_RETORNO,
                    c.DESC_TABELA
                from
                    tbcaso_painel_conf p
                    left join tbcaso_campo_consulta c on (c.campo_id = p.id)

                where p.painel_id = :PAINEL_ID
                and p.status = 1
                order by p.ordem';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Configuração Painel itens
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function confPainelItens($painel_id,$con) {

        $sql = 'SELECT
                    *
                from
                    tbcaso_painel_conf_itens p
                where p.painel_id = :PAINEL_ID
                order by p.ordem';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar vinculos dos campos de um Painel itens
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function vinculos($painel_id,$con) {

        $sql = 'SELECT

                    d.*,
                    (select first 1 c.descricao from  tbcaso_painel_conf c where c.painel_id = d.painel_id and c.id = d.campo_vinculo) as descricao
                    
                from tbcaso_campo_dependencia d
                where d.painel_id = :PAINEL_ID
                order by d.CAMPO_ID';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar vinculos dos campos de um Painel itens
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function itensVinculo($painel_id,$con) {

        $sql = 'SELECT

                    d.ID,
                    d.PAINEL_ID,
                    d.CAMPO_ID,
                    d.CAMPO_VINCULO,
                    b.DEPENDENCIA_ID,
                    b.VALOR_CAMPO,
                    b.VALOR_VINCULO,
                    b.STATUS,
                    (select first 1 c.descricao from  tbcaso_painel_conf c where c.painel_id = d.painel_id and c.id = d.campo_vinculo) as descricao

                from tbcaso_campo_dependencia d, tbcaso_dependencia_detalhe b
                where d.painel_id = :PAINEL_ID
                and b.dependencia_id = d.id
                and b.painel_id = d.painel_id
                order by d.CAMPO_ID, b.VALOR_CAMPO';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar vinculos dos campos de um Painel itens
     * @access public
     * @param Int painel_id
     * @param Conection $con
     * @return array
     */
    public static function validacao($painel_id,$con) {

        $sql = 'SELECT * from tbcaso_validacao v
                where v.painel_id = :PAINEL_ID
                and v.status = 1';

        $args = array(
            ':PAINEL_ID' => $painel_id
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function paineisCasos($con) {

        $sql = 'SELECT * from tbcaso_painel p';

        $ret = $con->query($sql);
        
        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarContato($con,$itens,$painel_id) {

        $id_iten = $con->gen_id('GTBCASO_CONTATO_REG');

        foreach($itens as $i => $iten){

            $valor = $iten['VALOR'];

            $sql  = 'INSERT INTO TBCASO_CONTATO (REG_ID, CAMPO, VALOR, PAINEL_ID)
                        VALUES (:REG_ID, :CAMPO, :VALOR, :PAINEL_ID);';

            $args = array(
                ':CAMPO'     => $iten['ID'],
                ':REG_ID'    => $id_iten,
                ':PAINEL_ID' => $painel_id,
                ':VALOR'     => $valor,
            );

            $con->query($sql, $args);

            if($iten['COMITENS']){
                foreach($iten['ITENS'] as $ii => $iten2){
                    $sql = 'INSERT INTO TBCASO_CONTATO_ITENS (REG_ID, VALOR, CAMPO_ID, SELECIONADO)
                        VALUES (:REG_ID, :VALOR, :CAMPO_ID, :SELECIONADO);';

                    $args = array(
                        ':REG_ID'       => $id_iten,
                        ':VALOR'        => $iten2['CAMPO_VALOR'],
                        ':CAMPO_ID'     => $iten['ID'],
                        ':SELECIONADO'  => $iten2['VALOR']
                    );

                    $con->query($sql, $args);   
                }   
            }
        }

        return $id_iten;
    }

    /**
     * Gravar Caso
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarCaso($con,$itens,$campos,$painel_id,$caso_id) {

        if($caso_id < 1){
            $id_iten = $con->gen_id('gtbcaso_registro');
        }else{
           $id_iten = $caso_id;
        }

        foreach($itens as $i => $iten){

            $valor = 0;
            if(array_key_exists('VALOR',$iten)){
                $valor = $iten['VALOR'];
            }            

            $sql  = 'UPDATE or insert  into TBCASO_REGISTRO_DETALHE (REGISTRO_ID, CAMPO_ID, VALOR, PAINEL_ID,JSON)
                        VALUES (:REGISTRO_ID, :CAMPO, :VALOR, :PAINEL_ID, :JSON)
                        matching(REGISTRO_ID,CAMPO_ID,PAINEL_ID);';

            $args = array(
                ':CAMPO'       => $iten['ID'],
                ':REGISTRO_ID' => $id_iten,
                ':PAINEL_ID'   => $painel_id,
                ':VALOR'       => $valor,
                ':JSON'        => $iten['JSON'],
            );

            $con->query($sql, $args);

            if($iten['COMITENS']){
                foreach($iten['ITENS'] as $ii => $iten2){
                    $sql = 'UPDATE or insert  into TBCASO_REGISTRO_ITENS (REGISTRO_ID, VALOR, CAMPO_ID, SELECIONADO)
                              VALUES (:REGISTRO_ID, :VALOR, :CAMPO_ID, :SELECIONADO)
                              matching(REGISTRO_ID,CAMPO_ID,VALOR);';

                    $args = array(
                        ':REGISTRO_ID'  => $id_iten,
                        ':VALOR'        => $iten2['CAMPO_VALOR'],
                        ':CAMPO_ID'     => $iten['ID'],
                        ':SELECIONADO'  => $iten2['VALOR']
                    );

                    $con->query($sql, $args);   
                }   
            }
        }

        $sql  = 'UPDATE or insert  into TBCASO_REGISTRO (ID, PAINEL_ID, USUARIO_ID, MOTIVO_ID, TIPO_ID, ORIGEM_ID, RESPONSAVEL_ID, STATUS_ID, CONTATO_ID)
                     VALUES (:ID, :PAINEL_ID, :USUARIO_ID, :MOTIVO_ID, :TIPO_ID, :ORIGEM_ID, :RESPONSAVEL_ID, :STATUS_ID, :CONTATO_ID) matching(ID,PAINEL_ID);';

        $MOTIVO = '';
        if(array_key_exists('MOTIVO',$campos)){
            $MOTIVO = $campos['MOTIVO'];
        } 

        $TIPO = '';
        if(array_key_exists('TIPO',$campos)){
            $TIPO = $campos['TIPO'];
        }

        $ORIGEM = '';
        if(array_key_exists('ORIGEM',$campos)){
            $ORIGEM = $campos['ORIGEM'];
        } 

        $RESPONSAVEL = '';
        if(array_key_exists('RESPONSAVEL',$campos)){
            $RESPONSAVEL = $campos['RESPONSAVEL'];
        } 

        $STATUS = '';
        if(array_key_exists('STATUS',$campos)){
            $STATUS = $campos['STATUS'];
        } 

        $CONTATO = '';
        if(array_key_exists('CONTATO',$campos)){
            $CONTATO = $campos['CONTATO'];
        } 

        $args = array(
            ':ID'             => $id_iten,
            ':PAINEL_ID'      => $painel_id,
            ':USUARIO_ID'     => Auth::user()->CODIGO,
            ':MOTIVO_ID'      => $MOTIVO,
            ':TIPO_ID'        => $TIPO,
            ':ORIGEM_ID'      => $ORIGEM,
            ':RESPONSAVEL_ID' => $RESPONSAVEL,
            ':STATUS_ID'      => $STATUS,
            ':CONTATO_ID'     => $CONTATO,
        );


        $con->query($sql, $args);

        return $id_iten;
    }

    public static function Motivos($param,$con) {

        $filter  = strtoupper($param['FILTRO']);
        $options = $param['OPTIONS'];
        $id      = $param['PARAN']['ID'];

        $desc = !empty($filter) ? 'and a.id||\' \'||upper(a.descricao) like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';
        $reg  = $id > 0 ? 'and a.id = '.$id : '';


        $sql = 'SELECT
                    *
                from tbcaso_motivo a  
                
                where a.painel_id = :PAINEL_ID

                '.$desc.'
                '.$reg .'

            ';

        $args = array(
            ':PAINEL_ID' => $options['PAINEL_CASO']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }

    public static function confContato($param,$con) {

        $sql = 'SELECT
                    *
                from tbcaso_contato_conf c
                where c.painel_id = :PAINEL_ID
                and status = 1

                order by ordem
            ';

        $args = array(
            ':PAINEL_ID' => $param['PAINEL_CASO']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }

    public static function finalizar($param,$con) {

        $sql = 'select first 1 e.id from tbcaso_status e where e.painel_id = :PAINEL_ID and e.fechado = 1';
        $args = array(
            ':PAINEL_ID' => $param['painel_id'],
        );

        $id = 0;

        $ret = $con->query($sql,$args);
        if(count($ret) > 0){$id = $ret[0]->ID;}

        if($id > 0){

            $dados = [
                'FEED_ID'           => 0,
                'DE'                => '', 
                'PARA'              => '', 
                'EM_COPIA'          => '',
                'EM_COPIA_OCULTA'   => '', 
                'MENSAGEM'          => 'Descrição técnica do caso<br><br>' . $param['problema'], 
                'ASSUNTO'           => 'Descrição técnica do caso',
                'PAINEL_ID'         => $param['painel_id'], 
                'CASO_ID'           => $param['caso_id'],  
                'SUBFEED'           => 0, 
                'TIPO'              => 1,
                'USUARIO_ID'        => 0
            ];

            _11150DAO::gravarFeed($dados, $con);

            $dados = [
                'FEED_ID'           => 0,
                'DE'                => '', 
                'PARA'              => '', 
                'EM_COPIA'          => '',
                'EM_COPIA_OCULTA'   => '', 
                'MENSAGEM'          => 'Solução para este caso<br><br>' . $param['problema'], 
                'ASSUNTO'           => 'Solução para este caso',
                'PAINEL_ID'         => $param['painel_id'], 
                'CASO_ID'           => $param['caso_id'],  
                'SUBFEED'           => 0, 
                'TIPO'              => 1,
                'USUARIO_ID'        => 0
            ];

            _11150DAO::gravarFeed($dados, $con);

            $sql = 'UPDATE TBCASO_REGISTRO SET 
                        STATUS_ID = :STATUS_ID,
                        FECHADO   = 1
                    WHERE (ID = :CASO_ID) AND
                          (PAINEL_ID = :PAINEL_ID);';

            $args = array(
                ':PAINEL_ID' => $param['painel_id'],
                ':CASO_ID'   => $param['caso_id'],
                ':STATUS_ID' => $id
            );

            $ret = $con->execute($sql,$args);
        }else{
           log_erro('Status para casos finalizados não foi definido');            
        }
        
        return $ret;

    }

    public static function confItens($param,$con) {

        if(array_key_exists('OPTIONS',$param)){
            if(array_key_exists('PAINEL_CASO',$param['OPTIONS'])){
                $id = $param['OPTIONS']['PAINEL_CASO']['ID'];
            }else{
                $id = $param['PAINEL_CASO']['ID'];
            }
        }else{
            $id = $param['PAINEL_CASO']['ID'];
        }

        $sql = 'SELECT
                    *
                from tbcaso_contato_conf_itens c
                where c.painel_id = :PAINEL_ID

                order by c.campo_id,c.ordem
            ';

        $args = array(
            ':PAINEL_ID' => $id,
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }

    public static function camposAgrupamentos($param,$con) {

        $sql = 'SELECT
                    *
                from tbcaso_campos_agrupamento c
                where c.painel_id = :PAINEL_ID
            ';

        $args = array(
            ':PAINEL_ID' => $param['PAINEL_CASO']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;
    }

    public static function camposAgrupamentos2($painel_id, $con) {

        $param = [];
        $param['PAINEL_CASO'] = [];
        $param['PAINEL_CASO']['ID'] = $painel_id;

        $ret = _11150DAO::camposAgrupamentos($param,$con);
        
        return $ret;
    }

    public static function Tipos($param,$con) {

        $filter = strtoupper($param['FILTRO']);
        $desc = !empty($filter) ? 'and a.id||\' \'||upper(a.descricao) like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';

        $id      = $param['PARAN']['ID'];
        $reg  = $id > 0 ? 'and a.id = '.$id : '';

        $options = $param['OPTIONS'];

        $sql = 'SELECT
                    *
                from tbcaso_tipo a  
                
                where a.motivo_id = :MOTIVO_ID

                '.$desc.'
                '.$reg.'

            ';

        $args = array(
            ':MOTIVO_ID' => $options['dados']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }

    public static function Origens($param,$con) {

        $filter = strtoupper($param['FILTRO']);
        $desc = !empty($filter) ? 'and a.id||\' \'||upper(a.descricao) like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';

        $options = $param['OPTIONS'];

        $id      = $param['PARAN']['ID'];
        $reg  = $id > 0 ? 'and a.id = '.$id : '';

        $sql = 'SELECT
                    *
                from tbcaso_origem a  
                
                where a.tipo_id = :MOTIVO_ID

                '.$desc.'
                '.$reg.'

            ';

        $args = array(
            ':MOTIVO_ID' => $options[1]['dados']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }

    public static function usuario($painel_id,$id,$con) {

        $sql = 'SELECT first 50

                    e.USUARIO,
                    e.CODIGO,
                    e.TURNO_CODIGO,
                    e.FAMILIA_CODIGO,
                    e.EMAIL,
                    e.SETOR,
                    e.CHAVE,
                    e.IP,
                    e.RAMAL,
                    e.NOME,
                    e.SKYPE,
                    e.CARGO,
                    e.CCUSTO_ID,

                    u.adicionar,
                    u.alterar,
                    u.atender,
                    u.excluir,
                    u.VER_ANOTACOES,
                    u.VER_EMAIL,
                    u.PAINEL_ID,
                    u.RESPONSAVEL,
                    u.FINALIZAR,
                    u.FEED

                    --coalesce(r.representante_codigo,0) representante_codigo

                from
                tbcaso_usuarios u,
                tbusuario e
                --left join tbusuario_representante r on (r.usuario_codigo = e.codigo)
                where u.painel_id = :PAINEL_ID
                and e.codigo = u.usuario_id
                and e.codigo = :CODIGO
                and e.codigo > 0

            ';

        $args = array(
            ':PAINEL_ID' => $painel_id,
            ':CODIGO'    => $id,
        );

        $ret = $con->query($sql,$args);
        
        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = [];
        }

        return $ret;

    }

    public static function usuario_parametros($painel_id,$id,$con) {

        $sql = 'SELECT
                    u.nome,
                    p.valor
                from
                    tbcaso_parametros_usuario u,
                    tbcaso_usuario_parametro  p

                where u.painel_id = :PAINEL_ID
                and p.parametro_id = u.id
                and p.usuario_id = :USUARIO_ID';

        $args = array(
            ':PAINEL_ID'  => $painel_id,
            ':USUARIO_ID' => $id,
        );

        $ret = $con->query($sql,$args);

        return $ret;

    }

    public static function Responsavel($param,$con) {

        $filter = strtoupper($param['FILTRO']);
        $desc = !empty($filter) ? 'and e.codigo||\' \'||upper(e.usuario) like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';

        $options = $param['OPTIONS'];

        $id      = $param['PARAN']['ID'];
        $reg  = $id > 0 ? 'and e.CODIGO = '.$id : '';

        $sql = 'SELECT first 50

                    e.NOME as USUARIO,
                    e.CODIGO,
                    e.TURNO_CODIGO,
                    e.FAMILIA_CODIGO,
                    e.EMAIL,
                    e.SETOR,
                    e.CHAVE,
                    e.IP,
                    e.RAMAL,
                    e.NOME,
                    e.SKYPE,
                    e.CARGO,
                    e.CCUSTO_ID,

                    u.adicionar,
                    u.alterar,
                    u.atender,
                    u.excluir,
                    u.VER_ANOTACOES,
                    u.VER_EMAIL,
                    u.PAINEL_ID,
                    u.RESPONSAVEL,

                    coalesce(r.representante_codigo,0) representante_codigo

                from
                tbcaso_usuarios u,
                tbusuario e
                left join tbusuario_representante r on (r.usuario_codigo = e.codigo)
                where u.responsavel = 1
                and u.painel_id = :PAINEL_ID
                and e.codigo = u.usuario_id
                and e.codigo > 00

                '.$desc.'
                '.$reg.'

            ';

        $args = array(
            ':PAINEL_ID' => $options['PAINEL_CASO']['ID'],
        );

        $ret = $con->query($sql,$args);
 
        return $ret;

    }

     public static function getResponsavel($painel_id, $caso_id, $con) {

        $sql = 'SELECT
                    u.usuario_id
                from
                    tbcaso_usuarios u
                where   u.notificacao = 1
                    and u.responsavel = 1
                    and u.painel_id = :PAINEL_ID
                    and u.usuario_id <> :USUARIO_ID
                    and u.usuario_id > 0
                ';

        $args = array(
            ':PAINEL_ID'  => $painel_id,
            ':USUARIO_ID' => \Auth::user()->CODIGO
        );

        $ret = $con->query($sql,$args);
 
        return $ret;

    }

    public static function getUserNotification($painel_id, $caso_id, $con) {

        $sql = 'SELECT distinct * from
                    (

                        SELECT
                            u.usuario_id
                        from
                            tbcaso_usuarios u
                        where   u.notificacao = 1
                            and u.responsavel = 1
                            and u.painel_id = :PAINEL_ID
                            and u.usuario_id <> :USUARIO_ID1
                            and u.usuario_id > 0
                        
                            union 
                        
                        select e.usuario_id from tbcaso_envolvidos e where e.caso_id = :CASO_ID
                        and e.usuario_id <> :USUARIO_ID2

                    )  order by usuario_id desc
                ';

        $args = array(
            ':PAINEL_ID'   => $painel_id,
            ':USUARIO_ID1' => \Auth::user()->CODIGO,
            ':USUARIO_ID2' => \Auth::user()->CODIGO,
            ':CASO_ID'     => $caso_id
        );

        $ret = $con->query($sql,$args);
 
        return $ret;

    }

    

    public static function Contatos($param,$con) {

        $filter = strtoupper($param['FILTRO']);
        $desc = !empty($filter) ? '%'.  str_replace(' ', '%', $filter) .'%' : '%';

        $options = $param['OPTIONS'];

        $id      = $param['PARAN']['ID'];
        $reg  = $id > 0 ? ' and C.REG_ID = '.$id : '';

        $id = array_key_exists('CONTATO_ID',$param['OPTIONS']) ? ' AND C.REG_ID = ' . $param['OPTIONS']['CONTATO_ID'] : '';

        $sql = 'SELECT --first 50

                    t.*, f.tipo,

                    (select list(i.valor||\'|\'||i.selecionado,\',\') from tbcaso_contato_itens i where i.campo_id = t.campo and i.reg_id = t.reg_id) as VAL_ITEN

                FROM TBCASO_CONTATO T , TBCASO_CONTATO_CONF F, (
                    SELECT FIRST 10 DISTINCT
                    
                        C.REG_ID AS ID
                    
                    FROM TBCASO_CONTATO C,TBCASO_CONTATO_CONF F
                    
                    WHERE C.PAINEL_ID = :PAINEL_ID
                    AND F.PAINEL_ID = C.PAINEL_ID
                    AND C.CAMPO = F.ID
                    AND IIF(F.FILTRO = 1,UPPER(C.VALOR) LIKE UPPER(\''.$desc.'\'),FALSE)

                    '.$id.'
                    '.$reg.'
                    

                ) E WHERE T.REG_ID = E.ID and f.id = t.campo

                order by t.reg_id, f.ordem';

        $args = array(
            ':PAINEL_ID' => $options['PAINEL_CASO']['ID'],
        );

        $ret = $con->query($sql,$args);
        
        return $ret;

    }
	
}