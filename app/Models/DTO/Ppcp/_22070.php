<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22070DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _22070
{
    /**
     * Retorna os registros de produção da laminação
     * @param type $param
     * <ul>
     *      <li><b>RETORNO</b>: Consultas a serem retornadas na chave.<br/>
     *          Ex.: _22070::listar( RETORNO => [PRODUCAO] ), retornará a producao
     *      </li>
     * </ul>
     * @return type
     */
    public static function gravar($param = []) {
        return _22070DAO::gravar(obj_case($param));
    }
}
