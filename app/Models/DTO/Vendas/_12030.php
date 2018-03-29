<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12030DAO;

/**
 * Empresa
 */
class _12030
{
   
	public static function index($dados) {
		return _12030DAO::index($dados);
	}

    public static function index2($dados) {
		return _12030DAO::index2($dados);
	}
    
    public static function gravar($dados) {
		return _12030DAO::gravar($dados);
	}
    
    public static function show($dados) {
		return _12030DAO::show($dados);
	}
    
    public static function delete($dados) {
		return _12030DAO::delete($dados);
	}

}