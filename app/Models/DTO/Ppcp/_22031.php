<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22031DAO;

/**
 * Gestão de Unidades Produtivas
 */
class _22031
{
   /**
     * Retorna Listagem Principal
     * @param array $param
     * <ul>
     *  <li>
     *    <b>STATUS</b>: Sequência com os códigos dos status. <br/> Ex.: STATUS => [1, 2, 3, 4, ...]
     *  </li>
     *  <li>
     *    <b>FAMILIA</b>: Sequência com os códigos das famílias de produto. <br/> Ex.: FAMILIA => [1, 2, 3, 4, ...]
     *  </li>
     * </ul>
     * @return type
     */    
    public static function listar($param = []) {
        return _22031DAO::listar((object) $param);
    }
}