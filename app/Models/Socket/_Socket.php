<?php

namespace App\Models\Socket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Exception;
use WebSocket\Client;

class _Socket implements MessageComponentInterface {
    protected $clients;

    function httpPost($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {

        try{

            $this->clients->attach($conn);

            $cookies = $conn->WebSocket->request->getCookies();

            $url = "http://192.168.0.179:8091/socket";
            $retorno = $this->httpPost($url, $cookies);

            if($retorno == 1){
                
                $dados['CHANEL']  = $conn->resourceId;
                $dados['VALIDE']  = TRUE;

                $dados['MENSAGE']['TYPE']    = 'GETIDCOM';
                $dados['MENSAGE']['METODO']  = 'GETIDCOM';
                $dados['MENSAGE']['DADOS']   = [];
                $dados['MENSAGE']['REQUEST'] =  'REQUEST';

                //$conn->send(json_encode($dados));

                echo "Nova conexao ({$conn->resourceId})\n";
            }else{
                echo "Usuario nao autenticado ({$conn->resourceId})\n";
                throw new Exception('Usuario nao autenticado ({$conn->resourceId})\n');                
            }

        } catch (Exception $e) {
            throw new $e;
        }

    }

    public function onMessage(ConnectionInterface $from, $obj) {

        $item = json_decode($obj);

        $de       = $item->DE;
        $para     = $item->PARA;
        $send     = $item->MENSAGE;

        $type     = $send->TYPE;
        $dado     = $send->DADOS;
        $metod    = $send->METODO;
        $request  = $send->REQUEST;

        $dados['CHANEL']  = $from->resourceId;
        $dados['VALIDE']  = TRUE;
        
        $dados['MENSAGE']['TYPE']    = $type;
        $dados['MENSAGE']['METODO']  = $metod;
        $dados['MENSAGE']['DADOS']   = $dado;
        $dados['MENSAGE']['REQUEST'] = $request;

        if($type == 'RESPOSE'){

            $dados['TYPE']    = $metod;
            $dados['METODO']  = $type;

            $this->senMensage($from, $dados,$para);    
        }else{
            if($type == 'GETIDCOM'){
                $this->senMensage($from, $dados,$from->resourceId);    
            }else{
                if($type == 'GETROUTER'){

                    $url     = "http://localhost:8091/"+$metod;
                    $retorno = $this->httpPost($url, $request);

                    $dados['MENSAGE']['REQUEST'] = $retorno;

                    $this->senMensage($from, $dados,$para);

                }else{
                    $this->senMensage($from, $dados,$para);
                }
            }
        }

    }

    function senMensage(ConnectionInterface $from, $obj,$para){

        //print_r($obj);

        foreach ($this->clients as $client) {

            if($para == $client->resourceId){
                $client->send(json_encode($obj));
            }

        }    
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Conexao {$conn->resourceId} foi desconectada\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e) {
        echo "Ocorreu um erro: {".$e->getMessage()."}\n";

        $conn->close();
    }

}
