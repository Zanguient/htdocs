<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DTO\Helper\Email;
use App\Models\Conexao\_Conexao;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Env_Email extends Command
{
    
    protected $signature = 'Env_Email';
    protected $description = 'Executa agendamento de emails';
    
    public static function gravarEnvio($id){
        
        try {
            echo date("d/m/Y H:i:s").' - Gravando envio do email de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbemail set datahora_envio = current_timestamp, status = 10 where id = :ID
            ";	

            $args = [
                ':ID' => $id
            ];

            $con->execute($sql,$args);
            $con->commit();
            $con->close();
            $con = null;

        } catch (Exception $exc) {

            try {
                $con->rollback();
                $con->close();
                $con = null;
            } catch (Exception $ex) {}

            echo date("d/m/Y H:i:s").' - Erro ao gravar envio do email de ID:'.$id . "\n";
            echo $exc->getTraceAsString();
            echo '';
        }

    }
    
    public static function gravarTentativa($id){
        
        try {
            echo date("d/m/Y H:i:s").' - Gravando tentativa de envio do email de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbemail set status = (status+1) where id = :ID
            ";	

            $args = [
                ':ID' => $id
            ];

            $con->execute($sql,$args);
            $con->commit();
            $con->close();
            $con = null;

        } catch (Exception $exc) {
            
            try {
                $con->rollback();
                $con->close();
                $con = null;
            } catch (Exception $ex) {}

            echo date("d/m/Y H:i:s").' - Erro ao gravar tentativa de envio do email de ID:'.$id . "\n";
            echo $exc->getTraceAsString();
            echo '';
        }

    }
    
    public static function gravarErro($id){
        
        try {
            echo date("d/m/Y H:i:s").' - Gravando Erro na tentativa de envio do email de ID:'.$id . "\n";
            $con = new _Conexao();
        
            $sql = "
                update tbemail set status = 20 where id = :ID
            ";	

            $args = [
                ':ID' => $id
            ];

            $con->execute($sql,$args);
            $con->commit();
            $con->close();
            $con = null;

        } catch (Exception $exc) {
            
            try {
                $con->rollback();
                $con->close();
                $con = null;
            } catch (Exception $ex) {}

            echo date("d/m/Y H:i:s").' - Erro ao gravar tentativa de envio do email de ID:'.$id . "\n";
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
            select
                ID,
                EMAIL,
                USUARIO_ID,
                ASSUNTO,
                CORPO,
                STATUS,
                DATAHORA,
                DATAHORA_ENVIO,
                FLAG,
                coalesce(CC,'') CC,
                coalesce(CCO,'') CCO,
                PAINEL_ID,
                iif(PAINEL_ID = 0, DE1,DE2) as DE

            from

            (
                select first 5
                    e.*,
                   coalesce((select first 1 u.email from tbusuario u where u.codigo = e.usuario_id),'gestaocorporativa@delfa.com.br') as DE1,
                   coalesce((select first 1 p.email from tbcaso_painel p where p.id = e.painel_id ),'gestaocorporativa@delfa.com.br') as DE2

                from tbemail e

                where e.status < 10
                and e.email <> ''
                and e.datahora_envio is null
                and e.flag = 0
            ) A
        ";  
        
        $ret = $con->query($sql);

        try {

            $con1 = new _Conexao();

            $sql = "
                update tbemail e

                    set e.flag = 1

                where e.status < 10
                and e.email <> ''
                and e.datahora_envio is null
                and e.flag = 0
            ";  
            
            $ret2 = $con1->query($sql);

            $con1->commit();
            $con1->close();
            $con1 = null;
        } catch (Exception $ex) {}
        
        foreach ($ret as $value) {

            $con1 = new _Conexao();

            $sql = "
                select
                    ID,
                    EMAIL_ID,
                    ARQUIVO_ID,
                    DESCRICAO
                from TBEMAIL_ANEXO e
                where e.email_id = ".$value->ID."
            ";  
            
            $files = $con1->query($sql);

            $con1->commit();
            $con1->close();
            $con1 = null;
            
            try {
                
                $this->gravarTentativa($value->ID);
                echo date("d/m/Y H:i:s").' - Enviando o email de ID:'.$value->ID ." para ". $value->EMAIL. "\n";
                try {
                    //*
                    $mail = new PHPMailer(true);
                    try {

                        $email_servidor = trim($value->DE);
                        $para     = str_replace(';',',',trim($value->EMAIL));
                        $mensagem = trim($value->CORPO);
                        $assunto  = trim($value->ASSUNTO);

                        $cco = trim($value->CCO);
                        $cc  = trim($value->CC);

                        
                        $mail->SMTPDebug = 2;
                        $mail->setFrom($email_servidor);
                        $mail->addReplyTo($email_servidor);

                        if($para  != ''){
                            $lista = explode(",", $para);
                            foreach ($lista as &$item) {
                               if(trim($item) != ''){
                                   $mail->addAddress(trim($item));
                               } 
                            }
                        }

                        if($cc  != ''){
                            $lista = explode(",", $cc);
                            foreach ($lista as &$item) {
                               if(trim($item) != ''){
                                   $mail->addCC(trim($item));
                               } 
                            }
                        }

                        if($cco  != ''){
                            $lista = explode(",", $cco);
                            foreach ($lista as &$item) {
                               if(trim($item) != ''){
                                   $mail->addBCC(trim($item));
                               } 
                            }
                        }

                        if(count($files) > 0){

                            $conFile = new _Conexao('FILES');

                            foreach ($files as $file) {
                                $a = getFile($conFile, $file->ARQUIVO_ID);

                                if(is_object($a)){
                                    $mail->addAttachment($a->PATH);
                                }

                            }
                        }

                        //Attachments
                        //$mail->addAttachment('/var/tmp/file.tar.gz');       // Add attachments
                        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name

                        //Content
                        $mail->CharSet   = "UTF-8";
                        $mail->Encoding  = "8bit";
                        $mail->isHTML(true);
                        $mail->Subject = $assunto;
                        $mail->Body    = $mensagem;
                        $mail->AltBody = $mensagem;

                        $mail->send();
                        echo "Email de ID:".$value->ID . " enviado.". "\n";
                        $this->gravarEnvio($value->ID);

                    } catch (Exception $e) {
                        echo date("d/m/Y H:i:s")." - Erro ao enviar email de ID:".$value->ID." -> Erro:" . $mail->ErrorInfo. "\n";
                    }
                    //*/
                    /*
                    $email_servidor = trim($value->DE);
                    $para = str_replace(';',',',trim($value->EMAIL));
                    $mensagem = trim($value->CORPO);
                    $assunto = trim($value->ASSUNTO);

                    $cco = trim($value->CCO);
                    $cc  = trim($value->CC);
                    if($cc  != ''){ $cc  = "Cc: $cc\n"; }
                    if($cco != ''){ $cco = "Bcc: $cco\n"; }

                    $headers  = "MIME-Version: 1.1\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\n";
                    $headers .= "From: $email_servidor\n";
                    $headers .= "Return-Path: $email_servidor\n";
                    $headers .= "Reply-To: $email_servidor\n";
                    $headers .= $cc . $cco;
                    
                    $envio = mail($para, $assunto, $mensagem, $headers, "-f$email_servidor");
                    
                    //if($envio == 1){
                        echo "Status:".$envio ." de envio do email de ID:".$value->ID . "". "\n";
                        $this->gravarEnvio($value->ID);
                    //}else{
                    //    echo "Erro ao enviar email de ID:".$value->ID;
                    //    $this->gravarErro($value->ID);
                    //}
                    //*/
                     
                } catch (Exception $exc) {
                    echo date("d/m/Y H:i:s")." - Erro ao enviar email de ID:".$value->ID." -> Erro:".$exc->getTraceAsString(). "\n";
                }
                
            } catch (Exception $exc) {
                echo date("d/m/Y H:i:s")." - Erro ao enviar email de ID:".$value->ID." -> Erro:".$exc->getTraceAsString(). "\n";
            }   
                
        }

        try {

            $con1 = new _Conexao();

            $sql = "
                update tbemail e

                    set e.flag = 0

                where e.flag = 1
            ";  
            
            $ret2 = $con1->query($sql);

            $con1->commit();
            $con1->close();
            $con1 = null;
        } catch (Exception $ex) {}
        
        $con->commit();
        $con->close();
        $con = null;

    } catch (Exception $exc) {
            
        try {
            $con->rollback();
            $con->close();
            $con = null;
        } catch (Exception $ex) {}

        echo date("d/m/Y H:i:s").' - Erro ao executar agendamento de envio de email de ID:'.$value->ID.' ('.$exc->getTraceAsString().')';
    }
			
}
     
}

