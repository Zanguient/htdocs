<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\PrintZebra;
use Illuminate\Support\Facades\Response;

class PrintController extends Controller
{
	
	/**
     * Consulta Descrição das impressoras.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
	public function getPrints(Request $request)
    {

    	if ( $request->ajax() ) {
            $ret = PrintZebra::getPrints();
            
            $res = [];
            
            if(count($ret)){
                foreach ($ret as $imp){
                    $item = [
                        $imp->CODIGO,
                        $imp->DESCRICAO,
                        $imp->ID,
                        $imp->FLAG
                    ];
                    
                    array_push($res, $item);
                    
                }
            }
            
            return $res;

    	}

    }

}