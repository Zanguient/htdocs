<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {	
        $erro_msg         = $e->getMessage();
        $erro_tratado_app = $e->getCode() == 99998 || $e->getCode() == 99990 || $e->getCode() == 401;                    //erro tratado na aplicação;
        $erro_trigger     = stripos($erro_msg, 'exception 1 ..') > 0; //erro tratado no banco de dados
        $erro_tratado_bd  = $this->databaseExecetion($e);
        
        $user = str_pad( (Auth::check() ? Auth::user()->USUARIO : ''), 10) . ' | ' . str_pad(\Request::getClientIp(),13) . ' | ';
            
        if ( (env('STACKTRACE', 0) == 0) && $erro_tratado_app || !$erro_tratado_bd->STACKTRACE ) {  
            
            if ( $erro_trigger ) {
                
                $erro_msg = str_replace("\n", ' ', $erro_msg);
                $erro_msg = str_replace("\r", ' ', $erro_msg);
                $erro_msg = str_replace(array(' .'), '' , $erro_msg);
            } else 
            if ( $erro_tratado_bd->BOOL ) {
                $erro_msg = $erro_tratado_bd->TEXT;
//                
//                $erro_msg = str_replace("\n", ' ', $erro_msg);
//                $erro_msg = str_replace("\r", ' ', $erro_msg);
//                $erro_msg = str_replace(array(' .'), '' , $erro_msg);
            }
            
            if ( $e->getCode() != 99990 ) {
                Log::info( $user . $erro_msg);
            }
        }
        else {
            Log::info( $user . 'Ocorreu uma falha no sistema:');
            //Escreve o stacktrace completo, caso não seja um erro tratado
            return parent::report($e);
        } 
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
		
		return parent::render($request, $e);
    }
	
	/**
     * Convert the given exception into a Response instance.
     *
     * @param \Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $e)
    {
		$msg_erro = $e->getMessage();	//msg de erro
        
		//verifica se o debug está ativo
        $debug = env('APP_DEBUG');
        if ($debug) {
			
			if ( $e->getCode() === 99998 || $e->getCode() === 99990 ) {
				return response()->view('errors.500', ['exception' => $msg_erro], 500);
			}
			else {
				abort(500, $msg_erro);
			}
		}
        
        $dominio = getenv('HTTP_HOST');
        $url	 = "http://" . $dominio. getenv('REQUEST_URI');
 
		//Verifica se a mensagem é um alerta.
		if ( $e->getCode() === 99998 || $e->getCode() === 99990 ) {
			$text = $msg_erro;
		}
		//Verifica se a sessão expirou.
		else if ( $e->getCode() == 401 ) {
			return response()->view('errors.500', ['exception' => $msg_erro], 401);
		}
        else if($e->getCode() == 'HY000'){
            $text = $this->databaseExecetion($e)->TEXT;
        }

//        //Verifica se é uma mensangem vinda do banco de dados
//        else if($e->getCode() == 'HY000'){
//            //Verifica se houve um deadlock update no banco de dados
//            if ( stripos($msg_erro, 'General error: -913') > 0 ) {
//                $text = 'Execução interrompida. Houve uma atualização simultanea por outro usuário. Por favor, tente novamente.';
//            }
//            else{
//                $text = 'Ocorreu uma falha ao executar esta operação.';
//            }  
//            
//        }
        
		//Verifica se o item (página) não existe.
		//Para isso, é verificada a existência da palavra 'offset'.
		else if ( stripos($msg_erro, 'offset') > 0 && stripos($msg_erro, 'offset') < 20 ) {
			abort(404);
		}
		else {
            if($e->getCode() == 99997){
                $text  = $msg_erro;
            }else{
                $text  = '<div class="msg-erro-formatada">';
                $text .= '<span class="lbl">Erro:</span><span class="lbl-msg">'. $this->trataErro($e->getCode(),$msg_erro) .'</span><br>';
                $text .= '<span class="lbl">Código:</span><span class="lbl-msg">'. $e->getCode() .'</span><br>';
                $text .= '<span class="lbl">Tipo:</span><span class="lbl-msg">'. $this->trataFile($e->getFile()) .'</span><br>';
                $text .= '<span class="lbl">Linha:</span><span class="lbl-msg">'. $e->getLine() .'</span><br>';
                $text .= '<span class="lbl">Url:</span><span class="lbl-msg">'. $url .'</span><br>';
                $text .= '</div>';
            }
			//$text .= "<button class='btn btn-default ' >";
			//$text .= "<span class='glyphicon glyphicon glyphicon-envelope' gofullscreen='fundo-tela'></span> Enviar erro para o suporte</button>";
		}
		
        return response()->view('errors.500', ['exception' => $text], 500);
    }
    
    public function trataErro($Code, $Erro){
		
		return $this->getMessageCode($Code, $Erro);
		
    }
    
    public function trataFile($File){
        
        $Arquivo = $File;
        $Resposta = 'unknown';
        
        if (strripos($Arquivo,'Controllers' ) > 0)   { $Resposta = 'Arquivo de controle'		;}
        if (strripos($Arquivo,'DAO'         ) > 0)   { $Resposta = 'Arquivo de acesso aos dados';}
        if (strripos($Arquivo,'DTO'         ) > 0)   { $Resposta = 'Arquivo de objeto'			;}
        if (strripos($Arquivo,'Routes'      ) > 0)   { $Resposta = 'Arquivo de caminho'			;}
        if (strripos($Arquivo,'routes'      ) > 0)   { $Resposta = 'Arquivo de caminho'			;}
        if (strripos($Arquivo,'assets'      ) > 0)   { $Resposta = 'Arquivo de tratamento'		;}
        if (strripos($Arquivo,'Conexao'     ) > 0)   { $Resposta = 'Arquivo de conexão'			;}

        return $Resposta;
    }
    
    public function getMessageCode($code, $erro){
        
	$resposta = 'Erro não identificado';
        
        if ($code == 99999      )  {$resposta = $erro;}
        if ($code == 99997      )  {$resposta = $erro;}
        if ($code == 'HY105'    )  {$resposta = 'Um parâmetro não pode ser nulo';}
        if ($code == -303       )  {$resposta = 'Erro de SQL: Tamanho da variável maior do que o tamanho do campo';}
        if ($code == 1205       )  {$resposta = 'Erro de PHP: variável não definida';}
        if ($code == -836       )  {$resposta = 'trigger';}
        if ($code == -206       )  {$resposta = 'Erro de SQL. Coluna desconhecida.';}
        if ($code == 'HY000'    )  {$resposta = 'Erro de SQL';}
        if ($code == '-913'     )  {$resposta = 'Execução interrompida. Houve uma atualização simultanea por outro usuário. Por favor, tente novamente.';}
        
        return $resposta;
    }
	
    
    public function databaseExecetion(Exception $e) {
        
        $return = (object) [
            'TEXT'       => 'Ocorreu uma falha ao realizar esta operação. Tente novamente. Se o erro persistir, entre em contato com o suporte técnico. Ramal: 27',
            'BOOL'       => false,
            'STACKTRACE' => true
        ];
        
        
        $codigo = $e->getCode();
        $msg    = $e->getMessage();
        
        if ( $codigo == 'HY000' ) {
            
            /**
             * Banco de dados fora do ar
             */
            if ( stripos($msg, 'Unable to complete network') > 0 || stripos($msg, 'General error: -902') > 0 ) {

                $return->TEXT = 'Falha ao tentar conectar com o banco de dados.';
                $return->BOOL = true;                        
                $return->STACKTRACE = false;
            }
            /**
             * Erro de SQL mal estruturado
             */
            else
            if ( stripos($msg, 'General error: -104') > 0 ) {

                $return->TEXT = 'Erro de execução de comando.<b><br/>Entre em contato com suporte técnico. Ramal: 27.<br/>Informe o Código: -104</b>';
                $return->BOOL = true;                        
            }
            /**
             * Erro de SQL Tabela desconhecida
             */
            else
            if ( stripos($msg, 'General error: -204') > 0 ) {

                $return->TEXT = 'Erro de execução de comando.<b><br/>Entre em contato com suporte técnico. Ramal: 27.<br/>Informe o Código: -204</b>';
                $return->BOOL = true;                        
            }
            /**
             * Erro de SQL Coluna desconhecida
             */
            else
            if ( stripos($msg, 'General error: -206') > 0 ) {

                $return->TEXT = 'Erro de execução de comando.<b><br/>Entre em contato com suporte técnico. Ramal: 27.<br/>Informe o Código: -206</b>';
                $return->BOOL = true;                        
            }
            /**
             * Erro de  numeric overflow, or string truncation string right truncation
             */
            else
            if ( stripos($msg, 'General error: -303') > 0 && stripos($msg, 'conversion error from string') > 0 ) {
                
                $return->TEXT = 'Formato do valor informado está incorreto.';
                $return->BOOL = true;                        
            }
            /**
             * Erro de  numeric overflow, or string truncation string right truncation
             */
            else
            if ( stripos($msg, 'General error: -303') > 0 || stripos($msg, 'General error: -802') > 0 && stripos($msg, 'right truncation') > 0 ) {

                $esperado = '';
                $atual    = '';

                preg_match('/expected length (.*), actual(.*)/i', $msg, $esperado);
                preg_match('/, actual (.*)/i', $msg, $atual);

                $limites = '';
                if ( isset($esperado[1]) && isset($atual[1]) ) {
                    $limites =  'Quantidade permitida: ' . $esperado[1] . ',<br/>Quantidade informada: ' . $atual[1];
                }
                
                $return->TEXT = 'Limite de caracteres execido. ' . $limites;
                $return->BOOL = true;                        
            }
            /**
             * Erro de arithmetic exception, numeric overflow, or string truncation Integer divide by zero.
             */
            else
            if ( stripos($msg, 'General error: -802') > 0 && stripos($msg, 'divide by zero') > 0 ) {
                
                $return->TEXT = 'O sistema tentou realizar uma divisão por zero.';
                $return->BOOL = true;                        
            }
            /**
             * Erro de trigger
             */
            else
            if ( stripos($msg, 'General error: -836') > 0 ) {

                $matches = '';
                
                preg_match('/exception 1 ...(.*) At (.*)/i', trim($msg), $matches);

                if ( isset( $matches[1] ) ) {
                    $return->TEXT = str_replace(array(' .'), '' , $matches[1]);   
                } else
                {
                    preg_match('/exception 1 ...(.*) . . (.*)/i', $msg, $matches);

                    if ( isset( $matches[1] ) ) {
                        $return->TEXT = str_replace(array(' .'), '' , $matches[1]);    
                    } else
                    {
                        preg_match('/exception 1 ...(.*)/m', $msg, $matches);

                        if ( isset( $matches[1] ) ) {
                            $return->TEXT = str_replace(array(' .'), '' , $matches[1]);    
                        } else
                        {

                            preg_match('/exception 1 ...(.*)/s', $msg, $matches);

                            if ( isset( $matches[1] ) ) {
                                $return->TEXT = str_replace(array(' .'), '' , $matches[1]);    
                            } else
                            {
                                $return->TEXT = 'Bloqueio do sistema não identificado. Entre em contato com suporte técnico. Ramal: 27.';
                            }                            
                        }
                    }                    
                }               
                
                $return->BOOL = true;                
                $return->STACKTRACE = false;                     
            }
            /**
             * Erro de  deadlock update conflicts
             */
            else
            if ( stripos($msg, 'General error: -913') > 0 ) {

                $return->TEXT = 'Execução interrompida. Houve uma atualização simultanea por outro usuário. Por favor, tente novamente.';
                $return->BOOL = true;         
                $return->STACKTRACE = false;                      
            }
        }
        
        return $return;
    }
}
