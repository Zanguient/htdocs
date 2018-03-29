<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25700DAO;
use Illuminate\Support\Facades\Response;

class _25700 {
    
    public static function coletado($filtro) {
        return _25700DAO::coletado($filtro);
    }
    
    public static function prodgp($filtro) {
        return _25700DAO::prodgp($filtro);
    }
    
    public static function pendente($filtro) {
        return _25700DAO::pendente($filtro);
    }
    
    /**
     * Função para gravar plano de ação
     * @access public
     * @param string $filtro
     * @return array
     */
    public static function store($filtro) {
        return _25700DAO::store($filtro);
    }
    
    /**
     * Função para alterar plano de a��o
     * @access public
     * @param string $filtro
     * @return array
     */
    public static function alterar($filtro) {
        return _25700DAO::alterar($filtro);
    }
    
    /**
     * Lista de registros filtrador por perfil, vinculo e ccusto  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function getListaPA($dados) {
        return _25700DAO::getListaPA($dados);
    }
    
    /**
     * Lista um plano de ação 
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function showitem($dados) {
        return _25700DAO::showitem($dados);
    }
    
    /**
     * Excluir um plano de ação 
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function excluir($dados) {
        return _25700DAO::excluir($dados);
    }

}
