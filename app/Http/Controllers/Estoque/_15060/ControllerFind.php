<?php

namespace app\Http\Controllers\Estoque\_15060;

use App\Http\Controllers\Estoque\_15060\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Estoque\_15060;

/**
 * Controller do objeto _15060 - Geracao de Remessas de Bojo
 */
class ControllerFind extends Ctrl {

    public function find(Request $request) {
        try {
            
            $this->valid();
            
            set_time_limit ( 0 );
            
            parent::setRequest(json_decode(json_encode((object) $request->all()), false));

            parent::setCon();
            
            $estoque_localizacoes = _15060::selectEstoqueLocalizacao(parent::getRequest(),parent::getCon());
            
            $this->concatEstoqueGrade($estoque_localizacoes);
            $this->concatEstoqueTransacao($estoque_localizacoes);
            
            parent::getCon()->commit();
            
            return Response::json($estoque_localizacoes);
        }
        catch (Exception $e) {
			parent::getCon()->rollback();
			throw $e;
		}
    }

    private function valid() {
        _11010::permissaoMenu($this->menu);
    }
    
    private function concatEstoqueGrade(&$estoque_localizacoes = []) {
        
        foreach ( $estoque_localizacoes as $localizacao ) {
            
            $args = (object) array_merge((array) parent::getRequest(), (array) $localizacao);
            
            $localizacao->ESTOQUE_GRADES = _15060::selectEstoqueGrade($args,parent::getCon());
        }
    }
    
    private function concatEstoqueTransacao(&$estoque_localizacoes = []) {
        
        foreach ( $estoque_localizacoes as $localizacao ) {
            
            $args = (object) array_merge((array) parent::getRequest(), (array) $localizacao);
            
            $localizacao->ESTOQUE_TRANSACOES = _15060::selectEstoqueTransacao($args,parent::getCon());
        }
    }
    
}