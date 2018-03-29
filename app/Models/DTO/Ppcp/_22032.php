<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22032DAO;

/**
 * Gestão de Estações de Trabalho
 */
class _22032
{
   /**
     * Retorna Listagem Principal
     * @param array $param
     * <ul>
     *  <li>
     *    <b>UP</b>: Sequência com os códigos das Unidades Produtivas. <br/> Ex.: FAMILIA => [1, 2, 3, 4, ...]
     *  </li>
     *  <li>
     *    <b>PERFIL_SKU</b>: Sequência com os códigos dos SKU. <br/> Ex.: FAMILIA => [1, 2, 3, 4, ...]
     *  </li>
     *  <li>
     *    <b>STATUS</b>: Sequência com os códigos dos status. <br/> Ex.: STATUS => [1, 2, 3, 4, ...]
     *  </li>
     * </ul>
     * @return type
     */    
    public static function listar($param = []) {
        return _22032DAO::listar((object) $param);
    }
}