<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Cache;
use App\Models\Socket\Socket;

class Agd_Est extends Command
{

    protected $signature    = 'Agd_Est';
    protected $description  = 'Executa agendamento de Estoque';
    protected $con          = null;
    protected $cache_name   = '15080-KANBAN-AGENDADOR';
    protected $socket_title = 'Kanban - Reposição de Estoque';

//    public function __construct() {
//        parent::__construct();
//        
//        $this->con = new _Conexao();
//    }
//    
//    public function __destruct() {
//        $this->con->commit();
//    }

    /**
     * Codigo executado
     */
    public function handle()
    {   
        
        
        $this->con = new _Conexao();
        try {
            


    //        $this->deleteCache();

            $necessidades = $this->getNecessidadeFamilia();
            $cached       = $this->getCache();

            if ( empty($cached) ) {

                foreach ( $necessidades as $item ) {
                    $this->notify($item);
                }

                $this->postCache($necessidades);
            } else {

                $notificar = [];
                $localized = false;

                foreach ($necessidades as $item) {

                    $localized = false;

                    foreach ( $cached as $cache ) {

                        if ( $item->USUARIO_ID == $cache->USUARIO_ID && $item->LOCALIZACAO_ID == $cache->LOCALIZACAO_ID ) {

                            $localized  = true;

                            if ( trim($item->FAMILIAS_ID) != trim($cache->FAMILIAS_ID) || $item->ESTOQUE_NECESSIDADE > $cache->ESTOQUE_NECESSIDADE ) {

                                $localized  = false;                       
                            }  
                        }   
                    }

                    if ( !$localized ) {

                        if ( ! in_array($item, $notificar) ) {
                            array_push($notificar, $item);
                        }
                    }
                }

                $notified  = false;
                foreach ( $notificar as $item ) {
                    $this->notify($item);
                    $notified = true;
                }

                if ( $notified ) {
                    $this->deleteCache();
                    $this->postCache($necessidades);            
                }
            }
        
            $this->con->commit();
        } catch (Exception $ex) {
            $ex->getTraceAsString();
            $this->con->rollback();
        }        
    }
    
    private function notify($item) {

        $user_id     = $item->USUARIO_ID;
        $localizacao = $item->LOCALIZACAO_DESCRICAO;
        $familias    = $item->FAMILIAS_DESCRICAO;
        $msg         = '<span>Necessidade para as seguintes famílias da localização <b>' . $localizacao . '</b>:<br/>' . $familias . '.</span><a href="' . url('/_15080') . '" target="_blank">Consulte o Detalhamento</a>';

        $this->sendMsg($msg, $user_id);   
    }
    
    private function sendMsg($msg,$user) {

        $sc = new Socket([]);

        $data = [
            'MENSAGEM'          => $msg, 
            'TITULO'            => $this->socket_title 
        ];

        $sc->sendNotification($data, 1, $user, 'NOTIFICACAO');

        $sc->close();                 
    }
    
    private function postCache($obj) {
        Cache::forever($this->cache_name, $obj);             
    }
    
    private function getCache() {
        return Cache::get($this->cache_name);
    }
    
    private function deleteCache() {
        return Cache::forget($this->cache_name);
    }


    private function getNecessidadeFamilia() {

        $sql = "
            SELECT
                LOCALIZACAO_ID,
                LOCALIZACAO_DESCRICAO,
                USUARIO_ID,           
                LIST(DISTINCT FAMILIA_ID,', ') FAMILIAS_ID,
                LIST(DISTINCT ' • ' || FAMILIA_DESCRICAO,'<br/>') FAMILIAS_DESCRICAO,
                SUM(ESTOQUE_NECESSIDADE) ESTOQUE_NECESSIDADE

            FROM (
                SELECT
                    X.*,
                    IIF( ESTOQUE_FISICO < ESTOQUE_MIN, ESTOQUE_MAX - ESTOQUE_FISICO, 0 ) ESTOQUE_NECESSIDADE
                FROM (
                    SELECT
                        E.LOCALIZACAO_ID,
                        L1.DESCRICAO LOCALIZACAO_DESCRICAO,
                        E.FAMILIA_ID,
                        (SELECT FIRST 1 DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = E.FAMILIA_ID) FAMILIA_DESCRICAO,
                        E.ESTOQUE_MIN,
                        E.ESTOQUE_MAX,
                        COALESCE(
                        (SELECT FIRST 1 SUM(SP.SALDO)
                           FROM VWESTOQUE_SALDO_PRODUTO SP
                          WHERE SP.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_ID
                            AND SP.LOCALIZACAO_ID     = E.LOCALIZACAO_ID
                            AND SP.PRODUTO_ID         = E.PRODUTO_ID
                            AND SP.TAMANHO            = E.TAMANHO),0) ESTOQUE_FISICO,
                        U.USUARIO_ID
        
                    FROM
                        VWESTOQUE_MINIMO_TAMANHO E
                        LEFT JOIN TBKANBAN_LOTE L ON L.LOCALIZACAO_ID = E.LOCALIZACAO_ID AND L.STATUS = '0',
                        TBLOCALIZACAO L1,
                        TBPRODUTO P,
                        TBFAMILIA_FICHA F,
                        TBNOFICICACAO_USUARIO U
        
        
                    WHERE
                        L1.CODIGO                = E.LOCALIZACAO_ID
                    AND P.CODIGO                 = E.PRODUTO_ID
                    AND F.FAMILIA_CODIGO         = P.FAMILIA_CODIGO
                    AND F.ESTABELECIMENTO_CODIGO = E.ESTABELECIMENTO_ID
                    AND P.CODIGO                 = E.PRODUTO_ID
                    AND E.HABILITA_KANBAN        = '1'
                    AND U.TABELA                 = 'TBLOCALIZACAO'
                    AND U.TABELA_NIVEL           = 0
                    AND U.TABELA_ID              = E.LOCALIZACAO_ID
                    ) X
                ) Y
            WHERE ESTOQUE_NECESSIDADE > 0

            GROUP BY 1,2,3          
        ";	
        
        return $this->con->query($sql);        
    }
}