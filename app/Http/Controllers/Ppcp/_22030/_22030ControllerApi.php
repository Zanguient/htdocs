<?php

namespace app\Http\Controllers\Ppcp\_22030;

use App\Http\Controllers\Ppcp\_22030\_22030Controller as Ctrl;
use App\Models\DTO\Ppcp\_22030;


/**
 * Controller do objeto _22030 - Geracao de Remessas de Bojo
 */
class _22030ControllerApi extends Ctrl {
      
    
    public function getGp() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22030 = new _22030($this->con());

            $consumos = $dto_22030->selectGp($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getUp() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22030 = new _22030($this->con());

            $consumos = $dto_22030->selectUp($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getEstacao() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22030 = new _22030($this->con());

            $consumos = $dto_22030->selectEstacao($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getGpAutenticacao() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22030 = new _22030($this->con());


            validator($request, [
                'BARRAS' => ['Código de Barras do Grupo de Produção','required'],
            ],true);              
            
            $gps = $dto_22030->selectGp((object)['GP_BARRAS'=>$request->BARRAS]);
            
            if ( !isset($gps[0]) ) {
                log_erro('Nenhum grupo de produção localizado para o código de barras informado.');
            }
            $this->con()->commit();
                        
            return (array) $gps[0];
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
}