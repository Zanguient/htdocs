<?php

namespace App\Models\Socket;

use Exception;
use WebSocket\Client;

class Socket{
	
	public $socket_token;
    public $request;
    public $Client;
    public $dados;
    public $progress_maximo = 0;


	public function __construct($request){
        set_time_limit (0);

        $cookie = '';
        $origin = 'INTERNO';
        $token = 0;
        $this->request = $request;

        if(is_array($request) == true){
            $this->dados   = [];
        }else{
            $this->dados   = $request->all();
            $headers       = $this->request->headers->all();
            
            if(array_key_exists('socket-token', $request->header())){
                $token = $request->header()['socket-token'][0];
            }

            if(array_key_exists('cookie', $headers)){
                $cookie = $headers['cookie'][0];
            }
		}

        $this->socket_token = $token;

        $temp_headers['origin'] = $origin;
        $temp_headers['cookie'] = $cookie;

        //$this->client = new Client("ws://127.0.0.1:81",['headers' => $temp_headers]);
        //log_info($this->client);
        $this->client = new Client("ws://192.168.0.101:81",['headers' => $temp_headers]);
	}

    /**
     * Barra de progresso via socket
     * @param type $valor_atual Posição atual do processo
     * @param type $valor_maximo Número máximo de posições do progress<br/><br/>
     * <b><i>Modo de utilizar:</i></b><br/>
     * <ul>
     * <li>Para Inicializar:<br/>
     * $socket->sendProcess(0,100); O progress terá 100 posições
     * </li>
     * <li>
     * Para Incrementar:<br/>
     * $socket->sendProcess(10); O progress estará na posição 10 dos 100 da inicialização
     * </li>
     * </ul>
     */
    public function sendProgress($valor_atual, $valor_maximo = 0){
        
        $retorno = 0;
        
        if ( $valor_atual == 0 && $valor_maximo > 0 ) {
            $this->progress_maximo = $valor_maximo;
        }
        else 
        if ( $valor_atual > 0 && $valor_maximo == 0 ) {
            $retorno = ($valor_atual / $this->progress_maximo) * 280;
        } else {
            log_erro('O método de progresso retornou um erro. Verifique se o progresso foi inicializado ou incrementado.');
        }        

        if ( $valor_atual > 0 && $this->progress_maximo > 0 ) {
            
            $dados['DE']    = $this->socket_token;
            $dados['PARA']  = $this->socket_token;

            $dados['MENSAGE']['TYPE']     = 'RESPONSE';
            $dados['MENSAGE']['METODO']   = 'PROGRESS';
            $dados['MENSAGE']['DADOS']    = 145+$retorno;
            $dados['MENSAGE']['REQUEST']  = $this->dados;

            $this->client->send(json_encode($dados));
        }
    }

    public function sendDados($itens,$metodo){

        $dados['DE']    = $this->socket_token;
        $dados['PARA']  = $this->socket_token;
        
        $dados['MENSAGE']['TYPE']     = 'RESPONSE';
        $dados['MENSAGE']['METODO']   = $metodo;
        $dados['MENSAGE']['DADOS']    = $itens;
        $dados['MENSAGE']['REQUEST']  = $this->dados;

        $this->client->send(json_encode($dados));
    }

    public function sendUserNotfi($itens,$metodo){

        $dados['DE']    = $this->socket_token;
        $dados['PARA']  = $this->socket_token;
        
        $dados['MENSAGE']['TYPE']     = 'sendUserNotfi';
        $dados['MENSAGE']['METODO']   = $metodo;
        $dados['MENSAGE']['DADOS']    = $itens;
        $dados['MENSAGE']['REQUEST']  = $this->dados;

        $this->client->send(json_encode($dados));
    }

    public function getTagId($id)
    {
        $str  = date(DATE_ATOM, time());
        $str .= $id;
        $str .= rand();

        $str = str_replace('-', '', $str);
        $str = str_replace(':', '', $str);
        
        return $str;
    }
    
    /**
     * Envia notificação
     * @param any $msg Dados a serem entregues
     * @param int $tipo Tipo de mensagem 1 = Mensagem; 2 = Erro; 3 = Evento; 4 = Função
     * @param int $user_id Id do usuário
     * @param string $metodo Nome do método que o socket irá acessar
     * @return array
     */
    public function sendNotification($msg,$tipo,$user_id,$metodo){

        try {

            $dados['DE']    = 0;
            $dados['PARA']  = $user_id;

            $user = Socket::getTagId($user_id);

            $msg['MENSAGE_SOCKET_ID']     = $user; 
            
            $dados['MENSAGE']['TYPE']     = 'sendNotification';
            $dados['MENSAGE']['MSG_TYPE'] = $tipo;
            $dados['MENSAGE']['METODO']   = $metodo;
            $dados['MENSAGE']['DADOS']    = $msg;
            $dados['MENSAGE']['REQUEST']  = $this->dados;

            $this->client->send(json_encode($dados));

        } catch (Exception $e) {
            log_info($e->getMessage());
        }

        return ['ok'];
    }

    public function sendBroadcastMenu($itens,$metodo,$menu){

        $dados['DE']    = $this->socket_token;
        $dados['PARA']  = $menu;
        
        $dados['MENSAGE']['TYPE']     = 'BROADCAST_MENU';
        $dados['MENSAGE']['METODO']   = $metodo;
        $dados['MENSAGE']['DADOS']    = $itens;
        $dados['MENSAGE']['REQUEST']  = $this->dados;

        $this->client->send(json_encode($dados));
    }

    public function close(){
        unset($this->client);
        $this->client = null;
    }
}