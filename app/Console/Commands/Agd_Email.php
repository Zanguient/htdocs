<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DTO\Helper\Email;
use App\Models\Conexao\_Conexao;

class Agd_Email extends Command
{
 
    protected $signature = 'Agd_Email';
    protected $description = 'Executa agendamento de emails';
    
    public static function gravarExecucao($id){
        
        try {
            echo 'Gravando execução do agendamento de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbagendamento_email set datahoraultimoexec = current_timestamp, flag = 0 where id = :ID
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
        
        try {
            
        $sql = "
			select (((current_timestamp - e.datahoraultimoexec) * 60 * 24)) as tempo_decorrido,e.*,s.sql
            from tbagendamento_email e,tbsql s

            WHERE current_time between e.horainicio AND e.horafim
                 and s.id = e.sql_id
                 and e.flag = 0
                 and (

                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > e.intervalo) and e.repetivel = 1)
                    OR
                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > 1350) and e.repetivel = 0)

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

        $sql = "update tbagendamento_email e

            set e.flag = 1,
            e.datahoraultimoexec = current_timestamp

            WHERE current_time between e.horainicio AND e.horafim

                 and (

                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > e.intervalo) and e.repetivel = 1)
                    OR
                    ((((current_timestamp - e.datahoraultimoexec) * 60 * 24) > 1350) and e.repetivel = 0)

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
        
        $ret2 = $con->query($sql);

        try {
            $con->commit();
            $con->close();
            $con = null;
        } catch (Exception $ex) {

        }
        
        foreach ($ret as $value) {
            
            try {
                echo 'Execução do agendamento de ID:'.$value->ID . "\n";
                $this->gravarExecucao($value->ID);
                
                $con2 = new _Conexao();

                $ret = $con2->query( utf8_encode($value->SQL));

                if($value->GERARELATORIO == 1){
                    if(count($ret) > 0){
                        echo 'Gerando relatório para o agendamento de ID:'.$value->ID . "\n";

                        $rel_id = $value->RELATORIO_ID;

                        $args = [
                            ':ID' => $rel_id
                        ];

                        $sql = "select h.* from tbrelatorio_html h where h.id = :ID";
                        $rel = $con2->query($sql,$args);

                        if(count($rel) > 0){$rel = $rel[0];}

                        $sql = "select h.* from tbrelatorio_html_detalhe h where h.relatorio_id = :ID";	
                        $det = $con2->query($sql,$args);

                        $sql = "select h.* from tbhtml_totalizador h where h.relatorio_id = :ID";	
                        $tot = $con2->query($sql,$args);

                        $sql = "select h.* from tbhtml_totalizador_geral h where h.relatorio_id = :ID";	
                        $tog = $con2->query($sql,$args);

                        $relatorio = [
                            'RELATORIO'   => $rel,
                            'DETALHE'     => $det,
                            'TOTALIZADOR' => $tot,
                            'TOTALIZADOG' => $tog
                        ];

                        $dados_ret = objectToArray($ret);

                        $corpo_email = view('helper.templates.' . $rel->FILE, [
                            'RELATORIO' => $relatorio,
                            'DADOS'     => $dados_ret,
                            'DATAHORA'  => date('d/m/Y H:i:s')
                        ]);

                        $obj = [];

                        $obj['Email']       = $value->EMAIL;
                        $obj['UsuarioId']   = 0;
                        $obj['Assunto']     = $value->ASSUNTO;
                        $obj['Corpo']       = utf8_decode($corpo_email);
                        $obj['Status']      = '1';
                        $obj['Datahora']    = date('d.m.Y H:i:s');

                        Email::gravar2($obj);

                        echo 'Email do relatório de ID:'. $value->ID . " gravado " . "\n";
                    }else{
                        echo 'Não há dados para o relatório do agendamento de ID:'.$value->ID . "\n";
                    }
                }else{
                    echo 'Não gera relatório para o agendamento de ID:'.$value->ID . "\n";
                }

                $con2->commit();
                $con2->close();
                $con2 = null;
                
            } catch (Exception $exc) {
                
                try {
                    $con2->rollback();
                    $con2->close();
                    $con2 = null;
                } catch (Exception $ex) {
                    
                }
                
                echo 'Erro ao executar agendamento de email de ID:'.$value->ID.' ('.$exc->getTraceAsString().')';
            }

            try {

                $con = new _Conexao();

                $sql = "update tbagendamento_email set flag = 0 where id = ".$value->ID;  
            
                $ret2 = $con->query($sql);

                $con->commit();
                $con->close();
                $con = null;

            } catch (Exception $ex) {}
            
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
			
}
     
}

