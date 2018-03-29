<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\Historico;

class HistoricoController extends Controller
{

    public function GetHistorico(Request $request)
    {
        $tabela = $request->tabela;
        $id = $request->id;

        $dados = Historico::GetHistorico($tabela,$id);

        if ($dados['resposta'][0] === 'erro') {

            $Res = '<div class="content">';
            $Res .= '<div class="title">Erro <div>:(</div></div>';
            $Res .= '<div class="msg">Contacte o administrador do sistema.</div>';
            $Res .= '<div class="msg-erro">'.$dados['resposta'][1].'</div>';
            $Res .= '</div>';

        }else{

            $Res  = '<section class="tabela">';
            $Res .= '<table class="table table-striped table-bordered table-hover">';
            $Res .= '<thead>';
            $Res .= '<tr>';
            $Res .= '<th>Data/Hora</th>';
            $Res .= '<th>Usu&aacute;rio</th>';
            $Res .= '<th>Hist&oacute;rico</th>';
            $Res .= '<th>End. IP</th>';
            $Res .= '<th>Vers&atilde;o GC</th>';
            $Res .= '</tr>';
            $Res .= '</thead>';
            $Res .= '<tbody>';

            foreach ($dados['retorno'] as $iten){

                $Res .= '<tr>';
                $Res .= '<td>'.date('H:i:s d/m/Y', strtotime($iten->DATAHORA)).'</td>';
                $Res .= '<td>'.$iten->USUARIO.'</td>';
                $Res .= '<td class="limit-width">'.$iten->HISTORICO.'</td>';
                $Res .= '<td>'.$iten->IP.'</td>';
                $Res .= '<td>'.$iten->VERSAO.'</td>';
                $Res .= '</tr>';
            }

            $Res .= '</tbody>';
            $Res .= '</table>';

            $Res .= '</section>';
        }

        return $Res;

    }
    
    public function getApiHistorico (Request $request) {
        
        $request = obj_case($request->all());
        
                    
        validator($request, [
            'TABELA'    => ['Tabela'      ,'required'],
            'TABELA_ID' => ['Id da Tabela','required'],
        ],true);   
        
        log_info('Consultando Histórico da Tabela: ' . $request->TABELA . ' Id: ' . $request->TABELA_ID);
        
        return Historico::getHistorico2($request);
    }

    public static function setHistorico($tabela, $id, $descricao) {
        return Historico::setHistorico($tabela, $id, $descricao);
    }




}
