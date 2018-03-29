<?php

namespace app\Http\Controllers\Ppcp\_22140;

use App\Http\Controllers\Ppcp\_22140\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22140;
use App\Models\Socket\Socket;

/**
 * Controller do objeto _22140 - Geracao de Remessas de Bojo
 */
class ControllerFind extends Ctrl {

    public function find(Request $request) {
        
//        $sc = new Socket($request);
//        
//        $sc->sendProgress(0, 10);
//        
//        for ( $i = 1; $i <= 10 ; $i++ ) {
//            sleep(1);
//            $sc->sendProgress($i);
//        }
//
//        $sc->sendDados('Retorno concluído!','progressoConsulta');
        
        
        try {
            
            parent::setRequest(json_decode(json_encode((object) $request->all()), false));
            
            $this->valid();

            parent::setCon();            
            
            // ************* Captura os Dias ***************
            $dias = _22140::selectDiasPeriodo(parent::getRequest(),parent::getCon());
            
            if ( empty($dias) ) {
                log_erro('Não há dias para o período informado.');
            }
            
            // ************* Captura as estações ***************
            $estacoes = _22140::selectEstacoesPorData(parent::getRequest(),parent::getCon());
            
            if ( empty($estacoes) ) {
                log_erro('Não calendário disponível para estações no período informado.');
            }
            
//            // ***************** Armazena os dados para o progress
//            $count_datas_estacaos = count($dias) + count($estacoes);
//            $sc->sendProgress(0, $count_datas_estacaos);
//            $progress = 1;
            
            
            // **************** Captura os minutos ****************                
            foreach ( $dias as $dia ) {    
                $dia->MINUTOS = _22140::selectMinutosDia([], parent::getCon());
                
                foreach ( $dia->MINUTOS as $minuto ) {
                    
                    $time_escala = strtotime($dia->DIA . ' ' . $minuto->HORA);
                    $time_talao = strtotime($dia->DIA . ' ' . $minuto->HORA);
                    if ( $time_escala == $time_talao ) {
                        
                    }
                }
            }
            
            
            // **************** Captura os talões ****************                
            foreach ( $estacoes as $estacao ) {    

//                $estacao->DIAS   = $dias;
                
                $estacao->TALOES = 
                    _22140::selectTaloesPorData([
                        'DATAHORA_1' => date('Y-m-d',strtotime(parent::getRequest()->DATA_1)) . ' 00:00:00',
                        'DATAHORA_2' => date('Y-m-d',strtotime(parent::getRequest()->DATA_2)) . ' 23:59:59',
                        'GP_ID'      => $estacao->GP_ID,
                        'UP_ID'      => $estacao->UP_ID,
                        'ESTACAO'    => $estacao->ESTACAO
                    ], parent::getCon())
                ;
                
//                foreach ( $estacao->DIAS as $dia ) {
//                    foreach ( $dia->MINUTOS as $minuto ) {
//                        
//                        $time_escala = strtotime($dia->DIA . ' ' . $minuto->HORA);
//                        
//                        $minuto->TALOES = 
//                            _22140::selectTaloesPorDataHora([
//                                'DATAHORA' => date('Y-m-d H:i:s',$time_escala),
//                                'GP_ID'      => $estacao->GP_ID,
//                                'UP_ID'      => $estacao->UP_ID,
//                                'ESTACAO'    => $estacao->ESTACAO
//                            ], parent::getCon())
//                        ;
//                    }
//                }

            }
            
            parent::getCon()->commit();
            
            $ret = [
                'DIAS'     => $dias,
                'ESTACOES' => $estacoes
            ];
            
//            $sc->sendDados('Retorno concluído!','progressoConsulta');
            
            return $ret;
            
        }
        catch (Exception $e) {
			parent::getCon()->rollback();
			throw $e;
		}
    }

    private function valid() {
        _11010::permissaoMenu($this->menu);
        
        validator((array) parent::getRequest(), [
            'DATA_1' => ['Data Inicial','required|date'],
            'DATA_2' => ['Data Final'  ,'required|date']
        ],true);
    }
}