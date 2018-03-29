<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\Conexao\_Conexao;

class Agd_Sql extends Command
{

    protected $signature = 'Agd_Sql';
    protected $description = 'Executa agendamento de sqls';
    
    public static function gravarExecucao($id){
        
        try {
            echo 'Gravando execução do agendamento de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbagendamento_sql set datahoraultimoexec = current_timestamp where id = :ID
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
        $con = new _Conexao();
        
        $sql = "
			select (((current_timestamp - e.datahoraultimoexec) * 60 * 24)) as tempo_decorrido,e.id,e.tipo,e.horainicio,e.horafim,e.intervalo,e.sql_id, s.sql
            from tbagendamento_sql e,tbsql s

            WHERE current_time between e.horainicio AND e.horafim
                 and s.id = e.sql_id
                 and (

                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > e.intervalo) and e.repetivel = 1)
                    OR
                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > 1200) and e.repetivel = 0)

                 )

                  and e.status = 1
                  and (
                  (E.tipo = 'M' AND E.dia = extract(day from current_date))
                    or  (E.tipo = 'S' AND (0 = extract( WEEKDAY from current_date)
                    AND E.dom = 1))  or  (E.tipo = 'S' AND (1 = extract( WEEKDAY from current_date)
                    AND E.seg = 1))  or  (E.tipo = 'S' AND (2 = extract( WEEKDAY from current_date)
                    AND E.ter = 1))  or  (E.tipo = 'S' AND (3 = extract( WEEKDAY from current_date)
                    AND E.qua = 1))  or  (E.tipo = 'S' AND (4 = extract( WEEKDAY from current_date)
                    AND E.qui = 1))  or  (E.tipo = 'S' AND (5 = extract( WEEKDAY from current_date)
                    AND E.sex = 1))  or  (E.tipo = 'S' AND (6 = extract( WEEKDAY from current_date)
                    AND E.sab = 1))
                    or  (E.tipo = 'D' AND (0 = extract( WEEKDAY from current_date)
                    AND E.dom = 1))  or  (E.tipo = 'D' AND (1 = extract( WEEKDAY from current_date)
                    AND E.seg = 1))  or  (E.tipo = 'D' AND (2 = extract( WEEKDAY from current_date)
                    AND E.ter = 1))  or  (E.tipo = 'D' AND (3 = extract( WEEKDAY from current_date)
                    AND E.qua = 1))  or  (E.tipo = 'D' AND (4 = extract( WEEKDAY from current_date)
                    AND E.qui = 1))  or  (E.tipo = 'D' AND (5 = extract( WEEKDAY from current_date)
                    AND E.sex = 1))  or  (E.tipo = 'D' AND (6 = extract( WEEKDAY from current_date)
                    AND E.sab = 1))
                  )
		";	
		
		$ret = $con->query($sql);

        foreach ($ret as &$value) {
            $con2 = new _Conexao();
            try {
                echo 'Execução do agendamento SQL de ID:'.$value->ID . "\n";
                
                
                $ret = $con2->execute($value->SQL);
                
                try {
                    echo 'Commit execução:'.$value->ID . "\n";
                    $con2->commit();
                    $con2->close();
                    $con2 = null;
                } catch (Exception $ex) {
                    $con2->rollback();
                    $con2->close();
                    $con2 = null;
                    echo 'Erro ao executar agendamento de SQL de ID:'.$value->ID.' ('.$exc->getTraceAsString().')';                    
                }

                $this->gravarExecucao($value->ID);
            } catch (Exception $exc) {
                try {
                    $con2->rollback();
                    $con2->close();
                    $con2 = null;
                } catch (Exception $ex) {
                    
                }
                echo 'Erro ao executar agendamento de SQL de ID:'.$value->ID.' ('.$exc->getTraceAsString().')';
            }
            
        }
			
    }
}


