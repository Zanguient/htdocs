<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11050DAO;
use App\Http\Controllers\Admin\_11050Controller;

/**
 * Etiquetas<br/>
 * • Métodos de leitura e gravação de modelos de etiqueta
 */
class _11050
{
   /**
     * Retorna Listagem Principal
     * @param array $param
     * Campos de entrada:
     * <ul>
     *    <li>
     *      <b>RETORNO</b> retornos da consulta<br/>
     *       Disponíveis: <b>ETIQUETA</b>.
     *    </li>
     *    <li>
     *      <b>ID</b> array de id's
     *    </li>
     *   <li>
     *     <b>FILTRO</b> string de filtragem
     *   </li>
     * </ul>
     * @return type
     */    
    public static function listar($param = []) {
        return _11050DAO::listar(obj_case($param));
    }
    
    /**
     * Retorna a string de impressão de etiqueta
     * @param integer $id Id do modelo
     * @return string String para imoressão 
     */    
    public static function etiqueta($id) {
        return _11050Controller::etiqueta($id);
    }
}