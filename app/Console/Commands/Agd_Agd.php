<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DTO\Helper\Email;
use App\Models\Conexao\_Conexao;
use App\Http\Controllers\Admin\_11190Controller;
use App\Models\Socket\Socket;

class Agd_Agd extends Command
{
 
    protected $signature = 'Agd_Agd';
    protected $description = 'Executa agendamento de emails';
    
    public static function gravarExecucao($id){
        
        try {
            echo 'Gravando execução do agendamento de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbnotificacao f set f.executado = 1 where f.id = :ID
            ";	

            $args = [
                ':ID' => $id
            ];

            $con->execute($sql,$args);
            $con->commit();
            
        } catch (Exception $exc) {
            echo 'Erro ao gravar execução do agendamento de ID:'.$id . "\n";
            echo $exc->getTraceAsString();
            echo '';
        }

    } 
    
    /**
     * Codigo executado
     */
    public function handle()
    {   
        ///*
        $con = new _Conexao();
        
        try{
            
            $sql = "SELECT
                        *
                    from
                        tbnotificacao f
                    where f.executado = 0
                    and f.agendamento <= current_timestamp";	
    		
    		$ret = $con->query($sql);
            
            foreach ($ret as $value) {
                
                try {
                    
                    echo 'Execução do agendamento de ID:'.$value->ID . "\n";

                    $ms = ['MENSAGEM'  => $value->MENSAGEM, 'TITULO' => $value->TITULO, 'AGENDAMENTO_ID' => $value->ID ];

                    $sc = new Socket([]);
                    $sc->sendNotification($ms, $value->TIPO, $value->USUARIO_ID, 'NOTIFICACAO');

                    $this->gravarExecucao($value->ID);

                } catch (Exception $exc) {
                    echo 'Erro ao executar agendamento de email de ID:'.$value->ID.' ('.$exc->getTraceAsString().')';
                }
                
            }
            
        } catch (Exception $exc) {
            try {
                $con->rollback();
                $con->close();
                $con = null;
            } catch (Exception $ex) {

            }
            echo $exc->getTraceAsString();
        }
        //*/
			
    }
     
}

