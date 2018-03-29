<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\StreamedResponse;

class SSE{
    
    private $old_values = [];
    private $values = [];

    public function emitEvent($callback, $return_time = 5) {
        set_time_limit ( 0 );
        
        $response = new StreamedResponse();

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->setCallback(
            function() use($callback, $return_time) {
            
                $i = 0;
                $dh = date('Y-m-d H:i:s');
                
                @ini_set('zlib.output_compression', 'Off');
                @ini_set('output_buffering', 'Off');
                @ini_set('output_handler', '');
                @apache_setenv('no-gzip', 1);	
                  
                ignore_user_abort(true);
                
                while ( true ) {
                    
                    if (is_callable($callback)) {
                        $callback();
                    }               
                    
                    $old_json_encoded = json_encode($this->old_values);
                    $new_json_encoded = json_encode($this->values);
 
                    ob_implicit_flush(true);
                    
                    if ( $old_json_encoded != $new_json_encoded ) {

                        $this->old_values = $this->values;
                        echo 'data: ' . $new_json_encoded . "\n\n";
                        echo ": heartbeat\n\n";
                        echo "\n\n";
                    }        
                    
                    echo "\n"; //<-- send this to the client
                    
                    if (connection_status()!=0){
                        die;
                    }
                    
                    ob_flush();
                    flush();
                    
                    sleep($return_time);
                }
            }
        );

        $response->send();
    }
    
    public function setValues($values) {
        $this->values = $values;
    }
}