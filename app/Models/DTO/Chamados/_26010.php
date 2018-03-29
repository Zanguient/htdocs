<?php

namespace App\Models\DTO\Chamados;

use App\Models\DAO\Chamados\_26010DAO;

class _26010
{
    /**
     * Retorna Chamados
     * @param array $param
     * <ul>
     *  <li>
     *    <b>STATUS_ENTRE</b>: Intervalo com os códigos dos status. <br/> Ex.: STATUS_ENTRE => [1, 99]
     *  </li>
     *  <li>
     *    <b>STATUS</b>: Sequência com os códigos dos status. <br/> Ex.: STATUS => [1, 2, 3, 4, ...]
     *  </li>
     * </ul>
     * @return type
     */
    public static function listar($param = []) {
        return _26010DAO::listar(obj_case($param));
    } 
}