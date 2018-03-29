<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22050DAO;
use App\Http\Controllers\Ppcp\_22050Controller;

/**
 * Gestão de Operadores de Processos
 */
class _22050
{
    /**
     * Retorna os registros de produção da laminação
     * @param type $param
     * <ul>
     *      <li><b>RETORNO</b>: Consultas a serem retornadas na chave.<br/>
     *          Ex.: _22050::listar( RETORNO => ['OPERADOR'] ), retornará os operadores
     *      </li>
     * </ul>
     * @return type
     */
    public static function listar($param = []) {
        return _22050DAO::listar((object) $param);
    }
    
    public static function autenticacao($cod_barras, $controle_operacao, $abort) {
        return _22050Controller::operadorAutenticacao($cod_barras, $controle_operacao, $abort);
    }
    
    /**
     * Valida o Operador de Processo
     * @param type $dados Exemplo de Uso:<br/>
     * $dados = [<br/>
            'COD_BARRAS'  => $request->barras,<br/>
            'OPERACAO_ID' => $request->operacao_id,<br/>
            'VALOR_EXT'   => $request->valor_ext,<br/>
            'ABORT'       => $request->abort,<br/>
            'VERIFICAR_UP'=> $request->verificar_up<br/>
        ];
     * @return type
     */
    public static function validarOperador($dados) {
        return _22050Controller::validarOperador($dados);
    }
}