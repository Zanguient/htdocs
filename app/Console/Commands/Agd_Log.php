<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class Agd_Log extends Command
{

    
    protected $signature = 'Agd_Log';
    protected $description = 'Agrupa logs';
    
    /**
     * Codigo executado
     */
    public function handle()
    {   
        try{
            
            $schedule         = app_path(). '/Console/Commands/Saidas/';
            $schedule2        = app_path(). '/Console/Commands/Logs/';
            $diretorio        = dir($schedule);
            $arquivos         = [];
            $novo_log         = "";
            
            while($file_name = $diretorio->read()){

                if (  pathinfo($file_name, PATHINFO_EXTENSION) != 'txt' ) {
                    continue;
                }

                array_push($arquivos, (object)[
                    'FILE_DIR'  => $schedule,
                    'FILE_NAME' => $file_name,
                    'TIPO'      => 1
                ]);

                $lines            = file($schedule.$file_name);
                $file             = "";

                // Percorre o array, mostrando o fonte HTML com numeração de linhas.
                foreach ($lines as $line_num => $line) {
                    $file .= $line;
                }

                $novo_log = $novo_log."\n".$file; 
            }
            
            $fp = fopen($schedule2."Log_".date("Ymd").".txt", "a+");
            $escreve = fwrite($fp, "\n"."\n"."#####################################################"."\n"."Log de ".date("d/m/Y H:i:s")."\n".$novo_log);
            fclose($fp);

            $diretorio->close();
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
			
    }
     
}
